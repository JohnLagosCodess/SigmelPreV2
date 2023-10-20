$(document).ready(function(){

    // Inicialización select2 estado
    $(".estado").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    // Inicialización select2 estado edicion
    $(".estado_edicion").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    // Inicialización select2 status
    $(".status").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    let token = $('input[name=_token]').val();

    //Listado de tipo entidad
    let datos_lista_estado = {
        '_token': token,
        'parametro':"lista_estados"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresAcciones',
        data: datos_lista_estado,
        success:function(data) {
            //console.log(data);
            $('#estado').empty();
            $('#estado').append('<option></option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#estado').append('<option value="'+data[claves[i]]["Id_Parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });

    // Crear una nueva Acción
    $(document).on('submit', "form[id^='form_nueva_accion']", function(e){
        e.preventDefault();
        let token = $("input[name='_token']").val();

        var datos_nueva_accion = {
            '_token': token,
            'Estado_accion': $("#estado").val(),
            'Accion': $("#accion").val(),
            'Descripcion_accion': $("#descrip_accion").val(),
            'Status_accion': $("#status").val(),
            'F_creacion_accion': $("#fecha_creacion").val(),
        };

        $.ajax({
            type:'POST',
            url:'/CrearNuevaAccion',
            data: datos_nueva_accion,
            success:function(response){
                if (response.parametro == "accion_creada") {
                    $("#resultado_insercion_nueva_accion").empty();
                    $("#resultado_insercion_nueva_accion").removeClass('d-none');
                    $("#resultado_insercion_nueva_accion").addClass('alert-success');
                    $("#resultado_insercion_nueva_accion").append('<strong>'+response.mensaje+'</strong>');
                    $("#btn_nueva_accion").prop("disabled", true);

                    setTimeout(() => {
                        $("#resultado_insercion_nueva_accion").addClass('d-none');
                        $("#resultado_insercion_nueva_accion").removeClass('alert-success');
                        $("#resultado_insercion_nueva_accion").empty();
                        $("#btn_nueva_accion").prop("disabled", false);
                        location.reload();
                    }, 3000);
                }              
            }
        });

    });

    // TABLA PARA MOSTRAR EL LISTADO DE ACCIONES
    $('#listado_acciones thead tr').clone(true).addClass('filters').appendTo('#listado_acciones thead');
    $('#listado_acciones').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        pageLength: 5,
        "destroy": true,
        "order": [[1, 'asc']],
        initComplete: function () {
            var api = this.api();
                // For each column
            api.columns().eq(0).each(function (colIdx) {
                // Set the header cell to contain the input element
                var cell = $('.filters th').eq(
                    $(api.column(colIdx).header()).index()
                );
                
                var title = $(cell).text();
                
                if (title !== 'Detalle') {
                    
                    $(cell).html('<input type="text" style="width:100%;"/>');
                    $('input',$('.filters th').eq($(api.column(colIdx).header()).index())).off('keyup change')
                    .on('change', function (e) {
                        // Get the search value
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
                    title: 'Lista de Acciones',
                    text:'Exportar datos',
                    className: 'btn btn-info',
                    "excelStyles": [                      // Add an excelStyles definition
                                                
                    ],
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8]
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

    // SETEAR CAMPOS DEL FORMULARIO DE EDICION DE ACCION
    $(document).on('mouseover', "a[id^='btn_modal_edicion_accion_']", function(){
        // borrar el modal title para insertar un texto en especifico y eliminar el modal footer
        $(".modal-title").empty();
        $(".modal-footer").remove();

        // Captura del id de la accion y el nombre de la misma.
        let id_editar_accion = $(this).data("id_editar_accion");
        let nombre_editar_accion = $(this).data("nombre_editar_accion");

        // generar el datatarget especifico para cuando se abra un modal de edicion de accion
        $(".habilitar_modal_edicion_accion").attr("id", "modalEdicionAccion_"+id_editar_accion);
        // colocar el titulo del modal
        $(".modal-title").append("<i class='fa fa-pen mr-2'></i> Edición Acción : "+nombre_editar_accion);

        // añadir el atributo id para cuando se necesite enviar los datos de la accion a editar
        $(".actualizar_accion").attr("id", "form_actualizar_accion_"+id_editar_accion);
        // Enviar el id de la accion a editar para temas de actualizacion en el controlador.
        $('#id_accion_editar').val(id_editar_accion);

        // Eliminar el contenido de todo el formulario de edcion de accion antes de entrar en el
        $('#estado_edicion').empty();
        $("#accion").empty();
        $("#descrip_accion").empty();
        $("#status").empty();
        $("#fecha_creacion").empty();

        // SETEAR CAMPOS DEL FORMULARIO DE EDICION DE ACCION
        var consultar_info_accion = {
            'id_accion_editar': id_editar_accion,
            '_token': $('input[name=_token]').val()
        };

        $.ajax({
        
            type:'POST',
            url: $('#traer_datos_accion').val(),
            data: consultar_info_accion,
            success:function(data) {
               
               let datos_lista_estado_edicion = {
                '_token': $('input[name=_token]').val(),
                'parametro':"lista_estados_edicion"
                };
                $.ajax({
                    type:'POST',
                    url:'/selectoresAcciones',
                    data: datos_lista_estado_edicion,
                    success:function(data_edicion) {
                        
                        $('#estado_edicion').empty();
                        $('#estado_edicion').append('<option></option>');
                        let claves = Object.keys(data_edicion);
                        for (let i = 0; i < claves.length; i++) {
                            if (data_edicion[claves[i]]["Id_Parametro"] == data[0]["Estado_accion"]) {
                                $('#estado_edicion').append('<option value="'+data_edicion[claves[i]]["Id_Parametro"]+'" selected>'+data_edicion[claves[i]]["Nombre_parametro"]+'</option>');
                            } else {
                                $('#estado_edicion').append('<option value="'+data_edicion[claves[i]]["Id_Parametro"]+'">'+data_edicion[claves[i]]["Nombre_parametro"]+'</option>');
                            }
                        }
                    }
                });
               
               $("#accion").val(data[0]["Accion"]);
               $("#descrip_accion").val(data[0]["Descripcion_accion"]);
               $("#fecha_creacion").val(data[0]["F_creacion_accion"]);

               $("#status").empty();
               $("#status").append('<option></option>');
               if (data[0]["Status_accion"] == "Activo") {
                    $("#status").append('<option value="Activo" selected>Activo</option> <option value="Inactivo">Inactivo</option>');
                }else{
                   $("#status").append('<option value="Activo">Activo</option> <option value="Inactivo" selected>Inactivo</option>');
               }


            }

        });

    });

    /* ENVÍO FORMULARIO EDICIÓN ACCIÓN PARA ACTUALIZAR INFORMACIÓN */
    $(document).on('submit', "form[id^='form_actualizar_accion_']", function(e){
        e.preventDefault();
        let token = $("input[name='_token']").val();

        var datos_editar_accion = {
            '_token': token,
            'id_accion': $("#id_accion_editar").val(),
            'Estado_accion': $("#estado_edicion").val(),
            'Accion': $("#accion").val(),
            'Descripcion_accion': $("#descrip_accion").val(),
            'Status_accion': $("#status").val(),
            'F_creacion_accion': $("#fecha_creacion").val(),
        };

        $.ajax({
            type:'POST',
            url:'/ActualizarAccion',
            data: datos_editar_accion,
            success:function(response){
                if (response.parametro == "accion_editada") {
                    $("#resultado_insercion_edicion_accion").empty();
                    $("#resultado_insercion_edicion_accion").removeClass('d-none');
                    $("#resultado_insercion_edicion_accion").addClass('alert-success');
                    $("#resultado_insercion_edicion_accion").append('<strong>'+response.mensaje+'</strong>');
                    $("#btn_actualizar_accion").prop("disabled", true);

                    setTimeout(() => {
                        $("#resultado_insercion_edicion_accion").addClass('d-none');
                        $("#resultado_insercion_edicion_accion").removeClass('alert-success');
                        $("#resultado_insercion_edicion_accion").empty();
                        $("#btn_actualizar_accion").prop("disabled", false);
                        location.reload();
                    }, 3000);
                }              
            }
        });

    });

});