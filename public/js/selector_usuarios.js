$(document).ready(function(){
    /* TODO LO REFERENTE AL FORMULARIO DE ASIGNACIÓN DE ROLES A USUARIOS */

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE USUARIOS */
    $(".listado_usuarios").select2({
        placeholder: "Listado Usuarios",
        allowClear: false
    });

    /* CARGA DE LOS USUARIOS */
    var datos = {
        '_token': $('input[name=_token]').val()
    };
    $.ajax({
        type:'POST',
        url:'/listausuarios',
        data: datos,
        success:function(data) {
            $('#listado_usuarios').empty();
            $('#listado_usuarios').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#listado_usuarios').append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["name"]+'</option>');
            }
        }
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE ROLES */
    $(".listado_todos_roles").select2({
        placeholder: "Listado Roles",
        allowClear: false
    });

    /* CARGA DE TODOS LOS ROLES QUE FALTAN POR ASIGNAR AL USUARIO CUANDO SE SELECCIONE UN USUARIO */
    $('#listado_usuarios').change(function(){
        var id_usuario_seleccionado = $(this).val();
        
        var datos_consulta_roles_faltantes = {
            '_token': $('input[name=_token]').val(),
            'id_usuario_seleccionado': id_usuario_seleccionado
        };
        $.ajax({
            type:'POST',
            url:'/listadoRolesXUsuario',
            data: datos_consulta_roles_faltantes,
            success:function(data) {
                $('#listado_todos_roles').empty();
                $('#listado_todos_roles').append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $('#listado_todos_roles').append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["nombre_rol"]+'</option>');
                }
            }
        });
    });











    

    /* TODO LO REFERENTE A LA CONSULTA DE ASIGNACION DE ROL */

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE USUARIOS PARA CONSULTAR LOS ROLES QUE TIENE ASIGNADOS */
    $(".listado_usuarios_asignacion_rol").select2({
        placeholder: "Listado Usuarios",
        allowClear: false
    });

    /* CARGA DE LOS USUARIOS PARA CONSULTAR LOS ROLES QUE TIENE ASIGNADOS*/
    var datos = {
        '_token': $('input[name=_token]').val()
    };
    $.ajax({
        type:'POST',
        url:'/listausuarios',
        data: datos,
        success:function(data) {
            $('#listado_usuarios_asignacion_rol').empty();
            $('#listado_usuarios_asignacion_rol').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#listado_usuarios_asignacion_rol').append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["name"]+'</option>');
            }
        }
    });
    

});