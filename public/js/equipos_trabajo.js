$(document).ready(function(){
    
    /* TODO LO REFERENTE A LA CREACIÓN DEL EQUIPO DE TRABAJO */

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO PROCESOS */
    $(".proceso").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    // CARGUE LISTADO DE PROCESOS
    let token = $('input[name=_token]').val();
    let datos_listado_proceso = {
        '_token': token,
        'parametro' : "listado_proceso_equipo_trabajo"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_listado_proceso,
        success:function(data) {
            //console.log(data);
            $('#proceso').empty();
            $('#proceso').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#proceso').append('<option value="'+data[claves[i]]["Id_proceso"]+'">'+data[claves[i]]["Nombre_proceso"]+'</option>');
            }
        }
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE LIDERES */
    $(".listado_lider").select2({
        placeholder: "Listado Usuarios",
        allowClear: false
    });

    /* CARGA DE LOS LIDERES */
    $("#proceso").change(function(){
        var id_proceso_seleccionado = $(this).val();

        var datos_consulta = {
            '_token': token,
            'id_proceso_seleccionado': id_proceso_seleccionado
        };
        $.ajax({
            type:'POST',
            url:'/ListaLideresXProceso',
            data: datos_consulta,
            success:function(data) {
                // console.log(data.length);
                $('#listado_lider').empty();
                $('#listado_usuarios_equipo').empty();
                if (data.length > 0) {
                    $('#listado_lider').append('<option value="" selected>Seleccione</option>');
                    let claves = Object.keys(data);
                    for (let i = 0; i < claves.length; i++) {
                        $('#listado_lider').append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["name"]+' ('+data[claves[i]]["email"]+')</option>');

                        $('#listado_usuarios_equipo').append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["name"]+' ('+data[claves[i]]["email"]+')</option>');
                        $('#listado_usuarios_equipo').bootstrapDualListbox('refresh', true);
                    }
                    $('.mensaje_no_hay_usuarios').addClass('d-none');
                } else {
                    $('.mensaje_no_hay_usuarios').removeClass('d-none');
                    $('#listado_usuarios_equipo').empty();
                    $('#listado_usuarios_equipo').bootstrapDualListbox('refresh', true);
                }
                
            }
        });
    });

    if ($('#listado_usuarios_equipo').length > 0) {
        /* CARGA DE LOS USUARIOS PARA ASIGNAR AL EQUIPO */
        var datos = {
            '_token': token
        };
        /* $.ajax({
            type:'POST',
            url:'/listausuarios',
            data: datos,
            success:function(data) {
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $('#listado_usuarios_equipo').append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["name"]+' ('+data[claves[i]]["email"]+')</option>');
                    $('#listado_usuarios_equipo').bootstrapDualListbox('refresh', true);

                }
            }
        }); */

        /* IMPLEMENTACIÓN PLUGIN DUALLISTBOX 4.0.2 PARA EL SELECTOR DE USUARIOS A ASIGNAR */
        $('#listado_usuarios_equipo').bootstrapDualListbox({
            nonSelectedListLabel: 'No Seleccionados',
            selectedListLabel: 'Selecionados',
            filterPlaceHolder: 'Buscar usuario',
            infoText: 'Total {0}',
            infoTextFiltered: '<span class="badge badge-warning">Resultados</span> {0} de {1}',
            infoTextEmpty: 'Lista vacía',
        });
        
    }

    /* TODO LO REFERENTE A LA EDICIÓN DEL EQUIPO DE TRABAJO */

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO PROCESOS */
    $(".editar_proceso").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE LIDERES PARA EDICIÓN  */
    $(".editar_listado_lider").select2({
        placeholder: "Listado Usuarios",
        allowClear: false
    });

    /* FUNCIONES ASOCIADAS AL BOTÓN QUE HABILITA EL MODAL */
    $(document).on('mouseover', "a[id^='btn_modal_edicion_equipo_']", function(){

        $(".modal-footer").remove();
        $("button[id^='btn_actualizar_consulta_']").addClass('d-none');

        var id_proceso = $(this).data("id_proceso");
        var id_equipo_trabajo = $(this).data("id_equipo_trabajo");

        // CARGUE LISTADO DE PROCESOS
        let datos_listado_proceso_edicion = {
            '_token': token,
            'parametro' : "listado_proceso_edicion_equipo",
            'id_proceso': $("#editar_proceso_"+id_proceso).val()
        };
        // console.log(datos_listado_proceso_edicion);
        $.ajax({
            type:'POST',
            url:'/cargarselectores',
            data: datos_listado_proceso_edicion,
            success:function(data) {
                // console.log(data);
                $("#editar_proceso_"+id_proceso).empty();
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    if (data[claves[i]]["Id_proceso"] == id_proceso) {
                        $("#editar_proceso_"+id_proceso).append('<option value="'+data[claves[i]]["Id_proceso"]+'" selected>'+data[claves[i]]["Nombre_proceso"]+'</option>');
                    } else {
                        $("#editar_proceso_"+id_proceso).append('<option value="'+data[claves[i]]["Id_proceso"]+'">'+data[claves[i]]["Nombre_proceso"]+'</option>');
                    }
                }
            }
        });

        /* CARGA DE LOS LIDERES PARA EDICIÓN APENAS CARGA EL MODAL */
        var datos_lideres_proceso = {
            '_token': token,
            'id_proceso_seleccionado': id_proceso
        };
        $.ajax({
            type:'POST',
            url:'/ListaLideresXProceso',
            data: datos_lideres_proceso,
            success:function(data) {
                $('#editar_listado_lider_'+id_proceso).empty();
                if (data.length > 0) {
                    // $('#editar_listado_lider_'+id_proceso).append('<option value="" selected>Seleccione</option>');
                    let claves = Object.keys(data);
                    for (let i = 0; i < claves.length; i++) {
                        if (data[claves[i]]["id"] == $(this).data("id_lider")) {
                            $('#editar_listado_lider_'+id_proceso).append('<option value="'+data[claves[i]]["id"]+'" selected>'+data[claves[i]]["name"]+' ('+data[claves[i]]["email"]+')</option>');
                        } else {
                            $('#editar_listado_lider_'+id_proceso).append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["name"]+' ('+data[claves[i]]["email"]+')</option>');
                        }
                    }
                    $('.mensaje_no_hay_usuarios_edicion').addClass('d-none');
                } else {
                    $('.mensaje_no_hay_usuarios_edicion').removeClass('d-none');
                }
            }
        });

        /* CARGA DE LOS LIDERES PARA EDICIÓN APENAS CARGA EL MODAL ASOCIADA A CUANDO SE APLICA EL EVENTO CHANGE*/
        $('.mensaje_no_hay_usuarios_edicion').addClass('d-none');
        $(document).on('change', "select[id^='editar_proceso_']", function(){
            var id_proceso_seleccionado = $(this).val();
            // console.log(id_proceso_seleccionado);
            var datos_consulta_edicion = {
                '_token': token,
                'id_proceso_seleccionado': id_proceso_seleccionado,
                'id_proceso_borrar': id_proceso
            };
            
            $.ajax({
                type:'POST',
                url:'/ListaLideresXProceso',
                data: datos_consulta_edicion,
                success:function(data) {
                    $('#editar_listado_lider_'+id_proceso).empty();
                    $('#editar_listado_usuarios_equipo_'+id_equipo_trabajo).empty();
                    if (data.length > 0) {
                        $('#editar_listado_lider_'+id_proceso).append('<option value="" selected>Seleccione</option>');
                        let claves = Object.keys(data);
                        for (let i = 0; i < claves.length; i++) {
                            $('#editar_listado_lider_'+id_proceso).append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["name"]+' ('+data[claves[i]]["email"]+')</option>');

                            $('#editar_listado_usuarios_equipo_'+id_equipo_trabajo).append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["name"]+' ('+data[claves[i]]["email"]+')</option>');
                            $('#editar_listado_usuarios_equipo_'+id_equipo_trabajo).bootstrapDualListbox('refresh', true);
                        }
                        $('.mensaje_no_hay_usuarios_edicion').addClass('d-none');
                    } else {
                        $('#editar_listado_usuarios_equipo_'+id_equipo_trabajo).empty();
                         $('#editar_listado_usuarios_equipo_'+id_equipo_trabajo).bootstrapDualListbox('refresh', true);
                        $('.mensaje_no_hay_usuarios_edicion').removeClass('d-none');
                    }
                }
            });
        });

        /* TRAER EL LISTADO DE USUARIOS ASIGNADOS Y NO ASIGNADOS DEL EQUIPO DE TRABAJO SELECCIONADO */
        if ($('#editar_listado_usuarios_equipo_'+id_equipo_trabajo).length >0) {
            var datos_usuarios_asignados = {
                '_token': token,
                'id_equipo_trabajo' : id_equipo_trabajo,
                'id_proceso_seleccionado': id_proceso
            };
            
            $.ajax({
                type:'POST',
                url:'/listadoUsuariosAsignacion',
                data: datos_usuarios_asignados,
                success:function(data) {
                    $(".btn-group ").css("width", "100%");
                    $('#editar_listado_usuarios_equipo_'+id_equipo_trabajo).empty();
                    let claves = Object.keys(data);
                    for (let i = 0; i < claves.length; i++) {
                        if (data[claves[i]]["seleccionado"] === 'selected') {
                            $('#editar_listado_usuarios_equipo_'+id_equipo_trabajo).append('<option selected="selected" value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["name"]+' ('+data[claves[i]]["email"]+')</option>');
                        }else{
                            $('#editar_listado_usuarios_equipo_'+id_equipo_trabajo).append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["name"]+' ('+data[claves[i]]["email"]+')</option>');
                        }
                        $('#editar_listado_usuarios_equipo_'+id_equipo_trabajo).bootstrapDualListbox('refresh', true);
                    }
                }
            });
        
            /* IMPLEMENTACIÓN PLUGIN DUALLISTBOX 4.0.2 PARA EL SELECTOR DE USUARIOS DE EDICIÓN DE GRUPO */
            $('#editar_listado_usuarios_equipo_'+id_equipo_trabajo).bootstrapDualListbox({
                nonSelectedListLabel: 'No Seleccionados',
                selectedListLabel: 'Selecionados',
                filterPlaceHolder: 'Buscar usuario',
                infoText: 'Total {0}',
                infoTextFiltered: '<span class="badge badge-warning">Resultados</span> {0} de {1}',
                infoTextEmpty: 'Lista vacía',
            });
        }

    });

    /* ENVÍO DE FORMULARIO PARA GUARDAR EDICIÓN DE EQUIPO DE TRABAJO */
    $(document).on('submit', "form[id^='form_actualizar_equipo_']", function(e){
        e.preventDefault();
    
        var formData = new FormData($(this)[0]);
        // for (var pair of formData.entries()) {
        //     console.log(pair[0]+" - "+ pair[1]);
        // }

        $.ajax({
            url: $('#ruta_guardar_edicion_equipo').val(),
            type: "post",
            dataType: "json",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success:function(response){
                if (response.parametro == "exito") {
                    $("button[id^='btn_actualizar_consulta_']").removeClass('d-none');
                    
                    $("div[id^='mostrar_mensaje_act_equipo_']").removeClass('d-none');
                    $("div[id^='mostrar_mensaje_act_equipo_']").addClass('alert-success');
                    $("div[id^='mostrar_mensaje_act_equipo_']").append('<strong>'+response.mensaje+'</strong>');

                    setTimeout(() => {
                        $("div[id^='mostrar_mensaje_act_equipo_']").addClass('d-none');
                        $("div[id^='mostrar_mensaje_act_equipo_']").removeClass('alert-success');
                        $("div[id^='mostrar_mensaje_act_equipo_']").empty();
                    }, 9000);
                }else{
                    $("div[id^='mostrar_mensaje_act_equipo_']").removeClass('d-none');
                    $("div[id^='mostrar_mensaje_act_equipo_']").addClass('alert-danger');
                    $("div[id^='mostrar_mensaje_act_equipo_']").append('<strong>'+response.mensaje+'</strong>');

                    setTimeout(() => {
                        $("div[id^='mostrar_mensaje_act_equipo_']").addClass('d-none');
                        $("div[id^='mostrar_mensaje_act_equipo_']").removeClass('alert-danger');
                        $("div[id^='mostrar_mensaje_act_equipo_']").empty();
                    }, 9000);
                }
            }         
        });


    });

    /* FUNCIONALIDAD ACTUALIZAR PÁGINA DE CONSULTA */
    $(document).on('click', "button[id^='btn_actualizar_consulta_']", function(){
        location.reload();
    });
    
    
});