$(document).ready(function(){

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE LIDERES */
    $(".listado_lider").select2({
        placeholder: "Listado Usuarios",
        allowClear: false
    });

    /* CARGA DE LOS LIDERES */
    var datos = {
        '_token': $('input[name=_token]').val()
    };
    $.ajax({
        type:'POST',
        url:'/listausuarios',
        data: datos,
        success:function(data) {
            $('#listado_lider').empty();
            $('#listado_lider').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#listado_lider').append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["name"]+' ('+data[claves[i]]["email"]+')</option>');
            }
        }
    });


    /* CARGA DE LOS USUARIOS PARA ASIGNAR AL GRUPO */
    var datos = {
        '_token': $('input[name=_token]').val()
    };
    $.ajax({
        type:'POST',
        url:'/listausuarios',
        data: datos,
        success:function(data) {
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#listado_usuarios_grupo').append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["name"]+' ('+data[claves[i]]["email"]+')</option>');
                $('#listado_usuarios_grupo').bootstrapDualListbox('refresh', true);

            }
        }
    });

    /* IMPLEMENTACIÓN PLUGIN DUALLISTBOX 4.0.2 PARA EL SELECTOR DE USUARIOS A ASIGNAR */
    $('#listado_usuarios_grupo').bootstrapDualListbox({
        nonSelectedListLabel: 'No Seleccionados',
        selectedListLabel: 'Selecionados',
        filterPlaceHolder: 'Buscar usuario',
        infoText: 'Total {0}',
        infoTextFiltered: '<span class="badge badge-warning">Resultados</span> {0} de {1}',
        infoTextEmpty: 'Lista vacía',
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE LIDERES PARA EDICIÓN  */

    $(".editar_listado_lider").select2({
        placeholder: "Listado Usuarios",
        allowClear: false
    });

    /* CARGA DE LOS LIDERES PARA EDICIÓN */
    var datos = {
        'id' : $("#editar_listado_lider").val(),
        '_token': $('input[name=_token]').val()
    };
    $.ajax({
        type:'POST',
        url:'/listadoLideresEditar',
        data: datos,
        success:function(data) {
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#editar_listado_lider').append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["name"]+' ('+data[claves[i]]["email"]+')</option>');
            }
        }
    });

    
    /* CATPURA DEL ID DEL GRUPO */
    let id_grupo = $('#id_grupo').val();
    var datos_usuarios_asignados = {
        'id_grupo_trabajo' : id_grupo,
        '_token': $('input[name=_token]').val()
    };
    $.ajax({
        type:'POST',
        url:'/listadoUsuariosAsignacion',
        data: datos_usuarios_asignados,
        success:function(data) {
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["seleccionado"] === 'selected') {
                    $('#editar_listado_usuarios_grupo').append('<option selected="selected" value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["name"]+' ('+data[claves[i]]["email"]+')</option>');
                }else{
                    $('#editar_listado_usuarios_grupo').append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["name"]+' ('+data[claves[i]]["email"]+')</option>');
                }
                $('#editar_listado_usuarios_grupo').bootstrapDualListbox('refresh', true);
            }
        }
    });

    /* IMPLEMENTACIÓN PLUGIN DUALLISTBOX 4.0.2 PARA EL SELECTOR DE USUARIOS DE EDICIÓN DE GRUPO */
    $('#editar_listado_usuarios_grupo').bootstrapDualListbox({
        nonSelectedListLabel: 'No Seleccionados',
        selectedListLabel: 'Selecionados',
        filterPlaceHolder: 'Buscar usuario',
        infoText: 'Total {0}',
        infoTextFiltered: '<span class="badge badge-warning">Resultados</span> {0} de {1}',
        infoTextEmpty: 'Lista vacía',
    });
    
});