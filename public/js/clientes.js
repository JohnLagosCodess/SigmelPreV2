$(document).ready(function(){

    $(".centrar").css('text-align', 'center');

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE TIPOS DE CLIENTES */
    $(".tipo_cliente").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE DEPARTAMENTOS  y CIUDADES */
    $(".departamento").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    $(".ciudad").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT2 STATUS CLIENTE */
    $(".status_cliente").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    let token = $('input[name=_token]').val();

    // listado de tipos de clientes
    let datos_lista_tipo_clientes = {
        '_token': token,
        'parametro' : "lista_tipo_clientes",
        'parametro1': "nuevo_cliente"
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

    //Listado de departamento
    let datos_lista_departamento_cliente = {
        '_token': token,
        'parametro':"lista_departamentos_cliente"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_departamento_cliente,
        success:function(data) {
            //console.log(data);
            $('#departamento').empty();
            $('#departamento').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#departamento').append('<option value="'+data[claves[i]]["Id_departamento"]+'">'+data[claves[i]]["Nombre_departamento"]+'</option>');
            }
        }
    });

    // Listado Municipio
    $('#departamento').change( function(){
        $('#ciudad').prop('disabled', false);
        let id_departamento_cliente = $('#departamento').val();
        let datos_municipio_cliente = {
            '_token': token,
            'parametro' : "lista_municipios_cliente",
            'id_departamento_cliente': id_departamento_cliente
        };

        $.ajax({
            type:'POST',
            url:'/cargarselectores',
            data: datos_municipio_cliente,
            success:function(data) {
                //console.log(data);
                $('#ciudad').empty();
                $('#ciudad').append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $('#ciudad').append('<option value="'+data[claves[i]]["Id_municipios"]+'">'+data[claves[i]]["Nombre_municipio"]+'</option>');
                }
            }
        });
    });

    /* Validación opción OTRO/¿Cuál? del selector Tipo de Cliente FALTA */
    $('.columna_otro_tipo_cliente').css('display','none');
    $('#tipo_cliente').change(function(){
        let opt_otro_cual_tipo_cliente = $("#tipo_cliente option:selected").text();
        if (opt_otro_cual_tipo_cliente === "OTRO/¿Cuál?") {
            $(".columna_otro_tipo_cliente").slideDown('slow');
            $('#otro_tipo_cliente').prop('required', true);
        }else{
            $(".columna_otro_tipo_cliente").slideUp('slow');
            $('#otro_tipo_cliente').prop('required', false);
        }
    });

    // VALIDACIÓN CHECKBOXES TABLA SERVICIOS CONTRATADOS

    /* PROCESO Origen ATEL */

    // Servicio Determinación de Origen (DTO)
    $("#checkbox_servicio_dto").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_dto").removeClass('d-none');
            // $("#nro_consecutivo_servicio_dto").removeClass('d-none');
        }else{
            $("#valor_tarifa_servicio_dto").addClass('d-none');
            // $("#nro_consecutivo_servicio_dto").addClass('d-none');

            $("#valor_tarifa_servicio_dto").val('');
            // $("#nro_consecutivo_servicio_dto").val('');
        }
    });

    // Servicio Adición DX
    $("#checkbox_servicio_adicion_dx").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_adicion_dx").removeClass('d-none');
            // $("#nro_consecutivo_servicio_adicion_dx").removeClass('d-none');
        }else{
            $("#valor_tarifa_servicio_adicion_dx").addClass('d-none');
            // $("#nro_consecutivo_servicio_adicion_dx").addClass('d-none');

            $("#valor_tarifa_servicio_adicion_dx").val('');
            // $("#nro_consecutivo_servicio_adicion_dx").val('');
        }
    });

    // Servicio Pronunciamientos
    $("#checkbox_servicio_pronunciamiento").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_pronunciamiento").removeClass('d-none');
            // $("#nro_consecutivo_servicio_pronunciamiento").removeClass('d-none');
        }else{
            $("#valor_tarifa_servicio_pronunciamiento").addClass('d-none');
            // $("#nro_consecutivo_servicio_pronunciamiento").addClass('d-none');

            $("#valor_tarifa_servicio_pronunciamiento").val('');
            // $("#nro_consecutivo_servicio_pronunciamiento").val('');
        }
    });

    /* PROCESO Calificación PCL */

    // Servicio Calificación Técnica
    $("#checkbox_servicio_calificacion_tecnica").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_calificacion_tecnica").removeClass('d-none');
            // $("#nro_consecutivo_servicio_calificacion_tecnica").removeClass('d-none');
        }else{
            $("#valor_tarifa_servicio_calificacion_tecnica").addClass('d-none');
            // $("#nro_consecutivo_servicio_calificacion_tecnica").addClass('d-none');

            $("#valor_tarifa_servicio_calificacion_tecnica").val('');
            // $("#nro_consecutivo_servicio_calificacion_tecnica").val('');
        }
    });

    // Servicio Recalificación
    $("#checkbox_servicio_recalificacion").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_recalificacion").removeClass('d-none');
            // $("#nro_consecutivo_servicio_recalificacion").removeClass('d-none');
        }else{
            $("#valor_tarifa_servicio_recalificacion").addClass('d-none');
            // $("#nro_consecutivo_servicio_recalificacion").addClass('d-none');

            $("#valor_tarifa_servicio_recalificacion").val('');
            // $("#nro_consecutivo_servicio_recalificacion").val('');
        }
    });

    // Servicio Revisión Pensión
    $("#checkbox_servicio_revision_pension").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_revision_pension").removeClass('d-none');
            // $("#nro_consecutivo_servicio_revision_pension").removeClass('d-none');
        }else{
            $("#valor_tarifa_servicio_revision_pension").addClass('d-none');
            // $("#nro_consecutivo_servicio_revision_pension").addClass('d-none');

            $("#valor_tarifa_servicio_revision_pension").val('');
            // $("#nro_consecutivo_servicio_revision_pension").val('');
        }
    });

    // Servicio Pronunciamientos
    $("#checkbox_servicio_pronunciamiento_pcl").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_pronunciamiento_pcl").removeClass('d-none');
            // $("#nro_consecutivo_servicio_pronunciamiento_pcl").removeClass('d-none');
        }else{
            $("#valor_tarifa_servicio_pronunciamiento_pcl").addClass('d-none');
            // $("#nro_consecutivo_servicio_pronunciamiento_pcl").addClass('d-none');

            $("#valor_tarifa_servicio_pronunciamiento_pcl").val('');
            // $("#nro_consecutivo_servicio_pronunciamiento_pcl").val('');
        }
    });

    /* PROCESO Juntas */

    // Servicio Controversia Origen
    $("#checkbox_servicio_controversia_origen").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_controversia_origen").removeClass('d-none');
            // $("#nro_consecutivo_servicio_controversia_origen").removeClass('d-none');
        }else{
            $("#valor_tarifa_servicio_controversia_origen").addClass('d-none');
            // $("#nro_consecutivo_servicio_controversia_origen").addClass('d-none');

            $("#valor_tarifa_servicio_controversia_origen").val('');
            // $("#nro_consecutivo_servicio_controversia_origen").val('');
        }
    });

    // Servicio Controversia Pcl
    $("#checkbox_servicio_controversia_pcl").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_controversia_pcl").removeClass('d-none');
            // $("#nro_consecutivo_servicio_controversia_pcl").removeClass('d-none');
        }else{
            $("#valor_tarifa_servicio_controversia_pcl").addClass('d-none');
            // $("#nro_consecutivo_servicio_controversia_pcl").addClass('d-none');

            $("#valor_tarifa_servicio_controversia_pcl").val('');
            // $("#nro_consecutivo_servicio_controversia_pcl").val('');
        }
    });

    /* PROCESO Otros */

    // Servicio PQRD
    $("#checkbox_servicio_pqrd").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_pqrd").removeClass('d-none');
            // $("#nro_consecutivo_servicio_pqrd").removeClass('d-none');
        }else{
            $("#valor_tarifa_servicio_pqrd").addClass('d-none');
            // $("#nro_consecutivo_servicio_pqrd").addClass('d-none');

            $("#valor_tarifa_servicio_pqrd").val('');
            // $("#nro_consecutivo_servicio_pqrd").val('');
        }
    });

    // Servicio Tutelas
    $("#checkbox_servicio_tutelas").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_tutelas").removeClass('d-none');
            // $("#nro_consecutivo_servicio_tutelas").removeClass('d-none');
        }else{
            $("#valor_tarifa_servicio_tutelas").addClass('d-none');
            // $("#nro_consecutivo_servicio_tutelas").addClass('d-none');

            $("#valor_tarifa_servicio_tutelas").val('');
            // $("#nro_consecutivo_servicio_tutelas").val('');
        }
    });
    
    // Servicio Gestión Integral del Siniestro (GIS)
    $("#checkbox_servicio_gis").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_gis").removeClass('d-none');
            // $("#nro_consecutivo_servicio_gis").removeClass('d-none');
        }else{
            $("#valor_tarifa_servicio_gis").addClass('d-none');
            // $("#nro_consecutivo_servicio_gis").addClass('d-none');

            $("#valor_tarifa_servicio_gis").val('');
            // $("#nro_consecutivo_servicio_gis").val('');
        }
    });

    // Servicio Auditorías
    $("#checkbox_servicio_auditorias").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_auditorias").removeClass('d-none');
            // $("#nro_consecutivo_servicio_auditorias").removeClass('d-none');
        }else{
            $("#valor_tarifa_servicio_auditorias").addClass('d-none');
            // $("#nro_consecutivo_servicio_auditorias").addClass('d-none');

            $("#valor_tarifa_servicio_auditorias").val('');
            // $("#nro_consecutivo_servicio_auditorias").val('');
        }
    });

    // Validación de la escritura de correo en el campo Email principal
    $("#email_principal").keyup(function(){
        var email_escrito = $(this).val();
        var patronCorreo = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (!patronCorreo.test(email_escrito)) {
            $(".mensaje_correo_mal_escrito").removeClass('d-none');
            $("#GuardarCliente").prop("disabled", true);
        } else {
            $(".mensaje_correo_mal_escrito").addClass('d-none');
            $("#GuardarCliente").prop("disabled", false);
        }
    });

    // Previsualización de la imagen
    $("#logo_cliente").change(function(){
        var selectedFile = $(this)[0].files[0];
        if (selectedFile) {
            var fileName = selectedFile.name;
            var fileExtension = fileName.split('.').pop().toLowerCase();
            $("#nombre_ext_imagen").val(fileExtension);

            if (fileExtension === 'png' || fileExtension === 'jpg') {
                $(".mensaje_extension_logo").addClass('d-none');
                visualizar_imagen(this,'#img_codificada','#previewImage');
            } else {
                $(".mensaje_extension_logo").removeClass('d-none');
                setTimeout(() => {
                    $(".mensaje_extension_logo").addClass('d-none');
                }, 5000);
                $(this).val('');
            }
        }
    });
    //Previsualización del footer
    $("#logo_footer").change(function(){
        var selectedFile = $(this)[0].files[0];

        if (selectedFile) {
            var fileName = selectedFile.name;
            var fileExtension = fileName.split('.').pop().toLowerCase();
            $("#nombre_ext_footer").val(fileExtension);

            if (fileExtension === 'png' || fileExtension === 'jpg') {
                $(".mensaje_extension_footer").addClass('d-none');
                visualizar_imagen(this,'#footer_codificado','#footerContainer');
            } else {
                $(".mensaje_extension_footer").removeClass('d-none');
                setTimeout(() => {
                    $(".mensaje_extension_footer").addClass('d-none');
                }, 5000);
                $(this).val('');
            }
        }
    });

    /* INICIALIZACIÓN SUMMERNOTE PARA FIRMAS DEL CLIENTE */
    $('#firma_del_cliente').summernote({
        height: 200,
        toolbar: [
            ['font', ['fontname']],
            ['fontsize', ['fontsize']],
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['forecolor']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            ['insert', ['picture']],
        ]
    });

    /* INICIALIZACIÓN SUMMERNOTE PARA FIRMAS DEL PROVEEDOR */
    $('#firma_del_proveedor').summernote({
        height: 200,
        toolbar: [
            ['font', ['fontname']],
            ['fontsize', ['fontsize']],
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['forecolor']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            ['insert', ['picture']],
        ]
    });
   
    /* Pruebas FIRMAS */
    // $("#prueba_datos").click(function(){
    //     // Creación de array con los datos de la tabla dinámica firmas cliente
    //     var guardar_datos_firmas_cliente = [];
    //     var datos_finales_firmas_cliente = [];

    //     guardar_datos_firmas_cliente.push($("#nombre_del_firmante_cliente").val());
    //     guardar_datos_firmas_cliente.push($("#cargo_del_firmante_cliente").val());
    //     guardar_datos_firmas_cliente.push($('#firma_del_cliente').summernote('code'));
    //     datos_finales_firmas_cliente.push(guardar_datos_firmas_cliente);
    //     guardar_datos_firmas_cliente = [];
        
    //     var url_imagenes = [];
    //     var extension_imagenes = [];
    //     $.each(datos_finales_firmas_cliente, function (index, subArray) {
    //         $.each(subArray, function (subIndex, value) {

    //             var container = document.createElement('div');
    //             container.innerHTML = value;

    //             var images = container.getElementsByTagName('img');
    //             for (var i = 0; i < images.length; i++) {
    //                 var imageUrl = images[i].currentSrc;
    //                 var extension = images[i].dataset.filename;
    //                 url_imagenes.push(imageUrl);
    //                 extension_imagenes.push(extension.split('.').pop().toLowerCase());
    //             }
    //         });
    //     });

    //     let token = $("input[name='_token']").val();
    //     var enviar_info_firmas_cliente = {
    //         '_token': token,
    //         'Firmas': datos_finales_firmas_cliente,
    //         'Urls': url_imagenes,
    //         'Extensiones': extension_imagenes,
    //     };

    //     $.ajax({
    //         type: 'POST',
    //         url: '/GuardarFirmasCliente',
    //         data: enviar_info_firmas_cliente,
    //         success: function(response){

              
    //         }
    //     });
        
    // });

    // Envío de información para cuardar un nuevo cliente
    $("#form_guardar_cliente").submit(function(e){
        e.preventDefault();

        // Mostramos el spiner de espera y ocultamos el boton de guardar
        $("#mostrar_barra_creando_cliente").removeClass('d-none');
        $("#contenedor_btn_guardar_cliente").addClass('d-none');

        let token = $("input[name='_token']").val();

        // Creación de array con los datos de la tabla dinámica Sucursales
        var guardar_datos_sucursales = [];
        var datos_finales_sucursales = [];
        
        // RECORREMOS LOS TD DE LA TABLA PARA EXTRAER LOS DATOS E INSERTARLOS EN UN ARREGLO 
        // (LA INSERCIÓN LA HACE POR CADA FILA, POR ENDE, ES UN ARRAY MULTIDIMENSIONAL)
        $('#sucursales tbody tr').each(function (index) {
            // if ($(this).attr('id') !== "datos_examenes_interconsulta") {
                $(this).children("td").each(function (index2) {
                    var nombres_ids = $(this).find('*').attr("id");
                    if (nombres_ids != undefined) {
                        guardar_datos_sucursales.push($('#'+nombres_ids).val());                        
                    }
                    if((index2+1) % 11 === 0){
                        datos_finales_sucursales.push(guardar_datos_sucursales);
                        guardar_datos_sucursales = [];
                    }
                });
            // }
        });
        
        // Recolección de los Servicios Contratados con su correspondiente valor de tarifa y nro consecutivo dictamen
        var array_servicios_contratados = [];
        var listado_nombre_servicios = [];
        $('input[type="checkbox"]').each(function() {
            var id_checkboxes_servicios_contratados = $(this).attr('id');   
            if (id_checkboxes_servicios_contratados === 'checkbox_servicio_dto') {                
                if ($(this).is(':checked')) {                
                    var valor_servicio = $(this).val();
                    var valor_proceso = 1;
                    listado_nombre_servicios.push(valor_proceso);
                    listado_nombre_servicios.push(valor_servicio);
                    listado_nombre_servicios.push($("#valor_tarifa_servicio_dto").val());
                    // listado_nombre_servicios.push($("#nro_consecutivo_servicio_dto").val());

                    array_servicios_contratados.push(listado_nombre_servicios);
                    listado_nombre_servicios = [];
                }
            };

            if (id_checkboxes_servicios_contratados === 'checkbox_servicio_adicion_dx') {                
                if ($(this).is(':checked')) {                
                    var valor_servicio = $(this).val();
                    var valor_proceso = 1;
                    listado_nombre_servicios.push(valor_proceso);
                    listado_nombre_servicios.push(valor_servicio);
                    listado_nombre_servicios.push($("#valor_tarifa_servicio_adicion_dx").val());
                    // listado_nombre_servicios.push($("#nro_consecutivo_servicio_adicion_dx").val());
                    array_servicios_contratados.push(listado_nombre_servicios);

                    listado_nombre_servicios = [];
                }
            }

            if (id_checkboxes_servicios_contratados === 'checkbox_servicio_pronunciamiento') {                
                if ($(this).is(':checked')) {                
                    var valor_servicio = $(this).val();
                    var valor_proceso = 1;
                    listado_nombre_servicios.push(valor_proceso);
                    listado_nombre_servicios.push(valor_servicio);
                    listado_nombre_servicios.push($("#valor_tarifa_servicio_pronunciamiento").val());
                    // listado_nombre_servicios.push($("#nro_consecutivo_servicio_pronunciamiento").val());
                    array_servicios_contratados.push(listado_nombre_servicios);

                    listado_nombre_servicios = [];
                }
            }

            if (id_checkboxes_servicios_contratados === 'checkbox_servicio_calificacion_tecnica') {                
                if ($(this).is(':checked')) {                
                    var valor_servicio = $(this).val();
                    var valor_proceso = 2;
                    listado_nombre_servicios.push(valor_proceso);
                    listado_nombre_servicios.push(valor_servicio);
                    listado_nombre_servicios.push($("#valor_tarifa_servicio_calificacion_tecnica").val());
                    // listado_nombre_servicios.push($("#nro_consecutivo_servicio_calificacion_tecnica").val());
                    array_servicios_contratados.push(listado_nombre_servicios);

                    listado_nombre_servicios = [];
                }
            }

            if (id_checkboxes_servicios_contratados === 'checkbox_servicio_recalificacion') {                
                if ($(this).is(':checked')) {                
                    var valor_servicio = $(this).val();
                    var valor_proceso = 2;
                    listado_nombre_servicios.push(valor_proceso);
                    listado_nombre_servicios.push(valor_servicio);
                    listado_nombre_servicios.push($("#valor_tarifa_servicio_recalificacion").val());
                    // listado_nombre_servicios.push($("#nro_consecutivo_servicio_recalificacion").val());
                    array_servicios_contratados.push(listado_nombre_servicios);

                    listado_nombre_servicios = [];
                }
            }

            if (id_checkboxes_servicios_contratados === 'checkbox_servicio_revision_pension') {                
                if ($(this).is(':checked')) {                
                    var valor_servicio = $(this).val();
                    var valor_proceso = 2;
                    listado_nombre_servicios.push(valor_proceso);
                    listado_nombre_servicios.push(valor_servicio);
                    listado_nombre_servicios.push($("#valor_tarifa_servicio_revision_pension").val());
                    // listado_nombre_servicios.push($("#nro_consecutivo_servicio_revision_pension").val());
                    array_servicios_contratados.push(listado_nombre_servicios);

                    listado_nombre_servicios = [];
                }
            }

            if (id_checkboxes_servicios_contratados === 'checkbox_servicio_pronunciamiento_pcl') {                
                if ($(this).is(':checked')) {                
                    var valor_servicio = $(this).val();
                    var valor_proceso = 2;
                    listado_nombre_servicios.push(valor_proceso);
                    listado_nombre_servicios.push(valor_servicio);
                    listado_nombre_servicios.push($("#valor_tarifa_servicio_pronunciamiento_pcl").val());
                    // listado_nombre_servicios.push($("#nro_consecutivo_servicio_pronunciamiento_pcl").val());
                    array_servicios_contratados.push(listado_nombre_servicios);

                    listado_nombre_servicios = [];
                }
            }

            if (id_checkboxes_servicios_contratados === 'checkbox_servicio_controversia_origen') {                
                if ($(this).is(':checked')) {                
                    var valor_servicio = $(this).val();
                    var valor_proceso = 3;
                    listado_nombre_servicios.push(valor_proceso);
                    listado_nombre_servicios.push(valor_servicio);
                    listado_nombre_servicios.push($("#valor_tarifa_servicio_controversia_origen").val());
                    // listado_nombre_servicios.push($("#nro_consecutivo_servicio_controversia_origen").val());
                    array_servicios_contratados.push(listado_nombre_servicios);

                    listado_nombre_servicios = [];
                }
            }

            if (id_checkboxes_servicios_contratados === 'checkbox_servicio_controversia_pcl') {                
                if ($(this).is(':checked')) {                
                    var valor_servicio = $(this).val();
                    var valor_proceso = 3;
                    listado_nombre_servicios.push(valor_proceso);
                    listado_nombre_servicios.push(valor_servicio);
                    listado_nombre_servicios.push($("#valor_tarifa_servicio_controversia_pcl").val());
                    // listado_nombre_servicios.push($("#nro_consecutivo_servicio_controversia_pcl").val());
                    array_servicios_contratados.push(listado_nombre_servicios);

                    listado_nombre_servicios = [];
                }
            }
        });

        // Creación de array con los datos de la tabla dinámica ANS
        var guardar_datos_ans = [];
        var datos_finales_ans = [];

        // RECORREMOS LOS TD DE LA TABLA PARA EXTRAER LOS DATOS E INSERTARLOS EN UN ARREGLO 
        // (LA INSERCIÓN LA HACE POR CADA FILA, POR ENDE, ES UN ARRAY MULTIDIMENSIONAL)
        $('#ans tbody tr').each(function (index) {
            // if ($(this).attr('id') !== "datos_examenes_interconsulta") {
                $(this).children("td").each(function (index2) {
                    var nombres_ids = $(this).find('*').attr("id");
                    if (nombres_ids != undefined) {
                        guardar_datos_ans.push($('#'+nombres_ids).val());                        
                    }
                    if((index2+1) % 4 === 0){
                        datos_finales_ans.push(guardar_datos_ans);
                        guardar_datos_ans = [];
                    }
                });
            // }
        });
        

        // Creación de arrays con los datos de la firma cliente
        var guardar_datos_firmas_cliente = [];
        var datos_finales_firmas_cliente = [];

        if($('#firma_del_cliente').summernote('code') != "<p><br></p>"){
            guardar_datos_firmas_cliente.push($("#nombre_del_firmante_cliente").val());
            guardar_datos_firmas_cliente.push($("#cargo_del_firmante_cliente").val());
            guardar_datos_firmas_cliente.push($('#firma_del_cliente').summernote('code'));
            datos_finales_firmas_cliente.push(guardar_datos_firmas_cliente);
            guardar_datos_firmas_cliente = [];

        };
        
        var url_imagenes = [];
        var extension_imagenes = [];
        $.each(datos_finales_firmas_cliente, function (index, subArray) {
            $.each(subArray, function (subIndex, value) {

                var container = document.createElement('div');
                container.innerHTML = value;

                var images = container.getElementsByTagName('img');
                for (var i = 0; i < images.length; i++) {
                    var imageUrl = images[i].currentSrc;
                    var extension = images[i].dataset.filename;
                    url_imagenes.push(imageUrl);
                    extension_imagenes.push(extension.split('.').pop().toLowerCase());
                }
            });
        });

        // Creación de arrays con los datos de la firma proveedor
        var guardar_datos_firmas_proveedor = [];
        var datos_finales_firmas_proveedor = [];

        if($('#firma_del_proveedor').summernote('code')!= "<p><br></p>"){
            guardar_datos_firmas_proveedor.push($("#nombre_del_firmante_proveedor").val());
            guardar_datos_firmas_proveedor.push($("#cargo_del_firmante_proveedor").val());
            guardar_datos_firmas_proveedor.push($('#firma_del_proveedor').summernote('code'));
            datos_finales_firmas_proveedor.push(guardar_datos_firmas_proveedor);
            guardar_datos_firmas_proveedor = [];
        }
        
        var url_imagenes_proveedor = [];
        var extension_imagenes_proveedor = [];
        $.each(datos_finales_firmas_proveedor, function (index, subArray) {
            $.each(subArray, function (subIndex, value) {

                var container_proveedor = document.createElement('div');
                container_proveedor.innerHTML = value;

                var images_proveedor = container_proveedor.getElementsByTagName('img');
                for (var i = 0; i < images_proveedor.length; i++) {
                    var imageUrl_proveedor = images_proveedor[i].currentSrc;
                    var extension_proveedor = images_proveedor[i].dataset.filename;
                    url_imagenes_proveedor.push(imageUrl_proveedor);
                    extension_imagenes_proveedor.push(extension_proveedor.split('.').pop().toLowerCase());
                }
            });
        });

        /* Datos del Footer */
        // var footer_dato_1 = $("#footer_dato_1").val();
        // var footer_dato_2 = $("#footer_dato_2").val();
        // var footer_dato_3 = $("#footer_dato_3").val();
        // var footer_dato_4 = $("#footer_dato_4").val();
        // var footer_dato_5 = $("#footer_dato_5").val();
        
        // Recolección de la información para crear un cliente
        var enviar_info_nuevo_cliente = {
            '_token': token,
            'Tipo_cliente' : $("#tipo_cliente").val(),
            'Otro_tipo_cliente': $("#otro_tipo_cliente").val(),
            'Nombre_cliente' : $("#nombre_cliente").val(),
            'Nit' : $("#nit_cliente").val(),
            'Telefono_principal' : $("#telefono_principal").val(),
            'Otros_telefonos' : $("#otros_telefonos").val(),
            'Email_principal' : $("#email_principal").val(),
            'Otros_emails' : $("#otros_emails").val(),
            'Linea_atencion_principal' : $("#linea_atencion_principal").val(),
            'Otras_lineas_atencion' : $("#otras_lineas_atencion").val(),
            'Direccion' : $("#direccion").val(),
            'Id_Departamento' : $("#departamento").val(),
            'Id_Ciudad' : $("#ciudad").val(),
            'Nro_Contrato': $("#nro_contrato").val(),
            'F_inicio_contrato': $("#f_inicio_contrato").val(),
            'F_finalizacion_contrato': $("#f_finalizacion_contrato").val(),
            'Nro_consecutivo_dictamen': $("#nro_consecutivo_dictamen").val(),
            'Estado': $("#status_cliente").val(),
            'Codigo_cliente': $("#codigo_cliente").val(),
            'Fecha_creacion': $("#fecha_creacion").val(),
            'Sucursales': datos_finales_sucursales,
            'Servicios_contratados': array_servicios_contratados,
            'ANS': datos_finales_ans,
            'Logo': $("#img_codificada").val(),
            'Extension_logo': $("#nombre_ext_imagen").val(),
            'Footer': $("#footer_codificado").val(),
            'Extension_footer': $("#nombre_ext_footer").val(),
            'Firmas': datos_finales_firmas_cliente,
            'Urls': url_imagenes,
            'Extensiones_firmas': extension_imagenes,
            'Firmas_proveedor': datos_finales_firmas_proveedor,
            'Urls_proveedor': url_imagenes_proveedor,
            'Extensiones_firmas_proveedor': extension_imagenes_proveedor,
            // 'footer_dato_1': footer_dato_1,
            // 'footer_dato_2': footer_dato_2,
            // 'footer_dato_3': footer_dato_3,
            // 'footer_dato_4': footer_dato_4,
            // 'footer_dato_5': footer_dato_5
        };
        
        $.ajax({
            type: 'POST',
            url: '/CrearCliente',
            data: enviar_info_nuevo_cliente,
            success: function(response){

                if (response.parametro == "agrego_cliente") {
                    $("#mostrar_barra_creando_cliente").addClass('d-none');
                    $("#mostrar_mensaje_insercion_cliente").removeClass('d-none');
                    $(".mensaje_agrego_cliente").addClass('alert-success');
                    $(".mensaje_agrego_cliente").append('<strong>'+response.mensaje+'</strong>');
                    
                    setTimeout(() => {
                        $("#mostrar_mensaje_insercion_cliente").addClass('d-none');
                        $(".mensaje_agrego_cliente").removeClass('alert-success');
                        $(".mensaje_agrego_cliente").empty();
                        $("#contenedor_btn_guardar_cliente").removeClass('d-none');
                        window.location.reload();
                    }, 3000);
                }else{
                    $("#mostrar_barra_creando_cliente").addClass('d-none');
                    $("#mostrar_mensaje_insercion_cliente").removeClass('d-none');
                    $(".mensaje_agrego_cliente").addClass('alert-danger');
                    $(".mensaje_agrego_cliente").append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(() => {
                        $("#mostrar_mensaje_insercion_cliente").addClass('d-none');
                        $(".mensaje_agrego_cliente").removeClass('alert-success');
                        $(".mensaje_agrego_cliente").empty();
                        $("#contenedor_btn_guardar_cliente").removeClass('d-none');
                        // location.reload();
                    }, 3000);
                }
            }
        });

    });

    // Función solo numeros para input valor ans
    $(document).on('input', "input[id^='valor_ans_']", function(event){
        var value = $(this).val();
      
        // Eliminar todos los caracteres no numéricos y no "."
        value = value.replace(/[^0-9.]/g, '');
        
        // Verificar si hay más de un punto decimal y eliminar el exceso
        var decimalCount = (value.match(/\./g) || []).length;
        if (decimalCount > 1) {
            value = value.replace(/\.+$/,"");
        }
        
        // Actualizar el valor del input
        $(this).val(value);

        /* var value = $(this).val();
        value = value.replace(/\D/g, ""); // Remove non-digit characters
        value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"); // Add comma for thousands separators
        value = value.replace(/(\d)(\.(\d{2}))$/, "$1$2"); // Add period for decimal places
        value = "$" + value; // Add "$" at the beginning
        $(this).val(value); */
    });

    function visualizar_imagen(input,imagen,container) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(container).attr('src', e.target.result);
                $(imagen).val(e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

});

/* Función para añadir los controles de cada elemento de cada fila en la tabla sucursales */
function funciones_elementos_fila_sucursales(num_consecutivo){

    // SELECT 2 DEPARTAMENTOS
    $(".departamento_sucursal_"+num_consecutivo).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    // SELECT2 CIUDADES
    $(".ciudad_sucursal_"+num_consecutivo).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    let token = $("input[name='_token']").val();

    //Listado de departamento
    let datos_lista_departamento_cliente = {
        '_token': token,
        'parametro':"lista_departamentos_cliente"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_departamento_cliente,
        success:function(data) {
            //console.log(data);
            $('#departamento_sucursal_'+num_consecutivo).empty();
            $('#departamento_sucursal_'+num_consecutivo).append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#departamento_sucursal_'+num_consecutivo).append('<option value="'+data[claves[i]]["Id_departamento"]+'">'+data[claves[i]]["Nombre_departamento"]+'</option>');
            }
        }
    });

    // Listado Municipio
    $('#departamento_sucursal_'+num_consecutivo).change( function(){
        $('#ciudad_sucursal_'+num_consecutivo).prop('disabled', false);
        let id_departamento_cliente = $('#departamento_sucursal_'+num_consecutivo).val();
        let datos_municipio_cliente = {
            '_token': token,
            'parametro' : "lista_municipios_cliente",
            'id_departamento_cliente': id_departamento_cliente
        };

        $.ajax({
            type:'POST',
            url:'/cargarselectores',
            data: datos_municipio_cliente,
            success:function(data) {
                //console.log(data);
                $('#ciudad_sucursal_'+num_consecutivo).empty();
                $('#ciudad_sucursal_'+num_consecutivo).append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $('#ciudad_sucursal_'+num_consecutivo).append('<option value="'+data[claves[i]]["Id_municipios"]+'">'+data[claves[i]]["Nombre_municipio"]+'</option>');
                }
            }
        });
    });

}

/* Función para añadir los controles de cada elemento de cada fila en la tabla ans */
function funciones_elementos_fila_ans(num_consecutivo){

    // SELECT 2 DEPARTAMENTOS
    $(".unidad_ans_"+num_consecutivo).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    let token = $("input[name='_token']").val();

    //Listado de departamento
    let datos_lista_unidades_ans = {
        '_token': token,
        'parametro':"lista_unidades_ans"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_unidades_ans,
        success:function(data) {
            //console.log(data);
            $('#unidad_ans_'+num_consecutivo).empty();
            $('#unidad_ans_'+num_consecutivo).append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#unidad_ans_'+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });

}

/* Función para añadir los controles de cada elemento de cada fila en la tabla firmas de cliente */
/* function funciones_elementos_fila_firmas_cliente(num_consecutivo){

    $('#firma_del_cliente_'+num_consecutivo).summernote({
        height: 200,
        lang: "es-ES",
        toolbar: [
            ['font', ['fontname']],
            ['fontsize', ['fontsize']],
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['forecolor']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            ['insert', ['picture']],
        ]
    });

}; */
