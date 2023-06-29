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
                                IrEvento = '<span class="d-none">'+data[i]["ID_evento"]+'</span><form id="form_editar_evento_'+data[i]["ID_evento"]+'" action="" method="POST">'+
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
                                            <form id="form_nuevo_servicio_evento_'+data[i]['ID_evento']+'" method="POST">\
                                                <div class="modal-body">\
                                                    <div class="row">\
                                                        <div class="col-12">\
                                                            <div class="form-group row">\
                                                                <label for="" class="col-sm-3 col-form-label">Fecha de Radicación <span style="color:red;">(*)</span></label>\
                                                                <div class="col-sm-9">\
                                                                    <input type="date" class="form-control" name="nueva_fecha_radicacion" id="nueva_fecha_radicacion" required>\
                                                                </div>\
                                                            </div>\
                                                            <div class="form-group row">\
                                                                <label for="" class="col-sm-3 col-form-label">Proceso</label>\
                                                                <div class="col-sm-9">\
                                                                    <input type="hidden" class="form-control" name="id_proceso_actual" id="id_proceso_actual_'+data[i]["ID_evento"]+'" value="'+data[i]["Id_proceso"]+'">\
                                                                    <input type="text" readonly class="form-control" name="nuevo_proceso" id="nuevo_proceso" value="'+data[i]["Nombre_proceso"]+'">\
                                                                </div>\
                                                            </div>\
                                                            <div class="form-group row">\
                                                                <label for="" class="col-sm-3 col-form-label">Servicio <span style="color:red;">(*)</span></label>\
                                                                <div class="col-sm-9">\
                                                                    <input type="hidden" class="form-control" name="id_servicio_actual" id="id_servicio_actual_'+data[i]["ID_evento"]+'" value="'+data[i]["Id_Servicio"]+'">\
                                                                    <select class="nuevo_servicio_'+data[i]['ID_evento']+' custom-select" name="nuevo_servicio" id="nuevo_servicio_'+data[i]['ID_evento']+'" style="width:100%;" requierd></select>\
                                                                </div>\
                                                            </div>\
                                                            <div class="form-group row">\
                                                                <label for="" class="col-sm-3 col-form-label">Fecha de acción <span style="color:red;">(*)</span></label>\
                                                                <div class="col-sm-9">\
                                                                    <input type="date" class="form-control" name="nueva_fecha_accion" id="nueva_fecha_accion_'+data[i]['ID_evento']+'" required>\
                                                                </div>\
                                                            </div>\
                                                            <div class="form-group row">\
                                                                <label for="" class="col-sm-3 col-form-label">Acción <span style="color:red;">(*)</span></label>\
                                                                <div class="col-sm-9">\
                                                                    <select class="nueva_accion_'+data[i]['ID_evento']+' custom-select" name="nueva_accion" id="nueva_accion_'+data[i]['ID_evento']+'" style="width:100%;" requierd></select>\
                                                                </div>\
                                                            </div>\
                                                            <div class="form-group row">\
                                                                <label for="" class="col-sm-3 col-form-label">Profesional</label>\
                                                                <div class="col-sm-9">\
                                                                    <input type="text" readonly class="form-control" name="nuevo_profesional" id="nuevo_profesional" value="Nombre Profesional">\
                                                                </div>\
                                                            </div>\
                                                            <div class="form-group row">\
                                                                <label for="" class="col-sm-3 col-form-label">Descripción</label>\
                                                                <div class="col-sm-9">\
                                                                    <textarea class="form-control" name="nueva_descripcion" id="nueva_descripcion" rows="2"></textarea>\
                                                                </div>\
                                                            </div>\
                                                            <div class="form-group row">\
                                                                <label for="" class="col-sm-3 col-form-label">Fecha alerta <span style="color:red;">(*)</span></label>\
                                                                <div class="col-sm-9">\
                                                                    <input type="date" class="form-control" name="nueva_fecha_alerta" id="nueva_fecha_alerta" required>\
                                                                </div>\
                                                            </div>\
                                                        </div>\
                                                    </div>\
                                                </div>\
                                                <div class="modal-footer">\
                                                    <input type="hidden" class="form-control" id="nro_evento_'+data[i]["ID_evento"]+'" value="'+data[i]["ID_evento"]+'">\
                                                    <a href="javascript:void(0);" class="text-dark text-md mr-auto" data-toggle="modal" data-target="#modalListaDocumentos" id="cargue_documentos_evento_'+data[i]['ID_evento']+'">\
                                                    <i class="far fa-file text-info"></i> <strong>Cargue Documentos</strong></a>\
                                                    <input type="submit" class="form-control btn btn-info" id="crear_servicio_evento_'+data[i]['ID_evento']+'" value="Crear">\
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>\
                                                </div>\
                                            </form>\
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
        setTimeout(() => {
            var botonBuscar = $('#contenedorTable').parents();
            var contenedorBotonBuscar = botonBuscar[0].childNodes[5].childNodes[1].childNodes[1].childNodes[0].classList[0];
            //console.log(contenedorBotonBuscar);        
            $('.'+contenedorBotonBuscar).addClass('d-none');
        }, 5000);

        
        
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
                            columns: [ 0,1,2,3,4,5,6,7,8,9,10,11,12]
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
    $('#btn_expor_datos').click(function () {
        var infobtnExcel = $(this).parents();
        var selectorbtnExcel = infobtnExcel[4].childNodes[13].childNodes[1].childNodes[5].childNodes[1].childNodes[1].childNodes[0].childNodes[0].classList[0];

        $('.'+selectorbtnExcel).click();

        //console.log(selectorbtnExcel);
    });
    
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

        /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE ACCIONES */
        $("select[id^='nueva_accion_']").select2({
            placeholder: "Seleccione una opción",
            allowClear: false
        });

    });

    /* CARGUE DE INFORMACIÓN DEL SELECTOR DE Servicio */
    $(document).on('mouseover', "span[aria-labelledby^='select2-nuevo_servicio_']", function(){
        var info_formulario_servicio = $(this).parents();
        
        let id_proceso_actual = info_formulario_servicio[5].childNodes[1].childNodes[3].childNodes[3].childNodes[1].value;
        let id_servicio_actual = info_formulario_servicio[5].childNodes[1].childNodes[5].childNodes[3].childNodes[1].value;
        let nro_evento = info_formulario_servicio[7][10].value;

        let token = $("input[name='_token']").val();
        
        let datos_listado_servicios = {
            '_token': token,
            'parametro' : "listado_servicios_nuevo_servicio",
            'id_proceso_actual' : id_proceso_actual,
            'id_servicio_actual': id_servicio_actual,
            'nro_evento': nro_evento
        };

        $.ajax({
            type:'POST',
            url:'/cargarselectores',
            data: datos_listado_servicios,
            success:function(data) {
                var selector_nuevo_servicio = info_formulario_servicio[3].childNodes[3].childNodes[3].id;
                $("#"+selector_nuevo_servicio).empty();
                $("#"+selector_nuevo_servicio).append('<option value="" selected>Seleccione</option>');

                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    
                    $("#"+selector_nuevo_servicio).append('<option value="'+data[claves[i]]["Id_Servicio"]+'">'+data[claves[i]]["Nombre_servicio"]+'</option>');
                }
            }
        });
    });

    /* CARGUE DE INFORMACIÓN DEL SELECTOR DE Acción */
    $(document).on('mouseover', "span[aria-labelledby^='select2-nueva_accion_']", function(){
        var info_formulario_accion = $(this).parents();
        
        let token = $("input[name='_token']").val();
        let datos_listado_servicios = {
            '_token': token,
            'parametro' : "listado_accion_nuevo_servicio",
        };

        $.ajax({
            type:'POST',
            url:'/cargarselectores',
            data: datos_listado_servicios,
            success:function(data) {
                var selector_nuevo_accion = info_formulario_accion[2].childNodes[1].id;
                $("#"+selector_nuevo_accion).empty();
                $("#"+selector_nuevo_accion).append('<option value="" selected>Seleccione</option>');

                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#"+selector_nuevo_accion).append('<option value="'+data[claves[i]]["Id_Accion"]+'" selected>'+data[claves[i]]["Nombre_accion"]+'</option>');
                }
            }
        });

    });

    /* CAPTURA ID EVENTO PARA PINTAR EL LISTADO DE DOCUMENTOS PERTENENCIENTES A EL */
    $(document).on('click', "a[id^='cargue_documentos_evento_']", function(){
        let token = $("input[name='_token']").val();
        var info_form_evento = $(this).parents();
        var selector_nro_evento = info_form_evento[0].childNodes[1].id;
        var id_consultar_documentos_evento = $("input[id^='"+selector_nro_evento+"']").val();

        let datos_a_consultar = {
            '_token': token,
            'id_evento':id_consultar_documentos_evento
        }
        
        $.ajax({
            type:'POST',
            url:'/cargueDocumentosXEvento',
            data: datos_a_consultar,
            success:function(data) {
                
                $('#habilitar').removeClass('d-none');
                $('#agregar_documentos').empty();
                $.each(data, function(index, arraylistado_documentos){
                   $('#agregar_documentos').append('\
                        <tr>\
                            <td>'+arraylistado_documentos["Nro_documento"]+'</td>\
                            <td style="width: 34% !important;">'+arraylistado_documentos["Nombre_documento"]+'</td>\
                            <td id="estadoDocumento_'+arraylistado_documentos["Id_Documento"]+'">'+(arraylistado_documentos["estado_documento"] == "Cargado" ? '<strong class="text-success">Cargado</strong>': '<strong class="text-danger">No Cargado</strong>')+'</td>\
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
                                            <p>'+(arraylistado_documentos["nombre_Documento"] != "" ? arraylistado_documentos["nombre_Documento"] : '')+'</p>\
                                            <div class="input-group">\
                                                <input type="file" class="form-control select-doc" name="listadodocumento" id="listadodocumento_'+arraylistado_documentos["Id_Documento"]+'"\
                                                aria-describedby="Carguedocumentos" aria-label="Upload"'+(arraylistado_documentos["Requerido"] === 'Si' ? 'required' : '')+'>&nbsp;\
                                                <button class="btn btn-info button-doc-select" type="submit" id="CargarDocumento_'+arraylistado_documentos["Id_Documento"]+'">\
                                                    '+(arraylistado_documentos["estado_documento"] == "Cargado" ? 'Actualizar' : 'Cargar')+'\
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
                                    </div>\
                                    <div class="row">\
                                            <p>'+(arraylistado_documentos["nombre_Documento"] != "" ? arraylistado_documentos["nombre_Documento"] : '')+'</p>\
                                            <div class="input-group">\
                                                <input type="file" class="form-control select-doc" name="listadodocumento" id="listadodocumento_'+arraylistado_documentos["Id_Documento"]+'"\
                                                aria-describedby="Carguedocumentos" aria-label="Upload"'+(arraylistado_documentos["Requerido"] === 'Si' ? 'required' : '')+'>&nbsp;\
                                                <button class="btn btn-info button-doc-select" type="submit" id="CargarDocumento_'+arraylistado_documentos["Id_Documento"]+'">\
                                                    '+(arraylistado_documentos["estado_documento"] == "Cargado" ? 'Actualizar' : 'Cargar')+'\
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

    /* Envío de Información del Documento a Cargar */
    $(document).on('submit', "form[id^='formulario_documento_']", function(e){

        e.preventDefault();
        var formData = new FormData($(this)[0]);
        var cambio_estado = $(this).parents()[1].childNodes[5].id;
        var input_documento = $(this).parents()[1].childNodes[7].childNodes[1][4].id;

        /* console.log(cambio_estado);
        console.log(input_documento); */

        /* for (var pair of formData.entries()) {
            console.log(pair[0]+ ', ' + pair[1]); 
        } */

        // Enviamos los datos para validar y guardar el docmuento correspondiente
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

});


