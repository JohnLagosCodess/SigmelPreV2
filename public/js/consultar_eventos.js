$(document).ready(function () {
    var idRol = $("#id_rol").val();            
    $('#form_consultar_evento').submit(function(e){
        $(".dt-buttons").addClass('d-none');
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
                    // console.log(data);
                    if (data.parametro == "sin_datos") {
                        /* Mostrar contenedor mensaje de que no hay información */
                        $('.resultado_validacion').removeClass('d-none');
                        $('.resultado_validacion').addClass('alert-danger');
                        $('#llenar_mensaje_validacion').append(data.mensaje);
                        setTimeout(() => {
                            $('.resultado_validacion').addClass('d-none');
                            $('.resultado_validacion').removeClass('alert-danger');
                            $('#llenar_mensaje_validacion').empty();
                        }, 6000);

                        /* Ocultar contenedor informacion del afiliado */
                        $('.contenedor_info_afiliado').addClass('d-none');
                        $('#span_nombre_afiliado').empty();
                        $('#span_nro_identificacion').empty();
                        $('#span_tipo_afiliado').empty();

                        /* Ocultar contenedor información del evento */
                        $('.contenedor_info_evento').addClass('d-none');
                        $('#num_registros').empty();
                        $('#body_listado_eventos').empty();

                    } else {
                        /* Ocultar contenedor mensaje de que no hay información */
                        $('.resultado_validacion').addClass('d-none');
                        $('.contenendor_mensaje_no_datos').addClass('d-none');
                        $('.mensaje_no_datos').empty();

                        /* Habilitar contenedor informacion del afiliado */
                        $('#span_nombre_afiliado').empty();
                        $('#span_nro_identificacion').empty();
                        $('#span_tipo_afiliado').empty();
                        $('.contenedor_info_afiliado').removeClass('d-none');
                        $('#span_nombre_afiliado').append(data[0]['Nombre_afiliado']);
                        $('#span_nro_identificacion').append(data[0]['Nro_identificacion']);
                        $('#span_tipo_afiliado').append(data[0]['Tipo_afiliado']);

                        /* Habilitar contenedor información del evento */
                        $('#num_registros').empty();
                        $('.contenedor_info_evento').removeClass('d-none');
                        $('#num_registros').append(data.length);

                        var IrEvento = '';
                        var Ver = '';
                        var agregar_nuevo_servicio = '';
                        var agregar_nuevo_proceso = '';

                        for (let i = 0; i < data.length; i++) {
                            // Validación para mostrar el formulario de edición correspondiente al ID de evento.
                            if (data[i]['ID_evento'] != '') {
                                IrEvento = '<span class="d-none">'+data[i]["ID_evento"]+'</span><form id="form_editar_evento_'+data[i]["ID_evento"]+'" action="" method="POST">'+
                                '<input type="hidden" name="_token" value="'+token+'">'+
                                '<input class="btn text-info btn-sm" id="edit_evento_'+data[i]["ID_evento"]+'" type="submit" style="font-weight: bold;" value="'+data[i]["ID_evento"]+'">'+
                                '<input type="hidden" name="badera_buscador_evento" id="badera_buscador_evento" value="desdebuscador">'+
                                '<input type="hidden" name="newIdEvento" value="'+data[i]["ID_evento"]+'">'+
                                '</form>';
                                data[i]['consulta_evento'] = IrEvento;
                            }     
                            
                            if (data[i]['ID_evento'] != '' &&  data[i]['Id_proceso'] == 1) {
                                Ver = '<form id="form_modulo_principal_origen'+data[i]["ID_evento"]+'_'+data[i]["Id_Asignacion"]+'" action="" method="POST">'+
                                '<input type="hidden" name="_token" value="'+token+'">'+
                                '<label for="evento_modulo_origen_'+data[i]["ID_evento"]+'_'+data[i]["Id_Asignacion"]+'"><i class="far fa-eye text-info"></i></label>'+
                                '<input class="btn btn-icon-only text-info btn-sm" id="evento_modulo_origen_'+data[i]["ID_evento"]+'_'+data[i]["Id_Asignacion"]+'" type="submit" style="font-weight: bold;" value="'+data[i]["ID_evento"]+'">'+
                                '<input type="hidden" name="badera_modulo_principal_origen" id="badera_modulo_principal_origen" value="desdebus_mod_origen">'+
                                '<input type="hidden" name="newIdEvento" value="'+data[i]["ID_evento"]+'">'+
                                '<input type="hidden" name="newIdProceso" value="'+data[i]["Id_proceso"]+'">'+
                                '<input type="hidden" name="newIdServicio" value="'+data[i]["Id_Servicio"]+'">'+
                                '<input type="hidden" name="newIdAsignacion" value="'+data[i]["Id_Asignacion"]+'">'+
                                '</form>';
                                data[i]['Ver'] = Ver;
                            } 

                            if (data[i]['ID_evento'] != '' &&  data[i]['Id_proceso'] == 2) {
                                Ver = '<form id="form_modulo_principal_pcl_'+data[i]["ID_evento"]+'_'+data[i]["Id_Asignacion"]+'" action="" method="POST">'+
                                '<input type="hidden" name="_token" value="'+token+'">'+
                                '<label for="evento_modulo_pcl_'+data[i]["ID_evento"]+'_'+data[i]["Id_Asignacion"]+'"><i class="far fa-eye text-info"></i></label>'+
                                '<input class="btn btn-icon-only text-info btn-sm" id="evento_modulo_pcl_'+data[i]["ID_evento"]+'_'+data[i]["Id_Asignacion"]+'" type="submit" style="font-weight: bold;" value="'+data[i]["ID_evento"]+'">'+
                                '<input type="hidden" name="badera_modulo_principal_pcl" id="badera_modulo_principal_pcl" value="desdebus_mod_pcl">'+
                                '<input type="hidden" name="newIdEvento" value="'+data[i]["ID_evento"]+'">'+
                                '<input type="hidden" name="newIdProceso" value="'+data[i]["Id_proceso"]+'">'+
                                '<input type="hidden" name="newIdServicio" value="'+data[i]["Id_Servicio"]+'">'+
                                '<input type="hidden" name="newIdAsignacion" value="'+data[i]["Id_Asignacion"]+'">'+
                                '</form>';
                                data[i]['Ver'] = Ver;
                            } 

                            if (data[i]['ID_evento'] != '' &&  data[i]['Id_proceso'] == 3) {
                                Ver = '<form id="form_modulo_principal_juntas_'+data[i]["ID_evento"]+'_'+data[i]["Id_Asignacion"]+'" action="" method="POST">'+
                                '<input type="hidden" name="_token" value="'+token+'">'+
                                '<label for="evento_modulo_juntas_'+data[i]["ID_evento"]+'_'+data[i]["Id_Asignacion"]+'"><i class="far fa-eye text-info"></i></label>'+
                                '<input class="btn btn-icon-only text-info btn-sm" id="evento_modulo_juntas_'+data[i]["ID_evento"]+'_'+data[i]["Id_Asignacion"]+'" type="submit" style="font-weight: bold;" value="'+data[i]["ID_evento"]+'">'+
                                '<input type="hidden" name="badera_modulo_principal_juntas" id="badera_modulo_principal_juntas" value="desdebus_mod_juntas">'+
                                '<input type="hidden" name="newIdEvento" value="'+data[i]["ID_evento"]+'">'+
                                '<input type="hidden" name="newIdProceso" value="'+data[i]["Id_proceso"]+'">'+
                                '<input type="hidden" name="newIdServicio" value="'+data[i]["Id_Servicio"]+'">'+
                                '<input type="hidden" name="newIdAsignacion" value="'+data[i]["Id_Asignacion"]+'">'+
                                '</form>';
                                data[i]['Ver'] = Ver;
                            } 

                            if (data[i]['ID_evento'] != '' &&  data[i]['Id_proceso'] == 4) {
                                Ver = '<form id="form_modulo_principal_noti_'+data[i]["ID_evento"]+'_'+data[i]["Id_Asignacion"]+'" action="" method="POST">'+
                                '<input type="hidden" name="_token" value="'+token+'">'+
                                '<label for="evento_modulo_noti_'+data[i]["ID_evento"]+'_'+data[i]["Id_Asignacion"]+'"><i class="far fa-eye text-info"></i></label>'+
                                '<input class="btn btn-icon-only text-info btn-sm" id="evento_modulo_noti_'+data[i]["ID_evento"]+'_'+data[i]["Id_Asignacion"]+'" type="submit" style="font-weight: bold;" value="'+data[i]["ID_evento"]+'">'+
                                '<input type="hidden" name="badera_modulo_principal_noti" id="badera_modulo_principal_noti" value="desdebus_mod_noti">'+
                                '<input type="hidden" name="newIdEvento" value="'+data[i]["ID_evento"]+'">'+
                                '<input type="hidden" name="newIdProceso" value="'+data[i]["Id_proceso"]+'">'+
                                '<input type="hidden" name="newIdServicio" value="'+data[i]["Id_Servicio"]+'">'+
                                '<input type="hidden" name="newIdAsignacion" value="'+data[i]["Id_Asignacion"]+'">'+
                                '</form>';
                                data[i]['Ver'] = Ver;
                            }
                            
                            // Ver = '<a href="javascript:void(0);"><i class="far fa-eye text-info"></i></a>';
                            // data[i]['Ver'] = Ver;

                            // Validación para crear el modal del formulario de nuevo servicio
                            if(data[i]['Nombre_servicio'] == 'Determinación del Origen (DTO) ATEL' || data[i]['Nombre_servicio'] == 'Adición DX' || data[i]['Nombre_servicio'] == 'Calificación técnica' || data[i]['Nombre_servicio'] == 'Recalificación' || data[i]['Nombre_servicio'] == 'Revisión pensión'){
                                if(data[i]['Visible_Nuevo_Servicio'] == 'Si' && data[i]['Nombre_estado'] == 'Gestionado'){
                                    agregar_nuevo_servicio = '<a href="javascript:void(0);" data-toggle="modal" data-target="#modalNuevoServicio_'+data[i]["ID_evento"]+'" id="btn_nuevo_servicio_'+data[i]["ID_evento"]+'" title="Agregar Nuevo Servicio"\
                                     data-id_evento_nuevo_servicio="'+data[i]["ID_evento"]+'" data-id_proceso_nuevo_servicio="'+data[i]["Id_proceso"]+'" data-nombre_proceso_nuevo_servicio="'+data[i]["Nombre_proceso"]+'" \
                                     data-id_servicio_nuevo_servicio="'+data[i]["Id_Servicio"]+'" data-id_asignacion_nuevo_servicio="'+data[i]["Id_Asignacion"]+'" data-id_cliente="'+data[i]["Id_cliente"]+'"><i class="fa fa-puzzle-piece text-info"></i></a>';
                                    
                                    data[i]['agregar_nuevo_servicio'] = agregar_nuevo_servicio;

                                }else{
                                    data[i]['agregar_nuevo_servicio'] = ""; 
                                }
                            }else{
                                data[i]['agregar_nuevo_servicio'] = ""; 
                            }

                            // Validación para crear el modal del formulario de nuevo proceso
                            /*if(data[i]['Visible_Nuevo_Proceso'] == 'Si'){*/
                                agregar_nuevo_proceso = '<a href="javascript:void(0);" data-toggle="modal" data-target="#modalNuevoProceso_'+data[i]["ID_evento"]+'" id="btn_nuevo_proceso_'+data[i]["ID_evento"]+'" title="Agregar Nuevo Proceso"\
                                data-id_evento_nuevo_proceso="'+data[i]["ID_evento"]+'" data-id_proceso_nuevo_proceso="'+data[i]["Id_proceso"]+'"\
                                data-id_servicio_nuevo_proceso="'+data[i]["Id_Servicio"]+'" data-id_asignacion_nuevo_proceso="'+data[i]["Id_Asignacion"]+'" data-id_cliente="'+data[i]["Id_cliente"]+'"><i class="far fa-clone text-info"></i></a>';
                                data[i]['agregar_nuevo_proceso'] = agregar_nuevo_proceso; 
                            /*}else{*/
                                //data[i]['agregar_nuevo_proceso'] = ""; 
                            /*}*/
                        }                        

                        $.each(data, function(index, value){
                            llenar_informacion_evento(data, index, value);
                        });

                        setTimeout(() => {
                            var botonBuscar = $('#contenedorTable').parents();
                            var contenedorBotonBuscar = botonBuscar[0].childNodes[5].childNodes[1].childNodes[1].childNodes[0].classList[0];
                            $('.'+contenedorBotonBuscar).addClass('d-none');
                        }, 100);

                        // Desactivar los elementos de nuevo proceso y nuevo servicio si el id rol del usuario es 7 = Consulta
                        
                        if (idRol == 7) {
                            $("a[id^='btn_nuevo_servicio_']").prop('disabled', true);                                   
                            $("a[id^='btn_nuevo_proceso_']").prop('disabled', true);                            
                        } 
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
            }, 6500);
        }
        
    });  

    /* LLENADO DE DATOS Y PINTADO DEL DATATABLE */
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
            dom: 'Bfrtip',
            /* buttons: [
                'excel',                
            ], */            
            buttons:{
                dom:{
                    buttons:{
                        className: 'btn'
                    }
                },
                buttons:[
                    {
                        extend:"excel",
                        title: response[0]['Nombre_afiliado']+" "+response[0]['Nro_identificacion'],
                        text:'Exportar datos',
                        className: 'btn btn-success',
                        "excelStyles": [                      // Add an excelStyles definition
                                                     
                        ],
                        exportOptions: {
                            columns: [ 0,1,2,3,4,5,6,7,8,9,10,11,12,13,14]
                        }
                    }
                ]
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
                {"data":"Accion"},
                {"data":"F_accion"},
                {"data":"F_dictamen"},
                {"data":"F_notificacion"},
                {"data":"Nombre_profesional"},
                {
                    data: null,
                    render: function (data, type, row) {
                        return data.Ver + '  ' + data.agregar_nuevo_servicio + '  ' + data.agregar_nuevo_proceso;
                    }
                },
                
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

    /* GENERAR DESCARGA DE DATOS EN EXCEL */
    $('#btn_expor_datos').click(function () {
        // var infobtnExcel = $(this).parents();
        // var selectorbtnExcel = infobtnExcel[4].childNodes[13].childNodes[1].childNodes[5].childNodes[1].childNodes[1].childNodes[0].childNodes[0].classList[0];
        // console.log(selectorbtnExcel);
        // $('.'+selectorbtnExcel).click();
        //console.log(selectorbtnExcel);

        $('.dt-button').click();
    });
    
    /* Asignar ruta del formulario de edicion de evento antes de dar clic en el botón para ir al Evento */
    $(document).on('mouseover',"input[id^='edit_evento_']", function(){
        let url_editar_evento = $('#action_evento_consultar').val();
        $("form[id^='form_editar_evento_']").attr("action", url_editar_evento);    
    });

    $(document).on('mouseover',"label[for^='evento_modulo_origen_']", function(){
        let url_editar_evento = $('#action_modulo_principal_origen').val();
        $("form[id^='form_modulo_principal_origen']").attr("action", url_editar_evento);    
    });

    $(document).on('mouseover',"label[for^='evento_modulo_pcl_']", function(){
        let url_editar_evento = $('#action_modulo_principal_pcl').val();
        $("form[id^='form_modulo_principal_pcl_']").attr("action", url_editar_evento);    
    });

    $(document).on('mouseover',"label[for^='evento_modulo_juntas_']", function(){
        let url_editar_evento = $('#action_modulo_principal_juntas').val();
        $("form[id^='form_modulo_principal_juntas_']").attr("action", url_editar_evento);    
    });

    $(document).on('mouseover',"label[for^='evento_modulo_noti_']", function(){
        let url_editar_evento = $('#action_modulo_principal_noti').val();
        $("form[id^='form_modulo_principal_noti_']").attr("action", url_editar_evento);    
    });

    var dato_cargar = "";
    /* CREACIÓN Y AGREGACIÓN DEL MODAL NUEVO SERVICIO AL CONTENEDOR DE RENDERIZAMIENTO */
    $(document).on('mouseover', "a[id^='btn_nuevo_servicio_']", function(){
        dato_cargar = "Nuevo servicio";
        var id_evento_nuevo_servicio =  $(this).data("id_evento_nuevo_servicio"); //ID EVENTO
        var id_proceso_nuevo_servicio =  $(this).data("id_proceso_nuevo_servicio"); // ID PROCESO ACTUAL
        var nombre_proceso_nuevo_servicio =  $(this).data("nombre_proceso_nuevo_servicio"); // NOMBRE PROCESO ACTUAL
        var id_servicio_nuevo_servicio =  $(this).data("id_servicio_nuevo_servicio"); //ID SERVICIO ACTUAL
        var id_asignacion_nuevo_servicio =  $(this).data("id_asignacion_nuevo_servicio"); // ID TUPLA DE SERVICIO (PARA COLOCAR ESTADO DE VISIBLE EN NO)
        var id_cliente = $(this).data("id_cliente"); // ID DEL CLIENTE
        var fecha_de_hoy = $("#fecha_de_hoy").val();

        $string_html_modal_nuevo_servicio = '\
            <div class="modal fade" id="modalNuevoServicio_'+id_evento_nuevo_servicio+'" tabindex="-1" aria-hidden="true">\
                <div class="modal-dialog modal-lg">\
                    <div class="modal-content">\
                        <div class="modal-header bg-info">\
                            <h4 class="modal-title"><i class="fa fa-puzzle-piece"></i> Nuevo servicio para el evento: '+id_evento_nuevo_servicio+'</h4>\
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
                                <span aria-hidden="true">&times;</span>\
                            </button>\
                        </div>\
                        <form id="form_nuevo_servicio_evento_'+id_evento_nuevo_servicio+'" method="POST">\
                            <div class="modal-body">\
                                <p>Los campos marcados con <span style="color:red;">(*)</span> son de obligatorio diligenciamiento para guardar la información.</p>\
                                <input type="hidden" class="form-control" name="id_clientes" id="id_clientes_'+id_cliente+'" value="'+id_cliente+'">\
                                <div class="row">\
                                    <div class="col-12">\
                                        <div class="form-group row">\
                                            <label for="" class="col-sm-3 col-form-label">Fecha de Radicación <span style="color:red;">(*)</span></label>\
                                            <div class="col-sm-9">\
                                                <input type="date" class="form-control" name="nueva_fecha_radicacion" id="nueva_fecha_radicacion_'+id_evento_nuevo_servicio+'" max="'+fecha_de_hoy+'" required>\
                                            </div>\
                                        </div>\
                                        <div class="form-group row">\
                                            <label for="" class="col-sm-3 col-form-label">Proceso</label>\
                                            <div class="col-sm-9">\
                                                <input type="hidden" class="form-control" name="id_proceso_actual" id="id_proceso_actual_'+id_evento_nuevo_servicio+'" value="'+id_proceso_nuevo_servicio+'">\
                                                <input type="text" readonly class="form-control" name="nuevo_proceso" id="nuevo_proceso_'+id_evento_nuevo_servicio+'" value="'+nombre_proceso_nuevo_servicio+'">\
                                            </div>\
                                        </div>\
                                        <div class="form-group row">\
                                            <label for="" class="col-sm-3 col-form-label">Servicio <span style="color:red;">(*)</span></label>\
                                            <div class="col-sm-9">\
                                                <input type="hidden" class="form-control" name="id_servicio_actual" id="id_servicio_actual_'+id_evento_nuevo_servicio+'" value="'+id_servicio_nuevo_servicio+'">\
                                                <select class="nuevo_servicio_'+id_evento_nuevo_servicio+' custom-select" name="nuevo_servicio" id="nuevo_servicio_'+id_evento_nuevo_servicio+'" style="width:100%;" required></select>\
                                            </div>\
                                        </div>\
                                        <div class="form-group row">\
                                            <label for="" class="col-sm-3 col-form-label">Fecha de acción <span style="color:red;">(*)</span></label>\
                                            <div class="col-sm-9">\
                                                <input type="date" readonly class="form-control" name="nueva_fecha_accion" id="nueva_fecha_accion_'+id_evento_nuevo_servicio+'" required>\
                                            </div>\
                                        </div>\
                                        <div class="form-group row">\
                                            <label for="" class="col-sm-3 col-form-label">Acción <span style="color:red;">(*)</span></label>\
                                            <div class="col-sm-9">\
                                                <select class="nueva_accion_'+id_evento_nuevo_servicio+' custom-select" name="nueva_accion" id="nueva_accion_'+id_evento_nuevo_servicio+'" style="width:100%;" required></select>\
                                            </div>\
                                        </div>\
                                        <div class="form-group row">\
                                            <label for="" class="col-sm-3 col-form-label">Profesional</label>\
                                            <div class="col-sm-9">\
                                                <select class="nuevo_profesional_'+id_evento_nuevo_servicio+' custom-select" name="nuevo_profesional" id="nuevo_profesional_'+id_evento_nuevo_servicio+'" style="width:100%;"></select>\
                                                <strong class="mensaje_no_hay_profesionales_servicio text-danger text-sm d-none" role="alert">No hay usuarios relacionados al proceso seleccionado.</strong>\
                                            </div>\
                                        </div>\
                                        <div class="form-group row">\
                                            <label for="" class="col-sm-3 col-form-label">Descripción <span style="color:red;">(*)</span></label>\
                                            <div class="col-sm-9">\
                                                <textarea class="form-control" name="nueva_descripcion" id="nueva_descripcion_'+id_evento_nuevo_servicio+'" rows="2" required></textarea>\
                                            </div>\
                                        </div>\
                                        <div class="form-group row">\
                                            <label for="" class="col-sm-3 col-form-label">Fecha alerta <span style="color:red;">(*)</span></label>\
                                            <div class="col-sm-9">\
                                                <input type="date" class="form-control" name="nueva_fecha_alerta" id="nueva_fecha_alerta_'+id_evento_nuevo_servicio+'" min="'+fecha_de_hoy+'" required>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>\
                            <div class="mostrar_mensaje_creo_servicio alert alert-success mt-2 mr-auto d-none" role="alert"></div>\
                            <div class="modal-footer">\
                                <div class="alert alert-danger no_ejecutar_parametrica_mod_consultar d-none" role="alert">\
                                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> No puede crear el servicio debido a que el proceso, servicio y/o acción seleccionados no tienen una parametrización\
                                    asociada. Debe configurar una.\
                                </div>\
                                <input type="hidden" class="form-control" id="nro_evento_'+id_evento_nuevo_servicio+'" value="'+id_evento_nuevo_servicio+'">\
                                <a href="javascript:void(0);" class="text-dark text-md mr-auto" data-toggle="modal" data-target="#modalListaDocumentos" id="cargue_documentos_evento_'+id_evento_nuevo_servicio+'">\
                                <i class="far fa-file text-info"></i> <strong>Cargue Documentos</strong></a>\
                                <button type="submit" class="btn btn-info" id="crear_servicio_evento_'+id_evento_nuevo_servicio+'">Crear</button>\
                                <button type="button" class="btn btn-info d-none" id="actualizar_consulta_'+id_evento_nuevo_servicio+'">Actualizar</button>\
                                <input type="hidden" class="form-control" id="tupla_servicio_evento_'+id_evento_nuevo_servicio+'" value="'+id_asignacion_nuevo_servicio+'">\
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>\
                            </div>\
                        </form>\
                    </div>\
                </div>\
            </div>\
        ';

        $('.renderizar_nuevo_servicio').empty();
        $('.renderizar_nuevo_servicio').append($string_html_modal_nuevo_servicio);

    });

    /* FUNCIONES ASOCIADAS AL BOTÓN DE NUEVO SERVICIO */
    $(document).on('click', "a[id^='btn_nuevo_servicio_']", function(){

        /* INICIALIZACIÓN DEL SELECT2 DE LISTADO SERVICIOS */
        $("select[id^='nuevo_servicio_']").select2({
            placeholder: "Seleccione una opción",
            allowClear: false
        });

        let token = $("input[name='_token']").val();
        let id_clientes_actual = $('.renderizar_nuevo_servicio').find("input[id^='id_clientes_']").val();
        let id_proceso_actual = $('.renderizar_nuevo_servicio').find("input[id^='id_proceso_actual_']").val();
        let id_servicio_actual = $('.renderizar_nuevo_servicio').find("input[id^='id_servicio_actual_']").val();
        let nro_evento = $('.renderizar_nuevo_servicio').find("input[id^='nro_evento_']").val();
        let id_asignacion = $('.renderizar_nuevo_servicio').find("input[id^='tupla_servicio_evento_']").val();
        
        /* CARGUE DE INFORMACIÓN DEL SELECTOR DE Servicio */
        let selector_nuevo_servicio = $('.renderizar_nuevo_servicio').find("select[id^='nuevo_servicio_']").attr("id");
        
        let datos_listado_servicios_nuevo_servicio = {
            '_token': token,
            'parametro' : "listado_servicios_nuevo_servicio",
            'id_proceso_actual' : id_proceso_actual,
            'id_servicio_actual': id_servicio_actual,
            'nro_evento': nro_evento,
            'id_asignacion': id_asignacion,
            'id_cliente':id_clientes_actual
        };
        
        $.ajax({
            type:'POST',
            url:'/cargarselectores',
            data: datos_listado_servicios_nuevo_servicio,
            success:function(data) {
                $("#"+selector_nuevo_servicio).empty();
                $("#"+selector_nuevo_servicio).append('<option value="" selected>Seleccione</option>');
    
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#"+selector_nuevo_servicio).append('<option value="'+data[claves[i]]["Id_Servicio"]+'">'+data[claves[i]]["Nombre_servicio"]+'</option>');
                }
            }
        });

        /* SETEO DE LA FECHA ACTUAL PARA EL CAMPO DE FECHA DE ACCIÓN */
        var fecha = new Date();
        $('.renderizar_nuevo_servicio').find("input[id^='nueva_fecha_accion_']").val(fecha.toJSON().slice(0,10));

        /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE ACCIONES */
        $("select[id^='nueva_accion_']").select2({
            placeholder: "Seleccione una opción",
            allowClear: false
        });

        /* CARGUE DE INFORMACIÓN DEL SELECTOR DE Acción */
        var selector_nueva_accion_nuevo_servicio = $('.renderizar_nuevo_servicio').find("select[id^='nueva_accion_']").attr("id");
        $("#"+selector_nuevo_servicio).change(function(){
            // 'Id_servicio': id_servicio_actual,
            let datos_listado_accion = {
                '_token': token,
                'parametro' : "listado_accion_nuevo_servicio",
                'Id_proceso' : id_proceso_actual,
                'Id_servicio': $(this).val(),
                'nro_evento': nro_evento,
                'Id_asignacion': id_asignacion
            };
            
            $.ajax({
                type:'POST',
                url:'/cargarselectores',
                data: datos_listado_accion,
                success:function(data) {
                    if (data.length > 0) {
                        $("#"+selector_nueva_accion_nuevo_servicio).empty();
                        $("#"+selector_nueva_accion_nuevo_servicio).append('<option></option>');
            
                        let claves = Object.keys(data);
                        for (let i = 0; i < claves.length; i++) {
                            $("#"+selector_nueva_accion_nuevo_servicio).append('<option value="'+data[claves[i]]["Id_Accion"]+'">'+data[claves[i]]["Nombre_accion"]+'</option>');
                        }

                        $(".no_ejecutar_parametrica_mod_consultar").addClass('d-none');
                        $('.renderizar_nuevo_servicio').find("a[id^='cargue_documentos_evento_']").removeClass('d-none');
                        $('.renderizar_nuevo_servicio').find("button[id^='crear_servicio_evento_']").removeClass('d-none');
                    } else {
                        $("#"+selector_nueva_accion_nuevo_servicio).empty();
                        $("#"+selector_nueva_accion_nuevo_servicio).append('<option></option>');
                        $(".no_ejecutar_parametrica_mod_consultar").removeClass('d-none');
                        $('.renderizar_nuevo_servicio').find("a[id^='cargue_documentos_evento_']").addClass('d-none');
                        $('.renderizar_nuevo_servicio').find("button[id^='crear_servicio_evento_']").addClass('d-none');
                    }
                }
            });
        });

        /* VALIDACIÓN PARA DETERMINAR QUE LA PARAMÉTRICA QUE SE CONFIGURE PARA EL MÓDULO CONSULTAR ESTE EN UN VALOR DE SI EN LA TABLA sigmel_informacion_parametrizaciones_clientes */
        var validar_mod_consultar = setInterval(() => {
            if(id_proceso_actual != '' && $("#"+selector_nuevo_servicio).val() != '' && $("#"+selector_nueva_accion_nuevo_servicio).val() != ''){
                let datos_ejecutar_parametrica_mod_consultar= {
                    '_token': token,
                    'parametro': "validarSiModConsultar",
                    'Id_proceso': id_proceso_actual,
                    'Id_servicio': $("#"+selector_nuevo_servicio).val(),
                    'Id_accion': $("#"+selector_nueva_accion_nuevo_servicio).val(),
                    'nro_evento': nro_evento
                };
                $.ajax({
                    type:'POST',
                    url:'/validacionParametricaEnSi',
                    data: datos_ejecutar_parametrica_mod_consultar,
                    success:function(data) {
                        if(data.length > 0){
                            if (data[0]["Modulo_consultar"] !== "Si") {
                                // $("#"+selector_nueva_accion_nuevo_servicio).empty();
                                // $("#"+selector_nueva_accion_nuevo_servicio).append('<option></option>');
                                $(".no_ejecutar_parametrica_mod_consultar").removeClass('d-none');
                                $('.renderizar_nuevo_servicio').find("a[id^='cargue_documentos_evento_']").addClass('d-none');
                                $('.renderizar_nuevo_servicio').find("button[id^='crear_servicio_evento_']").addClass('d-none');
                            } else {
                                $(".no_ejecutar_parametrica_mod_consultar").addClass('d-none');
                                $('.renderizar_nuevo_servicio').find("a[id^='cargue_documentos_evento_']").removeClass('d-none');
                                $('.renderizar_nuevo_servicio').find("button[id^='crear_servicio_evento_']").removeClass('d-none');
                                clearInterval(validar_mod_consultar);
                            }
                        }
                    
                    }
                });
            }
            
        }, 500);

        /* INICIALIZACIÓN DEL SELECT2 DE LISTADO PROFESIONALES DEPENDIENDO DEL PROCESO. */
        $("select[id^='nuevo_profesional_']").select2({
            placeholder: "Seleccione una opción",
            allowClear: false
        });

        let selector_nuevo_profesional = $('.renderizar_nuevo_servicio').find("select[id^='nuevo_profesional_']").attr("id");

        let datos_listado_profesionales_proceso = {
            '_token': token,
            'id_proceso' : id_proceso_actual,
        };

        // CARGUE DE PROFESIONALES ACORDE AL PROCESO.
        $.ajax({
            type:'POST',
            url:'/ProfesionalesXProceso',
            data: datos_listado_profesionales_proceso,
            success:function(data) {
                if (data.length > 0) {
                    $("#"+selector_nuevo_profesional).empty();
                    $("#"+selector_nuevo_profesional).append('<option value="" selected>Seleccione</option>');
        
                    let claves = Object.keys(data);
                    for (let i = 0; i < claves.length; i++) {
                        $("#"+selector_nuevo_profesional).append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["name"]+'</option>');
                    }
                    
                    $('.mensaje_no_hay_profesionales_servicio').addClass("d-none");
                }else{
                    $('.mensaje_no_hay_profesionales_servicio').removeClass("d-none");
                }
            }
        });
    });

    /* Obtener el ID del servicio a dar clic en cualquier botón de cargue de archivo y asignarlo al input hidden del id servicio */
    $(document).on('click', "input[id^='listadodocumento_']", function(){
        
        if (dato_cargar.includes("Nuevo servicio")) {
            // nuevo_servicio_987456321
            var id_servicio_seleccionado = $("select[id^='nuevo_servicio_']").val();
        } else {
            // selector_nuevo_servicio_987456321
            var id_servicio_seleccionado = $("select[id^='selector_nuevo_servicio_']").val();
        }
        
        $("input[id^='Id_servicio_']").val(id_servicio_seleccionado);
    });

    /* CAPTURA ID EVENTO PARA PINTAR EL LISTADO DE DOCUMENTOS PERTENENCIENTES A EL */
    $(document).on('click', "a[id^='cargue_documentos_evento_']", function(){
        let token = $("input[name='_token']").val();
        let id_consultar_documentos_evento = $('.renderizar_nuevo_servicio').find("input[id^='nro_evento_']").val();

        let datos_a_consultar = {
            '_token': token,
            'id_evento':id_consultar_documentos_evento
        }

        $.ajax({
            type:'POST',
            url:'/cargueDocumentosXEvento',
            data: datos_a_consultar,
            success:function(data) {
                
                // <td id="estadoDocumento_'+arraylistado_documentos["Id_Documento"]+'">'+(arraylistado_documentos["estado_documento"] == "Cargado" ? '<strong class="text-success">Cargado</strong>': '<strong class="text-danger">No Cargado</strong>')+'</td>\
                $('#habilitar').removeClass('d-none');
                $('#agregar_documentos').empty();
                $.each(data, function(index, arraylistado_documentos){
                   $('#agregar_documentos').append('\
                        <tr>\
                            <td>'+arraylistado_documentos["Nro_documento"]+'</td>\
                            <td style="width: 34% !important;">'+arraylistado_documentos["Nombre_documento"]+'</td>\
                            <td id="estadoDocumento_'+arraylistado_documentos["Id_Documento"]+'"><strong class="text-danger">No Cargado</strong></td>\
                            <td>'+(arraylistado_documentos["Nombre_documento"] === "Otros documentos" ? '\
                                <form id="formulario_documento_'+arraylistado_documentos["Id_Documento"]+'" class="form-inline align-items-center"" method="POST" enctype="multipart/form-data">\
                                    <input type="hidden" name="_token" value="'+token+'">\
                                    <div class="col-12">\
                                        <div class="d-none">\
                                            <input type="text" name="Id_Documento" value="'+arraylistado_documentos["Id_Documento"]+'">\
                                            <input type="text" name="Nombre_documento" value="'+arraylistado_documentos["Nombre_documento"]+'">\
                                            <input type="text" name="EventoID" id="EventoID_'+arraylistado_documentos["Id_Documento"]+'" value="'+id_consultar_documentos_evento+'">\
                                            <input type="text" name="bandera_nombre_otro_doc" value="'+arraylistado_documentos["nombre_Documento"]+'">\
                                        </div>\
                                        <div class="row">\
                                            <div class="input-group">\
                                                <input type="file" class="form-control select-doc" name="listadodocumento" id="listadodocumento_'+arraylistado_documentos["Id_Documento"]+'"\
                                                aria-describedby="Carguedocumentos" aria-label="Upload"'+(arraylistado_documentos["Requerido"] === 'Si' ? 'required' : '')+'>&nbsp;\
                                                <button class="btn btn-info button-doc-select" type="submit" id="CargarDocumento_'+arraylistado_documentos["Id_Documento"]+'">\
                                                    '+(arraylistado_documentos["estado_documento"] == "Cargado" ? 'Cargar' : 'Cargar')+'\
                                                </button>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </form>\
                            ' : '\
                                <form id="formulario_documento_'+arraylistado_documentos["Id_Documento"]+'" method="POST" enctype="multipart/form-data">\
                                    <input type="hidden" name="_token" value="'+token+'">\
                                    <div class="d-none">\
                                        <input type="text" name="Id_Documento" value="'+arraylistado_documentos["Id_Documento"]+'">\
                                        <input type="text" name="Nombre_documento" value="'+arraylistado_documentos["Nombre_documento"]+'">\
                                        <input  type="text" name="EventoID" id="EventoID_'+arraylistado_documentos["Id_Documento"]+'" value="'+id_consultar_documentos_evento+'">\
                                        <input  type="text" name="Id_servicio" id="Id_servicio_'+arraylistado_documentos["Id_Documento"]+'">\
                                    </div>\
                                    <div class="row">\
                                            <div class="input-group">\
                                                <input type="file" class="form-control select-doc" name="listadodocumento" id="listadodocumento_'+arraylistado_documentos["Id_Documento"]+'"\
                                                aria-describedby="Carguedocumentos" aria-label="Upload"'+(arraylistado_documentos["Requerido"] === 'Si' ? 'required' : '')+'>&nbsp;\
                                                <button class="btn btn-info button-doc-select" type="submit" id="CargarDocumento_'+arraylistado_documentos["Id_Documento"]+'">\
                                                    '+(arraylistado_documentos["estado_documento"] == "Cargado" ? 'Cargar' : 'Cargar')+'\
                                                </button>\
                                            </div>\
                                        </div>\
                                </form>\
                            ')+'\
                            </td>\
                            <td class="text-center" style="width: 10% !important;">\
                                <input type="checkbox" class="scales" name="checkdocumentos" id="check_documento_'+arraylistado_documentos["Id_Documento"]+'"\
                                '+(arraylistado_documentos["Requerido"] === "Si" ? 'checked disabled' : 'disabled')+'\
                                >\
                            </td>\
                        </tr>\
                   ');
                });
            }
        });
    });



    /* CREACIÓN DE NUEVO SERVICIO */
    $(document).on('submit', "form[id^='form_nuevo_servicio_evento_']", function(e){
        e.preventDefault();

        var crear_servicio_evento = $("button[id^='crear_servicio_evento_']");

        if (crear_servicio_evento.length > 0) {
            document.querySelector("button[id^='crear_servicio_evento_']").disabled=true;            
        }

        let id_clientes = $('.renderizar_nuevo_servicio').find("input[id^='id_clientes_']").val();        
        let nro_evento = $('.renderizar_nuevo_servicio').find("input[id^='nro_evento_']").val();
        let tupla_servicio_escogido = $('.renderizar_nuevo_servicio').find("input[id^='tupla_servicio_evento_']").val();
        let token = $("input[name='_token']").val();

        let nombre_profesional_escogido = $('.renderizar_nuevo_servicio').find("select[id^='nuevo_profesional_'] option:selected").text();

        let datos_nuevo_servicio = {
            '_token': token,
            'nombre_profesional': nombre_profesional_escogido,
            'id_evento': nro_evento,
            'id_clientes': id_clientes,
            'tupla_servicio_escogido': tupla_servicio_escogido
        };

        var formData = new FormData($(this)[0]);
        for (var pair of formData.entries()) {
            var nombres_keys = pair[0];
            datos_nuevo_servicio[nombres_keys] = pair[1];
        }
        
        $.ajax({
            url: "/crearNuevoServicio",
            type: "post",
            data: datos_nuevo_servicio,
            success:function(response){
                if(response.parametro == "creo_servicio"){

                    $("#crear_servicio_evento_"+response.retorno_id_evento).addClass('d-none');
                    $("#actualizar_consulta_"+response.retorno_id_evento).removeClass('d-none');

                    $('.mostrar_mensaje_creo_servicio').removeClass('d-none');
                    $('.mostrar_mensaje_creo_servicio').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.mostrar_mensaje_creo_servicio').addClass('d-none');
                    $('.mostrar_mensaje_creo_servicio').empty();
                    }, 9000);
                }
            }         
        });

    });

    /* ACTUALIZAR CONSULTA (Botón Actualizar formulario Nuevo Servicio) */
    $(document).on('click', "button[id^='actualizar_consulta_']", function(){

        let token = $("input[name='_token']").val();

        let datos_formulario_consulta = {
            "_token": token,
            "parametro": "mantener_datos_busqueda",
            "consulta_nro_identificacion": $("#consultar_nro_identificacion").val(),
            "consulta_id_evento": $("#consultar_id_evento").val()
        };

        $.ajax({
            url: "/mantenerDatosBusquedaEvento",
            type: "post",
            data: datos_formulario_consulta,
            success:function(response){
                if (response.parametro == "creo_variables") {
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            }         
        });
    })




    /* CREACIÓN Y AGREGACIÓN DEL MODAL NUEVO PROCESO AL CONTENEDOR DE REDENRIZAMIENTO */
    $(document).on('mouseover', "a[id^='btn_nuevo_proceso_']", function(){
        dato_cargar = "Nuevo proceso";
        var id_evento_nuevo_proceso =  $(this).data("id_evento_nuevo_proceso"); //ID EVENTO
        var id_proceso_nuevo_proceso =  $(this).data("id_proceso_nuevo_proceso"); // ID PROCESO ACTUAL
        var id_servicio_nuevo_proceso =  $(this).data("id_servicio_nuevo_proceso"); //ID SERVICIO ACTUAL
        var id_asignacion_nuevo_proceso =  $(this).data("id_asignacion_nuevo_proceso"); // ID TUPLA DE PROCESO (PARA COLOCAR ESTADO DE VISIBLE EN NO)
        var id_cliente = $(this).data("id_cliente"); // ID DEL CLIENTE
        var fecha_de_hoy = $("#fecha_de_hoy").val();

        $string_html_modal_nuevo_proceso = '\
            <div class="modal fade" id="modalNuevoProceso_'+id_evento_nuevo_proceso+'" tabindex="-1" aria-hidden="true">\
                <div class="modal-dialog modal-lg">\
                    <div class="modal-content">\
                        <div class="modal-header bg-info">\
                            <h4 class="modal-title"><i class="far fa-clone"></i> Nuevo proceso para el evento: '+id_evento_nuevo_proceso+'</h4>\
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
                                <span aria-hidden="true">&times;</span>\
                            </button>\
                        </div>\
                        <form id="form_nuevo_proceso_evento_'+id_evento_nuevo_proceso+'" method="POST">\
                            <div class="modal-body">\
                                <p>Los campos marcados con <span style="color:red;">(*)</span> son de obligatorio diligenciamiento para guardar la información.</p>\
                                <input type="hidden" class="form-control" name="id_clientes" id="id_clientes_'+id_cliente+'" value="'+id_cliente+'">\
                                <div class="row">\
                                    <div class="col-12">\
                                        <div class="form-group row">\
                                            <label for="" class="col-sm-3 col-form-label">ID evento relacionado</label>\
                                            <div class="col-sm-9">\
                                                <input type="text" class="form-control" readonly value="'+id_evento_nuevo_proceso+'">\
                                            </div>\
                                        </div>\
                                        <div class="form-group row">\
                                            <label for="" class="col-sm-3 col-form-label">Fecha de Radicación <span style="color:red;">(*)</span></label>\
                                            <div class="col-sm-9">\
                                                <input type="date" class="form-control" name="fecha_radicacion_nuevo_proceso" id="fecha_radicacion_nuevo_proceso" max="'+fecha_de_hoy+'" required>\
                                            </div>\
                                        </div>\
                                        <div class="form-group row">\
                                            <label for="" class="col-sm-3 col-form-label">Proceso <span style="color:red;">(*)</span></label>\
                                            <div class="col-sm-9">\
                                                <input type="hidden" class="form-control" name="id_proceso_actual_nuevo_proceso" id="id_proceso_actual_nuevo_proceso_'+id_evento_nuevo_proceso+'" value="'+id_proceso_nuevo_proceso+'">\
                                                <select class="selector_nuevo_proceso_'+id_evento_nuevo_proceso+' custom-select" name="selector_nuevo_proceso" id="selector_nuevo_proceso_'+id_evento_nuevo_proceso+'" style="width:100%;" requierd></select>\
                                            </div>\
                                        </div>\
                                        <div class="form-group row">\
                                            <label for="" class="col-sm-3 col-form-label">Servicio <span style="color:red;">(*)</span></label>\
                                            <div class="col-sm-9">\
                                                <input type="hidden" class="form-control" name="id_servicio_actual_nuevo_proceso" id="id_servicio_actual_nuevo_proceso_'+id_evento_nuevo_proceso+'" value="'+id_servicio_nuevo_proceso+'">\
                                                <select class="selector_nuevo_servicio_'+id_evento_nuevo_proceso+' custom-select" name="selector_nuevo_servicio" id="selector_nuevo_servicio_'+id_evento_nuevo_proceso+'" style="width:100%;" required></select>\
                                            </div>\
                                        </div>\
                                        <div class="form-group row">\
                                            <label for="" class="col-sm-3 col-form-label">Fecha de acción <span style="color:red;">(*)</span></label>\
                                            <div class="col-sm-9">\
                                                <input type="date" readonly class="form-control" name="nueva_fecha_accion_nuevo_proceso" id="nueva_fecha_accion_nuevo_proceso_'+id_evento_nuevo_proceso+'" required>\
                                            </div>\
                                        </div>\
                                        <div class="form-group row">\
                                            <label for="" class="col-sm-3 col-form-label">Acción <span style="color:red;">(*)</span></label>\
                                            <div class="col-sm-9">\
                                                <select class="nueva_accion_nuevo_proceso_'+id_evento_nuevo_proceso+' custom-select" name="nueva_accion_nuevo_proceso" id="nueva_accion_nuevo_proceso_'+id_evento_nuevo_proceso+'" style="width:100%;" required></select>\
                                            </div>\
                                        </div>\
                                        <div class="form-group row">\
                                            <label for="" class="col-sm-3 col-form-label">Profesional</label>\
                                            <div class="col-sm-9">\
                                                <select class="nuevo_profesional_nuevo_proceso_'+id_evento_nuevo_proceso+' custom-select" name="nuevo_profesional_nuevo_proceso" id="nuevo_profesional_nuevo_proceso_'+id_evento_nuevo_proceso+'" style="width:100%;"></select>\
                                                <strong class="mensaje_no_hay_profesionales_proceso text-danger text-sm d-none" role="alert">No hay usuarios relacionados al proceso seleccionado.</strong>\
                                            </div>\
                                        </div>\
                                        <div class="form-group row">\
                                            <label for="" class="col-sm-3 col-form-label">Descripción <span style="color:red;">(*)</span></label>\
                                            <div class="col-sm-9">\
                                                <textarea class="form-control" name="nueva_descripcion_nuevo_proceso" id="nueva_descripcion_nuevo_proceso_'+id_evento_nuevo_proceso+'" rows="2" required></textarea>\
                                            </div>\
                                        </div>\
                                        <div class="form-group row">\
                                            <label for="" class="col-sm-3 col-form-label">Fecha alerta <span style="color:red;">(*)</span></label>\
                                            <div class="col-sm-9">\
                                                <input type="date" class="form-control" name="nueva_fecha_alerta_nuevo_proceso" id="nueva_fecha_alerta_nuevo_proceso_'+id_evento_nuevo_proceso+'" min="'+fecha_de_hoy+'" required>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>\
                            <div class="mostrar_mensaje_creo_proceso alert alert-success mt-2 mr-auto d-none" role="alert"></div>\
                            <div class="modal-footer">\
                                <div class="alert alert-danger no_ejecutar_parametrica_mod_consultar d-none" role="alert">\
                                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> No puede crear el proceso debido a que el proceso, servicio y/o acción seleccionados no tienen una parametrización\
                                    asociada. Debe configurar una.\
                                </div>\
                                <input type="hidden" class="form-control" id="nro_evento_nuevo_proceso_'+id_evento_nuevo_proceso+'" value="'+id_evento_nuevo_proceso+'">\
                                <a href="javascript:void(0);" class="text-dark text-md mr-auto" data-toggle="modal" data-target="#modalListaDocumentos" id="cargue_documentos_nuevo_proceso_evento_'+id_evento_nuevo_proceso+'">\
                                <i class="far fa-file text-info"></i> <strong>Cargue Documentos</strong></a>\
                                <button type="submit" class="btn btn-info" id="crear_proceso_evento_'+id_evento_nuevo_proceso+'">Crear</button>\
                                <button type="button" class="btn btn-info d-none" id="actualizar_consulta_nuevo_proceso_'+id_evento_nuevo_proceso+'">Actualizar</button>\
                                <input type="hidden" class="form-control" id="tupla_proceso_evento_'+id_evento_nuevo_proceso+'" value="'+id_asignacion_nuevo_proceso+'">\
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>\
                            </div>\
                        </form>\
                    </div>\
                </div>\
            </div>\
        ';
        $('.renderizar_nuevo_proceso').empty();
        $('.renderizar_nuevo_proceso').append($string_html_modal_nuevo_proceso);
    });

    /* FUNCIONES ASOCIADAS AL BOTÓN DE NUEVO PROCESO */
    $(document).on('click', "a[id^='btn_nuevo_proceso_']", function(){

        /* INICIALIZACIÓN DEL SELECT2 DE LISTADO PROCESOS */
        $("select[id^='selector_nuevo_proceso_']").select2({
            placeholder: "Seleccione una opción",
            allowClear: false
        });

        let token = $("input[name='_token']").val();
        let id_clientes = $('.renderizar_nuevo_proceso').find("input[id^='id_clientes_']").val();
        let ident_evento_actual = $('.renderizar_nuevo_proceso').find("input[id^='nro_evento_nuevo_proceso_']").val();
        let selector_nuevo_proceso = $('.renderizar_nuevo_proceso').find("select[id^='selector_nuevo_proceso_']").attr("id");
        let nro_evento_nuevo_proceso = $('.renderizar_nuevo_proceso').find("input[id^='nro_evento_nuevo_proceso_']").val();
        let id_asignacion = $('.renderizar_nuevo_proceso').find("input[id^='tupla_proceso_evento_']").val();

        /* CARGUE DE INFORMACIÓN DEL SELECTOR DE Proceso */
        let datos_listado_procesos_nuevo_proceso = {
            '_token': token,
            'parametro' : "listado_procesos_nuevo_proceso",
            'ident_evento_actual': ident_evento_actual,
            'id_clientes': id_clientes
        };
        
        $.ajax({
            type:'POST',
            url:'/cargarselectores',
            data: datos_listado_procesos_nuevo_proceso,
            success:function(data) {
                $("#"+selector_nuevo_proceso).empty();
                $("#"+selector_nuevo_proceso).append('<option value="" selected>Seleccione</option>');
    
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#"+selector_nuevo_proceso).append('<option value="'+data[claves[i]]["Id_proceso"]+'">'+data[claves[i]]["Nombre_proceso"]+'</option>');
                }
            }
        });

        /* INICIALIZACIÓN DEL SELECT2 DE LISTADO SERVICIOS */
        $("select[id^='selector_nuevo_servicio_']").select2({
            placeholder: "Seleccione una opción",
            allowClear: false
        });

        /* SETEO DE LA FECHA ACTUAL PARA EL CAMPO DE FECHA DE ACCIÓN */
        var fecha = new Date();
        $('.renderizar_nuevo_proceso').find("input[id^='nueva_fecha_accion_nuevo_proceso_']").val(fecha.toJSON().slice(0,10));

        /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE ACCIONES */
        $("select[id^='nueva_accion_nuevo_proceso_']").select2({
            placeholder: "Seleccione una opción",
            allowClear: false
        });

        /* CARGUE DE INFORMACIÓN DEL SELECTOR DE Acción */
        let selector_nueva_accion_nuevo_proceso = $(".renderizar_nuevo_proceso").find("select[id^='nueva_accion_nuevo_proceso_']").attr("id");
        var selector_nuevo_servicio = $('.renderizar_nuevo_proceso').find("select[id^='selector_nuevo_servicio_']").attr("id");
        var id_proceso_escogido = $('.renderizar_nuevo_proceso').find("select[id^='selector_nuevo_proceso_']").attr("id");

        $("#"+selector_nuevo_servicio).change(function(){

            let datos_listado_accion_nuevo_proceso = {
                '_token': token,
                'parametro' : "listado_accion_nuevo_proceso",
                'Id_proceso' : $("#"+id_proceso_escogido).val(),
                'Id_servicio': $(this).val(),
                'nro_evento': nro_evento_nuevo_proceso,
                'Id_asignacion': id_asignacion
            };
            // console.log(datos_listado_accion_nuevo_proceso);
            $.ajax({
                type:'POST',
                url:'/cargarselectores',
                data: datos_listado_accion_nuevo_proceso,
                success:function(data) {
                    if (data.length > 0) {
                        $("#"+selector_nueva_accion_nuevo_proceso).empty();
                        $("#"+selector_nueva_accion_nuevo_proceso).append('<option></option>');
        
                        let claves = Object.keys(data);
                        for (let i = 0; i < claves.length; i++) {
                            $("#"+selector_nueva_accion_nuevo_proceso).append('<option value="'+data[claves[i]]["Id_Accion"]+'">'+data[claves[i]]["Nombre_accion"]+'</option>');
                        }

                        $(".no_ejecutar_parametrica_mod_consultar").addClass('d-none');
                        $('.renderizar_nuevo_proceso').find("a[id^='cargue_documentos_nuevo_proceso_evento_']").removeClass('d-none');
                        $('.renderizar_nuevo_proceso').find("button[id^='crear_proceso_evento_']").removeClass('d-none');

                    } else {
                        $("#"+selector_nueva_accion_nuevo_proceso).empty();
                        $("#"+selector_nueva_accion_nuevo_proceso).append('<option></option>');

                        $(".no_ejecutar_parametrica_mod_consultar").removeClass('d-none');
                        $('.renderizar_nuevo_proceso').find("a[id^='cargue_documentos_nuevo_proceso_evento_']").addClass('d-none');
                        $('.renderizar_nuevo_proceso').find("button[id^='crear_proceso_evento_']").addClass('d-none');

                    }
                }
            });

        });

        /* VALIDACIÓN PARA DETERMINAR QUE LA PARAMÉTRICA QUE SE CONFIGURE PARA EL MÓDULO CONSULTAR ESTE EN UN VALOR DE SI EN LA TABLA sigmel_informacion_parametrizaciones_clientes */
        var validar_mod_consultar = setInterval(() => {
            if($("#"+id_proceso_escogido).val() != '' && $("#"+selector_nuevo_servicio).val() != '' && $("#"+selector_nueva_accion_nuevo_proceso).val() != ''){
                let datos_ejecutar_parametrica_mod_consultar= {
                    '_token': token,
                    'parametro': "validarSiModConsultar",
                    'Id_proceso': $("#"+id_proceso_escogido).val(),
                    'Id_servicio': $("#"+selector_nuevo_servicio).val(),
                    'Id_accion': $("#"+selector_nueva_accion_nuevo_proceso).val(),
                    'nro_evento': nro_evento_nuevo_proceso
                };
                // console.log(datos_ejecutar_parametrica_mod_consultar);
                $.ajax({
                    type:'POST',
                    url:'/validacionParametricaEnSi',
                    data: datos_ejecutar_parametrica_mod_consultar,
                    success:function(data) {
                        if(data.length > 0){
                            if (data[0]["Modulo_consultar"] !== "Si") {
                                // $("#"+selector_nueva_accion_nuevo_servicio).empty();
                                // $("#"+selector_nueva_accion_nuevo_servicio).append('<option></option>');
                                $(".no_ejecutar_parametrica_mod_consultar").removeClass('d-none');
                                $('.renderizar_nuevo_proceso').find("a[id^='cargue_documentos_nuevo_proceso_evento_']").addClass('d-none');
                                $('.renderizar_nuevo_proceso').find("button[id^='crear_proceso_evento_']").addClass('d-none');
                            } else {
                                $(".no_ejecutar_parametrica_mod_consultar").addClass('d-none');
                                $('.renderizar_nuevo_proceso').find("a[id^='cargue_documentos_nuevo_proceso_evento_']").removeClass('d-none');
                                $('.renderizar_nuevo_proceso').find("button[id^='crear_proceso_evento_']").removeClass('d-none');
                                clearInterval(validar_mod_consultar);
                            }
                        }
                    
                    }
                });
            }
            
        }, 500);


        /* INICIALIZACIÓN DEL SELECT2 DE LISTADO PROFESIONALES DEPENDIENDO DEL PROCESO. */
        $("select[id^='nuevo_profesional_nuevo_proceso_']").select2({
            placeholder: "Seleccione una opción",
            allowClear: false
        });
        
    });

    /* CARGUE DE INFORMACIÓN DEL SELECTOR de Servicios y Profesionales que dependen del proceso. */
    $(document).on('change', "select[id^='selector_nuevo_proceso_']", function(){
        let id_clientes = $('.renderizar_nuevo_proceso').find("input[id^='id_clientes_']").val();
        let id_servicio_actual_nuevo_proceso = $('.renderizar_nuevo_proceso').find("input[id^='id_servicio_actual_']").val();
        let nro_evento_nuevo_proceso = $('.renderizar_nuevo_proceso').find("input[id^='nro_evento_nuevo_proceso_']").val();
        let token = $("input[name='_token']").val();
        let id_proceso_escogido = $(this).val();

        var selector_nuevo_servicio = $('.renderizar_nuevo_proceso').find("select[id^='selector_nuevo_servicio_']").attr("id");
        let selector_nuevo_profesional_nuevo_proceso = $('.renderizar_nuevo_proceso').find("select[id^='nuevo_profesional_nuevo_proceso_']").attr("id");

        let datos_listado_servicios_nuevo_proceso = {
            '_token': token,
            'parametro' : "listado_servicios_nuevo_proceso",
            'id_proceso_escogido' : id_proceso_escogido,
            'id_servicio_actual_nuevo_proceso': id_servicio_actual_nuevo_proceso,
            'nro_evento': nro_evento_nuevo_proceso,
            'id_cliente':id_clientes
        };

        $.ajax({
            type:'POST',
            url:'/cargarselectores',
            data: datos_listado_servicios_nuevo_proceso,
            success:function(data) {
                // console.log(data);
                $("#"+selector_nuevo_servicio).empty();
                $("#"+selector_nuevo_servicio).append('<option></option>');
    
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#"+selector_nuevo_servicio).append('<option value="'+data[claves[i]]["Id_Servicio"]+'">'+data[claves[i]]["Nombre_servicio"]+'</option>');
                }
            }
        });

        let datos_listado_profesionales_proceso = {
            '_token': token,
            'id_proceso' : id_proceso_escogido,
        };

        // CARGUE DE PROFESIONALES ACORDE AL PROCESO.
        $.ajax({
            type:'POST',
            url:'/ProfesionalesXProceso',
            data: datos_listado_profesionales_proceso,
            success:function(data) {
                if (data.length > 0) {
                    $("#"+selector_nuevo_profesional_nuevo_proceso).empty();
                    $("#"+selector_nuevo_profesional_nuevo_proceso).append('<option value="" selected>Seleccione</option>');
        
                    let claves = Object.keys(data);
                    for (let i = 0; i < claves.length; i++) {
                        $("#"+selector_nuevo_profesional_nuevo_proceso).append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["name"]+'</option>');
                    }
                    $('.mensaje_no_hay_profesionales_proceso').addClass("d-none");
                }else{
                    $('.mensaje_no_hay_profesionales_proceso').removeClass("d-none");
                }
            }
        });
        

    });

    /* CAPTURA ID EVENTO PARA PINTAR EL LISTADO DE DOCUMENTOS PERTENENCIENTES A EL */
    $(document).on('click', "a[id^='cargue_documentos_nuevo_proceso_evento_']", function(){
        let token = $("input[name='_token']").val();
        let id_consultar_documentos_evento = $('.renderizar_nuevo_proceso').find("input[id^='nro_evento_nuevo_proceso_']").val();

        let datos_a_consultar = {
            '_token': token,
            'id_evento':id_consultar_documentos_evento
        }

        $.ajax({
            type:'POST',
            url:'/cargueDocumentosXEvento',
            data: datos_a_consultar,
            success:function(data) {
                
                // <td id="estadoDocumento_'+arraylistado_documentos["Id_Documento"]+'">'+(arraylistado_documentos["estado_documento"] == "Cargado" ? '<strong class="text-success">Cargado</strong>': '<strong class="text-danger">No Cargado</strong>')+'</td>\
                $('#habilitar').removeClass('d-none');
                $('#agregar_documentos').empty();
                $.each(data, function(index, arraylistado_documentos){
                $('#agregar_documentos').append('\
                        <tr>\
                            <td>'+arraylistado_documentos["Nro_documento"]+'</td>\
                            <td style="width: 34% !important;">'+arraylistado_documentos["Nombre_documento"]+'</td>\
                            <td id="estadoDocumento_'+arraylistado_documentos["Id_Documento"]+'"><strong class="text-danger">No Cargado</strong></td>\
                            <td>'+(arraylistado_documentos["Nombre_documento"] === "Otros documentos" ? '\
                                <form id="formulario_documento_'+arraylistado_documentos["Id_Documento"]+'" class="form-inline align-items-center"" method="POST" enctype="multipart/form-data">\
                                    <input type="hidden" name="_token" value="'+token+'">\
                                    <div class="col-12">\
                                        <div class="d-none">\
                                            <input type="text" name="Id_Documento" value="'+arraylistado_documentos["Id_Documento"]+'">\
                                            <input type="text" name="Nombre_documento" value="'+arraylistado_documentos["Nombre_documento"]+'">\
                                            <input type="text" name="EventoID" id="EventoID_'+arraylistado_documentos["Id_Documento"]+'" value="'+id_consultar_documentos_evento+'">\
                                            <input type="text" name="bandera_nombre_otro_doc" value="'+arraylistado_documentos["nombre_Documento"]+'">\
                                        </div>\
                                        <div class="row">\
                                            <div class="input-group">\
                                                <input type="file" class="form-control select-doc" name="listadodocumento" id="listadodocumento_'+arraylistado_documentos["Id_Documento"]+'"\
                                                aria-describedby="Carguedocumentos" aria-label="Upload"'+(arraylistado_documentos["Requerido"] === 'Si' ? 'required' : '')+'>&nbsp;\
                                                <button class="btn btn-info button-doc-select" type="submit" id="CargarDocumento_'+arraylistado_documentos["Id_Documento"]+'">\
                                                    '+(arraylistado_documentos["estado_documento"] == "Cargado" ? 'Cargar' : 'Cargar')+'\
                                                </button>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </form>\
                            ' : '\
                                <form id="formulario_documento_'+arraylistado_documentos["Id_Documento"]+'" method="POST" enctype="multipart/form-data">\
                                    <input type="hidden" name="_token" value="'+token+'">\
                                    <div class="d-none">\
                                        <input type="text" name="Id_Documento" value="'+arraylistado_documentos["Id_Documento"]+'">\
                                        <input type="text" name="Nombre_documento" value="'+arraylistado_documentos["Nombre_documento"]+'">\
                                        <input  type="text" name="EventoID" id="EventoID_'+arraylistado_documentos["Id_Documento"]+'" value="'+id_consultar_documentos_evento+'">\
                                        <input  type="text" name="Id_servicio" id="Id_servicio_'+arraylistado_documentos["Id_Documento"]+'">\
                                    </div>\
                                    <div class="row">\
                                            <div class="input-group">\
                                                <input type="file" class="form-control select-doc" name="listadodocumento" id="listadodocumento_'+arraylistado_documentos["Id_Documento"]+'"\
                                                aria-describedby="Carguedocumentos" aria-label="Upload"'+(arraylistado_documentos["Requerido"] === 'Si' ? 'required' : '')+'>&nbsp;\
                                                <button class="btn btn-info button-doc-select" type="submit" id="CargarDocumento_'+arraylistado_documentos["Id_Documento"]+'">\
                                                    '+(arraylistado_documentos["estado_documento"] == "Cargado" ? 'Cargar' : 'Cargar')+'\
                                                </button>\
                                            </div>\
                                        </div>\
                                </form>\
                            ')+'\
                            </td>\
                            <td class="text-center" style="width: 10% !important;">\
                                <input type="checkbox" class="scales" name="checkdocumentos" id="check_documento_'+arraylistado_documentos["Id_Documento"]+'"\
                                '+(arraylistado_documentos["Requerido"] === "Si" ? 'checked disabled' : 'disabled')+'\
                                >\
                            </td>\
                        </tr>\
                ');
                });
            }
        });

    });

    /* FUNCIONALIDAD DESCARGA DOCUMENTO */
    $(document).on('click', "a[id^='btn_generar_descarga_']", function(){
        var id_documento = $(this).data('id_documento_descargar');
        var nombre_documento = $("#nombre_documento_descarga_"+id_documento).val();
        var extension_documento = $("#extension_documento_descarga_"+id_documento).val();
        var regex = /IdEvento_(.*?)_IdServicio/;
        var resultado = nombre_documento.match(regex);

        if (resultado) {
            var id_evento = resultado[1];
        } else {
            var id_evento = "";
        }
    
        // Crear un enlace temporal para la descarga
        var enlaceDescarga = document.createElement('a');
        enlaceDescarga.href = '/descargar-archivo/'+nombre_documento+'.'+extension_documento+'/'+id_evento;
        enlaceDescarga.target = '_self'; // Abrir en una nueva ventana/tab
        enlaceDescarga.style.display = 'none';
        document.body.appendChild(enlaceDescarga);
    
        // Simular clic en el enlace para iniciar la descarga
        enlaceDescarga.click();
    
        // Eliminar el enlace después de la descarga
        setTimeout(function() {
            document.body.removeChild(enlaceDescarga);
        }, 1000);
    });

    /* ENVÍO DE INFORMACIÓN DEL DOCUMENTO A CARGAR (APLICA PARA LOS DOCUMENTOS EN AMBOS MODALES: SERVICIO Y PROCESO) */
    $(document).on('submit', "form[id^='formulario_documento_']", function(e){
        e.preventDefault();

        var formData = new FormData($(this)[0]);
        var cambio_estado = $(this).parents()[1].childNodes[5].id;
        var input_documento = $(this).parents()[1].childNodes[7].childNodes[1][4].id;

        /* for (var pair of formData.entries()) {
            console.log(pair[0]+ ', ' + pair[1]); 
        } */

        $.ajax({
            url: "/cargarDocumentos",
            type: "post",
            dataType: "json",
            data: formData,
            cache: false,
            contentType: false,
            processData: false  ,
            success:function(response){
                // console.log(response);
                if (response.parametro == "fallo") {
                    if (response.otro != undefined) {
                        $('#listadodocumento_'+response.otro).val('');
                    }else{
                        $('#'+input_documento).val('');
                    }
                    $('.mostrar_fallo').removeClass('d-none');
                    $('.mostrar_fallo').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.mostrar_fallo').addClass('d-none');
                        $('.mostrar_fallo').empty();
                    }, 6000);
                }else if (response.parametro == "exito") {
                    if(response.otro != undefined){
                        $("#estadoDocumentoOtro_"+response.otro).empty();
                        $("#estadoDocumentoOtro_"+response.otro).append('<strong class="text-success">Cargado</strong>');
                        $('#listadodocumento_'+response.otro).prop("disabled", true);
                        $('#CargarDocumento_'+response.otro).prop("disabled", true);
                        $('#habilitar_modal_otro_doc').prop("disabled", true);
                    }else{
                        $("#"+cambio_estado).empty();
                        $("#"+cambio_estado).append('<strong class="text-success">Cargado</strong>');
                    }
                    $('.mostrar_exito').removeClass('d-none');
                    $('.mostrar_exito').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.mostrar_exito').addClass('d-none');
                        $('.mostrar_exito').empty();
                    }, 6000);
                }else{}
                
            }         
        });
    });

    /* CREACIÓN DEL NUEVO PROCESO */
    $(document).on('submit', "form[id^='form_nuevo_proceso_evento_']", function(e){
        e.preventDefault();

        var crear_proceso_evento = $("button[id^='crear_proceso_evento_']");

        if (crear_proceso_evento.length > 0) {
            document.querySelector("button[id^='crear_proceso_evento_']").disabled=true;            
        }

        let nro_evento_nuevo_proceso = $('.renderizar_nuevo_proceso').find("input[id^='nro_evento_nuevo_proceso_']").val();
        let tupla_proceso_escogido = $('.renderizar_nuevo_proceso').find("input[id^='tupla_proceso_evento_']").val();
        let token = $("input[name='_token']").val();

        let nombre_profesional_escogido = $('.renderizar_nuevo_proceso').find("select[id^='nuevo_profesional_nuevo_proceso_'] option:selected").text();

        let datos_nuevo_proceso = {
            "_token": token,
            'nombre_profesional_nuevo_proceso': nombre_profesional_escogido,
            "id_evento": nro_evento_nuevo_proceso,
            "tupla_proceso_escogido": tupla_proceso_escogido
        };

        var formData = new FormData($(this)[0]);
        for (var pair of formData.entries()) {
            var nombres_keys = pair[0];
            datos_nuevo_proceso[nombres_keys] = pair[1];
        }
        $.ajax({
            url: "/crearNuevoProceso",
            type: "post",
            data: datos_nuevo_proceso,
            success:function(response){
                if(response.parametro == "creo_proceso"){
    
                    $("#crear_proceso_evento_"+response.retorno_id_evento).addClass('d-none');
                    $("#actualizar_consulta_nuevo_proceso_"+response.retorno_id_evento).removeClass('d-none');
    
                    $('.mostrar_mensaje_creo_proceso').removeClass('d-none');
                    $('.mostrar_mensaje_creo_proceso').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.mostrar_mensaje_creo_proceso').addClass('d-none');
                        $('.mostrar_mensaje_creo_proceso').empty();
                    }, 9000);
    
                }
            }         
        });

    });

    /* ACTUALIZAR CONSULTA (Botón Actualizar formulario Nuevo Proceso) */
    $(document).on('click', "button[id^='actualizar_consulta_nuevo_proceso_']", function(){

        let token = $("input[name='_token']").val();
        
        let datos_formulario_consulta = {
            "_token": token,
            "parametro": "mantener_datos_busqueda",
            "consulta_nro_identificacion": $("#consultar_nro_identificacion").val(),
            "consulta_id_evento": $("#consultar_id_evento").val()
        };

        $.ajax({
            url: "/mantenerDatosBusquedaEvento",
            type: "post",
            data: datos_formulario_consulta,
            success:function(response){
                if (response.parametro == "creo_variables") {
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            }         
        });
    })

    /* FUNCIONALIDAD DEL BOTÓN NUEVA CONSULTA */
    $('#btn_nueva_consulta').click(function(){
        // location.reload();
        let token = $("input[name='_token']").val();
        let datos_formulario_consulta = {
            "_token": token,
            "parametro": "borrar_datos_busqueda",
        };

        $.ajax({
            url: "/mantenerDatosBusquedaEvento",
            type: "post",
            data: datos_formulario_consulta,
            success:function(response){
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }         
        });
    });

});


