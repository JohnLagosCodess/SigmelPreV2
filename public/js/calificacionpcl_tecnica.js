$(document).ready(function(){

    var idRol = $("#id_rol").val();
    //localStorage.clear();
    localStorage.removeItem('filas');
    localStorage.removeItem('checkboxDxPrincipalNew');
    // Inicializacion del select2 de listados  Módulo Calificacion Tecnica PCL
    $(".origen_firme").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".modalidad_calificacion").select2({
        placeholder:"Seleccione una opción",
        allowClear:false,
        width: '100%'
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

    $(".poblacion_califi2").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });
        
    $(".tipo_destinatario_principal").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".nombre_destinatariopri").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".cual").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".reviso").select2({
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

    $(".tipo_enfermedad").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".dominancia").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    // llenado de selectores
    var token = $('input[name=_token]').val();

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

    //Listado de Modalidad calificacion PCL
    let datos_lista_modalidad_calificacion = {
        '_token': token,
        'parametro':"lista_modalidad_calificacion_pcl"
    };

    $.ajax({
        type:'POST',
        url:'/selectoresModuloCalificacionPCL',
        data: datos_lista_modalidad_calificacion,
        success:function(data){
            //console.log(data);
            let NombremodalidadCalificacionPcl = $('select[name=modalidad_calificacion]').val();
            let modalidadCalificacionPcl = Object.keys(data);
            for (let i = 0; i < modalidadCalificacionPcl.length; i++) {
                if (data[modalidadCalificacionPcl[i]]['Id_Parametro'] != NombremodalidadCalificacionPcl) {                    
                    $('#modalidad_calificacion').append('<option value="'+data[modalidadCalificacionPcl[i]]['Id_Parametro']+'">'+data[modalidadCalificacionPcl[i]]['Nombre_parametro']+'</option>');
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

    // Listado tipo destinatario

    let datos_lista_tipo_destinatario = {
        '_token': token,
        'parametro' : "listado_destinatarios"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresCalificacionTecnicaPCL',
        data: datos_lista_tipo_destinatario,
        success:function(data) {
            $('#tipo_destinatario_principal').append('<option value="" selected>Seleccione</option>');
            //let IdCalifi_contro = $('select[name=tipo_destinatario_principal]').val();
            let partecontro = Object.keys(data);
            for (let i = 0; i < partecontro.length; i++) {
                if (data[partecontro[i]]['Id_solicitante'] == $('#db_tipo_destinatario_principal').val()) {  
                    $('#tipo_destinatario_principal').append('<option value="'+data[partecontro[i]]["Id_solicitante"]+'" selected>'+data[partecontro[i]]["Solicitante"]+'</option>');
                }else{                    
                    $('#tipo_destinatario_principal').append('<option value="'+data[partecontro[i]]["Id_solicitante"]+'">'+data[partecontro[i]]["Solicitante"]+'</option>');
                }
            }
        }        
    });

    // Listado tipo nombre destinatario

    if($('select[name=nombre_destinatariopri]').val() !== ''){
        $('#nombre_destinatariopri').prop('disabled', false);
        let id_solicitante = $('#db_tipo_destinatario_principal').val();
        let datos_listado_nombre_solicitante = {
            '_token': token,
            'parametro' : "nombre_destinatariopri",
            'id_solicitante': id_solicitante
        };
        
        $.ajax({
            type:'POST',
            url:'/selectoresCalificacionTecnicaPCL',
            data: datos_listado_nombre_solicitante,
            success:function(data) {
                let nombre_solicitanteEdicion = $('select[name=nombre_destinatariopri]').val();                
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    if (data[claves[i]]['Id_Nombre_solicitante'] == $('#db_nombre_destinatariopri').val()) {  
                        $('#nombre_destinatariopri').append('<option value="'+data[claves[i]]["Id_Nombre_solicitante"]+'" selected>'+data[claves[i]]["Nombre_solicitante"]+'</option>');
                    }else{                    
                        $('#nombre_destinatariopri').append('<option value="'+data[claves[i]]["Id_Nombre_solicitante"]+'">'+data[claves[i]]["Nombre_solicitante"]+'</option>');
                    }
                }
            }
        });
    }

    $('#tipo_destinatario_principal').change(function(){
        $('#nombre_destinatariopri').empty();
        $('#nombre_destinatariopri').prop('disabled', false);
        let id_solicitante = $('#tipo_destinatario_principal').val();
        let datos_listado_nombre_destinatariopri = {
            '_token': token,
            'parametro' : "nombre_destinatariopri",
            'id_solicitante': id_solicitante
        };
        $.ajax({
            type:'POST',
            url:'/selectoresCalificacionTecnicaPCL',
            data: datos_listado_nombre_destinatariopri,
            success:function(data) {
                $('#nombre_destinatariopri').append('<option value="" selected>Seleccione</option>');
                //let IdCalifi_contro = $('select[name=nombre_destinatariopri]').val();
                let partecontro = Object.keys(data);
                for (let i = 0; i < partecontro.length; i++) {
                    //console.log(data[partecontro[i]]['Id_Nombre_solicitante']);
                    if (data[partecontro[i]]['Id_Nombre_solicitante'] == $('#db_nombre_destinatariopri').val()) {  
                        $('#nombre_destinatariopri').append('<option value="'+data[partecontro[i]]["Id_Nombre_solicitante"]+'" selected>'+data[partecontro[i]]["Nombre_solicitante"]+'</option>');
                    }else{                    
                        $('#nombre_destinatariopri').append('<option value="'+data[partecontro[i]]["Id_Nombre_solicitante"]+'">'+data[partecontro[i]]["Nombre_solicitante"]+'</option>');
                    }
                }
            }
        });
    });

    //Listado juntas regional Correspondencia
     let datos_lista_regional_junta = {
        '_token': token,
        'parametro':"lista_regional_junta"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresCalificacionTecnicaPCL',
        data: datos_lista_regional_junta,
        success:function(data) {
            //console.log(data);
            let IdJunta = $('select[name=cual]').val();
            let primercali = Object.keys(data);
            for (let i = 0; i < primercali.length; i++) {
                if (data[primercali[i]]['Id_juntaR'] != IdJunta) {  
                    $('#cual').append('<option value="'+data[primercali[i]]["Ciudad_Junta"]+'">'+data[primercali[i]]["Ciudad_Junta"]+'</option>');
                }
            }
        }
    });

    // Listado Reviso (Lideres del grupos de trabajo segun proceso PCL)
    var idProcesoLider = $("#Id_Proceso_decreto").val();
    let datos_lista_reviso = {
        '_token': token,
        'parametro':"lista_reviso",
        'idProcesoLider':idProcesoLider
    };

    $.ajax({
        type:'POST',
        url:'/selectoresCalificacionTecnicaPCL',
        data: datos_lista_reviso,
        success:function(data){
            //console.log(data);
            let NombreReviso = $('select[name=reviso]').val();
            let nombreRevisoPcl = Object.keys(data);
            for (let i = 0; i < nombreRevisoPcl.length; i++) {
                if (data[nombreRevisoPcl[i]]['name'] != NombreReviso) {                    
                    $('#reviso').append('<option value="'+data[nombreRevisoPcl[i]]['name']+'">'+data[nombreRevisoPcl[i]]['name']+'</option>');
                }
            }
            $("#reviso").prop("selectedIndex", 1);
        }
    });

    // Listado Tipo de evento Concepto final del Dictamen Pericial

    let datos_lista_tipo_evento = {
        '_token': token,
        'parametro':"lista_tipo_evento"
    };

    $.ajax({
        type:'POST',
        url:'/selectoresCalificacionTecnicaPCL',
        data: datos_lista_tipo_evento,
        success:function(data){
            //console.log(data);
            let NombreTipoEvento = $('select[name=tipo_evento]').val();
            let tipoEventocalifPcl = Object.keys(data);
            for (let i = 0; i < tipoEventocalifPcl.length; i++) {
                if (data[tipoEventocalifPcl[i]]['Id_Evento'] != NombreTipoEvento) {                    
                    $('#tipo_evento').append('<option value="'+data[tipoEventocalifPcl[i]]['Id_Evento']+'">'+data[tipoEventocalifPcl[i]]['Nombre_evento']+'</option>');
                }
            }
        }
    });

    //Listado Origen Concepto final del Dictamen Pericial
    let datos_lista_Origen = {
        '_token': token,
        'parametro':"lista_origen"  
    };

    $.ajax({
        type:'POST',
        url:'/selectoresCalificacionTecnicaPCL',
        data: datos_lista_Origen,
        success:function(data){
            //console.log(data);
            let NombreOrigen = $('select[name=tipo_origen]').val();
            let origencalifiPcl = Object.keys(data);
            for (let i = 0; i < origencalifiPcl.length; i++) {
                if (data[origencalifiPcl[i]]['Id_Parametro'] != NombreOrigen) {                    
                    $('#tipo_origen').append('<option value="'+data[origencalifiPcl[i]]['Id_Parametro']+'">'+data[origencalifiPcl[i]]['Nombre_parametro']+'</option>');
                }
            }
        }
    });

    //Listado Tipo de enfermedad Concepto final del Dictamen Pericial
    let datos_lista_Tipo_enfermedad = {
        '_token': token,
        'parametro':"lista_Tipo_enfermedad"
    };

    $.ajax({
        type:'POST',
        url:'/selectoresCalificacionTecnicaPCL',
        data: datos_lista_Tipo_enfermedad,
        success:function(data){
            //console.log(data);
            let TipoEnfermedad = $('select[name=tipo_enfermedad]').val();
            let tipoEnfermedadCalifiPCl = Object.keys(data);
            for (let i = 0; i < tipoEnfermedadCalifiPCl.length; i++) {
                if (data[tipoEnfermedadCalifiPCl[i]]['Id_Parametro'] != TipoEnfermedad) {                    
                    $('#tipo_enfermedad').append('<option value="'+data[tipoEnfermedadCalifiPCl[i]]['Id_Parametro']+'">'+data[tipoEnfermedadCalifiPCl[i]]['Nombre_parametro']+'</option>');
                }
            }
        }
    });

    // listado dominancia
    let datos_listado_dominancia = {
        '_token': token,
        'parametro':"lista_dominancia"
    }

    $.ajax({
        type:'POST',
        url:'/selectoresCalificacionTecnicaPCL',
        data: datos_listado_dominancia,
        success:function(data){
            let bd_id_dominancia = $('#bd_id_dominancia').val();
            $('#dominancia').append('<option value="">Seleccione</option>');
            let lista_dominancia = Object.keys(data);
            for (let i = 0; i < lista_dominancia.length; i++) {
                if (data[lista_dominancia[i]]['Id_Dominancia'] == bd_id_dominancia) {                    
                    $('#dominancia').append('<option value="'+data[lista_dominancia[i]]['Id_Dominancia']+'" selected>'+data[lista_dominancia[i]]['Nombre_dominancia']+'</option>');
                }else{
                    $('#dominancia').append('<option value="'+data[lista_dominancia[i]]['Id_Dominancia']+'">'+data[lista_dominancia[i]]['Nombre_dominancia']+'</option>');
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
        opt_cobertura = parseInt($('#origen_cobertura').val());
        opt_decreto = parseInt($('#decreto_califi').val());        
        $("#origen_firme").val(opt_origen);
        $("#origen_cobertura").val(opt_cobertura);
        $("#decreto_califi").val(opt_decreto);
        iniciarIntervalo_decreto();            
    });       
    
    $("#origen_cobertura").change(function(){
        opt_cobertura = parseInt($(this).val());
        opt_origen = parseInt($('#origen_firme').val());
        opt_decreto = parseInt($('#decreto_califi').val());
        $("#origen_cobertura").val(opt_cobertura);
        $("#origen_firme").val(opt_origen);
        $("#decreto_califi").val(opt_decreto);
        iniciarIntervalo_decreto();
    });
   
    $("#decreto_califi").change(function(){
        opt_decreto = parseInt($(this).val());
        opt_cobertura = parseInt($('#origen_cobertura').val());
        opt_origen = parseInt($('#origen_firme').val()) ;
        $("#origen_cobertura").val(opt_cobertura);
        $("#origen_firme").val(opt_origen);
        $("#decreto_califi").val(opt_decreto);
        iniciarIntervalo_decreto();
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
                $('#botonNoDecrecto').addClass('d-none');
                $("#descripcion_enfermedad").attr("required", "required");
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

            } else if(opt_origen == 49 && opt_cobertura == 51){
                // Deslizar hacia arriba (ocultar) los elementos
                $("#descripcion_enfermedad").removeAttr("required");                
                elementosDeslizar.forEach(elemento => {
                    $(elemento).slideUp(tiempoDeslizamiento);
                });
                elementosDeslizar2.forEach(elemento => {
                    $(elemento).slideUp(tiempoDeslizamiento2);
                });
                elementosDeslizar3.forEach(elemento => {
                    $(elemento).slideUp(tiempoDeslizamiento3);
                });                   
            }else if(opt_origen == 48 && opt_cobertura == 51){
                $("#descripcion_enfermedad").removeAttr("required");                 
                $('#botonNoDecrecto').removeClass('d-none');
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
                return;
            }else if(opt_origen == 49 && opt_cobertura == 50){
                $("#descripcion_enfermedad").removeAttr("required");                  
                $('#botonNoDecrecto').removeClass('d-none');
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
                return;        
            }
        }, 500);
    }    

    /* VALIDACIÓN MOSTRAR ITEMS LABORAL DE ACUERDO AL ROL  */ 
    var opt_tipo_laboral;
    var opt_tipo_poblacion;
    if ($('input[type="radio"][name="tipo_laboral"]').is(":checked")) {  
        opt_tipo_laboral = $('#laboral_actual').val(); 
        iniciarIntervalo_laboral();
        $('input[type="radio"][name="rol_ocupa"]').prop("disabled", true);         
    }

    if ($('input[type="radio"][name="rol_ocupa"]').is(":checked")) {  
        opt_tipo_laboral = $('#rol_ocupacional').val(); 
        iniciarIntervalo_laboral();        
        $('input[type="radio"][name="tipo_laboral"]').prop("disabled", true);
        var opt_tipo_poblacion = $('#poblacion_califi').val()
        if (opt_tipo_poblacion == 75) {
            iniciarIntervalo_poblacion();
            $('#columna_row1_tabla_12').css('display', 'block');
        } else if(opt_tipo_poblacion == 76){
            iniciarIntervalo_poblacion();
            $('#columna_row1_tabla_13').css('display', 'block');
        } else if(opt_tipo_poblacion == 77){
            iniciarIntervalo_poblacion();
            $('#columna_row1_tabla_14').css('display', 'block');
        }

    }

    $("#laboral_actual").change(function(){
        opt_tipo_laboral = $('#laboral_actual').val();
        $("#laboral_actual").val(opt_tipo_laboral);
        iniciarIntervalo_laboral();
        iniciarIntervaloOtrasAreas();
        iniciarIntervaloLaboralOtras();          
        $('input[type="radio"][name="rol_ocupa"]').prop("checked", false);  
        $('#columna_row1_tabla_12').addClass('d-none');
        $('#columna_row1_tabla_13').addClass('d-none');
        $('#columna_row1_tabla_14').addClass('d-none');
      
    }); 
    $("#rol_ocupacional").change(function(){
        opt_tipo_laboral = $('#rol_ocupacional').val();
        $("#rol_ocupacional").val(opt_tipo_laboral);
        iniciarIntervalo_laboral();
        iniciarIntervalo_poblacion();
        $('input[type="radio"][name="tipo_laboral"]').prop("checked", false);
        $('#columna_row1_tabla_12').removeClass('d-none');
        $('#columna_row1_tabla_13').removeClass('d-none');
        $('#columna_row1_tabla_14').removeClass('d-none');

       
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
    var opt_tabla_1 = $("[id^='restricciones_rol_']").is(":checked") ? $("[id^='restricciones_rol_']:checked").val() : 0; 
    var opt_tabla_2 = $("[id^='autosuficiencia_']").is(":checked") ? $("[id^='autosuficiencia_']:checked").val() : 0;
    var opt_tabla_3 = $("[name='edad_cronologica']").is(":checked") ? $("[name='edad_cronologica']:checked").val() : 0;
    var opt_total_laboral30 =  0;

    // Validacion de la edad cronologica del afiliado
    if ($('#Edad_Menor').length > 0) {
        let edades_cronologicas = $('#Edad_Menor').val();
        if (edades_cronologicas) {
            if (edades_cronologicas == opt_tabla_3) {
                $('#div_alerta_sirena').addClass('d-none');
            } else {
                $('#div_alerta_sirena').removeClass('d-none');            
            }
        }
    } else if ($('#Edad_Mayor').length > 0){
        let edades_cronologicas = $('#Edad_Mayor').val();        
        if (edades_cronologicas) {
            if (edades_cronologicas == opt_tabla_3) {
                $('#div_alerta_sirena').addClass('d-none');
            } else {
                $('#div_alerta_sirena').removeClass('d-none');            
            }
        }
    }
    
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
        //clearInterval(intervalo);
        intervalo = setInterval(() => {
            opt_total_laboral30 = Number(opt_tabla_1) + Number(opt_tabla_2)+  Number(opt_tabla_3);
            if(!isNaN(opt_total_laboral30)){
                $('#resultado_rol_laboral_30').val(redondear(opt_total_laboral30)); //Coloca resultado Rol Laboral (30%)
            }
        }, 500);
    }
    //Tabla 6 - Aprendizaje y aplicación del conocimiento
      
    var opt_tabla6_mirar = $("[id^='mirar_']").is(":checked") ? $("[id^='mirar_']:checked").val() : 0;    
    var opt_tabla6_escuchar = $("[id^='escuchar_']").is(":checked") ? $("[id^='escuchar_']:checked").val() : 0;        
    var opt_tabla6_aprender = $("[id^='aprender_']").is(":checked") ? $("[id^='aprender_']:checked").val() : 0;
    var opt_tabla6_calcular = $("[id^='calcular_']").is(":checked") ? $("[id^='calcular_']:checked").val() : 0;
    var opt_tabla6_pensar = $("[id^='pensar_']").is(":checked") ? $("[id^='pensar_']:checked").val() : 0;
    var opt_tabla6_leer = $("[id^='leer_']").is(":checked") ? $("[id^='leer_']:checked").val() : 0;
    var opt_tabla6_escribir = $("[id^='escribir_']").is(":checked") ? $("[id^='escribir_']:checked").val() : 0;
    var opt_tabla6_matematicos = $("[id^='matematicos_']").is(":checked") ? $("[id^='matematicos_']:checked").val() : 0;
    var opt_tabla6_decisiones = $("[id^='decisiones_']").is(":checked") ? $("[id^='decisiones_']:checked").val() : 0;
    var opt_tabla6_tareas_simples = $("[id^='tareas_simples_']").is(":checked") ? $("[id^='tareas_simples_']:checked").val() : 0;
    var opt_total_tabla6 = $("[id='resultado_tabla6']").val() || 0;

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
    var opt_tabla7_comuni_verbal = $("[id^='comunicarse_mensaje_']").is(":checked") ? $("[id^='comunicarse_mensaje_']:checked").val() : 0;
    var opt_tabla7_no_comuni_verbal = $("[id^='no_comunicarse_mensaje_']").is(":checked") ? $("[id^='no_comunicarse_mensaje_']:checked").val() : 0;
    var opt_tabla7_comuni_signos = $("[id^='comunicarse_signos_']").is(":checked") ? $("[id^='comunicarse_signos_']:checked").val() : 0;
    var opt_tabla7_comuni_escrito = $("[id^='comunicarse_escrito_']").is(":checked") ? $("[id^='comunicarse_escrito_']:checked").val() : 0;
    var opt_tabla7_habla = $("[id^='habla_']").is(":checked") ? $("[id^='habla_']:checked").val() : 0;
    var opt_tabla7_no_verbales = $("[id^='no_verbales_']").is(":checked") ? $("[id^='no_verbales_']:checked").val() : 0;
    var opt_tabla7_mensajes_escritos = $("[id^='mensajes_escritos_']").is(":checked") ? $("[id^='mensajes_escritos_']:checked").val() : 0;
    var opt_tabla7_sostener_conversa = $("[id^='sostener_conversa_']").is(":checked") ? $("[id^='sostener_conversa_']:checked").val() : 0;
    var opt_tabla7_iniciar_discusiones = $("[id^='iniciar_discusiones_']").is(":checked") ? $("[id^='iniciar_discusiones_']:checked").val() : 0;
    var opt_tabla7_utiliza_dispositivos = $("[id^='utiliza_dispositivos_']").is(":checked") ? $("[id^='utiliza_dispositivos_']:checked").val() : 0;
    var opt_total_tabla7 = $("[id='resultado_tabla7']").val() || 0;
    
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
    var opt_tabla8_cambiar_posturas = $("[id^='cambiar_posturas_']").is(":checked") ? $("[id^='cambiar_posturas_']:checked").val() : 0;
    var opt_tabla8_posicion_cuerpos = $("[id^='posicion_cuerpo_']").is(":checked") ? $("[id^='posicion_cuerpo_']:checked").val() : 0;
    var opt_tabla8_llevar_objetos = $("[id^='llevar_objetos_']").is(":checked") ? $("[id^='llevar_objetos_']:checked").val() : 0;
    var opt_tabla8_uso_fino_mano = $("[id^='uso_fino_mano_']").is(":checked") ? $("[id^='uso_fino_mano_']:checked").val() : 0;
    var opt_tabla8_uso_mano_brazo = $("[id^='uso_mano_brazo_']").is(":checked") ? $("[id^='uso_mano_brazo_']:checked").val() : 0;
    var opt_tabla8_desplazarse_entorno = $("[id^='desplazarse_entorno_']").is(":checked") ? $("[id^='desplazarse_entorno_']:checked").val() : 0;
    var opt_tabla8_distintos_lugares = $("[id^='distintos_lugares_']").is(":checked") ? $("[id^='distintos_lugares_']:checked").val() : 0;
    var opt_tabla8_desplaza_con_equipo = $("[id^='desplazarse_con_equipo_']").is(":checked") ? $("[id^='desplazarse_con_equipo_']:checked").val() : 0;
    var opt_tabla8_transporte_pasajero = $("[id^='transporte_pasajero_']").is(":checked") ? $("[id^='transporte_pasajero_']:checked").val() : 0;
    var  opt_tabla8_conduccion = $("[id^='conduccion_']").is(":checked") ? $("[id^='conduccion_']:checked").val() : 0;
    var opt_total_tabla8 = $("[id='resultado_tabla8']").val() || 0;
    
    $("[name='cambiar_posturas']").on("change", function(){
        opt_tabla8_cambiar_posturas = $(this).val();
        $(this).val(opt_tabla8_cambiar_posturas);
        iniciarIntervaloTotaltabla8();
    });

    $("[name='posicion_cuerpo']").on("change", function(){
        opt_tabla8_posicion_cuerpos = $(this).val();
        $(this).val(opt_tabla8_posicion_cuerpos);
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
            opt_total_tabla8 = Number(opt_tabla8_cambiar_posturas) + Number(opt_tabla8_posicion_cuerpos)+ Number(opt_tabla8_llevar_objetos)
            + Number(opt_tabla8_uso_fino_mano)+ Number(opt_tabla8_uso_mano_brazo)+ Number(opt_tabla8_desplazarse_entorno)+ Number(opt_tabla8_distintos_lugares)
            + Number(opt_tabla8_desplaza_con_equipo)+ Number(opt_tabla8_transporte_pasajero)+ Number(opt_tabla8_conduccion);
            if(!isNaN(opt_total_tabla8)){
                $('#resultado_tabla8').val(redondear(opt_total_tabla8));
            }
        }, 500);
    }
    //Tabla 9 - Relación por categorías para el área ocupacional del cuidado personal
    var opt_tabla9_lavarse = $("[id^='lavarse_']").is(":checked") ? $("[id^='lavarse_']:checked").val() : 0;
    var opt_tabla9_cuidado_cuerpo = $("[id^='cuidado_cuerpo_']").is(":checked") ? $("[id^='cuidado_cuerpo_']:checked").val() : 0;
    var opt_tabla9_higiene_personal = $("[id^='higiene_personal_']").is(":checked") ? $("[id^='higiene_personal_']:checked").val() : 0;
    var opt_tabla9_vestirse = $("[id^='vestirse_']").is(":checked") ? $("[id^='vestirse_']:checked").val() : 0;
    var opt_tabla9_quitarse_ropa = $("[id^='quitarse_ropa_']").is(":checked") ? $("[id^='quitarse_ropa_']:checked").val() : 0;
    var opt_tabla9_ponerse_calzado = $("[id^='ponerse_calzado_']").is(":checked") ? $("[id^='ponerse_calzado_']:checked").val() : 0;
    var opt_tabla9_comer = $("[id^='comer_']").is(":checked") ? $("[id^='comer_']:checked").val() : 0;
    var opt_tabla9_beber = $("[id^='beber_']").is(":checked") ? $("[id^='beber_']:checked").val() : 0;
    var opt_tabla9_cuidado_salud = $("[id^='cuidado_salud_']").is(":checked") ? $("[id^='cuidado_salud_']:checked").val() : 0;
    var opt_tabla9_control_dieta = $("[id^='control_dieta_']").is(":checked") ? $("[id^='control_dieta_']:checked").val() : 0;
    var opt_total_tabla9 = $("[id='resultado_tabla9']").val() || 0;

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
        $(this).val(opt_tabla9_higiene_personal);
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
    var opt_tabla10_adquisi_vivir = $("[id^='adquisicion_para_vivir_']").is(":checked") ? $("[id^='adquisicion_para_vivir_']:checked").val() : 0;
    var opt_tabla10_bienes_servicios = $("[id^='bienes_servicios_']").is(":checked") ? $("[id^='bienes_servicios_']:checked").val() : 0;
    var opt_tabla10_comprar = $("[id^='comprar_']").is(":checked") ? $("[id^='comprar_']:checked").val() : 0;
    var opt_tabla10_preparar_comida = $("[id^='preparar_comida_']").is(":checked") ? $("[id^='preparar_comida_']:checked").val() : 0;
    var opt_tabla10_quehaceres_casa = $("[id^='quehaceres_casa_']").is(":checked") ? $("[id^='quehaceres_casa_']:checked").val() : 0;
    var opt_tabla10_limpieza_vivienda = $("[id^='limpieza_vivienda_']").is(":checked") ? $("[id^='limpieza_vivienda_']:checked").val() : 0;
    var opt_tabla10_objetos_hogar = $("[id^='objetos_hogar_']").is(":checked") ? $("[id^='objetos_hogar_']:checked").val() : 0;
    var opt_tabla10_ayudar_los_demas = $("[id^='ayudar_los_demas_']").is(":checked") ? $("[id^='ayudar_los_demas_']:checked").val() : 0;
    var opt_tabla10_mante_dispositivos = $("[id^='mantenimiento_dispositivos_']").is(":checked") ? $("[id^='mantenimiento_dispositivos_']:checked").val() : 0;
    var opt_tabla10_cuidado_animales = $("[id^='cuidado_animales_']").is(":checked") ? $("[id^='cuidado_animales_']:checked").val() : 0;
    var opt_total_tabla10 = $("[id='resultado_tabla10']").val() || 0;
    var opt_sumaTotal_20 = 0;
    var opt_sumaTotal_50 = 0;

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
        //clearInterval(intervalo);
        intervalo = setInterval(() => {                        
            opt_sumaTotal_20 = Number(opt_total_tabla6) + Number(opt_total_tabla7)+ Number(opt_total_tabla8)
            + Number(opt_total_tabla9)+ Number(opt_total_tabla10);
            if(!isNaN(opt_sumaTotal_20)){
                //console.log(opt_sumaTotal_20);
                $('#total_otras').val(redondear(opt_sumaTotal_20));
            }
        }, 500);
    }
    // Suma Total rol laboral y otras areas(50%)
    function iniciarIntervaloLaboralOtras() {
        //clearInterval(intervalo);
        intervalo= setInterval(() => {
            opt_sumaTotal_50 = Number(opt_sumaTotal_20) + Number(opt_total_laboral30);
            if(!isNaN(opt_sumaTotal_50)){
                $('#total_rol_areas').val(redondear(opt_sumaTotal_50));
            }
        }, 500);
    }

    $(document).ready(function() {
        $('[id^="activarintervalos"]').each(function() {
            $(this).hover(function() {
                iniciarIntervaloTotalLaboral30();
                iniciarIntervaloOtrasAreas();
                iniciarIntervaloLaboralOtras();
            });
        });
    });
    //Tabla 12 - Criterios desarrollo neuroevolutivo Niños y Niñas 0 a 3 años
    var opt_tabla12_mantiene_postura = $("[id^='mantiene_postura']").is(":checked") ? $("[id^='mantiene_postura']:checked").val() : 0;
    var opt_tabla12_activi_espontanea = $("[id^='actividad_espontanea']").is(":checked") ? $("[id^='actividad_espontanea']:checked").val() : 0;
    var opt_tabla12_sujeta_cabeza = $("[id^='sujeta_cabeza']").is(":checked") ? $("[id^='sujeta_cabeza']:checked").val() : 0;
    var opt_tabla12_sienta_apoyo = $("[id^='sienta_apoyo']").is(":checked") ? $("[id^='sienta_apoyo']:checked").val() : 0;
    var opt_tabla12_sobre_mismo = $("[id^='sobre_mismo']").is(":checked") ? $("[id^='sobre_mismo']:checked").val() : 0;
    var opt_tabla12_sentado_sin_apoyo = $("[id^='sentado_sin_apoyo']").is(":checked") ? $("[id^='sentado_sin_apoyo']:checked").val() : 0;
    var opt_tabla12_tumbado_sentado = $("[id^='tumbado_sentado']").is(":checked") ? $("[id^='tumbado_sentado']:checked").val() : 0;
    var opt_tabla12_pie_apoyo = $("[id^='pie_apoyo']").is(":checked") ? $("[id^='pie_apoyo']:checked").val() : 0;
    var opt_tabla12_pasos_apoyo = $("[id^='pasos_apoyo']").is(":checked") ? $("[id^='pasos_apoyo']:checked").val() : 0;
    var opt_tabla12_mantiene_sin_apoyo = $("[id^='mantiene_sin_apoyo']").is(":checked") ? $("[id^='mantiene_sin_apoyo']:checked").val() : 0;
    var opt_tabla12_anda_solo = $("[id^='anda_solo']").is(":checked") ? $("[id^='anda_solo']:checked").val() : 0;
    var opt_tabla12_empuja_pelota = $("[id^='empuja_pelota']").is(":checked") ? $("[id^='empuja_pelota']:checked").val() : 0;
    var opt_tabla12_sorteando_obstaculos = $("[id^='sorteando_obstaculos']").is(":checked") ? $("[id^='sorteando_obstaculos']:checked").val() : 0;
    var opt_tabla12_succiona = $("[id^='succiona']").is(":checked") ? $("[id^='succiona']:checked").val() : 0;
    var opt_tabla12_fija_mirada = $("[id^='fija_mirada']").is(":checked") ? $("[id^='fija_mirada']:checked").val() : 0;
    var opt_tabla12_trayectoria_objeto = $("[id^='trayectoria_objeto']").is(":checked") ? $("[id^='trayectoria_objeto']:checked").val() : 0;
    var opt_tabla12_sostiene_sonajero = $("[id^='sostiene_sonajero']").is(":checked") ? $("[id^='sostiene_sonajero']:checked").val() : 0;
    var opt_tabla12_hacia_objeto = $("[id^='hacia_objeto']").is(":checked") ? $("[id^='hacia_objeto']:checked").val() : 0;
    var opt_tabla12_sostiene_objeto = $("[id^='sostiene_objeto']").is(":checked") ? $("[id^='sostiene_objeto']:checked").val() : 0;
    var opt_tabla12_abre_cajones = $("[id^='abre_cajones']").is(":checked") ? $("[id^='abre_cajones']:checked").val() : 0;
    var opt_tabla12_bebe_solo = $("[id^='bebe_solo']").is(":checked") ? $("[id^='bebe_solo']:checked").val() : 0;
    var opt_tabla12_quita_prenda = $("[id^='quita_prenda']").is(":checked") ? $("[id^='quita_prenda']:checked").val() : 0;
    var opt_tabla12_espacios_casa = $("[id^='espacios_casa']").is(":checked") ? $("[id^='espacios_casa']:checked").val() : 0;
    var opt_tabla12_imita_trazaso = $("[id^='imita_trazaso']").is(":checked") ? $("[id^='imita_trazaso']:checked").val() : 0;
    var opt_tabla12_abre_puerta = $("[id^='abre_puerta']").is(":checked") ? $("[id^='abre_puerta']:checked").val() : 0;
    var opt_total_tabla12 = $("[id='total_tabla12']").val() || 0;
    
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
    var opt_tabla13_ocupacionales_juego = $("[id^='roles_ocupacionales_juego']").is(":checked") ? $("[id^='roles_ocupacionales_juego']:checked").val() : 0;
    var opt_total_tabla13 = $("[id='total_tabla13']").val() || 0;
    $("[name='roles_ocupacionales_juego']").on("change", function(){
        opt_tabla13_ocupacionales_juego = $(this).val();
        $(this).val(opt_tabla13_ocupacionales_juego);
        iniciarIntervaloTotaltabla13();
    });
    if (opt_total_tabla13 === 0.0) {
        opt_total_tabla13 = 0;
        $('#total_tabla13').val(opt_total_tabla13);       
    }    
    //Realiza suma de tabla 13
    function iniciarIntervaloTotaltabla13() {
        //clearInterval(intervalo);
        intervalo = setInterval(() => {
            opt_total_tabla13 = Number(opt_tabla13_ocupacionales_juego);
            if(!isNaN(opt_total_tabla13)){
                $('#total_tabla13').val(redondear(opt_total_tabla13));
            }
        }, 500);
    }
    //console.log(opt_total_tabla13);
    
    //Tabla 14 - Valoración de los roles ocupacional relacionado
    var opt_tabla14_ocupacionales_adultos = $("[id^='roles_ocupacionales_adultos']").is(":checked") ? $("[id^='roles_ocupacionales_adultos']:checked").val() : 0;
    var opt_total_tabla14 = $("[id='total_tabla14']").val() || 0;
    $("[name='roles_ocupacionales_adultos']").on("change", function(){
        opt_tabla14_ocupacionales_adultos = $(this).val();
        $(this).val(opt_tabla14_ocupacionales_adultos);
        iniciarIntervaloTotaltabla14();
    });
    if (opt_total_tabla14 === 0.0) {
        opt_total_tabla14 = 0;
        $('#total_tabla14').val(opt_total_tabla14);       
    }  
    //Realiza suma de tabla 14
    function iniciarIntervaloTotaltabla14() {
        //clearInterval(intervalo);
        intervalo = setInterval(() => {
            opt_total_tabla14 = Number(opt_tabla14_ocupacionales_adultos);
            if(!isNaN(opt_total_tabla14)){
                $('#total_tabla14').val(redondear(opt_total_tabla14));
            }
        }, 500);
    }
    //Cargar opciones de selectores Libro II Calificación de las discapacidades (20%) 
    var options = ['0.1', '0.2', '0.3'];
    var options2 = ['0.1', '0.2'];
    var select = $('[id^="conducta_"],[id^="comunicacion_"],[id^="cuidado_personal_"],[id^="lomocion_"],[id^="disposicion_"],[id^="destreza_"], [id="situacion_76"], [id="situacion_77"]');
    var select2 = $('[id="situacion_70"], [id="situacion_71"], [id="situacion_72"], [id="situacion_73"],[id="situacion_74"],[id="situacion_75"],[id="situacion_78"]');

    function appendOptions(selectElement, optionsArray) {
        optionsArray.forEach(function(value) {
            selectElement.append($('<option>').text(value).attr('value', value));
        });
    }
    

    appendOptions(select, options);
    appendOptions(select2,options2);

    //Suma de valores conducta
    var opt_conducta = [];
    var opt_total_conducta = $("[id='total_conducta']").val() || 0; 
    
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
        //clearInterval(intervalo);   
        intervalo = setInterval(() => {
            opt_total_conducta = Number($("[id='conducta_10']").val()) + Number($("[id='conducta_11']").val()) + Number($("[id='conducta_12']").val()) +
            Number($("[id='conducta_13']").val()) + Number($("[id='conducta_14']").val()) + Number($("[id='conducta_15']").val()) +
            Number($("[id='conducta_16']").val()) + Number($("[id='conducta_17']").val()) + Number($("[id='conducta_18']").val()) +
            Number($("[id='conducta_19']").val());          
            if(!isNaN(opt_total_conducta)){
                $('#total_conducta').val(redondear(opt_total_conducta));                
            }
        }, 500);        
    } 

    //Suma de valores Comunicación
    var opt_comunicacion = [];
    var opt_total_comunicacion = $("[id='total_comunicacion']").val() || 0; 

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
        //clearInterval(intervalo);
        intervalo = setInterval(() => {
            opt_total_comunicacion = Number($("[id='comunicacion_20']").val()) + Number($("[id='comunicacion_21']").val()) + Number($("[id='comunicacion_22']").val()) +
            Number($("[id='comunicacion_23']").val()) + Number($("[id='comunicacion_24']").val()) + Number($("[id='comunicacion_25']").val()) +
            Number($("[id='comunicacion_26']").val()) + Number($("[id='comunicacion_27']").val()) + Number($("[id='comunicacion_28']").val()) +
            Number($("[id='comunicacion_29']").val());
            if(!isNaN(opt_total_comunicacion)){
                $('#total_comunicacion').val(redondear(opt_total_comunicacion));
            }
        }, 500);
    }

    //Suma de valores Cuidado personal
    var opt_cuidado_personal = [];
    var opt_total_cuidado_personal = $("[id='total_cuidado_personal']").val() || 0; 

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
        //clearInterval(intervalo);
        intervalo = setInterval(() => {
            opt_total_cuidado_personal = Number($("[id='cuidado_personal_30']").val()) + Number($("[id='cuidado_personal_31']").val()) + Number($("[id='cuidado_personal_32']").val()) +
            Number($("[id='cuidado_personal_33']").val()) + Number($("[id='cuidado_personal_34']").val()) + Number($("[id='cuidado_personal_35']").val()) +
            Number($("[id='cuidado_personal_36']").val()) + Number($("[id='cuidado_personal_37']").val()) + Number($("[id='cuidado_personal_38']").val()) +
            Number($("[id='cuidado_personal_39']").val());
            if(!isNaN(opt_total_cuidado_personal)){
                $('#total_cuidado_personal').val(redondear(opt_total_cuidado_personal));
            }
        }, 500);
    }

     //Suma de valores Locomoción
     var opt_lomocion= [];
     var opt_total_lomocion = $("[id='total_lomocion']").val() || 0; 
 
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
         //clearInterval(intervalo);
         intervalo = setInterval(() => {
            opt_total_lomocion = Number($("[id='lomocion_40']").val()) + Number($("[id='lomocion_41']").val()) + Number($("[id='lomocion_42']").val()) +
            Number($("[id='lomocion_43']").val()) + Number($("[id='lomocion_44']").val()) + Number($("[id='lomocion_45']").val()) +
            Number($("[id='lomocion_46']").val()) + Number($("[id='lomocion_47']").val()) + Number($("[id='lomocion_48']").val()) +
            Number($("[id='lomocion_49']").val());
             if(!isNaN(opt_total_lomocion)){
                 $('#total_lomocion').val(redondear(opt_total_lomocion));
             }
         }, 500);
     }

     //Suma de valores Disposición del cuerpo
     var opt_disposicion= [];
     var opt_total_disposicion = $("[id='total_disposicion']").val() || 0; 
 
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
         //clearInterval(intervalo);
         intervalo = setInterval(() => {
            opt_total_disposicion = Number($("[id='disposicion_50']").val()) + Number($("[id='disposicion_51']").val()) + Number($("[id='disposicion_52']").val()) +
            Number($("[id='disposicion_53']").val()) + Number($("[id='disposicion_54']").val()) + Number($("[id='disposicion_55']").val()) +
            Number($("[id='disposicion_56']").val()) + Number($("[id='disposicion_57']").val()) + Number($("[id='disposicion_58']").val()) +
            Number($("[id='disposicion_59']").val());
             if(!isNaN(opt_total_disposicion)){
                 $('#total_disposicion').val(redondear(opt_total_disposicion));
             }
         }, 500);
     }

      //Suma de valores Destreza
      var opt_destreza= [];
      var opt_total_destreza = $("[id='total_destreza']").val() || 0; 
  
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
          //clearInterval(intervalo);
          intervalo = setInterval(() => {
            opt_total_destreza = Number($("[id='destreza_60']").val()) + Number($("[id='destreza_61']").val()) + Number($("[id='destreza_62']").val()) +
            Number($("[id='destreza_63']").val()) + Number($("[id='destreza_64']").val()) + Number($("[id='destreza_65']").val()) +
            Number($("[id='destreza_66']").val()) + Number($("[id='destreza_67']").val()) + Number($("[id='destreza_68']").val()) +
            Number($("[id='destreza_69']").val());
              if(!isNaN(opt_total_destreza)){
                  $('#total_destreza').val(redondear(opt_total_destreza));
              }
          }, 500);
      }

    //Suma de valores Situación
    var opt_situacion= [];
    var opt_total_situacion = $("[id='total_situacion']").val() || 0;

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
        //clearInterval(intervalo);
        intervalo = setInterval(() => {
            opt_total_situacion = Number($("[id='situacion_70']").val()) + Number($("[id='situacion_71']").val()) + Number($("[id='situacion_72']").val()) +
            Number($("[id='situacion_73']").val()) + Number($("[id='situacion_74']").val()) + Number($("[id='situacion_75']").val()) +
            Number($("[id='situacion_76']").val()) + Number($("[id='situacion_77']").val()) + Number($("[id='situacion_78']").val());
            if(!isNaN(opt_total_situacion)){
                $('#total_situacion').val(redondear(opt_total_situacion));
            }
        }, 500);
    }

    //Total Discapacidades
    var opt_sumaTotal_disca = 0;
    function iniciarIntervaloDiscapacida() {
        intervaloDisca= setInterval(() => {
            opt_sumaTotal_disca= Number(opt_total_conducta) + Number(opt_total_comunicacion) + Number(opt_total_cuidado_personal) + Number(opt_total_lomocion) + 
            Number(opt_total_disposicion) + Number(opt_total_destreza) + Number(opt_total_situacion);
            if(!isNaN(opt_sumaTotal_disca)){
                $('#total_discapacidades').val(redondear(opt_sumaTotal_disca));
            }
        }, 500);
    }
    
    // total_minusvalia
    var opt_orientacion= $("[id^='orientacion_']").is(":checked") ? $("[id^='orientacion_']:checked").val() : 0;
    var opt_indepen_fisica = $("[id^='indepen_fisica_']").is(":checked") ? $("[id^='indepen_fisica_']:checked").val() : 0;
    var opt_desplazamiento = $("[id^='desplazamiento_']").is(":checked") ? $("[id^='desplazamiento_']:checked").val() : 0;
    var opt_ocupacional = $("[id^='ocupacional_']").is(":checked") ? $("[id^='ocupacional_']:checked").val() : 0;
    var opt_social = $("[id^='social_']").is(":checked") ? $("[id^='social_']:checked").val() : 0;
    var opt_economica = $("[id^='economica_']").is(":checked") ? $("[id^='economica_']:checked").val() : 0;
    var opt_cronologica = $("[name='cronologica']").is(":checked") ? $("[name='cronologica']:checked").val() : 0;
    var opt_sumaTotal_valia = 0;

    $("[name='orientacion']").on("change", function(){
        opt_orientacion = $(this).val();
        $(this).val(opt_orientacion);
        iniciarIntervaloMinusvalia();
    });

    $("[name='indepen_fisica']").on("change", function(){
        opt_indepen_fisica = $(this).val();
        $(this).val(opt_indepen_fisica);
        iniciarIntervaloMinusvalia();
    });

    $("[name='desplazamiento']").on("change", function(){
        opt_desplazamiento = $(this).val();
        $(this).val(opt_desplazamiento);
        iniciarIntervaloMinusvalia();
    });

    $("[name='ocupacional']").on("change", function(){
        opt_ocupacional = $(this).val();
        $(this).val(opt_ocupacional);
        iniciarIntervaloMinusvalia();
    });

    $("[name='social']").on("change", function(){
        opt_social = $(this).val();
        $(this).val(opt_social);
        iniciarIntervaloMinusvalia();
    });

    $("[name='economica']").on("change", function(){
        opt_economica = $(this).val();
        $(this).val(opt_economica);
        iniciarIntervaloMinusvalia();
    });

    $("[name='cronologica']").on("change", function(){
        opt_cronologica = Number($(this).val());
        $(this).val(opt_cronologica);
        iniciarIntervaloMinusvalia();
    });

    function iniciarIntervaloMinusvalia() {
        //clearInterval(intervalo);
        intervalo = setInterval(() => {
            opt_sumaTotal_valia = Number(opt_orientacion) + Number(opt_indepen_fisica) + Number(opt_desplazamiento) + Number(opt_ocupacional)
            + Number(opt_social) + Number(opt_economica) + Number(opt_cronologica);
            if (!isNaN(opt_sumaTotal_valia)){
                $('#total_minusvalia').val(redondear(opt_sumaTotal_valia));
            }
        }, 500);
    }

    /* $(document).ready(function() {
        $('[id^="activarminusvalia_"]').each(function() {
            $(this).hover(function() {
                iniciarIntervaloMinusvalia();
            });
        });
    }); */


    $('#form_CaliTecDecreto').submit(function (e){
        e.preventDefault();
        var GuardarDecreto = $('#GuardarDecreto');
        var ActualizarDecreto = $('#ActualizarDecreto');

        if (GuardarDecreto.length > 0) {
            document.querySelector('#GuardarDecreto').disabled=true;            
        }
        if (ActualizarDecreto.length > 0) {
            document.querySelector('#ActualizarDecreto').disabled=true;
        }

        var origen_firme = $('#origen_firme').val();
        var origen_cobertura = $('#origen_cobertura').val();
        var modalidad_calificacion = $('#modalidad_calificacion').val();
        
        if(origen_firme == 49 && origen_cobertura == 51 || origen_firme == 48 && origen_cobertura == 51 || origen_firme == 49 && origen_cobertura == 50){
            
            var banderaGuardarNoDecreto = $('#banderaGuardarNoDecreto').val();
            var Id_EventoDecreto = $('#Id_Evento_decreto').val();
            var Id_ProcesoDecreto = $('#Id_Proceso_decreto').val();
            var Id_Asignacion_Dcreto  = $('#Id_Asignacion_decreto').val();
            var origenFirme = $('#origen_firme').val();
            var cobertura = $('#origen_cobertura').val();
            var decreto = $('#decreto_califi').val();
            let token = $('input[name=_token]').val();

            var datos_agregarNoDecretoDicRelFun ={
                '_token': token,
                'Id_Evento_decreto':Id_EventoDecreto,
                'Id_Proceso_decreto':Id_ProcesoDecreto,
                'Id_Asignacion_decreto':Id_Asignacion_Dcreto,
                'origen_firme':origenFirme,
                'origen_cobertura':cobertura,
                'decreto_califi':decreto,
                'banderaGuardarNoDecreto': banderaGuardarNoDecreto,
                'modalidad_calificacion': modalidad_calificacion
            }
            $.ajax({
                type:'POST',
                url:'/guardarDecretoDictamenRelacionDocFunda',
                data: datos_agregarNoDecretoDicRelFun,
                success: function(response){
                    if (response.parametro == 'agregar_Nodecreto_parte') {
                        document.querySelector('#GuardarNoDecreto').disabled=true;
                        $('#div_alerta_Nodecreto').removeClass('d-none');
                        $('.alerta_Nodecreto').append('<strong>'+response.mensaje+'</strong>');                                            
                        setTimeout(function(){
                            $('#div_alerta_Nodecreto').addClass('d-none');
                            $('.alerta_Nodecreto').empty();   
                            location.reload();
                        }, 3000);   
                    }else if(response.parametro == 'actualizar_Nodecreto_parte'){
                        document.querySelector('#ActualizarNoDecreto').disabled=true;
                        $('#div_alerta_Nodecreto').removeClass('d-none');
                        $('.alerta_Nodecreto').append('<strong>'+response.mensaje2+'</strong>');                                            
                        setTimeout(function(){
                            $('#div_alerta_Nodecreto').addClass('d-none');
                            $('.alerta_Nodecreto').empty();
                            document.querySelector('#ActualizarNoDecreto').disabled=false;
                            location.reload();
                        }, 3000);
                    }
                }
            })
        }else if(origen_firme == 48 && origen_cobertura == 50){
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
            var dominancia = $("#dominancia").val();
            var id_afiliado = $("#id_afiliado").val();
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
                'dominancia': dominancia,
                'id_afiliado': id_afiliado,
                'bandera_decreto_guardar_actualizar':bandera_decreto_guardar_actualizar,
                'modalidad_calificacion': modalidad_calificacion
            }
            $.ajax({
                type:'POST',
                url:'/guardarDecretoDictamenRelacionDocFunda',
                data: datos_agregarDecretoDicRelFun,
                success: function(response){
                    if (response.parametro == 'agregar_decreto_parte') {
                        $('#div_alerta_decreto').removeClass('d-none');
                        $('.alerta_decreto').append('<strong>'+response.mensaje+'</strong>');                                            
                        setTimeout(function(){
                            document.querySelector('#GuardarDecreto').disabled=false;
                            $('#div_alerta_decreto').addClass('d-none');
                            $('.alerta_decreto').empty();   
                            location.reload();
                        }, 3000);   
                    }else if(response.parametro == 'update_decreto_parte'){
                        $('#div_alerta_decreto').removeClass('d-none');
                        $('.alerta_decreto').append('<strong>'+response.mensaje2+'</strong>');                                            
                        setTimeout(function(){
                            document.querySelector('#ActualizarDecreto').disabled=false;
                            $('#div_alerta_decreto').addClass('d-none');
                            $('.alerta_decreto').empty();
                            document.querySelector('#ActualizarDecreto').disabled=false;
                            location.reload();
                        }, 3000);
                    }
    
                }
            })
        }
    })

    $('#form_laboralmente_activo').submit(function (e){
        e.preventDefault();
        var Id_EventoDecreto = $('#Id_Evento_decreto').val();
        var Id_ProcesoDecreto = $('#Id_Proceso_decreto').val();
        var Id_Asignacion_Dcreto  = $('#Id_Asignacion_decreto').val();
        console.log(Id_Asignacion_Dcreto);
        var restricion_rol = $('input[name="restricion_rol"]:checked').val();
        var auto_suficiencia = $('input[name="auto_suficiencia"]:checked').val();
        if ($('input[type="radio"][id^="edad_cronologica_"]').is(":checked")) {                            
            var edad_cronologica_adulto = $('input[id^="edad_cronologica_"]:checked').val();
        }else{            
            var edad_cronologica_menor = $('input[id^="cronologica_menor"]:checked').val();
        }
        var resultado_rol_laboral_30 = $('#resultado_rol_laboral_30').val();
        var mirar = $('input[name="mirar"]:checked').val();
        var escuchar = $('input[name="escuchar"]:checked').val();
        var aprender = $('input[name="aprender"]:checked').val();
        var calcular = $('input[name="calcular"]:checked').val();
        var pensar = $('input[name="pensar"]:checked').val();
        var leer = $('input[name="leer"]:checked').val();
        var escribir = $('input[name="escribir"]:checked').val();
        var matematicos = $('input[name="matematicos"]:checked').val();
        var decisiones = $('input[name="decisiones"]:checked').val();
        var tareas_simples = $('input[name="tareas_simples"]:checked').val();
        var resultado_tabla6 = $('#resultado_tabla6').val();
        var comunicarse_mensaje = $('input[name="comunicarse_mensaje"]:checked').val();
        var no_comunicarse_mensaje = $('input[name="no_comunicarse_mensaje"]:checked').val();
        var comunicarse_signos = $('input[name="comunicarse_signos"]:checked').val();
        var comunicarse_escrito = $('input[name="comunicarse_escrito"]:checked').val();
        var habla = $('input[name="habla"]:checked').val();
        var no_verbales = $('input[name="no_verbales"]:checked').val();
        var mensajes_escritos = $('input[name="mensajes_escritos"]:checked').val();
        var sostener_conversa = $('input[name="sostener_conversa"]:checked').val();
        var iniciar_discusiones = $('input[name="iniciar_discusiones"]:checked').val();
        var utiliza_dispositivos = $('input[name="utiliza_dispositivos"]:checked').val();
        var resultado_tabla7 = $('#resultado_tabla7').val();
        var cambiar_posturas = $('input[name="cambiar_posturas"]:checked').val();
        var posicion_cuerpo = $('input[name="posicion_cuerpo"]:checked').val();
        var llevar_objetos = $('input[name="llevar_objetos"]:checked').val();
        var uso_fino_mano = $('input[name="uso_fino_mano"]:checked').val();
        var uso_mano_brazo = $('input[name="uso_mano_brazo"]:checked').val();
        var desplazarse_entorno = $('input[name="desplazarse_entorno"]:checked').val();
        var distintos_lugares = $('input[name="distintos_lugares"]:checked').val();
        var desplazarse_con_equipo = $('input[name="desplazarse_con_equipo"]:checked').val();
        var transporte_pasajero = $('input[name="transporte_pasajero"]:checked').val();
        var conduccion = $('input[name="conduccion"]:checked').val();
        var resultado_tabla8 = $('#resultado_tabla8').val();
        var lavarse = $('input[name="lavarse"]:checked').val();
        var cuidado_cuerpo = $('input[name="cuidado_cuerpo"]:checked').val();
        var higiene_personal = $('input[name="higiene_personal"]:checked').val();
        var vestirse = $('input[name="vestirse"]:checked').val();
        var quitarse_ropa = $('input[name="quitarse_ropa"]:checked').val();
        var ponerse_calzado = $('input[name="ponerse_calzado"]:checked').val();
        var comer = $('input[name="comer"]:checked').val();
        var beber = $('input[name="beber"]:checked').val();
        var cuidado_salud = $('input[name="cuidado_salud"]:checked').val();
        var control_dieta = $('input[name="control_dieta"]:checked').val();
        var resultado_tabla9 = $('#resultado_tabla9').val();
        var adquisicion_para_vivir = $('input[name="adquisicion_para_vivir"]:checked').val();
        var bienes_servicios = $('input[name="bienes_servicios"]:checked').val();
        var comprar = $('input[name="comprar"]:checked').val();
        var preparar_comida = $('input[name="preparar_comida"]:checked').val();
        var quehaceres_casa = $('input[name="quehaceres_casa"]:checked').val();
        var limpieza_vivienda = $('input[name="limpieza_vivienda"]:checked').val();
        var objetos_hogar = $('input[name="objetos_hogar"]:checked').val();
        var ayudar_los_demas = $('input[name="ayudar_los_demas"]:checked').val();
        var mantenimiento_dispositivos = $('input[name="mantenimiento_dispositivos"]:checked').val();
        var cuidado_animales = $('input[name="cuidado_animales"]:checked').val();
        var resultado_tabla10 = $('#resultado_tabla10').val();
        var total_otras = $('#total_otras').val();
        var total_rol_areas = $('#total_rol_areas').val();    
        var bandera_LaboralActivo_guardar_actualizar =$('#bandera_LaboralActivo_guardar_actualizar').val();       
        
        var datos_agregarLaboralmenteActivo ={
            '_token': token,
            'Id_Evento_decreto':Id_EventoDecreto,
            'Id_Proceso_decreto':Id_ProcesoDecreto,
            'Id_Asignacion_decreto':Id_Asignacion_Dcreto, 
            'restricion_rol':restricion_rol,
            'auto_suficiencia':auto_suficiencia,
            'edad_cronologica_adulto':edad_cronologica_adulto,
            'edad_cronologica_menor':edad_cronologica_menor,
            'resultado_rol_laboral_30':resultado_rol_laboral_30,
            'mirar':mirar,
            'escuchar':escuchar,
            'aprender':aprender,
            'calcular':calcular,
            'pensar':pensar,
            'leer':leer,
            'escribir':escribir,
            'matematicos':matematicos,
            'decisiones':decisiones,
            'tareas_simples':tareas_simples,
            'resultado_tabla6':resultado_tabla6,
            'comunicarse_mensaje':comunicarse_mensaje,
            'no_comunicarse_mensaje':no_comunicarse_mensaje,
            'comunicarse_signos':comunicarse_signos,
            'comunicarse_escrito':comunicarse_escrito,
            'habla':habla,
            'no_verbales':no_verbales,
            'mensajes_escritos':mensajes_escritos,
            'sostener_conversa':sostener_conversa,
            'iniciar_discusiones':iniciar_discusiones,
            'utiliza_dispositivos':utiliza_dispositivos,
            'resultado_tabla7':resultado_tabla7,
            'cambiar_posturas':cambiar_posturas,
            'posicion_cuerpo':posicion_cuerpo,
            'llevar_objetos':llevar_objetos,
            'uso_fino_mano':uso_fino_mano,
            'uso_mano_brazo':uso_mano_brazo,
            'desplazarse_entorno':desplazarse_entorno,
            'distintos_lugares':distintos_lugares,
            'desplazarse_con_equipo':desplazarse_con_equipo,
            'transporte_pasajero':transporte_pasajero,
            'conduccion':conduccion,
            'resultado_tabla8':resultado_tabla8,
            'lavarse':lavarse,
            'cuidado_cuerpo':cuidado_cuerpo,
            'higiene_personal':higiene_personal,
            'vestirse':vestirse,
            'quitarse_ropa':quitarse_ropa,
            'ponerse_calzado':ponerse_calzado,
            'comer':comer,
            'beber':beber,
            'cuidado_salud':cuidado_salud,
            'control_dieta':control_dieta,
            'resultado_tabla9':resultado_tabla9,
            'adquisicion_para_vivir':adquisicion_para_vivir,
            'bienes_servicios':bienes_servicios,
            'comprar':comprar,
            'preparar_comida':preparar_comida,
            'quehaceres_casa':quehaceres_casa,
            'limpieza_vivienda':limpieza_vivienda,
            'objetos_hogar':objetos_hogar,
            'ayudar_los_demas':ayudar_los_demas,
            'mantenimiento_dispositivos':mantenimiento_dispositivos,
            'cuidado_animales':cuidado_animales,
            'resultado_tabla10':resultado_tabla10,
            'total_otras':total_otras,
            'total_rol_areas':total_rol_areas,
            'bandera_LaboralActivo_guardar_actualizar': bandera_LaboralActivo_guardar_actualizar,
        }
        $.ajax({
            type:'POST',
            url:'/guardarLaboralmenteActivos',
            data: datos_agregarLaboralmenteActivo,
            success: function(response){
                if (response.parametro == 'insertar_laboralmente_activo') {
                    document.querySelector('#GuardarLaboralActivo').disabled=true;
                    $('#div_alerta_laboralmente_activo').removeClass('d-none');
                    $('.alerta_laboralmente_activo').append('<strong>'+response.mensaje+'</strong>');                                            
                    setTimeout(function(){
                        $('#div_alerta_laboralmente_activo').addClass('d-none');
                        $('.alerta_laboralmente_activo').empty();   
                        location.reload();
                    }, 3000);   
                }else if(response.parametro == 'update_laboralmente_activo'){
                    document.querySelector('#ActualizarLaboralActivo').disabled=true;
                    $('#div_alerta_laboralmente_activo').removeClass('d-none');
                    $('.alerta_laboralmente_activo').append('<strong>'+response.mensaje2+'</strong>');                                            
                    setTimeout(function(){
                        $('#div_alerta_laboralmente_activo').addClass('d-none');
                        $('.alerta_laboralmente_activo').empty();
                        document.querySelector('#ActualizarLaboralActivo').disabled=false;
                        location.reload();
                    }, 3000);
                }
            }
        })
    })  
    
    $('#form_rol_ocupacional').submit(function (e){
        e.preventDefault();
        var Id_EventoDecreto = $('#Id_Evento_decreto').val();
        var Id_ProcesoDecreto = $('#Id_Proceso_decreto').val();
        var Id_Asignacion_Dcreto  = $('#Id_Asignacion_decreto').val();
        var poblacion_califi = $('#poblacion_califi').val();
        var mantiene_postura = $('input[name="mantiene_postura"]:checked').val();        
        var actividad_espontanea = $('input[name="actividad_espontanea"]:checked').val();        
        var sujeta_cabeza = $('input[name="sujeta_cabeza"]:checked').val();        
        var sienta_apoyo = $('input[name="sienta_apoyo"]:checked').val();        
        var sobre_mismo = $('input[name="sobre_mismo"]:checked').val();        
        var sentado_sin_apoyo = $('input[name="sentado_sin_apoyo"]:checked').val();        
        var tumbado_sentado = $('input[name="tumbado_sentado"]:checked').val();        
        var pie_apoyo = $('input[name="pie_apoyo"]:checked').val();        
        var pasos_apoyo = $('input[name="pasos_apoyo"]:checked').val();        
        var mantiene_sin_apoyo = $('input[name="mantiene_sin_apoyo"]:checked').val();        
        var anda_solo = $('input[name="anda_solo"]:checked').val();        
        var empuja_pelota = $('input[name="empuja_pelota"]:checked').val();        
        var sorteando_obstaculos = $('input[name="sorteando_obstaculos"]:checked').val();                
        var succiona = $('input[name="succiona"]:checked').val();        
        var fija_mirada = $('input[name="fija_mirada"]:checked').val();        
        var trayectoria_objeto = $('input[name="trayectoria_objeto"]:checked').val();        
        var sostiene_sonajero = $('input[name="sostiene_sonajero"]:checked').val();        
        var hacia_objeto = $('input[name="hacia_objeto"]:checked').val();        
        var sostiene_objeto = $('input[name="sostiene_objeto"]:checked').val();        
        var abre_cajones = $('input[name="abre_cajones"]:checked').val();        
        var bebe_solo = $('input[name="bebe_solo"]:checked').val();        
        var quita_prenda = $('input[name="quita_prenda"]:checked').val();        
        var espacios_casa = $('input[name="espacios_casa"]:checked').val();        
        var imita_trazaso = $('input[name="imita_trazaso"]:checked').val();        
        var abre_puerta = $('input[name="abre_puerta"]:checked').val();    
        var total_tabla12 = $('#total_tabla12').val();
        var roles_ocupacionales_juego = $('input[name="roles_ocupacionales_juego"]:checked').val();    
        var total_tabla13 = $('#total_tabla13').val();
        var roles_ocupacionales_adultos = $('input[name="roles_ocupacionales_adultos"]:checked').val();            
        var total_tabla14 = $('#total_tabla14').val();
        var bandera_RolOcupacional_guardar_actualizar =$('#bandera_RolOcupacional_guardar_actualizar').val();

        var datos_agregarRolOcupacional ={
            '_token': token,
            'Id_EventoDecreto':Id_EventoDecreto,
            'Id_ProcesoDecreto':Id_ProcesoDecreto,
            'Id_Asignacion_Dcreto':Id_Asignacion_Dcreto, 
            'poblacion_califi':poblacion_califi,
            'mantiene_postura':mantiene_postura,
            'actividad_espontanea':actividad_espontanea,
            'sujeta_cabeza':sujeta_cabeza,
            'sienta_apoyo':sienta_apoyo,
            'sobre_mismo':sobre_mismo,
            'sentado_sin_apoyo':sentado_sin_apoyo,
            'tumbado_sentado':tumbado_sentado,
            'pie_apoyo':pie_apoyo,
            'pasos_apoyo':pasos_apoyo,
            'mantiene_sin_apoyo':mantiene_sin_apoyo,
            'anda_solo':anda_solo,
            'empuja_pelota':empuja_pelota,
            'sorteando_obstaculos':sorteando_obstaculos,
            'succiona':succiona,
            'fija_mirada':fija_mirada,
            'trayectoria_objeto':trayectoria_objeto,            
            'sostiene_sonajero':sostiene_sonajero,
            'hacia_objeto':hacia_objeto,
            'sostiene_objeto':sostiene_objeto,
            'abre_cajones':abre_cajones,
            'bebe_solo':bebe_solo,
            'quita_prenda':quita_prenda,
            'espacios_casa':espacios_casa,
            'imita_trazaso':imita_trazaso,
            'abre_puerta':abre_puerta,
            'total_tabla12':total_tabla12,
            'roles_ocupacionales_juego':roles_ocupacionales_juego,
            'total_tabla13':total_tabla13,
            'roles_ocupacionales_adultos':roles_ocupacionales_adultos,
            'total_tabla14':total_tabla14,
            'bandera_RolOcupacional_guardar_actualizar':bandera_RolOcupacional_guardar_actualizar,            
        }
        $.ajax({
            type:'POST',
            url:'/guardarRolOcupacionales',
            data: datos_agregarRolOcupacional,
            success: function(response){
                if (response.parametro == 'insertar_rol_ocupacional') {
                    document.querySelector('#GuardarRolOcupacional').disabled=true;
                    $('#div_alerta_rol_ocupacional').removeClass('d-none');
                    $('.alerta_rol_ocupacional').append('<strong>'+response.mensaje+'</strong>');                                            
                    setTimeout(function(){
                        $('#div_alerta_rol_ocupacional').addClass('d-none');
                        $('.alerta_rol_ocupacional').empty();   
                        location.reload();
                    }, 3000);   
                }else if(response.parametro == 'actualizar_rol_ocupacional'){
                    document.querySelector('#ActualizarRolOcupacional').disabled=true;
                    $('#div_alerta_rol_ocupacional').removeClass('d-none');
                    $('.alerta_rol_ocupacional').append('<strong>'+response.mensaje2+'</strong>');                                            
                    setTimeout(function(){
                        $('#div_alerta_rol_ocupacional').addClass('d-none');
                        $('.alerta_rol_ocupacional').empty();
                        document.querySelector('#ActualizarRolOcupacional').disabled=false;
                        location.reload();
                    }, 3000);
                }
            }
        })
    }) 

    $('#form_libros_2_3').submit(function (e){
        e.preventDefault();
        var Id_EventoDecreto = $('#Id_Evento_decreto').val();
        var Id_ProcesoDecreto = $('#Id_Proceso_decreto').val();
        var Id_Asignacion_Dcreto  = $('#Id_Asignacion_decreto').val();
        var conducta_10 = $('#conducta_10').val();
        var conducta_11 = $('#conducta_11').val();
        var conducta_12 = $('#conducta_12').val();
        var conducta_13 = $('#conducta_13').val();
        var conducta_14 = $('#conducta_14').val();
        var conducta_15 = $('#conducta_15').val();
        var conducta_16 = $('#conducta_16').val();
        var conducta_17 = $('#conducta_17').val();
        var conducta_18 = $('#conducta_18').val();
        var conducta_19 = $('#conducta_19').val();
        var total_conducta = $('#total_conducta').val();
        var comunicacion_20 = $('#comunicacion_20').val();
        var comunicacion_21 = $('#comunicacion_21').val();
        var comunicacion_22 = $('#comunicacion_22').val();
        var comunicacion_23 = $('#comunicacion_23').val();
        var comunicacion_24 = $('#comunicacion_24').val();
        var comunicacion_25 = $('#comunicacion_25').val();
        var comunicacion_26 = $('#comunicacion_26').val();
        var comunicacion_27 = $('#comunicacion_27').val();
        var comunicacion_28 = $('#comunicacion_28').val();
        var comunicacion_29 = $('#comunicacion_29').val();
        var total_comunicacion = $('#total_comunicacion').val();
        var cuidado_personal_30 = $('#cuidado_personal_30').val();
        var cuidado_personal_31 = $('#cuidado_personal_31').val();
        var cuidado_personal_32 = $('#cuidado_personal_32').val();
        var cuidado_personal_33 = $('#cuidado_personal_33').val();
        var cuidado_personal_34 = $('#cuidado_personal_34').val();
        var cuidado_personal_35 = $('#cuidado_personal_35').val();
        var cuidado_personal_36 = $('#cuidado_personal_36').val();
        var cuidado_personal_37 = $('#cuidado_personal_37').val();
        var cuidado_personal_38 = $('#cuidado_personal_38').val();
        var cuidado_personal_39 = $('#cuidado_personal_39').val();
        var total_cuidado_personal = $('#total_cuidado_personal').val();
        var lomocion_40 = $('#lomocion_40').val();
        var lomocion_41 = $('#lomocion_41').val();
        var lomocion_42 = $('#lomocion_42').val();
        var lomocion_43 = $('#lomocion_43').val();
        var lomocion_44 = $('#lomocion_44').val();
        var lomocion_45 = $('#lomocion_45').val();
        var lomocion_46 = $('#lomocion_46').val();
        var lomocion_47 = $('#lomocion_47').val();
        var lomocion_48 = $('#lomocion_48').val();
        var lomocion_49 = $('#lomocion_49').val();
        var total_lomocion = $('#total_lomocion').val();
        var disposicion_50 = $('#disposicion_50').val();
        var disposicion_51 = $('#disposicion_51').val();
        var disposicion_52 = $('#disposicion_52').val();
        var disposicion_53 = $('#disposicion_53').val();
        var disposicion_54 = $('#disposicion_54').val();
        var disposicion_55 = $('#disposicion_55').val();
        var disposicion_56 = $('#disposicion_56').val();
        var disposicion_57 = $('#disposicion_57').val();
        var disposicion_58 = $('#disposicion_58').val();
        var disposicion_59 = $('#disposicion_59').val();
        var total_disposicion = $('#total_disposicion').val();
        var destreza_60 = $('#destreza_60').val();
        var destreza_61 = $('#destreza_61').val();
        var destreza_62 = $('#destreza_62').val();
        var destreza_63 = $('#destreza_63').val();
        var destreza_64 = $('#destreza_64').val();
        var destreza_65 = $('#destreza_65').val();
        var destreza_66 = $('#destreza_66').val();
        var destreza_67 = $('#destreza_67').val();
        var destreza_68 = $('#destreza_68').val();
        var destreza_69 = $('#destreza_69').val();
        var total_destreza = $('#total_destreza').val();
        var situacion_70 = $('#situacion_70').val();
        var situacion_71 = $('#situacion_71').val();
        var situacion_72 = $('#situacion_72').val();
        var situacion_73 = $('#situacion_73').val();
        var situacion_74 = $('#situacion_74').val();
        var situacion_75 = $('#situacion_75').val();
        var situacion_76 = $('#situacion_76').val();
        var situacion_77 = $('#situacion_77').val();
        var situacion_78 = $('#situacion_78').val();
        var total_situacion = $('#total_situacion').val();
        var total_discapacidades = $('#total_discapacidades').val();
        var orientacion = $('input[name="orientacion"]:checked').val();        
        var indepen_fisica = $('input[name="indepen_fisica"]:checked').val();        
        var desplazamiento = $('input[name="desplazamiento"]:checked').val();        
        var ocupacional = $('input[name="ocupacional"]:checked').val();        
        var social = $('input[name="social"]:checked').val();        
        var economica = $('input[name="economica"]:checked').val();      
        if ($('input[type="radio"][id^="cronologica_"]').is(":checked")) {                            
            var cronologica_adulto = $('input[id^="cronologica_"]:checked').val();
        }else{            
            var cronologica_menor = $('input[id^="menor_18"]:checked').val();
        }    
        var total_minusvalia = $('#total_minusvalia').val();        
        var bandera_Libros2_3_guardar_actualizar =$('#bandera_Libros2_3_guardar_actualizar').val();
                
        var datos_agregarLibros2_3={
            '_token': token,
            'Id_EventoDecreto':Id_EventoDecreto,
            'Id_ProcesoDecreto':Id_ProcesoDecreto,
            'Id_Asignacion_Dcreto':Id_Asignacion_Dcreto,
            'conducta_10':conducta_10,
            'conducta_11':conducta_11,
            'conducta_12':conducta_12,
            'conducta_13':conducta_13,
            'conducta_14':conducta_14,
            'conducta_15':conducta_15,
            'conducta_16':conducta_16,
            'conducta_17':conducta_17,
            'conducta_18':conducta_18,
            'conducta_19':conducta_19,
            'total_conducta':total_conducta,
            'comunicacion_20':comunicacion_20,
            'comunicacion_21':comunicacion_21,
            'comunicacion_22':comunicacion_22,
            'comunicacion_23':comunicacion_23,
            'comunicacion_24':comunicacion_24,
            'comunicacion_25':comunicacion_25,
            'comunicacion_26':comunicacion_26,
            'comunicacion_27':comunicacion_27,
            'comunicacion_28':comunicacion_28,
            'comunicacion_29':comunicacion_29,
            'total_comunicacion':total_comunicacion,
            'cuidado_personal_30':cuidado_personal_30,
            'cuidado_personal_31':cuidado_personal_31,
            'cuidado_personal_32':cuidado_personal_32,
            'cuidado_personal_33':cuidado_personal_33,
            'cuidado_personal_34':cuidado_personal_34,
            'cuidado_personal_35':cuidado_personal_35,
            'cuidado_personal_36':cuidado_personal_36,
            'cuidado_personal_37':cuidado_personal_37,
            'cuidado_personal_38':cuidado_personal_38,
            'cuidado_personal_39':cuidado_personal_39,
            'total_cuidado_personal':total_cuidado_personal,
            'lomocion_40':lomocion_40,
            'lomocion_41':lomocion_41,
            'lomocion_42':lomocion_42,
            'lomocion_43':lomocion_43,
            'lomocion_44':lomocion_44,
            'lomocion_45':lomocion_45,
            'lomocion_46':lomocion_46,
            'lomocion_47':lomocion_47,
            'lomocion_48':lomocion_48,
            'lomocion_49':lomocion_49,
            'total_lomocion':total_lomocion,
            'disposicion_50':disposicion_50,
            'disposicion_51':disposicion_51,
            'disposicion_52':disposicion_52,
            'disposicion_53':disposicion_53,
            'disposicion_54':disposicion_54,
            'disposicion_55':disposicion_55,
            'disposicion_56':disposicion_56,
            'disposicion_57':disposicion_57,
            'disposicion_58':disposicion_58,
            'disposicion_59':disposicion_59,
            'total_disposicion':total_disposicion,
            'destreza_60':destreza_60,
            'destreza_61':destreza_61,
            'destreza_62':destreza_62,
            'destreza_63':destreza_63,
            'destreza_64':destreza_64,
            'destreza_65':destreza_65,
            'destreza_66':destreza_66,
            'destreza_67':destreza_67,
            'destreza_68':destreza_68,
            'destreza_69':destreza_69,
            'total_destreza':total_destreza,
            'situacion_70':situacion_70,
            'situacion_71':situacion_71,
            'situacion_72':situacion_72,
            'situacion_73':situacion_73,
            'situacion_74':situacion_74,
            'situacion_75':situacion_75,
            'situacion_76':situacion_76,
            'situacion_77':situacion_77,
            'situacion_78':situacion_78,
            'total_situacion':total_situacion,
            'total_discapacidades':total_discapacidades,
            'orientacion':orientacion,
            'indepen_fisica':indepen_fisica,
            'desplazamiento':desplazamiento,
            'ocupacional':ocupacional,
            'social':social,
            'economica':economica,
            'cronologica_adulto':cronologica_adulto,
            'cronologica_menor':cronologica_menor,
            'total_minusvalia':total_minusvalia,
            'bandera_Libros2_3_guardar_actualizar':bandera_Libros2_3_guardar_actualizar,            
        }
        $.ajax({
            type:'POST',
            url:'/guardarLibros2_3',
            data: datos_agregarLibros2_3,
            success: function(response){
                if (response.parametro == 'insertar_libros_2_3') {
                    document.querySelector('#GuardarLibros2_3').disabled=true;
                    $('#div_alerta_libros2_3').removeClass('d-none');
                    $('.alerta_libros2_3').append('<strong>'+response.mensaje+'</strong>');                                            
                    setTimeout(function(){
                        $('#div_alerta_libros2_3').addClass('d-none');
                        $('.alerta_libros2_3').empty();   
                        location.reload();
                    }, 3000);   
                }else if(response.parametro == 'actualizar_libros_2_3'){
                    document.querySelector('#ActualizarLibros2_3').disabled=true;
                    $('#div_alerta_libros2_3').removeClass('d-none');
                    $('.alerta_libros2_3').append('<strong>'+response.mensaje2+'</strong>');                                            
                    setTimeout(function(){
                        $('#div_alerta_libros2_3').addClass('d-none');
                        $('.alerta_libros2_3').empty();
                        document.querySelector('#ActualizarLibros2_3').disabled=false;
                        location.reload();
                    }, 3000);
                }
            }
        })
    })    

    
    /* Porcentaje PCl,  Rango PCL y Justificación de dependencia Concepto final del Dictamen Pericial  */
    $(document).ready(function() {

        definirDecreto_deficiencia = $('#decreto_califi').val();
        total_deficiencia = $('#Total_Deficiencia50').val();          
        total_rol_ocupacional12 = $('#total_tabla12').val();
        total_rol_ocupacional13 = $('#total_tabla13').val();
        total_rol_ocupacional14 = $('#total_tabla14').val();  
        total_rol_ocupacional = 0;
        dicapacidad_total = $('#total_discapacidades').val();
        minusvalia_total = $('#total_minusvalia').val();

        if (definirDecreto_deficiencia == 1) {
            if($.trim(total_rol_ocupacional12) == 0 && $.trim(total_rol_ocupacional13) == 0 && $.trim(total_rol_ocupacional14) == 0){
                total_rol_ocupacional = 0;
            }else if($.trim(total_rol_ocupacional12) > 0 && $.trim(total_rol_ocupacional13) == 0 && $.trim(total_rol_ocupacional14) == 0){                
                total_rol_ocupacional = Number(total_rol_ocupacional12);
            }else if($.trim(total_rol_ocupacional12) == 0 && $.trim(total_rol_ocupacional13) > 0 && $.trim(total_rol_ocupacional14) == 0){                
                total_rol_ocupacional = Number(total_rol_ocupacional13);
            }else if($.trim(total_rol_ocupacional12) == 0 && $.trim(total_rol_ocupacional13) == 0 && $.trim(total_rol_ocupacional14) > 0){               
                total_rol_ocupacional = Number(total_rol_ocupacional14);
            }
            total_rol_laboral = $('#total_rol_areas').val();
            if($.trim(total_deficiencia) == 0 && $.trim(total_rol_ocupacional) == 0 && $.trim(total_rol_laboral) === ""){
                porcentajePcl = 0;
            }else if($.trim(total_deficiencia) > 0 && $.trim(total_rol_ocupacional) == 0 && $.trim(total_rol_laboral) === ""){
                porcentajePcl = Number(total_deficiencia);
            }else if($.trim(total_deficiencia) == 0 && $.trim(total_rol_ocupacional) > 0 && $.trim(total_rol_laboral) === ""){
                porcentajePcl = Number(total_rol_ocupacional);
            }else if($.trim(total_deficiencia) == 0 && $.trim(total_rol_ocupacional) == 0 && $.trim(total_rol_laboral) > 0){
                porcentajePcl = Number(total_rol_laboral);
            }else if($.trim(total_deficiencia) > 0 && $.trim(total_rol_ocupacional) > 0 && $.trim(total_rol_laboral) === ""){
                porcentajePcl = Number(total_deficiencia) + Number(total_rol_ocupacional);  
            }else if($.trim(total_deficiencia) > 0 && $.trim(total_rol_ocupacional) == 0 && $.trim(total_rol_laboral) > 0){
                porcentajePcl = Number(total_deficiencia) + Number(total_rol_laboral);            
            }      
            //console.log(porcentajePcl);
            $("#porcentaje_pcl").val(porcentajePcl.toFixed(2));
            if (porcentajePcl == 0) {
                $("#rango_pcl").val('PCL 0');
            }else if (porcentajePcl < 5) {
                $("#rango_pcl").val('Menor al 5%');
            }else if(porcentajePcl >= 5 && porcentajePcl <= 14.99){
                $("#rango_pcl").val('Entre 5 y 14,99%');            
            }else if(porcentajePcl >= 15 && porcentajePcl <= 29.99){
                $("#rango_pcl").val('Entre 15 y 29,99%');            
            }else if(porcentajePcl >= 30 && porcentajePcl <= 49.99){
                $("#rango_pcl").val('Entre 30,01 y 49,99%');            
            }else if(porcentajePcl > 50){
                $("#rango_pcl").val('Mayor a 50%');            
            }   
            // Calculo del monto de indemnizacion (meses)
            if (porcentajePcl == 0) {
                $("#monto_inde").val(0);
            } else {
                var montoIndemnizacion = ((porcentajePcl / 2) - 0.5);
                $("#monto_inde").val(montoIndemnizacion.toFixed(2));                    
            }    
        } else if(definirDecreto_deficiencia == 3) {

            var total_deficiencia1999 = $('#Total_Deficiencia50');
            
            $('#Total_Deficiencia50').focus(function() {
                $("#Total_Deficiencia50").on("input", function() {
                    total_deficiencia1999 = $(this).val();
                    if ($.trim(total_deficiencia1999) == 0 && $.trim(dicapacidad_total) == 0 && $.trim(minusvalia_total) == 0) {
                        porcentajePcl = 0;
                    }else if($.trim(total_deficiencia1999) > 0 && $.trim(dicapacidad_total) == 0 && $.trim(minusvalia_total) == 0){
                        porcentajePcl = Number(total_deficiencia1999);
                    }else if($.trim(total_deficiencia1999) == 0 && $.trim(dicapacidad_total) > 0 && $.trim(minusvalia_total) == 0){
                        porcentajePcl = Number(dicapacidad_total);
                    }else if($.trim(total_deficiencia1999) == 0 && $.trim(dicapacidad_total) == 0 && $.trim(minusvalia_total) > 0){
                        porcentajePcl = Number(minusvalia_total);
                    }else if($.trim(total_deficiencia1999) > 0 && $.trim(dicapacidad_total) > 0 && $.trim(minusvalia_total) == 0){
                        porcentajePcl = Number(total_deficiencia1999) + Number(dicapacidad_total);
                    }else if($.trim(total_deficiencia1999) > 0 && $.trim(dicapacidad_total) == 0 && $.trim(minusvalia_total) > 0){
                        porcentajePcl = Number(total_deficiencia1999) + Number(minusvalia_total);
                    }else if($.trim(total_deficiencia1999) == 0 && $.trim(dicapacidad_total) > 0 && $.trim(minusvalia_total) > 0){
                        porcentajePcl = Number(dicapacidad_total) + Number(minusvalia_total);                
                    }else if($.trim(total_deficiencia1999) > 0 && $.trim(dicapacidad_total) > 0 && $.trim(minusvalia_total) > 0){                   
                        porcentajePcl = Number(total_deficiencia1999) + Number(dicapacidad_total) + Number(minusvalia_total);                
                    }                  
                    
                    $("#porcentaje_pcl").val(porcentajePcl.toFixed(2));
                    if (porcentajePcl == 0) {
                        $("#rango_pcl").val('PCL 0');
                    }else if (porcentajePcl < 5) {
                        $("#rango_pcl").val('Menor al 5%');
                    }else if(porcentajePcl >= 5 && porcentajePcl <= 14.99){
                        $("#rango_pcl").val('Entre 5 y 14,99%');            
                    }else if(porcentajePcl >= 15 && porcentajePcl <= 29.99){
                        $("#rango_pcl").val('Entre 15 y 29,99%');            
                    }else if(porcentajePcl >= 30 && porcentajePcl <= 49.99){
                        $("#rango_pcl").val('Entre 30,01 y 49,99%');            
                    }else if(porcentajePcl > 50){
                        $("#rango_pcl").val('Mayor a 50%');            
                    }
                    
                    // Calculo del monto de indemnizacion (meses)
                    if (porcentajePcl == 0) {
                        $("#monto_inde").val(0);
                    } else {
                        var montoIndemnizacion = ((porcentajePcl / 2) - 0.5);
                        $("#monto_inde").val(montoIndemnizacion.toFixed(2));                    
                    }
                });

            }); 
            
            if ($.trim(total_deficiencia) == 0 && $.trim(dicapacidad_total) == 0 && $.trim(minusvalia_total) == 0) {
                porcentajePcl = 0;
            }else if($.trim(total_deficiencia) > 0 && $.trim(dicapacidad_total) == 0 && $.trim(minusvalia_total) == 0){
                porcentajePcl = Number(total_deficiencia);
            }else if($.trim(total_deficiencia) == 0 && $.trim(dicapacidad_total) > 0 && $.trim(minusvalia_total) == 0){
                porcentajePcl = Number(dicapacidad_total);
            }else if($.trim(total_deficiencia) == 0 && $.trim(dicapacidad_total) == 0 && $.trim(minusvalia_total) > 0){
                porcentajePcl = Number(minusvalia_total);
            }else if($.trim(total_deficiencia) > 0 && $.trim(dicapacidad_total) > 0 && $.trim(minusvalia_total) == 0){
                porcentajePcl = Number(total_deficiencia) + Number(dicapacidad_total);
            }else if($.trim(total_deficiencia) > 0 && $.trim(dicapacidad_total) == 0 && $.trim(minusvalia_total) > 0){
                porcentajePcl = Number(total_deficiencia) + Number(minusvalia_total);
            }else if($.trim(total_deficiencia) == 0 && $.trim(dicapacidad_total) > 0 && $.trim(minusvalia_total) > 0){
                porcentajePcl = Number(dicapacidad_total) + Number(minusvalia_total);                
            }else if($.trim(total_deficiencia) > 0 && $.trim(dicapacidad_total) > 0 && $.trim(minusvalia_total) > 0){                   
                porcentajePcl = Number(total_deficiencia) + Number(dicapacidad_total) + Number(minusvalia_total);                
            }

            //console.log(porcentajePcl);
            $("#porcentaje_pcl").val(porcentajePcl.toFixed(2));
            if (porcentajePcl == 0) {
                $("#rango_pcl").val('PCL 0');
            }else if (porcentajePcl < 5) {
                $("#rango_pcl").val('Menor al 5%');
            }else if(porcentajePcl >= 5 && porcentajePcl <= 14.99){
                $("#rango_pcl").val('Entre 5 y 14,99%');            
            }else if(porcentajePcl >= 15 && porcentajePcl <= 29.99){
                $("#rango_pcl").val('Entre 15 y 29,99%');            
            }else if(porcentajePcl >= 30 && porcentajePcl <= 49.99){
                $("#rango_pcl").val('Entre 30,01 y 49,99%');            
            }else if(porcentajePcl > 50){
                $("#rango_pcl").val('Mayor a 50%');            
            }

            // Calculo del monto de indemnizacion (meses)
            if (porcentajePcl == 0) {
                $("#monto_inde").val(0);
            } else {
                var montoIndemnizacion = ((porcentajePcl / 2) - 0.5);
                $("#monto_inde").val(montoIndemnizacion.toFixed(2));                    
            }

        }else if(definirDecreto_deficiencia == 2) {            
            porcentajePcl = 0;   
            //console.log(porcentajePcl);
            $("#porcentaje_pcl").val(porcentajePcl.toFixed(2));
            if (porcentajePcl == 0) {
                $("#rango_pcl").val('PCL 0');
            }else if (porcentajePcl < 5) {
                $("#rango_pcl").val('Menor al 5%');
            }else if(porcentajePcl >= 5 && porcentajePcl <= 14.99){
                $("#rango_pcl").val('Entre 5 y 14,99%');            
            }else if(porcentajePcl >= 15 && porcentajePcl <= 29.99){
                $("#rango_pcl").val('Entre 15 y 29,99%');            
            }else if(porcentajePcl >= 30 && porcentajePcl <= 49.99){
                $("#rango_pcl").val('Entre 30,01 y 49,99%');            
            }else if(porcentajePcl > 50){
                $("#rango_pcl").val('Mayor a 50%');            
            }        
            // Calculo del monto de indemnizacion (meses)
            if (porcentajePcl == 0) {
                $("#monto_inde").val(0);
            } else {
                var montoIndemnizacion = ((porcentajePcl / 2) - 0.5);
                $("#monto_inde").val(montoIndemnizacion.toFixed(2));                    
            }
        }else{            
            porcentajePcl = 0;      
            //console.log(porcentajePcl);
            $("#porcentaje_pcl").val(porcentajePcl.toFixed(2));
            if (porcentajePcl == 0) {
                $("#rango_pcl").val('PCL 0');
            }else if (porcentajePcl < 5) {
                $("#rango_pcl").val('Menor al 5%');
            }else if(porcentajePcl >= 5 && porcentajePcl <= 14.99){
                $("#rango_pcl").val('Entre 5 y 14,99%');            
            }else if(porcentajePcl >= 15 && porcentajePcl <= 29.99){
                $("#rango_pcl").val('Entre 15 y 29,99%');            
            }else if(porcentajePcl >= 30 && porcentajePcl <= 49.99){
                $("#rango_pcl").val('Entre 30,01 y 49,99%');            
            }else if(porcentajePcl > 50){
                $("#rango_pcl").val('Mayor a 50%');            
            }      
            // Calculo del monto de indemnizacion (meses)
            if (porcentajePcl == 0) {
                $("#monto_inde").val(0);
            } else {
                var montoIndemnizacion = ((porcentajePcl / 2) - 0.5);
                $("#monto_inde").val(montoIndemnizacion.toFixed(2));                    
            }
        }  
        // validacion para mantener actualizado el porcentaje pcl, rango y monto
        var ActualizarDecreto = $('#ActualizarDecreto');

        if (ActualizarDecreto.length > 0) {
            var Decreto_pericial = $('#decreto_califi').val();
            var Id_EventoDecreto = $('#Id_Evento_decreto').val();
            var Id_ProcesoDecreto = $('#Id_Proceso_decreto').val();
            var Id_Asignacion_Dcreto  = $('#Id_Asignacion_decreto').val();
            var sumas_combinada = $('#suma_combinada').val();
            var Totales_Deficiencia50 = $('#Total_Deficiencia50').val();            
            var porcentaje_pcl = $('#porcentaje_pcl').val();
            var rango_pcl = $('#rango_pcl').val();
            var monto_inde = $('#monto_inde').val();
            var bandera_Pcl_rango_monto = 'bandera_Pcl_rango_monto';
            var datos_dictamenPericialPcl_rango_monto={
                '_token': token,            
                'Decreto_pericial':Decreto_pericial,
                'Id_EventoDecreto':Id_EventoDecreto,
                'Id_ProcesoDecreto':Id_ProcesoDecreto,
                'Id_Asignacion_Dcreto':Id_Asignacion_Dcreto,            
                'porcentaje_pcl':porcentaje_pcl,
                'rango_pcl':rango_pcl,
                'monto_inde':monto_inde,  
                'sumas_combinada':sumas_combinada,
                'Totales_Deficiencia50':Totales_Deficiencia50,
                'bandera_dictamen_pericial' :bandera_Pcl_rango_monto,
            }
                 
            $.ajax({
                type: 'POST',
                url:'/guardardictamenesPericial',
                data: datos_dictamenPericialPcl_rango_monto,
            });

        }
        
        var tercerapersona = $("#requiere_persona");
        var tomadecisiones = $("#requiere_decisiones_persona");
        var dispositivoapoyo = $("#requiere_dispositivo_apoyo");
        var justiDependencia = $("#justiDependencia");
        function mostrarOcultarDependencia() {
            if (tercerapersona.is(":checked") || tomadecisiones.is(":checked") || dispositivoapoyo.is(":checked")) {
                $("#justiDependencia").val("");
                justiDependencia.removeClass('d-none');
            } else {
                justiDependencia.addClass('d-none');
            }
        }        
        tercerapersona.change(mostrarOcultarDependencia);
        tomadecisiones.change(mostrarOcultarDependencia);
        dispositivoapoyo.change(mostrarOcultarDependencia);

        if ($('input[type="checkbox"].dependencia_justificacion').is(':checked')) {
            justiDependencia.removeClass('d-none');
        }
        // habilitar botones de la vista antes de guardar
        var valorFecha = $('input[type="date"].f_estructura_pericial').val();        
        if (typeof valorFecha === "undefined") {
            $('#ActualizarLaboralActivo').prop('disabled', false);
            $('#GuardarLaboralActivo').prop('disabled', false);
            $('#btn_abrir_modal_agudeza').prop('disabled', false);
            $('#btn_abrir_modal_auditivo').prop('disabled', false);
            $('#guardar_datos_deficiencia_alteraciones').prop('disabled', false);
            $('#guardar_datos_cie10').prop('disabled', false);
            $('#guardar_datos_examenes').prop('disabled', false);           
            $('#ActualizarDecreto').prop('disabled', false);
            $('#ActualizarRolOcupacional').prop('disabled', false);
            $('#GuardarRolOcupacional').prop('disabled', false);            
            $('#ActualizarLibros2_3').prop('disabled', false); 
            $('#GuardrDictamenPericial').prop('disabled', true);  

        }else if(valorFecha !== ""){
            // seccion comite interdisciplinario
            if (idRol != 7) {
                $('#div_comite_interdisciplinario').removeClass('d-none');
            } 
            $('#div_comunicado_dictamen_oficioremisorio').removeClass('d-none');
        }

        var textareaenfermedadactual = document.querySelector('#descripcion_enfermedad');
        var enfermedadactual = textareaenfermedadactual.value;
        
        if(enfermedadactual == ""){
            $('#ActualizarLaboralActivo').prop('disabled', true);
            $('#GuardarLaboralActivo').prop('disabled', true);            
            $('#btn_abrir_modal_agudeza').prop('disabled', true);
            $('#btn_abrir_modal_agudeza').css('cursor', 'not-allowed');
            $('#btn_abrir_modal_auditivo').prop('disabled', true);
            $('#btn_abrir_modal_auditivo').css('cursor', 'not-allowed'); 
            $('#guardar_datos_deficiencia_alteraciones').prop('disabled', true);
            $('#guardar_deficiencias_DecretoCero').prop('disabled', true);
            $('#guardar_datos_cie10').prop('disabled', true);
            $('#guardar_datos_examenes').prop('disabled', true); 
            $('#ActualizarRolOcupacional').prop('disabled', true);
            $('#GuardarRolOcupacional').prop('disabled', true);            
            $('#ActualizarLibros2_3').prop('disabled', true);  
            $('#GuardarLibros2_3').prop('disabled', true);                        
            $('#GuardrDictamenPericial').prop('disabled', true);  
        }
        
        if(enfermedadactual !== "" && valorFecha !== ""){
            $('#origen_firme').prop('disabled', true);
            $('#origen_cobertura').prop('disabled', true);
            $('#decreto_califi').prop('disabled', true); 
        }else if(enfermedadactual !== ""){
            $('#origen_firme').prop('disabled', true);
            $('#origen_cobertura').prop('disabled', true);
            $('#decreto_califi').prop('disabled', true);
            $('#GuardrDictamenPericial').prop('disabled', false);  
        }

        // Desmarcar radio buttons tablas de rol ocupacional al momento de ingresar una nueva insercion
        var selectortablasrolocupacional = $('#poblacion_califi');        
        var total_tabla12rolocu = $('#total_tabla12');
        var total_tabla13rolocu = $('#total_tabla13');
        var total_tabla14rolocu = $('#total_tabla14');        
        selectortablasrolocupacional.on('change', function() {
            var valordelselect = selectortablasrolocupacional.val();
            if (valordelselect == 75) {
                $("input[type='radio'][name='roles_ocupacionales_juego']").prop("checked", false);                
                total_tabla13rolocu.val("");
                $("input[type='radio'][name='roles_ocupacionales_adultos']").prop("checked", false);                
                total_tabla14rolocu.val("");
            }else if(valordelselect == 76){
                $("input[type='radio'][name='mantiene_postura']").prop("checked", false);                                
                $("input[type='radio'][name='actividad_espontanea']").prop("checked", false);                                
                $("input[type='radio'][name='sujeta_cabeza']").prop("checked", false);                                
                $("input[type='radio'][name='sienta_apoyo']").prop("checked", false);                                
                $("input[type='radio'][name='sobre_mismo']").prop("checked", false);                                
                $("input[type='radio'][name='sentado_sin_apoyo']").prop("checked", false);                                
                $("input[type='radio'][name='tumbado_sentado']").prop("checked", false);                                
                $("input[type='radio'][name='pie_apoyo']").prop("checked", false);                                
                $("input[type='radio'][name='pasos_apoyo']").prop("checked", false);                                
                $("input[type='radio'][name='mantiene_sin_apoyo']").prop("checked", false);                                
                $("input[type='radio'][name='anda_solo']").prop("checked", false);                                
                $("input[type='radio'][name='empuja_pelota']").prop("checked", false);                                
                $("input[type='radio'][name='sorteando_obstaculos']").prop("checked", false);                                
                $("input[type='radio'][name='succiona']").prop("checked", false);                                
                $("input[type='radio'][name='fija_mirada']").prop("checked", false);                                
                $("input[type='radio'][name='trayectoria_objeto']").prop("checked", false);                                
                $("input[type='radio'][name='sostiene_sonajero']").prop("checked", false);                                
                $("input[type='radio'][name='hacia_objeto']").prop("checked", false);                                
                $("input[type='radio'][name='sostiene_objeto']").prop("checked", false);                                
                $("input[type='radio'][name='abre_cajones']").prop("checked", false);                                
                $("input[type='radio'][name='bebe_solo']").prop("checked", false);                                
                $("input[type='radio'][name='quita_prenda']").prop("checked", false);                                
                $("input[type='radio'][name='espacios_casa']").prop("checked", false);                                
                $("input[type='radio'][name='imita_trazaso']").prop("checked", false);                                
                $("input[type='radio'][name='abre_puerta']").prop("checked", false);
                total_tabla12rolocu.val("");
                $("input[type='radio'][name='roles_ocupacionales_adultos']").prop("checked", false);                
                total_tabla14rolocu.val("");
            }else if(valordelselect == 77){
                $("input[type='radio'][name='mantiene_postura']").prop("checked", false);                                
                $("input[type='radio'][name='actividad_espontanea']").prop("checked", false);                                
                $("input[type='radio'][name='sujeta_cabeza']").prop("checked", false);                                
                $("input[type='radio'][name='sienta_apoyo']").prop("checked", false);                                
                $("input[type='radio'][name='sobre_mismo']").prop("checked", false);                                
                $("input[type='radio'][name='sentado_sin_apoyo']").prop("checked", false);                                
                $("input[type='radio'][name='tumbado_sentado']").prop("checked", false);                                
                $("input[type='radio'][name='pie_apoyo']").prop("checked", false);                                
                $("input[type='radio'][name='pasos_apoyo']").prop("checked", false);                                
                $("input[type='radio'][name='mantiene_sin_apoyo']").prop("checked", false);                                
                $("input[type='radio'][name='anda_solo']").prop("checked", false);                                
                $("input[type='radio'][name='empuja_pelota']").prop("checked", false);                                
                $("input[type='radio'][name='sorteando_obstaculos']").prop("checked", false);                                
                $("input[type='radio'][name='succiona']").prop("checked", false);                                
                $("input[type='radio'][name='fija_mirada']").prop("checked", false);                                
                $("input[type='radio'][name='trayectoria_objeto']").prop("checked", false);                                
                $("input[type='radio'][name='sostiene_sonajero']").prop("checked", false);                                
                $("input[type='radio'][name='hacia_objeto']").prop("checked", false);                                
                $("input[type='radio'][name='sostiene_objeto']").prop("checked", false);                                
                $("input[type='radio'][name='abre_cajones']").prop("checked", false);                                
                $("input[type='radio'][name='bebe_solo']").prop("checked", false);                                
                $("input[type='radio'][name='quita_prenda']").prop("checked", false);                                
                $("input[type='radio'][name='espacios_casa']").prop("checked", false);                                
                $("input[type='radio'][name='imita_trazaso']").prop("checked", false);                                
                $("input[type='radio'][name='abre_puerta']").prop("checked", false);
                total_tabla12rolocu.val("");
                $("input[type='radio'][name='roles_ocupacionales_juego']").prop("checked", false);                
                total_tabla13rolocu.val("");
            }            
        });  

        var valorOrigen = $('#origen_firme').val();
        var valorCobertura = $('#origen_cobertura').val();
        var valorDecreto = $('#decreto_califi').val();

        if (valorOrigen == 48 &&  valorCobertura == 50 && valorDecreto == 1 || valorDecreto == 2 || valorDecreto == 3) {            
            $('#botonNoDecrecto').addClass('d-none');
        }

    }); 

    // Captura del nombre del usuario que marca el checkbox Visar
    var NombreUsuario = $("#NombreUsuario").val();
    var Visar = $("#visar");

    Visar.change(function(){
        if ($(this).prop('checked')) {
            $("#profesional_comite").val(NombreUsuario);            
        } else {            
            $("#profesional_comite").val('');            
        }
    });    
    
    //Captura Formulario Comite Interdisciplinario
    $('#form_comite_interdisciplinario').submit(function (e){
        e.preventDefault();              
       
        var Id_EventoDecreto = $('#Id_Evento_decreto').val();
        var Id_ProcesoDecreto = $('#Id_Proceso_decreto').val();
        var Id_Asignacion_Dcreto  = $('#Id_Asignacion_decreto').val();
        var visar = $('#visar').val();
        var profesional_comite = $('#profesional_comite').val();
        var f_visado_comite = $('#f_visado_comite').val();
       
        var datos_comiteInterdisciplianario={
            '_token': token,            
            'Id_EventoDecreto':Id_EventoDecreto,
            'Id_ProcesoDecreto':Id_ProcesoDecreto,
            'Id_Asignacion_Dcreto':Id_Asignacion_Dcreto,
            'visar':visar,
            'profesional_comite':profesional_comite,
            'f_visado_comite':f_visado_comite,
        }

        $.ajax({    
            type:'POST',
            url:'/guardarcomitesinterdisciplinario',
            data: datos_comiteInterdisciplianario,
            success: function(response){
                if (response.parametro == 'insertar_comite_interdisciplinario') {
                    $('#GuardarComiteInter').prop('disabled', true);
                    $('#div_alerta_comiteInter').removeClass('d-none');
                    $('.alerta_comiteInter').append('<strong>'+response.mensaje+'</strong>');                                            
                    setTimeout(function(){
                        $('#div_alerta_comiteInter').addClass('d-none');
                        $('.alerta_comiteInter').empty();   
                        location.reload();
                    }, 3000);   
                }
            }          
        })
    }) 
    // habilitar o deshabilitar botones o etiquetas de toda la vista
    var profesional_comite = $("#profesional_comite").val();
    if (profesional_comite !== '' && idRol != 6) {
        // botones
        $('#ActualizarLaboralActivo').prop('disabled', true);
        $('#GuardarLaboralActivo').prop('disabled', true);            
        $('#btn_abrir_modal_agudeza').prop('disabled', true);
        $('#btn_abrir_modal_agudeza').css('cursor', 'not-allowed');
        $('#btn_guardar_agudeza').prop('disabled', true);            
        $('#btn_abrir_modal_auditivo').prop('disabled', true);
        $('#btn_abrir_modal_auditivo').css('cursor', 'not-allowed'); 
        $('#guardar_datos_deficiencia_alteraciones').prop('disabled', true);
        $('#guardar_deficiencias_DecretoCero').prop('disabled', true);
        $('#guardar_deficiencias_Decreto3').prop('disabled', true);
        $('#guardar_datos_cie10').prop('disabled', true);
        $('#guardar_datos_examenes').prop('disabled', true);           
        $('#ActualizarDecreto').prop('disabled', true);
        $('#ActualizarRolOcupacional').prop('disabled', true);
        $('#GuardarRolOcupacional').prop('disabled', true);            
        $('#ActualizarLibros2_3').prop('disabled', true);  
        $('#GuardarLibros2_3').prop('disabled', true);                        
        $('#GuardrDictamenPericial').prop('disabled', true);  
        //etiquetas a o botones de eliminar filas
        $("a[id^='btn_remover_examen_fila_examenes_']").css({
            cursor: "not-allowed",
            "pointer-events": "none"
        });
        $("a[id^='btn_remover_diagnosticos_moticalifi']").css({
            cursor: "not-allowed",
            "pointer-events": "none"
        });
        $("a[id^='btn_remover_deficiencia_alteraciones']").css({
            cursor: "not-allowed",
            "pointer-events": "none"
        });
        $("a[id^='btn_remover_deficiencias_decretocero_']").css({
            cursor: "not-allowed",
            "pointer-events": "none"
        });            
        $("a[id^='btn_remover_deficiencias_decreto3_']").css({
            cursor: "not-allowed",
            "pointer-events": "none"
        }); 
        $("a[id^='btn_remover_examen_fila_agudeza']").css({
            cursor: "not-allowed",
            "pointer-events": "none"
        });
        $("a[id^='btn_remover_fila_']").css({
            cursor: "not-allowed",
            "pointer-events": "none"
        });    
        
        // checkboxes
        $("input[id^='dx_principal_diganostico_']").prop('disabled', true);            
        $("input[id^='dx_principal_deficiencia_alteraciones_']").prop('disabled', true);            
        $("input[id^='dx_principal_deficiencia_auditiva_']").prop('disabled', true);                        
        $("input[id^='dx_principal_deficiencia_visual_']").prop('disabled', true);

        // formulario corrrespondencia
        $("#GuardarComiteInter").prop('disabled', true);
        $("#div_correspondecia").removeClass('d-none');
    }

    // Validar cual de los oficios esta marcado

    var oficiopclcorres = $('#oficiopcl');
    var oficioincacorres = $('#oficioinca');
    
    oficiopclcorres.change(function(){
        if ($(this).prop('checked')) {
            oficioincacorres.prop('disabled', true);            
        }else{
            oficioincacorres.prop('disabled', false);            
        }
    });

    oficioincacorres.change(function(){
        if ($(this).prop('checked')) {
            oficiopclcorres.prop('disabled', true);            
        }else{
            oficiopclcorres.prop('disabled', false);            
        }
    });

    if (oficiopclcorres.prop('checked')) {
        oficioincacorres.prop('disabled', true);                    
    }
    if (oficioincacorres.prop('checked')) {
        oficiopclcorres.prop('disabled', true);                    
    }

    // validar si el otro destinatario principal esta marcado

    var otrodestinariop = $("#otrodestinariop");
    otrodestinariop.change(function(){
        if ($(this).prop('checked')) {     
            $('#tipo_destinatario_principal').empty();

            $(".tipo_destinatario_principal").select2({
                placeholder:"Seleccione una opción",
                allowClear:false
            });            
            
            let datos_lista_tipo_destinatario = {
                '_token': token,
                'parametro' : "listado_destinatarios"
            };
            $.ajax({
                type:'POST',
                url:'/selectoresJuntasControversia',
                data: datos_lista_tipo_destinatario,
                success:function(data) {
                    $('#tipo_destinatario_principal').append('<option value="" selected>Seleccione</option>');
                    let IdCalifi_contro = $('select[name=tipo_destinatario_principal]').val();
                    let partecontro = Object.keys(data);
                    for (let i = 0; i < partecontro.length; i++) {
                        if (data[partecontro[i]]['Id_solicitante'] == $('#db_tipo_destinatario_principal').val()) {  
                            $('#tipo_destinatario_principal').append('<option value="'+data[partecontro[i]]["Id_solicitante"]+'" selected>'+data[partecontro[i]]["Solicitante"]+'</option>');
                        }else{                    
                            $('#tipo_destinatario_principal').append('<option value="'+data[partecontro[i]]["Id_solicitante"]+'">'+data[partecontro[i]]["Solicitante"]+'</option>');
                        }
                    }
                }
                
            });

            if($('select[name=nombre_destinatariopri]').val() !== ''){
                $('#nombre_destinatariopri').prop('disabled', false);
                let id_solicitante = $('#db_tipo_destinatario_principal').val();
                let datos_listado_nombre_solicitante = {
                    '_token': token,
                    'parametro' : "nombre_destinatariopri",
                    'id_solicitante': id_solicitante
                };
                
                $.ajax({
                    type:'POST',
                    url:'/selectoresJuntasControversia',
                    data: datos_listado_nombre_solicitante,
                    success:function(data) {
                        let nombre_solicitanteEdicion = $('select[name=nombre_destinatariopri]').val();                
                        let claves = Object.keys(data);
                        for (let i = 0; i < claves.length; i++) {
                            if (data[claves[i]]['Id_Nombre_solicitante'] == $('#db_nombre_destinatariopri').val()) {  
                                $('#nombre_destinatariopri').append('<option value="'+data[claves[i]]["Id_Nombre_solicitante"]+'" selected>'+data[claves[i]]["Nombre_solicitante"]+'</option>');
                            }else{                    
                                $('#nombre_destinatariopri').append('<option value="'+data[claves[i]]["Id_Nombre_solicitante"]+'">'+data[claves[i]]["Nombre_solicitante"]+'</option>');
                            }
                        }
                    }
                });
            }                      
            
            var destinatario_principal_select = $('#db_tipo_destinatario_principal').val();
            if (destinatario_principal_select == 8) {
                $('#div_tipo_destinatario_principal').slideDown('slow');            
                $('#div_datos_otro_destinatario').slideDown('slow');
                $('#div_nombre_destinatariopri').slideUp('up');                
            }else if(destinatario_principal_select == 4) {
                $('#div_tipo_destinatario_principal').slideDown('slow');            
                $('#div_datos_otro_destinatario').slideUp('up');
                $('#div_nombre_destinatariopri').slideUp('up');
                $('#div_nombre_destinatariopri_afi_').slideDown('slow');
            }else if(destinatario_principal_select == 5) {
                $('#div_tipo_destinatario_principal').slideDown('slow');            
                $('#div_datos_otro_destinatario').slideUp('up');
                $('#div_nombre_destinatariopri').slideUp('up');
                $('#div_nombre_destinatariopri_empl').slideDown('slow');
            }
            else {
                $('#tipo_destinatario_principal').prop('required', true);
                $('#nombre_destinatariopri').prop('required', true);
                $('#div_tipo_destinatario_principal').slideDown('slow');            
                $('#div_nombre_destinatariopri').slideDown('slow');
                $('#div_datos_otro_destinatario').slideUp('up');
            }             
        } else {
            $('#div_tipo_destinatario_principal').slideUp('up');
            $('#tipo_destinatario_principal').prop('required', false);
            $('#tipo_destinatario_principal').empty();
            $('#nombre_destinatariopri').prop('required', false); 
            $('#div_nombre_destinatariopri').slideUp('up');            
            $('#div_datos_otro_destinatario').slideUp('up'); 
            $('#div_nombre_destinatariopri_afi_').slideUp('up');
            $('#div_nombre_destinatariopri_empl').slideUp('up');
        }
    });

    // validar si tipo de destinatario es igual a otro

    var select_destinatario_principal = $('#tipo_destinatario_principal');
    var select_destinatario_principal2 = $('#db_tipo_destinatario_principal').val();
    
    select_destinatario_principal.change(function() {
       if ($(this).val() == 8) {
            $('#nombre_destinatariopri').prop('required', false); 
            $('#div_nombre_destinatariopri').slideUp('up');
            $('#div_datos_otro_destinatario').slideDown('slow');
            $('#div_nombre_destinatariopri_afi_').slideUp('up');
            $('#div_nombre_destinatariopri_empl').slideUp('up');
       }else if ($(this).val() == 4) {
            $('#nombre_destinatariopri').prop('required', false); 
            $('#div_nombre_destinatariopri').slideUp('up');
            $('#div_datos_otro_destinatario').slideUp('up');
            $('#div_nombre_destinatariopri_afi_').slideDown('slow');
            $('#div_nombre_destinatariopri_empl').slideUp('up');
        }else if ($(this).val() == 5) {
            $('#nombre_destinatariopri').prop('required', false); 
            $('#div_nombre_destinatariopri').slideUp('up');
            $('#div_datos_otro_destinatario').slideUp('up');
            $('#div_nombre_destinatariopri_empl').slideDown('slow');
            $('#div_nombre_destinatariopri_afi_').slideUp('up');
        }else {
            $('#nombre_destinatariopri').prop('required', true); 
            $('#div_nombre_destinatariopri').slideDown('slow');
            $('#div_datos_otro_destinatario').slideUp('up');  
            $('#div_nombre_destinatariopri_afi_').slideUp('up');
            $('#div_nombre_destinatariopri_empl').slideUp('up');
        } 
    });

    if(otrodestinariop.prop('checked')){           
        $('#div_tipo_destinatario_principal').slideDown('slow');
        if (select_destinatario_principal2 == 8) {
            $('#div_datos_otro_destinatario').slideDown('slow');
            $('#div_nombre_destinatariopri').slideUp('up');
        }else if(select_destinatario_principal2 == 4) {
            $('#div_datos_otro_destinatario').slideUp('up');
            $('#div_nombre_destinatariopri').slideUp('up');
            $('#div_nombre_destinatariopri_afi_').slideDown('slow');
        }else if(select_destinatario_principal2 == 5) {
            $('#div_datos_otro_destinatario').slideUp('up');
            $('#div_nombre_destinatariopri').slideUp('up');
            $('#div_nombre_destinatariopri_empl').slideDown('slow');
        }
        else {
            $('#div_nombre_destinatariopri').slideDown('slow');
            $('#div_datos_otro_destinatario').slideUp('up');
        }        
    }else{
        $('#div_nombre_destinatariopri').slideUp('up'); 
        $('#div_datos_otro_destinatario').slideUp('up');
    }

    // validar si la Junta regional esta marcada
    
    var juntaregionalCi = $("#jrci");
    juntaregionalCi.change(function() {
        if ($(this).prop('checked')) {
            $('#div_cual').slideDown('slow');
            $('#cual').prop('required', true);
        }else{
            $('#div_cual').slideUp('up');  
            $('#cual').prop('required', false);
        }
    });

    if (juntaregionalCi.prop('checked')) {
        $('#div_cual').slideDown('slow');        
    }

    // validar asunto

    var asuntocorrespondencia = $("#Asunto").val();
    if (asuntocorrespondencia !== '') {
        $("#div_correspondecia").addClass('d-none');
    }

    // Habilitar formulario de correspondencia

    $(document).on('click','#editar_correspondencia',function(){
        $("#div_correspondecia").removeClass('d-none');
    });

    //Cargar comunicado
    $('#cargarComunicado').click(function(){
        if(!$('#cargue_comunicados')[0].files[0]){
            return $(".cargueundocumentoprimero").removeClass('d-none');
        }
        $(".cargueundocumentoprimero").addClass('d-none');
        var archivo = $('#cargue_comunicados')[0].files[0];
        var documentName = archivo.name;
        var formData = new FormData($('form')[0]);
        formData.append('cargue_comunicados', archivo);
        formData.append('token', $("input[name='_token']").val());
        formData.append('ciudad', 'N/A');
        formData.append('Id_evento',$("#Id_Evento_decreto").val());
        formData.append('Id_asignacion',$('#Id_Asignacion_decreto').val());
        formData.append('Id_procesos',$("#Id_Proceso_decreto").val());
        formData.append('fecha_comunicado2',null);
        formData.append('radicado2',$('#radicado_comunicado_manual').val());
        formData.append('cliente_comunicado2','N/A');
        formData.append('nombre_afiliado_comunicado2',$('#nombre_afiliado').val());
        formData.append('tipo_documento_comunicado2','N/A');
        formData.append('identificacion_comunicado2',$('#identificacion').val());
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
        formData.append('modulo_creacion','calificacionTecnicaPCL');
        formData.append('modulo','Comunicados calificación tecnica');
        $.ajax({
            type:'POST',
            url:'/registrarComunicadoOrigen',
            data: formData,   
            processData: false,
            contentType: false,    
            beforeSend:  function() {
                $("#cargarComunicado").addClass("descarga-deshabilitada");
            },     
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
            },
            complete:function(){
                $("#cargarComunicado").removeClass("descarga-deshabilitada");
            }
        });  
    }); 

    let selectores_notificacion = {
        '_token': $('input[name=_token]').val(),
        'parametro': 'EstadosNotificaion'
    }

    let opciones_Notificacion = [];

    $.ajax({
        type: 'POST',
        url: '/cargarselectores',
        data: selectores_notificacion,
        success: function (data) {
            $.each(data, function (index, item) {
                //Establecemos el color que tendra le texto de cada opcion segun corresponda
                let color = (()=>{
                    switch(item.Nombre_parametro){
                        case 'Pendiente':
                            return '#000000'; //negro
                            break;
                        case 'No notificar':
                            return '#CBCBCB'; //Gris
                            break;
                        case 'Devuelto':
                            return '#E70000'; //Rojo
                            break;
                        case 'Notificado efectivamente':
                            return '#00E738'; //Verde
                            break;
                        case 'Notificado parcialmente':
                            return '#00ACE7'; //Azul
                            break;
                    }
                })();
                
                /**@var opciones_Notificacion Corresponde a las propiedades del elemento */
                opciones_Notificacion.push({
                    opciones: `<option value="${item.Id_Parametro}">${item.Nombre_parametro}</option>`,
                    id:item.Id_Parametro,
                    texto: item.Nombre_parametro,
                    color: color
                });
            });

        }
    });

    //pbs014
    $("#listado_comunicados_clpcl tbody tr").each(function() {
        let $tableComunicados = $(this);
        let radicado = $tableComunicados.find("td").eq(0).text();
        let StatusSeleccionado = $("#status_default_" + radicado).val();
        let NotaComunicado = $("#Nota_comunicado_" + radicado).val();
        let estado_correspondencia =  $("#Estado_Correspondencia_" + radicado).val();
        
        let info_comunicado = {
            '_token': $('input[name=_token]').val(),
            'bandera': 'info_comunicado',
            'radicado': radicado,
            'id_asignacion': $("#newIdAsignacion").val(),
        }
        let camposNotificacion = {};
        $.ajax({
            type:'POST',
            url: '/historialComunicadoPcl',
            data: info_comunicado,
            success: function(data){
                //datos para llenar la modal de correspondencia
                let data_comunicado = {
                    Destinatario: data[0].Destinatario,
                    Id_Comunicado: data[0].Id_Comunicado,
                    Agregar_copia: data[0].Agregar_copia,
                    Correspondencia: data[0].Correspondencia,
                    ID_evento: data[0].ID_evento,
                    Id_Asignacion: data[0].Id_Asignacion,
                    Id_proceso: data[0].Id_proceso,
                    Anexos: data[0].Anexos,
                    Correspondencia: data[0].Correspondencia,
                    Tipo_descarga: data[0].Tipo_descarga,
                    Nombre_afiliado: data[0].Nombre_afiliado,
                    N_identificacion: data[0].N_identificacion,
                    Id_Destinatarios: data[0].Id_Destinatarios,
                    Estado_correspondencia: estado_correspondencia,
                }

                let controlComunicados = {
                    deshabilitar_selector: estado_correspondencia == '1' || (StatusSeleccionado == 359 || StatusSeleccionado == 358) ? false: true,
                    deshabilitar_edicion: estado_correspondencia == '1' || (StatusSeleccionado == 359 || StatusSeleccionado == 358) ? '' : 'pointer-events: none; color: gray;'
                }

                // Extraer el contenido de la columna de acciones y limpiar la columna
                let acciones = $tableComunicados.find("td").eq(4).html();

                //validamos si el check verde debe agregarse por el idRol
                let checkAccion = `<a href="javascript:void(0);" id="editar_comunicado" data-radicado="${radicado}" style="${controlComunicados.deshabilitar_edicion}"><i class="fa fa-sm fa-check text-success"></i></a></td>`;
                if(idRol === '7'){
                    checkAccion = `</td>`
                }
                
                //Obtenemos los nuevos campos a incluir en la tabla
                camposNotificacion = getHistorialNotificacion(radicado,NotaComunicado,opciones_Notificacion,data_comunicado);

                //Agregamos campo destinatario
                $tableComunicados.find("td").eq(4).removeAttr('style').removeClass().empty().html(camposNotificacion.Destinatarios);
                
                //demas campos
                $tableComunicados.append(`
                    <td>${camposNotificacion.Estado_General}</td>
                    <td >${camposNotificacion.Nota_Comunicados}</td>
                    <td>${acciones}${checkAccion}`
                );
                // Estilo de la columna de acciones
                $tableComunicados.find("td").eq(7).css({
                        'display': 'flex',
                        'padding': '2px',
                        'flex-direction': 'row',
                        'justify-content': 'space-around'
                });

                //Si el evento no se encuentra en la bandeja de notificaciones ocultamos la columna destinatarios
                ubicacionEvento().then(status => {
                    if(!status){
                        $("#listado_comunicados_clpcl thead th").eq(4).hide(); //cabecera
                        $tableComunicados.find("td").eq(4).hide(); // fila
                    }else{
                        if(estado_correspondencia == '1' || (StatusSeleccionado == 359 || StatusSeleccionado == 358)){
                            $("#btn_reemplazar_archivo_"+data_comunicado.Id_Comunicado).prop('disabled',false);
                        }else{
                            $("#btn_reemplazar_archivo_"+data_comunicado.Id_Comunicado).prop('disabled',true);
                        }
                    }
                });
                
                $(`#status_notificacion_${radicado}`).select2();
                setTimeout(() => {
                    config_Select2(radicado, controlComunicados, opciones_Notificacion, StatusSeleccionado);
                }, 0);
                
            },
        });
        
    });

    function cargarSelectorModalCorrespondencia(){
        //Listado de opciones de estado de notificación Correspondencia
        let selectores_notificacion_correspondencia = {
            '_token': $('input[name=_token]').val(),
            'parametro': 'EstadosNotificacionCorrespondencia'
        }
        $.ajax({
            type: 'POST',
            url: '/selectoresJuntas',
            data: selectores_notificacion_correspondencia,
            beforeSend:  function() {
                $("#btn_guardar_actualizar_correspondencia").addClass("descarga-deshabilitada");
            },
            success: function (data) {
                let optionSelected = data.find(finder => finder.Id_Parametro === 362);
                $("#modalCorrespondencia #state_notificacion").val(optionSelected?.Id_Parametro);
                $('#state_notificacion').empty();
                $('#state_notificacion').append('<option value="'+optionSelected?.Id_Parametro+'" selected>'+optionSelected?.Nombre_parametro+'</option>');
                let SelectorModalCorrespondencia = $('select[name=state_notificacion]').val();
                let formaenviogenerarcomunicado = Object.keys(data);
                for (let i = 0; i < formaenviogenerarcomunicado.length; i++) {
                    if (data[formaenviogenerarcomunicado[i]]['Id_Parametro'] != SelectorModalCorrespondencia) {
                        $('#state_notificacion').append('<option value="'+data[formaenviogenerarcomunicado[i]]['Id_Parametro']+'">'+data[formaenviogenerarcomunicado[i]]['Nombre_parametro']+'</option>');
                    }                
                }                                     
            },
            complete: function(){
                $("#btn_guardar_actualizar_correspondencia").removeClass("descarga-deshabilitada");
            }
        });
    }

    let correspondencia_array = [];
    $(document).on('click', "#CorrespondenciaNotificacion", async function() {
        //Reestablecer modal
        cleanModalCorrespondencia();
        //Cargar selectores modal con Pendiente como valor por defecto
        cargarSelectorModalCorrespondencia();
        //Capturar información
        let id = $(this);
        let token = $('input[name=_token]').val(); 
        let tipo_correspondencia = $(id).data('tipo_correspondencia');
        let idComunicado = $(id).data('id_comunicado');
        let N_radicado = $(id).data('n_radicado');
        let destinatarioPrincipal = $(id).data("destinatario_principal");
        let copias = $(id).data("copias");
        let id_evento = $(id).data('id_evento');
        let id_proceso = $(id).data('id_proceso');
        let id_asignacion = $(id).data('id_asignacion');
        let anexos = $(id).data('anexos');
        let correspondencia = $(id).data('correspondencia');
        //Tipo de comunicado si fue cargado manualmente o es generado por Sigmel
        let tipo_descarga = $(id).data('tipo_descarga');
        let id_destinatario = retornarIdDestinatario($(id).data('ids_destinatario'),tipo_correspondencia);
        //Se consultan las correspondencias que fueron guardadas como no notificados por medio de cargue masivo, los cuales deben salir en negrilla
        let correspondencias_guardadas = await consultarRegistroPorIdDestinatario(id_destinatario);
        //Ya que en un principio las copias llegan en un string se separan por , y se les elimina los espacios en blancos para poder comparar 
        copias = copias ? copias.split(',').map(copia => copia.trim()) : copias;
        //Desactiva el formulario en caso de que la correspodencia este inactiva.
        if($(id).data("estado_correspondencia") != 1){
            $("#btn_guardar_actualizar_correspondencia").remove();
            $("#form_correspondencia *").prop('disabled',true);
            $("#cerar_modalCorrespondencia").prop('disabled',false);
        }
        
        if(tipo_descarga === 'Manual' || tipo_descarga === 'Dictamen'){
            $("#modalCorrespondencia #nombre_afiliado").val($("#nombre_afiliado").val());
            $("#modalCorrespondencia #n_identificacion").val($("#identificacion").val());
        }
        else{
            $("#modalCorrespondencia #nombre_afiliado").val($(id).data('nombre_afiliado'));
            $("#modalCorrespondencia #n_identificacion").val($(id).data('numero_identificacion'));
        }
        $("#modalCorrespondencia #id_destinatario").val(id_destinatario);
        $("#modalCorrespondencia #id_evento").val($(id).data('id_evento'));
        $("#modalCorrespondencia #enlace_ed_evento").text($(id).data('id_evento'));
        if(tipo_descarga === 'Manual'){
            $("#modalCorrespondencia #check_principal").prop('checked', false);
            $("#modalCorrespondencia #check_principal").prop('disabled', false);
            $("#modalCorrespondencia #check_copia").prop('disabled', false);
            $("#modalCorrespondencia #check_copia").prop('checked', false);
        }
        if(correspondencia && correspondencia.length >0){
            array_temp = correspondencia.split(",").map(item => item.trim());
            correspondencia_array = array_temp;
        }
        $("#modalCorrespondencia #tipo_correspondencia").val(tipo_correspondencia);
        $("#modalCorrespondencia #id_asignacion").val(id_asignacion);
        $("#modalCorrespondencia #id_proceso").val(id_proceso);
        $("#modalCorrespondencia #id_comunicado").val(idComunicado);
        if(correspondencia_array.includes(tipo_correspondencia) || correspondencias_guardadas === tipo_correspondencia){
            data_comunicado = {
                _token: token,
                id_comunicado: idComunicado,
                id_evento: id_evento,
                id_asignacion: id_asignacion,
                id_proceso: id_proceso,
                tipo_correspondencia: tipo_correspondencia,
                previous_saved: true 
            }
            
            $.ajax({
                type:'POST',
                url:'/getInformacionCorrespondencia',
                data: data_comunicado,
                beforeSend:  function() {
                    showLoading();
                },
                success: function(response){
                    if(response && response[0]){
                        $("#btn_guardar_actualizar_correspondencia").val('Actualizar');

                        $("#modalCorrespondencia #n_orden").val(response[0]?.N_orden);
                        $("#modalCorrespondencia #nombre_destinatario").val(response[0]?.Nombre_destinatario);
                        $("#modalCorrespondencia #direccion").val(response[0]?.Direccion_destinatario);
                        $("#modalCorrespondencia #departamento").val(response[0]?.Departamento);
                        $("#modalCorrespondencia #ciudad").val(response[0]?.Ciudad);
                        $("#modalCorrespondencia #telefono").val(response[0]?.Telefono_destinatario);
                        $("#modalCorrespondencia #email").val(response[0]?.Email_destinatario);
                        $("#modalCorrespondencia #m_notificacion").val(response[0]?.Medio_notificacion);
                        $("#modalCorrespondencia #folios").val(response[0]?.Folios);
                        $("#modalCorrespondencia #radicado").val(response[0]?.N_radicado);
                        $("#modalCorrespondencia .modal-title").text('Correspondencia ' + response[0]?.Tipo_correspondencia);
                        $("#modalCorrespondencia #n_guia").val(response[0]?.N_guia);
                        $("#modalCorrespondencia #f_envio").val(response[0]?.F_envio);
                        $("#modalCorrespondencia #f_notificacion").val(response[0]?.F_notificacion);
                        $("#modalCorrespondencia #state_notificacion").val(response[0]?.Id_Estado_corresp);
                        $("#modalCorrespondencia #id_correspondencia").val(response[0]?.Id_Correspondencia);
                        
                        if(response[0]?.Tipo_destinatario){
                            if(response[0]?.Tipo_destinatario === $('#modalCorrespondencia #check_principal').val()){
                                if(tipo_descarga != 'Manual'){
                                    $("#modalCorrespondencia #check_principal").prop('checked', true);
                                    $("#modalCorrespondencia #check_copia").prop('disabled', true);
                                    $("#modalCorrespondencia #check_copia").prop('required', false);
                                }
                                else{
                                    $("#modalCorrespondencia #check_principal").prop('checked', true);
                                    $("#modalCorrespondencia #check_principal").prop('disabled', false);
                                    $("#modalCorrespondencia #check_copia").prop('disabled', true);
                                    $("#modalCorrespondencia #check_copia").prop('required', false);
                                }
                                
                            }
                            else if(response[0]?.Tipo_destinatario === $('#modalCorrespondencia #check_copia').val()){
                                if(tipo_descarga != 'Manual'){
                                    $("#modalCorrespondencia #check_copia").prop('checked', true);
                                    $("#modalCorrespondencia #check_copia").prop('disabled', true);
                                    $("#modalCorrespondencia #check_principal").prop('required', false);
                                }
                                else{
                                    $("#modalCorrespondencia #check_copia").prop('checked', true);
                                    $("#modalCorrespondencia #check_principal").prop('disabled', true);
                                    $("#modalCorrespondencia #check_principal").prop('required', false);
                                    $("#modalCorrespondencia #check_copia").prop('disabled', false);
                                }
                            } 
                        }
                        let selectores_notificacion_correspondencia = {
                            '_token': $('input[name=_token]').val(),
                            'parametro': 'EstadosNotificacionCorrespondencia'
                        }
                        $.ajax({
                            type: 'POST',
                            url: '/selectoresJuntas',
                            data: selectores_notificacion_correspondencia,
                            beforeSend:  function() {
                                $("#btn_guardar_actualizar_correspondencia").addClass("descarga-deshabilitada");
                            },
                            success: function (data) {
                                let optionSelected = data.find(finder => finder.Id_Parametro === response[0]?.Id_Estado_corresp);
                                $('#state_notificacion').empty();
                                $('#state_notificacion').append('<option value="'+response[0]?.Id_Estado_corresp+'" selected>'+optionSelected?.Nombre_parametro+'</option>');
                                let SelectorModalCorrespondencia = $('select[name=state_notificacion]').val();
                                let formaenviogenerarcomunicado = Object.keys(data);
                                for (let i = 0; i < formaenviogenerarcomunicado.length; i++) {
                                    if (data[formaenviogenerarcomunicado[i]]['Id_Parametro'] != SelectorModalCorrespondencia) {
                                        $('#state_notificacion').append('<option value="'+data[formaenviogenerarcomunicado[i]]['Id_Parametro']+'">'+data[formaenviogenerarcomunicado[i]]['Nombre_parametro']+'</option>');
                                    }                
                                }
                            },
                            complete: function(){
                                $("#btn_guardar_actualizar_correspondencia").removeClass("descarga-deshabilitada");
                            }
                        });
                    }
                },
                error: function (error) {
                    console.error('Ha ocurrido un error:', error);
                },
                complete: function(){
                    hideLoading();
                }
            });
        }
        else{
            data_comunicado = {
                _token: token,
                id_comunicado: idComunicado,
                id_evento: id_evento,
                id_asignacion: id_asignacion,
                id_proceso: id_proceso,
                tipo_correspondencia: tipo_correspondencia,
                previous_saved: false
            }
            $.ajax({
                type:'POST',
                url:'/getInformacionCorrespondencia',
                data: data_comunicado,
                beforeSend:  function() {
                    showLoading();
                },
                success: function(response){
                    if(response && response.datos){
                        $("#modalCorrespondencia #n_orden").val(response?.nro_orden);
                        $("#modalCorrespondencia #nombre_destinatario").val(response?.datos?.Nombre_destinatario);
                        $("#modalCorrespondencia #direccion").val(response?.datos?.Direccion_destinatario);
                        $("#modalCorrespondencia #departamento").val(response?.datos?.Departamento_destinatario);
                        $("#modalCorrespondencia #ciudad").val(response?.datos?.Ciudad_destinatario);
                        $("#modalCorrespondencia #telefono").val(response?.datos?.Telefono_destinatario);
                        $("#modalCorrespondencia #email").val(response?.datos?.Email_destinatario);
                        $("#modalCorrespondencia #m_notificacion").val(response?.datos?.Medio_notificacion_destinatario);
                        $("#modalCorrespondencia #folios").val(anexos);
                        $("#modalCorrespondencia .modal-title").text('Correspondencia ' + tipo_correspondencia);
                        $("#modalCorrespondencia #radicado").val(N_radicado);
                        if(tipo_descarga != 'Manual' && tipo_correspondencia.toLowerCase() === destinatarioPrincipal.toLowerCase()){
                            $("#modalCorrespondencia #check_principal").prop('checked', true);
                            $("#modalCorrespondencia #check_copia").prop('disabled', true);
                            $("#modalCorrespondencia #check_copia").prop('required', false);
                        }
                        else if(tipo_descarga != 'Manual' && tipo_correspondencia.toLowerCase() !== destinatarioPrincipal.toLowerCase() && Array.isArray(copias) && copias?.some(copia => copia.toLowerCase() === tipo_correspondencia.toLowerCase())){
                            $("#modalCorrespondencia #check_copia").prop('checked', true);
                            $("#modalCorrespondencia #check_copia").prop('disabled', true);
                            $("#modalCorrespondencia #check_principal").prop('required', false);
                        }
                    }
                },
                error: function (error) {
                    console.error('Ha ocurrido un error:', error);
                },
                complete: function(){
                    hideLoading();
                }
            });
        }
        // Mostrar la modal
        $("#modalCorrespondencia").show();

        //Eventos checkbox principal
        $("#check_principal").change(function() {
            if ($(this).is(':checked')) {
                $("#check_copia").prop('disabled', true).prop('required', false);
            } else {
                $("#check_copia").prop('disabled', false).prop('required', true);
            }
        });
        //Eventos checkbox copia
        $("#check_copia").change(function() {
            if ($(this).is(':checked')) {
                $("#check_principal").prop('disabled', true).prop('required', false);
            } 
            else if(tipo_descarga == 'Manual') {
                $("#check_principal").prop('disabled', false).prop('required', true);
            }
            else{
                $("#check_principal").prop('disabled', true).prop('required', true);
            }
        });
    });

    $('#form_correspondencia').submit(function (e) {
        e.preventDefault();
        let token = $('input[name=_token]').val(); 
        let tipo_correspondencia = $('#modalCorrespondencia #tipo_correspondencia').val();
        if (!correspondencia_array.includes(tipo_correspondencia)) {
            correspondencia_array.push(tipo_correspondencia);
        }
        tipoDestinatario = null;
        if($('#check_principal').is(':checked')){
            tipoDestinatario = $('#modalCorrespondencia #check_principal').val();
            $("#modalCorrespondencia #check_principal").prop('required', false);
        }
        else if($('#check_copia').is(':checked')){
            tipoDestinatario = $('#modalCorrespondencia #check_copia').val();
        }
        else{
            tipoDestinatario = null;
        }
        datos_correspondencia = {
            '_token': token,
            'correspondencia': correspondencia_array,
            'nombre_afiliado': $('#modalCorrespondencia #nombre_afiliado').val(),
            'n_identificacion_afiliado': $('#modalCorrespondencia #n_identificacion').val(),
            'id_asignacion': $('#modalCorrespondencia #id_asignacion').val(),
            'id_proceso': $('#modalCorrespondencia #id_proceso').val(),
            'id_evento': $('#modalCorrespondencia #id_evento').val(),
            'id_comunicado': $('#modalCorrespondencia #id_comunicado').val(),
            'id_destinatario': $('#modalCorrespondencia #id_destinatario').val(),
            'n_radicado': $('#modalCorrespondencia #radicado').val(),
            'n_orden': $('#modalCorrespondencia #n_orden').val(),
            'tipo_destinatario': tipoDestinatario,
            'nombre_destinatario': $('#modalCorrespondencia #nombre_destinatario').val(),
            'direccion_destinatario': $('#modalCorrespondencia #direccion').val(),
            'departamento_destinatario': $('#modalCorrespondencia #departamento').val(),
            'ciudad_destinatario': $('#modalCorrespondencia #ciudad').val(),
            'telefono_destinatario': $('#modalCorrespondencia #telefono').val(),
            'email_destinatario': $('#modalCorrespondencia #email').val(),
            'medio_notificacion_destinatario': $('#modalCorrespondencia #m_notificacion').val(),
            'n_guia': $('#modalCorrespondencia #n_guia').val(),
            'folios': $('#modalCorrespondencia #folios').val(),
            'fecha_envio': $('#modalCorrespondencia #f_envio').val(),
            'fecha_notificacion': $('#modalCorrespondencia #f_notificacion').val(),
            'estado_notificacion': $('#modalCorrespondencia #state_notificacion').val(),
            'tipo_correspondencia': tipo_correspondencia,
            'id_correspondencia': $('#modalCorrespondencia #id_correspondencia').val(),
            'accion': $('#btn_guardar_actualizar_correspondencia').val()
        };
        $.ajax({    
            type:'POST',
            url:'/guardarInformacionCorrespondencia',
            data: datos_correspondencia,
            beforeSend:  function() {
                $("#btn_guardar_actualizar_correspondencia").addClass("descarga-deshabilitada");
                showLoading();
            },
            success: function(response){
                if (response.parametro == 'agregar_correspondencia') {
                    $('.alerta_correspondencia').removeClass('d-none');
                    $('.alerta_correspondencia').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.alerta_correspondencia').addClass('d-none');
                        $('.alerta_correspondencia').empty();
                        localStorage.setItem("#Generar_comunicados", true);
                        location.reload();
                    }, 3000);
                }
            },
            error: function (error) {
                $('.alerta_error').removeClass('d-none');
                $('.alerta_error').append('<strong> Ha ocurrido un error al momento de guardar la correspondencia.</strong>');
                setTimeout(function(){
                    $('.alerta_error').addClass('d-none');
                    $('.alerta_error').empty();
                }, 3000);
            },
            complete: function(){
                $("#btn_guardar_actualizar_correspondencia").removeClass("descarga-deshabilitada");
                hideLoading();
            }
        });
    });

    //Descargar archivo cargado manualmente
    $(document).on('submit',"form[id^='form_descargar_archivo_']",function (e){
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

    //Accion editar comunicado
    $("#listado_comunicados_clpcl").on("click",'#editar_comunicado',function(){
        let radicado = $(this).data('radicado');
        let datos_comunicados_actualizar = {
            '_token' : token,
            'bandera': 'Actualizar',
            'radicado' : $(this).data('radicado'),
            'Nota': $("#nota_comunicado_" + radicado).val(),
            'Estado_general': $("#status_notificacion_" + radicado).val(),
            'id_asignacion': $('#Id_Asignacion_decreto').val()
        };
        $.ajax({
            type:'POST',
            url:'/historialComunicadoPcl',
            data: datos_comunicados_actualizar,
            success:function(data){
                $('.alerta_externa_comunicado').removeClass('d-none');
                $(".alerta_externa_comunicado").append("<strong>" + data + "</strong>");
                setTimeout(()=>{
                    localStorage.setItem("#Generar_comunicados", true);
                    location.reload();
                },1500);

            }
        });
    });

    //Reemplazar archivo 
    let comunicado_reemplazar = null;
    $(document).on('submit',"form[id^='form_reemplazar_archivo_']",function (e){
        e.preventDefault();           
        //Se abre el modal
        $('#modalReemplazarArchivos').modal('show');  
        //Se limpian las advertencias y el input de archivo
        $(".cargueundocumentoprimeromodal").addClass('d-none');
        $(".extensionInvalidaModal").addClass('d-none');
        $('#cargue_comunicados_modal').val('');
        //Se obtiene la info del archivo que toca reemplazar
        comunicado_reemplazar = $(this).data('archivo');
        data_comunicado = {
            '_token': $('input[name=_token]').val(),
            'id_comunicado': comunicado_reemplazar.Id_Comunicado
        }
        $.ajax({
            type:'POST',
            url:'/getInfoComunicado',
            data: data_comunicado,
            beforeSend:  function() {
                $("#cargarComunicadoModal").addClass("descarga-deshabilitada");
            },
            success:function(response){
                if(response && response[0]){
                    comunicado_reemplazar = response[0];
                    let nombre_doc = comunicado_reemplazar.Nombre_documento;
                    if(nombre_doc != null && nombre_doc != "null" && comunicado_reemplazar.Tipo_descarga !== 'Manual'){
                        extensionDoc = ['.pdf','.doc','.docx','.xlsx'];//`.${ nombre_doc.split('.').pop()}`;
                        document.getElementById('cargue_comunicados_modal').setAttribute('accept', extensionDoc);
                    }
                    else if(comunicado_reemplazar.Tipo_descarga === 'Manual'){
                        extensionDocManual = ['.pdf','.doc','.docx','.xlsx']
                        document.getElementById('cargue_comunicados_modal').setAttribute('accept', '.pdf, .doc, .docx, .xlsx');
                    }
                }
            },
            complete:function(){
                $("#cargarComunicadoModal").removeClass("descarga-deshabilitada");
            }
        });
    });

    const initValueExtension = document.getElementById('extensionInvalidaMensaje')?.textContent;
    $("form[id^='reemplazar_documento']").submit(function(e){
        e.preventDefault();
        if(!$('#cargue_comunicados_modal')[0].files[0]){
            return $(".cargueundocumentoprimeromodal").removeClass('d-none');
        }
        $(".cargueundocumentoprimeromodal").addClass('d-none');
        $(".extensionInvalidaModal").addClass('d-none');
        var archivo = $('#cargue_comunicados_modal')[0].files[0];
        extensionDocCargado = `.${archivo.name.split('.').pop()}`;
        if(comunicado_reemplazar.Tipo_descarga === 'Manual' && extensionDocManual.includes(extensionDocCargado)){
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
            formData.append('modulo_creacion', 'determinacionOrigenATEL');
            formData.append('nombre_documento', archivo.name);
            formData.append('asunto', archivo.name);
            formData.append('nombre_anterior', comunicado_reemplazar.Nombre_documento);
            $.ajax({
                type:'POST',
                url:'/reemplazarDocumento',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend:  function() {
                    $("#cargarComunicadoModal").addClass("descarga-deshabilitada");
                },
                success:function(response){
                    if (response.parametro == 'reemplazar_comunicado') {
                        $('.alerta_externa_comunicado_modal').removeClass('d-none');
                        $('.alerta_externa_comunicado_modal').append('<strong>'+response.mensaje+'</strong>');
                        setTimeout(function(){
                            $('.alerta_externa_comunicado_modal').addClass('d-none');
                            $('.alerta_externa_comunicado_modal').empty();
                            localStorage.setItem("#Generar_comunicados", true);
                            location.reload();
                            $("#modalReemplazarArchivos").modal('hide');
                        }, 1000);
                    }
                },
                complete:function(){
                    $("#cargarComunicadoModal").removeClass("descarga-deshabilitada");
                }
            });
        }
        else if(comunicado_reemplazar.Tipo_descarga !== 'Manual' && extensionDoc.includes(extensionDocCargado)){
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
            formData.append('modulo_creacion', 'determinacionOrigenATEL');
            // if(comunicado_reemplazar.Tipo_descarga === 'Manual'){
            //     formData.append('nombre_documento', archivo.name);
            //     formData.append('asunto', archivo.name);
            //     formData.append('nombre_anterior', comunicado_reemplazar.Nombre_documento);
            // }else{
            formData.append('nombre_documento', comunicado_reemplazar.Nombre_documento);
            formData.append('asunto', comunicado_reemplazar.Asunto);
            formData.append('nombre_anterior', '');
            // }
            $.ajax({
                type:'POST',
                url:'/reemplazarDocumento',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend:  function() {
                    $("#cargarComunicadoModal").addClass("descarga-deshabilitada");
                },
                success:function(response){
                    if (response.parametro == 'reemplazar_comunicado') {
                        $('.alerta_externa_comunicado_modal').removeClass('d-none');
                        $('.alerta_externa_comunicado_modal').append('<strong>'+response.mensaje+'</strong>');
                        setTimeout(function(){
                            $('.alerta_externa_comunicado_modal').addClass('d-none');
                            $('.alerta_externa_comunicado_modal').empty();
                            localStorage.setItem("#Generar_comunicados", true);
                            location.reload();
                            $("#modalReemplazarArchivos").modal('hide');
                        }, 1000);
                    }
                },
                complete:function(){
                    $("#cargarComunicadoModal").removeClass("descarga-deshabilitada");
                }
            });
        }
        else{
            document.getElementById('extensionInvalidaMensaje').textContent = initValueExtension;
            if(comunicado_reemplazar.Tipo_descarga !== 'Manual'){
                if (!document.getElementById('extensionInvalidaMensaje').textContent.includes(extensionDoc)) {
                    document.getElementById('extensionInvalidaMensaje').textContent += extensionDoc;
                }
                return $(".extensionInvalidaModal").removeClass('d-none');
            }
            if (!document.getElementById('extensionInvalidaMensaje').textContent.includes(extensionDocManual)) {
                document.getElementById('extensionInvalidaMensaje').textContent += extensionDocManual;
            }
            return $(".extensionInvalidaModal").removeClass('d-none');
        }
    });

    $(document).on('submit',"form[id^='ver_dictamentPCL']",function (e){
    // $("form[id^='ver_dictamentPCL']").submit(function(e){
        e.preventDefault();
        var infoComunicado = $(this).data("archivo");
        var nombre_doc = infoComunicado.Nombre_documento;
        var idEvento = infoComunicado.ID_evento;
        var enlaceDescarga = document.createElement('a');
        enlaceDescarga.href = '/descargar-archivo/'+nombre_doc+'/'+idEvento;     
        enlaceDescarga.target = '_self'; // Abrir en una nueva ventana/tab
        enlaceDescarga.style.display = 'none';
        document.body.appendChild(enlaceDescarga);
        enlaceDescarga.click();
        setTimeout(function() {
            document.body.removeChild(enlaceDescarga);
        }, 1000);
    });
    $(document).on('submit',"form[id^='verNotificacionPCL']",function (e){
    // $("form[id^='verNotificacionPCL']").submit(function(e){
        e.preventDefault();
        var infoComunicado = $(this).data("archivo");
        var nombre_doc = infoComunicado.Nombre_documento;
        var idEvento = infoComunicado.ID_evento;
        var enlaceDescarga = document.createElement('a');
        enlaceDescarga.href = '/descargar-archivo/'+nombre_doc+'/'+idEvento;     
        enlaceDescarga.target = '_self'; // Abrir en una nueva ventana/tab
        enlaceDescarga.style.display = 'none';
        document.body.appendChild(enlaceDescarga);
        enlaceDescarga.click();
        setTimeout(function() {
            document.body.removeChild(enlaceDescarga);
        }, 1000);

    });

    $('form[name="ver_dictamenPcl"]').on('submit', function(event) {
        let form = $(this);
        var infoComunicado = $(this).data("archivo");

        if(form.attr('action') != undefined && form.attr('action') != null){
            if(infoComunicado.Nombre_documento == null){
                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method')
                })
                .always(function() {
                    setTimeout(function() {
                        location.reload();
                    },1000)
                });
            }
        }
    });
    $('form[name="ver_notificacionPcl"]').on('submit', function(event) {
        let form = $(this);
        var infoComunicado = $(this).data("archivo");

        if(form.attr('action') != undefined && form.attr('action') != null){
            if(infoComunicado.Nombre_documento == null){
                
                    $.ajax({
                        url: form.attr('action'),
                        method: form.attr('method')
                    })
                    .always(function() {
                        setTimeout(function() {
                            location.reload();
                        },1000)
                    });
                
            }
        }
    });

    //Captura Formulario Correspondencia
    $('#form_correspondencia_pcl').submit(function (e){
        e.preventDefault();              
        
        var Id_EventoDecreto = $('#Id_Evento_decreto').val();
        var Id_ProcesoDecreto = $('#Id_Proceso_decreto').val();
        var Id_Asignacion_Dcreto  = $('#Id_Asignacion_decreto').val();
        var oficiopcl = $('input[name="oficiopcl"]:checked').val();
        var oficioinca = $('input[name="oficioinca"]:checked').val();
        if (oficiopcl == undefined) {
            oficiopcl = '';
        }
        if(oficioinca == undefined){
            oficioinca = '';
        }
        var destinatario_principal = $('#destinatario_principal').val();        
        var otrodestinariop = $('input[name="otrodestinariop"]:checked').val();
        if (otrodestinariop == undefined) {
            var tipo_destinatario_principal = '';
            var nombre_destinatariopri = '';            
        } else {
            var tipo_destinatario_principal = $('#tipo_destinatario_principal').val();            
            var nombre_destinatariopri = $('#nombre_destinatariopri').val();
        }
        var nombre_destinatario_afi = $('#nombre_destinatario_afi').val();
        var nombre_destinatario_emp = $('#nombre_destinatario_emp').val();
        if (tipo_destinatario_principal == 4) {
            var nombre_destinatario_afi_emp = nombre_destinatario_afi;
        } else if(tipo_destinatario_principal == 5) {
            var nombre_destinatario_afi_emp = nombre_destinatario_emp;            
        }
        if(tipo_destinatario_principal == 8){
            var nombre_destinatario = $('#nombre_destinatario').val();
            var nitcc_destinatario = $('#nitcc_destinatario').val();
            var direccion_destinatario = $('#direccion_destinatario').val();
            var telefono_destinatario = $('#telefono_destinatario').val();
            var email_destinatario = $('#email_destinatario').val();
            var departamento_destinatario = $('#departamento_destinatario').val();
            var ciudad_destinatario = $('#ciudad_destinatario').val();
        }else{            
            var nombre_destinatario = '';
            var nitcc_destinatario = '';
            var direccion_destinatario = '';
            var telefono_destinatario = '';
            var email_destinatario = '';
            var departamento_destinatario = '';
            var ciudad_destinatario = '';
        }        
        var Asunto = $('#Asunto').val();
        var cuerpo_comunicado = $('#cuerpo_comunicado').val();
        var cuerpo_comunicado_cero = $('#cuerpo_comunicado_cero').val();

        if (cuerpo_comunicado == '' || cuerpo_comunicado == undefined) {
            var cuerpo_comunicadoPcl = cuerpo_comunicado_cero;
        } else {
            var cuerpo_comunicadoPcl = cuerpo_comunicado;
        }
        cuerpo_comunicado = cuerpo_comunicado ? cuerpo_comunicado.replace(/"/g, "'") : '';
        cuerpo_comunicado_cero = cuerpo_comunicado_cero ? cuerpo_comunicado_cero.replace(/"/g, "'") : '';
        var afiliado = $('input[name="afiliado"]:checked').val();
        var empleador = $('input[name="empleador"]:checked').val();   
        var eps = $('input[name="eps"]:checked').val();
        var afp = $('input[name="afp"]:checked').val();
        

        // Se valida si han marcado como si la opcion de la entidad de conocimiento (afp)
        var afp_conocimiento = '';
        if (entidad_conocimiento != '' && entidad_conocimiento == "Si") {
            afp_conocimiento = $('input[name="afp_conocimiento"]:checked').val();
        }

        var arl = $('input[name="arl"]:checked').val();
        var jrci = $('input[name="jrci"]:checked').val();   
            
        if (jrci == undefined) {
            var cual = '';                        
        } else {
            var cual = $('#cual').val();            
        }       
        var jnci = $('input[name="jnci"]:checked').val();
        var anexos = $('#anexos').val();
        var elaboro = $('#elaboro').val();
        var reviso = $('#reviso').val();
        var firmar = $('input[name="firmar"]:checked').val();
        var ciudad = $('#ciudad').val();
        var f_correspondencia = $('#f_correspondencia').val();
        var radicado = $('#radicado').val();
        var bandera_correspondecia_guardar_actualizar = $('#bandera_correspondecia_guardar_actualizar').val();
        var N_siniestro = $('#n_siniestro').val();
        
        var datos_correspondecia={
            '_token': token,            
            'Id_EventoDecreto':Id_EventoDecreto,
            'Id_ProcesoDecreto':Id_ProcesoDecreto,
            'Id_Asignacion_Dcreto':Id_Asignacion_Dcreto,     
            'oficiopcl':oficiopcl,
            'oficioinca':oficioinca,
            'destinatario_principal':destinatario_principal,
            'otrodestinariop' : otrodestinariop,
            'tipo_destinatario_principal' : tipo_destinatario_principal,
            'nombre_destinatariopri' : nombre_destinatariopri,
            'Nombre_dest_principal_afi_empl' : nombre_destinatario_afi_emp,
            'nombre_destinatario': nombre_destinatario,
            'nitcc_destinatario': nitcc_destinatario,
            'direccion_destinatario': direccion_destinatario,
            'telefono_destinatario': telefono_destinatario,
            'email_destinatario': email_destinatario,
            'departamento_destinatario': departamento_destinatario,
            'ciudad_destinatario': ciudad_destinatario,
            'Asunto':Asunto,
            'cuerpo_comunicado':cuerpo_comunicadoPcl,
            'afiliado': afiliado,
            'empleador':empleador,
            'eps':eps,
            'afp':afp,
            'afp_conocimiento': afp_conocimiento,
            'arl':arl,
            'jrci':jrci,
            'cual':cual,
            'jnci':jnci,
            'anexos':anexos,
            'elaboro':elaboro,
            'reviso':reviso,
            'firmar':firmar,
            'ciudad':ciudad,
            'f_correspondencia':f_correspondencia,
            'radicado':radicado,
            'bandera_correspondecia_guardar_actualizar':bandera_correspondecia_guardar_actualizar,
            'tipo_descarga':'Oficio',
            'N_siniestro': N_siniestro
        }
        // console.log('datos_correspondecia ', datos_correspondecia)
        $.ajax({    
            type:'POST',
            url:'/guardarcorrespondencias',
            data: datos_correspondecia,
            success: function(response){
                if (response.parametro == 'insertar_correspondencia') {
                    $('#GuardarCorrespondencia').prop('disabled', true);
                    $('#div_alerta_Correspondencia').removeClass('d-none');
                    $('.alerta_Correspondencia').append('<strong>'+response.mensaje+'</strong>');
                    let Id_Comunicado = response.Id_Comunicado;
                    let Bandera_boton_guardar_oficio = response.Bandera_boton_guardar_oficio;
                    let datos_generar_oficios = {
                        '_token': token,
                        'ID_Evento_comuni_comite':Id_EventoDecreto,
                        'Id_Proceso_comuni_comite':Id_ProcesoDecreto,
                        'Id_Asignacion_comuni_comite':Id_Asignacion_Dcreto,
                        'Firma_comuni_comite':firmar,
                        'Radicado_comuni_comite':radicado,
                        'Id_Comunicado':Id_Comunicado,
                        'N_siniestro': N_siniestro ,
                        'Bandera_boton_guardar_oficio':Bandera_boton_guardar_oficio,
                    }
                    // Llamar a la URL para generar el PDF después de que la primera solicitud que se completo
                    $.ajax({
                        type:'POST',
                        url:'/generarOficios_Pcl',
                        data: datos_generar_oficios,
                        success: function(pdfResponse) {
                            // la respuesta de generarPdfDictamenPcl
                            console.log('PDF generado');
                        },
                        error: function(xhr) {
                            console.error('Error al generar el PDF', xhr);
                        }

                    });
                    setTimeout(function(){
                        $('#div_alerta_Correspondencia').addClass('d-none');
                        $('.alerta_Correspondencia').empty();   
                        location.reload();
                    }, 3000);   
                }else if(response.parametro == 'actualizar_correspondencia'){
                    $('#ActualizarCorrespondencia').prop('disabled', true);
                    $('#div_alerta_Correspondencia').removeClass('d-none');
                    $('.alerta_Correspondencia').append('<strong>'+response.mensaje+'</strong>');
                    let Id_Comunicado = response.Id_Comunicado;
                    let Bandera_boton_guardar_oficio = response.Bandera_boton_guardar_oficio;
                    let datos_generar_oficios = {
                        '_token': token,
                        'ID_Evento_comuni_comite':Id_EventoDecreto,
                        'Id_Proceso_comuni_comite':Id_ProcesoDecreto,
                        'Id_Asignacion_comuni_comite':Id_Asignacion_Dcreto,
                        'Firma_comuni_comite':firmar,
                        'Radicado_comuni_comite':radicado,
                        'Id_Comunicado':Id_Comunicado,
                        'N_siniestro': N_siniestro ,
                        'Bandera_boton_guardar_oficio':Bandera_boton_guardar_oficio,
                    }
                    // Llamar a la URL para generar el PDF después de que la primera solicitud que se completo
                    $.ajax({
                        type:'POST',
                        url:'/generarOficios_Pcl',
                        data: datos_generar_oficios,
                        success: function(pdfResponse) {
                            // la respuesta de generarPdfDictamenPcl
                            console.log('PDF generado');
                        },
                        error: function(xhr) {
                            console.error('Error al generar el PDF', xhr);
                        }

                    });                                            
                    setTimeout(function(){
                        $('#div_alerta_Correspondencia').addClass('d-none');
                        $('.alerta_Correspondencia').empty();   
                        location.reload();
                    }, 3000);  
                }
            }          
        })
    }) 

    // Ocultar o habilitar la Fecha de Evento en el dictamen pericial
    $('#tipo_evento').change(function () {
        var valorSeleccionado = $(this).val();
        if (valorSeleccionado != 2) {
            $('#div_tipo_evento').removeClass('d-none');
            $('#f_evento_pericial').prop('required', true);
        } else if (valorSeleccionado == 2) {
            $('#div_tipo_evento').addClass('d-none');
            $('#f_evento_pericial').prop('required', false);
        } 
    });
    var t_evento = $('#tipo_evento').val();
    if (t_evento == 2) {
        $('#div_tipo_evento').addClass('d-none');
        $('#f_evento_pericial').prop('required', false);
    }else{
        $('#div_tipo_evento').removeClass('d-none');
    }
    // Captura Formulario Dictamen pericial
    $('#form_dictamen_pericial').submit(function (e){
        e.preventDefault();              
        document.querySelector('#GuardrDictamenPericial').disabled=true;        
        // Abrir modal para mostrar alerta y retornar al input
        var validarsuma_combinada = $('#suma_combinada').val();
        var validarTotal_Deficiencia50 = $('#Total_Deficiencia50').val();
        
        // Funcion de modal si la suma combinada esta vacia o la total deficiencia 50
        function displayModal() {
            $('#AlertaScTd').modal('show');    
            // Remover eventos previos para evitar duplicación
            $('#AlertaScTd').off('hidden.bs.modal');    
            // Configurar el evento para que, al cerrar la modal, se haga focus en el botón
            $('#AlertaScTd').on('hidden.bs.modal', function () {
                setTimeout(function() {
                    // Establecer el foco
                    $('#suma_combinada').focus(); 
                    document.querySelector('#GuardrDictamenPericial').disabled=false;
                }, 200);
            });
        }          

        if ((validarsuma_combinada.trim() === "" && validarTotal_Deficiencia50.trim() === "") 
        || (validarsuma_combinada.trim() === "" && validarTotal_Deficiencia50.trim() !== "") 
        || (validarsuma_combinada.trim() !== "" && validarTotal_Deficiencia50.trim() === "")) {
            displayModal();
            return;
        }

        var Decreto_pericial = $('#decreto_califi').val();
        var Id_EventoDecreto = $('#Id_Evento_decreto').val();
        var Id_ProcesoDecreto = $('#Id_Proceso_decreto').val();
        var Id_Asignacion_Dcreto  = $('#Id_Asignacion_decreto').val();
        var suma_combinada = $('#suma_combinada').val();
        var Total_Deficiencia50 = $('#Total_Deficiencia50').val();
        var total_discapacidades = $('#total_discapacidades').val();
        var total_minusvalia = $('#total_minusvalia').val();
        var radicado_dictamen = $('#radicado_dictamen').val();
        var porcentaje_pcl = $('#porcentaje_pcl').val();
        var rango_pcl = $('#rango_pcl').val();
        var monto_inde = $('#monto_inde').val();
        var tipo_evento = $('#tipo_evento').val();
        var tipo_origen = $('#tipo_origen').val();
        var f_evento_pericial = $('#f_evento_pericial').val();
        var f_estructura_pericial = $('#f_estructura_pericial').val();
        var n_siniestro = $('#n_siniestro').val();
        var requiere_rev_pension = $('input[name="requiere_rev_pension"]:checked').val();
        var sustenta_fecha = $('#sustenta_fecha').val();
        var detalle_califi = $('#detalle_califi').val();
        var enfermedad_catastrofica = $('input[name="enfermedad_catastrofica"]:checked').val(); 
        var enfermedad_congenita = $('input[name="enfermedad_congenita"]:checked').val(); 
        var tipo_enfermedad = $('#tipo_enfermedad').val();
        var requiere_persona = $('input[name="requiere_persona"]:checked').val(); 
        var requiere_decisiones_persona = $('input[name="requiere_decisiones_persona"]:checked').val(); 
        var requiere_dispositivo_apoyo = $('input[name="requiere_dispositivo_apoyo"]:checked').val();         
        var justi_dependencia = $('#justi_dependencia').val();   
        var bandera_dictamen_pericial = $('#bandera_dictamen_pericial').val();
        
        var datos_dictamenPericial={
            '_token': token,            
            'Decreto_pericial':Decreto_pericial,
            'Id_EventoDecreto':Id_EventoDecreto,
            'Id_ProcesoDecreto':Id_ProcesoDecreto,
            'Id_Asignacion_Dcreto':Id_Asignacion_Dcreto,
            'suma_combinada':suma_combinada,
            'Total_Deficiencia50':Total_Deficiencia50,
            'total_discapacidades':total_discapacidades,
            'total_minusvalia':total_minusvalia,
            'radicado_dictamen':radicado_dictamen,
            'porcentaje_pcl':porcentaje_pcl,
            'rango_pcl':rango_pcl,
            'monto_inde':monto_inde,
            'tipo_evento':tipo_evento,
            'tipo_origen':tipo_origen,
            'f_evento_pericial':f_evento_pericial,
            'f_estructura_pericial':f_estructura_pericial,
            'n_siniestro':n_siniestro,
            'requiere_rev_pension': requiere_rev_pension,
            'sustenta_fecha':sustenta_fecha,
            'detalle_califi':detalle_califi,
            'enfermedad_catastrofica':enfermedad_catastrofica,
            'enfermedad_congenita':enfermedad_congenita,
            'tipo_enfermedad':tipo_enfermedad,
            'requiere_persona':requiere_persona,
            'requiere_decisiones_persona':requiere_decisiones_persona,
            'requiere_dispositivo_apoyo':requiere_dispositivo_apoyo,
            'justi_dependencia':justi_dependencia,
            'bandera_dictamen_pericial' :bandera_dictamen_pericial,
        }

        $.ajax({
            type:'POST',
            url:'/guardardictamenesPericial',
            data: datos_dictamenPericial,
            success: function(response){
                if (response.parametro == 'insertar_dictamen_pericial') {
                    // document.querySelector('#GuardrDictamenPericial').disabled=true;
                    $('#div_alerta_dictamen_pericial').removeClass('d-none');
                    $('.alerta_dictamen_pericial').append('<strong>'+response.mensaje+'</strong>'); 

                    // retornamos la variables necesarios para poder generar el dictamen y guardarlo en el servidor
                    let Id_Comunicado = response.Id_Comunicado;
                    let radicado_dictamen = response.radicado_dictamen; 
                    let Bandera_boton_guardar_dictamen = response.Bandera_boton_guardar_dictamen;

                    let datos_generar_dictamenes = {
                        '_token': token,
                        'ID_Evento_comuni':Id_EventoDecreto,
                        'Id_Proceso_comuni':Id_ProcesoDecreto,
                        'Id_Asignacion_comuni':Id_Asignacion_Dcreto,
                        'N_siniestro':n_siniestro,
                        'Radicado_comuni': radicado_dictamen,
                        'Id_Comunicado': Id_Comunicado,
                        'Bandera_boton_guardar_dictamen': Bandera_boton_guardar_dictamen,

                    };
                    // Llamar a la URL para generar el PDF después de que la primera solicitud que se completo
                    // Según el decreto
                    if (Decreto_pericial == 1) {
                        $.ajax({
                            type: 'POST',
                            url: '/generarPdfDictamenesPcl',
                            data: datos_generar_dictamenes,
                            success: function(pdfResponse) {
                                // la respuesta de generarPdfDictamenPcl
                                // console.log('PDF generado');
                            },
                            error: function(xhr) {
                                console.error('Error al generar el PDF', xhr);
                            }
                        });                        
                    } else if (Decreto_pericial == 2){
                        $.ajax({
                            type: 'POST',
                            url: '/generarPdfDictamenesPclCero',
                            data: datos_generar_dictamenes,
                            success: function(pdfResponse) {
                                // la respuesta de generarPdfDictamenPcl
                                // console.log('PDF generado');
                            },
                            error: function(xhr) {
                                console.error('Error al generar el PDF', xhr);
                            }
                        }); 
                    } else if (Decreto_pericial == 3){
                        $.ajax({
                            type: 'POST',
                            url: '/generarPdfDictamenesPcl917',
                            data: datos_generar_dictamenes,
                            success: function(pdfResponse) {
                                // la respuesta de generarPdfDictamenPcl
                                // console.log('PDF generado');
                            },
                            error: function(xhr) {
                                console.error('Error al generar el PDF', xhr);
                            }
                        }); 
                    }

                    setTimeout(function(){
                        $('#div_alerta_dictamen_pericial').addClass('d-none');
                        $('.alerta_dictamen_pericial').empty();   
                        location.reload();
                    }, 3000);   
                }
            }          
        });
    })

    // Funcionalidad para insertar las etiquetas de diagnosticos cie10 y origen Notificacion calificacion numerica
    $("#cuerpo_comunicado").summernote({
        height: 'auto',
        toolbar: false,
        callbacks: {
            onPaste: function (e) {
                var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                e.preventDefault();
                document.execCommand('insertText', false, bufferText);
            }
        }
    });
    $('.note-editing-area').css("background", "white");
    $('.note-editor').css("border", "1px solid black");    

    var entidad_conocimiento = $("#entidad_conocimiento").val();
    // Retornar el texto por defecto en el asunto y cuerpo del comunicado

    var oficioremisoriopcl = $('#oficiopcl');
    oficioremisoriopcl.change(function(){
        if ($(this).prop('checked')) {
            $("#Asunto").val("Calificación de Pérdida de Capacidad Laboral al Fondo de Pensiones Porvenir S.A.");
            var texto_insertar = "<p>Hola, ¡{{$Nombre_afiliado}}! </p>"+
            "<p>En Seguros de Vida Alfa S.A. siempre buscamos la protección y satisfacción de nuestros clientes. De acuerdo con tu solicitud de  "+ 
            "calificación de pérdida de capacidad laboral (PCL) en la <b>AFP Porvenir S.A.</b>, te informamos que el historial médico ha sido revisado y "+
            "calificado por el grupo interdisciplinario de calificación de <b>Seguros de Vida Alfa S.A.</b>(1).</p>"+
            "<p>De acuerdo con los parámetros establecidos en el Manual Único para la Calificación de la Perdida de la Capacidad Laboral y "+
            "Ocupacional (2) se ha determinado una (PCL) de {{$PorcentajePcl_dp}}  y fecha de estructuración {{$F_estructuracionPcl_dp}} Origen {{$OrigenPcl_dp}}.</p>"+
            "<p>Si tu calificación es igual o superior al 50%, podrás iniciar los trámites ante la AFP Porvenir (3) para acceder a la prestación "+
            "económica correspondiente, previo el cumplimiento de los requisitos legales para acceder a la pensión de invalidez (4). Si este es tu "+
            "caso, podrás iniciar tu solicitud pensional a través de a página web www.porvenir.com.co o llamando a la línea de atención al cliente "+
            "de Porvenir 018000510800, con el fin de solicitar una cita para la radicación de la documentación.</p>"+
            "<p>En caso de que no te encuentres de acuerdo con la calificación emitida por Seguros de Vida Alfa S.A., cuentas con diez (10) días "+
            "hábiles siguientes a partir de la fecha de recibida la notificación para manifestar tu inconformidad frente a resultado. Esta manifestación se debe realizar por escrito y debe estar dirigida a Seguros de Vida Alfa S.A. en donde expreses sobre cuál o cuáles de los siguientes aspectos te encuentras en desacuerdo: <br><br>- Pérdida de capacidad laboral <br> - Origen <br>  - Fecha de estructuración <br><br> La carta debe ser remitida por medio de correo certificado a la dirección <strong>Carrera 10 # 18-36, piso 4, edificio José María Córdoba en "+
            "Bogotá o a inconformidad@segurosalfa.com.co.</strong> Ten presente que el comunicado debe venir firmado por ti, relacionando los datos de localización. Posterior a la revisión de tu carta, procederemos a remitir tu expediente a la respectiva Junta Regional de Calificación de Invalidez para obtener una segunda calificación."+
            "<p> Una vez realizada la solicitud, a más tardar en (15) quince días hábiles recibirás por parte de Seguros de Vida Alfa S.A. una comunicación donde te informaremos el estado del proceso. </p>"
            //"manifestación se debe realizar por escrito y debe estar dirigida a Seguros de Vida Alfa S.A. en donde expreses sobre cuál o cuáles de"+
            //"los siguientes aspectos te encuentras en desacuerdo: </p>"+
            //"<p>- Pérdida de capacidad laboral</p>"+
            //"<p>- Origen</p>"+
            //"<p>- Fecha de estructuración</p>"+
            //"<p>La carta debe ser remitida por medio de correo certificado a la dirección Carrera 10 # 18-36, piso 4 edificio José María Córdoba en "+
            //"Bogotá o a inconformidad@segurosalfa.com.co. Ten presente que el comunicado debe venir firmado por ti, relacionando los datos de "+
            //"localizaci ón. Posterior a la revisión de tu carta, procederemos a remitir tu expediente a la respectiva Junta Regional de "+
            //"Calificación de Invalidez para obtener una segunda calificación.</p>"+
            //"<p>Una vez realizada la solicitud, a más tardar en (15) quince días hábiles recibirás por parte de Seguros de Vida Alfa S.A. una "+
            //"comunicación donde te informaremos el estado del proceso.</p>"
            ;
            //console.log(texto_insertar);
            $('#cuerpo_comunicado').summernote('code', texto_insertar);

            // Habilitación etiquetas
            $("#btn_insertar_Nombre_afiliado").prop('disabled', false);
            $("#btn_insertar_porPcl").prop('disabled', false);
            $("#btn_insertar_F_estructuracion").prop('disabled', false);
            $("#btn_insertar_Origen").prop('disabled', false);

            // Selección automática de las copias a partes interesadas: Empleador, Eps, Arl, Afp, Afp conocimiento
            $("#empleador").prop('checked', true);
            $("#eps").prop('checked', true);
            $("#arl").prop('checked', true);
            // $("#afp").prop('checked', true);
            
            // Se valida si han marcado como si la opcion de la entidad de conocimiento (afp)
            if (entidad_conocimiento != '' && entidad_conocimiento == "Si") {
                $("#afp_conocimiento").prop('checked', true);
            }

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 1;
            $("#anexos").val(seteo_nro_anexos);

            // Selección automática del checkbox firmar
            $("#firmar").prop('checked', true);

        }else{
            $("#Asunto").val("");
            var texto_insertar = "";
            $('#cuerpo_comunicado').summernote('code', texto_insertar);

            // Deshabilitación etiquetas
            $("#btn_insertar_Nombre_afiliado").prop('disabled', true);
            $("#btn_insertar_porPcl").prop('disabled', true);
            $("#btn_insertar_F_estructuracion").prop('disabled', true);
            $("#btn_insertar_Origen").prop('disabled', true);

            // Deselección automática de las copias a partes interesadas: Empleador, Eps, Arl, Afp, Afp conocimiento
            $("#empleador").prop('checked', false);
            $("#eps").prop('checked', false);
            $("#arl").prop('checked', false);
            // $("#afp").prop('checked', false);
            
            // Se valida si han marcado como si la opcion de la entidad de conocimiento (afp)
            if (entidad_conocimiento != '' && entidad_conocimiento == "Si") {
                $("#afp_conocimiento").prop('checked', false);
            }

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos").val(seteo_nro_anexos);

            // Deselección automática del checkbox firmar
            $("#firmar").prop('checked', false);
        }       
    });

    var oficioremisorioincapcl = $('#oficioinca');
    oficioremisorioincapcl.change(function(){
        if ($(this).prop('checked')) {
            $("#Asunto").val("Calificación de Pérdida de Capacidad Laboral al Fondo de Pensiones Porvenir S.A.");
            var texto_insertar = "<p>Respetado (a) {{$Nombre_afiliado}}, cordial saludo: </p>"+
            "<p>Teniendo en cuenta que usted ha cumplido los términos de incapacidad temporal prolongada establecidos por la ley, la <b>AFP Porvenir "+ 
            "S.A.</b> en cumplimiento de la normatividad legal vigente, procede a notificarle el dictamen de calificación con respecto a las patologías "+
            "padecidas por usted y sustentadas en las historias clínicas aportadas que hacen parte integral de su expediente.</p>"+
            "<p>Para el caso particular, es necesario resaltar el siguiente acápite legal: Literal a) del Artículo 29 del Decreto 1352 de 2013.</p>"+
            "<p>Teniendo en cuenta lo anterior nos permitimos informarle que el grupo interdisciplinario de <b>Seguros de Vida Alfa S.A</b>, aseguradora que "+
            "maneja el seguro previsional de los afiliados a la <b>AFP Porvenir</b>, emitió dictamen de calificación de origen y pérdida de la capacidad "+
            "laboral (PCL), definiendo para su caso lo siguiente:</p>"+
            "<table class='tabla_cuerpo'>" +
            "<tr>" +
            "<th>Porcentaje</th>" +
            "<th>Origen</th>" +
            "<th>Fecha de estructuración </th>" +
            "</tr>" +
            "<tr>" +
            "<td>{{$PorcentajePcl_dp}}</td>" +
            "<td>{{$OrigenPcl_dp}}</td>" +
            "<td>{{$F_estructuracionPcl_dp}}</td>" +
            "</tr>" +
            "</table>"+
            "<p>Le informamos que de no encontrarse de acuerdo con la calificación emitida, usted tiene la posibilidad de manifestar a Seguros de "+
            "Vida Alfa S.A. su inconformidad dentro de los diez (10) días siguientes a partir de la fecha de recibida la notificación, evento en "+
            "el cual procederemos a remitir su caso a la respectiva Junta Regional de Calificación de Invalidez para obtener una segunda calificación.</p>"+
            "<p>Dicha manifestación debe realizarla por escrito dirigida a Seguros de Vida Alfa, en la que debe expresar sobre cuál de los aspectos "+
            "apela: origen, pérdida de capacidad laboral y/o fecha de estructuración. Remitirla a la <b>Cra. 10 N° 18 - 36, piso 4, Edificio José María "+
            "Córdoba en Bogotá</b>, al fax 7435333 ext.14440 0 al correo electrónico: inconformidad@segurosalfa.com.co.</p>";
            $('#cuerpo_comunicado').summernote('code', texto_insertar);

            // Habilitación etiquetas
            $("#btn_insertar_Nombre_afiliado").prop('disabled', false);
            $("#btn_insertar_porPcl").prop('disabled', false);
            $("#btn_insertar_F_estructuracion").prop('disabled', false);
            $("#btn_insertar_Origen").prop('disabled', false);

            // Selección automática de las copias a partes interesadas: Empleador, Eps, Arl, Afp, Afp conocimiento
            $("#empleador").prop('checked', true);
            $("#eps").prop('checked', true);
            $("#arl").prop('checked', true);
            // $("#afp").prop('checked', true);
            
            // Se valida si han marcado como si la opcion de la entidad de conocimiento (afp)
            if (entidad_conocimiento != '' && entidad_conocimiento == "Si") {
                $("#afp_conocimiento").prop('checked', true);
            }

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 1;
            $("#anexos").val(seteo_nro_anexos);

            // Selección automática del checkbox firmar
            $("#firmar").prop('checked', true);

        }else{
            $("#Asunto").val("");
            var texto_insertar = "";
            $('#cuerpo_comunicado').summernote('code', texto_insertar);

            // Deshabilitación etiquetas
            $("#btn_insertar_Nombre_afiliado").prop('disabled', true);
            $("#btn_insertar_porPcl").prop('disabled', true);
            $("#btn_insertar_F_estructuracion").prop('disabled', true);
            $("#btn_insertar_Origen").prop('disabled', true);

            // Deselección automática de las copias a partes interesadas: Empleador, Eps, Arl, Afp, Afp conocimiento
            $("#empleador").prop('checked', false);
            $("#eps").prop('checked', false);
            $("#arl").prop('checked', false);
            // $("#afp").prop('checked', false);
            
            // Se valida si han marcado como si la opcion de la entidad de conocimiento (afp)
            if (entidad_conocimiento != '' && entidad_conocimiento == "Si") {
                $("#afp_conocimiento").prop('checked', false);
            }

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos").val(seteo_nro_anexos);

            // Deselección automática del checkbox firmar
            $("#firmar").prop('checked', false);
        }        
    });
    
    
    $("#btn_insertar_Nombre_afiliado").click(function(e){
        e.preventDefault();

        var etiqueta_Nombre_afiliado = "{{$Nombre_afiliado}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta_Nombre_afiliado);
    }); 

    $("#btn_insertar_porPcl").click(function(e){
        e.preventDefault();

        var etiqueta_porPCL = "{{$PorcentajePcl_dp}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta_porPCL);
    });  
    
    $("#btn_insertar_F_estructuracion").click(function(e){
        e.preventDefault();

        var etiqueta_F_estructuracion = "{{$F_estructuracionPcl_dp}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta_F_estructuracion);
    });

    $("#btn_insertar_Origen").click(function(e){
        e.preventDefault();

        var etiqueta_nombreCIE10 = "{{$OrigenPcl_dp}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta_nombreCIE10);
    });

    // Funcionalidad para insertar las etiquetas de diagnosticos cie10 y origen Notificacion calificacion cero

    $("#cuerpo_comunicado_cero").summernote({
        height: 'auto',
        toolbar: false,
        callbacks: {
            onPaste: function (e) {
                var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                e.preventDefault();
                document.execCommand('insertText', false, bufferText);
            }
        }
    });
    $('.note-editing-area').css("background", "white");
    $('.note-editor').css("border", "1px solid black");

    // Retornar el texto por defecto en el asunto y cuerpo del comunicado

    var notificacionpclcero = $('#notificacionpclcero');

    notificacionpclcero.change(function(){
        if ($(this).prop('checked')) {
            $("#Asunto").val("CALIFICACIÓN DE PÉRDIDA DE CAPACIDAD LABORAL");
            var texto_insertar = "<p>Reciba usted un cordial saludo de Seguros de Vida Alfa S.A</p>"+
           "<p>De la manera más atenta queremos informar el resultado de la calificación realizada por el Grupo Interdisciplinario de Calificación "+ 
           "de Origen y Pérdida de la Capacidad Laboral de la Administradora de Riesgos Laborales de "+
           "Seguros de Vida Alfa S.A. El cual dio un porcentaje de {{$PorcentajePcl_cero}}; por el diagnostico {{$CIE10Nombres_cero}} "+
           "por lo tanto NO procede el pago de indemnización por Incapacidad Permanente Parcial, ya que de acuerdo con el Artículo 5 Ley 776 de 2002 "+
           "<strong>“Se considera como incapacitado permanente parcial, al afiliado que, como consecuencia de un accidente de trabajo o una enfermedad profesional, "+
           "presenta una disminución definitiva, igual o superior al 5% pero inferior al 50% de su capacidad laboral, para la cual ha sido contratado o capacitado”</strong>.</p>"+
           "<p>El dictamen de calificación del que anexo copia, puede ser apelado ante esta Administradora, dentro de los (10) diez días siguientes a partir de su notificación, "+
           " de acuerdo al Decreto 0019 de 2012 artículo 142, en la Carrera 10 #18-36 piso 4°, Edificio José María Córdoba, Bogotá.</p>"+
           "<p>Favor informar en la carta el motivo de su desacuerdo y en el asunto manifestar que es una inconformidad al dictamen "+
           "Cualquier información adicional con gusto será atendida por Auditoría Técnica de nuestra sucursal más cercana a su residencia.</p>";
            $('#cuerpo_comunicado_cero').summernote('code', texto_insertar);  
        }
        setTimeout(function() {
            notificacionpclcero.prop("checked", false);
        }, 3000);
    });

    $("#btn_insertar_porPcl_cero").click(function(e){
        e.preventDefault();

        var etiqueta_porPCL_cero = "{{$PorcentajePcl_cero}}";
        $('#cuerpo_comunicado_cero').summernote('editor.insertText', etiqueta_porPCL_cero);
    }); 
    
    $("#btn_insertar_nombreCIE10_cero").click(function(e){
        e.preventDefault();

        var etiqueta_nombreCIE10_cero = "{{$CIE10Nombres_cero}}";
        $('#cuerpo_comunicado_cero').summernote('editor.insertText', etiqueta_nombreCIE10_cero);
    });

    /* Funcionalidad para mostrar solo la tabla de comunicados para el rol de Consulta */
    if (idRol == 7) {
        $("#form_CaliTecDecreto").addClass('d-none');
        $(".columna_row1_interconsulta").addClass('d-none');
        $(".columna_row1_motivo_cali").addClass('d-none');
        $(".columna_row1_deficiencia").addClass('d-none');
        $(".columna_row1_valoracion_laboral").addClass('d-none');
        $("#form_libros_2_3").addClass('d-none');
        $(".columna_row1_dictamen").addClass('d-none');
        $("#div_comite_interdisciplinario").addClass('d-none');
        $("#div_correspondecia").addClass('d-none');
        $("#editar_correspondencia").addClass('d-none');
        $("#btn_guardar_actualizar_correspondencia").prop('disabled',true);
    }

    // A los usuarios que no tengan el rol Administrador se les aplica los siguientes controles en el formulario de correspondencia:
    // inhabilita los campos nro anexos, asunto, etiquetas, cuerpo comunicado, firmar
    if (idRol != 6) {
        $("#anexos").prop('readonly', true);
        $("#Asunto").prop('readonly', true);
        $("#btn_insertar_Nombre_afiliado").prop('disabled', true);
        $("#btn_insertar_porPcl").prop('disabled', true);
        $("#btn_insertar_F_estructuracion").prop('disabled', true);
        $("#btn_insertar_Origen").prop('disabled', true);
        $(".note-editable").attr("contenteditable", false);
        $("#firmar").prop('disabled', true);
    }

    //Valida si hay radicados duplicados
    setTimeout(function() {
        radicados_duplicados('listado_comunicados_clpcl');
    }, 500);
});
// guardar examenes de interconsulta
$(document).ready(function(){
    $("#guardar_datos_examenes").click(function(){       
        $('#guardar_datos_examenes').prop('disabled', true);                    
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
            'Estado_Recalificacion': 'Inactivo',
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
// remover filas de examenes de interconsulta
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
        //width: '100%',
        width: '270px',
        placeholder: "Seleccione",
        allowClear: false
    });

    $("#lista_origenCie10_fila_"+num_consecutivo).select2({
        // width: '100%',
        width: '126.062px',
        placeholder: "Seleccione",
        allowClear: false
    });

    $("#lista_lateralidadCie10_fila_"+num_consecutivo).select2({
        // width: '100%',
        width: '126.062px',
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

    let listado_LateralidadCIE10 = {
        '_token': token,
        'parametro' : "listado_LateralidadCIE10",
    };
    $.ajax({
        type:'POST',
        url:'/selectoresCalificacionTecnicaPCL',
        data: listado_LateralidadCIE10,
        success:function(data){
            // $("select[id^='lista_origenCie10_fila_']").empty();
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#lista_lateralidadCie10_fila_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
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
// guardar disgnosticos cie10
$(document).ready(function(){
    $("#guardar_datos_cie10").click(function(){       
        $('#guardar_datos_cie10').prop('disabled', true);                        
        let token = $("input[name='_token']").val();
        var guardar_datos = [];
        var datos_finales_diagnosticos_moticalifi = [];
        var array_id_filas = [];
        // RECORREMOS LOS TD DE LA TABLA PARA EXTRAER LOS DATOS E INSERTARLOS EN UN ARREGLO (LA INSERCIÓN LA HACE POR CADA FILA, POR ENDE, ES UN ARRAY MULTIDIMENSIONAL)
        $('#listado_diagnostico_cie10 tbody tr').each(function (index) {
            // array_id_filas.push($(this).attr('id'));
            // if ($(this).attr('id') !== "datos_diagnostico") {
                $(this).children("td").each(function (index2) {
                    var nombres_ids = $(this).find('*').attr("id");
                    // if (nombres_ids != undefined) {
                    //     guardar_datos.push($('#'+nombres_ids).val());                                                
                    // }

                    // if((index2+1) % 5 === 0){
                    //     datos_finales_diagnosticos_moticalifi.push(guardar_datos);
                    //     guardar_datos = [];
                    // }
                    if (nombres_ids != undefined) {                                              
                        // Se extrae la info si se eligió o no el selector CIE10
                        if (nombres_ids.startsWith("lista_Cie10_fila_")) {
                            valor_select_cie10 = $("#"+nombres_ids).val();                              
                            guardar_datos.push(valor_select_cie10);                                                     
                        }
                        // Se extrae la info del input 
                        if (nombres_ids.startsWith("nombre_cie10_fila_")) {
                            valor_input = $("#"+nombres_ids).val();                              
                            guardar_datos.push(valor_input);                                                     
                        }
                        // Se extrae la info si se eligió o no el selector lateralidad
                        if (nombres_ids.startsWith("lista_lateralidadCie10_fila_")) {
                            valor_select_lateralidad = $("#"+nombres_ids).val();                              
                            guardar_datos.push(valor_select_lateralidad);                                                     
                        }
                        // Se extrae la info si se eligió o no el selector Origen
                        if (nombres_ids.startsWith("lista_origenCie10_fila_")) {
                            valor_select_origen = $("#"+nombres_ids).val();                              
                            guardar_datos.push(valor_select_origen);                                                     
                        }
                        // Se extrae la info si se eligió o no el checkbox DX PRINCIPAL
                        if (nombres_ids.startsWith("checkbox_dx_principal_cie10_")) {
                            if($("#"+nombres_ids).is(':checked')){
                                // console.log("si dx");
                                guardar_datos.push("Si");
                            }else{
                                // console.log("no dx");
                                guardar_datos.push("No");
                            }
                        }
                        // Se extrae la info del textarea
                        if (nombres_ids.startsWith("descripcion_cie10_fila_")) {
                            valor_textarea = $("#"+nombres_ids).val();                              
                            guardar_datos.push(valor_textarea);                                                     
                        }
                    }
                    // console.log(guardar_datos);
                    if((index2+1) % 6 === 0){
                        datos_finales_diagnosticos_moticalifi.push(guardar_datos);
                        guardar_datos = [];
                    }
                });
            // }
        });
        // console.log(datos_finales_diagnosticos_moticalifi);

        // ENVÍO POR AJAX LA INFORMACIÓN FINAL DE LA TABLA, JUNTO CON EL ID EVENTO, ID ASIGNACION, ID PROCESO
                  
        var envio_datos_diagnosticos = {
            '_token': token,
            'datos_finales_diagnosticos_moticalifi' : datos_finales_diagnosticos_moticalifi,
            'Id_evento': $('#Id_Evento_decreto').val(),
            'Id_Asignacion': $('#Id_Asignacion_decreto').val(),
            'Id_proceso': $('#Id_Proceso_decreto').val(), 
            'Estado_Recalificacion': 'Inactivo',
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
// remover diagnosticos
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
                        location.reload();
                    }, 3000);
                }
                if (response.total_registros == 0) {
                    $("#conteo_listado_diagnosticos_moticalifi").val(response.total_registros);
                }
            }
        });        

    });
});
// Dx principal diganosticos cie10
$(document).ready(function(){
    $(document).on('click', "input[id^='dx_principal_diganostico_']", function(){        
        var fila = $(this).data("id_fila_dx_principal_diagnostico");
        var checkboxDxPrincipal = document.getElementById('dx_principal_diganostico_'+fila);        
        let token = $("input[name='_token']").val();      
        var banderaDxPrincipalDA = $('#banderaDxPrincipalDA').val();   
        
        if (checkboxDxPrincipal.checked) {
            var datos_actualizar_dxPrincial_deficiencias_alteraciones = {
                '_token': token,
                'fila':fila,
                'banderaDxPrincipalDA': banderaDxPrincipalDA,
                'Id_evento': $('#Id_Evento_decreto').val()

            };       
            $.ajax({
                type:'POST',
                url:'/actualizarDxPrincipalDiagnosticos',
                data: datos_actualizar_dxPrincial_deficiencias_alteraciones,
                success:function(response){
                    // console.log(response);
                    if (response.parametro == "fila_dxPrincipalDiagnostico_agregado") {
                        $('#resultado_insercion_cie10').empty();
                        $('#resultado_insercion_cie10').removeClass('d-none');
                        $('#resultado_insercion_cie10').addClass('alert-success');
                        $('#resultado_insercion_cie10').append('<strong>'+response.mensaje+'</strong>');
                        
                        setTimeout(() => {
                            $('#resultado_insercion_cie10').addClass('d-none');
                            $('#resultado_insercion_cie10').removeClass('alert-success');
                            $('#resultado_insercion_cie10').empty();
                            $('#banderaDxPrincipalDA').val("");
                            location.reload();
                        }, 3000);
                    }                
                }
            });
        }else {     
            banderaDxPrincipalDA = 'NoDxPrincipal_diagnostico';            
            var datos_actualizar_dxPrincial_deficiencias_alteraciones = {
                '_token': token,
                'fila':fila,
                'banderaDxPrincipalDA': banderaDxPrincipalDA,
                'Id_evento': $('#Id_Evento_decreto').val()
            };      
            
            $.ajax({
                type:'POST',
                url:'/actualizarDxPrincipalDiagnosticos',
                data: datos_actualizar_dxPrincial_deficiencias_alteraciones,
                success:function(response){
                    // console.log(response);
                    if (response.parametro == "fila_dxPrincipalDiagnostico_eliminado") {
                        $('#resultado_insercion_cie10').empty();
                        $('#resultado_insercion_cie10').removeClass('d-none');
                        $('#resultado_insercion_cie10').addClass('alert-success');
                        $('#resultado_insercion_cie10').append('<strong>'+response.mensaje+'</strong>');
                        
                        setTimeout(() => {
                            $('#resultado_insercion_cie10').addClass('d-none');
                            $('#resultado_insercion_cie10').removeClass('alert-success');
                            $('#resultado_insercion_cie10').empty();
                            $('#banderaDxPrincipalDA').val("");
                            location.reload();
                        }, 3000);
                    }                
                }
            }); 
        }

    });
});
// Dx Principal inactivar y activar checkbox Diagnosticos
$(document).ready(function(){      
    function desavalidarCheckboxes2(checkbox2Id) {
        
        var numeroCheckbox2Seleccionado = checkbox2Id.split('_').pop();
        var checkboxes2 = document.querySelectorAll('[id^="checkbox_dx_principal_cie10_"]');
        checkboxes2.forEach(function(checkboxnew) {
            var numeroCheckbox = checkboxnew.id.split('_').pop();
            if (numeroCheckbox !== numeroCheckbox2Seleccionado) {
                checkboxnew.disabled = true;
            }
        });       

        $("[id^='dx_principal_diganostico_']").prop('disabled', true);
    }     

    function habivalidarCheckboxes2(checkbox2Id) {
        var numeroCheckbox2Seleccionado = checkbox2Id.split('_').pop();
    
        var checkboxes2 = document.querySelectorAll('[id^="checkbox_dx_principal_cie10_"]');
        checkboxes2.forEach(function(checkboxnew) {
            var numeroCheckbox = checkboxnew.id.split('_').pop();
            if (numeroCheckbox !== numeroCheckbox2Seleccionado) {
                checkboxnew.disabled = false;
            }
        });     
        $("[id^='dx_principal_diganostico_']").prop('disabled', false);

        localStorage.removeItem('filas');
        localStorage.removeItem('checkboxDxPrincipalNew');

        $('a[data-fila^="fila_"]').removeAttr('style');
    } 

    $(document).on('click', "input[id^='checkbox_dx_principal_cie10_']", function(){
        var filas = $(this).data("id_fila_checkbox_dx_principal_cie10_");
        localStorage.setItem("filas", filas);
        localStorage.setItem('checkboxDxPrincipalNew', $(this).prop('checked'));
        if ($(this).prop('checked')) {
            desavalidarCheckboxes2('checkbox_dx_principal_cie10_'+filas);
        }else{
            habivalidarCheckboxes2('checkbox_dx_principal_cie10_'+filas);
        }
    });
   
    var idCompleto = '';
    function capturarIdCheckbox() {
        $("[id^='checkbox_dx_principal_cie10_']").each(function() {
          if ($(this).is(':checked')) {
            idCompleto = $(this).attr('id');
            return false;
          }
        });    
        return idCompleto;
    }
    setInterval(() => {
        var filasnews = localStorage.getItem("filas")
        if (localStorage.getItem('checkboxDxPrincipalNew') === 'true') {
            desavalidarCheckboxes2('checkbox_dx_principal_cie10_'+filasnews);
            capturarIdCheckbox();    
            // console.log(idCompleto);                 
            // var matchResult = idCompleto.match(/\d+/);
            var matchResult = idCompleto.substr(idCompleto.lastIndexOf('_') + 1);      
            // console.log(matchResult);                 
            if (matchResult && matchResult.length > 0) {
                var newfilas = parseInt(matchResult[0]);  
                // console.log(newfilas);
                if(newfilas > 0){
                    $('a[data-fila="fila_'+newfilas+'"]').css({
                        "cursor": "not-allowed",
                        "pointer-events": "none"
                    }).attr('disabled', true); 
                }
            }
              
        }
    }, 500);
    
    setInterval(() => {
             
        var checkboxes = $('[id^="dx_principal_diganostico_"]');
        checkboxes.each(function() {
            var id_checkbox_dx_principal_deficiencia = $(this).attr('id');
            if ($("#" + id_checkbox_dx_principal_deficiencia).is(':checked')) {
                //console.log("Este checkbox " + id_checkbox_dx_principal_deficiencia + " está chequeado");                          
                var numeroCheckboxSeleccionado = id_checkbox_dx_principal_deficiencia.split('_').pop();
                var checkboxes = document.querySelectorAll('[id^="dx_principal_diganostico"]');
                checkboxes.forEach(function(checkbox) {
                    var numeroCheckbox = checkbox.id.split('_').pop();
                    if (numeroCheckbox !== numeroCheckboxSeleccionado) {
                        checkbox.disabled = true;
                    }
                });          
                $("input[id^='checkbox_dx_principal_cie10_']").prop("disabled", true);
            }            
        });

    }, 500);
});
/* Función para añadir los controles de cada elemento de cada fila en la tabla deficiencias decreto cero*/
function funciones_elementos_fila_deficienciasDecretocero(num_decretoceroconse) {
    // Inicializacion de select 2
    $("#lista_tabla_"+num_decretoceroconse).select2({
        width: '100%',
        placeholder: "Seleccione",
        allowClear: false
    });
    

    //Carga de datos en los selectores

    let token = $("input[name='_token']").val();
    let datos_decretocero = {
        '_token': token,
        'parametro' : "listado_tablas_decreto",
    };
    $.ajax({
        type:'POST',
        url:'/ListadoSelectoresDefiAlteraciones',
        data: datos_decretocero,
        success:function(data){
            // $("select[id^='lista_Cie10_fila_']").empty();
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#lista_tabla_"+num_decretoceroconse).append('<option value="'+data[claves[i]]["Id_tabla"]+'">'+data[claves[i]]["Ident_tabla"]+'-'+data[claves[i]]["Nombre_tabla"]+'</option>');
            }
        }
    });   

    $(document).on('change', '#lista_tabla_'+num_decretoceroconse, function() {        
        let Id_tabla = $(this).val();        
        let datos_Nombre_tabla = {
            '_token': token,
            'parametro' : "nombre_tabla",
            'Id_tabla': Id_tabla,
        };    
        $.ajax({
            type:'POST',
            url:'/ListadoSelectoresDefiAlteraciones',
            data: datos_Nombre_tabla,
            success:function(data){
                //console.log(data);                
                $("#titulotabla_"+num_decretoceroconse).empty();
                $("#titulotabla_"+num_decretoceroconse).append(data[0]["Nombre_tabla"]);
            }
        });
    });
}
// guardar deficiencicias decreto cero
$(document).ready(function(){
    $("#guardar_deficiencias_DecretoCero").click(function(){       
        $('#guardar_deficiencias_DecretoCero').prop('disabled', true);            
        let token = $("input[name='_token']").val();
        var guardar_datos_alteraciones = [];
        var datos_finales_deficiciencias_decreto_cero = [];      

        $("#listado_deficiencias_decretoCero tbody tr").each(function (index){
            $(this).children("td").each(function (index2) {

                // extraemos todos los id
                var nombres_ids_alteraciones = $(this).find('*').attr("id");

                if (nombres_ids_alteraciones != undefined) {
                    
                    // Extraemos el id de la tabla
                    if (nombres_ids_alteraciones.startsWith("lista_tabla_")) {
                        var idtabla = $("#"+nombres_ids_alteraciones).val();
                        // console.log(idtabla);
                        guardar_datos_alteraciones.push(idtabla);
                    }

                    // Analizamos si existe un select, input o text para extraer la información.
                    if ($("#"+nombres_ids_alteraciones).val() == "") {
                        var hay_select = '#'+nombres_ids_alteraciones+' select';
                        var hay_input = '#'+nombres_ids_alteraciones+' input';
                        if ($(hay_select).attr("id") != undefined) {
                            var selector = $(hay_select).attr("id");
                            var valor_select = $("#"+selector).val();
                        }else if($(hay_input).attr("id") != undefined){
                            var entrada_texto = $(hay_input).attr("id");
                            var valor_input = $("#"+entrada_texto).val();
                        }else{
                            var valor_texto = $("#"+nombres_ids_alteraciones).text();
                        }

                        if (valor_select != undefined) {
                            // console.log(valor_select);
                            guardar_datos_alteraciones.push(valor_select);
                        }
                        if (valor_input != undefined && valor_input != "on") {
                            // console.log(valor_input);
                            guardar_datos_alteraciones.push(valor_input);
                        }

                        if (valor_texto) {
                            // console.log(valor_texto);
                            guardar_datos_alteraciones.push(valor_texto);
                        }
                      
                    }               
                    
                }

                if((index2+1) % 3 === 0){
                    guardar_datos_alteraciones.splice(1,1);
                    datos_finales_deficiciencias_decreto_cero.push(guardar_datos_alteraciones);
                    //console.log(datos_finales_deficiciencias_decreto_cero);
                    guardar_datos_alteraciones = [];
                }
            });
        });
        //console.log(datos_finales_deficiciencias_decreto_cero);        
        // ENVÍO POR AJAX LA INFORMACIÓN FINAL DE LA TABLA, JUNTO CON EL ID EVENTO, ID ASIGNACION, ID PROCESO
                  
        var envio_datos_deficiencia_decretocero = {
            '_token': token,
            'datos_finales_deficiciencias_decreto_cero' : datos_finales_deficiciencias_decreto_cero,
            'Id_evento': $('#Id_Evento_decreto').val(),
            'Id_Asignacion': $('#Id_Asignacion_decreto').val(),
            'Id_proceso': $('#Id_Proceso_decreto').val(),  
            'Estado_Recalificacion': 'Inactivo',
        };  

        //console.log(envio_datos_deficiencia_decretocero);
        
        $.ajax({
            type:'POST',
            url:'/guardarDeficieciasDecretosCero',
            data: envio_datos_deficiencia_decretocero,
            success:function(response){
                // console.log(response);
                if (response.parametro == "inserto_informacion_deficiencias_decreto_cero") {
                    $('#insercion_decreto_cero').removeClass('d-none');
                    $('#insercion_decreto_cero').addClass('alert-success');
                    $('#insercion_decreto_cero').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(() => {
                        $('#insercion_decreto_cero').addClass('d-none');
                        $('#insercion_decreto_cero').removeClass('alert-success');
                        $('#insercion_decreto_cero').empty();
                        location.reload();
                    }, 3000);
                }
            }          
            
        });      
               
    });     
});
// remover deficiencias decreto cero
$(document).ready(function(){
    $(document).on('click', "a[id^='btn_remover_deficiencias_decretocero_']", function(){

        let token = $("input[name='_token']").val();
        var datos_fila_quitar_deficiencia_cero = {
            '_token': token,
            'fila' : $(this).data("id_fila_quitar"),
            'Id_evento': $('#Id_Evento_decreto').val()
        };

        //console.log(datos_fila_quitar_deficiencia_cero);
        
        $.ajax({
            type:'POST',
            url:'/eliminarDeficieciasDecretosCero',
            data: datos_fila_quitar_deficiencia_cero,
            success:function(response){
                // console.log(response);
                if (response.parametro == "fila_deficiencia_cero_eliminada") {
                    $('#insercion_decreto_cero').empty();
                    $('#insercion_decreto_cero').removeClass('d-none');
                    $('#insercion_decreto_cero').addClass('alert-success');
                    $('#insercion_decreto_cero').append('<strong>'+response.mensaje+'</strong>');
                    
                    setTimeout(() => {
                        $('#insercion_decreto_cero').addClass('d-none');
                        $('#insercion_decreto_cero').removeClass('alert-success');
                        $('#insercion_decreto_cero').empty();
                    }, 3000);
                }
                if (response.total_registros == 0) {
                    $("#conteo_listado_deficiencia_alteraciones").val(response.total_registros);
                }
            }
        });        

    });
});
// remover filas agudeza auditiva
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
// Dx Principal agudeza auditiva
$(document).ready(function(){
    $(document).on('click', "input[id^='dx_principal_deficiencia_auditiva_']", function(){

        var dataAuditiva = $(this).data("id_fila_dx_auditiva")
        var checkboxDxPrincipal = document.getElementById('dx_principal_deficiencia_auditiva_'+dataAuditiva);        
        let token = $("input[name='_token']").val();
        var dxPrincipal = $('#dx_principal_deficiencia_auditiva_'+dataAuditiva).val();
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
                url:'/actualizarDxPrincipalAgudezaAuditiva',
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
                url:'/actualizarDxPrincipalAgudezaAuditiva',
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
// DX Principal agudeza visual
$(document).ready(function(){
    $(document).on('click', "input[id^='dx_principal_deficiencia_visual_']", function(){

        var dataVisual = $(this).data("id_fila_dx_visual")
        var checkboxDxPrincipal = document.getElementById('dx_principal_deficiencia_visual_'+dataVisual);      
        let token = $("input[name='_token']").val();
        var dx_principal_visual = $('#dx_principal_deficiencia_visual_'+dataVisual).val();
        var banderaDxPrincipal_visual = $('#banderaDxPrincipal_visual').val();
        if (checkboxDxPrincipal.checked) {
            //console.log(banderaDxPrincipal_visual);
            var datos_actualizar_dxPrincial_visual = {
                '_token': token,
                'dx_principal_visual' : dx_principal_visual,
                'banderaDxPrincipal_visual': banderaDxPrincipal_visual,
                'Id_evento': $('#Id_Evento_decreto').val()
            };       
            $.ajax({
                type:'POST',
                url:'/actualizarDxPrincipalAgudezasVisual',
                data: datos_actualizar_dxPrincial_visual,
                success:function(response){
                    // console.log(response);
                    if (response.parametro == "fila_dxPrincipalagudeza_visual_agregado") {                                               
                        $('#dx_visual').empty();
                        $('#dx_visual').removeClass('d-none');
                        $('#dx_visual').addClass('alert-success');
                        $('#dx_visual').append('<strong>'+response.mensaje+'</strong>');
                        
                        setTimeout(() => {
                            $('#dx_visual').addClass('d-none');
                            $('#dx_visual').removeClass('alert-success');
                            $('#dx_visual').empty();
                            location.reload();
                        }, 3000);
                    }                
                }
            }); 
        }else {                        
            //console.log(banderaDxPrincipal_visual);
            var datos_actualizar_dxPrincial_visual = {
                '_token': token,
                'dx_principal_visual' : dx_principal_visual,
                'banderaDxPrincipal_visual': banderaDxPrincipal_visual,
                'Id_evento': $('#Id_Evento_decreto').val()
            };        
            
            $.ajax({
                type:'POST',
                url:'/actualizarDxPrincipalAgudezasVisual',
                data: datos_actualizar_dxPrincial_visual,
                success:function(response){
                    // console.log(response);
                    if (response.parametro == "fila_dxPrincipalagudeza_visual_agregado") {
                        $('#dx_visual').empty();
                        $('#dx_visual').removeClass('d-none');
                        $('#dx_visual').addClass('alert-success');
                        $('#dx_visual').append('<strong>'+response.mensaje+'</strong>');
                        
                        setTimeout(() => {
                            $('#dx_visual').addClass('d-none');
                            $('#dx_visual').removeClass('alert-success');
                            $('#dx_visual').empty();
                            location.reload();
                        }, 3000);
                    }                
                }
            });
        }

    });
});
// Dx Principal inactivar y activar (disabled) checkbox agudeza audiva, visual y deficiencias por alteraciones
$(document).ready(function(){      
    function desavalidarCheckboxes2(checkbox2Id) {
        
        var numeroCheckbox2Seleccionado = checkbox2Id.split('_').pop();
        var checkboxes2 = document.querySelectorAll('[id^="checkbox_dx_principal_DefiAlteraciones_"]');
        checkboxes2.forEach(function(checkboxnew) {
            var numeroCheckbox = checkboxnew.id.split('_').pop();
            if (numeroCheckbox !== numeroCheckbox2Seleccionado) {
                checkboxnew.disabled = true;
            }
        });       

        $("[id^='dx_principal_deficiencia_auditiva_']").prop('disabled', true);
        $("[id^='dx_principal_deficiencia_visual_']").prop('disabled', true);
        $("[id^='dx_principal_deficiencia_alteraciones_']").prop('disabled', true);
    }     

    function habivalidarCheckboxes2(checkbox2Id) {
        var numeroCheckbox2Seleccionado = checkbox2Id.split('_').pop();
    
        var checkboxes2 = document.querySelectorAll('[id^="checkbox_dx_principal_DefiAlteraciones_"]');
        checkboxes2.forEach(function(checkboxnew) {
            var numeroCheckbox = checkboxnew.id.split('_').pop();
            if (numeroCheckbox !== numeroCheckbox2Seleccionado) {
                checkboxnew.disabled = false;
            }
        });
        $("[id^='dx_principal_deficiencia_auditiva_']").prop('disabled', false);
        $("[id^='dx_principal_deficiencia_visual_']").prop('disabled', false);       
        $("[id^='dx_principal_deficiencia_alteraciones_']").prop('disabled', false);

        localStorage.removeItem('filas');
        localStorage.removeItem('checkboxDxPrincipalNew');

        $('a[data-fila^="fila_alteraciones_"]').removeAttr('style');
    } 

    $(document).on('click', "input[id^='checkbox_dx_principal_DefiAlteraciones_']", function(){
        var filas = $(this).data("id_fila_checkbox_dx_principal_defialteraciones");
        localStorage.setItem("filas", filas);
        localStorage.setItem('checkboxDxPrincipalNew', $(this).prop('checked'));
        if ($(this).prop('checked')) {
            desavalidarCheckboxes2('checkbox_dx_principal_DefiAlteraciones_'+filas);
        }else{
            habivalidarCheckboxes2('checkbox_dx_principal_DefiAlteraciones_'+filas);
        }
    });
   
    var idCompleto = '';
    function capturarIdCheckbox() {
        $("[id^='checkbox_dx_principal_DefiAlteraciones_']").each(function() {
          if ($(this).is(':checked')) {
            idCompleto = $(this).attr('id');
            return false;
          }
        });    
        return idCompleto;
        
    }
    setInterval(() => {
        var filasnews = localStorage.getItem("filas")
        if (localStorage.getItem('checkboxDxPrincipalNew') === 'true') {
            desavalidarCheckboxes2('checkbox_dx_principal_DefiAlteraciones_'+filasnews);
            capturarIdCheckbox();                     
            var matchResult = idCompleto.match(/\d+/);            
            if (matchResult && matchResult.length > 0) {
                var newfilas = parseInt(matchResult[0]);  
                //console.log(newfilas);
                if(newfilas > 0){
                    $('a[data-fila="fila_alteraciones_'+newfilas+'"]').css({
                        "cursor": "not-allowed",
                        "pointer-events": "none"
                    }).attr('disabled', true); 
                }
            }
              
        }
    }, 500);
    
    setInterval(() => {
             
        var checkboxes = $('[id^="dx_principal_deficiencia_alteraciones_"]');
        checkboxes.each(function() {
            var id_checkbox_dx_principal_deficiencia = $(this).attr('id');
            if ($("#" + id_checkbox_dx_principal_deficiencia).is(':checked')) {
                //console.log("Este checkbox " + id_checkbox_dx_principal_deficiencia + " está chequeado");                          
                var numeroCheckboxSeleccionado = id_checkbox_dx_principal_deficiencia.split('_').pop();
                var checkboxes = document.querySelectorAll('[id^="dx_principal_deficiencia_alteraciones"]');
                checkboxes.forEach(function(checkbox) {
                    var numeroCheckbox = checkbox.id.split('_').pop();
                    if (numeroCheckbox !== numeroCheckboxSeleccionado) {
                        checkbox.disabled = true;
                    }
                });          
                $("input[id^='checkbox_dx_principal_DefiAlteraciones_']").prop("disabled", true);
                $("input[id^='dx_principal_deficiencia_auditiva_']").prop("disabled", true);
                $("input[id^='dx_principal_deficiencia_visual_']").prop("disabled", true);
            }            
        });
        
        var id_checkbox_dx_principal_auditivo = $("input[id^='dx_principal_deficiencia_auditiva_']").attr("id");
        if ($("#" + id_checkbox_dx_principal_auditivo).is(':checked')) {
            //console.log("Este checkbox " + id_checkbox_dx_principal_auditivo + " está chequeado"); 
            $("input[id^='checkbox_dx_principal_DefiAlteraciones_']").prop("disabled", true);
            $("input[id^='dx_principal_deficiencia_alteraciones_']").prop("disabled", true);
            $("input[id^='dx_principal_deficiencia_visual_']").prop("disabled", true);
        }

        var id_checkbox_dx_principal_visual = $("input[id^='dx_principal_deficiencia_visual_']").attr("id");
        if ($("#" + id_checkbox_dx_principal_visual).is(':checked')) {
            //console.log("Este checkbox " + id_checkbox_dx_principal_visual + " está chequeado"); 
            $("input[id^='checkbox_dx_principal_DefiAlteraciones_']").prop("disabled", true);
            $("input[id^='dx_principal_deficiencia_alteraciones_']").prop("disabled", true);
            $("input[id^='dx_principal_deficiencia_auditiva_']").prop("disabled", true);
        }

    }, 500);
});
// Guardar deficiencias decreto 3
$(document).ready(function(){
    $("#guardar_deficiencias_Decreto3").click(function(){       
        $('#guardar_deficiencias_Decreto3').prop('disabled', true);           
        let token = $("input[name='_token']").val();
        var guardar_datos_alteraciones = [];
        var datos_finales_deficiciencias_decreto_tres = [];      

        $("#listado_deficiencias_decreto_tres tbody tr").each(function (index){
            $(this).children("td").each(function (index2) {

                // extraemos todos los id
                var nombres_ids_alteraciones = $(this).find('*').attr("id");

                if (nombres_ids_alteraciones != undefined) {
                    
                    // Extraemos el id de la tabla
                    if (nombres_ids_alteraciones.startsWith("tabladecreto3_")) {
                        var idtabla = $("#"+nombres_ids_alteraciones).val();
                        // console.log(idtabla);
                        guardar_datos_alteraciones.push(idtabla);
                    }

                    // Analizamos si existe un select, input o text para extraer la información.
                    if ($("#"+nombres_ids_alteraciones).val() == "") {
                        var hay_select = '#'+nombres_ids_alteraciones+' select';
                        var hay_input = '#'+nombres_ids_alteraciones+' input';
                        if ($(hay_select).attr("id") != undefined) {
                            var selector = $(hay_select).attr("id");
                            var valor_select = $("#"+selector).val();
                        }else if($(hay_input).attr("id") != undefined){
                            var entrada_texto = $(hay_input).attr("id");
                            var valor_input = $("#"+entrada_texto).val();
                        }else{
                            var valor_texto = $("#"+nombres_ids_alteraciones).text();
                        }

                        if (valor_select != undefined) {
                            // console.log(valor_select);
                            guardar_datos_alteraciones.push(valor_select);
                        }
                        if (valor_input != undefined && valor_input != "on") {
                            // console.log(valor_input);
                            guardar_datos_alteraciones.push(valor_input);
                        }

                        if (valor_texto) {
                            // console.log(valor_texto);
                            guardar_datos_alteraciones.push(valor_texto);
                        }
                      
                    }   
                    
                    if (nombres_ids_alteraciones.startsWith("tablatitulodecreto3_")) {
                        var idtabla = $("#"+nombres_ids_alteraciones).val();
                        // console.log(idtabla);
                        guardar_datos_alteraciones.push(idtabla);
                    }

                    // Analizamos si existe un select, input o text para extraer la información.
                    if ($("#"+nombres_ids_alteraciones).val() == "") {
                        var hay_select = '#'+nombres_ids_alteraciones+' select';
                        var hay_input = '#'+nombres_ids_alteraciones+' input';
                        if ($(hay_select).attr("id") != undefined) {
                            var selector = $(hay_select).attr("id");
                            var valor_select = $("#"+selector).val();
                        }else if($(hay_input).attr("id") != undefined){
                            var entrada_texto = $(hay_input).attr("id");
                            var valor_input = $("#"+entrada_texto).val();
                        }else{
                            var valor_texto = $("#"+nombres_ids_alteraciones).text();
                        }

                        if (valor_select != undefined) {
                            // console.log(valor_select);
                            guardar_datos_alteraciones.push(valor_select);
                        }
                        if (valor_input != undefined && valor_input != "on") {
                            // console.log(valor_input);
                            guardar_datos_alteraciones.push(valor_input);
                        }

                        if (valor_texto) {
                            // console.log(valor_texto);
                            guardar_datos_alteraciones.push(valor_texto);
                        }
                      
                    }

                    if (nombres_ids_alteraciones.startsWith("deficienciadecreto3_")) {
                        var idtabla = $("#"+nombres_ids_alteraciones).val();
                        // console.log(idtabla);
                        guardar_datos_alteraciones.push(idtabla);
                    }

                    // Analizamos si existe un select, input o text para extraer la información.
                    if ($("#"+nombres_ids_alteraciones).val() == "") {
                        var hay_select = '#'+nombres_ids_alteraciones+' select';
                        var hay_input = '#'+nombres_ids_alteraciones+' input';
                        if ($(hay_select).attr("id") != undefined) {
                            var selector = $(hay_select).attr("id");
                            var valor_select = $("#"+selector).val();
                        }else if($(hay_input).attr("id") != undefined){
                            var entrada_texto = $(hay_input).attr("id");
                            var valor_input = $("#"+entrada_texto).val();
                        }else{
                            var valor_texto = $("#"+nombres_ids_alteraciones).text();
                        }

                        if (valor_select != undefined) {
                            // console.log(valor_select);
                            guardar_datos_alteraciones.push(valor_select);
                        }
                        if (valor_input != undefined && valor_input != "on") {
                            // console.log(valor_input);
                            guardar_datos_alteraciones.push(valor_input);
                        }

                        if (valor_texto) {
                            // console.log(valor_texto);
                            guardar_datos_alteraciones.push(valor_texto);
                        }
                      
                    }
                    
                    
                }

                if((index2+1) % 3 === 0){
                    //guardar_datos_alteraciones.splice(1,1);
                    datos_finales_deficiciencias_decreto_tres.push(guardar_datos_alteraciones);
                    console.log(datos_finales_deficiciencias_decreto_tres);
                    guardar_datos_alteraciones = [];
                }
            });
        });
        
        // ENVÍO POR AJAX LA INFORMACIÓN FINAL DE LA TABLA, JUNTO CON EL ID EVENTO, ID ASIGNACION, ID PROCESO
                  
        var envio_datos_deficiencia_decretotres = {
            '_token': token,
            'datos_finales_deficiciencias_decreto_tres' : datos_finales_deficiciencias_decreto_tres,
            'Id_evento': $('#Id_Evento_decreto').val(),
            'Id_Asignacion': $('#Id_Asignacion_decreto').val(),
            'Id_proceso': $('#Id_Proceso_decreto').val(),
            'Estado_Recalificacion': 'Inactivo',
        };  
        
        $.ajax({
            type:'POST',
            url:'/guardarDeficieciasDecretosTres',
            data: envio_datos_deficiencia_decretotres,
            success:function(response){
                // console.log(response);
                if (response.parametro == "inserto_informacion_deficiencias_decreto_tres") {
                    $('#insercion_decreto_3').removeClass('d-none');
                    $('#insercion_decreto_3').addClass('alert-success');
                    $('#insercion_decreto_3').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(() => {
                        $('#insercion_decreto_3').addClass('d-none');
                        $('#insercion_decreto_3').removeClass('alert-success');
                        $('#insercion_decreto_3').empty();
                        location.reload();
                    }, 3000);
                }
            }          
            
        });      
               
    });     
});
// remover filas deficiencias decreto 3
$(document).ready(function(){
    $(document).on('click', "a[id^='btn_remover_deficiencias_decreto3_']", function(){

        let token = $("input[name='_token']").val();
        var datos_fila_quitar_deficiencia_cero = {
            '_token': token,
            'fila' : $(this).data("id_fila_quitar"),
            'Id_evento': $('#Id_Evento_decreto').val()
        };

        //console.log(datos_fila_quitar_deficiencia_cero);
        
        $.ajax({
            type:'POST',
            url:'/eliminarDeficieciasDecretosTres',
            data: datos_fila_quitar_deficiencia_cero,
            success:function(response){
                // console.log(response);
                if (response.parametro == "fila_deficiencia_tres_eliminada") {
                    $('#insercion_decreto_3').empty();
                    $('#insercion_decreto_3').removeClass('d-none');
                    $('#insercion_decreto_3').addClass('alert-success');
                    $('#insercion_decreto_3').append('<strong>'+response.mensaje+'</strong>');
                    
                    setTimeout(() => {
                        $('#insercion_decreto_3').addClass('d-none');
                        $('#insercion_decreto_3').removeClass('alert-success');
                        $('#insercion_decreto_3').empty();
                    }, 3000);
                }
                if (response.total_registros == 0) {
                    $("#conteo_listado_deficiencia_alteraciones").val(response.total_registros);
                }
            }
        });        

    });
});

function redondear(numero) {
    return Math.round(numero * 100) / 100;
}

$(document).ready(function(){
    // Obtener sessionStorage del navegador
    //var posicionActual = $(window).scrollTop(); // Guarda cuando recarga la pagina
    var posicionMemoria = sessionStorage.getItem("scrollToptecnica"); // Guarda session scrollTop
    
    if (posicionMemoria !== null) {
        $(window).scrollTop(posicionMemoria);
        sessionStorage.removeItem("scrollToptecnica");
        //console.log("Se ha restaurado la posición guardada en memoria");
    } else {
        //console.log("No se ha encontrado una posición guardada en memoria");
    }
    //guardar la posición de desplazamiento actual en la memoria
    $(window).on("beforeunload", function() {
        sessionStorage.setItem("scrollToptecnica", $(window).scrollTop());
    });
    //console.log("Posición al refrescar la página: " + posicionActual + "-" + posicionMemoria);    
});

/**
 * Funcion construir los elementos a las columnas de notificacion a las tablas de comunicados
 * @param {string} n_radicado #Radicado asociado al comunicado
 * @param {string} nota Opcional Nota del comunicado
 * @param {object} status_notificacion corresponde a las opciones disponibles que se incluiran en el selector del estado general de notificaciones
 * @returns {Array} correspondiente a las columnas asociadas a notificacion (Destinatarios','Estado_general','Nota')
 */
function getHistorialNotificacion(n_radicado, nota,status_notificacion,data_comunicado) {

    let Destinatario = data_comunicado['Destinatario'];
    let Copias = data_comunicado['Agregar_copia'];
    let Correspondencia = data_comunicado['Correspondencia'];
    if(Copias){
        Copias = Copias.split(',').map(copia => copia.trim().toLowerCase());
    }
    if(Correspondencia){
        Correspondencia = Correspondencia.split(',').map(correspondencia => correspondencia.trim().toLowerCase());
    }
    //Función para agregar el subrayado al destinatario principal y aquellos que hayan sido seleccionados como copia
    function getUnderlineStyle(entity,tipo_descarga = null) {
        let negrita = (Correspondencia && Correspondencia.includes(entity)) ? 'font-weight:700;' : '';
        let underline = (Destinatario.toLowerCase() === entity || (Copias && Copias.includes(entity))) ? 'text-decoration-line: underline;' : '';
        return negrita + underline;
    }
    let info_notificacion = {
        'Destinatarios': 
            `<a href="javascript:void(0);" data-toggle="modal" data-target="#modalCorrespondencia" id="CorrespondenciaNotificacion" data-tipo_correspondencia="Afiliado" \
                data-estado_correspondencia="${data_comunicado["Estado_correspondencia"]}" data-id_comunicado="${data_comunicado["Id_Comunicado"]}" data-n_radicado="${n_radicado}" data-copias="${Copias}" data-destinatario_principal="${Destinatario}"\
                data-id_evento="${data_comunicado['ID_evento']}" data-id_asignacion="${data_comunicado['Id_Asignacion']}" data-id_proceso="${data_comunicado['Id_proceso']}" \
                data-anexos="${data_comunicado['Anexos']}" data-correspondencia="${data_comunicado['Correspondencia']}" data-tipo_descarga="${data_comunicado['Tipo_descarga']}" \
                data-nombre_afiliado="${data_comunicado["Nombre_afiliado"]}" data-numero_identificacion="${data_comunicado["N_identificacion"]}" \ 
                data-ids_destinatario="${data_comunicado['Id_Destinatarios']}" style="${getUnderlineStyle('afiliado',data_comunicado['Tipo_descarga'])}">Afiliado</a>
            <a href="javascript:void(0);" label="Open Modal" data-toggle="modal" data-target="#modalCorrespondencia" id="CorrespondenciaNotificacion" data-tipo_correspondencia="Empleador" \
                data-estado_correspondencia="${data_comunicado["Estado_correspondencia"]}" data-id_comunicado="${data_comunicado["Id_Comunicado"]}" data-n_radicado="${n_radicado}" data-copias="${Copias}" data-destinatario_principal="${Destinatario}"\
                data-id_evento="${data_comunicado['ID_evento']}" data-id_asignacion="${data_comunicado['Id_Asignacion']}" data-id_proceso="${data_comunicado['Id_proceso']}" \
                data-anexos="${data_comunicado['Anexos']}" data-correspondencia="${data_comunicado['Correspondencia']}" data-tipo_descarga="${data_comunicado['Tipo_descarga']}" \
                data-nombre_afiliado="${data_comunicado["Nombre_afiliado"]}" data-numero_identificacion="${data_comunicado["N_identificacion"]}" \ 
                data-ids_destinatario="${data_comunicado['Id_Destinatarios']}" style="${getUnderlineStyle('empleador')}">Empleador</a>
            <a href="javascript:void(0);" data-toggle="modal" data-target="#modalCorrespondencia" id="CorrespondenciaNotificacion" data-tipo_correspondencia="eps" \
                data-estado_correspondencia="${data_comunicado["Estado_correspondencia"]}" data-id_comunicado="${data_comunicado["Id_Comunicado"]}" data-n_radicado="${n_radicado}" data-copias="${Copias}" data-destinatario_principal="${Destinatario}"\
                data-id_evento="${data_comunicado['ID_evento']}" data-id_asignacion="${data_comunicado['Id_Asignacion']}" data-id_proceso="${data_comunicado['Id_proceso']}" \
                data-anexos="${data_comunicado['Anexos']}" data-correspondencia="${data_comunicado['Correspondencia']}" data-tipo_descarga="${data_comunicado['Tipo_descarga']}" \
                data-nombre_afiliado="${data_comunicado["Nombre_afiliado"]}" data-numero_identificacion="${data_comunicado["N_identificacion"]}" \ 
                data-ids_destinatario="${data_comunicado['Id_Destinatarios']}" style="${getUnderlineStyle('eps',data_comunicado['Tipo_descarga'])}">EPS</a>
            <a href="javascript:void(0);" data-toggle="modal" data-target="#modalCorrespondencia" id="CorrespondenciaNotificacion" data-tipo_correspondencia="afp" \
                data-estado_correspondencia="${data_comunicado["Estado_correspondencia"]}" data-id_comunicado="${data_comunicado["Id_Comunicado"]}" data-n_radicado="${n_radicado}" data-copias="${Copias}" data-destinatario_principal="${Destinatario}"\
                data-id_evento="${data_comunicado['ID_evento']}" data-id_asignacion="${data_comunicado['Id_Asignacion']}" data-id_proceso="${data_comunicado['Id_proceso']}" \
                data-anexos="${data_comunicado['Anexos']}" data-correspondencia="${data_comunicado['Correspondencia']}" data-tipo_descarga="${data_comunicado['Tipo_descarga']}" \
                data-nombre_afiliado="${data_comunicado["Nombre_afiliado"]}" data-numero_identificacion="${data_comunicado["N_identificacion"]}" \ 
                data-ids_destinatario="${data_comunicado['Id_Destinatarios']}" style="${getUnderlineStyle('afp',data_comunicado['Tipo_descarga'])}">AFP</a>
            <a href="javascript:void(0);" data-toggle="modal" data-target="#modalCorrespondencia" id="CorrespondenciaNotificacion" data-tipo_correspondencia="arl" \ 
                data-estado_correspondencia="${data_comunicado["Estado_correspondencia"]}" data-id_comunicado="${data_comunicado["Id_Comunicado"]}" data-n_radicado="${n_radicado}" data-copias="${Copias}" data-destinatario_principal="${Destinatario}"\
                data-id_evento="${data_comunicado['ID_evento']}" data-id_asignacion="${data_comunicado['Id_Asignacion']}" data-id_proceso="${data_comunicado['Id_proceso']}" \
                data-anexos="${data_comunicado['Anexos']}" data-correspondencia="${data_comunicado['Correspondencia']}" data-tipo_descarga="${data_comunicado['Tipo_descarga']}" \
                data-nombre_afiliado="${data_comunicado["Nombre_afiliado"]}" data-numero_identificacion="${data_comunicado["N_identificacion"]}" \ 
                data-ids_destinatario="${data_comunicado['Id_Destinatarios']}" style="${getUnderlineStyle('arl',data_comunicado['Tipo_descarga'])}">ARL</a>
            <a href="javascript:void(0);" data-toggle="modal" data-target="#modalCorrespondencia" id="CorrespondenciaNotificacion" data-tipo_correspondencia="afp_conocimiento" \
                data-estado_correspondencia="${data_comunicado["Estado_correspondencia"]}" data-id_comunicado="${data_comunicado["Id_Comunicado"]}" data-n_radicado="${n_radicado}" data-copias="${Copias}" data-destinatario_principal="${Destinatario}"\
                data-id_evento="${data_comunicado['ID_evento']}" data-id_asignacion="${data_comunicado['Id_Asignacion']}" data-id_proceso="${data_comunicado['Id_proceso']}" \
                data-anexos="${data_comunicado['Anexos']}" data-correspondencia="${data_comunicado['Correspondencia']}" data-tipo_descarga="${data_comunicado['Tipo_descarga']}" \
                data-nombre_afiliado="${data_comunicado["Nombre_afiliado"]}" data-numero_identificacion="${data_comunicado["N_identificacion"]}" \ 
                data-ids_destinatario="${data_comunicado['Id_Destinatarios']}" style="${getUnderlineStyle('afp_conocimiento',data_comunicado['Tipo_descarga'])}">AFP Conocimiento</a>`,

        'Nota_Comunicados': `<textarea class="form-control nota-col" name="nota_comunicado_${n_radicado}" id="nota_comunicado_${n_radicado}" cols="70" rows="5" style="resize:none; width:200px;">${nota == null ? "" : nota}</textarea>`,
    };
    //Opciones a incluir en el selector del estado general de la notificacion
    let opciones_Notificacion = '';
    $.each(status_notificacion,function(item,index){
        opciones_Notificacion += index.opciones;
    });

    info_notificacion['Estado_General'] =`<select class="custom-select" id="status_notificacion_${n_radicado}" style="width:100%;">${opciones_Notificacion}</select>`;

    return info_notificacion;
}

function cleanModalCorrespondencia(){
    $("#btn_guardar_actualizar_correspondencia").val('Guardar');

    correspondencia_array = [];
    $("#modalCorrespondencia #check_principal").prop('checked', false).prop('disabled', true).prop('required', true);
    $("#modalCorrespondencia #check_copia").prop('checked', false).prop('disabled', false).prop('required', true);
    $('#state_notificacion').val('').trigger('change');
    $("#modalCorrespondencia #tipo_correspondencia").val('');
    $("#modalCorrespondencia #n_orden").val('');
    $("#modalCorrespondencia #nombre_destinatario").val('');
    $("#modalCorrespondencia #direccion").val('');
    $("#modalCorrespondencia #departamento").val('');
    $("#modalCorrespondencia #ciudad").val('');
    $("#modalCorrespondencia #telefono").val('');
    $("#modalCorrespondencia #email").val('');
    $("#modalCorrespondencia #m_notificacion").val('');
    $("#modalCorrespondencia #folios").val('');
    $("#modalCorrespondencia #n_guia").val('');
    $("#modalCorrespondencia #f_envio").val('');
    $("#modalCorrespondencia #f_notificacion").val('');
    $("#modalCorrespondencia #state_notificacion").val('');
    $("#modalCorrespondencia #id_correspondencia").val('');
    $("#modalCorrespondencia #id_asignacion").val('');
    $("#modalCorrespondencia #id_proceso").val('');
    $("#modalCorrespondencia #id_comunicado").val('');
    $("#modalCorrespondencia #id_destinatario").val('');
}

function showLoading() {
    $('#loading').addClass('loading');
    $('#loading-content').addClass('loading-content');
}
function hideLoading() {
    $('#loading').removeClass('loading');
    $('#loading-content').removeClass('loading-content');
    
}

function config_Select2(radicado,controlComunicados,opciones_Notificacion,StatusSeleccionado){
    return new Promise((resolve,reject) => {
        $(`#status_notificacion_${radicado}`).select2('destroy').select2({
            placeholder: "Seleccione una opción",
            allowClear: false,
            disabled: controlComunicados.deshabilitar_selector,
            data: opciones_Notificacion,
            templateResult: function(data) {
                if (data.color != undefined) {
                    return $(`<span style="color: ${data.color}">${data.texto}</span>`);
                }
            },
            templateSelection: function(data) {
                if (data.color != undefined) {
                    return $(`<span style="color: ${data.color}">${data.texto}</span>`);
                }
            }
        }).val(StatusSeleccionado).trigger('change');
        resolve();
    });
}