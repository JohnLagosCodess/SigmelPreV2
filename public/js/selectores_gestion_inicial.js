$(document).ready(function(){

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE CLIENTES */
    $(".cliente").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE TIPOS CLIENTES */
    $(".tipo_cliente").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE TIPO DE EVENTOS */
    $(".tipo_evento").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE TIPO DE DOCUMENTO */
    $(".tipo_documento").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE GÉNERO */
    $(".genero").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE ESTADO CIVIL */
    $(".estado_civil").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE NIVEL ESCOLAR */
    $(".nivel_escolar").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE DOMINANCIA */
    $(".dominancia").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE DEPARTAMENOS (INFORMACIÓN AFILIADO) */
    $(".departamento_info_afiliado").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE MUNCIPIOS (INFORMACIÓN AFILIADO) */
    $(".municipio_info_afiliado").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE TIPOS DE AFILIADO */
    $(".tipo_afiliado").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE EPS */
    $(".eps").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE AFP */
    $(".afp").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE ARL (INFORMACIÓN AFILIADO) */
    $(".arl_info_afiliado").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE APODERADO */
    $(".apoderado").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE ACTIVO */
    $(".activo").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });


    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO ARL (INFORMACIÓN LABORAL) */
    $(".arl_info_laboral").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DEPARTAMENTO (INFORMACIÓN LABORAL) */
    $(".departamento_info_laboral").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO MUNICIPIO (INFORMACIÓN LABORAL) */
    $(".municipio_info_laboral").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO ACTIVIDAD ECONOMICA */
    $(".actividad_economica").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO CLASE RIESGO */
    $(".clase_riesgo").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO CIUO */
    $(".codigo_ciuo").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO MOTIVO SOLICITUD */
    $(".motivo_solicitud").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO TIPO DE VINCULO */
    $(".tipovinculo").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO REGIMEN */
    $(".regimen").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO SOLICITANTE */
    $(".solicitante").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO NOMBRE SOLICITANTE */
    $(".nombre_solicitante").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO FUENTE DE INFORMACION */
    $(".fuente_informacion").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO PROCESO */
    $(".proceso").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO SERVICIO */
    $(".servicio").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO ACCION */
    $(".accion").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* LLENADO DE SELECTORES PRINCIPALES */
    let token = $('input[name=_token]').val();

    // listado de clientes
    let datos_lista_clientes = {
        '_token': token,
        'parametro' : "lista_clientes"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_clientes,
        success:function(data) {
            // console.log(data);
            $('#cliente').empty();
            $('#cliente').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#cliente').append('<option value="'+data[claves[i]]["Id_Cliente"]+'">'+data[claves[i]]["Nombre_cliente"]+'</option>');
            }
        }
    });
    // listado de tipos de clientes
    let datos_lista_tipo_clientes = {
        '_token': token,
        'parametro' : "lista_tipo_clientes"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_tipo_clientes,
        success:function(data) {
            // console.log(data);
            $('#tipo_cliente').empty();
            $('#tipo_cliente').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#tipo_cliente').append('<option value="'+data[claves[i]]["Id_TipoCliente"]+'">'+data[claves[i]]["Nombre_tipo_cliente"]+'</option>');
            }
        }
    });
    // listado tipo de evento
    let datos_lista_tipo_evento = {
        '_token': token,
        'parametro' : "lista_tipo_evento"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_tipo_evento,
        success:function(data) {
            // console.log(data);
            $('#tipo_evento').empty();
            $('#tipo_evento').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#tipo_evento').append('<option value="'+data[claves[i]]["Id_Evento"]+'">'+data[claves[i]]["Nombre_evento"]+'</option>');
            }
        }
    });
    // listado tipos de documento
    let datos_lista_tipo_documento = {
        '_token': token,
        'parametro' : "lista_tipo_documento"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_tipo_documento,
        success:function(data) {
            // console.log(data);
            $('#tipo_documento').empty();
            $('#tipo_documento').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#tipo_documento').append('<option value="'+data[claves[i]]["Nombre_parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });
    // listado generos
    let datos_lista_generos = {
        '_token': token,
        'parametro' : "genero"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_generos,
        success:function(data) {
            // console.log(data);
            $('#genero').empty();
            $('#genero').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#genero').append('<option value="'+data[claves[i]]["Nombre_parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });
    // listado estado civil
    let datos_lista_estado_civil = {
        '_token': token,
        'parametro' : "estado_civil"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_estado_civil,
        success:function(data) {
            // console.log(data);
            $('#estado_civil').empty();
            $('#estado_civil').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#estado_civil').append('<option value="'+data[claves[i]]["Nombre_parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });
    // listado nivel escolar
    let datos_lista_nivel_escolar = {
        '_token': token,
        'parametro' : "nivel_escolar"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_nivel_escolar,
        success:function(data) {
            // console.log(data);
            $('#nivel_escolar').empty();
            $('#nivel_escolar').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#nivel_escolar').append('<option value="'+data[claves[i]]["Nombre_parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });
    // listado dominancia
    let datos_lista_dominancia = {
        '_token': token,
        'parametro' : "dominancia"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_dominancia,
        success:function(data) {
            // console.log(data);
            $('#dominancia').empty();
            $('#dominancia').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#dominancia').append('<option value="'+data[claves[i]]["Id_Dominancia"]+'">'+data[claves[i]]["Nombre_dominancia"]+'</option>');
            }
        }
    });
    // listado Departamentos (Informacion Afiliado)
    let datos_lista_departamentos_info_afiliado = {
        '_token': token,
        'parametro' : "departamentos_info_afiliado"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_departamentos_info_afiliado,
        success:function(data) {
            // console.log(data);
            $('#departamento_info_afiliado').empty();
            $('#departamento_info_afiliado').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#departamento_info_afiliado').append('<option value="'+data[claves[i]]["Id_Departamentos"]+'">'+data[claves[i]]["Nombre_departamento"]+'</option>');
            }
        }
    });
    // listado municipios dependiendo del departamentos (informacion afiliado)

    // listado tipo de afiliado
    let datos_lista_tipo_afiliado = {
        '_token': token,
        'parametro' : "tipo_afiliado"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_tipo_afiliado,
        success:function(data) {
            // console.log(data);
            $('#tipo_afiliado').empty();
            $('#tipo_afiliado').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#tipo_afiliado').append('<option value="'+data[claves[i]]["Nombre_parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });
    // lista eps
    let datos_lista_eps = {
        '_token': token,
        'parametro' : "lista_eps"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_eps,
        success:function(data) {
            // console.log(data);
            $('#eps').empty();
            $('#eps').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#eps').append('<option value="'+data[claves[i]]["Id_Eps"]+'">'+data[claves[i]]["Nombre_eps"]+'</option>');
            }
        }
    });

    // lista afp
    let datos_lista_afp = {
        '_token': token,
        'parametro' : "lista_afp"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_afp,
        success:function(data) {
            // console.log(data);
            $('#afp').empty();
            $('#afp').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#afp').append('<option value="'+data[claves[i]]["Id_Afp"]+'">'+data[claves[i]]["Nombre_afp"]+'</option>');
            }
        }
    });
    // lista arl (información afiliado)
    let datos_lista_arl_info_afiliado = {
        '_token': token,
        'parametro' : "lista_arl_info_afiliado"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_arl_info_afiliado,
        success:function(data) {
            // console.log(data);
            $('#arl_info_afiliado').empty();
            $('#arl_info_afiliado').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#arl_info_afiliado').append('<option value="'+data[claves[i]]["Id_Arl"]+'">'+data[claves[i]]["Nombre_arl"]+'</option>');
            }
        }
    });
    // lista apoderado
    let datos_lista_arl_apoderado = {
        '_token': token,
        'parametro' : "apoderado"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_arl_apoderado,
        success:function(data) {
            // console.log(data);
            $('#apoderado').empty();
            $('#apoderado').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#apoderado').append('<option value="'+data[claves[i]]["Nombre_parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });
    // lista activo
    let datos_lista_activo = {
        '_token': token,
        'parametro' : "activo"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_activo,
        success:function(data) {
            // console.log(data);
            $('#activo').empty();
            $('#activo').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#activo').append('<option value="'+data[claves[i]]["Nombre_parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });

    //LISTADO ARL (Información Laboral)
    let datos_lista_arl = {
        '_token': token,
        'parametro' : "listado_arl_info_laboral"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_arl,
        success:function(data) {
            // console.log(data);
            $('#arl_info_laboral').empty();
            $('#arl_info_laboral').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#arl_info_laboral').append('<option value="'+data[claves[i]]["Id_Arl"]+'">'+data[claves[i]]["Nombre_arl"]+'</option>');
            }
        }
    });

    //LISTADO DEPARTAMENTO
    let datos_listado_departamento = {
        '_token': token,
        'parametro' : "listado_departamento_info_laboral"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_listado_departamento,
        success:function(data) {
            //console.log(data);
            $('#departamento_info_laboral').empty();
            $('#departamento_info_laboral').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#departamento_info_laboral').append('<option value="'+data[claves[i]]["Id_departamento"]+'">'+data[claves[i]]["Nombre_departamento"]+'</option>');
            }
        }
    });

    //LISTADO ACTIVIDAD ECONOMICA
    let datos_lista_actividad_economica = {
        '_token': token,
        'parametro' : "listado_actividad_economica"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_actividad_economica,
        success:function(data) {
            //console.log(data);
            $('#actividad_economica').empty();
            $('#actividad_economica').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#actividad_economica').append('<option value="'+data[claves[i]]["Id_ActEco"]+'">'+data[claves[i]]["id_codigo"]+' - '+data[claves[i]]["Nombre_actividad"]+'</option>');
            }
        }
    });

    //LISTADO CLASE DE RIESGO
    let datos_lista_clase_de_riesgos = {
        '_token': token,
        'parametro' : "listado_clase_riesgo"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_clase_de_riesgos,
        success:function(data) {
            //console.log(data);
            $('#clase_riesgo').empty();
            $('#clase_riesgo').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#clase_riesgo').append('<option value="'+data[claves[i]]["Id_Riesgo"]+'">'+data[claves[i]]["Nombre_riesgo"]+'</option>');
            }
        }
    });

    //LISTADO CIUO
    let datos_lista_ciuo = {
        '_token': token,
        'parametro' : "listado_codigo_ciuo"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_ciuo,
        success:function(data) {
            //console.log(data);
            $('#codigo_ciuo').empty();
            $('#codigo_ciuo').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#codigo_ciuo').append('<option value="'+data[claves[i]]["Id_Codigo"]+'">'+data[claves[i]]["id_codigo_ciuo"]+' - '+data[claves[i]]["Nombre_ciuo"]+'</option>');
            }
        }
    });

    //LISTADO MOTIVO SOLICITUD
    let datos_lista_motivo_solicitud = {
        '_token': token,
        'parametro' : "listado_motivo_solicitud"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_motivo_solicitud,
        success:function(data) {
            //console.log(data);
            $('#motivo_solicitud').empty();
            $('#motivo_solicitud').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#motivo_solicitud').append('<option value="'+data[claves[i]]["Id_Solicitud"]+'">'+data[claves[i]]["Nombre_solicitud"]+'</option>');
            }
        }
    });

    //LISTADO TIPO VINCULACION
    let datos_lista_tipo_viculacion = {
        '_token': token,
        'parametro' : "listado_tipo_vinculo"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_tipo_viculacion,
        success:function(data) {
            //console.log(data);
            $('#tipovinculo').empty();
            $('#tipovinculo').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#tipovinculo').append('<option value="'+data[claves[i]]["Nombre_parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });

    //LISTADO REGIMEN EN SALUD
    let datos_listado_solicitud_regimen_en_salud = {
        '_token': token,
        'parametro' : "listado_solicitud_regimen_en_salud"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_listado_solicitud_regimen_en_salud,
        success:function(data) {
            //console.log(data);
            $('#regimen').empty();
            $('#regimen').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#regimen').append('<option value="'+data[claves[i]]["Nombre_parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });

    //LISTADO SOLICITANTE
    let datos_listado_solicitante = {
        '_token': token,
        'parametro' : "listado_solicitante"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_listado_solicitante,
        success:function(data) {
            //console.log(data);
            $('#solicitante').empty();
            $('#solicitante').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#solicitante').append('<option value="'+data[claves[i]]["Id_solicitante"]+'">'+data[claves[i]]["Solicitante"]+'</option>');
            }
        }
    });

    //LISTADO FUENTE DE INFORMACION
    let datos_listado_fuente_informacion = {
        '_token': token,
        'parametro' : "listado_fuente_informacion"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_listado_fuente_informacion,
        success:function(data) {
            //console.log(data);
            $('#fuente_informacion').empty();
            $('#fuente_informacion').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#fuente_informacion').append('<option value="'+data[claves[i]]["Nombre_parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });

    //LISTADO PROCESO
    let datos_listado_proceso = {
        '_token': token,
        'parametro' : "listado_proceso"
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

    //LISTADO ACCION
    let datos_listado_accion = {
        '_token': token,
        'parametro' : "listado_accion"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_listado_accion,
        success:function(data) {
            //console.log(data);
            $('#accion').empty();
            $('#accion').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#accion').append('<option value="'+data[claves[i]]["Id_Accion"]+'">'+data[claves[i]]["Nombre_accion"]+'</option>');
            }
        }
    });

});