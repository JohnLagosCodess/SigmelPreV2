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
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });

    /* INPUTS DEL FORMULARIO DE EDICIÓN DE EQUIPOS DE TRABAJO */
    $(document).on('keyup', "input[id^='editar_nombre_equipo_trabajo_']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusCadaPalabra(textoEscrito));
    });
    $(document).on('keyup', "textarea[id^='editar_descripcion_equipo_trabajo_']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });

    /* INPUTS Y TEXT AREAS DEL MODAL Solicitud Documentos - Seguimientos Módulo Calificación PCL*/
    $(document).on('keyup', "input[id^='nombre_otro_doc_']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });
	
	$(document).on('keyup', "textarea[id^='documento_soli_fila_']", function(){
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
    // Text  y inputs areas vista calificacion tecnica
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

    $(document).on('keyup', "input[id^='Asunto']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));        
    });

    $(document).on('keyup', "textarea[id^='cuerpo_comunicado']", function(){
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

    $(document).on('keyup', "input[id^='tabladecreto3_']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));        
    });

    $(document).on('keyup', "input[id^='tablatitulodecreto3_']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));        
    });

    // Text  y inputs areas vista Recalificacion tecnica

    $(document).on('keyup', "textarea[id^='descripcion_nueva_calificacion']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));        
    });

    // Tesxt Asunto y Sustentacion Pronunciamiento

    $(document).on('keyup', "textarea[id^='asunto_cali']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));        
    }); 

    $(document).on('keyup', "textarea[id^='sustenta_cali']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));        
    });    

    // TEXTAREA DESCRIPCIÓN FURAT (DTO ATEL)
    $(document).on('keyup', "textarea[id^='descripcion_FURAT']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito)); 
    });

    // TEXTAREA JUSTIFICACIÓN REVISION ORIGEN (DTO ATEL)
    $(document).on('keyup', "textarea[id^='justificacion_revision_origen']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito)); 
    });

    // INPUT OTROS (DTO ATEL)
    $(document).on('keyup', "input[id^='otros']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));        
    });

    // TEXTAREA SUSTENTACION CALFICACION ORIGEN (DTO ATEL)
    $(document).on('keyup', "textarea[id^='sustentacion_califi_origen']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito)); 
    });

    // INPUT NOMBRE ENTIDAD HEREDADA (DTO ATEL)
    $(document).on('keyup', "input[id^='entidad_enfermedad']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));        
    });


    // TEXTAREA SUSTENTACION ADICION DX
    $(document).on('keyup', "textarea[id^='sustentacion_adicion_dx']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito)); 
    });

    // OTROS DOC ADICION DX
    $(document).on('keyup', "input[id^='otros_docs']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));        
    });
	
	// Clase para poner  la primera letra en mayuscula
    $('.CadaLetraMayus').keypress(function(event) {
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));   
    });
    // Clase para poner solo la primera letra en mayuscula
    $('.soloPrimeraLetraMayus').keypress(function(event) {
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));   
    });

    // TEXTAREA FORMULARIO NUEVA ACCION
    $(document).on('keyup', "textarea[id^='descrip_accion']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });

    // INPUT O TEXT AREA DE FORMULARIO NUEVA ACCION
    $("#accion").keyup(function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });

    /* TODO LO RELACIONADO A CLIENTES (CREACIÓN Y EDICIÓN) */
    $("#otro_tipo_cliente").keyup(function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });

    $("#nombre_cliente").keyup(function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });

    $(document).on('keyup', "input[id^='nombre_sucursal_']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });

    $(document).on('keyup', "input[id^='nombre_gerente_sucursal_']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });

    $("#nombre_del_firmante_cliente").keyup(function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });

    $("#cargo_del_firmante_cliente").keyup(function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });

    $("#nombre_del_firmante_proveedor").keyup(function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });
    
    $("#cargo_del_firmante_proveedor").keyup(function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });

    $(document).on('keyup', "input[id^='nombre_ans_']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });

    $(document).on('keyup', "textarea[id^='descripcion_ans_']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });

    $(document).on('keyup', "input[id^='valor_ans_']", function(){
        var inputId = this.id;
        Maximo2Decimales(inputId);
    });

    $(document).on('keyup', "input[id^='porcentaje_pcl']", function(){
        var inputId = this.id;
        Maximo2Decimales(inputId);
    });

    /* TODO LO CORRESPONDIENTE A LA PARAMETRIZACION */
    $(document).on('keyup', "input[id^='tiempo_alerta_origen_atel_']", function(){
        var inputId = this.id;
        Maximo2Decimales(inputId);
    });

    $(document).on('keyup', "input[id^='bd_tiempo_alerta_origen_atel_']", function(){
        var inputId = this.id;
        Maximo2Decimales(inputId);
    });

    $(document).on('keyup', "textarea[id^='motivo_movimiento_origen_atel_']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });

    $(document).on('keyup', "textarea[id^='bd_motivo_movimiento_origen_atel_']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });

    $(document).on('keyup', "input[id^='tiempo_alerta_calificacion_pcl_']", function(){
        var inputId = this.id;
        Maximo2Decimales(inputId);
    });

    $(document).on('keyup', "input[id^='bd_tiempo_alerta_calificacion_pcl_']", function(){
        var inputId = this.id;
        Maximo2Decimales(inputId);
    });

    $(document).on('keyup', "textarea[id^='motivo_movimiento_calificacion_pcl_']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });

    $(document).on('keyup', "textarea[id^='bd_motivo_movimiento_calificacion_pcl_']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });


    $(document).on('keyup', "input[id^='tiempo_alerta_juntas_']", function(){
        var inputId = this.id;
        Maximo2Decimales(inputId);
    });

    $(document).on('keyup', "input[id^='bd_tiempo_alerta_juntas_']", function(){
        var inputId = this.id;
        Maximo2Decimales(inputId);
    });

    $(document).on('keyup', "textarea[id^='motivo_movimiento_juntas_']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });

    $(document).on('keyup', "textarea[id^='bd_motivo_movimiento_juntas_']", function(){
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
    
    function NumerosEnteros(input) {
        var value = $(input).val();      
        if (!Number.isInteger(Number(value))) {
          $(input).val("");
        }
    }

    
    
    $(document).on("input", '[id^="deficienciadecreto3_"]', function() {
        NumerosEnteros(this);
    });   
    
    function Maximo2Decimales(idinput){
        $('#'+idinput).on('input', function(){
            var inputValue = $(this).val();
            var decimalCount = (inputValue.split('.')[1] || []).length;        
            if (decimalCount > 2) {
              $(this).val(parseFloat(inputValue).toFixed(2));
            }
        });
    };

    function Maximo1Decimal(idinput){
        $('#'+idinput).on('input', function(){
            var inputValue = $(this).val();
            var decimalCount = (inputValue.split('.')[1] || []).length;        
            if (decimalCount > 1) {
              $(this).val(parseFloat(inputValue).toFixed(1));
            }
        });
    }

    $(document).on("input", '[id^="pcl_anterior"]', function() {
        var inputId = this.id;
        Maximo2Decimales(inputId);
    });

    $(document).on("input", '[id^="suma_combinada"]', function() {
        var inputId = this.id;
        Maximo2Decimales(inputId);
    });

    $(document).on("input", '[id^="resultado_Deficiencia_"]', function() {
        var inputId = this.id;
        Maximo2Decimales(inputId);
    });
    

    /* Input que permita registrar en formato contabilidad */
    $(".soloContabilidad").on({
        "focus": function(event) {
            $(event.target).select();
        },
        "input": function(event) {
            let value = event.target.value;
            value = value.replace(/\D/g, ""); // Remove non-digit characters
            value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"); // Add comma for thousands separators
            value = value.replace(/(\d)(\.(\d{2}))$/, "$1$2"); // Add period for decimal places
            value = "$" + value; // Add "$" at the beginning
            event.target.value = value;
        }
    });

    $(document).on("input", '[id^="Total_Deficiencia50"]', function() {
        var inputId = this.id;
        Maximo2Decimales(inputId);
    });

    $(document).ready(function(){
        $('#suma_combinada').on('input', function(){
          var inputValue = $(this).val();
          var decimalCount = (inputValue.split('.')[1] || []).length;
      
          if (decimalCount > 2) {
            // Si el número tiene más de 2 decimales, truncar el valor
            $(this).val(parseFloat(inputValue).toFixed(2));
          }
        });
    });

     /* INPUTS DEL FORMULARIO DE CREACIÓN ENTIDAD */
    $('.mayus_entidad').keyup(function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusCadaPalabra(textoEscrito));
    });

    $('.mayus_general').keyup(function(){
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
	
	 /* SOLO PERMITTE DOS DECIMALES */
    $('.soloDosDecimales').keypress(function(event) {
        var inputId = this.id;
        Maximo2Decimales(inputId);
    });

    /* Funcionalidad para habilitar el formulario de visado para el rol de Comité (Id del rol: 10) */
    var id_rol = $("#id_rol").val();
    if (id_rol == 10) {
        $("#visar").prop('disabled', false);
        $("#GuardarComiteInter").prop('disabled', false);
    } else {
        $("#visar").prop('disabled', true);
        $("#GuardarComiteInter").prop('disabled', true);
    }

    /* Datatable para el listado de documentos (Módulos Principales) */
    var listado_documentos_ed = $("#listado_documentos_ed").DataTable({
        "destroy":true,
        "paging":false,
        "ordering": false,
        "searching": true,
        "scrollCollapse": true,
        "scrollX": true,
        "scrollY": 350,
        "language":{                
            "search": "Buscar",
            "lengthMenu": "Mostrar _MENU_ registros",
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
        },
        "initComplete": function(settings, json) {
            // Eliminar el contenedor <div class="col-sm-12 col-md-6"></div> que rodea al campo de búsqueda
            $('#listado_documentos_ed_filter').parent().prev('.col-sm-12.col-md-6').remove();
        }
    });

    $('#listado_documentos_ed_filter').addClass('pull-left');
    autoAdjustColumns(listado_documentos_ed);
    
    // recargar ventana en cargue documentos en modulos principales
    $("#recargar_ventana").click(function(){
        location.reload();
    });
});

/* Función para ajustar un Datatable cuando este tenga un scroll vertical */
function autoAdjustColumns(table) {
    var container = table.table().container();
    //console.log(container);
    if (container instanceof Element) {
      var resizeObserver = new ResizeObserver(function () {
        table.columns.adjust().draw();
      });
  
      resizeObserver.observe(container);
    } else {
      console.error("'container' is not a valid DOM element.");
    }
}

/**
 * La función para validar y establecer un limite en la fecha dentro de un input, mostrando a su vez un mensaje de advertencia debajo del elemento.
 * @param {string} Idselector Corresponde al id del input
 * @param {string} operador Operador con el cual se estara evaluando la validacion
 * @param {string} fecha Fecha con la cual se estara evaluando
 * @param {string} info Mensaje de advertencia al cumplirse la validacion
*/
function Validarfecha(IdSelector,operador = '>',fecha = null,info = 'La fecha no debe ser mayor a'){
    let fechaActual = '';
    let operadores = {
        '<' : function(a,b) {return a < b},
        '>' : function(a,b) {return a > b},
        '>=' : function(a,b) {return a >= b},
        '<=' : function(a,b) {return a <= b},
        '!==' : function(a,b) {return a !== b},
        '==' : function(a,b) {return a == b}
    }
    if(fecha == null){
        fechaActual = new Date().toISOString().slice(0,10); //Obtenemos la fecha en Ymd
    }else{
        fechaActual = fecha;
    }


    $(IdSelector).off("change").on("change",function(){
        let FechaDigitada = $(this).val();
        let etiqueta = $(IdSelector).next('i');
        let mensaje = `<i style="color:red;">${info} ${fechaActual}.</i>`;
        
        if(operadores[operador](FechaDigitada,fechaActual)){
            $(IdSelector).val(fechaActual); //Seteamos la el input como fecha maxima la actual
            if(etiqueta.length > 0){
                etiqueta.remove();
            }
            $(IdSelector).after(mensaje); //Agregamos el mensaje despues del input
        }else{
            etiqueta.remove();
        }

    });

}
/**
 * Funcion para descargar los documentos generales
 * @returns void
 */
function descargarDocumentos(){
    let evento =  $('#newId_evento').val();
    let servicio =  $(".Id_servicio").val();
    let token = $("input[name='_token']").val();

    let datos = {
        '_token': token,
        'parametro': 'descargaCompleta',
        'IdEvento': evento,
        'IdServicio': servicio,

    };
    
    if (evento === undefined || evento === '' || servicio === undefined || servicio === '') {
        console.error('Debe suministrar un evento y el servicio asignado para poder descargar los documentos');
        return;
    }

    $("#descargar_documentos").prop('disabled',true);
    $("#status_spinner").removeClass('d-none');  

    $.ajax({
        url: '/descargar-documentos',
        type: 'POST',
        data: datos,
        xhrFields: {
            responseType: 'blob' // El archivo al venir en la respuesta necesitamos construir el archivo desde el lado del cliente
        },
        success:function(response,status, jqXHR){
            const blob = new Blob([response], { type: 'application/zip' });

            //Se crea un enlace temporal para poder descargar el archivo devuelto
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.href = url;
            link.setAttribute('download', 'ListadoDocumentos.zip'); // Corresponde al nombre del archivo al descargarse
            document.body.appendChild(link);
            link.click();

            $("#status_spinner").addClass('d-none');  
            $('.mostrar_exito').removeClass('d-none');
            $('.mostrar_exito').empty();
            $('.mostrar_exito').append('<strong>Archivo generado de manera correcta.</strong>');

            // Eliminamos el enlace despues de descargarse y los mensajes de estado.
            setTimeout(function() {
                document.body.removeChild(link);
                window.URL.revokeObjectURL(url);
                $('.mostrar_exito').addClass('d-none');
                $("#descargar_documentos").prop('disabled',false);
            }, 3000);
        },
    });
}

/**
 * Valida si el evento se encuentra en la bandeja de notificacion
 * @returns bool
 */
function ubicacionEvento() {
    return new Promise((resolve, reject) => {
        let data = {
            '_token': $('input[name=_token]').val(),
            'bandera': 'info_evento',
            'id_asignacion': $("#newIdAsignacion").val(),
            'evento': $("#newIdEvento").val()
        };
        
        $.ajax({
            type: 'POST',
            url: '/informacionBandejaNotifi',
            dataType: 'json',
            data: data,
            success: function(response) {
                let status = 'No';
                if(response != '' && response != undefined){
                    let status = response[0].Notificacion == 'Si'; 
                }
                
                resolve(status); //true o false 
            }
        });
    });
}