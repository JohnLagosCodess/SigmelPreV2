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




});