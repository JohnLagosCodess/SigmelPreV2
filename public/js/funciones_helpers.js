/* AQUÍ SE CREARÁN LAS FUNCIONES QUE SE IMPLEMENTARÁN PARA VARIAS VISTAS */
$(document).ready(function () {

    $.fn.modal.Constructor.prototype.enforceFocus = function() {};
    
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

    $(document).on('keyup', "input[id^='alerta_naranja_ans_']", function(){
        var inputId = this.id;
        Maximo2Decimales(inputId);
    });

    $(document).on('keyup', "input[id^='alerta_roja_ans_']", function(){
        var inputId = this.id;
        Maximo2Decimales(inputId);
    });

    $(document).on('keyup', "input[id^='porcentaje_pcl']", function(){
        var inputId = this.id;
        Maximo2Decimales(inputId);
    });

    $(document).on('keyup', "input[id^='edicion_nombre_ans_']", function(){
        var textoEscrito = $(this).val();
        $(this).val(LetraMayusPrimeraLetraTexto(textoEscrito));
    });

    $(document).on('keyup', "input[id^='edicion_valor_ans_']", function(){
        var inputId = this.id;
        Maximo2Decimales(inputId);
    });

    $(document).on('keyup', "input[id^='edicion_alerta_naranja_ans_']", function(){
        var inputId = this.id;
        Maximo2Decimales(inputId);
    });

    $(document).on('keyup', "input[id^='edicion_alerta_roja_ans_']", function(){
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
        // Expresión regular para solo números enteros positivos, sin comas ni puntos
        var isInteger = /^[0-9]+$/; 
        if (!isInteger.test(value)) {
            // Si no es un número entero positivo válido, limpiamos el valor del input
            $(input).val("");
        }
    }

    $(document).on("input", '[id^="posicionFoleo"], [id^="posicion_expediente_"]', function() {
        NumerosEnteros(this);
    });   
    
    // Obtener el botón
    // let mybutton = document.getElementById("id_subir_scroll");

    // // Mostrar el botón cuando el usuario hace scroll hacia abajo 20px desde la parte superior
    // window.onscroll = function() {Subirscroll()};

    // function Subirscroll() {
    //     if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    //         mybutton.style.display = "block";
    //     } else {
    //         mybutton.style.display = "none";
    //     }
    // }

    // // Cuando el usuario hace clic en el botón, se desplaza hacia la parte superior de la página
    // mybutton.onclick = function() {
    //     window.scrollTo({
    //         top: 0,
    //         behavior: 'smooth' // Scroll suave
    //     });
    // }
    
    // $(document).on("input", '[id^="deficienciadecreto3_"]', function() {
    //     NumerosEnteros(this);
    // });
            
    

    $(document).on("input", '[id^="deficienciadecreto3_"]', function() {
        var inputId = this.id;
        Maximo2Decimales(inputId);
    });

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
        $("#oficio_origen").prop("disabled", false);
        $("#GuardarComiteInter").prop('disabled', false);
    } else {
        $("#visar").prop('disabled', true);
        $("#oficio_origen").prop("disabled", true);
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

    $(".initSelect2").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(document).on('click',"#Edicion", async function(e){
        e.preventDefault();
        /**
         * Datos que se incluiran en la modal
         */
        let f_accion =  $('#fecha_accion').val();
        let accion_ejecutar = $("#accion option:selected").text();
        let estado_facturacion = $("#estado_facturacion").val();
        let profecional_asignado = $("#profesional option:selected").text();
        let id_profecional_asignado = $("#profesional option:selected").val();
        let servicio = $("#servicio").val();
        //Limpiamos los campos de la modal
        $("#c_accion_ejecutar, #c_f_accion, #c_e_facturacion, #c_profesional, #c_servicio, #alerta_accion").empty();
        // VALIDACIÓN DEL GUARDADO DEL SUBMODULO ANTES DE EJECUTAR CIERTAS ACCIONES - PBS 068
        if($("#accion").val() && ($("#accion").val() == 144 || $("#accion").val() == 145 || $("#accion").val() == 146)){
            let id_evento = $("#Id_evento").val();
            let id_asignacion = $("#Id_asignacion").val();
            let id_proceso = $("#Id_procesos").val();
            let id_servicio = $('#Id_servicio').val();
            let is_save = await consultaGuardadoSubmodulo(id_evento,id_asignacion,id_proceso,id_servicio);
            if(!is_save){
                $('#modalAlerta').modal('show');
                $('#mensaje_alerta').text('Recuerde que antes de ejecutar ésta acción debe gestionar y guardar el Pronunciamiento. Por favor valide nuevamente');
                setTimeout(() => {
                    $('#modalAlerta').modal('hide');
                    $('#mensaje_alerta').text('');
                }, 4000);
                $("#alerta_accion").removeClass('d-none');
                $("#alerta_accion").append("<i class='fas fa-info-circle'></i><strong>Importante:</strong>Recuerde que antes de ejecutar ésta acción debe gestionar y guardar el Pronunciamiento. Por favor valide nuevamente");
                $("#c_ejecutar_accion").prop('disabled',true);
                return;
            }
        }
        // VALIDACIÓN DEL VISADO DEL SUBMODULO ANTES DE EJECUTAR CIERTAS ACCIONES - PBS068
        if($("#accion").val() && ($("#accion").val() == 141 || $("#accion").val() == 142 || $("#accion").val() == 143 || $("#accion").val() == 175 
        || $("#accion").val() == 176 || $("#accion").val() == 152 || $("#accion").val() == 153 || $("#accion").val() == 154)){
            let id_evento = $("#Id_evento").val();
            let id_asignacion = $("#Id_asignacion").val();
            let id_proceso = $("#Id_procesos").val();
            let id_servicio = $('#Id_servicio').val();
            let is_save = await consultaVisadoSubmodulo(id_evento,id_asignacion,id_proceso,id_servicio);
            if(!is_save){
                $('#modalAlerta').modal('show');
                $('#mensaje_alerta').text('Recuerde que antes de aprobar la calificación debe realizar el visado de la misma. Por favor valide nuevamente');
                setTimeout(() => {
                    $('#modalAlerta').modal('hide');
                    $('#mensaje_alerta').text('');
                }, 4000);
                $("#alerta_accion").removeClass('d-none');
                $("#alerta_accion").append("<i class='fas fa-info-circle'></i><strong>Importante:</strong>Recuerde que antes de aprobar la calificación debe realizar el visado de la misma. Por favor valide nuevamente");
                $("#c_ejecutar_accion").prop('disabled',true);
                return;
            }
        }
        //Si en controversia no han seleccionado una fuente de información PBS068
        if(servicio.startsWith('Controversia')){
            if($("#fuente_info_juntas").val() == ''){
                $("#alerta_accion").removeClass('d-none');
                $("#alerta_accion").append("<i class='fas fa-info-circle'></i><strong>Importante:</strong> Por favor seleccione una fuente de información");
                $("#c_ejecutar_accion").prop('disabled',true);
                return;
            }
        }
        /**
         * De no haber una accion y/o profesional seleccionado no se habilitara el boton de ejecucion
         */
        if(accion_ejecutar == "" || id_profecional_asignado == ""){
           $("#alerta_accion").removeClass('d-none');
           $("#alerta_accion").append("<i class='fas fa-info-circle'></i><strong>Importante:</strong> No se puede ejecutar la accion debido a que no ha seleccionado una accion y/o profesional");
           $("#c_ejecutar_accion").prop('disabled',true);
           profecional_asignado = "";
        }else{
            $("#alerta_accion").addClass('d-none');
            $("#c_ejecutar_accion").prop('disabled',false);
        }

        //Muestra el campo de facturacion si este esta presente
        if(estado_facturacion == ""){
            $("#c_estado_facturacion, #n_confirmarAccion").addClass("d-none");
        }else{
            $("#c_estado_facturacion, #n_confirmarAccion").removeClass("d-none");
            $("#c_e_facturacion").append(estado_facturacion);
        }

        //Se agregan los datos a la modal
        $("#c_accion_ejecutar").append(accion_ejecutar);
        $("#c_f_accion").append(f_accion);
        $("#c_profesional").append(profecional_asignado);
        $("#c_servicio").append(servicio);
        
        //Se ejecuta la accion
        $("#c_ejecutar_accion").off('click').on('click', function() {
            $("#c_ejecutar_accion").prop("disabled",true);
            let id_accion = $("#accion option:selected").val();

            validarAccion_ejecutar(id_accion).then(response => {

                /**
                 * Siempre y cuando la accion este sin ejecutar para el servicio actual podra ser ejecutada, de no ser asi se bloqueara.
                 */
                if (response === "sin_ejecutar") {
                    $("#alerta_accion_ejecutando").removeClass('d-none');

                    let id_formulario = getId_formulario();
                    $(`#${id_formulario}`).submit();

                    setTimeout(() => {
                        $("#alerta_accion_ejecutando").addClass('d-none');
                        location.reload();
                    }, 7000);

                } else {
                    $("#alerta_accion").removeClass('d-none');
                    $("#c_ejecutar_accion").prop('disabled', true);
                    $("#alerta_accion").append("<i class='fas fa-info-circle'></i><strong>Importante:</strong> Ésta acción solo puede ser ejecutada una única vez. Por favor valide el historial de acciones del servicio.");
                }

            });
        });
    });

    // Función que permite solamente dos decimales escribir
    function Maximo2Decimales(idinput){
    $('#'+idinput).on('input', function(){
        var inputValue = $(this).val();
        var decimalCount = (inputValue.split('.')[1] || []).length;        
        if (decimalCount > 2) {
          $(this).val(parseFloat(inputValue).toFixed(2));
        }
        });
    };

    // Función que permite solamente un decimal escribir
    function Maximo1Decimal(idinput){
    $('#'+idinput).on('input', function(){
        var inputValue = $(this).val();
        var decimalCount = (inputValue.split('.')[1] || []).length;        
        if (decimalCount > 1) {
          $(this).val(parseFloat(inputValue).toFixed(1));
        }
    });
    }

    //evento cuando se le de click en el boton de eliminar
    $(document).on('click', '.btn_eliminar_radicado', function() {  
        // Deshabilita todo los botones de eliminar menos el clickeado
        $('.btn_eliminar_radicado').css({
            'color': '#ff4f4f',
            'pointer-events': 'none', //deshabilita el click
            'opacity': '0.5'
        });

        //Habilita el boton seleccionado
        $(this).css({
            'color': 'red', 
            'pointer-events': 'auto',
            'opacity': '1'
        });

        //Habilita nuevamente el boton tras finalizar el proceso.
        let resultado = eliminar_evento($(this).data('id_comunicado'));
        if(resultado == 'ok'){
            $('.btn_eliminar_radicado').css({
                'color': 'red',
                'pointer-events': 'auto', //habilita el click
                'opacity': '1'
            });
        }
    });
    historial_servicios();

    //Mantiene el foco dentro del modal, principalmente para que sea compatible con select2
    $.fn.modal.Constructor.prototype._enforceFocus = function () {
        var that = this;
        $(document).on('focusin.modal', function (e) {
        if ($(e.target).hasClass('select2-input')) {
           return true;
        }

        if (that && that.$element && that && that.$element && that.$element[0] !== e.target && !that.$element.has(e.target).length){
            that.$element.focus();
        }
        });
    };

    $(document).on('click', "#limpiar_cache", function () {
        limpiar_cache();
    });
    
});

/**
 * Limpia la cache del navegador
 */
function limpiar_cache() {
    // Crear un arreglo para almacenar los recursos
    let archivosJS = [];
    let archivosCSS = [];

    // Recoger los scripts cargados
    let scripts = document.scripts;
    for (let script of scripts) {
        if (script.src !== "") archivosJS.push(script.src);
    }

    // Recoger los archivos CSS
    $('link[rel="stylesheet"]').each(function () {
        let href = $(this).attr('href');
        if (href !== "") archivosCSS.push(href);
    });

    // Añadir el parámetro 'cacheBust' a los recursos JS
    archivosJS.forEach((resource) => {
        const resourceUrl = new URL(resource, window.location.href);
        resourceUrl.searchParams.set('cacheBust', new Date().getTime()); // Añadir timestamp a la URL

        // Reemplazar la URL de los archivos JS en el DOM
        let scriptElement = document.querySelector(`script[src="${resource}"]`);
        if (scriptElement) {
            scriptElement.src = resourceUrl;
        }
    });

    // Añadir el parámetro 'cacheBust' a los archivos CSS
    archivosCSS.forEach((resource) => {
        const resourceUrl = new URL(resource, window.location.href);
        resourceUrl.searchParams.set('cacheBust', new Date().getTime()); // Añadir timestamp a la URL

        // Reemplazar la URL de los archivos CSS en el DOM
        let linkElement = document.querySelector(`link[href="${resource}"]`);
        if (linkElement) {
            linkElement.href = resourceUrl;
        }
    });

    localStorage.clear();
    sessionStorage.clear();
    location.reload(true);
}
/**
 * Procesa una alerta y la muestra para un proceso correspondiente, homologando la acccion del boton sobre el formulario al cual esta enlazado.
 * @param {string} id_boton selector del boton donde se estara escuchando el evento. 
 * @param {string} id_form selector del formulario al cual se delegará el evento submit
 * @param {int} proceso id del proceso para el cual se esta procesando la alerta 
 * @param {string} tipo_alerta El tipo de la alerta que se estara mostrando. alerta -> muestra una modal interactiva con botones de accion, otro - Muestra solo una modal informativa.
 */
function procesar_alertas_gestion(id_boton, id_form, proceso, tipo_alerta = "alerta") {
    let timet_out = "";

    //Remueve el footer slot, por alguna razon en algunos casos se agrega el footer slot y en otros no
    $("#alertas_gestion").find(".modal-footer").remove();

    let id_servicio = $("#newIdservicio").val();

    /**
     * Obtiene el nombre del servicio en funcion del id indicado
     * @param {int} id_servicio 
     * @returns
     */
    let nombre_servicio = (id_servicio) => {
            let servicios = {
                1: "Determinación del origen ATEL",
                6: "Calificación Técnica", 
                7: "Recalificacion", 
                8: "Revisión pensión",
                9: "Pronunciamiento PCL",
                3: "Pronunciamiento Origen",
                13: "Controversia PCL",
                12: "Controversia Origen"
            };
        return servicios[id_servicio] || "";
    }

    /**
     * Obtiene la alerta correspondiente al proceso indicado
     * @param {string} proceso 
     * @returns 
     */
    let data_alerta = (proceso) => {
        let subtitulo = $("#nombre_afiliado").val() + " " + $("#tipo_documento").find(":selected").text() + " " + $("#nro_identificacion").val();

        let match = {
            creacion_evento: {
                titulo: "¡Usted está registrando la información del afiliado!",
                subtitulo: subtitulo.trim() == "Seleccione" ? "" : subtitulo.toUpperCase(),
                subtitulo2: "Al cual se le ejecutará el siguiente movimiento:",
                cuerpo: {
                    Fecha_radicacion: $("#fecha_radicacion").val(),
                    Proceso: $("#proceso").find(":selected").text(),
                    Servicio: $("#servicio").find(":selected").text(),
                    Accion: $("#accion").find(":selected").text(),
                    Fecha_alerta: $("#fecha_alerta").val(),
                    Profesional_asignado: $("#profesional").find(":selected").text() == "Seleccione" ? "" : $("#profesional").find(":selected").text(),
                    Fecha_vencimiento: $("#fecha_vencimiento_visual").val()
                },
                alerta_gestion: "<span>Asignacion <b><u>No</u></b> guardada. !Valide nuevamente!</span>"
            },
            consultador_evento: {
                titulo: "Está a punto de realizar el siguiente movimiento:",
                cuerpo: {
                    Fecha_radicacion: $("#fecha_radicacion_nuevo_proceso").val() || "",
                    Proceso: $("[name^='selector_nuevo_proceso']").find(":selected").text() || "",
                    Servicio: $("[name^='selector_nuevo_servicio']").find(":selected").text() || "",
                    Accion: $("[name^='nueva_accion_nuevo_proceso']").find(":selected").text() || "",
                    Fecha_alerta: $("#nueva_fecha_alerta_nuevo_proceso").val() || "",
                    Profesional_asignado: $("[name^='nuevo_profesional_nuevo_proceso']").find(":selected").text() || "",
                    Fecha_vencimiento: $("#fecha_visual_vencimiento_nuevo_proceso_").val() || ""
                },
                alerta_gestion: "<span>Movimiento <b><u>No</u></b> guardada. !Valide nuevamente!</span>"
            },
            dto: {
                titulo: "Está a punto de emitir la siguiente calificación:",
                cuerpo: {
                    Servicio: nombre_servicio(id_servicio),
                    Tipo_de_evento: $("#tipo_evento").find(":selected").text() || "",
                    Origen: $("#origen_dto_atel").find(":selected").text() || "",
                },
                alerta_gestion: "<span>Calificacion <b><u>No</u></b> guardada. !Valide nuevamente!</span>",
            },
            visar_dto: {
                titulo: "Está a punto de visar la siguiente calificación:",
                cuerpo: {
                    Servicio: nombre_servicio(id_servicio),
                    Tipo_de_evento: $("#tipo_evento").find(":selected").text() || "",
                    Origen: $("#origen_dto_atel").find(":selected").text() || "",
                },
                alerta_gestion: "<span>Visado <b><u>No</u></b> guardada. !Valide nuevamente!</span>",
                footer: "<span class='text-danger text-center h6 mt-2'><b>Nota:</b>  Recuerde que, posterior al visado, no podrá realizar ajustes a la calificación<span>"
            },
            calificacion_tec:{
                subtitulo: `Se iniciará la <b>${nombre_servicio(id_servicio)}</b> con el decreto <b>${$("#decreto_califi").find(":selected").text()}</b>; posterior a su confirmación, el sistema <b>NO</b> le permitirá modificar el decreto de calificación.`,
                cuerpo: {
                    Servicio: nombre_servicio(id_servicio),
                    Decreto: $("#decreto_califi").find(":selected").text(),
                },
                alerta_gestion: "<span>No se realizo el guardado. !Valide nuevamente!</span>",
            },
            calificacion_tec_pericial:{
                subtitulo: `Esta a punto de emitir la siguiente calificación:`,
                cuerpo: {
                    Servicio: nombre_servicio(id_servicio),
                    Tipo_de_evento: $("#tipo_evento").find(":selected").text() || "",
                    Origen:  $("#tipo_origen").find(":selected").text(),
                    $pPCL: $("#porcentaje_pcl").val() + "%",
                    Decreto: $("#decreto_califi").find(":selected").text(),
                },
                alerta_gestion: "<span>Calificacion <b><u>No</u></b> guardada. !Valide nuevamente!</span>",
            },
            calificacion_tec_visar:{
                subtitulo: `Esta a punto de visar la siguiente calificación:`,
                cuerpo: {
                    Servicio: nombre_servicio(id_servicio),
                    Tipo_de_evento: $("#tipo_evento").find(":selected").text(),
                    Origen: $("#tipo_origen").find(":selected").text(),
                    $pPCL: $("#porcentaje_pcl").val() + "%",
                    Decreto: $("#decreto_califi").find(":selected").text(),
                },
                alerta_gestion: "<span>No se realizo el guardado. !Valide nuevamente!</span>",
                footer: "<span class='text-danger text-center h6 mt-2'><b>Nota:</b>  Recuerde que, posterior al visado, no podrá realizar ajustes a la calificación<span>"
            },
            pronunciamiento_pcl:{
                subtitulo: `Está a punto de emitir el siguiete pronunciamiento:`,
                cuerpo: {
                    Servicio: nombre_servicio(id_servicio),
                    Tipo_de_evento: $("#tipo_evento").find(":selected").text(),
                    Origen: $("#tipo_origen").find(":selected").text(),
                    $iPCL: $("#porcentaje_pcl").val() + "%",
                    pronunciamiento_ante_la_calificación: $("[name^='decision_pr']:checked").val(),
                },
                alerta_gestion: "<span>Pronunciamiento <b>No</b> guardado. !Valide nuevamente!</span>",
            },
            pronunciamiento_origen:{
                subtitulo: `Está a punto de emitir el siguiete pronunciamiento:`,
                cuerpo: {
                    Servicio: nombre_servicio(id_servicio),
                    Tipo_de_evento: $("#tipo_evento").find(":selected").text(),
                    Origen: $("#tipo_origen").find(":selected").text(),
                    pronunciamiento_ante_la_calificación: $("[name^='decision_pr']:checked").val(),
                },
                alerta_gestion: "<span>Pronunciamiento <b>No</b> guardado. !Valide nuevamente!</span>",
            },
            juntas:{
                subtitulo: `Está a punto de emitir el siguiente pronunciamiento ante la JRCI:`,
                cuerpo: {
                    Servicio: nombre_servicio(id_servicio),
                    Tipo_de_evento: $("#Tipo_evento_juntas").val(),
                    Origen_JRCI: $("#origen_jrci_emitido").find(":selected").text(),
                    $iPCL_JRCI:  $("#porcentaje_pcl_jrci_emitido").val() || "",
                    pronunciamiento_ante_Dictamen_de_JRCI: $("[name^='decision_dictamen_jrci']:checked").val(),
                },
                alerta_gestion: "<span>Pronunciamiento <b>No</b> guardado. !Valide nuevamente!</span>",
            }
        }

        return match[proceso] || null;
    }

    /**
     * Dibuja la alerta dentro del modal
     * @param {string} titulo 
     * @param {string} subtitulo 
     * @param {string} subtitulo2 
     * @param {string} cuerpoHTML 
     * @param {string} footer 
     */
    const mostrarAlerta = (titulo, subtitulo, subtitulo2, cuerpoHTML,footer) => {
        const $alerta = $("#contenido_header_gestion");
        $alerta.find("#titulo").html(titulo);
        $alerta.find("#subtitulo").html(subtitulo);
        $alerta.find("#subtitulo2").html(subtitulo2);

        $("#content_footer").html(footer);
        $("#cuerpo_gestion").empty().append(cuerpoHTML);
        $("#alerta_gestion").addClass("d-none");
        $("#contenido_header_gestion, #info_footer, #ejecutar_gestion").removeClass('d-none');
        $("#alertas_gestion").show();
    };

    /**
     * Dibuja el cuerpo de la alerta dentro de la modal
     * @param {Array} cuerpo
     * @returns 
     */
    const generarCuerpoHTML = (cuerpo) => {
        return Object.entries(cuerpo)
            .map(([key, value]) => {
                const columna1 = transformar_caracteres(key);
                return `
                    <div class="col-5"><span class="h6"><b>${columna1}:</b></span></div>
                    <div class="col-7"><span class="h6">${value}</span></div>
                `;
            })
            .join('');
    };

    const ocultarAlerta = () => {
        $("#alertas_gestion").hide();
        $("#alerta_gestion").addClass("d-none");
    };

    /**
     * Dependiento del tipo de alerta mostrara los botones interaccion para homologar el evento submit, sino mostrara solo una alerta
     */
    if (tipo_alerta == "alerta") {
        $(id_boton).click(function () {
            clearTimeout(timet_out);
            const datos = data_alerta(proceso);
            const cuerpoHTML = generarCuerpoHTML(datos.cuerpo);
            $("#cerrar_modal").remove();
            $("#no_ejecutar_gestion").removeClass("d-none");
            mostrarAlerta(datos.titulo, datos.subtitulo, datos.subtitulo2, cuerpoHTML,datos.footer);

            $("#ejecutar_gestion").off('click').on('click', function () {
                const $form = $(id_form);
                if ($form[0].checkValidity()) {
                    $form.trigger("submit");
                } else {
                    $form[0].reportValidity();
                }
                ocultarAlerta();
            });
        });
    }else {
        //Actualmente solo para dto
        $("#alertas_gestion").show();
        $("#contenido_header_gestion, #info_footer, #ejecutar_gestion").addClass('d-none');
        $("#no_ejecutar_gestion").addClass("d-none");
        $("#cerrar_modal").remove();
        $("#no_ejecutar_gestion").after('<button class="btn btn-danger d-none" id="cerrar_modal" data-dismiss="modal">Cerrar</button>');
        $("#cerrar_modal").removeClass("d-none");
        $("#cuerpo_gestion").empty().html(`
        <span>
            El formulario a gestionar dependerá de la selección registrada en el campo Tipo de evento.
            Adicionalmente, esta información actualizará el campo de Tipo de Evento del formulario 
            <u><b>Edición de Evento</b></u>
        </span>
    `);

        timet_out = setTimeout(ocultarAlerta, 6000);
    }

    $("#no_ejecutar_gestion").click(function () {
        const alertaHTML = data_alerta(proceso).alerta_gestion;
        $("#alerta_gestion").removeClass("d-none").html(alertaHTML);
        timet_out =  setTimeout(ocultarAlerta, 3000);
    });

    $("#cerrar_modal").click(function(){clearTimeout(timet_out); ocultarAlerta()});
    
}

/**
 * Transforma un string de acuerdo al patron que este definido dentro del string
 * _ : lo remplaza por espacios en blanco
 * $p : transforma el string en una pregunta
 * $i : agrega el signo % de acuerdo a la coincidencia del patron
 * 
 * @param {string} target 
 * @returns 
 */
function transformar_caracteres(target) {
    let remplazar = {
        "_": (string) => string.replace(/_/g, ' '),  //Transforma los espacios bajo en guiones
        "$p": (string) => string.includes("$p") ? "¿" + string.replace("$p","") + "?" : string, // si el string tiene la letra $p lo convertira en una pregunta ¿..?
        "$i": (string) => string.replace("$i", '%') // si el string tiene la letra $i lo convertira en un $
    };

    if (typeof target === "string") {
        let string_transformado = target;

        for (let key in remplazar) {
            if (remplazar.hasOwnProperty(key)) {
                // Ejecutamos la función correspondiente a cada clave
                string_transformado = remplazar[key](string_transformado);
            }
        }

        return string_transformado;
    }

    return undefined;
}
// Función que permite solamente dos decimales escribir
function Maximo2Decimales(idinput){
    $('#'+idinput).on('input', function(){
        var inputValue = $(this).val();
        var decimalCount = (inputValue.split('.')[1] || []).length;        
        if (decimalCount > 2) {
        $(this).val(parseFloat(inputValue).toFixed(2));
        }
    });
};

// Función que permite solamente un decimal escribir
function Maximo1Decimal(idinput){
    $('#'+idinput).on('input', function(){
        var inputValue = $(this).val();
        var decimalCount = (inputValue.split('.')[1] || []).length;        
        if (decimalCount > 1) {
        $(this).val(parseFloat(inputValue).toFixed(1));
        }
    });
}

/**
 * Obtiene el historial de servicio para el evento consultado con base a la identificacion del afiliado.
 */
function historial_servicios(){
    let identificacion = $("#identificacion").val() || $("#nro_identificacion").val();
    if(identificacion == ""){
        return;
    }

    let procesos = {
        1: () => {
            return {
                'Nombre': 'Origen',
                'id': 'form_modulo_califi_Origen_',
                'url': $("#action_modulo_calificacion_Origen").val()
            }
        },
        2: () => {
            return {
                'Nombre': 'PCL',
                'id': 'form_modulo_calificacion_pcl_',
                'url': $("#action_modulo_calificacion_pcl").val()
            }
        },
        3: () => {
            return {
                'Nombre': 'Juntas',
                'id': 'form_modulo_califi_Juntas_',
                'url': $("#action_modulo_calificacion_Juntas").val()
            }
        },
        default: () => { return null}
    }

    let afiliado =  $("#nombre_afiliado").val();
    let tipo_doc = $("#identificacion").data('tipo') || $("#tipo_documento").text();
    $('#historial_servicios .modal-header h4').append(`${afiliado} - ${tipo_doc} - ${identificacion}`);

    let token = $("input[name='_token']").val();
    let data = {
        '_token': token,
        'n_doc': identificacion,
        'tipo': tipo_doc
    }

    $.post('/historial_servicios',data,function(response){
        let tabla_historial = $("#listado_historial_s").DataTable({
            orderCellsTop: true,
            fixedHeader: true,
            scrollY: 350,
            scrollX: true,
            autoWidth: false,
            data: response,
            pageLength: 5,
            order: [[5, 'desc']],
            destroy:true,
            columns: [
                { "data": "F_registro" },
                { "data": null, render: function(data){
                        let action = $("#formularioLlevarEdicionEvento").attr("action");
                        let editar_evento = `<form action="${action}" id="formularioLlevarEdicionEvento" method="POST">
                            <input type="hidden" name="_token" value="${token}">
                            <input type="hidden" name="regresar_anterior"  value="regresar_anterior">
                            <input type="hidden" name="newIdEvento" value="${data.ID_evento}">
                            <input type="hidden" name="newIdProceso" value="${data.Id_proceso}">
                            <input type="hidden" name="newIdServicio" value="${data.Id_Servicio}">
                            <input type="hidden" name="newIdAsignacion" value="${data.Id_Asignacion}">
                            <button type="submit" class="btn btn-icon-only text-info btn-sm"><strong>${data.ID_evento}</strong></button>
                        </form>`;
                        return editar_evento;
                    } 
                },
                { "data": "Nombre_servicio" },
                { "data": "Nombre_estado" },
                { "data": "Accion" },
                { "data": "F_accion" },
                { "data": null, render: function(data){
                        if (data.Id_proceso == null) return "";
                        let action = procesos[data.Id_proceso]().url;
                        let form = `
                            <form action="${action}" method="POST">
                                <input type="hidden" name="_token" value="${token}">
                                <input type="hidden" name="badera_modulo_principal_origen" value="desdebus_mod_origen">
                                <input type="hidden" name="newIdEvento" value="${data.ID_evento}">
                                <input type="hidden" name="newIdProceso" value="${data.Id_proceso}">
                                <input type="hidden" name="newIdServicio" value="${data.Id_Servicio}">
                                <input type="hidden" name="newIdAsignacion" value="${data.Id_Asignacion}">
                               <button type="submit" class="btn" style="border: none; background: transparent;">
                                    <i class="far fa-eye text-info"></i>
                                </button>
                            </form>`;

                        return form;
                    } 
                },
            ],
            language: {
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
            }
        });
    
        autoAdjustColumns(tabla_historial);
    });

}
// aqui mauro
/**
 * Obtiene el id del formulario para el modulo principal actual
 * @returns Id del formulario cargado en el dom
 */
function getId_formulario(){
    let disponibles = ["#form_calificacionJuntas","#form_calificacionPcl","#form_calificacionOrigen"];
    // Busca el primer ID que esté presente en el DOM
    let formularioPresente = $(disponibles.join(',')).filter(':visible').first();

    // Retorna el ID o null si no se encontró ninguno
    return formularioPresente.length > 0 ? formularioPresente.attr('id') : null;
}

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
 * Fuerza los atributos min - max del input de tipo date
 * @param {event} selector del input 
 */
function enforceMinMax(el) {
    if (el.val() !== "") {
        if (parseInt(el.val()) < parseInt(el.attr('min'))) {
            el.val(el.attr('min'));
        }
        if (parseInt(el.val()) > parseInt(el.attr('max'))) {
            el.val(el.attr('max'));
        }
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
 * Función para agregar la validación a los inputs de tipo fecha actualmente se esta usando en los que se generan dinamicamente
 * @param {string} input Id del input tipo date al cual se le quieren agregar las validaciones generales de fecha.
 * @param {string} hideButton Id del boton que se quiere ocultar para evitar acciones de guardado o demás en caso de que el input este con error.
*/
function agregarValidacionFecha(input,hideButton = null) {
    let today = new Date().toISOString().split("T")[0];
    
    $(input).on('change', function() {
        // Validación de fecha mínima
        if (this.value < '1900-01-01') {
            $(`#${this.id}_alerta`).text("La fecha ingresada no es válida. Por favor valide la fecha ingresada").removeClass("d-none");
            if(hideButton != null){
                $(`#${hideButton}`).addClass("d-none");
            }
            return;
        }
        // Validación de fecha máxima (hoy)
        if (this.value > today) {
            $(`#${this.id}_alerta`).text("La fecha ingresada no puede ser mayor a la actual").removeClass("d-none");
            if(hideButton != null){
                $(`#${hideButton}`).addClass("d-none");
            }
            return;
        }
        // Limpiar mensaje de error si la fecha es válida
        if(hideButton != null){
            $(`#${hideButton}`).removeClass("d-none");
        }
        $(`#${this.id}_alerta`).text('').addClass("d-none");
    });
}
 /*
 * Funcion para esconder boton, hasta que la tabla no tenga un registro
 * @returns void
 */
function EsconderBotonGuardado(id_boton, id_tabla, id_alerta) {
    let tbody = document.querySelector(`#${id_tabla} tbody`);
    let table = document.getElementById(`${id_tabla}`);
    console.log(tbody.children)
    // let rowCount = tabla.rows({ filter: 'applied' }).nodes().toArray().filter(row => !$(row).hasClass('dataTables_empty')).length
    if (tbody && tbody.children.length > 0) {
        $(`#${id_boton}`).removeClass('d-none');
    } else {
        $(`#${id_boton}`).addClass('d-none'); // Oculta el botón si no hay filas
        $(`.${id_alerta}`).removeClass('d-none');
    }
}
/**
 * Funcion para descargar los documentos generales
 * @returns void
 */
function descargarDocumentos(){
    let evento =  $('#newId_evento').val();
    let servicio =  $(".Id_servicio").val();
    let asignacion =  $("#newId_asignacion").val();

    let token = $("input[name='_token']").val();

    let datos = {
        '_token': token,
        'parametro': 'descargaCompleta',
        'IdEvento': evento,
        'IdServicio': servicio,
        'IdAsignacion': asignacion,

    };
    
    if (evento === undefined || evento === '' || servicio === undefined || servicio === '' || asignacion === undefined || asignacion === '') {
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

// Función para enviar el formulario y retornar una promesa
function enviarFormulario(selectorFormulario) {
    return new Promise((resolve, reject) => {
        // Enviar el formulario
        $(selectorFormulario).trigger('submit');

        // Escuchar un evento que confirme el envío (puedes adaptarlo)
        $(selectorFormulario).on('submit', function(event) {
            event.preventDefault(); // Esto puede omitirse si no quieres prevenir el envío

            // Aquí podrías verificar si el formulario fue enviado correctamente
            resolve(); // Llamar a resolve cuando se complete el envío
        });

        // Opcional: Rechazar la promesa si ocurre un error en un tiempo límite
        setTimeout(() => reject("El formulario tardó demasiado en procesarse"), 5000);
    });
}
/*
* Recibe una cadena string con todos los IDs de destinatario de un comunicado y el 'destinatario' con el fin d
* @returns string | null
*/
function retornarIdDestinatario(ids_destinatario, destinatario){
        if(ids_destinatario != null && ids_destinatario != undefined){
            //Se usa split para armar un array con todos los ids de destinatario
            let ids = ids_destinatario.split(',');
            //Se validan los prefijos en base al destinatario
            const prefijos = {
                'Afiliado': 'AFI',
                'Empleador': 'EMP',
                'eps': 'EPS',
                'afp': 'AFP',
                'arl': 'ARL',
                'afp_conocimiento': 'FPC',
                'jrci': 'JRC',
                'jnci': 'JNC'
            };
            // Se busca el prefijo con el que deberia venir el ID en base al destinatario
            let Id_Destinatario = ids.find(finder => finder !== '' && finder.startsWith(prefijos[destinatario]));

            return Id_Destinatario && Id_Destinatario !== '' ? Id_Destinatario.split('_')[1] : null
        }
    }

    /*
        Muestra un spinner en el centro de la pantalla para indicarle al usuario que x recurso se esta cargando
    */
    function showLoading() {
        $('#loading').addClass('loading');
        $('#loading-content').addClass('loading-content');
    }
    /*
        Esconde el spinner para indicarle al usuario que x recurso fue cargado
    */
    function hideLoading() {
        $('#loading').removeClass('loading');
        $('#loading-content').removeClass('loading-content');  
    }
    /*
        Consulta en la base de datos si un registro de correspondencia fue guardado con estado distinto a notificado
        si encuentra el registro devuelve el tipo de correspondencia para que el sistema cargue la información desde la tabla de correspondencias y no
        lo haga desde la de comunicados.
    */
    function consultarRegistroPorIdDestinatario(id_destinatario){
        if(id_destinatario){
            return new Promise((resolve, reject) => {
                let datos = {
                    '_token': $('input[name=_token]').val(),
                    'id_destinatario': id_destinatario
                };
        
                $.ajax({
                    url: '/getInfoCorrespByIdDest',
                    type: 'POST',
                    data: datos,
                    beforeSend: function () {
                        showLoading();
                    },
                    success: function (response) {
                        if (response.length > 0) {
                            resolve(response[0]['Tipo_correspondencia']);
                        } else {
                            resolve(null);
                        }
                    },
                    error: function (error) {
                        reject(error);
                    },
                    complete: function () {
                        hideLoading();
                    }
                });
            });
        }
        else{
            return null;
        }
    }
    /*
        Consulta si el submodulo fue guardado para poder permitir el guardado de alguna de las siguientes acciones 
        144, 145 o 146
    */
    function consultaGuardadoSubmodulo(id_evento, id_asignacion, id_proceso, id_servicio){
        return new Promise((resolve, reject) => {
            data = {
                '_token': $('input[name=_token]').val(),
                'id_evento': id_evento,
                'id_asignacion': id_asignacion,
                'id_proceso': id_proceso,
                'id_servicio': id_servicio,
            }
            $.ajax({
                type: 'POST',
                url: '/validarGuardadoSubmodulo',
                data: data,
                beforeSend: function(){
                    $('#c_ejecutar_accion').addClass("descarga-deshabilitada");
                },
                success: function(response) {
                    if(response){
                        resolve(response[0]);
                    }
                },
                complete: function(){
                    $('#c_ejecutar_accion').removeClass("descarga-deshabilitada");
                }
            });
        })
    }
    /*
        Consulta si el submodulo fue visado para poder permitir el guardado de alguna de las siguientes acciones 
        141, 142, 143, 175, 176, 152, 153, 154
    */
    function consultaVisadoSubmodulo(id_evento, id_asignacion, id_proceso, id_servicio){
        return new Promise((resolve, reject) => {
            data = {
                '_token': $('input[name=_token]').val(),
                'id_evento': id_evento,
                'id_asignacion': id_asignacion,
                'id_proceso': id_proceso,
                'id_servicio': id_servicio,
            }
            $.ajax({
                type: 'POST',
                url: '/validarVisadoSubmodulo',
                data: data,
                beforeSend: function(){
                    $('#c_ejecutar_accion').addClass("descarga-deshabilitada");
                },
                success: function(response) {
                    if(response){
                        resolve(response[0]);
                    }
                },
                complete: function(){
                    $('#c_ejecutar_accion').removeClass("descarga-deshabilitada");
                }
            });
        })
    }
    /*
        Cuando seleccionen la acción de ejecutar acción de DEVOLVER ASIGNACIÓN, se haran las validaciones solicitadas
        en el PBS068 y si todo sale correcto se retornara la información del usuario, si no, se retornara null;  
    */
    function consultaUltimoUsuarioEjecutarAccion(data_ult_usuario){
        return new Promise((resolve, reject) => { 
            $.ajax({
                type:'POST',
                url:'/capturarUsuarioUltAccion',
                data: data_ult_usuario,
                beforeSend: function () {
                    showLoading();
                },
                success:function (data) {
                    if(data){
                        resolve(data);
                    }
                    resolve(null);
                },
                complete: function () {
                    hideLoading();
                }
            });
        })
    }

/**
 * 
 * @param {int} id_accion Id de la accion que se va a ejecutar 
 * @returns Estado de la accion: sin_ejecutar - ejecutada
 */
function validarAccion_ejecutar(id_accion) {
    return new Promise((resolve, reject) => {
        if (id_accion === "") {
            reject("ID de acción vacío");
            return;
        }
        
        let datos = {
            '_token': $('input[name=_token]').val(),
            'bandera': 'validar_accion',
            'accion_ejecutar': id_accion,
            'Id_cliente': $("#cliente").data('id'),
            'Id_servicio': $("#Id_servicio").val(),
            'ID_evento': $("#newIdEvento").val(),
            'Id_Asignacion': $("#newIdAsignacion").val()
        };

        $.post("/validar_acciones", datos)
            .done(function(response) {
                resolve(response);
            })
            .fail(function(error) {
                reject(error);
            });
    });
}

/**
 * Funcion para eliminar un comunicado en especifico, principalmente cuando este se encuentra repetido
 * @param {int} id_comunicado id del comunicado a eliminar 
 * @param {int} proceso proceso al cual pertenece el comunicado
 * @returns 
 */
function eliminar_evento(id_comunicado,proceso){
    if(id_comunicado == "" || proceso == ""){
        return;
    }

    let mensajeConfirmacion = '¿Está seguro de eliminar este registro? tenga en cuenta que una vez eliminado, este no se podrá recuperar.';

    let data = {
        '_token': $("input[name='_token']").val(),
        'id_servicio': $("#Id_Servicio").val() || $("#Id_servicio").val(),
        'id_evento': $("#newIdEvento").val(),
        'id_asignacion': $("#newIdAsignacion").val(),
        'id_comunicado': id_comunicado
    };

    if(confirm(mensajeConfirmacion)){
        $.post('/eliminar_evento',data,function(response){
            if(response == 'ok'){
                $('.alerta_externa_comunicado').removeClass('d-none');
                $('.alerta_externa_comunicado').append('<strong>El comunicado se elimino de manera correcta</strong>');
                setTimeout(function(){
                    $('.alerta_externa_comunicado').addClass('d-none');
                    $('.alerta_externa_comunicado').empty();
                    location.reload();
                }, 3000);
            }
        });
    }
    return 'ok';
}

/**
 * Funcion para verificar si dentro los comunicados hay algun radicado duplicado
 */
function radicados_duplicados(tabla){

    let radicados_usados = [];
    let radicados_duplicados = [];

    $(`#${tabla} tr`).each(function() {
        let radicado = $(this).find('td:first-child').text().trim(); //Obtenemos el radicado y el id del comunicado ubicado en la primera columna
        let id_comunicado = $(this).find('td:first-child').data('id_comunicado')

        //So el radicado actual ya fue usado habilita el boton de eliminar
        if (radicados_usados.includes(radicado)) {
            radicados_duplicados.push(radicado);
            $(this).find('td:last-child').append(`<button class="btn_eliminar_radicado" data-id_comunicado="${id_comunicado}" style="border: none; background: transparent;"><i class="fas fa-trash" style="color: red;"></i></button>`);
        } else {
            radicados_usados.push(radicado);
        }
    });

    //Muestra la alerta informando la cantidad de radicados duplicados
    if(radicados_duplicados.length > 0){

        $("#alertaRadicado").show();
        $("#alerta_radicado_msj").empty();
        $("#alerta_radicado_msj").append(`se encontraron <strong>${radicados_duplicados.length }</strong> radicados duplicados, por favor verifique.`);
        $("#alertaRadicado").show();

        setTimeout(() => {
            $("#alertaRadicado").hide();
        }, 3500);
    }   
}

/**
 * Función para ejecutar varias peticiones de manera asíncrona
 * @param  {...Promise} peticiones - Las peticiones a ejecutar (promesas).
 * @returns {Promise} - Devuelve una promesa que se resuelve cuando todas las peticiones han finalizado.
 * @example 
 * let peticion = $.ajax({
 *     type: 'POST',
 *     url: '/url',
 *     data: {...}
 *
 * peticion_asincrona(peticion, otraPeticion);
 */
function peticion_asincrona(...peticiones) {
    if (peticiones.length === 0) {
        console.error('No se han proporcionado peticiones.');
        return Promise.reject('No se han proporcionado peticiones.');
    }

    return Promise.allSettled(peticiones)
        .then(results => {
            results.forEach((result, index) => {
                if (result.status === 'fulfilled') {
                    console.log(`Petición ${index + 1} completada con éxito:`, result.value);
                } else {
                    console.error(`Petición ${index + 1} fallida:`, result.reason);
                }
            });
        })
        .finally(() => {
            console.log('Todas las peticiones han sido procesadas');
        });
}

/* Función para colorear el caso ya sea naranja o roja dependiendo del ANS */
function ColoreadoEventosANS(Tiempo_actual,Fecha_alerta_naranja, Fecha_alerta_roja, tabla_bandeja) {
    if (Fecha_alerta_naranja > Fecha_alerta_roja) {
        if (Fecha_alerta_roja != "") {
    
            let alertaFechaRoja_ans = new Date(Fecha_alerta_roja);
            
            if (Tiempo_actual >= alertaFechaRoja_ans) {  
                // console.log("ROJA: "+alertaFechaRoja_ans);
                $(tabla_bandeja).find('td').css({'color':'red', 'font-weight': 'bold'});
            }
        }
        if (Fecha_alerta_naranja != "") {
            
            let alertaFechaNaranja_ans = new Date(Fecha_alerta_naranja);
            
            if (Tiempo_actual >= alertaFechaNaranja_ans) {  
                // console.log("NARANJA: "+alertaFechaNaranja_ans);
                $(tabla_bandeja).find('td').css({'color':'orange', 'font-weight': 'bold'});
            }
        }
    }
    else{
        if (Fecha_alerta_naranja != "") {
                                
            let alertaFechaNaranja_ans = new Date(Fecha_alerta_naranja);
            
            if (Tiempo_actual >= alertaFechaNaranja_ans) {  
                // console.log("NARANJA: "+alertaFechaNaranja_ans);
                $(tabla_bandeja).find('td').css({'color':'orange', 'font-weight': 'bold'});
            }
        }
        if (Fecha_alerta_roja != "") {

            let alertaFechaRoja_ans = new Date(Fecha_alerta_roja);
            
            if (Tiempo_actual >= alertaFechaRoja_ans) {  
                // console.log("ROJA: "+alertaFechaRoja_ans);
                $(tabla_bandeja).find('td').css({'color':'red', 'font-weight': 'bold'});
            }
        }
    }
}

/**
 * Obtiene la diferencia entre dos fechas
 * @param {string} fecha_inicial 
 * @param {string} fecha_final 
 * @returns devuelve la cantidad de dias entre las fechas
 */
function diff_date(fecha_inicial, fecha_final){
    let diff = moment(new Date(fecha_final)).diff(new Date(fecha_inicial), 'months', true);

    return Math.round(diff);
}

/**
 * Calcula la antiguedad en la empresa
 */
function calc_antiguedad_empresa(){
    if($("#fecha_ingreso").val() !== '' && $("#fecha_retiro").val() !== ''){
        let antiguedad = diff_date($("#fecha_ingreso").val(),$("#fecha_retiro").val());

        $("#antiguedad_empresa").val(antiguedad);
    }
}