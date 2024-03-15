$(document).ready(function(){

    var idRol = $("#id_rol").val();

    // Obtener sessionStorage del navegador
    //var posicionActual = $(window).scrollTop(); // Guarda cuando recarga la pagina
    var posicionMemoria = sessionStorage.getItem("scrollTop"); // Guarda session scrollTop

    if (posicionMemoria != null) {
        $(window).scrollTop(posicionMemoria);
        sessionStorage.removeItem("scrollTop");
        //console.log("Se ha restaurado la posición guardada en memoria");
    } else {
        //console.log("No se ha encontrado una posición guardada en memoria");
    }
    //guardar la posición de desplazamiento actual en la memoria
    $(window).on("beforeunload", function() {
        sessionStorage.setItem("scrollTop", $(window).scrollTop());
    });

    // Inicializacion del select2 de listados  Módulo pronunciamiento PCL
    $(".primer_calificador").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".nombre_calificador").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".tipo_evento").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".tipo_origen").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".junta_regional_cual").select2({
        width: '100%',
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".reviso").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".tipo_entidad").select2({
        width: '100%',
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".nombre_entidad").select2({
        width: '100%',
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    // llenado de selectores
    let token = $('input[name=_token]').val();

    //Listado de primer calificador
    let datos_lista_primer_califi = {
        '_token': token,
        'parametro':"lista_primer_calificador"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresPronunciamientoOrigen',
        data: datos_lista_primer_califi,
        success:function(data) {
            let IdCalifi = $('select[name=primer_calificador]').val();
            let primercali = Object.keys(data);
            for (let i = 0; i < primercali.length; i++) {
                if (data[primercali[i]]['Id_Entidad'] != IdCalifi) {  
                    $('#primer_calificador').append('<option value="'+data[primercali[i]]["Id_Entidad"]+'">'+data[primercali[i]]["Tipo_Entidad"]+'</option>');
                }
            }
        }
    });
    // Listado Nombre Entidad
    $('#primer_calificador').change( function(){
        $('#nombre_calificador').prop('disabled', false);
        let id_primer_calificador = $('#primer_calificador').val();
        let datos_nombre_entidad = {
            '_token': token,
            'parametro' : "lista_nombre_entidad",
            'id_primer_calificador': id_primer_calificador
        };

        $.ajax({
            type:'POST',
            url:'/selectoresPronunciamientoOrigen',
            data: datos_nombre_entidad,
            success:function(data) {
                $("#nombre_calificador").empty();
                let IdEntidad = $('select[name=nombre_calificador]').val();
                console.log(IdEntidad);
                $('#nit_calificador,#dir_calificador,#mail_calificador,#telefono_calificador,#depar_calificador,#ciudad_calificador').val(""); //Vaciar Campos
                $('#nombre_calificador').append('<option value="" selected>Seleccione</option>');
                let nombrecalifi = Object.keys(data);
                for (let i = 0; i < nombrecalifi.length; i++) {
                    if (data[nombrecalifi[i]]['Id_Entidad'] != IdEntidad) { 
                        $('#nombre_calificador').append('<option value="'+data[nombrecalifi[i]]["Id_Entidad"]+'">'+data[nombrecalifi[i]]["Nombre_entidad"]+'</option>');
                    }
                }
            }
        });
    });
    //Carga datos de entidad
    $('#nombre_calificador').change( function(){
        let id_primer_calificador_da = $('#nombre_calificador').val();
        let datos_nombre_entidad_da = {
            '_token': token,
            'parametro' : "lista_nombre_entidad_da",
            'id_primer_calificador_da': id_primer_calificador_da
        };
        $.ajax({
            type:'POST',
            url:'/selectoresPronunciamientoOrigen',
            data: datos_nombre_entidad_da,
            success:function(data) {
                // Añadir Nit
                $("#nit_calificador").empty();
                $("#nit_calificador").val(data[0]["Nit_entidad"]);
                // Añadir Direccion
                $("#dir_calificador").empty();
                $("#dir_calificador").val(data[0]["Direccion"]);
                // Añadir Email
                $("#mail_calificador").empty();
                $("#mail_calificador").val(data[0]["Emails"]);
                // Añadir telefonos
                $("#telefono_calificador").empty();
                $("#telefono_calificador").val(data[0]["Telefonos"]);
                // Añadir Departamento
                $("#depar_calificador").empty();
                $("#depar_calificador").val(data[0]["Nombre_departamento"]);
                // Añadir Ciudad
                $("#ciudad_calificador").empty();
                $("#ciudad_calificador").val(data[0]["Nombre_municipio"]);
            }
        });
    
    });
    //Listado de tipo pronunciamiento
    let datos_lista_tipo_pronuncia = {
        '_token': token,
        'parametro':"lista_tipo_pronuncia"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresPronunciamientoOrigen',
        data: datos_lista_tipo_pronuncia,
        success:function(data) {
            let IdPronuncia = $('select[name=tipo_pronunciamiento]').val();
            let tipopronuncia = Object.keys(data);
            for (let i = 0; i < tipopronuncia.length; i++) {
                if (data[tipopronuncia[i]]['Id_Parametro'] != IdPronuncia) { 
                    $('#tipo_pronunciamiento').append('<option value="'+data[tipopronuncia[i]]["Id_Parametro"]+'">'+data[tipopronuncia[i]]["Nombre_parametro"]+'</option>');
                }
            }
        }
    });
    //Listado de tipo evento
    let datos_lista_tipo_evento = {
        '_token': token,
        'parametro':"lista_tipo_evento"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresPronunciamientoOrigen',
        data: datos_lista_tipo_evento,
        success:function(data) {
            //console.log(data);
            let IdtipoEvento = $('select[name=tipo_evento]').val();
            let tipoevento = Object.keys(data);
            for (let i = 0; i < tipoevento.length; i++) {
                if (data[tipoevento[i]]['Id_Evento'] != IdtipoEvento) {  
                    $('#tipo_evento').append('<option value="'+data[tipoevento[i]]["Id_Evento"]+'">'+data[tipoevento[i]]["Nombre_evento"]+'</option>');
                }
            }
        }
    });
    //Listado de origen
    let datos_lista_tipo_origen = {
        '_token': token,
        'parametro':"lista_tipo_origen"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresPronunciamientoOrigen',
        data: datos_lista_tipo_origen,
        success:function(data) {
            let IdOrigen = $('select[name=tipo_origen]').val();
            let tipoorigen = Object.keys(data);
            for (let i = 0; i < tipoorigen.length; i++) {
                if (data[tipoorigen[i]]['Id_Parametro'] != IdOrigen) {  
                    $('#tipo_origen').append('<option value="'+data[tipoorigen[i]]["Id_Parametro"]+'">'+data[tipoorigen[i]]["Nombre_parametro"]+'</option>');
                }
            }
        }
    });
     //Listado juntas regionales
     let datos_lista_regional_junta = {
        '_token': token,
        'parametro':"lista_regional_junta"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresPronunciamientoOrigen',
        data: datos_lista_regional_junta,
        success:function(data) {
            //console.log(data);
            let IdJunta = $('select[name=junta_regional_cual]').val();
            let primercali = Object.keys(data);
            for (let i = 0; i < primercali.length; i++) {
                if (data[primercali[i]]['Id_juntaR'] != IdJunta) {  
                    $('#junta_regional_cual').append('<option value="'+data[primercali[i]]["Id_juntaR"]+'">'+data[primercali[i]]["Ciudad_Junta"]+'</option>');
                }
            }
        }
    });

    // Validación checkbox destinatario principal
    $("#destinatario_principal").change(function(){
        if ($(this).is(':checked')) {
            $(".columna_tipo_entidad").removeClass('d-none');
            $(".columna_tipo_entidad").slideDown('slow');

            $(".columna_nombre_entidad").removeClass('d-none');
            $(".columna_nombre_entidad").slideDown('slow');
        }else{
            $(".columna_tipo_entidad").addClass('d-none');
            $(".columna_tipo_entidad").slideUp('slow');

            $(".columna_nombre_entidad").addClass('d-none');
            $(".columna_nombre_entidad").slideUp('slow');

            $('#nombre_entidad').empty();
            $('#nombre_entidad').append('<option></option>');

        }
    });

    // validación dato bd del check destinatario principal para mostrar los selectores de entidad y nombre de entidad
    if ($("#bd_checkbox_destinatario_principal").val() == "Si" ) {
        $(".columna_tipo_entidad").removeClass('d-none');
        $(".columna_tipo_entidad").slideDown('slow');
        $(".columna_nombre_entidad").removeClass('d-none');
        $(".columna_nombre_entidad").slideDown('slow');
    } else {
        $(".columna_tipo_entidad").addClass('d-none');
        $(".columna_tipo_entidad").slideUp('slow');
        $(".columna_nombre_entidad").addClass('d-none');
        $(".columna_nombre_entidad").slideUp('slow');
    }

    // listado de tipos de entidad
    let datos_lista_tipo_entidad = {
        '_token': token,
        'parametro':"lista_tipo_entidad"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresPronunciamientoOrigen',
        data: datos_lista_tipo_entidad,
        success:function(data) {
            //console.log(data);
            $('#tipo_entidad').empty();
            $('#tipo_entidad').append('<option></option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if($("#bd_tipo_entidad").val() != '' && data[claves[i]]["Id_Entidad"] == $("#bd_tipo_entidad").val()){
                    $('#tipo_entidad').append('<option value="'+data[claves[i]]["Id_Entidad"]+'" selected>'+data[claves[i]]["Tipo_Entidad"]+'</option>');
                }else{
                    $('#tipo_entidad').append('<option value="'+data[claves[i]]["Id_Entidad"]+'">'+data[claves[i]]["Tipo_Entidad"]+'</option>');
                }
            }
        }
    });

    // Listado de nombre de entidades dependiendo del tipo de entidad
    if ($("#bd_tipo_entidad").val() != "") {
        let datos_nombre_entidad = {
            '_token': token,
            'parametro':"nombre_entidad",
            'id_tipo_entidad': $("#bd_tipo_entidad").val()
        };

        $.ajax({
            type:'POST',
            url:'/selectoresPronunciamientoOrigen',
            data: datos_nombre_entidad,
            success:function(data) {
                //console.log(data);
                $('#nombre_entidad').empty();
                $('#nombre_entidad').append('<option></option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    if($("#bd_nombre_entidad").val() != '' && data[claves[i]]["Id_Entidad"] == $("#bd_nombre_entidad").val()){
                        $('#nombre_entidad').append('<option value="'+data[claves[i]]["Id_Entidad"]+'" selected>'+data[claves[i]]["Nombre_entidad"]+'</option>');
                    }else{
                        $('#nombre_entidad').append('<option value="'+data[claves[i]]["Id_Entidad"]+'">'+data[claves[i]]["Nombre_entidad"]+'</option>');
                    }
                }
            }
        });
    };

    $('#tipo_entidad').change(function(){
        let datos_nombre_entidad = {
            '_token': token,
            'parametro':"nombre_entidad",
            'id_tipo_entidad': $(this).val()
        };
        $.ajax({
            type:'POST',
            url:'/selectoresPronunciamientoOrigen',
            data: datos_nombre_entidad,
            success:function(data) {
                $('#nombre_entidad').empty();
                $('#nombre_entidad').append('<option></option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $('#nombre_entidad').append('<option value="'+data[claves[i]]["Id_Entidad"]+'">'+data[claves[i]]["Nombre_entidad"]+'</option>');
                }
            }
        });
    });

    /* VALIDACIÓN MOSTRAR FECHA EVENTO DE ACUERDO A TIPO EVENTO  */ 
    $('#tipo_evento').change(function () {
        var valorSeleccionado = $(this).val();
        if (valorSeleccionado != 2) {
            $('#div_tipo_evento').removeClass('d-none');
            $('#fecha_evento').prop('required', true);
        } else if (valorSeleccionado == 2) {
            $('#div_tipo_evento').addClass('d-none');
            $('#fecha_evento').prop('required', false);
        } 
    });
    var t_evento = $('#tipo_evento').val();
    if (t_evento == 2) {
        $('#div_tipo_evento').addClass('d-none');
        $('#fecha_evento').prop('required', false);
    }else{
        $('#div_tipo_evento').removeClass('d-none');
        $('#fecha_evento').prop('required', true);
    }

    // Funcionalidad para insertar las etiquetas
    $("#sustenta_cali").summernote({
        height: 'auto',
        toolbar: false
    });
    $('.note-editing-area').css("background", "white");
    $('.note-editor').css("border", "1px solid black");

    $("#btn_insertar_cie10").click(function(e){
        e.preventDefault();

        var etiqueta_diagnosticos = "{{$diagnosticos_cie10}}";
        $('#sustenta_cali').summernote('editor.insertText', etiqueta_diagnosticos);
    });

    $("#btn_insertar_origen").click(function(e){
        e.preventDefault();

        var etiqueta_origen = "{{$origen}}";
        $('#sustenta_cali').summernote('editor.insertText', etiqueta_origen);
    });

    /* Etiquetas para la proforma del Desacuerdo */
    
    $("#btn_insertar_nro_dictamen_pri_cali").click(function(e){
        e.preventDefault();
        var cursorPos = $("#asunto_cali").prop('selectionStart');
        var currentValue = $("#asunto_cali").val();
        var newValue = currentValue.slice(0, cursorPos) + '{{$nro_dictamen_pri_cali}}' + currentValue.slice(cursorPos);
        // Actualiza el valor del input
        $("#asunto_cali").val(newValue);
        // Coloca el cursor después de la etiqueta
        $("#asunto_cali").prop('selectionStart', cursorPos + 27);
        $("#asunto_cali").prop('selectionEnd', cursorPos + 27);
        $("#asunto_cali").focus();
    });

    $("#btn_insertar_fecha_dictamen_pri_cali").click(function(e){
        e.preventDefault();
        var cursorPos = $("#asunto_cali").prop('selectionStart');
        var currentValue = $("#asunto_cali").val();
        var newValue = currentValue.slice(0, cursorPos) + '{{$fecha_dictamen_pri_cali}}' + currentValue.slice(cursorPos);
        // Actualiza el valor del input
        $("#asunto_cali").val(newValue);
        // Coloca el cursor después de la etiqueta
        $("#asunto_cali").prop('selectionStart', cursorPos + 29);
        $("#asunto_cali").prop('selectionEnd', cursorPos + 29);
        $("#asunto_cali").focus();
    });

    $("#btn_insertar_nombre_afiliado").click(function(e){
        e.preventDefault();
        var etiqueta_nombre_afiliado = "{{$nombre_afiliado}}";
        $("#sustenta_cali").summernote('editor.insertText', etiqueta_nombre_afiliado);
    });

    $("#btn_insertar_tipo_doc").click(function(e){
        e.preventDefault();
        var etiqueta_tipo_doc = "{{$tipo_documento}}";
        $("#sustenta_cali").summernote('editor.insertText', etiqueta_tipo_doc);
    });
    
    $("#btn_insertar_nro_identificacion").click(function(e){
        e.preventDefault();
        var etiqueta_nro_identificacion = "{{$nro_identificacion}}";
        $("#sustenta_cali").summernote('editor.insertText', etiqueta_nro_identificacion);
    });

    $("#btn_insertar_cie10_nombrecie10").click(function(e){
        e.preventDefault();
        var etiqueta_cie10_nombrecie10 = "{{$cie10_nombrecie10_origencie10}}";
        $("#sustenta_cali").summernote('editor.insertText', etiqueta_cie10_nombrecie10);
    });


    /* Validación mostrar item correspondencia pero cuando carga inicialmente la página */
    var opt_predeterminada = $("[name='decision_pr']").filter(":checked").val();

    if(opt_predeterminada == "Acuerdo"){
        $("#mostrar_mensaje_importante").addClass('d-none');
        $("#mostrar_mensaje_importante1").addClass('d-none');

        $("#btn_insertar_nro_dictamen_pri_cali").prop('disabled', true);
        $("#btn_insertar_fecha_dictamen_pri_cali").prop('disabled', true);
        $("#btn_insertar_nombre_afiliado").prop('disabled', true);
        $("#btn_insertar_tipo_doc").prop('disabled', true);
        $("#btn_insertar_nro_identificacion").prop('disabled', true);
        $("#btn_insertar_cie10_nombrecie10").prop('disabled', true);
    }else if(opt_predeterminada == "Desacuerdo") {
        $("#mostrar_mensaje_importante").removeClass('d-none');
        $("#mostrar_mensaje_importante1").removeClass('d-none');

        $("#btn_insertar_nro_dictamen_pri_cali").prop('disabled', false);
        $("#btn_insertar_fecha_dictamen_pri_cali").prop('disabled', false);
        $("#btn_insertar_nombre_afiliado").prop('disabled', false);
        $("#btn_insertar_tipo_doc").prop('disabled', false);
        $("#btn_insertar_nro_identificacion").prop('disabled', false);
        $("#btn_insertar_cie10_nombrecie10").prop('disabled', false);
    }

    /* VALIDACIÓN MOSTRAR ITEM DE CORRESPONDECIA */
    var opt_correspondencia;
    $("[name='decision_pr']").on("change", function(){
        opt_correspondencia = $(this).val();
        $(this).val(opt_correspondencia);
        iniciarIntervalo_correspon();
        
        $("#insertar_mensaje_importante").html("");
        if (opt_correspondencia == "Acuerdo") {

            $("#mostrar_mensaje_importante").addClass('d-none');
            $("#mostrar_mensaje_importante1").addClass('d-none');
            
            $("#btn_insertar_nro_dictamen_pri_cali").prop('disabled', true);
            $("#btn_insertar_fecha_dictamen_pri_cali").prop('disabled', true);
            $("#btn_insertar_nombre_afiliado").prop('disabled', true);
            $("#btn_insertar_tipo_doc").prop('disabled', true);
            $("#btn_insertar_nro_identificacion").prop('disabled', true);
            $("#btn_insertar_cie10_nombrecie10").prop('disabled', true);
            
            $("#asunto_cali").val("CONCEPTO MÉDICO DE DICTAMEN PÉRDIDA DE CAPACIDAD LABORAL ");
            $('#sustenta_cali').summernote('code', '');
            
        } else if(opt_correspondencia == "Desacuerdo") {

            $("#mostrar_mensaje_importante").removeClass('d-none');
            $("#mostrar_mensaje_importante1").removeClass('d-none');
            
            $("#btn_insertar_nro_dictamen_pri_cali").prop('disabled', false);
            $("#btn_insertar_fecha_dictamen_pri_cali").prop('disabled', false);
            $("#btn_insertar_nombre_afiliado").prop('disabled', false);
            $("#btn_insertar_tipo_doc").prop('disabled', false);
            $("#btn_insertar_nro_identificacion").prop('disabled', false);
            $("#btn_insertar_cie10_nombrecie10").prop('disabled', false);

            $("#asunto_cali").val("RECURSO DE REPOSICIÓN EN SUBSIDIO DE APELACIÓN FRENTE A DICTAMEN N° {{$nro_dictamen_pri_cali}} DEL {{$fecha_dictamen_pri_cali}}");
            var texto_insertar = "<p>Respetados Señores,</p><p>Yo, HUGO IGNACIO GÓMEZ DAZA, identificado como aparece al pie de mi firma, actuando en nombre y representación de SEGUROS DE VIDA ALFA S.A. Aseguradora que expidió el seguro previsional a la AFP PORVENIR S.A., debidamente facultado para ello, en atención al dictamen de la referencia, estando dentro de los términos de ley, me permito interponer RECURSO DE REPOSICIÓN Y EN SUBSIDIO DE APELACIÓN ante la Junta, por los siguientes motivos:</p><p>Nuestra inconformidad se dirige a la calificación de ORIGEN dictaminada al afiliado {{$nombre_afiliado}} {{$tipo_documento}} {{$nro_identificacion}}, donde califican los diagnósticos: {{$cie10_nombrecie10_origencie10}}.</p><p>1. (Descripción de recurso)</p><p>Por lo anterior, presentamos el recurso de reposición en subsidio de apelación, contra el origen de la patología de {{$cie10_nombrecie10_origencie10}}, con el fin que la Junta dictamine el origen de la patología del paciente dando aplicación a la Ley 1562 de 2012 y Decreto 1477 de 2014 como normatividad vigente. En caso de que no se revoque, solicitamos se de curso a la apelación ante la Junta Regional de Calificación, e informarnos con el fin de consignar los honorarios respectivos.</p><p>ANEXO:</p><p>Certificado de existencia y representación legal expedido por la Superintendencia Financiera.</p><p>NOTIFICACIONES:</p><p>Recibiré notificaciones en la Carrera 10 # 18 – 36 Edificio Córdoba Piso 4, en la ciudad de Bogotá, D.C.</p><p>Cualquier Información adicional con gusto le será suministrada,</p>";
            $('#sustenta_cali').summernote('code', texto_insertar);

        }
    });
    
    // Función para validar items a mostrar
    const tiempoDeslizamiento2 = 'slow';
    function iniciarIntervalo_correspon() {
          // Selección de los elementos que se deslizarán
          const elementosDeslizar2 = [
             '.row_correspondencia'
         ];
         var elaboro2 = $('#elaboro_data').val();
         $("#elaboro").empty();
         $("#elaboro").val(elaboro2);
         //Listado de lideres grupo trabajo
         let datos_lista_lider_grupo = {
                 '_token': token,
                 'parametro':"lista_lider_grupo",
                 'nom_usuario_session':elaboro2
         };
         $.ajax({
             type:'POST',
             url:'/selectoresPronunciamiento',
             data: datos_lista_lider_grupo,
             success:function(data) {
                 let Nreviso = $('select[name=reviso]').val();
                 let lidergru = Object.keys(data);
                 for (let i = 0; i < lidergru.length; i++) {
                     if (data[lidergru[i]]['name'] != Nreviso) {  
                         $('#reviso').append('<option value="'+data[lidergru[i]]["name"]+'">'+data[lidergru[i]]["name"]+'</option>');
                     }
                 }
             }
         });
         intervaloCo = setInterval(() => {
             switch (opt_correspondencia) {
                 case "Acuerdo":
                     elementosDeslizar2.forEach(elemento => {
                         $(elemento).slideDown(tiempoDeslizamiento2);
                     }); 
                     $('#reviso').prop('required', true);
                 break;
                 case "Desacuerdo": 
                     elementosDeslizar2.forEach(elemento => {
                         $(elemento).slideDown(tiempoDeslizamiento2);
                     });
                     $('#reviso').prop('required', true);
                 break;
 
                 default:
                     // Deslizar hacia arriba (ocultar) los elementos
                     elementosDeslizar2.forEach(elemento => {
                         $(elemento).slideUp(tiempoDeslizamiento2);
                     });
                     $('#reviso').prop('required', false);
                 break;
             }
 
         }, 500);
 
    }
    /* VALIDACIÓN MOSTRAR CUAL JUNTA REGIONAL */
    $('#div_cual').hide();
    $("#junta_regional").change(function() {
        // Verificar si está marcado
        if ($(this).prop("checked")) {
            $('#div_cual').slideDown('slow');
            $('#junta_regional_cual').prop('required', true);
        } else {
            $('#div_cual').slideUp('up');
            $('#junta_regional_cual').prop('required', false);
        }
    });

    var junta_regionalcheckbox = document.getElementById("junta_regional");

    // Verificar si está marcado al cargar la página
    if (junta_regionalcheckbox.checked) {
        $('#div_cual').slideDown('slow');
        $('#junta_regional_cual').prop('required', true);
    }

    /*Validar Cargue de archivo Pronuncia*/
    $("#DocPronuncia").change(function() {
        var file = this.files[0];
        var allowedExtensions = /(\.doc|\.docx|\.pdf)$/i; // Expresión regular para permitir solo extensiones .doc y .pdf
  
        if (!allowedExtensions.test(file.name)) {
           //alert('La extensión del archivo no es válida. Solo se permiten archivos con extensión .doc y .pdf.');
           $('#div_alerta_archivo').removeClass('d-none');
           $('.alerta_archivo').append('<strong>La extensión del archivo no es válida. Solo se permiten archivos con extensión .doc .docx .pdf</strong>');                                            
           setTimeout(function(){
               $('#div_alerta_archivo').addClass('d-none');
               $('.alerta_archivo').empty();
           }, 4000);
           // Resetea el valor del input de tipo "file" para que el usuario seleccione otro archivo
           $(this).val('');
        }
    });

    /*GUARDAR INFO PRONUNCIAMIENTO*/
    $('#form_CaliPronuncia').submit(function (e){
        e.preventDefault();
        var GuardarPronuncia = $('#GuardarPronuncia');
        var ActualizarPronuncia = $('#ActualizarPronuncia');

        if (GuardarPronuncia.length > 0) {
            document.querySelector('#GuardarPronuncia').disabled=true;            
        }
        if (ActualizarPronuncia.length > 0) {
            document.querySelector('#ActualizarPronuncia').disabled=true;
        }
        var guardar_datos = [];
        var datos_finales_diagnosticos_moticalifi = [];
        var array_id_filas = [];
        // RECORREMOS LOS TD DE LA TABLA PARA EXTRAER LOS DATOS E INSERTARLOS EN UN ARREGLO (LA INSERCIÓN LA HACE POR CADA FILA, POR ENDE, ES UN ARRAY MULTIDIMENSIONAL)
        $('#listado_diagnostico_cie10 tbody tr').each(function (index) {
            array_id_filas.push($(this).attr('id'));
            if ($(this).attr('id') !== "datos_diagnostico") {
                $(this).children("td").each(function (index2) {
                    var nombres_ids = $(this).find('*').attr("id");
                    if (nombres_ids != undefined) {
                        guardar_datos.push($('#'+nombres_ids).val());                        
                    }
                    if((index2+1) % 4 === 0){
                        datos_finales_diagnosticos_moticalifi.push(guardar_datos);
                        guardar_datos = [];
                    }
                });
            }
        });
        var formData = new FormData($('form')[0]);
        formData.append('datos_finales_diagnosticos_moticalifi', JSON.stringify(datos_finales_diagnosticos_moticalifi));
        //const arrayData = JSON.parse(formData.get('datos_finales_diagnosticos_moticalifi'));
        formData.append('token', $('input[name=_token]').val());
        formData.append('Id_EventoPronuncia', $('#Id_Evento_pronuncia').val());
        formData.append('Id_ProcesoPronuncia', $('#Id_Proceso_pronuncia').val());
        formData.append('Id_Asignacion_Pronuncia', $('#Asignacion_Pronuncia').val());
        formData.append('primer_calificador', $('#primer_calificador').val());
        formData.append('nombre_calificador', $('#nombre_calificador').val());
        formData.append('nit_calificador', $('#nit_calificador').val());
        formData.append('dir_calificador', $('#dir_calificador').val());
        formData.append('mail_calificador', $('#mail_calificador').val());
        formData.append('telefono_calificador', $('#telefono_calificador').val());
        formData.append('depar_calificador', $('#depar_calificador').val());
        formData.append('ciudad_calificador', $('#ciudad_calificador').val());
        formData.append('tipo_pronunciamiento', $('#tipo_pronunciamiento').val());
        formData.append('tipo_evento', $('#tipo_evento').val());
        formData.append('tipo_origen', $('#tipo_origen').val());
        formData.append('fecha_evento', $('#fecha_evento').val());
        formData.append('dictamen_calificador', $('#dictamen_calificador').val());
        formData.append('fecha_calificador', $('#fecha_calificador').val());
        formData.append('decision_pr', $("[id^='di_']").filter(":checked").val());
        formData.append('asunto_cali', $('#asunto_cali').val());
        formData.append('sustenta_cali', $('#sustenta_cali').val());
        formData.append('destinatario_principal', $('#destinatario_principal').filter(":checked").val());
        formData.append('tipo_entidad', $("#tipo_entidad").val());
        formData.append('nombre_entidad', $("#nombre_entidad").val());
        formData.append('copia_afiliado', $('#copia_afiliado').filter(":checked").val());
        formData.append('copia_empleador', $('#copia_empleador').filter(":checked").val());
        formData.append('copia_eps', $('#copia_eps').filter(":checked").val());
        formData.append('copia_afp', $('#copia_afp').filter(":checked").val());
        formData.append('copia_arl', $('#copia_arl').filter(":checked").val());
        formData.append('junta_regional', $('#junta_regional').filter(":checked").val());
        formData.append('junta_nacional', $('#junta_nacional').filter(":checked").val());
        formData.append('junta_regional_cual', $('#junta_regional_cual').val());
        formData.append('n_anexos', $('#n_anexos').val());
        formData.append('elaboro', $('#elaboro').val());
        formData.append('reviso', $('#reviso').val());
        formData.append('ciudad_correspon', $('#ciudad_correspon').val());
        formData.append('fecha_correspon', $('#fecha_correspon').val());
        formData.append('n_radicado', $('#n_radicado').val());
        formData.append('firmar', $('#firmar').filter(":checked").val());
        formData.append('nombre_afiliado', $('#nombre_afiliado').val());
        formData.append('identificacion', $('#identificacion').val());
        formData.append('DocPronuncia', $('#DocPronuncia')[0].files[0]);
        formData.append('bandera_pronuncia_guardar_actualizar', $('#bandera_pronuncia_guardar_actualizar').val());
        $.ajax({
            type:'POST',
            url:'/guardarInfoServiPronunciaOrigen',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                if (response.parametro == 'agregar_pronunciamiento') {
                    $('#div_alerta_pronuncia').removeClass('d-none');
                    $('.alerta_pronucia').append('<strong>'+response.mensaje+'</strong>');                                            
                    setTimeout(function(){
                    document.querySelector('#GuardarPronuncia').disabled=false;
                        $('#div_alerta_pronuncia').addClass('d-none');
                        $('.alerta_pronucia').empty();   
                        location.reload();
                    }, 3000);   
                }else if(response.parametro == 'update_pronunciamiento'){
                    $('#div_alerta_pronuncia').removeClass('d-none');
                    $('.alerta_pronucia').append('<strong>'+response.mensaje2+'</strong>');                                           
                    setTimeout(function(){
                    document.querySelector('#ActualizarPronuncia').disabled=false;
                        $('#div_alerta_pronuncia').addClass('d-none');
                        $('.alerta_pronucia').empty();
                        document.querySelector('#ActualizarPronuncia').disabled=false;
                        location.reload();
                    }, 3000);
                }
            }
        })
    });

    //Remover CIE10
    $(document).on('click', "a[id^='btn_remover_diagnosticos_moticalifi']", function(){

        let token = $("input[name='_token']").val();
        var datos_fila_quitar_examen = {
            '_token': token,
            'fila' : $(this).data("id_fila_quitar"),
            'Id_evento': $('#Id_Evento_pronuncia').val()
        };
        
        $.ajax({
            type:'POST',
            url:'/eliminarDiagnosticosMotivoCalificacion',
            data: datos_fila_quitar_examen,
            success:function(response){
                // console.log(response);
                if (response.parametro == "fila_diagnostico_eliminada") {
                    $('#resultado_insercion_cie10').empty();
                    $('#resultado_insercion_cie10').removeClass('d-none');
                    $('#resultado_insercion_cie10').addClass('alert-success');
                    $('#resultado_insercion_cie10').append('<strong>'+response.mensaje+'</strong>');
                    
                    setTimeout(() => {
                        $('#resultado_insercion_cie10').addClass('d-none');
                        $('#resultado_insercion_cie10').removeClass('alert-success');
                        $('#resultado_insercion_cie10').empty();
                    }, 3000);
                }
                if (response.total_registros == 0) {
                    $("#conteo_listado_diagnosticos_moticalifi").val(response.total_registros);
                }
            }
        });        

    });

    /* Generar proforma ACUERDO CALIFICACION DE EPS */
    $("#generar_proforma").click(function(event){
        event.preventDefault();

        var token = $('input[name=_token]').val();
        /* Captura de variables para enviar a la proforma */
        var bandera_tipo_proforma = $("#bandera_tipo_proforma").val();
        var ciudad = $("#ciudad_correspon").val();
        var fecha = $("#fecha_correspon").val();
        var nro_radicado = $("#n_radicado").val();
        var tipo_identificacion = $("#tipo_identificacion").val();
        var num_identificacion = $("#num_identificacion").val();
        var nro_siniestro = $("#nro_siniestro").val();
        var nombre_afiliado = $("#nombre_afiliado").val();
        var fecha_dictamen = $("#fecha_calificador").val();
        var origen = $("#tipo_origen option:selected").text();
        var asunto = $("#asunto_cali").val();
        var sustentacion = $("#sustenta_cali").val();
        var Id_Asignacion_consulta_dx = $("#Id_Asignacion_consulta_dx").val();
        var Id_Proceso_consulta_dx = $("#Id_Proceso_consulta_dx").val();
        /* Informacion destinatario principal */
        if($("#destinatario_principal").filter(":checked").val() != undefined){
            var destinatario_principal = "Si";
        }else{
            var destinatario_principal = "No";
        }

        var tipo_entidad_correspon = $("#tipo_entidad").val();
        var nombre_entidad_correspon = $("#nombre_entidad").val();
        
        /* Checkbox de Copias a partes interesadas */
        var copia_afiliado = $('#copia_afiliado').filter(":checked").val();
        var copia_empleador = $('#copia_empleador').filter(":checked").val();
        var copia_eps = $('#copia_eps').filter(":checked").val();
        var copia_afp = $('#copia_afp').filter(":checked").val();
        var copia_arl = $('#copia_arl').filter(":checked").val();
        var firmar = $('#firmar').filter(":checked").val();
        var Id_cliente_firma = $('#Id_cliente_firma').val();
        var nro_anexos = $("#n_anexos").val();

        var nombre_entidad = $("#nom_entidad_califi").val();
        var direccion_entidad = $("#dir_calificador").val();
        var telefono_entidad = $("#telefono_calificador").val();
        var ciudad_entidad = $("#ciudad_calificador").val();
        var departamento_entidad = $("#depar_calificador").val();
        var nro_dictamen_pri_cali = $("#dictamen_calificador").val();
        var fecha_dictamen_pri_cali = $("#fecha_calificador").val();

        var datos_generacion_proforma = {
            '_token': token,
            'bandera_tipo_proforma': bandera_tipo_proforma,
            'ciudad': ciudad,
            'fecha': fecha,
            'nro_radicado': nro_radicado,
            'tipo_identificacion': tipo_identificacion,
            'num_identificacion': num_identificacion,
            'nro_siniestro': nro_siniestro,
            'nombre_afiliado': nombre_afiliado,
            'fecha_dictamen': fecha_dictamen,
            'origen': origen,
            'asunto': asunto,
            'sustentacion': sustentacion,
            'Id_Asignacion_consulta_dx': Id_Asignacion_consulta_dx,
            'Id_Proceso_consulta_dx': Id_Proceso_consulta_dx,
            'destinatario_principal': destinatario_principal,
            'tipo_entidad_correspon': tipo_entidad_correspon,
            'nombre_entidad_correspon': nombre_entidad_correspon,
            'copia_afiliado': copia_afiliado,
            'copia_empleador': copia_empleador,
            'copia_eps': copia_eps,
            'copia_afp': copia_afp,
            'copia_arl': copia_arl,
            'firmar': firmar,
            'Id_cliente_firma': Id_cliente_firma,
            'nro_anexos': nro_anexos,
            'nombre_entidad': nombre_entidad,
            'direccion_entidad': direccion_entidad,
            'telefono_entidad': telefono_entidad,
            'ciudad_entidad': ciudad_entidad,
            'departamento_entidad': departamento_entidad,
            'nro_dictamen_pri_cali': nro_dictamen_pri_cali,
            'fecha_dictamen_pri_cali': fecha_dictamen_pri_cali
        }
        
        
        
        $.ajax({    
            type:'POST',
            url:'/DescargarProformaPronunciamiento',
            data: datos_generacion_proforma,
            xhrFields: {
                responseType: 'blob' // Indica que la respuesta es un blob
            },
            success: function (response, status, xhr) {
                var blob = new Blob([response], { type: xhr.getResponseHeader('content-type') });
        
                // Crear un enlace de descarga similar al ejemplo anterior
                if (bandera_tipo_proforma == "proforma_acuerdo") {
                    var nombre_documento = "ORI_ACUERDO_"+Id_Asignacion_consulta_dx+"_"+num_identificacion+".pdf";
                } else {
                    var nombre_documento = "ORI_DESACUERDO_"+Id_Asignacion_consulta_dx+"_"+num_identificacion+".docx";                    
                }
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = nombre_documento;  // Reemplaza con el nombre deseado para el archivo PDF
        
                // Adjuntar el enlace al documento y activar el evento de clic
                document.body.appendChild(link);
                link.click();
        
                // Eliminar el enlace del documento
                document.body.removeChild(link);
            },
            error: function (error) {
                // Manejar casos de error
                console.error('Error al descargar el PDF:', error);
            }       
        });
        
    });

    /* Validaciones para el rol Consulta cuando entra a la vista */
    if (idRol == 7) {
        $("#div_info_afi").addClass('d-none');
        $("#div_info_enti_califi").addClass('d-none');
        $("#div_info_califi").addClass('d-none');
        $("#div_mot_cali").addClass('d-none');
        $("#div_pronu_califi").addClass('d-none');
        $(".row_correspondencia").addClass('d-none');
        $("#div_doc_pronu").addClass('d-none');
        $("#div_msg_alerta").addClass('d-none');
        $("#ActualizarPronuncia").addClass('d-none');
        $("#GuardarPronuncia").addClass('d-none');
    }

});
/* Función para añadir los controles de cada elemento de cada fila en la tabla Diagnostico motivo de calificación*/
function funciones_elementos_fila_diagnosticos(num_consecutivo) {
    // Inicializacion de select 2
    $("#lista_Cie10_fila_"+num_consecutivo).select2({
        //width: '100%',
        width: '340px',
        placeholder: "Seleccione",
        allowClear: false
    });

    $("#lista_origenCie10_fila_"+num_consecutivo).select2({
        width: '100%',
        placeholder: "Seleccione",
        allowClear: false
    });

    //Carga de datos en los selectores

    let token = $("input[name='_token']").val();
    let datos_CIE10 = {
        '_token': token,
        'parametro' : "listado_CIE10",
    };
    $.ajax({
        type:'POST',
        url:'/selectoresCalificacionTecnicaPCL',
        data: datos_CIE10,
        success:function(data){
            // $("select[id^='lista_Cie10_fila_']").empty();
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#lista_Cie10_fila_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Cie_diagnostico"]+'">'+data[claves[i]]["CIE10"]+' - '+data[claves[i]]["Descripcion_diagnostico"]+'</option>');
            }
        }
    });

    let datos_Orgien_CIE10 = {
        '_token': token,
        'parametro' : "listado_OrgienCIE10",
    };
    $.ajax({
        type:'POST',
        url:'/selectoresCalificacionTecnicaPCL',
        data: datos_Orgien_CIE10,
        success:function(data){
            // $("select[id^='lista_origenCie10_fila_']").empty();
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#lista_origenCie10_fila_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });

    $(document).on('change', '#lista_Cie10_fila_'+num_consecutivo, function() {        
        let seleccion = $(this).val();        
        let datos_Nombre_CIE = {
            '_token': token,
            'parametro' : "listado_NombreCIE10",
            'seleccion': seleccion,
        };    
        $.ajax({
            type:'POST',
            url:'/selectoresCalificacionTecnicaPCL',
            data: datos_Nombre_CIE,
            success:function(data){
                //console.log(data);
                let claves = Object.keys(data);
                //console.log(claves);
                for (let i = 0; i < claves.length; i++) {
                    $("#nombre_cie10_fila_"+num_consecutivo).val(data[claves[i]]["Descripcion_diagnostico"]);
                }
            }
        });
    });
}