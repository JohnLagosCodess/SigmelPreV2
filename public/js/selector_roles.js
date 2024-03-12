$(document).ready(function(){
    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE ROLES */
    $(".listado_roles_usuario").select2({
        placeholder: "Listado Roles",
        allowClear: false
    });

    /* Captura de id y email y el rol principal del usuario */
    let id_usuario = $(".div_listado_roles_usuarios").data("id");
    let correo_usuario = $(".div_listado_roles_usuarios").data("email");
    let rol_usuario_principal = $(".div_listado_roles_usuarios").data("rol_usuario_principal");
    let id_cambio_rol = $(".div_listado_roles_usuarios").data("id_cambio_rol");

    /* CARGA INICIAL DEL LOS ROLES DEL USUARIO */
    var datos = {
        'id_usuario': id_usuario,
        'correo_usuario': correo_usuario,
        '_token': $('input[name=_token]').val()
    };
    $.ajax({
        type:'POST',
        url:'/rolesiniciales',
        data: datos,
        success:function(data) {
            $('#listado_roles_usuario').empty();
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
  
                if (rol_usuario_principal === data[claves[i]]["nombre_rol"]) {
                    $('#listado_roles_usuario').append('<option value="'+data[claves[i]]["rol_id"]+'" selected>'+data[claves[i]]["nombre_rol"]+'</option>');
                }else{
                    if (id_cambio_rol != '' && id_cambio_rol == data[claves[i]]["rol_id"]) {
                        $('#listado_roles_usuario').append('<option value="'+data[claves[i]]["rol_id"]+'" selected>'+data[claves[i]]["nombre_rol"]+'</option>');
                    }else{
                        $('#listado_roles_usuario').append('<option value="'+data[claves[i]]["rol_id"]+'">'+data[claves[i]]["nombre_rol"]+'</option>');
                    }
                }
            }
        }
    });

    $("#listado_roles_usuario").change(function(){
        $("input[name='_token']").prop('disabled', false);
        $("input[name='id_usuario']").prop('disabled', false);
        $("input[name='correo_usuario']").prop('disabled', false);
    });
})