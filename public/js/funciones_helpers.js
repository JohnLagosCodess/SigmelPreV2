/* AQUÍ SE CREARÁN LAS FUNCIONES QUE SE IMPLEMENTARÁN PARA VARIAS VISTAS */
$(document).ready(function () {
    
    /* INPUTS DEL FORMULARIO DE CREACIÓN NUEVO USUARIO */
    $('#nombre_usuario').keyup(function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusCadaPalabra(textoEscrito));
    });

    $('#empresa_usuario').keyup(function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusCadaPalabra(textoEscrito));
    });

    $('#cargo_usuario').keyup(function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusCadaPalabra(textoEscrito));
    });

    $('#correo_contacto_usuario').keyup(function () {
        var email_escrito = $(this).val();
        var resultado_validacion = ValidarCorreoEscrito(email_escrito);
        if (resultado_validacion) {
            $("#correo_usuario").val(email_escrito);
        }else {
            $("#correo_usuario").val("");
        }
    });

    /* INPUTS DEL FORMULARIO DE EDICIÓN DE USUARIO (VENTANA MODAL) */
    $('#editar_nombre_usuario').keyup(function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusCadaPalabra(textoEscrito));
    });

    $('#editar_empresa_usuario').keyup(function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusCadaPalabra(textoEscrito));
    });

    $('#editar_cargo_usuario').keyup(function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusCadaPalabra(textoEscrito));
    });

    $('#editar_correo_contacto_usuario').keyup(function () {
        var email_escrito = $(this).val();
        var resultado_validacion = ValidarCorreoEscrito(email_escrito);
        if (resultado_validacion) {
            $("#editar_correo_usuario").val(email_escrito);
        }else {
            $("#editar_correo_usuario").val("");
        }
    });

    /* INPUTS DEL FORMULARIO DE CREACIÓN DE ROL */
    $("#nombre_rol").keyup(function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusCadaPalabra(textoEscrito));
    });

    $("#descripcion_rol").keyup(function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusCadaPalabra(textoEscrito));
    });

    /* INPUTS DEL FORMULARIO DE EDICIÓN DE ROL */
    $(document).on('keyup', "input[id^='editar_nombre_rol_']",function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusCadaPalabra(textoEscrito));

    })

    $(document).on('keyup', "textarea[id^='editar_descripcion_rol_']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusCadaPalabra(textoEscrito));
    });

    /* INPUTS DEL FORMULARIO DE CREACIÓN DE EQUIPOS DE TRABAJO */
    $('#nombre_equipo_trabajo').keyup(function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusCadaPalabra(textoEscrito));
    });

    $('#descripcion_equipo_trabajo').keyup(function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusCadaPalabra(textoEscrito));
    });

    /* INPUTS DEL FORMULARIO DE EDICIÓN DE EQUIPOS DE TRABAJO */
    $(document).on('keyup', "input[id^='editar_nombre_equipo_trabajo_']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusCadaPalabra(textoEscrito));
    });
    $(document).on('keyup', "textarea[id^='editar_descripcion_equipo_trabajo_']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusCadaPalabra(textoEscrito));
    });

    /* INPUTS Y TEXT AREAS DEL MODAL Solicitud Documentos - Seguimientos Módulo Calificación PCL*/
    $(document).on('keyup', "input[id^='nombre_otro_doc_']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });
    $(document).on('keyup', "textarea[id^='descripcion_fila_']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });

    $(document).on('keyup', "input[id^='nombre_otro_solicitante_']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });
    // Text-area de modal agregar seguimiento
    $(document).on('keyup', "textarea[id^='descripcion_seguimiento']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });
    // Inputs Text-area de modal generar comunicado
    $(document).on('keyup', "input[id^='nombre_afiliado_comunicado']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });

    $(document).on('keyup', "input[id^='asunto']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });  
    
    $(document).on('keyup', "textarea[id^='cuerpo_comunicado']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });

    $(document).on('keyup', "textarea[id^='descripcion_accion']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });
    // Text areas vista calificacion tecnica
    $(document).on('keyup', "textarea[id^='descripcion_otros']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });

    $(document).on('keyup', "textarea[id^='descripcion_enfermedad']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });

    $(document).on('keyup', "input[id^='nombre_examen_fila_']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));        
    });

    $(document).on('keyup', "textarea[id^='descripcion_resultado_fila_']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));        
    });

    $(document).on('keyup', "textarea[id^='descripcion_cie10_fila_']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));        
    });

    $(document).on('keyup', "textarea[id^='sustenta_fecha']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));        
    });

    $(document).on('keyup', "textarea[id^='detalle_califi']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));        
    });

    $(document).on('keyup', "textarea[id^='justi_dependencia']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));        
    });    

    /* Función para colocar la primera letra en mayúscula de cada palabra que se escriba */
    function LetraMayusCadaPalabra(textoEscrito) {
        var palabras = textoEscrito.split(' ');
        for (var i = 0; i < palabras.length; i++) {
            var primeraLetra = palabras[i].charAt(0).toUpperCase();
            var restoPalabra = palabras[i].slice(1);
            palabras[i] = primeraLetra + restoPalabra;
        }
        var resultado_texto_final = palabras.join(' ');
        return resultado_texto_final;
    }

    /* Función para colocar solamente la primera letra en Mayuscula */
    function LetraMayusPrimeraLetraTexto(textoEscrito){
        var firstLetter = textoEscrito.charAt(0).toUpperCase();
        var restOfWord = textoEscrito.slice(1);
        return firstLetter + restOfWord;
    }

    /* Función para validar que un correo esté bien escrito */
    function ValidarCorreoEscrito(correo_escrito){
        var regEx = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (regEx.test(correo_escrito)) {
            return true;
        } else {
            return false;
        }
    }    

     /* INPUTS DEL FORMULARIO DE CREACIÓN ENTIDAD */
    $('.mayus_entidad').keyup(function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusCadaPalabra(textoEscrito));
    });

    /* SOLO PERMITE INGRESAR NUMEROS */
    $('.soloNumeros').keypress(function(event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode < 48 || keycode > 57) {
        event.preventDefault();
        }
    });
    
});

/* Función para ajustar un Datatable cuando este tenga un scroll vertical */
function autoAdjustColumns(table) {
    var container = table.table().container();
    var resizeObserver = new ResizeObserver(function () {
        table.columns.adjust();
    });
    resizeObserver.observe(container);
}

