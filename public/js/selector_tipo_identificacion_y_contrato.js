$(document).ready(function(){
    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE TIPOS DE IDENTIFICACIÓN */
    $(".tipo_identificacion_usuario").select2({
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE TIPOS DE COLABORADOR */
    $(".tipo_colaborador").select2({
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE PROCESOS */
    $(".listado_procesos_crear_usuario").select2({
        placeholder: "Seleccione",
        allowClear: false,
        multiple: true
    });

    /* INICIALIZACIÓN DEL SELECT2 DE STATUS DEL USUARIO */
    $(".status_crear_usuario").select2({
        placeholder: "Seleccione",
        allowClear: false,
        data: [
            { id: "Activo", text: 'Activo'},
            { id: "Inactivo", text: 'Inactivo'}
        ]
    });

    /* CARGA LISTADO DE TIPOS DE IDENTIFICACIÓN */
    var datos = {
        '_token': $('input[name=_token]').val()
    };
    $.ajax({
        type:'POST',
        url:'/listartiposidentificacion',
        data: datos,
        success:function(data) {
            $('#tipo_identificacion_usuario').empty();
            $('#tipo_identificacion_usuario').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#tipo_identificacion_usuario').append('<option value="'+data[claves[i]]["tipo_identificacion"]+'">'+data[claves[i]]["tipo_identificacion"]+'</option>');
            }
        }
    });

    /* CARGA LISTADO DE TIPOS DE COLABORADOR */
    var datos = {
        '_token': $('input[name=_token]').val()
    };
    $.ajax({
        type:'POST',
        url:'/listarTiposColaborador',
        data: datos,
        success:function(data) {
            $('#tipo_colaborador').empty();
            $('#tipo_colaborador').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#tipo_colaborador').append('<option value="'+data[claves[i]]["tipo_colaborador"]+'">'+data[claves[i]]["tipo_colaborador"]+'</option>');
            }
        }
    });

    /* CARGA LISTADO DE PROCESOS */
    var datos = {
        '_token': $('input[name=_token]').val(),
        'parametro': 'listado_proceso_nuevo_usuario'
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos,
        success:function(data) {
            $('#listado_procesos_crear_usuario').empty();
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#listado_procesos_crear_usuario').append('<option value="'+data[claves[i]]["Id_proceso"]+'">'+data[claves[i]]["Nombre_proceso"]+'</option>');
            }
        }
    });

    /* TODO LO REFERENTE AL FORMULARIO DE EDICIÓN DE USUARIO. */

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE TIPOS DE CONTRATO */
    $(".editar_tipo_identificacion_usuario").select2();
    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE TIPOS DE CONTRATO */
    $(".editar_tipo_colaborador").select2();
    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE TIPOS DE COLABORADOR */
    $(".editar_status_crear_usuario").select2({
        placeholder: "Seleccione",
        allowClear: false
    });
    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE PROCESOS */
    $(".editar_listado_procesos_crear_usuario").select2({
        placeholder: "Seleccione",
        allowClear: false,
        multiple: true
    });

    // LISTAR DATATABLE CON BOTON DE DESCARGA Y FILTROS POR COLUMNAS
    $('#listado_usuarios thead tr').clone(true).addClass('filters').appendTo('#listado_usuarios thead');
    $('#listado_usuarios').DataTable({
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
                
                if (title !== 'Acciones') {
                    
                    $(cell).html('<input type="text" />');
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
                    title: 'Lista Usuarios',
                    text:'Exportar datos',
                    className: 'btn btn-info',
                    "excelStyles": [                      // Add an excelStyles definition
                                                
                    ],
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8,9]
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

    // SETEAR CAMPOS FORMULARIO EDICIÓN USUARIO.
    $("a[id^='btn_modal_edicion_usuario_']").hover(function(){
        $(".modal-title").empty();
        $(".modal-footer").remove();
        let id_editar_usuario = $(this).data("id_editar_usuario");
        let nombre_editar_usuario = $(this).data("nombre_editar_usuario");

        $(".habilitar_modal_edicion_usuario").attr("id", "modalEdicionUsuario_"+id_editar_usuario);
        $(".modal-title").append("<i class='fa fa-pen mr-2'></i> Edición Usuario: "+nombre_editar_usuario);

        $(".actualizar_usuario").attr("id", "form_actualizar_usuario_"+id_editar_usuario);
        $('#captura_id_usuario').val(id_editar_usuario);

        var consulta = {
            'id_usuario': id_editar_usuario,
            '_token': $('input[name=_token]').val()
        };
        $.ajax({
            type:'POST',
            url: $('#ruta_ed_evento').val(),
            data: consulta,
            success:function(data) {
                // Añadir el nombre del usuario.
                $("#editar_nombre_usuario").empty();
                $("#editar_nombre_usuario").val(data[0]["name"]);
                // Añadir la opción de tipo de identificación para el selector de tipos de identificación en el formulario de edición
                $("#editar_tipo_identificacion_usuario").empty();
                $("#editar_tipo_identificacion_usuario").append('<option value="'+data[0]["tipo_identificacion"]+'">'+data[0]["tipo_identificacion"]+'</option>')
                // Añadir el numero de identificacion.
                $("#editar_nro_identificacion_usuario").empty();
                $("#editar_nro_identificacion_usuario").val(data[0]["nro_identificacion"]);
                // Añadir el E-MAIL.
                $("#editar_correo_contacto_usuario").empty();
                $("#editar_correo_contacto_usuario").val(data[0]["email_contacto"]);
                // Añadir la opción de tipo de identificación para el selector de tipos de colaborador en el formulario de edición
                $("#editar_tipo_colaborador").empty();
                $("#editar_tipo_colaborador").append('<option value="'+data[0]["tipo_colaborador"]+'">'+data[0]["tipo_colaborador"]+'</option>');
                // Añadir la empresa.
                $("#editar_empresa_usuario").empty();
                $("#editar_empresa_usuario").val(data[0]["empresa"]);
                // Añadir el cargo.
                $("#editar_cargo_usuario").empty();
                $("#editar_cargo_usuario").val(data[0]["cargo"]);
                // Añadir el numero de contacto.
                $("#editar_telefono_contacto_usuario").empty();
                $("#editar_telefono_contacto_usuario").val(data[0]["telefono_contacto"]);
                // Añadir el correo por usuario
                $("#editar_correo_usuario").empty();
                $("#editar_correo_usuario").val(data[0]["email"]);
                // Añadir ids de procesos
                $("#string_id_procesos").val(data[0]["id_procesos_usuario"]);
                // Añadir el status
                $("#editar_status_crear_usuario").empty();
                if (data[0]["estado"] == "Activo") {
                    $("#editar_status_crear_usuario").append('<option value="'+data[0]["estado"]+'" selected>'+data[0]["estado"]+'</option>');
                    $("#editar_status_crear_usuario").append('<option value="Inactivo">Inactivo</option>');
                }else{
                    $("#editar_status_crear_usuario").append('<option value="'+data[0]["estado"]+'" selected>'+data[0]["estado"]+'</option>');
                    $("#editar_status_crear_usuario").append('<option value="Activo">Activo</option>');
                }
               
            }
        });
        
        setTimeout(() => {
            /* CARGA LISTADO DE TIPOS DE IDENTIFICACIÓN EDICION USUARIO */
            var datos_tipo_ident = {
                'tipo_identificacion': $('#editar_tipo_identificacion_usuario').val(),
                '_token': $('input[name=_token]').val()
            };
            $.ajax({
                type:'POST',
                url:'/listadoTiposIdentificacionEditar',
                data: datos_tipo_ident,
                success:function(data) {
                    let claves = Object.keys(data);
                    for (let i = 0; i < claves.length; i++) {
                        $('#editar_tipo_identificacion_usuario').append('<option value="'+data[claves[i]]["tipo_identificacion"]+'">'+data[claves[i]]["tipo_identificacion"]+'</option>');
                    }
                }
            });

            /* CARGA LISTADO DE TIPOS DE IDENTIFICACIÓN EDICION USUARIO */
            var datos_tipos_colaborador = {
                'tipo_colaborador': $('#editar_tipo_colaborador').val(),
                '_token': $('input[name=_token]').val()
            };
            $.ajax({
                type:'POST',
                url:'/listadotiposColaboradorEditar',
                data: datos_tipos_colaborador,
                success:function(data) {
                    let claves = Object.keys(data);
                    for (let i = 0; i < claves.length; i++) {
                        $('#editar_tipo_colaborador').append('<option value="'+data[claves[i]]["tipo_colaborador"]+'">'+data[claves[i]]["tipo_colaborador"]+'</option>');
                    }
                }
            });

            /* CARGA LISTADO DE PROCESOS */
            $('#editar_listado_procesos_crear_usuario').empty(); 
            if ($("#string_id_procesos").val() != "") {
                var datos_procesos_usuario = {
                    '_token': $('input[name=_token]').val(),
                    'id_procesos': $("#string_id_procesos").val(),
                    'parametro': 'listado_proceso_edicion_usuario'
                };

                $.ajax({
                    type:'POST',
                    url:'/cargarselectores',
                    data: datos_procesos_usuario,
                    success:function(data) {
                        // console.log(data);
                        let claves = Object.keys(data);
                        for (let i = 0; i < claves.length; i++) {
                            if (data[claves[i]]['seleccionado'] == "si") {
                                $('#editar_listado_procesos_crear_usuario').append('<option value="'+data[claves[i]]["Id_proceso"]+'" selected>'+data[claves[i]]["Nombre_proceso"]+'</option>');
                            }else{
                                $('#editar_listado_procesos_crear_usuario').append('<option value="'+data[claves[i]]["Id_proceso"]+'">'+data[claves[i]]["Nombre_proceso"]+'</option>');
                            }
                        }
                    }
                });
            }else{
                var datos = {
                    '_token': $('input[name=_token]').val(),
                    'parametro': 'listado_proceso'
                };
                $.ajax({
                    type:'POST',
                    url:'/cargarselectores',
                    data: datos,
                    success:function(data) {
                        let claves = Object.keys(data);
                        for (let i = 0; i < claves.length; i++) {
                            $('#editar_listado_procesos_crear_usuario').append('<option value="'+data[claves[i]]["Id_proceso"]+'">'+data[claves[i]]["Nombre_proceso"]+'</option>');
                        }
                    }
                });
            }
        }, 200);
        
    });
    

    /* ENVÍO FORMULARIO EDICIÓN USUARIO PARA ACTUALIZAR INFORMACIÓN */
    $(document).on('submit', "form[id^='form_actualizar_usuario_']", function(e){
        e.preventDefault();
        
        var formData = new FormData($(this)[0]);
        // for (var pair of formData.entries()) {
        //     console.log(pair[0]+" - "+ pair[1]);
        // }

        $.ajax({
            url: $('#ruta_guardar_ed_evento').val(),
            type: "post",
            dataType: "json",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success:function(response){
                
                $("#btn_actualizar_consulta").removeClass('d-none');
                if (response.parametro == "exito") {
                    $("#mostrar_mensaje_actualizacion").removeClass('d-none');
                    $("#mostrar_mensaje_actualizacion").addClass('alert-success');
                    $("#mostrar_mensaje_actualizacion").append('<strong>'+response.mensaje+'</strong>');

                    setTimeout(() => {
                        $("#mostrar_mensaje_actualizacion").addClass('d-none');
                        $("#mostrar_mensaje_actualizacion").removeClass('alert-success');
                        $("#mostrar_mensaje_actualizacion").empty();
                    }, 9000);

                } else {
                    $("#mostrar_mensaje_actualizacion").removeClass('d-none');
                    $("#mostrar_mensaje_actualizacion").addClass('alert-danger');
                    $("#mostrar_mensaje_actualizacion").append('<strong>No se pudo actualizar.</strong>');

                    setTimeout(() => {
                        $("#mostrar_mensaje_actualizacion").addClass('d-none');
                        $("#mostrar_mensaje_actualizacion").removeClass('alert-success');
                        $("#mostrar_mensaje_actualizacion").empty();
                    }, 9000);
                }
            }         
        });

    });

    /* FUNCIONALIDAD ACTUALIZAR PÁGINA DE CONSULTA DE USUARIOS */
    $("#btn_actualizar_consulta").click(function(){
        location.reload();
    });
    
    $('#listado_usuarios').on('click','.editar_usuario', function() {
        // SETEAR CAMPOS FORMULARIO EDICIÓN USUARIO.
        $(".modal-title").empty();
        $(".modal-footer").remove();
        let id_editar_usuario = $(this).data("id_editar_usuario");
        let nombre_editar_usuario = $(this).data("nombre_editar_usuario");

        $(".habilitar_modal_edicion_usuario").attr("id", "modalEdicionUsuario_"+id_editar_usuario);
        $(".modal-title").append("<i class='fa fa-pen mr-2'></i> Edición Usuario: "+nombre_editar_usuario);

        $(".actualizar_usuario").attr("id", "form_actualizar_usuario_"+id_editar_usuario);
        $('#captura_id_usuario').val(id_editar_usuario);

        var consulta = {
            'id_usuario': id_editar_usuario,
            '_token': $('input[name=_token]').val()
        };
        $.ajax({
            type:'POST',
            url: $('#ruta_ed_evento').val(),
            data: consulta,
            success:function(data) {
                // Añadir el nombre del usuario.
                $("#editar_nombre_usuario").empty();
                $("#editar_nombre_usuario").val(data[0]["name"]);
                // Añadir la opción de tipo de identificación para el selector de tipos de identificación en el formulario de edición
                $("#editar_tipo_identificacion_usuario").empty();
                $("#editar_tipo_identificacion_usuario").append('<option value="'+data[0]["tipo_identificacion"]+'">'+data[0]["tipo_identificacion"]+'</option>')
                // Añadir el numero de identificacion.
                $("#editar_nro_identificacion_usuario").empty();
                $("#editar_nro_identificacion_usuario").val(data[0]["nro_identificacion"]);
                // Añadir el E-MAIL.
                $("#editar_correo_contacto_usuario").empty();
                $("#editar_correo_contacto_usuario").val(data[0]["email_contacto"]);
                // Añadir la opción de tipo de identificación para el selector de tipos de colaborador en el formulario de edición
                $("#editar_tipo_colaborador").empty();
                $("#editar_tipo_colaborador").append('<option value="'+data[0]["tipo_colaborador"]+'">'+data[0]["tipo_colaborador"]+'</option>');
                // Añadir la empresa.
                $("#editar_empresa_usuario").empty();
                $("#editar_empresa_usuario").val(data[0]["empresa"]);
                // Añadir el cargo.
                $("#editar_cargo_usuario").empty();
                $("#editar_cargo_usuario").val(data[0]["cargo"]);
                // Añadir el numero de contacto.
                $("#editar_telefono_contacto_usuario").empty();
                $("#editar_telefono_contacto_usuario").val(data[0]["telefono_contacto"]);
                // Añadir el correo por usuario
                $("#editar_correo_usuario").empty();
                $("#editar_correo_usuario").val(data[0]["email"]);
                // Añadir ids de procesos
                $("#string_id_procesos").val(data[0]["id_procesos_usuario"]);
                // Añadir el status
                $("#editar_status_crear_usuario").empty();
                if (data[0]["estado"] == "Activo") {
                    $("#editar_status_crear_usuario").append('<option value="'+data[0]["estado"]+'" selected>'+data[0]["estado"]+'</option>');
                    $("#editar_status_crear_usuario").append('<option value="Inactivo">Inactivo</option>');
                }else{
                    $("#editar_status_crear_usuario").append('<option value="'+data[0]["estado"]+'" selected>'+data[0]["estado"]+'</option>');
                    $("#editar_status_crear_usuario").append('<option value="Activo">Activo</option>');
                }
               
            }
        });
        
        setTimeout(() => {
            /* CARGA LISTADO DE TIPOS DE IDENTIFICACIÓN EDICION USUARIO */
            var datos_tipo_ident = {
                'tipo_identificacion': $('#editar_tipo_identificacion_usuario').val(),
                '_token': $('input[name=_token]').val()
            };
            $.ajax({
                type:'POST',
                url:'/listadoTiposIdentificacionEditar',
                data: datos_tipo_ident,
                success:function(data) {
                    let claves = Object.keys(data);
                    for (let i = 0; i < claves.length; i++) {
                        $('#editar_tipo_identificacion_usuario').append('<option value="'+data[claves[i]]["tipo_identificacion"]+'">'+data[claves[i]]["tipo_identificacion"]+'</option>');
                    }
                }
            });

            /* CARGA LISTADO DE TIPOS DE IDENTIFICACIÓN EDICION USUARIO */
            var datos_tipos_colaborador = {
                'tipo_colaborador': $('#editar_tipo_colaborador').val(),
                '_token': $('input[name=_token]').val()
            };
            $.ajax({
                type:'POST',
                url:'/listadotiposColaboradorEditar',
                data: datos_tipos_colaborador,
                success:function(data) {
                    let claves = Object.keys(data);
                    for (let i = 0; i < claves.length; i++) {
                        $('#editar_tipo_colaborador').append('<option value="'+data[claves[i]]["tipo_colaborador"]+'">'+data[claves[i]]["tipo_colaborador"]+'</option>');
                    }
                }
            });

            /* CARGA LISTADO DE PROCESOS */
            $('#editar_listado_procesos_crear_usuario').empty(); 
            if ($("#string_id_procesos").val() != "") {
                var datos_procesos_usuario = {
                    '_token': $('input[name=_token]').val(),
                    'id_procesos': $("#string_id_procesos").val(),
                    'parametro': 'listado_proceso_edicion_usuario'
                };

                $.ajax({
                    type:'POST',
                    url:'/cargarselectores',
                    data: datos_procesos_usuario,
                    success:function(data) {
                        // console.log(data);
                        let claves = Object.keys(data);
                        for (let i = 0; i < claves.length; i++) {
                            if (data[claves[i]]['seleccionado'] == "si") {
                                $('#editar_listado_procesos_crear_usuario').append('<option value="'+data[claves[i]]["Id_proceso"]+'" selected>'+data[claves[i]]["Nombre_proceso"]+'</option>');
                            }else{
                                $('#editar_listado_procesos_crear_usuario').append('<option value="'+data[claves[i]]["Id_proceso"]+'">'+data[claves[i]]["Nombre_proceso"]+'</option>');
                            }
                        }
                    }
                });
            }else{
                var datos = {
                    '_token': $('input[name=_token]').val(),
                    'parametro': 'listado_proceso'
                };
                $.ajax({
                    type:'POST',
                    url:'/cargarselectores',
                    data: datos,
                    success:function(data) {
                        let claves = Object.keys(data);
                        for (let i = 0; i < claves.length; i++) {
                            $('#editar_listado_procesos_crear_usuario').append('<option value="'+data[claves[i]]["Id_proceso"]+'">'+data[claves[i]]["Nombre_proceso"]+'</option>');
                        }
                    }
                });
            }
        }, 200);
    });
 
});