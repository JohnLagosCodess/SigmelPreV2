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
        iniciarIntervaloOtrasAreas();
        iniciarIntervaloLaboralOtras();
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
    let opt_tabla_1 = 0;
    let opt_tabla_2 = 0;
    let opt_tabla_3 = 0;
    let opt_total_laboral30 = 0;

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
                $('#resultado_rol_laboral_30').val(redondear(opt_total_laboral30)); //Coloca resultado Rol Laboral (30%)
            }
        }, 500);
    }
    //Tabla 6 - Aprendizaje y aplicación del conocimiento
    let opt_tabla6_mirar = 0;
    let opt_tabla6_escuchar = 0;
    let opt_tabla6_aprender = 0;
    let opt_tabla6_calcular = 0;
    let opt_tabla6_pensar = 0;
    let opt_tabla6_leer = 0;
    let opt_tabla6_escribir = 0;
    let opt_tabla6_matematicos = 0;
    let opt_tabla6_decisiones = 0;
    let opt_tabla6_tareas_simples = 0;
    var opt_total_tabla6 = 0;

    $("[name='mirar']").on("change", function(){
        opt_tabla6_mirar = $(this).val();
        $(this).val(opt_tabla6_mirar);
        iniciarIntervaloTotaltabla6();
    });

    $("[name='escuchar']").on("change", function(){
        opt_tabla6_escuchar = $(this).val();
        $(this).val(opt_tabla6_escuchar);
        iniciarIntervaloTotaltabla6();
    });

    $("[name='aprender']").on("change", function(){
        opt_tabla6_aprender = $(this).val();
        $(this).val(opt_tabla6_aprender);
        iniciarIntervaloTotaltabla6();
    });

    $("[name='calcular']").on("change", function(){
        opt_tabla6_calcular = $(this).val();
        $(this).val(opt_tabla6_calcular);
        iniciarIntervaloTotaltabla6();
    });

    $("[name='pensar']").on("change", function(){
        opt_tabla6_pensar = $(this).val();
        $(this).val(opt_tabla6_pensar);
        iniciarIntervaloTotaltabla6();
    });

    $("[name='leer']").on("change", function(){
        opt_tabla6_leer = $(this).val();
        $(this).val(opt_tabla6_leer);
        iniciarIntervaloTotaltabla6();
    });

    $("[name='escribir']").on("change", function(){
        opt_tabla6_escribir = $(this).val();
        $(this).val(opt_tabla6_escribir);
        iniciarIntervaloTotaltabla6();
    });

    $("[name='matematicos']").on("change", function(){
        opt_tabla6_matematicos = $(this).val();
        $(this).val(opt_tabla6_matematicos);
        iniciarIntervaloTotaltabla6();
    });

    $("[name='decisiones']").on("change", function(){
        opt_tabla6_decisiones = $(this).val();
        $(this).val(opt_tabla6_decisiones);
        iniciarIntervaloTotaltabla6();
    });

    $("[name='tareas_simples']").on("change", function(){
        opt_tabla6_tareas_simples = $(this).val();
        $(this).val(opt_tabla6_tareas_simples);
        iniciarIntervaloTotaltabla6();
    });

    //Realiza suma de tabla 6
    function iniciarIntervaloTotaltabla6() {
        clearInterval(intervalo);
        intervalo = setInterval(() => {
            opt_total_tabla6 = Number(opt_tabla6_mirar) + Number(opt_tabla6_escuchar)+ Number(opt_tabla6_aprender)
            + Number(opt_tabla6_calcular)+ Number(opt_tabla6_pensar)+ Number(opt_tabla6_leer)+ Number(opt_tabla6_escribir)
            + Number(opt_tabla6_matematicos)+ Number(opt_tabla6_decisiones)+ Number(opt_tabla6_tareas_simples);
            if(!isNaN(opt_total_tabla6)){
                $('#resultado_tabla6').val(redondear(opt_total_tabla6)); 
            }
        }, 500);
    }
    //Tabla 7 - Categorías del área ocupacional de comunicación
    let opt_tabla7_comuni_verbal = 0;
    let opt_tabla7_no_comuni_verbal = 0;
    let opt_tabla7_comuni_signos = 0;
    let opt_tabla7_comuni_escrito = 0;
    let opt_tabla7_habla = 0;
    let opt_tabla7_no_verbales = 0;
    let opt_tabla7_mensajes_escritos = 0;
    let opt_tabla7_sostener_conversa = 0;
    let opt_tabla7_iniciar_discusiones = 0;
    let opt_tabla7_utiliza_dispositivos = 0;
    let opt_total_tabla7 = 0;
    
    $("[name='comunicarse_mensaje']").on("change", function(){
        opt_tabla7_comuni_verbal = $(this).val();
        $(this).val(opt_tabla7_comuni_verbal);
        iniciarIntervaloTotaltabla7();
    });

    $("[name='no_comunicarse_mensaje']").on("change", function(){
        opt_tabla7_no_comuni_verbal = $(this).val();
        $(this).val(opt_tabla7_no_comuni_verbal);
        iniciarIntervaloTotaltabla7();
    });

    $("[name='comunicarse_signos']").on("change", function(){
        opt_tabla7_comuni_signos = $(this).val();
        $(this).val(opt_tabla7_comuni_signos);
        iniciarIntervaloTotaltabla7();
    });

    $("[name='comunicarse_escrito']").on("change", function(){
        opt_tabla7_comuni_escrito = $(this).val();
        $(this).val(opt_tabla7_comuni_escrito);
        iniciarIntervaloTotaltabla7();
    });

    $("[name='habla']").on("change", function(){
        opt_tabla7_habla = $(this).val();
        $(this).val(opt_tabla7_habla);
        iniciarIntervaloTotaltabla7();
    });

    $("[name='no_verbales']").on("change", function(){
        opt_tabla7_no_verbales = $(this).val();
        $(this).val(opt_tabla7_no_verbales);
        iniciarIntervaloTotaltabla7();
    });

    $("[name='mensajes_escritos']").on("change", function(){
        opt_tabla7_mensajes_escritos = $(this).val();
        $(this).val(opt_tabla7_mensajes_escritos);
        iniciarIntervaloTotaltabla7();
    });

    $("[name='sostener_conversa']").on("change", function(){
        opt_tabla7_sostener_conversa = $(this).val();
        $(this).val(opt_tabla7_sostener_conversa);
        iniciarIntervaloTotaltabla7();
    });

    $("[name='iniciar_discusiones']").on("change", function(){
        opt_tabla7_iniciar_discusiones = $(this).val();
        $(this).val(opt_tabla7_iniciar_discusiones);
        iniciarIntervaloTotaltabla7();
    });

    $("[name='utiliza_dispositivos']").on("change", function(){
        opt_tabla7_utiliza_dispositivos = $(this).val();
        $(this).val(opt_tabla7_utiliza_dispositivos);
        iniciarIntervaloTotaltabla7();
    });

    //Realiza suma de tabla 7
    function iniciarIntervaloTotaltabla7() {
        clearInterval(intervalo);
        intervalo = setInterval(() => {
            opt_total_tabla7 = Number(opt_tabla7_comuni_verbal) + Number(opt_tabla7_no_comuni_verbal)+ Number(opt_tabla7_comuni_signos)
            + Number(opt_tabla7_comuni_escrito)+ Number(opt_tabla7_habla)+ Number(opt_tabla7_no_verbales)+ Number(opt_tabla7_mensajes_escritos)
            + Number(opt_tabla7_sostener_conversa)+ Number(opt_tabla7_iniciar_discusiones)+ Number(opt_tabla7_utiliza_dispositivos);
            if(!isNaN(opt_total_tabla7)){
                $('#resultado_tabla7').val(redondear(opt_total_tabla7));
            }
        }, 500);
    }
    //Tabla 8 - Relación de categorías del área ocupacional de movilidad
    let opt_tabla8_cambiar_posturas = 0;
    let opt_tabla8_posicion_cuerpo = 0;
    let opt_tabla8_llevar_objetos = 0;
    let opt_tabla8_uso_fino_mano = 0;
    let opt_tabla8_uso_mano_brazo = 0;
    let opt_tabla8_desplazarse_entorno = 0;
    let opt_tabla8_distintos_lugares = 0;
    let opt_tabla8_desplaza_con_equipo = 0;
    let opt_tabla8_transporte_pasajero = 0;
    let  opt_tabla8_conduccion = 0;
    let opt_total_tabla8 = 0;
    
    $("[name='cambiar_posturas']").on("change", function(){
        opt_tabla8_cambiar_posturas = $(this).val();
        $(this).val(opt_tabla8_cambiar_posturas);
        iniciarIntervaloTotaltabla8();
    });

    $("[name='posicion_cuerpo']").on("change", function(){
        opt_tabla8_posicion_cuerpos = $(this).val();
        $(this).val(opt_tabla8_posicion_cuerpo);
        iniciarIntervaloTotaltabla8();
    });

    $("[name='llevar_objetos']").on("change", function(){
        opt_tabla8_llevar_objetos = $(this).val();
        $(this).val(opt_tabla8_llevar_objetos);
        iniciarIntervaloTotaltabla8();
    });

    $("[name='uso_fino_mano']").on("change", function(){
        opt_tabla8_uso_fino_mano = $(this).val();
        $(this).val(opt_tabla8_uso_fino_mano);
        iniciarIntervaloTotaltabla8();
    });

    $("[name='uso_mano_brazo']").on("change", function(){
        opt_tabla8_uso_mano_brazo = $(this).val();
        $(this).val(opt_tabla8_uso_mano_brazo);
        iniciarIntervaloTotaltabla8();
    });

    $("[name='desplazarse_entorno']").on("change", function(){
        opt_tabla8_desplazarse_entorno = $(this).val();
        $(this).val(opt_tabla8_desplazarse_entorno);
        iniciarIntervaloTotaltabla8();
    });

    $("[name='distintos_lugares']").on("change", function(){
        opt_tabla8_distintos_lugares = $(this).val();
        $(this).val(opt_tabla8_distintos_lugares);
        iniciarIntervaloTotaltabla8();
    });

    $("[name='desplazarse_con_equipo']").on("change", function(){
        opt_tabla8_desplaza_con_equipo = $(this).val();
        $(this).val(opt_tabla8_desplaza_con_equipo);
        iniciarIntervaloTotaltabla8();
    });

    $("[name='transporte_pasajero']").on("change", function(){
        opt_tabla8_transporte_pasajero= $(this).val();
        $(this).val(opt_tabla8_transporte_pasajero);
        iniciarIntervaloTotaltabla8();
    });

    $("[name='conduccion']").on("change", function(){
        opt_tabla8_conduccion= $(this).val();
        $(this).val(opt_tabla8_conduccion);
        iniciarIntervaloTotaltabla8();
    });

    //Realiza suma de tabla 8
    function iniciarIntervaloTotaltabla8() {
        clearInterval(intervalo);
        intervalo = setInterval(() => {
            opt_total_tabla8 = Number(opt_tabla8_cambiar_posturas) + Number(opt_tabla8_posicion_cuerpo)+ Number(opt_tabla8_llevar_objetos)
            + Number(opt_tabla8_uso_fino_mano)+ Number(opt_tabla8_uso_mano_brazo)+ Number(opt_tabla8_desplazarse_entorno)+ Number(opt_tabla8_distintos_lugares)
            + Number(opt_tabla8_desplaza_con_equipo)+ Number(opt_tabla8_transporte_pasajero)+ Number(opt_tabla8_conduccion);
            if(!isNaN(opt_total_tabla8)){
                $('#resultado_tabla8').val(redondear(opt_total_tabla8));
            }
        }, 500);
    }
    //Tabla 9 - Relación por categorías para el área ocupacional del cuidado personal
    let opt_tabla9_lavarse = 0;
    let opt_tabla9_cuidado_cuerpo = 0;
    let opt_tabla9_higiene_personal = 0;
    let opt_tabla9_vestirse = 0;
    let opt_tabla9_quitarse_ropa = 0;
    let opt_tabla9_ponerse_calzado = 0;
    let opt_tabla9_comer = 0;
    let opt_tabla9_beber = 0;
    let opt_tabla9_cuidado_salud = 0;
    let opt_tabla9_control_dieta = 0;
    let opt_total_tabla9 = 0;

    $("[name='lavarse']").on("change", function(){
        opt_tabla9_lavarse = $(this).val();
        $(this).val(opt_tabla9_lavarse);
        iniciarIntervaloTotaltabla9();
    });

    $("[name='cuidado_cuerpo']").on("change", function(){
        opt_tabla9_cuidado_cuerpo = $(this).val();
        $(this).val(opt_tabla9_cuidado_cuerpo);
        iniciarIntervaloTotaltabla9();
    });

    $("[name='higiene_personal']").on("change", function(){
        opt_tabla9_higiene_personal = $(this).val();
        $(this).val(opt_tabla9_cuidado_cuerpo);
        iniciarIntervaloTotaltabla9();
    });

    $("[name='vestirse']").on("change", function(){
        opt_tabla9_vestirse = $(this).val();
        $(this).val(opt_tabla9_vestirse);
        iniciarIntervaloTotaltabla9();
    });

    $("[name='quitarse_ropa']").on("change", function(){
        opt_tabla9_quitarse_ropa = $(this).val();
        $(this).val(opt_tabla9_quitarse_ropa);
        iniciarIntervaloTotaltabla9();
    });
    
    $("[name='ponerse_calzado']").on("change", function(){
        opt_tabla9_ponerse_calzado = $(this).val();
        $(this).val(opt_tabla9_ponerse_calzado);
        iniciarIntervaloTotaltabla9();
    });
    
    $("[name='comer']").on("change", function(){
        opt_tabla9_comer = $(this).val();
        $(this).val(opt_tabla9_comer);
        iniciarIntervaloTotaltabla9();
    });

    $("[name='beber']").on("change", function(){
        opt_tabla9_beber = $(this).val();
        $(this).val(opt_tabla9_beber);
        iniciarIntervaloTotaltabla9();
    });
    
    $("[name='cuidado_salud']").on("change", function(){
        opt_tabla9_cuidado_salud= $(this).val();
        $(this).val(opt_tabla9_cuidado_salud);
        iniciarIntervaloTotaltabla9();
    });

    $("[name='control_dieta']").on("change", function(){
        opt_tabla9_control_dieta= $(this).val();
        $(this).val(opt_tabla9_control_dieta);
        iniciarIntervaloTotaltabla9();
    });
    //Realiza suma de tabla 9
    function iniciarIntervaloTotaltabla9() {
        clearInterval(intervalo);
        intervalo = setInterval(() => {
            opt_total_tabla9 = Number(opt_tabla9_lavarse) + Number(opt_tabla9_cuidado_cuerpo)+ Number(opt_tabla9_higiene_personal)
            + Number(opt_tabla9_vestirse)+ Number(opt_tabla9_quitarse_ropa)+ Number(opt_tabla9_ponerse_calzado)+ Number(opt_tabla9_comer)
            + Number(opt_tabla9_beber)+ Number(opt_tabla9_cuidado_salud)+ Number(opt_tabla9_control_dieta);
            if(!isNaN(opt_total_tabla9)){
                $('#resultado_tabla9').val(redondear(opt_total_tabla9));
            }
        }, 500);
    }
    
    //Tabla 10 - Relación de las categorías para el área ocupacional de la vida doméstica
    let opt_tabla10_adquisi_vivir = 0;
    let opt_tabla10_bienes_servicios = 0;
    let opt_tabla10_comprar = 0;
    let opt_tabla10_preparar_comida = 0;
    let opt_tabla10_quehaceres_casa = 0;
    let opt_tabla10_limpieza_vivienda = 0;
    let opt_tabla10_objetos_hogar = 0;
    let opt_tabla10_ayudar_los_demas = 0;
    let opt_tabla10_mante_dispositivos = 0;
    let opt_tabla10_cuidado_animales = 0;
    let opt_total_tabla10 = 0;
    let opt_sumaTotal_20 = 0;

    $("[name='adquisicion_para_vivir']").on("change", function(){
        opt_tabla10_adquisi_vivir = $(this).val();
            $(this).val(opt_tabla10_adquisi_vivir);
            iniciarIntervaloTotaltabla10();
    });

    $("[name='bienes_servicios']").on("change", function(){
        opt_tabla10_bienes_servicios = $(this).val();
            $(this).val(opt_tabla10_bienes_servicios);
            iniciarIntervaloTotaltabla10();
    });

    $("[name='comprar']").on("change", function(){
        opt_tabla10_comprar = $(this).val();
            $(this).val(opt_tabla10_comprar);
            iniciarIntervaloTotaltabla10();
    });

    $("[name='preparar_comida']").on("change", function(){
        opt_tabla10_preparar_comida = $(this).val();
            $(this).val(opt_tabla10_preparar_comida);
            iniciarIntervaloTotaltabla10();
    });

    $("[name='quehaceres_casa']").on("change", function(){
        opt_tabla10_quehaceres_casa = $(this).val();
            $(this).val(opt_tabla10_quehaceres_casa);
            iniciarIntervaloTotaltabla10();
    });

    $("[name='limpieza_vivienda']").on("change", function(){
        opt_tabla10_limpieza_vivienda = $(this).val();
            $(this).val(opt_tabla10_limpieza_vivienda);
            iniciarIntervaloTotaltabla10();
    });

    $("[name='objetos_hogar']").on("change", function(){
        opt_tabla10_objetos_hogar = $(this).val();
            $(this).val(opt_tabla10_objetos_hogar);
            iniciarIntervaloTotaltabla10();
    });

    $("[name='ayudar_los_demas']").on("change", function(){
        opt_tabla10_ayudar_los_demas = $(this).val();
            $(this).val(opt_tabla10_ayudar_los_demas);
            iniciarIntervaloTotaltabla10();
    });

    $("[name='mantenimiento_dispositivos']").on("change", function(){
        opt_tabla10_mante_dispositivos = $(this).val();
            $(this).val(opt_tabla10_mante_dispositivos);
            iniciarIntervaloTotaltabla10();
    });

    $("[name='cuidado_animales']").on("change", function(){
        opt_tabla10_cuidado_animales = $(this).val();
            $(this).val(opt_tabla10_cuidado_animales);
            iniciarIntervaloTotaltabla10();
    });

     //Realiza suma de tabla 10
     function iniciarIntervaloTotaltabla10() {
        clearInterval(intervalo);
        intervalo = setInterval(() => {
            opt_total_tabla10 = Number(opt_tabla10_adquisi_vivir) + Number(opt_tabla10_bienes_servicios)+ Number(opt_tabla10_comprar)
            + Number(opt_tabla10_preparar_comida)+ Number(opt_tabla10_quehaceres_casa)+ Number(opt_tabla10_limpieza_vivienda)+ Number(opt_tabla10_objetos_hogar)
            + Number(opt_tabla10_ayudar_los_demas)+ Number(opt_tabla10_mante_dispositivos)+ Number(opt_tabla10_cuidado_animales);
            if(!isNaN(opt_total_tabla10)){
                $('#resultado_tabla10').val(redondear(opt_total_tabla10));
            }
        }, 500);
    }
    // Suma Total otras areas(20%)
    function iniciarIntervaloOtrasAreas() {
        intervaloOtras = setInterval(() => {
            let opt_sumaTotal_20=opt_total_tabla6 + opt_total_tabla7 + opt_total_tabla8 + opt_total_tabla9 + opt_total_tabla10;
            //console.log(opt_sumaTotal_20);
            if(!isNaN(opt_sumaTotal_20)){
                $('#total_otras').val(redondear(opt_sumaTotal_20));
            }
        }, 500);
    }
    // Suma Total rol laboral y otras areas(50%)
    function iniciarIntervaloLaboralOtras() {
        intervaloLaboral= setInterval(() => {
            let opt_sumaTotal_50= opt_sumaTotal_20 + opt_total_laboral30;
            if(!isNaN(opt_sumaTotal_50)){
                $('#total_rol_areas').val(redondear(opt_sumaTotal_50));
            }
        }, 500);
    }

    //Tabla 12 - Criterios desarrollo neuroevolutivo Niños y Niñas 0 a 3 años
    let opt_tabla12_mantiene_postura = 0;
    let opt_tabla12_activi_espontanea = 0;
    let opt_tabla12_sujeta_cabeza = 0;
    let opt_tabla12_sienta_apoyo = 0;
    let opt_tabla12_sobre_mismo = 0;
    let opt_tabla12_sentado_sin_apoyo = 0;
    let opt_tabla12_tumbado_sentado = 0;
    let opt_tabla12_pie_apoyo = 0;
    let opt_tabla12_pasos_apoyo = 0;
    let opt_tabla12_mantiene_sin_apoyo = 0;
    let opt_tabla12_anda_solo = 0;
    let opt_tabla12_empuja_pelota = 0;
    let opt_tabla12_sorteando_obstaculos = 0;
    let opt_tabla12_succiona = 0;
    let opt_tabla12_fija_mirada = 0;
    let opt_tabla12_trayectoria_objeto = 0;
    let opt_tabla12_sostiene_sonajero = 0;
    let opt_tabla12_hacia_objeto = 0;
    let opt_tabla12_sostiene_objeto = 0;
    let opt_tabla12_abre_cajones = 0;
    let opt_tabla12_bebe_solo = 0;
    let opt_tabla12_quita_prenda = 0;
    let opt_tabla12_espacios_casa = 0;
    let opt_tabla12_imita_trazaso = 0;
    let opt_tabla12_abre_puerta = 0;
    let opt_total_tabla12 = 0;
    
    $("[name='mantiene_postura']").on("change", function(){
        opt_tabla12_mantiene_postura = $(this).val();
        $(this).val(opt_tabla12_mantiene_postura);
        iniciarIntervaloTotaltabla12();
    });

    $("[name='actividad_espontanea']").on("change", function(){
        opt_tabla12_activi_espontanea = $(this).val();
        $(this).val(opt_tabla12_activi_espontanea);
        iniciarIntervaloTotaltabla12();
    });

    $("[name='sujeta_cabeza']").on("change", function(){
        opt_tabla12_sujeta_cabeza= $(this).val();
        $(this).val(opt_tabla12_sujeta_cabeza);
        iniciarIntervaloTotaltabla12();
    });

    $("[name='sienta_apoyo']").on("change", function(){
        opt_tabla12_sienta_apoyo= $(this).val();
        $(this).val(opt_tabla12_sienta_apoyo);
        iniciarIntervaloTotaltabla12();
    });

    $("[name='sobre_mismo']").on("change", function(){
        opt_tabla12_sobre_mismo= $(this).val();
        $(this).val(opt_tabla12_sobre_mismo);
        iniciarIntervaloTotaltabla12();
    });

    $("[name='sentado_sin_apoyo']").on("change", function(){
        opt_tabla12_sentado_sin_apoyo= $(this).val();
        $(this).val(opt_tabla12_sentado_sin_apoyo);
        iniciarIntervaloTotaltabla12();
    });

    $("[name='tumbado_sentado']").on("change", function(){
        opt_tabla12_tumbado_sentado= $(this).val();
        $(this).val(opt_tabla12_tumbado_sentado);
        iniciarIntervaloTotaltabla12();
    });
    
    $("[name='pie_apoyo']").on("change", function(){
        opt_tabla12_pie_apoyo= $(this).val();
        $(this).val(opt_tabla12_pie_apoyo);
        iniciarIntervaloTotaltabla12();
    });

    $("[name='pasos_apoyo']").on("change", function(){
        opt_tabla12_pasos_apoyo= $(this).val();
        $(this).val(opt_tabla12_pasos_apoyo);
        iniciarIntervaloTotaltabla12();
    });

    $("[name='mantiene_sin_apoyo']").on("change", function(){
        opt_tabla12_mantiene_sin_apoyo= $(this).val();
        $(this).val(opt_tabla12_mantiene_sin_apoyo);
        iniciarIntervaloTotaltabla12();
    });

    $("[name='anda_solo']").on("change", function(){
        opt_tabla12_anda_solo= $(this).val();
        $(this).val(opt_tabla12_anda_solo);
        iniciarIntervaloTotaltabla12();
    });

    $("[name='empuja_pelota']").on("change", function(){
        opt_tabla12_empuja_pelota= $(this).val();
        $(this).val(opt_tabla12_empuja_pelota);
        iniciarIntervaloTotaltabla12();
    });

    $("[name='sorteando_obstaculos']").on("change", function(){
        opt_tabla12_sorteando_obstaculos= $(this).val();
        $(this).val(opt_tabla12_sorteando_obstaculos);
        iniciarIntervaloTotaltabla12();
    });

    $("[name='succiona']").on("change", function(){
        opt_tabla12_succiona= $(this).val();
        $(this).val(opt_tabla12_succiona);
        iniciarIntervaloTotaltabla12();
    });

    $("[name='fija_mirada']").on("change", function(){
        opt_tabla12_fija_mirada= $(this).val();
        $(this).val(opt_tabla12_fija_mirada);
        iniciarIntervaloTotaltabla12();
    });

    $("[name='trayectoria_objeto']").on("change", function(){
        opt_tabla12_trayectoria_objeto= $(this).val();
        $(this).val(opt_tabla12_trayectoria_objeto);
        iniciarIntervaloTotaltabla12();
    });
    
    $("[name='sostiene_sonajero']").on("change", function(){
        opt_tabla12_sostiene_sonajero= $(this).val();
        $(this).val(opt_tabla12_sostiene_sonajero);
        iniciarIntervaloTotaltabla12();
    });

    $("[name='hacia_objeto']").on("change", function(){
        opt_tabla12_hacia_objeto= $(this).val();
        $(this).val(opt_tabla12_hacia_objeto);
        iniciarIntervaloTotaltabla12();
    });

    $("[name='sostiene_objeto']").on("change", function(){
        opt_tabla12_sostiene_objeto= $(this).val();
        $(this).val(opt_tabla12_sostiene_objeto);
        iniciarIntervaloTotaltabla12();
    });

    $("[name='abre_cajones']").on("change", function(){
        opt_tabla12_abre_cajones= $(this).val();
        $(this).val(opt_tabla12_abre_cajones);
        iniciarIntervaloTotaltabla12();
    });
    
    $("[name='bebe_solo']").on("change", function(){
        opt_tabla12_bebe_solo= $(this).val();
        $(this).val(opt_tabla12_bebe_solo);
        iniciarIntervaloTotaltabla12();
    });

    $("[name='quita_prenda']").on("change", function(){
        opt_tabla12_quita_prenda= $(this).val();
        $(this).val(opt_tabla12_quita_prenda);
        iniciarIntervaloTotaltabla12();
    });
    
    $("[name='espacios_casa']").on("change", function(){
        opt_tabla12_espacios_casa= $(this).val();
        $(this).val(opt_tabla12_espacios_casa);
        iniciarIntervaloTotaltabla12();
    });

    $("[name='imita_trazaso']").on("change", function(){
        opt_tabla12_imita_trazaso= $(this).val();
        $(this).val(opt_tabla12_imita_trazaso);
        iniciarIntervaloTotaltabla12();
    });

    $("[name='abre_puerta']").on("change", function(){
        opt_tabla12_abre_puerta= $(this).val();
        $(this).val(opt_tabla12_abre_puerta);
        iniciarIntervaloTotaltabla12();
    });

    //Realiza suma de tabla 12
    function iniciarIntervaloTotaltabla12() {
        clearInterval(intervalo);
        intervalo = setInterval(() => {
            opt_total_tabla12 = Number(opt_tabla12_mantiene_postura) + Number(opt_tabla12_activi_espontanea)+ Number(opt_tabla12_sujeta_cabeza)
            + Number(opt_tabla12_sienta_apoyo)+ Number(opt_tabla12_sobre_mismo)+ Number(opt_tabla12_sentado_sin_apoyo)+ Number(opt_tabla12_tumbado_sentado)
            + Number(opt_tabla12_pie_apoyo)+ Number(opt_tabla12_pasos_apoyo)+ Number(opt_tabla12_mantiene_sin_apoyo)+ Number(opt_tabla12_anda_solo)
            + Number(opt_tabla12_empuja_pelota)+ Number(opt_tabla12_sorteando_obstaculos)+ Number(opt_tabla12_succiona)+ Number(opt_tabla12_fija_mirada)
            + Number(opt_tabla12_trayectoria_objeto)+ Number(opt_tabla12_sostiene_sonajero)+ Number(opt_tabla12_hacia_objeto)+ Number(opt_tabla12_sostiene_objeto)+ Number(opt_tabla12_abre_cajones)
            + Number(opt_tabla12_bebe_solo)+ Number(opt_tabla12_quita_prenda)+ Number(opt_tabla12_espacios_casa)+ Number(opt_tabla12_imita_trazaso)+ Number(opt_tabla12_abre_puerta);
            if(!isNaN(opt_total_tabla12)){
                $('#total_tabla12').val(redondear(opt_total_tabla12));
            }
        }, 500);
    }

    //Tabla 13 - Valoración de los roles ocupacionales de juego
    let opt_tabla13_ocupacionales_juego = 0;
    let opt_total_tabla13 = 0;
    $("[name='roles_ocupacionales_juego']").on("change", function(){
        opt_tabla13_ocupacionales_juego = $(this).val();
        $(this).val(opt_tabla13_ocupacionales_juego);
        iniciarIntervaloTotaltabla13();
    });
    //Realiza suma de tabla 13
    function iniciarIntervaloTotaltabla13() {
        clearInterval(intervalo);
        intervalo = setInterval(() => {
            opt_total_tabla13 = opt_tabla13_ocupacionales_juego;
            if(!isNaN(opt_total_tabla13)){
                $('#total_tabla13').val(redondear(opt_total_tabla13));
            }
        }, 500);
    }
    //Tabla 14 - Valoración de los roles ocupacional relacionado
    let opt_tabla14_ocupacionales_adultos = 0;
    let opt_total_tabla14 = 0;
    $("[name='roles_ocupacionales_adultos']").on("change", function(){
        opt_tabla14_ocupacionales_adultos = $(this).val();
        $(this).val(opt_tabla14_ocupacionales_adultos);
        iniciarIntervaloTotaltabla14();
    });
    //Realiza suma de tabla 14
    function iniciarIntervaloTotaltabla14() {
        clearInterval(intervalo);
        intervalo = setInterval(() => {
            opt_total_tabla14 = opt_tabla14_ocupacionales_adultos;
            if(!isNaN(opt_total_tabla14)){
                $('#total_tabla14').val(redondear(opt_total_tabla14));
            }
        }, 500);
    }

    //Cargar opciones de selectores Libro II Calificación de las discapacidades (20%) 
    var options = ['0.1', '0.2', '0.3'];
    var options2 = ['0.1', '0.2'];
    var select = $('[id^="conducta_"],[id^="comunicacion_"],[id^="cuidado_personal_"],[id^="lomocion_"],[id^="disposicion_"],[id^="destreza_"]');
    var select2 = $('[id^="situacion_"]');

    function appendOptions(selectElement, optionsArray) {
        optionsArray.forEach(function(value) {
            selectElement.append($('<option>').text(value).attr('value', value));
        });
    }

    appendOptions(select, options);
    appendOptions(select2,options2);
  
    //Suma de valores conducta
    let opt_conducta = [];
    let opt_total_conducta = 0;

    for(let i = 10; i <= 19; i++) {
        opt_conducta[i] = 0;
        
        $("[name='conducta_" + i + "']").on("change", function(){
            opt_conducta[i] = $(this).val();
            $(this).val(opt_conducta[i]);
            iniciarIntervaloTotalConducta();
            iniciarIntervaloDiscapacida();
        });
    }

    // Realiza suma de conducta
    function iniciarIntervaloTotalConducta() {
        clearInterval(intervalo);
        intervalo = setInterval(() => {
            opt_total_conducta = opt_conducta.reduce((total, opt) => total + Number(opt), 0);
            if(!isNaN(opt_total_conducta)){
                $('#total_conducta').val(redondear(opt_total_conducta));
            }
        }, 500);
    }

    //Suma de valores Comunicación
    let opt_comunicacion = [];
    let opt_total_comunicacion = 0;

    for(let i = 20; i <= 29; i++) {
        opt_comunicacion[i] = 0;
        
        $("[name='comunicacion_" + i + "']").on("change", function(){
            opt_comunicacion[i] = $(this).val();
            $(this).val(opt_comunicacion[i]);
            iniciarIntervaloTotalcomunicacion();
            iniciarIntervaloDiscapacida();
        });
    }

    // Realiza suma de comunicacion
    function iniciarIntervaloTotalcomunicacion() {
        clearInterval(intervalo);
        intervalo = setInterval(() => {
            opt_total_comunicacion = opt_comunicacion.reduce((total, opt) => total + Number(opt), 0);
            if(!isNaN(opt_total_comunicacion)){
                $('#total_comunicacion').val(redondear(opt_total_comunicacion));
            }
        }, 500);
    }

    //Suma de valores Cuidado personal
    let opt_cuidado_personal = [];
    let opt_total_cuidado_personal = 0;

    for(let i = 30; i <= 39; i++) {
        opt_cuidado_personal[i] = 0;
        
        $("[name='cuidado_personal_" + i + "']").on("change", function(){
            opt_cuidado_personal[i] = $(this).val();
            $(this).val(opt_cuidado_personal[i]);
            iniciarIntervaloTotalcuidado_personal();
            iniciarIntervaloDiscapacida();
        });
    }

    // Realiza suma de cuidado_personal
    function iniciarIntervaloTotalcuidado_personal() {
        clearInterval(intervalo);
        intervalo = setInterval(() => {
            opt_total_cuidado_personal = opt_cuidado_personal.reduce((total, opt) => total + Number(opt), 0);
            if(!isNaN(opt_total_cuidado_personal)){
                $('#total_cuidado_personal').val(redondear(opt_total_cuidado_personal));
            }
        }, 500);
    }

     //Suma de valores Locomoción
     let opt_lomocion= [];
     let opt_total_lomocion = 0;
 
     for(let i = 40; i <= 49; i++) {
        opt_lomocion[i] = 0;
         
         $("[name='lomocion_" + i + "']").on("change", function(){
            opt_lomocion[i] = $(this).val();
             $(this).val(opt_lomocion[i]);
             iniciarIntervaloTotallomocion ();
             iniciarIntervaloDiscapacida();
         });
     }
 
     // Realiza suma de lomocion
     function iniciarIntervaloTotallomocion() {
         clearInterval(intervalo);
         intervalo = setInterval(() => {
                opt_total_lomocion = opt_lomocion.reduce((total, opt) => total + Number(opt), 0);
             if(!isNaN(opt_total_lomocion)){
                 $('#total_lomocion').val(redondear(opt_total_lomocion));
             }
         }, 500);
     }

     //Suma de valores Disposición del cuerpo
     let opt_disposicion= [];
     let opt_total_disposicion = 0;
 
     for(let i = 50; i <= 59; i++) {
        opt_disposicion[i] = 0;
         $("[name='disposicion_" + i + "']").on("change", function(){
            opt_disposicion[i] = $(this).val();
             $(this).val(opt_disposicion[i]);
             iniciarIntervaloTotaldisposicion();
             iniciarIntervaloDiscapacida();
         });
     }
 
     // Realiza suma de disposicion
     function iniciarIntervaloTotaldisposicion() {
         clearInterval(intervalo);
         intervalo = setInterval(() => {
                opt_total_disposicion = opt_disposicion.reduce((total, opt) => total + Number(opt), 0);
             if(!isNaN(opt_total_disposicion)){
                 $('#total_disposicion').val(redondear(opt_total_disposicion));
             }
         }, 500);
     }

      //Suma de valores Destreza
      let opt_destreza= [];
      let opt_total_destreza = 0;
  
      for(let i = 60; i <= 69; i++) {
         opt_destreza[i] = 0;
          $("[name='destreza_" + i + "']").on("change", function(){
             opt_destreza[i] = $(this).val();
              $(this).val(opt_destreza[i]);
              iniciarIntervaloTotaldestreza();
              iniciarIntervaloDiscapacida();
          });
      }
  
      // Realiza suma de destreza
      function iniciarIntervaloTotaldestreza() {
          clearInterval(intervalo);
          intervalo = setInterval(() => {
                opt_total_destreza = opt_destreza.reduce((total, opt) => total + Number(opt), 0);
              if(!isNaN(opt_total_destreza)){
                  $('#total_destreza').val(redondear(opt_total_destreza));
              }
          }, 500);
      }

    //Suma de valores Situación
    let opt_situacion= [];
    let opt_total_situacion = 0;

    for(let i = 70; i <= 78; i++) {
        opt_situacion[i] = 0;
        
        $("[name='situacion_" + i + "']").on("change", function(){
            opt_situacion[i] = $(this).val();
            $(this).val(opt_situacion[i]);
            iniciarIntervaloTotalsituacion();
            iniciarIntervaloDiscapacida();
        });
    }

    // Realiza suma de situacion
    function iniciarIntervaloTotalsituacion() {
        clearInterval(intervalo);
        intervalo = setInterval(() => {
            opt_total_situacion = opt_situacion.reduce((total, opt) => total + Number(opt), 0);
            if(!isNaN(opt_total_situacion)){
                $('#total_situacion').val(redondear(opt_total_situacion));
            }
        }, 500);
    }

    //Total Discapacidades
    var opt_sumaTotal_disca = 0;
    function iniciarIntervaloDiscapacida() {
        intervaloDisca= setInterval(() => {
            opt_sumaTotal_disca= opt_total_conducta + opt_total_comunicacion + opt_total_cuidado_personal + opt_total_lomocion + opt_total_disposicion + opt_total_destreza + opt_total_situacion;
            if(!isNaN(opt_sumaTotal_disca)){
                $('#total_discapacidades').val(redondear(opt_sumaTotal_disca));
            }
        }, 500);
    }
   
    // total_minusvalia
    let opt_orientacion = 0;
    let opt_indepen_fisica = 0;
    let opt_desplazamiento = 0;
    let opt_ocupacional = 0;
    let opt_social = 0;
    let opt_economica = 0;
    let opt_cronologica = 0;
    let opt_sumaTotal_valia = 0;

    $("[name='orientacion']").on("change", function(){
        opt_orientacion = Number($(this).val());
        $(this).val(opt_orientacion);
        iniciarIntervaloMinusvalia();
    });

    $("[name='indepen_fisica']").on("change", function(){
        opt_indepen_fisica = Number($(this).val());
        $(this).val(opt_indepen_fisica);
        iniciarIntervaloMinusvalia();
    });

    $("[name='desplazamiento']").on("change", function(){
        opt_desplazamiento = Number($(this).val());
        $(this).val(opt_desplazamiento);
        iniciarIntervaloMinusvalia();
    });

    $("[name='ocupacional']").on("change", function(){
        opt_ocupacional = Number($(this).val());
        $(this).val(opt_ocupacional);
        iniciarIntervaloMinusvalia();
    });

    $("[name='social']").on("change", function(){
        opt_social = Number($(this).val());
        $(this).val(opt_social);
        iniciarIntervaloMinusvalia();
    });

    $("[name='economica']").on("change", function(){
        opt_economica = Number($(this).val());
        $(this).val(opt_economica);
        iniciarIntervaloMinusvalia();
    });

    $("[name='cronologica']").on("change", function(){
        opt_cronologica = Number($(this).val());
        $(this).val(opt_cronologica);
        iniciarIntervaloMinusvalia();
    });

    function iniciarIntervaloMinusvalia() {
        clearInterval(intervalo);
        intervalo = setInterval(() => {
            opt_sumaTotal_valia = opt_orientacion + opt_indepen_fisica + opt_desplazamiento + opt_ocupacional
            + opt_social + opt_economica + opt_cronologica;
            if (!isNaN(opt_sumaTotal_valia)){
                $('#total_minusvalia').val(redondear(opt_sumaTotal_valia));
            }
        }, 500);
    }


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
            //console.log(banderaDxPrincipal);
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
            //console.log(banderaDxPrincipal);
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

