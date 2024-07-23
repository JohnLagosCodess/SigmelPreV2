$(document).ready(function(){
    /* INICIALIZACIÓN DEL SELECT DE LISTADO DE TIPO ENTIDAD */
    $(".tipo_entidad").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    $(".entidad_departamento").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    $(".entidad_ciudad").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    $(".entidad_medio_noti").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    $(".estado_entidad").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    $(".editar_tipo_entidad").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    $(".editar_entidad_departamento").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });
    
    $(".editar_entidad_ciudad").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });
    
    $(".editar_entidad_medio_noti").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    $(".editar_estado_entidad").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });


    // llenado de selectores
    let token = $('input[name=_token]').val();

    //Listado de tipo entidad
    let datos_lista_tipo_entidad = {
        '_token': token,
        'parametro':"lista_tipo_entidad"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresEntidad',
        data: datos_lista_tipo_entidad,
        success:function(data) {
            // console.log("Esto es data : ",data);
            $('#tipo_entidad').empty();
            $('#tipo_entidad').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#tipo_entidad').append('<option value="'+data[claves[i]]["Id_Entidad"]+'">'+data[claves[i]]["Tipo_Entidad"]+'</option>');
            }
        }
    });

    //Listado de departamento
    let datos_lista_departamento_entidad = {
        '_token': token,
        'parametro':"lista_departamento_entidad"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresEntidad',
        data: datos_lista_departamento_entidad,
        success:function(data) {
            //console.log(data);
            $('#entidad_departamento').empty();
            $('#entidad_departamento').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#entidad_departamento').append('<option value="'+data[claves[i]]["Id_departamento"]+'">'+data[claves[i]]["Nombre_departamento"]+'</option>');
            }
        }
    });
    // Listado Municipio
    $('#entidad_departamento').change( function(){
        $('#entidad_ciudad').prop('disabled', false);
        let id_departamento_entidad = $('#entidad_departamento').val();
        let datos_municipio_entidad = {
            '_token': token,
            'parametro' : "lista_municipios_entidad",
            'id_departamento_entidad': id_departamento_entidad
        };

        $.ajax({
            type:'POST',
            url:'/selectoresEntidad',
            data: datos_municipio_entidad,
            success:function(data) {
                //console.log(data);
                $('#entidad_ciudad').empty();
                $('#entidad_ciudad').append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $('#entidad_ciudad').append('<option value="'+data[claves[i]]["Id_municipios"]+'">'+data[claves[i]]["Nombre_municipio"]+'</option>');
                }
            }
        });
    });

    //Listado de medio notificacion
    let datos_lista_medio_noti = {
        '_token': token,
        'parametro':"lista_medio_noti"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresEntidad',
        data: datos_lista_medio_noti,
        success:function(data) {
            //console.log(data);
            $('#entidad_medio_noti').empty();
            $('#entidad_medio_noti').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#entidad_medio_noti').append('<option value="'+data[claves[i]]["Id_Parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });

    /* Validación opción Otro/¿Cual? del selector Tipo Entidad */
    $('#tipo_entidad').change(function(){
        let opt_otra_entidad = $('#tipo_entidad option:selected').text();
        if (opt_otra_entidad === "Otro/¿Cual?") {
            $(".columna_otro_entidad").removeClass('d-none');
            $(".columna_otro_entidad").slideDown('slow');
            $('#otra_entidad').prop('required', true);
        } else {
            $(".columna_otro_entidad").slideUp('slow');
            $('#otra_entidad').prop('required', false);
        }
    });

    //ejecuta boton de guardar
    $('#btn_guardar_entidad').click(function(){
        var tipo_entidad = $('#tipo_entidad').val();
        var nombre_entidad = $('#nombre_entidad').val();
        var nit_entidad = $('#nit_entidad').val();
        var entidad_telefono = $('#entidad_telefono').val();
        var entidad_email = $('#entidad_email').val();
        var entidad_direccion = $('#entidad_direccion').val();
        var entidad_medio_noti = $('#entidad_medio_noti').val();
        
        var entidad_email = $("#entidad_email").val();
        // Expresión regular para validar el formato del correo electrónico
        var patronCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (patronCorreo.test(entidad_email)) {
            if (tipo_entidad != '' && nombre_entidad != '' && nit_entidad != '' && entidad_telefono != '' && entidad_email != '' &&
            entidad_direccion != '' && entidad_medio_noti){
                $('#btn_guardar_entidad').addClass('d-none');
                $('#mostrar_barra_creacion_entidad').css("display","block");
            }   
        }

    });

    //Listar entidades
    $('#listado_entidades thead tr').clone(true).addClass('filters').appendTo('#listado_entidades thead');
    $('#listado_entidades').DataTable({
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
                
                if (title !== 'Acciones') {
                    
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
                    title: 'Lista de Entidades',
                    text:'Exportar datos',
                    className: 'btn btn-info',
                    "excelStyles": [                      // Add an excelStyles definition
                                                
                    ],
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17]
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

    // SETEAR CAMPOS FORMULARIO EDICIÓN ENTIDAD.
    let isDataLoaded = false;
    $(document).on('mouseover', "a[id^='btn_modal_edicion_entidad_']", function(){
        if (isDataLoaded) return;

        isDataLoaded = true;
        $(".modal-title").empty();
        $(".modal-footer").remove();
        let id_editar_entidad = $(this).data("id_editar_entidad");
        let nombre_editar_entidad = $(this).data("nombre_editar_entidad");

        $(".habilitar_modal_edicion_entidad").attr("id", "modalEdicionEntidad_"+id_editar_entidad);
        $(".modal-title").append("<i class='fa fa-pen mr-2'></i> Edición Entidad : "+nombre_editar_entidad);

        $(".actualizar_entidad").attr("id", "form_actualizar_entidad_"+id_editar_entidad);
        $('#captura_id_entidad').val(id_editar_entidad);

        var consulta = {
            'id_entidad': id_editar_entidad,
            '_token': $('input[name=_token]').val()
        };
        $.ajax({
        
            type:'POST',
            url: $('#ruta_ed_identidad').val(),
            data: consulta,
            success:function(data) {
                // Añadir la opción de tipo de identificación para el selector de tipo entidad
                $("#edi_tipo_entidad").empty();
                $("#edi_tipo_entidad").append('<option value="'+data[0]["IdTipo_entidad"]+'">'+data[0]["Tipo_Entidad"]+'</option>');
                // Añadir otra entidad
                $("#otra_entidad_edit").empty();
                $("#otra_entidad_edit").val(data[0]["Otro_entidad"]);
                if (data[0]["Otro_entidad"] != null && data[0]["IdTipo_entidad"] === 6) {
                    $(".columna_otro_entidad_edit").removeClass('d-none');
                    $(".columna_otro_entidad_edit").slideDown('slow');
                    $('#otra_entidad_edit').prop('required', true);
                }else{
                    $(".columna_otro_entidad_edit").slideUp('slow');
                    $('#otra_entidad_edit').prop('required', false);
                }
                // Añadir el nombre de la entidad
                $("#nombre_entidad").empty();
                $("#nombre_entidad").val(data[0]["Nombre_entidad"]);
                // Añadir Nit
                $("#nit_entidad").empty();
                $("#nit_entidad").val(data[0]["Nit_entidad"]);
               // Añadir Telefono principal
               $("#entidad_telefono").empty();
               $("#entidad_telefono").val(data[0]["Telefonos"]);
               // Añadir Otros Telefonos
               $("#entidad_telefono_otro").empty();
               $("#entidad_telefono_otro").val(data[0]["Otros_Telefonos"]);
                // Añadir Email Principal
                $("#entidad_email ").empty();
                $("#entidad_email").val(data[0]["Emails"]);
                // Añadir Otro Email
                $("#entidad_email_otro ").empty();
                $("#entidad_email_otro").val(data[0]["Otros_Emails"]);
                // Dirección
                $("#entidad_direccion ").empty();
                $("#entidad_direccion").val(data[0]["Direccion"]);
                // Departamento
                $("#edi_entidad_departamento").empty();
                $("#edi_entidad_departamento").append('<option value="'+data[0]["Id_Departamento"]+'">'+data[0]["Nombre_departamento"]+'</option>');
                //Ciudad
                $("#edi_entidad_ciudad").empty();
                $("#edi_entidad_ciudad").append('<option value="'+data[0]["Id_Ciudad"]+'">'+data[0]["Nombre_municipio"]+'</option>');
                //Medio Noti
                // $("#edi_entidad_medio_noti").empty();
                // $("#edi_entidad_medio_noti").append('<option value="'+data[0]["Id_Medio_Noti"]+'">'+data[0]["Medio_Noti"]+'</option>');
                let datos_lista_medio_noti_editar = {
                    '_token': token,
                    'parametro':"lista_medio_noti"
                };
                $.ajax({
                    type:'POST',
                    url:'/selectoresEntidad',
                    data: datos_lista_medio_noti_editar,
                    success:function(data_medios_noti) {
                        
                        $('#edi_entidad_medio_noti').empty();
                        $('#edi_entidad_medio_noti').append('<option value="">Seleccione</option>');
                        let claves = Object.keys(data_medios_noti);
                        for (let i = 0; i < claves.length; i++) {
                            if (data_medios_noti[claves[i]]["Id_Parametro"] == data[0]["Id_Medio_Noti"]) {
                                $('#edi_entidad_medio_noti').append('<option value="'+data_medios_noti[claves[i]]["Id_Parametro"]+'" selected>'+data_medios_noti[claves[i]]["Nombre_parametro"]+'</option>');
                            } else {
                                $('#edi_entidad_medio_noti').append('<option value="'+data_medios_noti[claves[i]]["Id_Parametro"]+'">'+data_medios_noti[claves[i]]["Nombre_parametro"]+'</option>');
                            }
                        }
                    }
                });
                
                // Sucursal
                $("#entidad_sucursal ").empty();
                $("#entidad_sucursal").val(data[0]["Sucursal"]);
                // Dirigido
                $("#entidad_dirigido ").empty();
                $("#entidad_dirigido").val(data[0]["Dirigido"]);
                // Añadir el status
                $("#edit_estado_entidad").empty();
                if (data[0]["Estado_entidad"] == "activo") {
                    $("#edit_estado_entidad").append('<option value="'+data[0]["Estado_entidad"]+'" selected>'+data[0]["Estado_entidad"]+'</option>');
                    $("#edit_estado_entidad").append('<option value="inactivo">Inactio</option>');
                }else{
                    $("#edit_estado_entidad").append('<option value="'+data[0]["Estado_entidad"]+'" selected>'+data[0]["Estado_entidad"]+'</option>');
                    $("#edit_estado_entidad").append('<option value="activo">Activo</option>');
                }
            }

        });
        setTimeout(() => {
            /* CARGA LISTADO DE TIPOS DE ENTIDAD */
            var datos_tipo_ident = {
                'edi_tipo_entidad': $('#edi_tipo_entidad').val(),
                '_token': $('input[name=_token]').val(),
                'parametro':"lista_tipo_entidad_edit"
            };
            $.ajax({
                type:'POST',
                url:'/selectoresEntidad',
                data: datos_tipo_ident,
                success:function(data) {
                    let entidadActual = Object.keys(data);
                    for (let i = 0; i < entidadActual.length; i++) {
                        $('#edi_tipo_entidad').append('<option value="'+data[entidadActual[i]]["Id_Entidad"]+'">'+data[entidadActual[i]]["Tipo_Entidad"]+'</option>');
                    }
                }
            });
            /* CARGA LISTADO DE DEPARTAMETOS */
            var datos_depar_ident = {
                'edi_departamento': $('#edi_entidad_departamento').val(),
                '_token': $('input[name=_token]').val(),
                'parametro':"lista_depar_edit"
            };
            $.ajax({
                type:'POST',
                url:'/selectoresEntidad',
                data: datos_depar_ident,
                success:function(data) {
                    let NombreDepartamento = $('select[name=edi_entidad_departamento]').val();
                    let departamentoactual = Object.keys(data);
                    for (let i = 0; i < departamentoactual.length; i++) {
                        if (data[departamentoactual[i]]['Id_departamento'] != NombreDepartamento) {                    
                            $('#edi_entidad_departamento').append('<option value="'+data[departamentoactual[i]]["Id_departamento"]+'">'+data[departamentoactual[i]]["Nombre_departamento"]+'</option>');
                        }
                        
                    }
                }
            });
            /* CARGAR LISTADO DE CIUDAD */    
            var datos_ciuda_ident = {
                'edi_departamento_c': $('#edi_entidad_departamento').val(),
                'edi_ciudad': $('#edi_entidad_ciudad').val(),
                '_token': $('input[name=_token]').val(),
                'parametro':"lista_ciudad_edit"
            };
            $.ajax({
                type:'POST',
                url:'/selectoresEntidad',
                data: datos_ciuda_ident,
                success:function(data) {
                    //$('#edi_entidad_departamento').empty();
                    let ciudadActual = Object.keys(data);
                    for (let i = 0; i < ciudadActual.length; i++) {
                        $('#edi_entidad_ciudad').append('<option value="'+data[ciudadActual[i]]["Id_departamento"]+'">'+data[ciudadActual[i]]["Nombre_departamento"]+'</option>');
                    }
                }
            });
            /* CARGAR LISTADO MEDIO NOTI */
           /* var datos_moti_soli = {
                'edi_medio_noti':$('#edi_entidad_medio_noti').val(),
                '_token': $('input[name=_token]').val(),
                'parametro':"lista_medio_noti_edit"
            };

            $.ajax({
                type:'POST',
                url:'/selectoresEntidad',
                data: datos_moti_soli,
                success:function(data) {
                    let NombreEntidad = $('select[name=edi_entidad_medio_noti]').val();
                    let motivoNotiActual = Object.keys(data);
                    //console.log(data)
                    for (let i = 0; i < motivoNotiActual.length; i++) {
                        if (data[motivoNotiActual[i]['Id_Parametro'] != NombreEntidad]) {
                            $('#edi_entidad_medio_noti').append('<option value="'+data[motivoNotiActual[i]]["Id_Parametro"]+'">'+data[motivoNotiActual[i]]["Nombre_parametro"]+'</option>');                            
                        }
                    }
                }
            }); */
        }, 1000);
    });
    /* Validación opción Otro/¿Cual? del selector Tipo Entidad edicion */
    $('#edi_tipo_entidad').change(function(){
        let opt_otra_entidad = $('#edi_tipo_entidad option:selected').text();
        if (opt_otra_entidad === "Otro/¿Cual?") {
            $(".columna_otro_entidad_edit").removeClass('d-none');
            $(".columna_otro_entidad_edit").slideDown('slow');
            $('#otra_entidad_edit').prop('required', true);
        } else {
            $(".columna_otro_entidad_edit").slideUp('slow');
            $('#otra_entidad_edit').prop('required', false);
        }
    });
    // Listado Municipio edicion
    $('#edi_entidad_departamento').change( function(){
        $('#edi_entidad_ciudad').prop('disabled', false);
        let id_departamento_entidad_edit = $('#edi_entidad_departamento').val();
        let datos_municipio_entidad_edit = {
            '_token': token,
            'parametro' : "lista_municipios_entidad_edit",
            'id_departamento_entidad_edit': id_departamento_entidad_edit
        };

        $.ajax({
            type:'POST',
            url:'/selectoresEntidad',
            data: datos_municipio_entidad_edit,
            success:function(data) {
                //console.log(data);
                $('#edi_entidad_ciudad').empty();
                $('#edi_entidad_ciudad').append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $('#edi_entidad_ciudad').append('<option value="'+data[claves[i]]["Id_municipios"]+'">'+data[claves[i]]["Nombre_municipio"]+'</option>');
                }
            }
        });
    });

    //ejecuta boton de guardar
    $('#btn_actualizar_entidad').click(function(){
        var tipo_entidad = $('#edi_tipo_entidad').val();
        var nombre_entidad = $('#nombre_entidad').val();
        var nit_entidad = $('#nit_entidad').val();
        var entidad_telefono = $('#entidad_telefono').val();
        var entidad_email = $('#entidad_email').val();
        var entidad_direccion = $('#entidad_direccion').val();
        var entidad_medio_noti = $('#edi_entidad_medio_noti').val();

        var entidad_email = $("#entidad_email").val();
        // Expresión regular para validar el formato del correo electrónico
        var patronCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (patronCorreo.test(entidad_email)) {
            if (tipo_entidad != '' && nombre_entidad != '' && nit_entidad != '' && entidad_telefono != '' && entidad_email != '' &&
            entidad_direccion != '' && entidad_medio_noti){
                $('#btn_actualizar_entidad').addClass('d-none');
                $('#mostrar_barra_editar_entidad').css("display","block");
            }   
        }
    });

    /* ENVÍO FORMULARIO EDICIÓN ENTIDAD PARA ACTUALIZAR INFORMACIÓN */
    $(document).on('submit', "form[id^='form_actualizar_entidad_']", function(e){
        e.preventDefault();
        // var formData = new FormData($(this)[0]);
        
        let datos_formulario_entidad = {
            '_token': token,
            'captura_id_entidad' : $("#captura_id_entidad").val(),
            'edi_tipo_entidad' : $("#edi_tipo_entidad").val(),
            'otra_entidad_edit' : $("#otra_entidad_edit").val(),
            'nombre_entidad' : $("#nombre_entidad").val(),
            'nit_entidad' : $("#nit_entidad").val(),
            'entidad_telefono' : $("#entidad_telefono").val(),
            'entidad_telefono_otro' : $("#entidad_telefono_otro").val(),
            'entidad_email' : $("#entidad_email").val(),
            'entidad_email_otro' : $("#entidad_email_otro").val(),
            'entidad_direccion' : $("#entidad_direccion").val(),
            'edi_entidad_departamento' : $("#edi_entidad_departamento").val(),
            'edi_entidad_ciudad' : $("#edi_entidad_ciudad").val(),
            'edi_entidad_medio_noti' : $("#edi_entidad_medio_noti").val(),
            'entidad_sucursal' : $("#entidad_sucursal").val(),
            'entidad_dirigido' : $("#entidad_dirigido").val(),
            'edit_estado_entidad' : $("#edit_estado_entidad").val(),
        };

        $.ajax({
            url: $('#ruta_guardar_ed_identidad').val(),
            type: "post",
            dataType: "json",
            data: datos_formulario_entidad,
            // cache: false,
            // contentType: false,
            // processData: false,
            success:function(response){
                $('#mostrar_barra_editar_entidad').addClass('d-none');
                $("#btn_actualizar_entidad").removeClass('d-none');
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

                }else {
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

});