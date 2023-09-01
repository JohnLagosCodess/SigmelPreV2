$(document).ready(function(){
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
    //console.log("Posición al refrescar la página: " + posicionActual + "-" + posicionMemoria);
    
    // Inicializacion del select2 de listados  Módulo Calificacion Tecnica PCL
    $(".origen_firme").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".origen_cobertura").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });
    
    $(".decreto_califi").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".motivo_solicitud").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".poblacion_califi").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });


    // llenado de selectores
    let token = $('input[name=_token]').val();

    //Listado de origen firmeza
    let datos_lista_origen_firme = {
        '_token': token,
        'parametro':"lista_origen_firme_pcl"
    };

    $.ajax({
        type:'POST',
        url:'/selectoresCalificacionTecnicaPCL',
        data: datos_lista_origen_firme,
        success:function(data){
            //console.log(data);
            let NombrefirmezaCalificacionPcl = $('select[name=origen_firme]').val();
            let firmezaCalificacionPcl = Object.keys(data);
            for (let i = 0; i < firmezaCalificacionPcl.length; i++) {
                if (data[firmezaCalificacionPcl[i]]['Id_Parametro'] != NombrefirmezaCalificacionPcl) {                    
                    $('#origen_firme').append('<option value="'+data[firmezaCalificacionPcl[i]]['Id_Parametro']+'">'+data[firmezaCalificacionPcl[i]]['Nombre_parametro']+'</option>');
                }
            }
        }
    });

    //Listado de origen cobertura
    let datos_lista_origen_cobertura = {
        '_token': token,
        'parametro':"lista_origen_cobertura_pcl"
    };

    $.ajax({
        type:'POST',
        url:'/selectoresCalificacionTecnicaPCL',
        data: datos_lista_origen_cobertura,
        success:function(data){
            //console.log(data);
            let NombrecoberturaCalificacionPcl = $('select[name=origen_cobertura]').val();
            let coberturaCalificacionPcl = Object.keys(data);
            for (let i = 0; i < coberturaCalificacionPcl.length; i++) {
                if (data[coberturaCalificacionPcl[i]]['Id_Parametro'] != NombrecoberturaCalificacionPcl) {                    
                    $('#origen_cobertura').append('<option value="'+data[coberturaCalificacionPcl[i]]['Id_Parametro']+'">'+data[coberturaCalificacionPcl[i]]['Nombre_parametro']+'</option>');
                }
            }
        }
    });

    //Listado de origen cobertura
    let datos_lista_cali_decreto = {
        '_token': token,
        'parametro':"lista_cali_decreto_pcl"
    };

    $.ajax({
        type:'POST',
        url:'/selectoresCalificacionTecnicaPCL',
        data: datos_lista_cali_decreto,
        success:function(data){
            //console.log(data);
            let NombredecretoCalificacionPcl = $('select[name=decreto_califi]').val();
            let decretoCalificacionPcl = Object.keys(data);
            for (let i = 0; i < decretoCalificacionPcl.length; i++) {
                if (data[decretoCalificacionPcl[i]]['Id_Decreto'] != NombredecretoCalificacionPcl) {                    
                    $('#decreto_califi').append('<option value="'+data[decretoCalificacionPcl[i]]['Id_Decreto']+'">'+data[decretoCalificacionPcl[i]]['Nombre_decreto']+'</option>');
                }
            }
        }
    });

    //Listado de motivo solicitud
    let datos_lista_motivo_solicitud = {
        '_token': token,
        'parametro':"lista_motivo_solicitud"
    };

    $.ajax({
        type:'POST',
        url:'/selectoresCalificacionTecnicaPCL',
        data: datos_lista_motivo_solicitud,
        success:function(data){
            //console.log(data);
            let NombremotivoCalificacionPcl = $('select[name=motivo_solicitud]').val();
            let motivoSolicitudPcl = Object.keys(data);
            for (let i = 0; i < motivoSolicitudPcl.length; i++) {
                if (data[motivoSolicitudPcl[i]]['Id_Solicitud'] != NombremotivoCalificacionPcl) {                    
                    $('#motivo_solicitud').append('<option value="'+data[motivoSolicitudPcl[i]]['Id_Solicitud']+'">'+data[motivoSolicitudPcl[i]]['Nombre_solicitud']+'</option>');
                }
            }
        }
    });

    //Listado de poblacion a calificar
    let datos_lista_poblacion_calificar = {
        '_token': token,
        'parametro':"lista_poblacion_calificar"
    };

    $.ajax({
        type:'POST',
        url:'/selectoresCalificacionTecnicaPCL',
        data: datos_lista_poblacion_calificar,
        success:function(data){
            //console.log(data);
            let NombrePoblacionCalifiPcl = $('select[name=poblacion_califi]').val();
            let motivoPoblacionPcl = Object.keys(data);
            for (let i = 0; i < motivoPoblacionPcl.length; i++) {
                if (data[motivoPoblacionPcl[i]]['Id_Parametro'] != NombrePoblacionCalifiPcl) {                    
                    $('#poblacion_califi').append('<option value="'+data[motivoPoblacionPcl[i]]['Id_Parametro']+'">'+data[motivoPoblacionPcl[i]]['Nombre_parametro']+'</option>');
                }
            }
        }
    });

    /* VALIDACIÓN MOSTRAR ITEMS DE ACUERDO A DECRETO  */ 
    var opt_decreto;
    var opt_origen;
    var opt_cobertura;
    $("#origen_firme").change(function(){
        opt_origen = parseInt($(this).val());
        $("#origen_firme").val(opt_origen);
        iniciarIntervalo_decreto();
    }); 
    $("#origen_cobertura").change(function(){
        opt_cobertura = parseInt($(this).val());
        $("#origen_cobertura").val(opt_cobertura);
        iniciarIntervalo_decreto();
    }); 
    $("#decreto_califi").change(function(){
        opt_decreto = parseInt($(this).val());
        $("#decreto_califi").val(opt_decreto);
        iniciarIntervalo_decreto();
        //console.log(opt_decreto)
    }); 

    // Función para validar items a mostrar
    const tiempoDeslizamiento = 'slow';
    const tiempoDeslizamiento2 = 'slow';
    const tiempoDeslizamiento3 = 'slow';

    function iniciarIntervalo_decreto() {
        // Selección de los elementos que se deslizarán
        const elementosDeslizar = [
            '.columna_row1_afiliado',
            '.columna_row1_dictamen',
            '.columna_row1_documentos',
            '.columna_row1_fundamentos',
            '.columna_row1_interconsulta',
            '.columna_row1_motivo_cali',
            '.columna_row1_deficiencia',
            '.columna_row1_dictamen'
        ];
        
       const elementosDeslizar2 = [
            '.columna_row1_auditivo',
            '.columna_row1_visual',
            '.columna_row1_valoracion_laboral'
        ];
        const elementosDeslizar3 = [
            '.columna_row1_discapacidades',
            '.columna_row1_minusvalias'
        ];

        intervalo = setInterval(() => {
            
            if(opt_origen == 48 && opt_cobertura == 50) { // si origen y cobertura es SI

                switch (opt_decreto) {
                    case 1:
                        elementosDeslizar.forEach(elemento => {
                            $(elemento).slideDown(tiempoDeslizamiento);
                        }); 
                        elementosDeslizar2.forEach(elemento => {
                            $(elemento).slideDown(tiempoDeslizamiento2);
                        });
                        elementosDeslizar3.forEach(elemento => {
                            $(elemento).slideUp(tiempoDeslizamiento3);
                        });
                    break;
                    
                    case 2: 
                        elementosDeslizar.forEach(elemento => {
                            $(elemento).slideDown(tiempoDeslizamiento);
                        });
                        // Deslizar hacia arriba (ocultar) los elementos
                        elementosDeslizar2.forEach(elemento => {
                            $(elemento).slideUp(tiempoDeslizamiento2);
                        });
                        elementosDeslizar3.forEach(elemento => {
                            $(elemento).slideUp(tiempoDeslizamiento3);
                        });
                    break;
    
                    case 3: 
                        elementosDeslizar.forEach(elemento => {
                            $(elemento).slideDown(tiempoDeslizamiento);
                        });
                        elementosDeslizar3.forEach(elemento => {
                            $(elemento).slideDown(tiempoDeslizamiento3);
                        });
                        // Deslizar hacia arriba (ocultar) los elementos
                        elementosDeslizar2.forEach(elemento => {
                            $(elemento).slideUp(tiempoDeslizamiento2);
                        });
                    break;
                
                    default:
                        // Deslizar hacia arriba (ocultar) los elementos
                        elementosDeslizar.forEach(elemento => {
                            $(elemento).slideUp(tiempoDeslizamiento);
                        });
                        elementosDeslizar2.forEach(elemento => {
                            $(elemento).slideUp(tiempoDeslizamiento2);
                        });
                        elementosDeslizar3.forEach(elemento => {
                            $(elemento).slideUp(tiempoDeslizamiento3);
                        });
                    break;
                }

            } else {
                // Deslizar hacia arriba (ocultar) los elementos
                elementosDeslizar.forEach(elemento => {
                    $(elemento).slideUp(tiempoDeslizamiento);
                });
                elementosDeslizar2.forEach(elemento => {
                    $(elemento).slideUp(tiempoDeslizamiento2);
                });
                elementosDeslizar3.forEach(elemento => {
                    $(elemento).slideUp(tiempoDeslizamiento3);
                });
            }
        }, 500);
    }

    /* VALIDACIÓN MOSTRAR ITEMS LABORAL DE ACUERDO AL ROL  */ 
    var opt_tipo_laboral;
    var opt_tipo_poblacion;
    $("#laboral_actual").change(function(){
        opt_tipo_laboral = $('#laboral_actual').val();
        $("#laboral_actual").val(opt_tipo_laboral);
        iniciarIntervalo_laboral();
    }); 
    $("#rol_ocupacional").change(function(){
        opt_tipo_laboral = $('#rol_ocupacional').val();
        $("#rol_ocupacional").val(opt_tipo_laboral);
        iniciarIntervalo_laboral();
    }); 
    
    $("#poblacion_califi").change(function(){
        opt_tipo_poblacion = $('#poblacion_califi').val();
        $("#poblacion_califi").val(opt_tipo_poblacion);
        iniciarIntervalo_poblacion();
    }); 
    // Función para validar items a mostrar
    const DeslizamientoLaboral = 'slow';
    const DeslizamientoOcupacional = 'slow';
    const DeslizamientoPoblacion = 'slow';

    function iniciarIntervalo_laboral() {
        // Selección de los elementos que se deslizarán
        const elementosDeslizar = [
            '.columna_row1_rol_laboral',
            '.columna_row1_otras_areas'
        ];
        const elementosDeslizar2 = [
            '.columna_row1_rol_ocupacional'
        ];
        intervalo = setInterval(() => {
            switch (opt_tipo_laboral) {
                case "Laboralmente_activo":
                    elementosDeslizar.forEach(elemento => {
                        $(elemento).slideDown(DeslizamientoLaboral);
                    });
                    elementosDeslizar2.forEach(elemento => {
                        $(elemento).slideUp(DeslizamientoOcupacional);
                    }); 
                break;
                case "Rol_ocupacional":
                    elementosDeslizar2.forEach(elemento => {
                        $(elemento).slideDown(DeslizamientoOcupacional);
                    }); 
                    elementosDeslizar.forEach(elemento => {
                        $(elemento).slideUp(DeslizamientoLaboral);
                    }); 
                break;
                default:
                    // Deslizar hacia arriba (ocultar) los elementos
                    elementosDeslizar.forEach(elemento => {
                        $(elemento).slideUp(DeslizamientoLaboral);
                    });
                    elementosDeslizar2.forEach(elemento => {
                        $(elemento).slideUp(DeslizamientoOcupacional);
                    }); 
                break;
            }
        }, 500);
      
    }

    //Validación de acuerdo de poblacion
    function iniciarIntervalo_poblacion() {
        // Selección de los elementos que se deslizarán
        const elementosDeslizar = [
            '.columna_row1_tabla_12'
        ];
        const elementosDeslizar2 = [
            '.columna_row1_tabla_13'
        ];
        const elementosDeslizar3 = [
            '.columna_row1_tabla_14'
        ];

        intervalo = setInterval(() => {
            switch (opt_tipo_poblacion) {
                case "75":
                    elementosDeslizar.forEach(elemento => {
                        $(elemento).slideDown(DeslizamientoPoblacion);
                    });
                    elementosDeslizar2.forEach(elemento => {
                        $(elemento).slideUp(DeslizamientoPoblacion);
                    });
                    elementosDeslizar3.forEach(elemento => {
                        $(elemento).slideUp(DeslizamientoPoblacion);
                    });
                break;
                case "76":
                    elementosDeslizar2.forEach(elemento => {
                        $(elemento).slideDown(DeslizamientoPoblacion);
                    }); 
                    elementosDeslizar.forEach(elemento => {
                        $(elemento).slideUp(DeslizamientoPoblacion);
                    });
                    elementosDeslizar3.forEach(elemento => {
                        $(elemento).slideUp(DeslizamientoPoblacion);
                    });
                break;
                case "77":
                    elementosDeslizar3.forEach(elemento => {
                        $(elemento).slideDown(DeslizamientoPoblacion);
                    }); 
                    elementosDeslizar.forEach(elemento => {
                        $(elemento).slideUp(DeslizamientoPoblacion);
                    });
                    elementosDeslizar2.forEach(elemento => {
                        $(elemento).slideUp(DeslizamientoPoblacion);
                    });
                break;
                default:
                    // Deslizar hacia arriba (ocultar) los elementos
                    elementosDeslizar.forEach(elemento => {
                        $(elemento).slideUp(DeslizamientoPoblacion);
                    });
                    elementosDeslizar2.forEach(elemento => {
                        $(elemento).slideUp(DeslizamientoPoblacion);
                    });
                    elementosDeslizar3.forEach(elemento => {
                        $(elemento).slideUp(DeslizamientoPoblacion);
                    });
                break;
            }
        }, 500);


    }
    /* VALIDACIÓN MOSTRAR LA SUMA TOTAL Rol Laboral (30%)  */
    /*var opt_tabla_1;
    var opt_tabla_2;
    var opt_total_laboral30;
    $("[name='restricion_rol']").on("change", function(){
        opt_tabla_1 = $(this).val();
        $(this).val(opt_tabla_1);
        iniciarIntervalo_total_laboral30();
    });
    $("[name='auto_suficiencia']").on("change", function(){
        opt_tabla_2 = $(this).val();
        $(this).val(opt_tabla_2);
        iniciarIntervalo_total_laboral30();
    });

    //Realiza la suma
    function iniciarIntervalo_total_laboral30() {
        intervalo = setInterval(() => {
            opt_total_laboral30 = opt_tabla_1 + opt_tabla_2;
            console.log(opt_total_laboral30);
        }, 500);
    
    }*/

    let opt_tabla_1 = 0;
    let opt_tabla_2 = 0;
    let opt_tabla_3 = 0;
    let opt_total_laboral30;

    $("[name='restricion_rol']").on("change", function(){
        opt_tabla_1 = $(this).val();
        $(this).val(opt_tabla_1);
        iniciarIntervaloTotalLaboral30();
    });

    $("[name='auto_suficiencia']").on("change", function(){
        opt_tabla_2 = $(this).val();
        $(this).val(opt_tabla_2);
        iniciarIntervaloTotalLaboral30();
    });

    $("[name='edad_cronologica']").on("change", function(){
        opt_tabla_3 = $(this).val();
        $(this).val(opt_tabla_3);
        iniciarIntervaloTotalLaboral30();
    });
    
    //Realiza suma de tablas 1,2,3
    function iniciarIntervaloTotalLaboral30() {
        clearInterval(intervalo);
        intervalo = setInterval(() => {
            opt_total_laboral30 = Number(opt_tabla_1) + Number(opt_tabla_2)+ Number(opt_tabla_3);
            if(!isNaN(opt_total_laboral30)){
                //console.log(opt_total_laboral30);
                $('#resultado_rol_laboral_30').val(opt_total_laboral30); //Coloca resultado Rol Laboral (30%)
            }
        }, 500);
    }
    //Tabla 6 - Aprendizaje y aplicación del conocimiento




    $('#form_CaliTecDecreto').submit(function (e){
        e.preventDefault();
        var Id_EventoDecreto = $('#Id_Evento_decreto').val();
        var Id_ProcesoDecreto = $('#Id_Proceso_decreto').val();
        var Id_Asignacion_Dcreto  = $('#Id_Asignacion_decreto').val();
        var origenFirme = $('#origen_firme').val();
        var cobertura = $('#origen_cobertura').val();
        var decreto = $('#decreto_califi').val();
        var numeroDictamen = $('#numero_dictamen').val();
        var motivoSolicitud = $('#motivo_solicitud').val();
        var Relacion_Documentos = [];
        $('input[type="checkbox"]').each(function() {
            var relacion_documento = $(this).attr('id');            
            if (relacion_documento === 'hitoria_clinica' || relacion_documento === 'examanes_preocupacionales' || 
                relacion_documento === 'epicrisis' || relacion_documento === 'examanes_periodicos' || 
                relacion_documento === 'examanes_paraclinicos' || relacion_documento === 'examanes_post_ocupacionales' || 
                relacion_documento === 'salud_ocupacionales') {                
                if ($(this).is(':checked')) {                
                var relacion_documento_valor = $(this).val();
                Relacion_Documentos.push(relacion_documento_valor);
                }
            }
        });
        var otros = $('#descripcion_otros').val();
        var descripcionEnfermedad = $('#descripcion_enfermedad').val();
        var bandera_decreto_guardar_actualizar = $('#bandera_decreto_guardar_actualizar').val();
        let token = $('input[name=_token]').val();
        var datos_agregarDecretoDicRelFun ={
            '_token': token,
            'Id_Evento_decreto':Id_EventoDecreto,
            'Id_Proceso_decreto':Id_ProcesoDecreto,
            'Id_Asignacion_decreto':Id_Asignacion_Dcreto,
            'origen_firme':origenFirme,
            'origen_cobertura':cobertura,
            'decreto_califi':decreto,
            'numeroDictamen':numeroDictamen,
            'motivo_solicitud':motivoSolicitud,
            'Relacion_Documentos':Relacion_Documentos,
            'descripcion_otros':otros,
            'descripcion_enfermedad':descripcionEnfermedad,
            'bandera_decreto_guardar_actualizar':bandera_decreto_guardar_actualizar,
        }
        $.ajax({
            type:'POST',
            url:'/guardarDecretoDictamenRelacionDocFunda',
            data: datos_agregarDecretoDicRelFun,
            success: function(response){
                if (response.parametro == 'agregar_decreto_parte') {
                    document.querySelector('#GuardarDecreto').disabled=true;
                    $('#div_alerta_decreto').removeClass('d-none');
                    $('.alerta_decreto').append('<strong>'+response.mensaje+'</strong>');                                            
                    setTimeout(function(){
                        $('#div_alerta_decreto').addClass('d-none');
                        $('.alerta_decreto').empty();   
                        location.reload();
                    }, 3000);   
                }else if(response.parametro == 'update_decreto_parte'){
                    document.querySelector('#ActualizarDecreto').disabled=true;
                    $('#div_alerta_decreto').removeClass('d-none');
                    $('.alerta_decreto').append('<strong>'+response.mensaje2+'</strong>');                                            
                    setTimeout(function(){
                        $('#div_alerta_decreto').addClass('d-none');
                        $('.alerta_decreto').empty();
                        document.querySelector('#ActualizarDecreto').disabled=false;
                    }, 3000);
                }

            }
        })
    })

    
});

$(document).ready(function(){
    $("#guardar_datos_examenes").click(function(){       
            
        let token = $("input[name='_token']").val();
        var guardar_datos = [];
        var datos_finales_examenes_interconsultas = [];
        var array_id_filas = [];
        // RECORREMOS LOS TD DE LA TABLA PARA EXTRAER LOS DATOS E INSERTARLOS EN UN ARREGLO (LA INSERCIÓN LA HACE POR CADA FILA, POR ENDE, ES UN ARRAY MULTIDIMENSIONAL)
        $('#listado_examenes_interconsultas tbody tr').each(function (index) {
            array_id_filas.push($(this).attr('id'));
            if ($(this).attr('id') !== "datos_examenes_interconsulta") {
                $(this).children("td").each(function (index2) {
                    var nombres_ids = $(this).find('*').attr("id");
                    if (nombres_ids != undefined) {
                        guardar_datos.push($('#'+nombres_ids).val());
                    }
                    if((index2+1) % 3 === 0){
                        datos_finales_examenes_interconsultas.push(guardar_datos);
                        guardar_datos = [];
                    }
                });
            }
        });
        
        // ENVÍO POR AJAX LA INFORMACIÓN FINAL DE LA TABLA, JUNTO CON EL ID EVENTO, ID ASIGNACION, ID PROCESO
                  
        var envio_datos_examenes = {
            '_token': token,
            'datos_finales_examenes_interconsultas' : datos_finales_examenes_interconsultas,
            'Id_evento': $('#Id_Evento_decreto').val(),
            'Id_Asignacion': $('#Id_Asignacion_decreto').val(),
            'Id_proceso': $('#Id_Proceso_decreto').val(),                
        };            
        $.ajax({
            type:'POST',
            url:'/guardarExamenesInterconsultas',
            data: envio_datos_examenes,
            success:function(response){
                // console.log(response);
                if (response.parametro == "inserto_informacion") {
                    $('#resultado_insercion_examen').removeClass('d-none');
                    $('#resultado_insercion_examen').addClass('alert-success');
                    $('#resultado_insercion_examen').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(() => {
                        $('#resultado_insercion_examen').addClass('d-none');
                        $('#resultado_insercion_examen').removeClass('alert-success');
                        $('#resultado_insercion_examen').empty();
                        location.reload();
                    }, 3000);
                }
            }
            
        });        
               
    });     
});

$(document).ready(function(){
    $(document).on('click', "a[id^='btn_remover_examen_fila_examenes_']", function(){

        let token = $("input[name='_token']").val();
        var datos_fila_quitar_examen = {
            '_token': token,
            'fila' : $(this).data("id_fila_quitar"),
            'Id_evento': $('#Id_Evento_decreto').val()
        };
        
        $.ajax({
            type:'POST',
            url:'/eliminarExamenesInterconsultas',
            data: datos_fila_quitar_examen,
            success:function(response){
                // console.log(response);
                if (response.parametro == "fila_examen_eliminada") {
                    $('#resultado_insercion_examen').empty();
                    $('#resultado_insercion_examen').removeClass('d-none');
                    $('#resultado_insercion_examen').addClass('alert-success');
                    $('#resultado_insercion_examen').append('<strong>'+response.mensaje+'</strong>');
                    
                    setTimeout(() => {
                        $('#resultado_insercion_examen').addClass('d-none');
                        $('#resultado_insercion_examen').removeClass('alert-success');
                        $('#resultado_insercion_examen').empty();
                    }, 3000);
                }
                if (response.total_registros == 0) {
                    $("#conteo_listado_examenes_interconsulta").val(response.total_registros);
                }
            }
        });        

    });
});

/* Función para añadir los controles de cada elemento de cada fila en la tabla Diagnostico motivo de calificación*/
function funciones_elementos_fila_diagnosticos(num_consecutivo) {
    // Inicializacion de select 2
    $("#lista_Cie10_fila_"+num_consecutivo).select2({
        width: '100%',
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
                $("#lista_Cie10_fila_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Cie_diagnostico"]+'">'+data[claves[i]]["CIE10"]+'</option>');
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

$(document).ready(function(){
    $("#guardar_datos_cie10").click(function(){       
            
        let token = $("input[name='_token']").val();
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
        
        // ENVÍO POR AJAX LA INFORMACIÓN FINAL DE LA TABLA, JUNTO CON EL ID EVENTO, ID ASIGNACION, ID PROCESO
                  
        var envio_datos_diagnosticos = {
            '_token': token,
            'datos_finales_diagnosticos_moticalifi' : datos_finales_diagnosticos_moticalifi,
            'Id_evento': $('#Id_Evento_decreto').val(),
            'Id_Asignacion': $('#Id_Asignacion_decreto').val(),
            'Id_proceso': $('#Id_Proceso_decreto').val(),                
        };            
        $.ajax({
            type:'POST',
            url:'/guardarDiagnosticosMotivoCalificacion',
            data: envio_datos_diagnosticos,
            success:function(response){
                // console.log(response);
                if (response.parametro == "inserto_diagnostico") {
                    $('#resultado_insercion_cie10').removeClass('d-none');
                    $('#resultado_insercion_cie10').addClass('alert-success');
                    $('#resultado_insercion_cie10').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(() => {
                        $('#resultado_insercion_cie10').addClass('d-none');
                        $('#resultado_insercion_cie10').removeClass('alert-success');
                        $('#resultado_insercion_cie10').empty();
                        location.reload();
                    }, 3000);
                }
            }
            
        });      
               
    });     
});

$(document).ready(function(){
    $(document).on('click', "a[id^='btn_remover_diagnosticos_moticalifi']", function(){

        let token = $("input[name='_token']").val();
        var datos_fila_quitar_examen = {
            '_token': token,
            'fila' : $(this).data("id_fila_quitar"),
            'Id_evento': $('#Id_Evento_decreto').val()
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
});

$(document).ready(function(){
    $(document).on('click', "a[id^='btn_remover_examen_fila_agudeza']", function(){

        let token = $("input[name='_token']").val();
        var datos_fila_quitar_examen = {
            '_token': token,
            'fila' : $(this).data("id_fila_quitar"),
            'Id_evento': $('#Id_Evento_decreto').val()
        };
        
        $.ajax({
            type:'POST',
            url:'/eliminarAgudezasAuditivas',
            data: datos_fila_quitar_examen,
            success:function(response){
                // console.log(response);
                if (response.parametro == "fila_agudeza_auditiva_eliminada") {
                    $('#eliminar_agudeza_auditiva').empty();
                    $('#eliminar_agudeza_auditiva').removeClass('d-none');
                    $('#eliminar_agudeza_auditiva').addClass('alert-success');
                    $('#eliminar_agudeza_auditiva').append('<strong>'+response.mensaje+'</strong>');
                    
                    setTimeout(() => {
                        $('#eliminar_agudeza_auditiva').addClass('d-none');
                        $('#eliminar_agudeza_auditiva').removeClass('alert-success');
                        $('#eliminar_agudeza_auditiva').empty();
                        location.reload();
                    }, 3000);
                }
                if (response.total_registros == 0) {
                    $("#conteo_listado_agudeza_auditiva").val(response.total_registros);
                }
            }
        });        

    });
});

$(document).ready(function(){
    $(document).on('click', "input[id^='dx_principal_auditiva']", function(){

        var checkboxDxPrincipal = document.getElementById('dx_principal_auditiva');        
        let token = $("input[name='_token']").val();
        var dxPrincipal = $('#dx_principal_auditiva').val();
        var banderaDxPrincipal = $('#banderaDxPrincipal').val();
        if (checkboxDxPrincipal.checked) {
            console.log("El checkbox está marcado");
            console.log(banderaDxPrincipal);
            var datos_actualizar_dxPrincial = {
                '_token': token,
                'dxPrincipal' : dxPrincipal,
                'banderaDxPrincipal': banderaDxPrincipal,
                'Id_evento': $('#Id_Evento_decreto').val()
            };       
            
            $.ajax({
                type:'POST',
                url:'/actualizarDxPrincipalAdudezaAuditivas',
                data: datos_actualizar_dxPrincial,
                success:function(response){
                    // console.log(response);
                    if (response.parametro == "fila_dxPrincipalagudeza_auditiva_agregado") {
                        $('#eliminar_agudeza_auditiva').empty();
                        $('#eliminar_agudeza_auditiva').removeClass('d-none');
                        $('#eliminar_agudeza_auditiva').addClass('alert-success');
                        $('#eliminar_agudeza_auditiva').append('<strong>'+response.mensaje+'</strong>');
                        
                        setTimeout(() => {
                            $('#eliminar_agudeza_auditiva').addClass('d-none');
                            $('#eliminar_agudeza_auditiva').removeClass('alert-success');
                            $('#eliminar_agudeza_auditiva').empty();
                            location.reload();
                        }, 3000);
                    }                
                }
            }); 
        } else {            
            console.log("El checkbox no está marcado");
            console.log(banderaDxPrincipal);
            var datos_actualizar_dxPrincial = {
                '_token': token,
                'dxPrincipal' : dxPrincipal,
                'banderaDxPrincipal': banderaDxPrincipal,
                'Id_evento': $('#Id_Evento_decreto').val()
            };       
            
            $.ajax({
                type:'POST',
                url:'/actualizarDxPrincipalAdudezaAuditivas',
                data: datos_actualizar_dxPrincial,
                success:function(response){
                    // console.log(response);
                    if (response.parametro == "fila_dxPrincipalagudeza_auditiva_agregado") {
                        $('#eliminar_agudeza_auditiva').empty();
                        $('#eliminar_agudeza_auditiva').removeClass('d-none');
                        $('#eliminar_agudeza_auditiva').addClass('alert-success');
                        $('#eliminar_agudeza_auditiva').append('<strong>'+response.mensaje+'</strong>');
                        
                        setTimeout(() => {
                            $('#eliminar_agudeza_auditiva').addClass('d-none');
                            $('#eliminar_agudeza_auditiva').removeClass('alert-success');
                            $('#eliminar_agudeza_auditiva').empty();
                            location.reload();
                        }, 3000);
                    }                
                }
            });
        }

    });
});

