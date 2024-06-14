$(document).ready(function(){

    var idRol = $("#id_rol").val();
    // Obtener sessionStorage del navegador
    //var posicionActual = $(window).scrollTop(); // Guarda cuando recarga la pagina
    var posicionMemoria = sessionStorage.getItem("scrollTopPronuncia"); // Guarda session scrollTop

    if (posicionMemoria != null) {
        $(window).scrollTop(posicionMemoria);
        sessionStorage.removeItem("scrollTopPronuncia");
        //console.log("Se ha restaurado la posición guardada en memoria");
    } else {
        //console.log("No se ha encontrado una posición guardada en memoria");
    }
    //guardar la posición de desplazamiento actual en la memoria
    $(window).on("beforeunload", function() {
        sessionStorage.setItem("scrollTopPronuncia", $(window).scrollTop());
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

    $(".tipo_pronunciamiento").select2({
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
        url:'/selectoresPronunciamiento',
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

    var info_pronuncia = $('#info_pronuncia').val();
    if(info_pronuncia){
        var info_pronunciamiento = JSON.parse(info_pronuncia)[0];
        if(info_pronunciamiento.Decision == 'Silencio'){
            $("#div_pronu_califi").removeClass('d-none');
        } 
        else{
            $("#ActualizarPronuncia").addClass('d-none');
            $("#div_pronu_califi").addClass('d-none');
            $("#div_doc_pronu").addClass('d-none');
        }     
    }
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
            url:'/selectoresPronunciamiento',
            data: datos_nombre_entidad,
            success:function(data) {
                $("#nombre_calificador").empty();
                let IdEntidad = $('select[name=nombre_calificador]').val();
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
            url:'/selectoresPronunciamiento',
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
        url:'/selectoresPronunciamiento',
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
        url:'/selectoresPronunciamiento',
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
        url:'/selectoresPronunciamiento',
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
        url:'/selectoresPronunciamiento',
        data: datos_lista_regional_junta,
        success:function(data) {
            //console.log(data);
            let IdJunta = $('select[name=junta_regional_cual]').val();
            let primercali = Object.keys(data);
            for (let i = 0; i < primercali.length; i++) {
                if (data[primercali[i]]['Ciudad_Junta'] != IdJunta) {  
                    $('#junta_regional_cual').append('<option value="'+data[primercali[i]]["Ciudad_Junta"]+'">'+data[primercali[i]]["Ciudad_Junta"]+'</option>');
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
        url:'/selectoresPronunciamiento',
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
            url:'/selectoresPronunciamiento',
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
            url:'/selectoresPronunciamiento',
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
    // Ocultar o habilitar la Fecha de Evento en el dictamen pericial
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

    /* VALIDACIÓN MOSTRAR RANGO PCL */
    $("#porcentaje_pcl").on("input", function(){
        opt_rango_pcl = $(this).val();
        //console.log(opt_rango_pcl);
        //$("#porcentaje_pcl").val(opt_rango_pcl);
        iniciarIntervalo_rangoPcl();
    });
    function iniciarIntervalo_rangoPcl() {
        var resul_rango=0;
        //clearInterval(intervaloRango);
        intervaloRango = setInterval(() => {
            if(opt_rango_pcl=='isNaN'){
                resul_rango = '0';
            }else if(opt_rango_pcl < "14,99"){
                resul_rango = 'Entre 1 y 14,99%';
            } else if (opt_rango_pcl >= "14,99" && opt_rango_pcl < "29,99"){
                resul_rango = 'Entre 15 y 29,99%';
            } else if (opt_rango_pcl >= "29,99" && opt_rango_pcl < "49,99"){
                resul_rango = 'Entre 30 y 49,99%';
            } else if (opt_rango_pcl >= "49,99"){
                resul_rango = 'Mayor o igual 50%';
            }else{
                resul_rango = '0';
            }
            
            $('#rango_pcl').val(resul_rango); //Coloca resultado Rango PCL
        }, 500);
    }
    
    /* VALIDACIÓN MOSTRAR ITEM DE CORRESPONDECIA */
    var opt_correspondencia;
    $("[name='decision_pr']").on("change", function(){
        opt_correspondencia = $(this).val();
        $(this).val(opt_correspondencia);
        iniciarIntervalo_correspon();
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
                $("#reviso").prop("selectedIndex", 1);
            }
        });
        if(!info_pronuncia){
            intervaloCo = setInterval(() => {
                switch (opt_correspondencia) {
                    case "Acuerdo":
                        elementosDeslizar2.forEach(elemento => {
                            $(elemento).slideUp(tiempoDeslizamiento2);
                        }); 
                        $('#reviso').prop('required', true);
                    break;
                    case "Desacuerdo": 
                        elementosDeslizar2.forEach(elemento => {
                            $(elemento).slideUp(tiempoDeslizamiento2);
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
            }, 500);1
        }
        else{
            switch (opt_correspondencia) {
                case "Silencio":
                    elementosDeslizar2.forEach(elemento => {
                        $(elemento).slideUp(tiempoDeslizamiento2);
                    });
                    $('#reviso').prop('required', false);
                break;
                default:
                    elementosDeslizar2.forEach(elemento => {
                        $(elemento).slideDown(tiempoDeslizamiento2);
                    });
                    $('#reviso').prop('required', true);
                break;
            }
        }
    }

    //Reemplazar archivo 
    let comunicado_reemplazar = null;
    $("form[id^='form_reemplazar_archivo_']").submit(function (e){
        e.preventDefault();           
        $('#modalReemplazarArchivos').modal('show');  
        comunicado_reemplazar = $(this).data('archivo');
        let nombre_doc = comunicado_reemplazar.Nombre_documento;
        let nombre_doc_manual = comunicado_reemplazar.Asunto;
        console.log('Nombre doc ', nombre_doc);
        if(nombre_doc != null && nombre_doc != "null"){
            extensionDoc = `.${ nombre_doc.split('.').pop()}`;
            document.getElementById('cargue_comunicados_modal').setAttribute('accept', extensionDoc);
        }
        else if(nombre_doc_manual != null && nombre_doc_manual != "null"){
            extensionDoc = `.${ nombre_doc_manual.split('.').pop()}`;
            document.getElementById('cargue_comunicados_modal').setAttribute('accept', extensionDoc);
        }
    });

    $("form[id^='reemplazar_documento']").submit(function(e){
        e.preventDefault();
        if(!$('#cargue_comunicados_modal')[0].files[0]){
            return $(".cargueundocumentoprimeromodal").removeClass('d-none');
        }
        $(".cargueundocumentoprimeromodal").addClass('d-none');
        $(".extensionInvalidaModal").addClass('d-none');
        var archivo = $('#cargue_comunicados_modal')[0].files[0];
        extensionDocCargado = `.${archivo.name.split('.').pop()}`;
        if(extensionDoc === extensionDocCargado){
            var formData = new FormData($('form')[0]);
            formData.append('doc_de_reemplazo', archivo);
            formData.append('token', $('input[name=_token]').val());
            formData.append('id_comunicado', comunicado_reemplazar.Id_Comunicado);
            formData.append('tipo_descarga', comunicado_reemplazar.Tipo_descarga);
            formData.append('id_asignacion', comunicado_reemplazar.Id_Asignacion);
            formData.append('id_proceso', comunicado_reemplazar.Id_proceso);
            formData.append('id_evento', comunicado_reemplazar.ID_evento);
            formData.append('n_radicado', comunicado_reemplazar.N_radicado);
            formData.append('numero_identificacion', comunicado_reemplazar.N_identificacion);
            formData.append('modulo_creacion', 'pronunciamientoPCL');
            formData.append('asunto', comunicado_reemplazar.Asunto);
            formData.append('nombre_documento', comunicado_reemplazar.Nombre_documento);
            $.ajax({
                type:'POST',
                url:'/reemplazarDocumento',
                data: formData,
                processData: false,
                contentType: false,
                success:function(response){
                    if (response.parametro == 'reemplazar_comunicado') {
                        $('.alerta_externa_comunicado_modal').removeClass('d-none');
                        $('.alerta_externa_comunicado_modal').append('<strong>'+response.mensaje+'</strong>');
                        setTimeout(function(){
                            $('.alerta_externa_comunicado_modal').addClass('d-none');
                            $('.alerta_externa_comunicado_modal').empty();
                            localStorage.setItem("#Generar_comunicados", true);
                            location.reload();
                        }, 3000);
                    }
                }
            });
        }
        else{
            document.getElementById('extensionInvalidaMensaje').textContent += extensionDoc;
            return $(".extensionInvalidaModal").removeClass('d-none');
        }
    });

    //Cargar comunicado manual
    $('#cargarComunicado').click(function(){
        if(!$('#cargue_comunicados')[0].files[0]){
            return $(".cargueundocumentoprimero").removeClass('d-none');
        }
        $(".cargueundocumentoprimero").addClass('d-none');
        var archivo = $('#cargue_comunicados')[0].files[0];
        var documentName = archivo.name;
        var formData = new FormData($('form')[0]);
        formData.append('cargue_comunicados', archivo);
        formData.append('token', $('input[name=_token]').val());
        formData.append('ciudad', 'N/A');
        formData.append('Id_evento',$('#Id_Evento_pronuncia').val());
        formData.append('Id_asignacion',$('#Asignacion_Pronuncia').val());
        formData.append('Id_procesos',$('#Id_Proceso_pronuncia').val());
        formData.append('fecha_comunicado2',null);
        formData.append('radicado2',$('#radicado_comunicado_manual').val());
        formData.append('cliente_comunicado2','N/A');
        formData.append('nombre_afiliado_comunicado2','N/A');
        formData.append('tipo_documento_comunicado2','N/A');
        formData.append('identificacion_comunicado2','N/A');
        formData.append('destinatario', 'N/A');
        formData.append('nombre_destinatario','N/A');
        formData.append('nic_cc','N/A');
        formData.append('direccion_destinatario','N/A');
        formData.append('telefono_destinatario',1);
        formData.append('email_destinatario','N/A');
        formData.append('departamento_destinatario',1);
        formData.append('ciudad_destinatario',1);
        formData.append('asunto',documentName);
        formData.append('cuerpo_comunicado','N/A');
        formData.append('anexos',0);
        formData.append('forma_envio',0);
        formData.append('reviso',0);
        formData.append('firmarcomunicado',null);
        formData.append('tipo_descarga', 'Manual');
        formData.append('Nombre_documento', documentName);
        formData.append('modulo_creacion','pronunciamientoPCL');
        formData.append('modulo','Comunicados pronuncionamiento PCL');
        $.ajax({
            type:'POST',
            url:'/registrarComunicadoOrigen',
            data: formData,   
            processData: false,
            contentType: false,         
            success:function(response){
                if (response.parametro == 'agregar_comunicado') {
                    $('.alerta_externa_comunicado').removeClass('d-none');
                    $('.alerta_externa_comunicado').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.alerta_externa_comunicado').addClass('d-none');
                        $('.alerta_externa_comunicado').empty();
                        location.reload();
                    }, 3000);
                }
            }
        });  
    }); 

    //Descargar archivo cargado manualmente
    $("form[id^='form_descargar_archivo_']").submit(function (e){
        e.preventDefault();              
        var archivo = $(this).data("archivo");

        var nombre_documento = archivo.Asunto;
        var idEvento = archivo.ID_evento;
        var enlaceDescarga = document.createElement('a');
        enlaceDescarga.href = '/descargar-archivo/'+nombre_documento+'/'+idEvento;     
        enlaceDescarga.target = '_self'; // Abrir en una nueva ventana/tab
        enlaceDescarga.style.display = 'none';
        document.body.appendChild(enlaceDescarga);
    
        // Simular clic en el enlace para iniciar la descarga
        enlaceDescarga.click();
    
        // Eliminar el enlace después de la descarga
        setTimeout(function() {
            document.body.removeChild(enlaceDescarga);
        }, 1000);
    });

    $("#editar_correspondencia").click(function(e){
        var info_pronunciamiento = JSON.parse(info_pronuncia)[0];
        $("#ActualizarPronuncia").removeClass('d-none');
        $("#div_pronu_califi").removeClass('d-none');
        $("#div_doc_pronu").removeClass('d-none');
        $("#correspondencia-item").removeClass('d-none');  
    });
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

     // Validar cual desicion esta marcada

     var di_acuerdo_pr = $('#di_acuerdo_pr');
     var di_desacuerdo_pr = $('#di_desacuerdo_pr');
     var di_silencio_pr = $('#di_silencio_pr');

     if (di_acuerdo_pr.prop('checked')) {
        $('#proformas_pro_pcl').removeClass('d-none');
        $('#proformas_pro_pcl').val('PDF');
        $('#btn_insertar_Nombre_afiliado').addClass('d-none')
        $('#btn_insertar_nombreCIE10').addClass('d-none')
        // $('#btn_insertar_Origen').addClass('d-none')
        $('#btn_insertar_porPcl').addClass('d-none')
        $('#btn_insertar_F_estructuracion').addClass('d-none')
     }else if(di_desacuerdo_pr.prop('checked')){
        $('#proformas_pro_pcl').removeClass('d-none');
        $('#proformas_pro_pcl').val('WORD');
        $('#btn_insertar_Nombre_afiliado').removeClass('d-none')
        $('#btn_insertar_nombreCIE10').removeClass('d-none')
        // $('#btn_insertar_Origen').removeClass('d-none')
        $('#btn_insertar_porPcl').removeClass('d-none')
        $('#btn_insertar_F_estructuracion').removeClass('d-none')
     }else if(di_silencio_pr.prop('checked')){
        $('#proformas_pro_pcl').addClass('d-none');
        $('#btn_insertar_Nombre_afiliado').addClass('d-none')
        $('#btn_insertar_nombreCIE10').addClass('d-none')
        // $('#btn_insertar_Origen').addClass('d-none')
        $('#btn_insertar_porPcl').addClass('d-none')
        $('#btn_insertar_F_estructuracion').addClass('d-none')
     }

     // Funcionalidad para insertar las etiquetas en la sutentacion
    $("#sustenta_cali").summernote({
        height: 'auto',
        toolbar: false
    });
    $('.note-editing-area').css("background", "white");
    $('.note-editor').css("border", "1px solid black");  

     // Funcionalidad para el llenado del asunto y habilitar boton PDF para descarga 

    $("[name='decision_pr']").on("change", function(){
    var opc_seleccionada = $(this).val();

        if (opc_seleccionada == 'Acuerdo') {
            $('#asunto_cali').val('CONCEPTO MÉDICO DE DICTAMEN PÉRDIDA DE CAPACIDAD LABORAL');
            var texto_insertar = "";
            $('#sustenta_cali').summernote('code', texto_insertar);
            $('#proformas_pro_pcl').removeClass('d-none');
            $('#proformas_pro_pcl').val('PDF');
            $('#btn_insertar_Nombre_afiliado').addClass('d-none');
            $('#btn_insertar_nombreCIE10').addClass('d-none');
            // $('#btn_insertar_Origen').addClass('d-none')
            $('#btn_insertar_porPcl').addClass('d-none');
            $('#btn_insertar_F_estructuracion').addClass('d-none');

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#n_anexos").val(seteo_nro_anexos);

            // Deselección automática de las copias a partes interesadas: Afiliado
            $("#copia_afiliado").prop('checked', false);

            // Selección automática del checkbox firmar
            $("#firmar").prop('checked', true);

        }else if (opc_seleccionada == 'Desacuerdo') {
            $('#asunto_cali').val('RECURSO DE REPOSICIÓN EN SUBSIDIO DE APELACIÓN FRENTE A DICTAMEN N°');
            var texto_insertar = "<p>Respetados Señores, </p>"+
            "<p>Yo, <strong>HUGO IGNACIO GÓMEZ DAZA</strong>, identificado como aparece al pie de mi firma, actuando en nombre y representación de <strong>SEGUROS DE VIDA "+ 
            "ALFA S.A.</strong> Aseguradora que expidió el seguro previsional a la <strong>AFP PORVENIR S.A.</strong>, debidamente facultado para ello, en atención al "+
            "dictamen de la referencia, estando dentro de los términos de ley, me permito interponer <strong>RECURSO DE REPOSICIÓN Y EN SUBSIDIO DE "+
            "APELACIÓN</strong> ante la Junta, por los siguientes motivos: </p>"+
            "<p>Nuestra inconformidad se dirige a la calificación de <strong>PERDIDA DE CAPACIDAD LABORAL</strong> dictaminada al afiliado <strong>{{$Nombre_afiliado}}</strong>, "+
            "donde califican los diagnósticos: <strong>{{$CIE10_Nombres_Origen}}</strong>, y por los cuales les otorga una calificación de <strong>{{$PorcentajePcl}}%</strong> fecha de "+
            "estructuración: <strong>{{$F_estructuracionPcl}}</strong>.</p>"+
            "<p>1. </p>"+
            "<p>Por lo anterior, presentamos el recurso de reposición y en subsidio el de apelación, contra la pérdida de capacidad laboral (PCL), "+
            "con el fin que se dictamine el valor correspondiente a las patologías del paciente dando aplicación al Decreto  1507/2014 como "+
            "normatividad vigente. En caso de que no se revoque, solicitamos se de curso a la apelación ante la Junta Regional de Calificación, e "+
            "informarnos con el fin de consignar los honorarios respectivos.</p>"+
            "<p>ANEXO:</p>"+
            "<p>Certificado de existencia y representación legal expedido por la Superintendencia Financiera. </p>"+
            "<p>NOTIFICACIONES:</p>"+
            "<p>Recibiré notificaciones en la Carrera 10 # 18 – 36 Edificio Córdoba Piso 4, en la ciudad de Bogotá, D.C. </p>"+
            "<p>Cualquier Información adicional con gusto  le será suministrada,</p>";
            $('#sustenta_cali').summernote('code', texto_insertar);
            $('#proformas_pro_pcl').removeClass('d-none');
            $('#proformas_pro_pcl').val('WORD');
            $('#btn_insertar_Nombre_afiliado').removeClass('d-none');
            $('#btn_insertar_nombreCIE10').removeClass('d-none');
            // $('#btn_insertar_Origen').removeClass('d-none')
            $('#btn_insertar_porPcl').removeClass('d-none');
            $('#btn_insertar_F_estructuracion').removeClass('d-none');

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#n_anexos").val(seteo_nro_anexos);

            // Selección automática de las copias a partes interesadas: Afiliado
            $("#copia_afiliado").prop('checked', true);

            // Selección automática del checkbox firmar
            $("#firmar").prop('checked', false);
        }
        else{
            $('#asunto_cali').val('');
            var texto_insertar = "";
            $('#sustenta_cali').summernote('code', texto_insertar);
            $('#proformas_pro_pcl').addClass('d-none');
            $('#btn_insertar_Nombre_afiliado').addClass('d-none');
            $('#btn_insertar_nombreCIE10').addClass('d-none');
            // $('#btn_insertar_Origen').addClass('d-none')
            $('#btn_insertar_porPcl').addClass('d-none');
            $('#btn_insertar_F_estructuracion').addClass('d-none');

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#n_anexos").val(seteo_nro_anexos);

            // Deselección automática de las copias a partes interesadas: Afiliado
            $("#copia_afiliado").prop('checked', false);

            // Selección automática del checkbox firmar
            $("#firmar").prop('checked', false);
        }
        iniciarIntervalo_correspon();
    });

     $("#btn_insertar_Nombre_afiliado").click(function(e){
        e.preventDefault();

        var etiqueta_Nombre_afiliado = "{{$Nombre_afiliado}}";
        $('#sustenta_cali').summernote('editor.insertText', etiqueta_Nombre_afiliado);
    }); 

    $("#btn_insertar_nombreCIE10").click(function(e){
        e.preventDefault();

        var etiqueta_nombreCIE10 = "{{$CIE10_Nombres_Origen}}";
        $('#sustenta_cali').summernote('editor.insertText', etiqueta_nombreCIE10);
    });

    // $("#btn_insertar_Origen").click(function(e){
    //     e.preventDefault();

    //     var etiqueta_nombreCIE10 = "{{$OrigenPcl}}";
    //     $('#sustenta_cali').summernote('editor.insertText', etiqueta_nombreCIE10);
    // });

    $("#btn_insertar_porPcl").click(function(e){
        e.preventDefault();

        var etiqueta_porPCL = "{{$PorcentajePcl}}";
        $('#sustenta_cali').summernote('editor.insertText', etiqueta_porPCL);
    });  
    
    $("#btn_insertar_F_estructuracion").click(function(e){
        e.preventDefault();

        var etiqueta_F_estructuracion = "{{$F_estructuracionPcl}}";
        $('#sustenta_cali').summernote('editor.insertText', etiqueta_F_estructuracion);
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
        formData.append('n_siniestro', $('#n_siniestro').val());
        formData.append('fecha_estruturacion', $('#fecha_estruturacion').val());
        formData.append('porcentaje_pcl', $('#porcentaje_pcl').val());
        formData.append('rango_pcl', $('#rango_pcl').val());
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
        if($('#bandera_pronuncia_guardar_actualizar').val() == 'Actualizar'){
            if($('#id_comunicado_a_editar').val()){
                formData.append('Id_Comunicado',$('#id_comunicado_a_editar').val());
            }
            else{
                formData.append('Id_Comunicado',null);
            }
        }
        $.ajax({
            type:'POST',
            url:'/guardarInfoServiPronuncia',
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
    $("form[id^='form_editar_comunicado_']").click(function(event){
        event.preventDefault();
        var tupla_comunicado = $(this).data("tupla_comunicado");
    });

    // Captura de datos para la proformas de pronunciamiento PCL acuerdo y desacuerdo
    $("form[id^='archivo_']").click(function (e) {
        e.preventDefault();
        let comunicado = $(this).data('archivo');
        var desicion_proforma_di_acuerdo_pr = $('#di_acuerdo_pr');
        var desicion_proforma_di_desacuerdo_pr = $('#di_desacuerdo_pr');

        if (desicion_proforma_di_acuerdo_pr.prop('checked')) {
            var desicion_proforma = 'proforma_acuerdo';
        }else if(desicion_proforma_di_acuerdo_pr.prop('checked')){
            var desicion_proforma = 'proforma_desacuerdo';
        }
        var fecha = $("#fecha_correspon").val();
        var nro_radicado = $("#n_radicado").val();
        var Id_Evento_pronuncia_corre = $('#Id_Evento_pronuncia').val();
        var Id_Proceso_pronuncia_corre = $('#Id_Proceso_pronuncia').val();
        var Asignacion_Pronuncia_corre = $('#Asignacion_Pronuncia').val();        
        var Nombre_afiliado_corre = $('#nombre_afiliado').val();
        var Iden_afiliado_corre = $('#identificacion').val();
        var Sustenta_cali = $('#sustenta_cali').val();
        var copia_afiliado = $('#copia_afiliado').filter(":checked").val();
        var copia_empleador = $('#copia_empleador').filter(":checked").val();
        var copia_eps = $('#copia_eps').filter(":checked").val();
        var copia_afp = $('#copia_afp').filter(":checked").val();
        var copia_arl = $('#copia_arl').filter(":checked").val();
        var firmar = $('#firmar').filter(":checked").val();

        let token = $("input[name='_token']").val();
        var datos_proforma_pro = {
            '_token': token,
            'fecha': fecha,
            'nro_radicado': nro_radicado,
            'Id_Evento_pronuncia_corre': Id_Evento_pronuncia_corre,
            'Id_Proceso_pronuncia_corre': Id_Proceso_pronuncia_corre,
            'Asignacion_Pronuncia_corre': Asignacion_Pronuncia_corre,
            'Nombre_afiliado_corre':Nombre_afiliado_corre,
            'Iden_afiliado_corre':Iden_afiliado_corre,
            'Sustenta_cali': Sustenta_cali,
            'copia_afiliado':copia_afiliado,
            'copia_empleador':copia_empleador,
            'copia_eps':copia_eps,
            'copia_afp':copia_afp,
            'copia_arl':copia_arl,
            'Firma_corre':firmar,
            'desicion_proforma':desicion_proforma,
            'id_comunicado': comunicado.Id_Comunicado,
        };
        if(comunicado.Reemplazado == 1){
            var nombre_doc = comunicado.Nombre_documento;
            var idEvento = comunicado.ID_evento;
            var enlaceDescarga = document.createElement('a');
            enlaceDescarga.href = '/descargar-archivo/'+nombre_doc+'/'+idEvento;     
            enlaceDescarga.target = '_self'; // Abrir en una nueva ventana/tab
            enlaceDescarga.style.display = 'none';
            document.body.appendChild(enlaceDescarga);
            enlaceDescarga.click();
            setTimeout(function() {
                document.body.removeChild(enlaceDescarga);
            }, 1000);
        }else{
            $.ajax({
                type:'POST',
                url:'/generarPdfProformaspro',
                data:datos_proforma_pro,
                xhrFields: {
                    responseType: 'blob' // Indica que la respuesta es un blob
                },
                beforeSend:  function() {
                    $("#btn_generar_proforma").addClass("descarga-deshabilitada");
                },
                success: function (response, status, xhr) {
                    var blob = new Blob([response], { type: xhr.getResponseHeader('content-type') });
            
                    // Crear un enlace de descarga similar al ejemplo anterior
                    if (desicion_proforma == "proforma_acuerdo") {
                        var nombre_documento = "PCL_ACUERDO_"+Asignacion_Pronuncia_corre+"_"+Iden_afiliado_corre+".pdf";
                    }else{
                        var nombre_documento = "PCL_DESACUERDO_"+Asignacion_Pronuncia_corre+"_"+Iden_afiliado_corre+".docx";
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
                },
                complete: function(){
                    $("#btn_generar_proforma").removeClass("descarga-deshabilitada");
                    location.reload();
                }  
            });
        }
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
    };

    /* Códigos para el tema del rol administrador (modelo a seguir) */
    // A los usuarios que no tengan el rol Administrador se les aplica los siguientes controles en el formulario de correspondencia:
    // inhabilita los campos nro anexos, asunto, etiquetas, cuerpo comunicado, firmar
    if (idRol != 6) {
        $("#n_anexos").prop('readonly', true);
        $("#asunto_cali").prop('readonly', true);
        $(".note-editable").attr("contenteditable", true);

        $("#btn_insertar_Nombre_afiliado").prop('disabled', false);
        $("#btn_insertar_nombreCIE10").prop('disabled', false);
        $("#btn_insertar_porPcl").prop('disabled', false);
        $("#sustenta_cali").prop('disabled', true);
        
        $("#firmar").prop('disabled', true);
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