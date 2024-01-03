$(document).ready(function(){

    // Obtener sessionStorage del navegador
    //var posicionActual = $(window).scrollTop(); // Guarda cuando recarga la pagina
    var posicionMemoria = sessionStorage.getItem("scrollTopControJuntas"); // Guarda session scrollTop

    if (posicionMemoria != null) {
        $(window).scrollTop(posicionMemoria);
        sessionStorage.removeItem("scrollTopControJuntas");
        //console.log("Se ha restaurado la posición guardada en memoria");
    } else {
        //console.log("No se ha encontrado una posición guardada en memoria");
    }
    //guardar la posición de desplazamiento actual en la memoria
    $(window).on("beforeunload", function() {
        sessionStorage.setItem("scrollTopControJuntas", $(window).scrollTop());
    });

    // Incialización selec2 Origen Controvertido
    $(".origen_controversia").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });
    // Incialización selec2 Manual de Calificación
    $(".manual_de_califi").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

  
    // Incialización selec2 Origen Jrci Emitido
    $(".origen_jrci_emitido").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    // Incialización Manual De Califi Jrci Emitido
    $(".manual_de_califi_jrci_emitido").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    //Causal de decisión
    $(".causal_decision").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    //Parte controvierte en JRCI
    $(".parte_contro_ante_jrci").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });
    
    //Parte reposicion en JRCI
    $(".origen_reposicion_jrci").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });
    //Manual reposicion JRCI
    $(".manual_reposicion_jrci").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    // Origen emitido JNCI
    $(".origen_jnci_emitido").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    //Manual emitido JNCI
    $(".manual_de_califi_jnci_emitido").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    // Select Correspondecias    
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

    var token = $('input[name=_token]').val();

    //Listado de origen
    let datos_lista_tipo_origen_controver = {
        '_token': token,
        'parametro':"lista_tipo_origen_controver"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresJuntasControversia',
        data: datos_lista_tipo_origen_controver,
        success:function(data) {
            let IdOrigen = $('select[name=origen_controversia]').val();
            let tipoorigen = Object.keys(data);
            for (let i = 0; i < tipoorigen.length; i++) {
                if (data[tipoorigen[i]]['Id_Parametro'] != IdOrigen) {  
                    $('#origen_controversia').append('<option value="'+data[tipoorigen[i]]["Id_Parametro"]+'">'+data[tipoorigen[i]]["Nombre_parametro"]+'</option>');
                }
            }
        }
    });
    // Listado Decretos Califi 
    let datos_lista_califi_decreto = {
        '_token': token,
        'parametro':"lista_tipo_califi_decretos"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresJuntasControversia',
        data: datos_lista_califi_decreto,
        success:function(data) {
            let IdCalifiDecreto = $('select[name=manual_de_califi]').val();
            let tipodecreto = Object.keys(data);
            for (let i = 0; i < tipodecreto.length; i++) {
                if (data[tipodecreto[i]]['Id_Decreto'] != IdCalifiDecreto) {  
                    $('#manual_de_califi').append('<option value="'+data[tipodecreto[i]]["Id_Decreto"]+'">'+data[tipodecreto[i]]["Nombre_decreto"]+'</option>');
                }
            }
        }
    });

    //Listado de origen JRCI emitido
    let datos_lista_tipo_origen_emitido_jrci = {
        '_token': token,
        'parametro':"lista_tipo_origen_emitdo_jrci"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresJuntasControversia',
        data: datos_lista_tipo_origen_emitido_jrci,
        success:function(data) {
            let IdOrigen2 = $('select[name=origen_jrci_emitido]').val();
            let tipoorigen2 = Object.keys(data);
            for (let i = 0; i < tipoorigen2.length; i++) {
                if (data[tipoorigen2[i]]['Id_Parametro'] != IdOrigen2) {  
                    $('#origen_jrci_emitido').append('<option value="'+data[tipoorigen2[i]]["Id_Parametro"]+'">'+data[tipoorigen2[i]]["Nombre_parametro"]+'</option>');
                }
            }
        }
    });

     // Listado Decretos Califi 
     let datos_lista_califi_decreto_jrci_emitido = {
        '_token': token,
        'parametro':"lista_tipo_califi_decretos_jrci_emitido"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresJuntasControversia',
        data: datos_lista_califi_decreto_jrci_emitido,
        success:function(data) {
            let IdCalifiDecretoJrci = $('select[name=manual_de_califi_jrci_emitido]').val();
            let tipodecretoJrci = Object.keys(data);
            for (let i = 0; i < tipodecretoJrci.length; i++) {
                if (data[tipodecretoJrci[i]]['Id_Decreto'] != IdCalifiDecretoJrci) {  
                    $('#manual_de_califi_jrci_emitido').append('<option value="'+data[tipodecretoJrci[i]]["Id_Decreto"]+'">'+data[tipodecretoJrci[i]]["Nombre_decreto"]+'</option>');
                }
            }
        }
    });

    // Listado Parte que presenta controversia ante JRCI
    let datos_lista_controversia_jrci = {
        '_token': token,
        'parametro':"lista_controvierte_calificacion"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresJuntasControversia',
        data: datos_lista_controversia_jrci,
        success:function(data) {
            let IdCalifi_contro = $('select[name=parte_contro_ante_jrci]').val();
            let partecontro = Object.keys(data);
            for (let i = 0; i < partecontro.length; i++) {
                if (data[partecontro[i]]['Id_Parametro'] != IdCalifi_contro) {  
                    $('#parte_contro_ante_jrci').append('<option value="'+data[partecontro[i]]["Id_Parametro"]+'">'+data[partecontro[i]]["Nombre_parametro"]+'</option>');
                }
            }
        }
    });

    //Listado de origen  reposicion JRCI
    let datos_lista_tipo_reposicion_jrci = {
        '_token': token,
        'parametro':"lista_tipo_reposicion_jrci"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresJuntasControversia',
        data: datos_lista_tipo_reposicion_jrci,
        success:function(data) {
            let IdOrigen3 = $('select[name=origen_reposicion_jrci]').val();
            let tipoorigen3 = Object.keys(data);
            for (let i = 0; i < tipoorigen3.length; i++) {
                if (data[tipoorigen3[i]]['Id_Parametro'] != IdOrigen3) {  
                    $('#origen_reposicion_jrci').append('<option value="'+data[tipoorigen3[i]]["Id_Parametro"]+'">'+data[tipoorigen3[i]]["Nombre_parametro"]+'</option>');
                }
            }
        }
    });

     // Listado Decretos Califi reposicion
     let datos_lista_califi_decreto_jrci_reposicion = {
        '_token': token,
        'parametro':"lista_tipo_califi_decretos_jrci_reposicion"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresJuntasControversia',
        data: datos_lista_califi_decreto_jrci_reposicion,
        success:function(data) {
            let IdCalifiDecretoReJrci = $('select[name=manual_reposicion_jrci]').val();
            let tipodecretoRepoJrci = Object.keys(data);
            for (let i = 0; i < tipodecretoRepoJrci.length; i++) {
                if (data[tipodecretoRepoJrci[i]]['Id_Decreto'] != IdCalifiDecretoReJrci) {  
                    $('#manual_reposicion_jrci').append('<option value="'+data[tipodecretoRepoJrci[i]]["Id_Decreto"]+'">'+data[tipodecretoRepoJrci[i]]["Nombre_decreto"]+'</option>');
                }
            }
        }
    });

     //Listado de origen JNCI emitido
     let datos_lista_tipo_origen_emitido_jnci = {
        '_token': token,
        'parametro':"lista_tipo_origen_emitdo_jnci"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresJuntasControversia',
        data: datos_lista_tipo_origen_emitido_jnci,
        success:function(data) {
            let IdOrigen3 = $('select[name=origen_jnci_emitido]').val();
            let tipoorigen3 = Object.keys(data);
            for (let i = 0; i < tipoorigen3.length; i++) {
                if (data[tipoorigen3[i]]['Id_Parametro'] != IdOrigen3) {  
                    $('#origen_jnci_emitido').append('<option value="'+data[tipoorigen3[i]]["Id_Parametro"]+'">'+data[tipoorigen3[i]]["Nombre_parametro"]+'</option>');
                }
            }
        }
    });

    // Listado Decretos Califi emitido JNCI
    let datos_lista_califi_decreto_jnci_emitido = {
        '_token': token,
        'parametro':"lista_tipo_califi_decretos_jnci_reposicion"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresJuntasControversia',
        data: datos_lista_califi_decreto_jnci_emitido,
        success:function(data) {
            let IdCalifiDecretoReJnci = $('select[name=manual_de_califi_jnci_emitido]').val();
            let tipodecretoRepoJnci = Object.keys(data);
            for (let i = 0; i < tipodecretoRepoJnci.length; i++) {
                if (data[tipodecretoRepoJnci[i]]['Id_Decreto'] != IdCalifiDecretoReJnci) {  
                    $('#manual_de_califi_jnci_emitido').append('<option value="'+data[tipodecretoRepoJnci[i]]["Id_Decreto"]+'">'+data[tipodecretoRepoJnci[i]]["Nombre_decreto"]+'</option>');
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
        url:'/selectoresJuntasControversia',
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
            url:'/selectoresJuntasControversia',
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
    var idProcesoLider = $("#Id_proceso").val();
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
        }
    });

    /* VALIDACIÓN MOSTRAR CAMPOS SI ES PCL */ 
    var opt_tipo_de_creto;
    var total_deficiencia =  $("[id='total_deficiencia']").val() || 0; 
    var total_rol_ocupacional = $("[id='total_rol_ocupacional']").val() || 0; 
    var total_discapacidad = $("[id='total_discapacidad']").val() || 0; 
    var total_minusvalia = $("[id='total_minusvalia']").val() || 0; 
    var opt_sumaTotal_pcl = $("[id='porcentaje_pcl']").val() || 0; 
    var rango_pcl= $("[id='rango_pcl']").val() || 0;
    $("#manual_de_califi").change(function(){
        opt_tipo_de_creto = parseInt($(this).val());
        $("#manual_de_califi").val(opt_tipo_de_creto);
        $("#total_deficiencia").val("");
        $("#total_discapacidad").val("");
        $("#total_rol_ocupacional").val("");
        $("#total_minusvalia").val("");
        $("#porcentaje_pcl").val("0");
        total_deficiencia=''; 
        total_rol_ocupacional='';
        total_discapacidad='';
        total_minusvalia='';
        opt_sumaTotal_pcl='';
        rango_pcl='0';
        iniciarIntervalo_tDecreto();
    });
    // Función para validar items a mostrar
    const tiempoDeslizamiento = 'slow';
    function iniciarIntervalo_tDecreto() {
         // Selección de los elementos que se deslizarán
         const elementosDeslizar = [
            '.rol_ocupacional'
        ];

        const elementosDeslizar2 = [
            '.total_discapaci',
            '.total_minusva'
        ];
        intervalo = setInterval(() => {
            switch (opt_tipo_de_creto) {
                case 1:
                    elementosDeslizar.forEach(elemento => {
                        $(elemento).slideDown(tiempoDeslizamiento);
                    }); 
                    elementosDeslizar2.forEach(elemento => {
                        $(elemento).slideUp(tiempoDeslizamiento);
                    });
                    $('#total_rol_ocupacional').prop('required', true);
                    $('#total_discapacidad').prop('required', false);
                    $('#total_minusvalia').prop('required', false);
                break;
                case 3:
                    elementosDeslizar2.forEach(elemento => {
                        $(elemento).slideDown(tiempoDeslizamiento);
                    });
                    elementosDeslizar.forEach(elemento => {
                        $(elemento).slideUp(tiempoDeslizamiento);
                    });
                    $('#total_discapacidad').prop('required', true);
                    $('#total_minusvalia').prop('required', true);
                    $('#total_rol_ocupacional').prop('required', false);

                break;
                default:
                    // Deslizar hacia arriba (ocultar) los elementos
                    elementosDeslizar.forEach(elemento => {
                        $(elemento).slideUp(tiempoDeslizamiento);
                    });
                    elementosDeslizar2.forEach(elemento => {
                        $(elemento).slideUp(tiempoDeslizamiento);
                    });
                    $('#total_rol_ocupacional').prop('required', false);
                    $('#total_discapacidad').prop('required', false);
                    $('#total_minusvalia').prop('required', false);
                break;
            }

        }, 500);

    }
    /* SUMAR PORCENTAJE %PCL */ 
    $("#total_deficiencia").on("input", function(){
        total_deficiencia =$(this).val();
        iniciarIntervalo_pPCL();
    }); 
    $("#total_rol_ocupacional").on("input", function(){
        total_rol_ocupacional =$(this).val();
        iniciarIntervalo_pPCL();
    });
    $("#total_discapacidad").on("input", function(){
        total_discapacidad =$(this).val();
        iniciarIntervalo_pPCL();
    });
    $("#total_minusvalia").on("input", function(){
        total_minusvalia =$(this).val();
        iniciarIntervalo_pPCL();
    });
    function iniciarIntervalo_pPCL() {
        intervalo = setInterval(() => {
            opt_sumaTotal_pcl = Number(total_deficiencia) + Number(total_rol_ocupacional)+ Number(total_discapacidad)+ Number(total_minusvalia);

            if (!isNaN(opt_sumaTotal_pcl)){
                $('#porcentaje_pcl').val(redondearNumero(opt_sumaTotal_pcl) );
            }
            if(opt_sumaTotal_pcl=='isNaN'){
                rango_pcl = '0';
            }else if(opt_sumaTotal_pcl < 15){
                rango_pcl = 'Entre 1 y 14,99%';
            } else if (opt_sumaTotal_pcl >= 15 && opt_sumaTotal_pcl < 30){
                rango_pcl = 'Entre 15 y 29,99%';
            } else if (opt_sumaTotal_pcl >= 30 && opt_sumaTotal_pcl < 50){
                rango_pcl = 'Entre 30 y 49,99%';
            } else if (opt_sumaTotal_pcl >= 50){
                rango_pcl = 'Mayor o igual 50%';
            }else{
                rango_pcl = '0';
            }
            $('#rango_pcl').val(rango_pcl); //Coloca resultado Rango PCL

        }, 500);
    }
    /* VALIDACIÓN MOSTRAR CAMPOS SI ES PCL EN EMITIDO JRCI*/ 
    var opt_tipo_de_creto_jrci_emitido;
    var total_deficiencia_jrci_emitido =  $("[id='total_deficiencia_jrci_emitido']").val() || 0; 
    var total_rol_ocupacional_jrci_emitido = $("[id='total_rol_ocupacional_jrci_emitido']").val() || 0; 
    var total_discapacidad_jrci_emitido = $("[id='total_discapacidad_jrci_emitido']").val() || 0; 
    var total_minusvalia_jrci_emitido = $("[id='total_minusvalia_jrci_emitido']").val() || 0; 
    var opt_sumaTotal_pcl_jrci_emitido = $("[id='porcentaje_pcl_jrci_emitido']").val() || 0; 
    var rango_pcl_jrci_emitido= $("[id='rango_pcl_jrci_emitido']").val() || 0;
    $("#manual_de_califi_jrci_emitido").change(function(){
        opt_tipo_de_creto_jrci_emitido = parseInt($(this).val());
        $("#manual_de_califi_jrci_emitido").val(opt_tipo_de_creto_jrci_emitido);
        $("#total_deficiencia_jrci_emitido").val("");
        $("#total_discapacidad_jrci_emitido").val("");
        $("#total_rol_ocupacional_jrci_emitido").val("");
        $("#total_minusvalia_jrci_emitido").val("");
        $("#porcentaje_pcl_jrci_emitido").val("0");
        total_deficiencia_jrci_emitido=''; 
        total_rol_ocupacional_jrci_emitido='';
        total_discapacidad_jrci_emitido='';
        total_minusvalia_jrci_emitido='';
        opt_sumaTotal_pcl_jrci_emitido='';
        rango_pcl_jrci_emitido='0';
        iniciarIntervalo_tDecreto_jrci_emitido();
    });
    // Función para validar items a mostrar
    const tiempoDeslizamientoJrci = 'slow';
    function iniciarIntervalo_tDecreto_jrci_emitido() {
         // Selección de los elementos que se deslizarán
         const elementosDeslizarJrci = [
            '.rol_ocupacional_jrci_emitido'
        ];

        const elementosDeslizarJrci2 = [
            '.total_discapaci_jrci_emitido',
            '.total_minusva_jrci_emitido'
        ];
        intervalo = setInterval(() => {
            switch (opt_tipo_de_creto_jrci_emitido) {
                case 1:
                    elementosDeslizarJrci.forEach(elemento => {
                        $(elemento).slideDown(tiempoDeslizamientoJrci);
                    }); 
                    elementosDeslizarJrci2.forEach(elemento => {
                        $(elemento).slideUp(tiempoDeslizamientoJrci);
                    });
                    $('#total_rol_ocupacional').prop('required', true);
                    $('#total_discapacidad').prop('required', false);
                    $('#total_minusvalia').prop('required', false);
                break;
                case 3:
                    elementosDeslizarJrci2.forEach(elemento => {
                        $(elemento).slideDown(tiempoDeslizamientoJrci);
                    });
                    elementosDeslizarJrci.forEach(elemento => {
                        $(elemento).slideUp(tiempoDeslizamientoJrci);
                    });
                    $('#total_discapacidad_jrci_emitido').prop('required', true);
                    $('#total_minusvalia_jrci_emitido').prop('required', true);
                    $('#total_rol_ocupacional_jrci_emitido').prop('required', false);

                break;
                default:
                    // Deslizar hacia arriba (ocultar) los elementos
                    elementosDeslizarJrci.forEach(elemento => {
                        $(elemento).slideUp(tiempoDeslizamientoJrci);
                    });
                    elementosDeslizarJrci2.forEach(elemento => {
                        $(elemento).slideUp(tiempoDeslizamientoJrci);
                    });
                    $('#total_rol_ocupacional_jrci_emitido').prop('required', false);
                    $('#total_discapacidad_jrci_emitido').prop('required', false);
                    $('#total_minusvalia_jrci_emitido').prop('required', false);
                break;
            }

        }, 500);

    }
    /* SUMAR PORCENTAJE %PCL */ 
    $("#total_deficiencia_jrci_emitido").on("input", function(){
        total_deficiencia_jrci_emitido =$(this).val();
        iniciarIntervalo_pPCL_jrci_emitido();
    }); 
    $("#total_rol_ocupacional_jrci_emitido").on("input", function(){
        total_rol_ocupacional_jrci_emitido =$(this).val();
        iniciarIntervalo_pPCL_jrci_emitido();
    });
    $("#total_discapacidad_jrci_emitido").on("input", function(){
        total_discapacidad_jrci_emitido =$(this).val();
        iniciarIntervalo_pPCL_jrci_emitido();
    });
    $("#total_minusvalia_jrci_emitido").on("input", function(){
        total_minusvalia_jrci_emitido =$(this).val();
        iniciarIntervalo_pPCL_jrci_emitido();
    });
    function iniciarIntervalo_pPCL_jrci_emitido() {
        intervalo = setInterval(() => {
            opt_sumaTotal_pcl_jrci_emitido = Number(total_deficiencia_jrci_emitido) + Number(total_rol_ocupacional_jrci_emitido) + Number(total_discapacidad_jrci_emitido)+ Number(total_minusvalia_jrci_emitido);

            if (!isNaN(opt_sumaTotal_pcl_jrci_emitido)){
                $('#porcentaje_pcl_jrci_emitido').val(redondearNumero(opt_sumaTotal_pcl_jrci_emitido) );
            }
            if(opt_sumaTotal_pcl_jrci_emitido=='isNaN'){
                rango_pcl_jrci_emitido = '0';
            }else if(opt_sumaTotal_pcl_jrci_emitido < 15){
                rango_pcl_jrci_emitido = 'Entre 1 y 14,99%';
            } else if (opt_sumaTotal_pcl_jrci_emitido >= 15 && opt_sumaTotal_pcl_jrci_emitido < 30){
                rango_pcl_jrci_emitido = 'Entre 15 y 29,99%';
            } else if (opt_sumaTotal_pcl_jrci_emitido >= 30 && opt_sumaTotal_pcl_jrci_emitido < 50){
                rango_pcl_jrci_emitido = 'Entre 30 y 49,99%';
            } else if (opt_sumaTotal_pcl_jrci_emitido >= 50){
                rango_pcl_jrci_emitido = 'Mayor o igual 50%';
            }else{
                rango_pcl_jrci_emitido = '0';
            }
            $('#rango_pcl_jrci_emitido').val(rango_pcl_jrci_emitido); //Coloca resultado Rango PCL

        }, 500);
    }
    /* VALIDACIÓN MOSTRAR CAMPOS SI ES PCL EN DATOS REPOSICION JRCI*/ 
    var opt_tipo_de_creto_jrci_reposicion;
    var total_deficiencia_reposicion_jrci =  $("[id='total_deficiencia_reposicion_jrci']").val() || 0; 
    var total_rol_reposicion_jrci = $("[id='total_rol_reposicion_jrci']").val() || 0; 
    var total_discapacidad_reposicion_jrci = $("[id='total_discapacidad_reposicion_jrci']").val() || 0; 
    var total_minusvalia_reposicion_jrci = $("[id='total_minusvalia_reposicion_jrci']").val() || 0; 
    var opt_sumaTotal_pcl_jrci_reposicion = $("[id='porcentaje_pcl_reposicion_jrci']").val() || 0; 
    var rango_pcl_reposicion_jrci = $("[id='rango_pcl_reposicion_jrci']").val() || 0;

    $("#manual_reposicion_jrci").change(function(){
        opt_tipo_de_creto_jrci_reposicion = parseInt($(this).val());
        $("#manual_reposicion_jrci").val(opt_tipo_de_creto_jrci_reposicion);
        $("#total_deficiencia_reposicion_jrci").val("");
        $("#total_discapacidad_reposicion_jrci").val("");
        $("#total_rol_reposicion_jrci").val("");
        $("#total_minusvalia_reposicion_jrci").val("");
        $("#porcentaje_pcl_reposicion_jrci").val("0");
        total_deficiencia_reposicion_jrci=''; 
        total_rol_ocupacional_jrci_emitido='';
        total_discapacidad_reposicion_jrci='';
        total_minusvalia_reposicion_jrci='';
        opt_sumaTotal_pcl_jrci_reposicion='';
        iniciarIntervalo_tDecreto_jrci_reposicion();
    });
    // Función para validar items a mostrar
    const tiempoDeslizamientoJrciRepo = 'slow';
    function iniciarIntervalo_tDecreto_jrci_reposicion() {
         // Selección de los elementos que se deslizarán
         const elementosDeslizarJrciRepo = [
            '.rol_ocupacional_jrci_reposicion'
        ];

        const elementosDeslizarJrciRepo2 = [
            '.total_dicapacida_jrci_reposicion',
            '.total_minusva_jrci_reposicion'
        ];
        intervalo = setInterval(() => {
            switch (opt_tipo_de_creto_jrci_reposicion) {
                case 1:
                    elementosDeslizarJrciRepo.forEach(elemento => {
                        $(elemento).slideDown(tiempoDeslizamientoJrciRepo);
                    }); 
                    elementosDeslizarJrciRepo2.forEach(elemento => {
                        $(elemento).slideUp(tiempoDeslizamientoJrciRepo);
                    });
                    $('#total_rol_reposicion_jrci').prop('required', true);
                    $('#total_discapacidad_reposicion_jrci').prop('required', false);
                    $('#total_minusvalia_reposicion_jrci').prop('required', false);
                break;
                case 3:
                    elementosDeslizarJrciRepo2.forEach(elemento => {
                        $(elemento).slideDown(tiempoDeslizamientoJrciRepo);
                    });
                    elementosDeslizarJrciRepo.forEach(elemento => {
                        $(elemento).slideUp(tiempoDeslizamientoJrciRepo);
                    });
                    $('#total_discapacidad_reposicion_jrci').prop('required', true);
                    $('#total_minusvalia_reposicion_jrci').prop('required', true);
                    $('#total_rol_reposicion_jrci').prop('required', false);

                break;
                default:
                    // Deslizar hacia arriba (ocultar) los elementos
                    elementosDeslizarJrciRepo.forEach(elemento => {
                        $(elemento).slideUp(tiempoDeslizamientoJrciRepo);
                    });
                    elementosDeslizarJrciRepo2.forEach(elemento => {
                        $(elemento).slideUp(tiempoDeslizamientoJrciRepo);
                    });
                    $('#total_rol_reposicion_jrci').prop('required', false);
                    $('#total_discapacidad_reposicion_jrci').prop('required', false);
                    $('#total_minusvalia_reposicion_jrci').prop('required', false);
                break;
            }

        }, 500);

    }
    /* SUMAR PORCENTAJE %PCL */ 
    $("#total_deficiencia_reposicion_jrci").on("input", function(){
        total_deficiencia_reposicion_jrci =$(this).val();
        iniciarIntervalo_pPCL_jrci_reposicion();
    }); 
    $("#total_rol_reposicion_jrci").on("input", function(){
        total_rol_reposicion_jrci =$(this).val();
        iniciarIntervalo_pPCL_jrci_reposicion();
    });
    $("#total_discapacidad_reposicion_jrci").on("input", function(){
        total_discapacidad_reposicion_jrci =$(this).val();
        iniciarIntervalo_pPCL_jrci_reposicion();
    });
    $("#total_minusvalia_reposicion_jrci").on("input", function(){
        total_minusvalia_reposicion_jrci =$(this).val();
        iniciarIntervalo_pPCL_jrci_reposicion();
    });
    function iniciarIntervalo_pPCL_jrci_reposicion() {
        intervalo = setInterval(() => {
            opt_sumaTotal_pcl_jrci_reposicion = Number(total_deficiencia_reposicion_jrci) + Number(total_rol_reposicion_jrci) + Number(total_discapacidad_reposicion_jrci)+ Number(total_minusvalia_reposicion_jrci);

            if (!isNaN(opt_sumaTotal_pcl_jrci_reposicion)){
                $('#porcentaje_pcl_reposicion_jrci').val(redondearNumero(opt_sumaTotal_pcl_jrci_reposicion) );
            }
            if(opt_sumaTotal_pcl_jrci_reposicion=='isNaN'){
                rango_pcl_reposicion_jrci = '0';
            }else if(opt_sumaTotal_pcl_jrci_reposicion < 15){
                rango_pcl_reposicion_jrci = 'Entre 1 y 14,99%';
            } else if (opt_sumaTotal_pcl_jrci_reposicion >= 15 && opt_sumaTotal_pcl_jrci_reposicion < 30){
                rango_pcl_reposicion_jrci = 'Entre 15 y 29,99%';
            } else if (opt_sumaTotal_pcl_jrci_reposicion >= 30 && opt_sumaTotal_pcl_jrci_reposicion < 50){
                rango_pcl_reposicion_jrci = 'Entre 30 y 49,99%';
            } else if (opt_sumaTotal_pcl_jrci_reposicion >= 50){
                rango_pcl_reposicion_jrci = 'Mayor o igual 50%';
            }else{
                rango_pcl_reposicion_jrci = '0';
            }
            $('#rango_pcl_reposicion_jrci').val(rango_pcl_reposicion_jrci); //Coloca resultado Rango PCL

        }, 500);
    }

    /* VALIDACIÓN MOSTRAR CAMPOS SI ES PCL EN EMITIDO JNCI*/ 
    var opt_tipo_de_creto_jnci_emitido;
    var total_deficiencia_jnci_emitido =  $("[id='total_deficiencia_jnci_emitido']").val() || 0; 
    var total_rol_ocupacional_jnci_emitido = $("[id='total_rol_ocupacional_jnci_emitido']").val() || 0; 
    var total_discapacidad_jnci_emitido = $("[id='total_discapacidad_jnci_emitido']").val() || 0; 
    var total_minusvalia_jnci_emitido = $("[id='total_minusvalia_jnci_emitido']").val() || 0; 
    var opt_sumaTotal_pcl_jnci_emitido = $("[id='porcentaje_pcl_jnci_emitido']").val() || 0; 
    var rango_pcl_jnci_emitido= $("[id='rango_pcl_jnci_emitido']").val() || 0;
    $("#manual_de_califi_jnci_emitido").change(function(){
        opt_tipo_de_creto_jnci_emitido = parseInt($(this).val());
        $("#manual_de_califi_jnci_emitido").val(opt_tipo_de_creto_jnci_emitido);
        $("#total_deficiencia_jnci_emitido").val("");
        $("#total_discapacidad_jnci_emitido").val("");
        $("#total_rol_ocupacional_jnci_emitido").val("");
        $("#total_minusvalia_jnci_emitido").val("");
        $("#porcentaje_pcl_jnci_emitido").val("0");
        total_deficiencia_jnci_emitido=''; 
        total_rol_ocupacional_jnci_emitido='';
        total_discapacidad_jnci_emitido='';
        total_minusvalia_jnci_emitido='';
        opt_sumaTotal_pcl_jnci_emitido='';
        rango_pcl_jnci_emitido='0';
        iniciarIntervalo_tDecreto_jnci_emitido();
    });
    // Función para validar items a mostrar
    const tiempoDeslizamientoJnci = 'slow';
    function iniciarIntervalo_tDecreto_jnci_emitido() {
         // Selección de los elementos que se deslizarán
         const elementosDeslizarJnci = [
            '.rol_ocupacional_jnci_emitido'
        ];

        const elementosDeslizarJnci2 = [
            '.total_discapaci_jnci_emitido',
            '.total_minusva_jnci_emitido'
        ];
        intervalo = setInterval(() => {
            switch (opt_tipo_de_creto_jnci_emitido) {
                case 1:
                    elementosDeslizarJnci.forEach(elemento => {
                        $(elemento).slideDown(tiempoDeslizamientoJnci);
                    }); 
                    elementosDeslizarJnci2.forEach(elemento => {
                        $(elemento).slideUp(tiempoDeslizamientoJnci);
                    });
                    $('#total_rol_ocupacional_jnci_emitido').prop('required', true);
                    $('#total_discapacidad_jnci_emitido').prop('required', false);
                    $('#total_minusvalia_jnci_emitido').prop('required', false);
                break;
                case 3:
                    elementosDeslizarJnci2.forEach(elemento => {
                        $(elemento).slideDown(tiempoDeslizamientoJnci);
                    });
                    elementosDeslizarJnci.forEach(elemento => {
                        $(elemento).slideUp(tiempoDeslizamientoJnci);
                    });
                    $('#total_discapacidad_jnci_emitido').prop('required', true);
                    $('#total_minusvalia_jnci_emitido').prop('required', true);
                    $('#total_rol_ocupacional_jnci_emitido').prop('required', false);

                break;
                default:
                    // Deslizar hacia arriba (ocultar) los elementos
                    elementosDeslizarJnci.forEach(elemento => {
                        $(elemento).slideUp(tiempoDeslizamientoJnci);
                    });
                    elementosDeslizarJnci.forEach(elemento => {
                        $(elemento).slideUp(tiempoDeslizamientoJnci);
                    });
                    $('#total_rol_ocupacional_jnci_emitido').prop('required', false);
                    $('#total_discapacidad_jnci_emitido').prop('required', false);
                    $('#total_minusvalia_jnci_emitido').prop('required', false);
                break;
            }

        }, 500);

    }
    /* SUMAR PORCENTAJE %PCL */ 
    $("#total_deficiencia_jnci_emitido").on("input", function(){
        total_deficiencia_jnci_emitido =$(this).val();
        iniciarIntervalo_pPCL_jnci_emitido();
    }); 
    $("#total_rol_ocupacional_jnci_emitido").on("input", function(){
        total_rol_ocupacional_jnci_emitido =$(this).val();
        iniciarIntervalo_pPCL_jnci_emitido();
    });
    $("#total_discapacidad_jnci_emitido").on("input", function(){
        total_discapacidad_jnci_emitido =$(this).val();
        iniciarIntervalo_pPCL_jnci_emitido();
    });
    $("#total_minusvalia_jnci_emitido").on("input", function(){
        total_minusvalia_jnci_emitido =$(this).val();
        iniciarIntervalo_pPCL_jnci_emitido();
    });
    function iniciarIntervalo_pPCL_jnci_emitido() {
        intervalo = setInterval(() => {
            opt_sumaTotal_pcl_jnci_emitido = Number(total_deficiencia_jnci_emitido) + Number(total_rol_ocupacional_jnci_emitido) + Number(total_discapacidad_jnci_emitido)+ Number(total_minusvalia_jnci_emitido);
            if (!isNaN(opt_sumaTotal_pcl_jnci_emitido)){
                $('#porcentaje_pcl_jnci_emitido').val(redondearNumero(opt_sumaTotal_pcl_jnci_emitido) );
            }
            if(opt_sumaTotal_pcl_jnci_emitido=='isNaN'){
                rango_pcl_jnci_emitido = '0';
            }else if(opt_sumaTotal_pcl_jnci_emitido < 15){
                rango_pcl_jnci_emitido = 'Entre 1 y 14,99%';
            } else if (opt_sumaTotal_pcl_jnci_emitido >= 15 && opt_sumaTotal_pcl_jnci_emitido < 30){
                rango_pcl_jnci_emitido = 'Entre 15 y 29,99%';
            } else if (opt_sumaTotal_pcl_jnci_emitido >= 30 && opt_sumaTotal_pcl_jnci_emitido < 50){
                rango_pcl_jnci_emitido = 'Entre 30 y 49,99%';
            } else if (opt_sumaTotal_pcl_jnci_emitido >= 50){
                rango_pcl_jnci_emitido = 'Mayor o igual 50%';
            }else{
                rango_pcl_jnci_emitido = '0';
            }
            $('#rango_pcl_jnci_emitido').val(rango_pcl_jnci_emitido); //Coloca resultado Rango PCL

        }, 500);
    }

    // Mostrar Modulo y Selector de acuerdo al pronunciamiento DICTAMEN
    var opt_concepto_jrci;
    $("[name='decision_dictamen_jrci']").on("change", function(){
        opt_concepto_jrci = $(this).val();
         $(this).val(opt_concepto_jrci);
         iniciarIntervalo_concepto_jrci();
    });
    // Función para validar items a mostrar
    const tiempoDeslizamiento_concepto = 'slow';
    function iniciarIntervalo_concepto_jrci() {
        // Selección de los elementos que se deslizarán
        const elementosDeslizar_concepto = [
            '.row_causal_decision',
        ];
        const elementosDeslizar_concepto2 = [
            '.row_sustenta_jrci',
            '.row_f_sustenta_jrci',
            '.activa_boton_g'
        ];
        const elementosDeslizar_concepto3= [
            '.row_recurso_ante_jrci'
        ];
        // Mostrar Selector de acuerdo a la revision
        if(opt_concepto_jrci=='Acuerdo' || opt_concepto_jrci=='Desacuerdo'){
            //Listado causales
            let datos_lista_causales = {
            '_token': token,
            'parametro': "lista_causales_jrci",
            'causal': opt_concepto_jrci
            };
            $.ajax({
                type:'POST',
                url:'/selectoresJuntasControversia',
                data: datos_lista_causales,
                success:function(data) {
                    $("#causal_decision").empty();
                    let Ncausal = $('select[name=causal_decision]').val();
                    let licausal = Object.keys(data);
                    for (let i = 0; i < licausal.length; i++) {
                        if (data[licausal[i]]['Id_Parametro'] != Ncausal) {  
                            $('#causal_decision').append('<option value="'+data[licausal[i]]["Id_Parametro"]+'">'+data[licausal[i]]["Nombre_parametro"]+'</option>');
                        }
                    }
                }
            });
        }
        intervaloRe = setInterval(() => {
            switch (opt_concepto_jrci) {
                case "Acuerdo":
                    elementosDeslizar_concepto.forEach(elemento => {
                         $(elemento).slideDown(tiempoDeslizamiento_concepto);
                    }); 
                    elementosDeslizar_concepto2.forEach(elemento => {
                        $(elemento).slideDown(tiempoDeslizamiento_concepto);
                    });
                    elementosDeslizar_concepto3.forEach(elemento => {
                        $(elemento).slideUp(tiempoDeslizamiento_concepto);
                    }); 
                     
                    $('#causal_decision').prop('required', true);
                    $('#sustentacion_concepto_jrci').prop('required', true);
                    $("#f_notificacion_recurso_jrci").val("");
                    $("#n_radicado_recurso_jrci").val("");
                    $("#termino_contro_propia_jrci").val("");
                 break;
                 case "Desacuerdo":
                    elementosDeslizar_concepto.forEach(elemento => {
                         $(elemento).slideDown(tiempoDeslizamiento_concepto);
                    }); 
                    elementosDeslizar_concepto2.forEach(elemento => {
                        $(elemento).slideDown(tiempoDeslizamiento_concepto);
                    }); 
                    elementosDeslizar_concepto3.forEach(elemento => {
                        $(elemento).slideDown(tiempoDeslizamiento_concepto);
                    }); 
                    $('#causal_decision').prop('required', true);
                    $('#sustentacion_concepto_jrci').prop('required', true);
                 break;
                 default:
                    // Deslizar hacia arriba (ocultar) los elementos
                    elementosDeslizar_concepto.forEach(elemento => {
                         $(elemento).slideUp(tiempoDeslizamiento_concepto);
                    });
                    elementosDeslizar_concepto3.forEach(elemento => {
                            $(elemento).slideUp(tiempoDeslizamiento_concepto);
                    }); 
                    $("#causal_decision").empty();
                    $('#causal_decision').prop('required', false);
                    $("#f_notificacion_recurso_jrci").val("");
                    $("#n_radicado_recurso_jrci").val("");
                    $("#termino_contro_propia_jrci").val("");
                 break;
            }
        }, 500);
    }
    // Mostrar parte interesada presenta controversia ante la JRCI
    $("#firmeza_intere_contro_jrci").click(function(){
        if ($(this).is(":checked")) {
            $("#row_firmeza_intere").removeClass('d-none');
            $('#parte_contro_ante_jrci').prop('required', true);
        }else{
            $("#row_firmeza_intere").addClass('d-none');
            $('#parte_contro_ante_jrci').prop('required', false);
            //$("#f_transferencia_enfermedad").val("");
        }
    });
    // Mostrar eposición del Dictamen por parte de la JRCI
    $("#firmeza_reposicion_jrci").click(function(){
        if ($(this).is(":checked")) {
            $("#row_repo_dictamen").removeClass('d-none');
            //$('#parte_contro_ante_jrci').prop('required', true);
        }else{
            $("#row_repo_dictamen").addClass('d-none');
            //$('#parte_contro_ante_jrci').prop('required', false);
            //$("#f_transferencia_enfermedad").val("");
        }
    });

    // Mostrar Registrar Acta Ejecutoria emitida por JRCI
    $("#firmeza_acta_ejecutoria_jrci").click(function(){
        if ($(this).is(":checked")) {
            $("#row_acta_ejecutoria").removeClass('d-none');
        }else{
            $("#row_acta_ejecutoria").addClass('d-none');
        }
    });

    // Mostrar Registrar Acta Ejecutoria emitida por JRCI
    $("#firmeza_apelacion_jnci_jrci").click(function(){
        if ($(this).is(":checked")) {
            $("#row_emitido_jnci").removeClass('d-none');
        }else{
            $("#row_emitido_jnci").addClass('d-none');
        }
    });
    

    // Mostrar Modulo y Selector de acuerdo al pronunciamiento DICTAMEN reposicion JRCI
    var opt_concepto_repo_jrci;
    $("[name='decision_dictamen_repo_jrci']").on("change", function(){
        opt_concepto_repo_jrci = $(this).val();
         $(this).val(opt_concepto_repo_jrci);
         iniciarIntervalo_concepto_repo_jrci();
    });
    // Función para validar items a mostrar
    const tiempoDeslizamiento_concepto_repo = 'slow';
    function iniciarIntervalo_concepto_repo_jrci() {
        // Selección de los elementos que se deslizarán
        const elementosDeslizar_repo_concepto = [
            '.row_causal_decision_repo',
        ];
        const elementosDeslizar_repo_concepto2 = [
            '.row_sustenta_repo_jrci',
            '.row_f_sustenta_reposicion_jrci',
            '.activa_boton_repo_g'
        ];
        // Mostrar Selector de acuerdo a la revision
        if(opt_concepto_repo_jrci=='Acuerdo' || opt_concepto_repo_jrci=='Desacuerdo'){
            //Listado causales
            let datos_lista_causales = {
            '_token': token,
            'parametro': "lista_causales_jrci",
            'causal': opt_concepto_repo_jrci
            };
            $.ajax({
                type:'POST',
                url:'/selectoresJuntasControversia',
                data: datos_lista_causales,
                success:function(data) {
                    $("#causal_decision_repo").empty();
                    let Ncausal2 = $('select[name=causal_decision_repo]').val();
                    let licausal2 = Object.keys(data);
                    for (let i = 0; i < licausal2.length; i++) {
                        if (data[licausal2[i]]['Id_Parametro'] != Ncausal2) {  
                            $('#causal_decision_repo').append('<option value="'+data[licausal2[i]]["Id_Parametro"]+'">'+data[licausal2[i]]["Nombre_parametro"]+'</option>');
                        }
                    }
                }
            });
        }
        intervaloRe = setInterval(() => {
            switch (opt_concepto_repo_jrci) {
                case "Acuerdo":
                    elementosDeslizar_repo_concepto.forEach(elemento => {
                         $(elemento).slideDown(tiempoDeslizamiento_concepto_repo);
                    }); 
                    elementosDeslizar_repo_concepto2.forEach(elemento => {
                        $(elemento).slideDown(tiempoDeslizamiento_concepto_repo);
                    });
                    $('#causal_decision_repo').prop('required', true);
                    $('#sustentacion_concepto_repo_jrci').prop('required', true);
                 break;
                 case "Desacuerdo":
                    elementosDeslizar_repo_concepto.forEach(elemento => {
                         $(elemento).slideDown(tiempoDeslizamiento_concepto_repo);
                    }); 
                    elementosDeslizar_repo_concepto2.forEach(elemento => {
                        $(elemento).slideDown(tiempoDeslizamiento_concepto_repo);
                    }); 
                    $('#causal_decision_repo').prop('required', true);
                    $('#sustentacion_concepto_repo_jrci').prop('required', true);
                 break;
                 default:
                    // Deslizar hacia arriba (ocultar) los elementos
                    elementosDeslizar_repo_concepto.forEach(elemento => {
                         $(elemento).slideUp(tiempoDeslizamiento_concepto_repo);
                    });
                    $("#causal_decision_repo").empty();
                    $('#causal_decision_repo').prop('required', false);
                    ('#sustentacion_concepto_repo_jrci').prop('required', true);
                   
                 break;
            }
        }, 500);
    }

    // Mostrar Corresponde pago a JNCI 
    $("#correspon_pago_jnci").click(function(){
        if ($(this).is(":checked")) {
            $("#row_apela_num").removeClass('d-none');
            $("#row_apela_fecha").removeClass('d-none');
            $("#row_apela_fecha_radi").removeClass('d-none');
            $('#n_orden_pago_jnci').prop('required', true);
            $('#f_orden_pago_jnci').prop('required', true);
            $('#f_radi_pago_jnci').prop('required', true);
           
        }else{
            $("#row_apela_num").addClass('d-none');
            $("#row_apela_fecha").addClass('d-none');
            $("#row_apela_fecha_radi").addClass('d-none');
            $('#n_orden_pago_jnci').prop('required', false);
            $('#f_orden_pago_jnci').prop('required', false);
            $('#f_radi_pago_jnci').prop('required', false);
            $("#n_orden_pago_jnci").val("");
            $("#f_orden_pago_jnci").val("");
            $("#f_radi_pago_jnci").val("");
        }
    });

    // Guardar Datos Dictamen Controvertido
    $('#form_guardarControvertido').submit(function (e) {
        e.preventDefault();
        let token = $('input[name=_token]').val();  
        // Creacion de array con los datos de la tabla dinámica Diagnóstico Juntas
        var guardar_datos_motivo_calificacion = [];
        var datos_finales_motivo_calificacion = [];
        var array_id_filas = [];
        // RECORREMOS LOS TD DE LA TABLA PARA EXTRAER LOS DATOS E INSERTARLOS EN UN ARREGLO (LA INSERCIÓN LA HACE POR CADA FILA, POR ENDE, ES UN ARRAY MULTIDIMENSIONAL)
        $('#listado_diagnostico_cie10_controvertido tbody tr').each(function (index) {
            array_id_filas.push($(this).attr('id'));
            if ($(this).attr('id') !== "datos_diagnostico_controvertido") {
                $(this).children("td").each(function (index2) {
                    var nombres_ids = $(this).find('*').attr("id");
                    if (nombres_ids != undefined) {
                        if ($('#'+nombres_ids).val() == "on") {
                            if ($('#'+nombres_ids).is(':checked')) {
                                guardar_datos_motivo_calificacion.push("Si");  
                            } else {
                                guardar_datos_motivo_calificacion.push("No");  
                            }
                        }else{
                            guardar_datos_motivo_calificacion.push($('#'+nombres_ids).val());                     
                        }
                    }
                    if((index2+1) % 6 === 0){
                        datos_finales_motivo_calificacion.push(guardar_datos_motivo_calificacion);
                        guardar_datos_motivo_calificacion = [];
                    }
                });
            }
        });
        var datos_controvertido_juntas = {
            '_token': token,
            'newId_evento': $('#newId_evento').val(),
            'newId_asignacion': $('#newId_asignacion').val(),
            'Id_proceso': $('#Id_proceso').val(),
            'origen_controversia': $('#origen_controversia').val(),
            'manual_de_califi': $('#manual_de_califi').val(),
            'total_deficiencia': $('#total_deficiencia').val(),
            'total_rol_ocupacional': $('#total_rol_ocupacional').val(),
            'total_discapacidad': $('#total_discapacidad').val(),
            'total_minusvalia': $('#total_minusvalia').val(),
            'porcentaje_pcl': $('#porcentaje_pcl').val(),
            'rango_pcl': $('#rango_pcl').val(),
            'f_estructuracion_contro': $('#f_estructuracion_contro').val(),
            'n_pago_jnci_contro': $('#n_pago_jnci_contro').val(),
            'f_pago_jnci_contro': $('#f_pago_jnci_contro').val(),
            'f_radica_pago_jnci_contro': $('#f_radica_pago_jnci_contro').val(),
            'Motivo_calificacion_controvertido': datos_finales_motivo_calificacion,
        }
        document.querySelector("#guardar_datos_controvertido_j").disabled = true;
        $.ajax({
            type:'POST',
            url:'/registrarControvertidoJuntas',
            data: datos_controvertido_juntas,            
            success:function(response){
                if (response.parametro == 'registro_controvertido_juntas') {
                    $('.alerta_controvertido_juntas').removeClass('d-none');
                    $('.alerta_controvertido_juntas').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.alerta_controvertido_juntas').addClass('d-none');
                        $('.alerta_controvertido_juntas').empty();
                        //localStorage.setItem("#Generar_controvertido", true);
                        location.reload();
                    }, 3000);
                }
            }
        })
    }) 

    // Inactivar filas visuales cuando se eliminen de la pantalla para la tabla de Diagnósticos Motivo Calificacion Controvertido
    $(document).on('click', "a[id^='btn_remover_diagnosticos_moticalifi']", function(){
        var id_evento = $("#newId_evento").val();
        var id_asignacion = $('#newId_asignacion').val();
        var id_proceso = $("#Id_proceso").val();
        let token = $("input[name='_token']").val();
        var datos_fila_quitar_examen = {
            '_token': token,
            'fila' : $(this).data("id_fila_quitar"),
            'Id_evento': id_evento,
            'Id_asignacion': id_asignacion,
            'Id_proceso': id_proceso
            
        };
        
        $.ajax({
            type:'POST',
            url:'/eliminarDiagnosticosMotivoCalificacionContro',
            data: datos_fila_quitar_examen,
            success:function(response){
                console.log(response);
                if (response.parametro == "fila_diagnostico_eliminada") {
                    $('#resultado_insercion_cie10_controvertido').empty();
                    $('#resultado_insercion_cie10_controvertido').removeClass('d-none');
                    $('#resultado_insercion_cie10_controvertido').addClass('alert-success');
                    $('#resultado_insercion_cie10_controvertido').append('<strong>'+response.mensaje+'</strong>');
                    
                    setTimeout(() => {
                        $('#resultado_insercion_cie10_controvertido').addClass('d-none');
                        $('#resultado_insercion_cie10_controvertido').removeClass('alert-success');
                        $('#resultado_insercion_cie10_controvertido').empty();
                    }, 3000);
                }
                if (response.total_registros == 0) {
                    $("#conteo_listado_diagnosticos_moticalifi").val(response.total_registros);
                }
            }
        });        

    });
    // Quitar el Si como DX principal en la tabla Diagnósticos Motivo Calificacion Controvertido
    $(document).on('click', "input[id^='checkbox_dx_principal_visual_Cie10_']", function(){
        var fila = $(this).data("id_fila_checkbox_dx_principal_cie10_visual");
        let token = $("input[name='_token']").val();

        if ($("#checkbox_dx_principal_visual_Cie10_"+fila).is(":checked")) {
            var informacion_actualizar = {
                '_token': token,
                'fila':fila,
                'bandera': "Si",
                'Id_evento': $('#newId_evento').val(),
                'Id_Asignacion': $('#newId_asignacion').val(),
                'Id_proceso': $('#Id_proceso').val()
            }
        } else {
            var informacion_actualizar = {
                '_token': token,
                'fila':fila,
                'bandera': "No",
                'Id_evento': $('#newId_evento').val(),
                'Id_Asignacion': $('#newId_asignacion').val(),
                'Id_proceso': $('#Id_proceso').val()
            }
        };

        $.ajax({
            type:'POST',
            url:'/actualizarDxPrincipalDTOATEL',
            data: informacion_actualizar,
            success:function(response){
                if (response.parametro == "hecho") {
                    $("#resultado_insercion_cie10_controvertido").empty();
                    $("#resultado_insercion_cie10_controvertido").removeClass('d-none');
                    $("#resultado_insercion_cie10_controvertido").addClass('alert-success');
                    $("#resultado_insercion_cie10_controvertido").append('<strong>'+response.mensaje+'</strong>');

                    setTimeout(() => {
                        $("#resultado_insercion_cie10_controvertido").addClass('d-none');
                        $("#resultado_insercion_cie10_controvertido").removeClass('alert-success');
                        $("#resultado_insercion_cie10_controvertido").empty();
                        location.reload();
                    }, 3000);
                }              
            }
        });
    });
    // Inactivar filas visuales cuando se eliminen de la pantalla para la tabla de Diagnósticos Motivo Calificacion emitido Jrci
    $(document).on('click', "a[id^='btn_remover_diagnosticos_jrci_emitido']", function(){
        var id_evento = $("#newId_evento").val();
        var id_asignacion = $('#newId_asignacion').val();
        var id_proceso = $("#Id_proceso").val();
        let token = $("input[name='_token']").val();
        var datos_fila_quitar_examen = {
            '_token': token,
            'fila' : $(this).data("id_fila_quitar"),
            'Id_evento': id_evento,
            'Id_asignacion': id_asignacion,
            'Id_proceso': id_proceso
            
        };
        
        $.ajax({
            type:'POST',
            url:'/eliminarDiagnosticosMotivoCalificacionContro',
            data: datos_fila_quitar_examen,
            success:function(response){
                console.log(response);
                if (response.parametro == "fila_diagnostico_eliminada") {
                    $('#resultado_insercion_cie10_jrci_emitido').empty();
                    $('#resultado_insercion_cie10_jrci_emitido').removeClass('d-none');
                    $('#resultado_insercion_cie10_jrci_emitido').addClass('alert-success');
                    $('#resultado_insercion_cie10_jrci_emitido').append('<strong>'+response.mensaje+'</strong>');
                    
                    setTimeout(() => {
                        $('#resultado_insercion_cie10_jrci_emitido').addClass('d-none');
                        $('#resultado_insercion_cie10_jrci_emitido').removeClass('alert-success');
                        $('#resultado_insercion_cie10_jrci_emitido').empty();
                    }, 3000);
                }
                /* if (response.total_registros == 0) {
                    $("#conteo_listado_diagnosticos_moticalifi").val(response.total_registros);
                } */
            }
        });        

    });
    // Quitar el Si como DX principal en la tabla Diagnósticos Motivo Calificacion Emitido Jrci
    $(document).on('click', "input[id^='checkbox_dx_principal_visual_emitido_Cie10_']", function(){
        var fila = $(this).data("id_fila_checkbox_dx_principal_cie10_emitido_visual");
        let token = $("input[name='_token']").val();

        if ($("#checkbox_dx_principal_visual_emitido_Cie10_"+fila).is(":checked")) {
            var informacion_actualizar = {
                '_token': token,
                'fila':fila,
                'bandera': "Si",
                'Id_evento': $('#newId_evento').val(),
                'Id_Asignacion': $('#newId_asignacion').val(),
                'Id_proceso': $('#Id_proceso').val()
            }
        } else {
            var informacion_actualizar = {
                '_token': token,
                'fila':fila,
                'bandera': "No",
                'Id_evento': $('#newId_evento').val(),
                'Id_Asignacion': $('#newId_asignacion').val(),
                'Id_proceso': $('#Id_proceso').val()
            }
        };

        $.ajax({
            type:'POST',
            url:'/actualizarDxPrincipalDTOATEL',
            data: informacion_actualizar,
            success:function(response){
                if (response.parametro == "hecho") {
                    $("#resultado_insercion_cie10_jrci_emitido").empty();
                    $("#resultado_insercion_cie10_jrci_emitido").removeClass('d-none');
                    $("#resultado_insercion_cie10_jrci_emitido").addClass('alert-success');
                    $("#resultado_insercion_cie10_jrci_emitido").append('<strong>'+response.mensaje+'</strong>');

                    setTimeout(() => {
                        $("#resultado_insercion_cie10_jrci_emitido").addClass('d-none');
                        $("#resultado_insercion_cie10_jrci_emitido").removeClass('alert-success');
                        $("#resultado_insercion_cie10_jrci_emitido").empty();
                        location.reload();
                    }, 3000);
                }              
            }
        });
    });
    // Inactivar filas visuales cuando se eliminen de la pantalla para la tabla de Diagnósticos Motivo Calificacion reposicion Jrci
    $(document).on('click', "a[id^='btn_remover_diagnosticos_jrci_reposicion']", function(){
        var id_evento = $("#newId_evento").val();
        var id_asignacion = $('#newId_asignacion').val();
        var id_proceso = $("#Id_proceso").val();
        let token = $("input[name='_token']").val();
        var datos_fila_quitar_examen = {
            '_token': token,
            'fila' : $(this).data("id_fila_quitar"),
            'Id_evento': id_evento,
            'Id_asignacion': id_asignacion,
            'Id_proceso': id_proceso
            
        };
        
        $.ajax({
            type:'POST',
            url:'/eliminarDiagnosticosMotivoCalificacionContro',
            data: datos_fila_quitar_examen,
            success:function(response){
                console.log(response);
                if (response.parametro == "fila_diagnostico_eliminada") {
                    $('#resultado_insercion_cie10_jrci_reposicion').empty();
                    $('#resultado_insercion_cie10_jrci_reposicion').removeClass('d-none');
                    $('#resultado_insercion_cie10_jrci_reposicion').addClass('alert-success');
                    $('#resultado_insercion_cie10_jrci_reposicion').append('<strong>'+response.mensaje+'</strong>');
                    
                    setTimeout(() => {
                        $('#resultado_insercion_cie10_jrci_reposicion').addClass('d-none');
                        $('#resultado_insercion_cie10_jrci_reposicion').removeClass('alert-success');
                        $('#resultado_insercion_cie10_jrci_reposicion').empty();
                    }, 3000);
                }
                /* if (response.total_registros == 0) {
                    $("#conteo_listado_diagnosticos_moticalifi").val(response.total_registros);
                } */
            }
        });        

    });
    // Quitar el Si como DX principal en la tabla Diagnósticos Motivo Calificacion Emitido Jrci
    $(document).on('click', "input[id^='checkbox_dx_principal_visual_reposicion_Cie10_']", function(){
        var fila = $(this).data("id_fila_checkbox_dx_principal_cie10_reposicion_visual");
        let token = $("input[name='_token']").val();

        if ($("#checkbox_dx_principal_visual_reposicion_Cie10_"+fila).is(":checked")) {
            var informacion_actualizar = {
                '_token': token,
                'fila':fila,
                'bandera': "Si",
                'Id_evento': $('#newId_evento').val(),
                'Id_Asignacion': $('#newId_asignacion').val(),
                'Id_proceso': $('#Id_proceso').val()
            }
        } else {
            var informacion_actualizar = {
                '_token': token,
                'fila':fila,
                'bandera': "No",
                'Id_evento': $('#newId_evento').val(),
                'Id_Asignacion': $('#newId_asignacion').val(),
                'Id_proceso': $('#Id_proceso').val()
            }
        };

        $.ajax({
            type:'POST',
            url:'/actualizarDxPrincipalDTOATEL',
            data: informacion_actualizar,
            success:function(response){
                if (response.parametro == "hecho") {
                    $("#resultado_insercion_cie10_jrci_reposicion").empty();
                    $("#resultado_insercion_cie10_jrci_reposicion").removeClass('d-none');
                    $("#resultado_insercion_cie10_jrci_reposicion").addClass('alert-success');
                    $("#resultado_insercion_cie10_jrci_reposicion").append('<strong>'+response.mensaje+'</strong>');

                    setTimeout(() => {
                        $("#resultado_insercion_cie10_jrci_reposicion").addClass('d-none');
                        $("#resultado_insercion_cie10_jrci_reposicion").removeClass('alert-success');
                        $("#resultado_insercion_cie10_jrci_reposicion").empty();
                        location.reload();
                    }, 3000);
                }              
            }
        });
    });
    // Inactivar filas visuales cuando se eliminen de la pantalla para la tabla de Diagnósticos Motivo Calificacion emision Jnci
    $(document).on('click', "a[id^='btn_remover_diagnosticos_jnci_emitido']", function(){
        var id_evento = $("#newId_evento").val();
        var id_asignacion = $('#newId_asignacion').val();
        var id_proceso = $("#Id_proceso").val();
        let token = $("input[name='_token']").val();
        var datos_fila_quitar_examen = {
            '_token': token,
            'fila' : $(this).data("id_fila_quitar"),
            'Id_evento': id_evento,
            'Id_asignacion': id_asignacion,
            'Id_proceso': id_proceso
            
        };
        
        $.ajax({
            type:'POST',
            url:'/eliminarDiagnosticosMotivoCalificacionContro',
            data: datos_fila_quitar_examen,
            success:function(response){
                console.log(response);
                if (response.parametro == "fila_diagnostico_eliminada") {
                    $('#resultado_insercion_cie10_jnci_emitido').empty();
                    $('#resultado_insercion_cie10_jnci_emitido').removeClass('d-none');
                    $('#resultado_insercion_cie10_jnci_emitido').addClass('alert-success');
                    $('#resultado_insercion_cie10_jnci_emitido').append('<strong>'+response.mensaje+'</strong>');
                    
                    setTimeout(() => {
                        $('#resultado_insercion_cie10_jnci_emitido').addClass('d-none');
                        $('#resultado_insercion_cie10_jnci_emitido').removeClass('alert-success');
                        $('#resultado_insercion_cie10_jnci_emitido').empty();
                    }, 3000);
                }
                /* if (response.total_registros == 0) {
                    $("#conteo_listado_diagnosticos_moticalifi").val(response.total_registros);
                } */
            }
        });        

    });
    // Quitar el Si como DX principal en la tabla Diagnósticos Motivo Calificacion Emitido Jnci
    $(document).on('click', "input[id^='checkbox_dx_principal_visual_emitido_jnci_Cie10_']", function(){
        var fila = $(this).data("id_fila_checkbox_dx_principal_cie10_jnci_visual");
        let token = $("input[name='_token']").val();

        if ($("#checkbox_dx_principal_visual_emitido_jnci_Cie10_"+fila).is(":checked")) {
            var informacion_actualizar = {
                '_token': token,
                'fila':fila,
                'bandera': "Si",
                'Id_evento': $('#newId_evento').val(),
                'Id_Asignacion': $('#newId_asignacion').val(),
                'Id_proceso': $('#Id_proceso').val()
            }
        } else {
            var informacion_actualizar = {
                '_token': token,
                'fila':fila,
                'bandera': "No",
                'Id_evento': $('#newId_evento').val(),
                'Id_Asignacion': $('#newId_asignacion').val(),
                'Id_proceso': $('#Id_proceso').val()
            }
        };

        $.ajax({
            type:'POST',
            url:'/actualizarDxPrincipalDTOATEL',
            data: informacion_actualizar,
            success:function(response){
                if (response.parametro == "hecho") {
                    $("#resultado_insercion_cie10_jnci_emitido").empty();
                    $("#resultado_insercion_cie10_jnci_emitido").removeClass('d-none');
                    $("#resultado_insercion_cie10_jnci_emitido").addClass('alert-success');
                    $("#resultado_insercion_cie10_jnci_emitido").append('<strong>'+response.mensaje+'</strong>');

                    setTimeout(() => {
                        $("#resultado_insercion_cie10_jnci_emitido").addClass('d-none');
                        $("#resultado_insercion_cie10_jnci_emitido").removeClass('alert-success');
                        $("#resultado_insercion_cie10_jnci_emitido").empty();
                        location.reload();
                    }, 3000);
                }              
            }
        });
    });
    // Guardar Datos Emitido Jrci
    $('#form_guardarEmitidoJrci').submit(function (e) {
        e.preventDefault();
        let token = $('input[name=_token]').val();  
        // Creacion de array con los datos de la tabla dinámica Diagnóstico Juntas
        var guardar_datos_motivo_calificacion = [];
        var datos_finales_motivo_calificacion = [];
        var array_id_filas = [];
        // RECORREMOS LOS TD DE LA TABLA PARA EXTRAER LOS DATOS E INSERTARLOS EN UN ARREGLO (LA INSERCIÓN LA HACE POR CADA FILA, POR ENDE, ES UN ARRAY MULTIDIMENSIONAL)
        $('#listado_diagnostico_cie10_jrci_emitido tbody tr').each(function (index) {
            array_id_filas.push($(this).attr('id'));
            if ($(this).attr('id') !== "datos_diagnostico_emitido_jrci") {
                $(this).children("td").each(function (index2) {
                    var nombres_ids = $(this).find('*').attr("id");
                    if (nombres_ids != undefined) {
                        if ($('#'+nombres_ids).val() == "on") {
                            if ($('#'+nombres_ids).is(':checked')) {
                                guardar_datos_motivo_calificacion.push("Si");  
                            } else {
                                guardar_datos_motivo_calificacion.push("No");  
                            }
                        }else{
                            guardar_datos_motivo_calificacion.push($('#'+nombres_ids).val());                     
                        }
                    }
                    if((index2+1) % 6 === 0){
                        datos_finales_motivo_calificacion.push(guardar_datos_motivo_calificacion);
                        guardar_datos_motivo_calificacion = [];
                    }
                });
            }
        });
        var datos_emitido_jrci = {
            '_token': token,
            'newId_evento': $('#newId_evento').val(),
            'newId_asignacion': $('#newId_asignacion').val(),
            'Id_proceso': $('#Id_proceso').val(),
            'n_dictamen_jrci_emitido': $('#n_dictamen_jrci_emitido').val(),
            'f_dictamen_jrci_emitido': $('#f_dictamen_jrci_emitido').val(),
            'origen_jrci_emitido': $('#origen_jrci_emitido').val(),
            'manual_de_califi_jrci_emitido': $('#manual_de_califi_jrci_emitido').val(),
            'total_deficiencia_jrci_emitido': $('#total_deficiencia_jrci_emitido').val(),
            'total_rol_ocupacional_jrci_emitido': $('#total_rol_ocupacional_jrci_emitido').val(),
            'total_discapacidad_jrci_emitido': $('#total_discapacidad_jrci_emitido').val(),
            'total_minusvalia_jrci_emitido': $('#total_minusvalia_jrci_emitido').val(),
            'porcentaje_pcl_jrci_emitido': $('#porcentaje_pcl_jrci_emitido').val(),
            'rango_pcl_jrci_emitido': $('#rango_pcl_jrci_emitido').val(),
            'f_estructuracion_contro_jrci_emitido': $('#f_estructuracion_contro_jrci_emitido').val(),
            'resumen_dictamen_jrci': $('#resumen_dictamen_jrci').val(),
            'f_noti_dictamen_jrci': $('#f_noti_dictamen_jrci').val(),
            'f_radica_dictamen_jrci': $('#f_radica_dictamen_jrci').val(),
            'Motivo_calificacion_emitido': datos_finales_motivo_calificacion,
        }
        document.querySelector("#guardar_datos_emitido_jrci").disabled = true;
        $.ajax({
            type:'POST',
            url:'/registrarEmitidoJrci',
            data: datos_emitido_jrci,           
            success:function(response){
                if (response.parametro == 'registro_emitido_jrci') {
                    $('.alerta_emitido_jrci').removeClass('d-none');
                    $('.alerta_emitido_jrci').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.alerta_emitido_jrci').addClass('d-none');
                        $('.alerta_emitido_jrci').empty();
                        location.reload();
                    }, 3000);
                }
            }
        })
    }) 

    // Guardar revision concepto Junta Regional
    $('#form_guardarRevisionjrci').submit(function (e) {
        e.preventDefault();
        let token = $('input[name=_token]').val();
        var datos_revision_jrci = {
            '_token': token,
            'newId_evento': $('#newId_evento').val(),
            'newId_asignacion': $('#newId_asignacion').val(),
            'Id_proceso': $('#Id_proceso').val(),
            'decision_dictamen_jrci': $('input[name=decision_dictamen_jrci]').filter(":checked").val(),
            'causal_decision': $('#causal_decision').val(),
            'sustentacion_concepto_jrci': $('#sustentacion_concepto_jrci').val(),
        }
       document.querySelector("#guardar_datos_revision_jrci").disabled = true;
        $.ajax({
            type:'POST',
            url:'/registrarRevisionJrci',
            data: datos_revision_jrci,           
            success:function(response){
                if (response.parametro == 'registro_revision_jrci') {
                    $('.alerta_revision_jrci').removeClass('d-none');
                    $('.alerta_revision_jrci').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.alerta_revision_jrci').addClass('d-none');
                        $('.alerta_revision_jrci').empty();
                        location.reload();
                    }, 3000);
                }
            }
        })
    }) 

    // Guardar Recurso Junta Jrci
    $('#form_guardarRecursojrci').submit(function (e) {
        e.preventDefault();
        let token = $('input[name=_token]').val();
        var datos_recurso_jrci = {
            '_token': token,
            'newId_evento': $('#newId_evento').val(),
            'newId_asignacion': $('#newId_asignacion').val(),
            'Id_proceso': $('#Id_proceso').val(),
            'f_notificacion_recurso_jrci': $('#f_notificacion_recurso_jrci').val(),
            'f_maxima_recurso_jrci': $('#f_maxima_recurso_jrci').val(),
            'n_radicado_recurso_jrci': $('#n_radicado_recurso_jrci').val(),
        }
       document.querySelector("#guardar_datos_recursos_jrci").disabled = true;
        $.ajax({
            type:'POST',
            url:'/registrarRecursoJrci',
            data: datos_recurso_jrci,           
            success:function(response){
                if (response.parametro == 'registro_recurso_jrci') {
                    $('.alerta_recursos_jrci').removeClass('d-none');
                    $('.alerta_recursos_jrci').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.alerta_recursos_jrci').addClass('d-none');
                        $('.alerta_recursos_jrci').empty();
                        location.reload();
                    }, 3000);
                }
            }
        })
    }) 
    //Guardar informacion partes interesadas
    $('#form_guardarInteresadajrci').submit(function (e) {
        e.preventDefault();
        let token = $('input[name=_token]').val();
        var datos_partes_jrci = {
            '_token': token,
            'newId_evento': $('#newId_evento').val(),
            'newId_asignacion': $('#newId_asignacion').val(),
            'Id_proceso': $('#Id_proceso').val(),
            'firmeza_intere_contro_jrci': $('#firmeza_intere_contro_jrci').filter(":checked").val(),
            'firmeza_reposicion_jrci': $('#firmeza_reposicion_jrci').filter(":checked").val(),
            'firmeza_acta_ejecutoria_jrci': $('#firmeza_acta_ejecutoria_jrci').filter(":checked").val(),
            'firmeza_apelacion_jnci_jrci': $('#firmeza_apelacion_jnci_jrci').filter(":checked").val(),
        }
       document.querySelector("#guardar_datos_partes_jrci").disabled = true;
        $.ajax({
            type:'POST',
            url:'/registrarPartesJrci',
            data: datos_partes_jrci,           
            success:function(response){
                if (response.parametro == 'registro_parte_jrci') {
                    $('.alerta_partes_jrci').removeClass('d-none');
                    $('.alerta_partes_jrci').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.alerta_partes_jrci').addClass('d-none');
                        $('.alerta_partes_jrci').empty();
                        location.reload();
                    }, 3000);
                }
            }
        })
    }) 
    //Guardar Otra parte interesada presenta controversia ante la JRCI
    $('#form_guardarOtraJRCI').submit(function (e) {
        e.preventDefault();
        let token = $('input[name=_token]').val();
        var datos_partes_contro_jrci = {
            '_token': token,
            'newId_evento': $('#newId_evento').val(),
            'newId_asignacion': $('#newId_asignacion').val(),
            'Id_proceso': $('#Id_proceso').val(),
            'parte_contro_ante_jrci': $('#parte_contro_ante_jrci').val(),
            'nombre_presen_contro_jrci': $('#nombre_presen_contro_jrci').val(),
            'f_contro_otra_jrci': $('#f_contro_otra_jrci').val(),
            'contro_origen_jrci': $('#contro_origen_jrci').filter(":checked").val(),
            'contro_pcl_jrci': $('#contro_pcl_jrci').filter(":checked").val(),
            'contro_diagnostico_jrci': $('#contro_diagnostico_jrci').filter(":checked").val(),
            'contro_f_estructura_jrci': $('#contro_f_estructura_jrci').filter(":checked").val(),
            'contro_m_califi_jrci': $('#contro_m_califi_jrci').filter(":checked").val(),
        }
       document.querySelector("#guardar_datos_interasa_contro_jrci").disabled = true;
        $.ajax({
            type:'POST',
            url:'/registrarPartesControJrci',
            data: datos_partes_contro_jrci,           
            success:function(response){
                if (response.parametro == 'registro_parte_contro_jrci') {
                    $('.alerta_interasa_contro_jrci').removeClass('d-none');
                    $('.alerta_interasa_contro_jrci').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.alerta_interasa_contro_jrci').addClass('d-none');
                        $('.alerta_interasa_contro_jrci').empty();
                        location.reload();
                    }, 3000);
                }
            }
        })
    }) 
    //Guardar Reposición del Dictamen por parte de la JRCI
    $('#form_guardarReposicionJRCI').submit(function (e) {
        e.preventDefault();
        let token = $('input[name=_token]').val();

        // Creacion de array con los datos de la tabla dinámica Diagnóstico Juntas
        var guardar_datos_motivo_calificacion = [];
        var datos_finales_motivo_calificacion = [];
        var array_id_filas = [];
        // RECORREMOS LOS TD DE LA TABLA PARA EXTRAER LOS DATOS E INSERTARLOS EN UN ARREGLO (LA INSERCIÓN LA HACE POR CADA FILA, POR ENDE, ES UN ARRAY MULTIDIMENSIONAL)
        $('#listado_diagnostico_cie10_jrci_reposicion tbody tr').each(function (index) {
            array_id_filas.push($(this).attr('id'));
            if ($(this).attr('id') !== "datos_diagnostico_repo_jrci") {
                $(this).children("td").each(function (index2) {
                    var nombres_ids = $(this).find('*').attr("id");
                    if (nombres_ids != undefined) {
                        if ($('#'+nombres_ids).val() == "on") {
                            if ($('#'+nombres_ids).is(':checked')) {
                                guardar_datos_motivo_calificacion.push("Si");  
                            } else {
                                guardar_datos_motivo_calificacion.push("No");  
                            }
                        }else{
                            guardar_datos_motivo_calificacion.push($('#'+nombres_ids).val());                     
                        }
                    }
                    if((index2+1) % 6 === 0){
                        datos_finales_motivo_calificacion.push(guardar_datos_motivo_calificacion);
                        guardar_datos_motivo_calificacion = [];
                    }
                });
            }
        });

        var datos_partes_repo_jrci = {
            '_token': token,
            'newId_evento': $('#newId_evento').val(),
            'newId_asignacion': $('#newId_asignacion').val(),
            'Id_proceso': $('#Id_proceso').val(),
            'reposicion_dictamen_jrci': $('input[name=reposicion_dictamen_jrci]').filter(":checked").val(),
            'n_dictamen_reposicion_jrci': $('#n_dictamen_reposicion_jrci').val(),
            'f_dictamen_reposicion_jrci': $('#f_dictamen_reposicion_jrci').val(),
            'origen_reposicion_jrci': $('#origen_reposicion_jrci').val(),
            'manual_reposicion_jrci': $('#manual_reposicion_jrci').val(),
            'total_deficiencia_reposicion_jrci': $('#total_deficiencia_reposicion_jrci').val(),
            'total_discapacidad_reposicion_jrci': $('#total_discapacidad_reposicion_jrci').val(),
            'total_minusvalia_reposicion_jrci': $('#total_minusvalia_reposicion_jrci').val(),
            'porcentaje_pcl_reposicion_jrci': $('#porcentaje_pcl_reposicion_jrci').val(),
            'f_estructuracion_contro_reposicion_jrci': $('#f_estructuracion_contro_reposicion_jrci').val(),
            'resumen_dictamen_reposicion_jrci': $('#resumen_dictamen_reposicion_jrci').val(),
            'f_noti_dictamen_reposicion_jrci': $('#f_noti_dictamen_reposicion_jrci').val(),
            'f_radica_dictamen_reposicion_jrci': $('#f_radica_dictamen_reposicion_jrci').val(),
            'Motivo_calificacion_repo': datos_finales_motivo_calificacion,
        }
       document.querySelector("#guardar_datos_reposicion_jrci").disabled = true;
        $.ajax({
            type:'POST',
            url:'/registrarDatosRepoJrci',
            data: datos_partes_repo_jrci,           
            success:function(response){
                if (response.parametro == 'registro_datos_repo_jrci') {
                    $('.alerta_reposicion_jrci').removeClass('d-none');
                    $('.alerta_reposicion_jrci').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.alerta_reposicion_jrci').addClass('d-none');
                        $('.alerta_reposicion_jrci').empty();
                        location.reload();
                    }, 3000);
                }
            }
        })
    })
    // Guardar recursos reposicion Junta Regional
    $('#form_guardarRevisionRecursojrci').submit(function (e) {
        e.preventDefault();
        let token = $('input[name=_token]').val();
        var datos_reposicion_jrci = {
            '_token': token,
            'newId_evento': $('#newId_evento').val(),
            'newId_asignacion': $('#newId_asignacion').val(),
            'Id_proceso': $('#Id_proceso').val(),
            'decision_dictamen_repo_jrci': $('input[name=decision_dictamen_repo_jrci]').filter(":checked").val(),
            'causal_decision_repo': $('#causal_decision_repo').val(),
            'sustentacion_concepto_repo_jrci': $('#sustentacion_concepto_repo_jrci').val(),
        }
       document.querySelector("#guardar_datos_concepto_repo_jrci").disabled = true;
        $.ajax({
            type:'POST',
            url:'/registrarReposicionJrci',
            data: datos_reposicion_jrci,           
            success:function(response){
                if (response.parametro == 'registro_reposicion_jrci') {
                    $('.alerta_concepto_repo_jrci').removeClass('d-none');
                    $('.alerta_concepto_repo_jrci').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.alerta_concepto_repo_jrci').addClass('d-none');
                        $('.alerta_concepto_repo_jrci').empty();
                        location.reload();
                    }, 3000);
                }
            }
        })
    }) 
    // Guardar Apelación de recurso ante la JNCI
    $('#form_guardarApelaciónJnci').submit(function (e) {
        e.preventDefault();
        let token = $('input[name=_token]').val();
        var datos_apelacion_jnci = {
            '_token': token,
            'newId_evento': $('#newId_evento').val(),
            'newId_asignacion': $('#newId_asignacion').val(),
            'Id_proceso': $('#Id_proceso').val(),
            'f_noti_apela_recurso_jrci': $('#f_noti_apela_recurso_jrci').val(),
            'n_radicado_apela_recurso_jrci': $('#n_radicado_apela_recurso_jrci').val(),
            'correspon_pago_jnci': $('input[name=correspon_pago_jnci]').filter(":checked").val(),
            'n_orden_pago_jnci': $('#n_orden_pago_jnci').val(),
            'f_orden_pago_jnci': $('#f_orden_pago_jnci').val(),
            'f_radi_pago_jnci': $('#f_radi_pago_jnci').val(),
            'f_maxima_apelacion_jrci': $('#f_maxima_apelacion_jrci').val(),
        }
       document.querySelector("#guardar_datos_apela_jnci").disabled = true;
        $.ajax({
            type:'POST',
            url:'/registrarApelaJrci',
            data: datos_apelacion_jnci,           
            success:function(response){
                if (response.parametro == 'registro_apela_jrci') {
                    $('.alerta_datos_apela_jnci').removeClass('d-none');
                    $('.alerta_datos_apela_jnci').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.alerta_datos_apela_jnci').addClass('d-none');
                        $('.alerta_datos_apela_jnci').empty();
                        location.reload();
                    }, 3000);
                }
            }
        })
    }) 
    // Guardar Acta Ejecutoria emitida por JRCI
    $('#form_guardarActaEjecuJrci').submit(function (e) {
        e.preventDefault();
        let token = $('input[name=_token]').val();
        var datos_acta_jrci = {
            '_token': token,
            'newId_evento': $('#newId_evento').val(),
            'newId_asignacion': $('#newId_asignacion').val(),
            'Id_proceso': $('#Id_proceso').val(),
            'n_acta_ejecutario_emitida_jrci': $('#n_acta_ejecutario_emitida_jrci').val(),
            'f_acta_ejecutoria_emitida_jrci': $('#f_acta_ejecutoria_emitida_jrci').val(),
            'f_firmeza_dictamen_jrci': $('#f_firmeza_dictamen_jrci').val(),
        }
       document.querySelector("#guardar_datos_ejecutoria_jrci").disabled = true;
        $.ajax({
            type:'POST',
            url:'/registrarActaJrci',
            data: datos_acta_jrci,           
            success:function(response){
                if (response.parametro == 'registro_acta_jrci') {
                    $('.alerta_datos_ejecutoria_jrci').removeClass('d-none');
                    $('.alerta_datos_ejecutoria_jrci').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.alerta_datos_ejecutoria_jrci').addClass('d-none');
                        $('.alerta_datos_ejecutoria_jrci').empty();
                        location.reload();
                    }, 3000);
                }
            }
        })
    }) 
    // Guardar Datos Emitido Jrci
    $('#form_guardarEmitidoJnci').submit(function (e) {
        e.preventDefault();
        let token = $('input[name=_token]').val();  
        // Creacion de array con los datos de la tabla dinámica Diagnóstico Juntas
        var guardar_datos_motivo_calificacion = [];
        var datos_finales_motivo_calificacion = [];
        var array_id_filas = [];
        // RECORREMOS LOS TD DE LA TABLA PARA EXTRAER LOS DATOS E INSERTARLOS EN UN ARREGLO (LA INSERCIÓN LA HACE POR CADA FILA, POR ENDE, ES UN ARRAY MULTIDIMENSIONAL)
        $('#listado_diagnostico_cie10_jnci_emitido tbody tr').each(function (index) {
            array_id_filas.push($(this).attr('id'));
            if ($(this).attr('id') !== "datos_diagnostico_emitido_jnci") {
                $(this).children("td").each(function (index2) {
                    var nombres_ids = $(this).find('*').attr("id");
                    if (nombres_ids != undefined) {
                        if ($('#'+nombres_ids).val() == "on") {
                            if ($('#'+nombres_ids).is(':checked')) {
                                guardar_datos_motivo_calificacion.push("Si");  
                            } else {
                                guardar_datos_motivo_calificacion.push("No");  
                            }
                        }else{
                            guardar_datos_motivo_calificacion.push($('#'+nombres_ids).val());                     
                        }
                    }
                    if((index2+1) % 6 === 0){
                        datos_finales_motivo_calificacion.push(guardar_datos_motivo_calificacion);
                        guardar_datos_motivo_calificacion = [];
                    }
                });
            }
        });
        var datos_emitido_jnci = {
            '_token': token,
            'newId_evento': $('#newId_evento').val(),
            'newId_asignacion': $('#newId_asignacion').val(),
            'Id_proceso': $('#Id_proceso').val(),
            'n_dictamen_jnci_emitido': $('#n_dictamen_jnci_emitido').val(),
            'f_dictamen_jnci_emitido': $('#f_dictamen_jnci_emitido').val(),
            'origen_jnci_emitido': $('#origen_jnci_emitido').val(),
            'manual_de_califi_jnci_emitido': $('#manual_de_califi_jnci_emitido').val(),
            'total_deficiencia_jnci_emitido': $('#total_deficiencia_jnci_emitido').val(),
            'total_rol_ocupacional_jnci_emitido': $('#total_rol_ocupacional_jnci_emitido').val(),
            'total_discapacidad_jnci_emitido': $('#total_discapacidad_jnci_emitido').val(),
            'total_minusvalia_jnci_emitido': $('#total_minusvalia_jnci_emitido').val(),
            'porcentaje_pcl_jnci_emitido': $('#porcentaje_pcl_jnci_emitido').val(),
            'rango_pcl_jnci_emitido': $('#rango_pcl_jnci_emitido').val(),
            'f_estructuracion_contro_jnci_emitido': $('#f_estructuracion_contro_jnci_emitido').val(),
            'resumen_dictamen_jnci': $('#resumen_dictamen_jnci').val(),
            'sustentacion_dictamen_jnci': $('#sustentacion_dictamen_jnci').val(),
            'f_sustenta_ante_jnci': $('#f_sustenta_ante_jnci').val(),
            'f_noti_ante_jnci': $('#f_noti_ante_jnci').val(),
            'f_radica_dictamen_jnci': $('#f_radica_dictamen_jnci').val(),
            'Motivo_calificacion_emitido': datos_finales_motivo_calificacion,
        }
        document.querySelector("#guardar_datos_emitido_jnci").disabled = true;
        $.ajax({
            type:'POST',
            url:'/registrarEmitidoJnci',
            data: datos_emitido_jnci,           
            success:function(response){
                if (response.parametro == 'registro_emitido_jnci') {
                    $('.alerta_datos_emitido_jnci').removeClass('d-none');
                    $('.alerta_datos_emitido_jnci').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.alerta_datos_emitido_jnci').addClass('d-none');
                        $('.alerta_datos_emitido_jnci').empty();
                        location.reload();
                    }, 3000);
                }
            }
        })
    })
    /* Obtener el ID del evento a dar clic en cualquier botón de cargue de archivo y asignarlo al input hidden del id evento */
    $("input[id^='listadodocumento_']").click(function(){
        let idobtenido = $('#newId_evento').val();
        //console.log(idobtenido);
        $("input[id^='EventoID_']").val(idobtenido);
    });
    /* Envío de Información del Documento a Cargar */
    $("form[id^='formulario_documento_']").submit(function(e){

        e.preventDefault();
        var formData = new FormData($(this)[0]);
        var cambio_estado = $(this).parents()[1]['children'][2]["id"];
        var input_documento = $(this).parents()[0]['children'][0][4]["id"];

        //for (var pair of formData.entries()) {
        //   console.log(pair[0]+ ', ' + pair[1]); 
        //}
        // Enviamos los datos para validar y guardar el docmuento correspondiente
        $.ajax({
            url: "/cargarDocumentos",
            type: "post",
            dataType: "json",
            data: formData,
            cache: false,
            contentType: false,
            processData: false  ,
            success:function(response){
                // console.log(response);
                if (response.parametro == "fallo") {
                    if (response.otro != undefined) {
                        $('#listadodocumento_'+response.otro).val('');
                    }else{
                        $('#'+input_documento).val('');
                    }
                    $('.mostrar_fallo').removeClass('d-none');
                    $('.mostrar_fallo').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.mostrar_fallo').addClass('d-none');
                        $('.mostrar_fallo').empty();
                    }, 6000);
                }else if (response.parametro == "exito") {
                    if(response.otro != undefined){
                        $("#estadoDocumentoOtro_"+response.otro).empty();
                        $("#estadoDocumentoOtro_"+response.otro).append('<strong class="text-success">Cargado</strong>');
                        $('#listadodocumento_'+response.otro).prop("disabled", true);
                        $('#CargarDocumento_'+response.otro).prop("disabled", true);
                        $('#habilitar_modal_otro_doc').prop("disabled", true);
                    }else{
                        $("#"+cambio_estado).empty();
                        $("#"+cambio_estado).append('<strong class="text-success">Cargado</strong>');
                    }
                    $('.mostrar_exito').removeClass('d-none');
                    $('.mostrar_exito').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.mostrar_exito').addClass('d-none');
                        $('.mostrar_exito').empty();
                    }, 6000);
                }else{}
                

            }         
        });
    });
    
    // Captura del nombre del usuario que marca el checkbox Visar
    // var NombreUsuario = $("#NombreUsuario").val();
    // var Visar = $("#visar");

    // Visar.change(function(){
    //     if ($(this).prop('checked')) {
    //         $("#profesional_comite").val(NombreUsuario);            
    //     } else {            
    //         $("#profesional_comite").val('');            
    //     }
    // });    
    
    // //Captura Formulario Comite Interdisciplinario
    // $('#form_comite_interdisciplinario').submit(function (e){
    //     e.preventDefault();              
       
    //     var newId_evento = $('#newId_evento').val();
    //     var Id_proceso = $('#Id_proceso').val();
    //     var newId_asignacion  = $('#newId_asignacion').val();
    //     var visar = $('#visar').val();
    //     var profesional_comite = $('#profesional_comite').val();
    //     var f_visado_comite = $('#f_visado_comite').val();
       
    //     var datos_comiteInterdisciplianario={
    //         '_token': token,            
    //         'newId_evento':newId_evento,
    //         'Id_proceso':Id_proceso,
    //         'newId_asignacion':newId_asignacion,
    //         'visar':visar,
    //         'profesional_comite':profesional_comite,
    //         'f_visado_comite':f_visado_comite,
    //     }

    //     $.ajax({    
    //         type:'POST',
    //         url:'/guardarcomitesinterdisciplinarioJuntas',
    //         data: datos_comiteInterdisciplianario,
    //         success: function(response){
    //             if (response.parametro == 'insertar_comite_interdisciplinario') {
    //                 $('#GuardarComiteInter').prop('disabled', true);
    //                 $('#div_alerta_comiteInter').removeClass('d-none');
    //                 $('.alerta_comiteInter').append('<strong>'+response.mensaje+'</strong>');                                            
    //                 setTimeout(function(){
    //                     $('#div_alerta_comiteInter').addClass('d-none');
    //                     $('.alerta_comiteInter').empty();   
    //                     location.reload();
    //                 }, 3000);   
    //             }
    //         }          
    //     })
    // }) 

    // var profesional_comite = $("#profesional_comite").val();
    // if (profesional_comite !== '') {
    //     $("#GuardarComiteInter").prop('disabled', true);
    //     $("#div_correspondecia").removeClass('d-none');
    // }

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

    //Captura Formulario Correspondencia
    $('#form_correspondencia').submit(function (e){
        e.preventDefault();              
        
        var newId_evento = $('#newId_evento').val();
        var Id_proceso = $('#Id_proceso').val();
        var newId_asignacion  = $('#newId_asignacion').val();
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
        var empleador = $('input[name="empleador"]:checked').val();;        
        var eps = $('input[name="eps"]:checked').val();
        var afp = $('input[name="afp"]:checked').val();
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
        
        var datos_correspondecia={
            '_token': token,            
            'newId_evento':newId_evento,
            'Id_proceso':Id_proceso,
            'newId_asignacion':newId_asignacion,            
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
            'cuerpo_comunicado':cuerpo_comunicado,
            'empleador':empleador,
            'eps':eps,
            'afp':afp,
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
            'bandera_correspondecia_guardar_actualizar':bandera_correspondecia_guardar_actualizar
        }

        $.ajax({    
            type:'POST',
            url:'/guardarcorrespondenciasJuntas',
            data: datos_correspondecia,
            success: function(response){
                if (response.parametro == 'insertar_correspondencia') {
                    $('#GuardarCorrespondencia').prop('disabled', true);
                    $('#div_alerta_Correspondencia').removeClass('d-none');
                    $('.alerta_Correspondencia').append('<strong>'+response.mensaje+'</strong>');                                            
                    setTimeout(function(){
                        $('#div_alerta_Correspondencia').addClass('d-none');
                        $('.alerta_Correspondencia').empty();   
                        location.reload();
                    }, 3000);   
                }else if(response.parametro == 'actualizar_correspondencia'){
                    $('#ActualizarCorrespondencia').prop('disabled', true);
                    $('#div_alerta_Correspondencia').removeClass('d-none');
                    $('.alerta_Correspondencia').append('<strong>'+response.mensaje+'</strong>');                                            
                    setTimeout(function(){
                        $('#div_alerta_Correspondencia').addClass('d-none');
                        $('.alerta_Correspondencia').empty();   
                        location.reload();
                    }, 3000);  
                }

            }          
        })
    })

});
/* Función para añadir los controles de cada elemento de cada fila en la tabla Diagnostico motivo de calificación*/
/*Para Diagnosticos Controvertido*/
function funciones_elementos_fila_diagnosticos(num_consecutivo) {
    // Inicializacion de select 2
    $("#lista_Cie10_fila_"+num_consecutivo).select2({
        width: '340px',
        placeholder: "Seleccione",
        allowClear: false
    });

    $("#lista_origenCie10_fila_"+num_consecutivo).select2({
        width: '100%',
        placeholder: "Seleccione",
        allowClear: false
    });

    $("#lista_lateralidadCie10_fila_"+num_consecutivo).select2({
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
        url:'/cargueListadoSelectoresDTOATEL',
        data: datos_CIE10,
        success:function(data){
            // $("select[id^='lista_Cie10_fila_']").empty();
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#lista_Cie10_fila_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Cie_diagnostico"]+'">'+data[claves[i]]["CIE10"]+' - '+data[claves[i]]["Descripcion_diagnostico"]+'</option>');
            }
        }
    });

    let datos_Origen_CIE10 = {
        '_token': token,
        'parametro' : "listado_OrigenCIE10",
    };
    $.ajax({
        type:'POST',
        url:'/cargueListadoSelectoresDTOATEL',
        data: datos_Origen_CIE10,
        success:function(data){
            // $("select[id^='lista_origenCie10_fila_']").empty();
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#lista_origenCie10_fila_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });

    let datos_Lateralidad_CIE10 = {
        '_token': token,
        'parametro' : "listado_LateralidadCIE10",
    };
    $.ajax({
        type:'POST',
        url:'/cargueListadoSelectoresDTOATEL',
        data: datos_Lateralidad_CIE10,
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
            url:'/cargueListadoSelectoresDTOATEL',
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
function funciones_elementos_fila_diagnosticos2(num_consecutivo) {
    // Inicializacion de select 2
    $("#lista_Cie10_fila_"+num_consecutivo).select2({
        width: '340px',
        placeholder: "Seleccione",
        allowClear: false
    });

    $("#lista_origenCie10_fila_"+num_consecutivo).select2({
        width: '100%',
        placeholder: "Seleccione",
        allowClear: false
    });

    $("#lista_lateralidadCie10_fila_"+num_consecutivo).select2({
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
        url:'/cargueListadoSelectoresDTOATEL',
        data: datos_CIE10,
        success:function(data){
            // $("select[id^='lista_Cie10_fila_']").empty();
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#lista_Cie10_fila_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Cie_diagnostico"]+'">'+data[claves[i]]["CIE10"]+' - '+data[claves[i]]["Descripcion_diagnostico"]+'</option>');
            }
        }
    });

    let datos_Origen_CIE10 = {
        '_token': token,
        'parametro' : "listado_OrigenCIE10",
    };
    $.ajax({
        type:'POST',
        url:'/cargueListadoSelectoresDTOATEL',
        data: datos_Origen_CIE10,
        success:function(data){
            // $("select[id^='lista_origenCie10_fila_']").empty();
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#lista_origenCie10_fila_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });

    let datos_Lateralidad_CIE10 = {
        '_token': token,
        'parametro' : "listado_LateralidadCIE10",
    };
    $.ajax({
        type:'POST',
        url:'/cargueListadoSelectoresDTOATEL',
        data: datos_Lateralidad_CIE10,
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
            url:'/cargueListadoSelectoresDTOATEL',
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
function funciones_elementos_fila_diagnosticos3(num_consecutivo) {
    // Inicializacion de select 2
    $("#lista_Cie10_fila_"+num_consecutivo).select2({
        width: '340px',
        placeholder: "Seleccione",
        allowClear: false
    });

    $("#lista_origenCie10_fila_"+num_consecutivo).select2({
        width: '100%',
        placeholder: "Seleccione",
        allowClear: false
    });

    $("#lista_lateralidadCie10_fila_"+num_consecutivo).select2({
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
        url:'/cargueListadoSelectoresDTOATEL',
        data: datos_CIE10,
        success:function(data){
            // $("select[id^='lista_Cie10_fila_']").empty();
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#lista_Cie10_fila_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Cie_diagnostico"]+'">'+data[claves[i]]["CIE10"]+' - '+data[claves[i]]["Descripcion_diagnostico"]+'</option>');
            }
        }
    });

    let datos_Origen_CIE10 = {
        '_token': token,
        'parametro' : "listado_OrigenCIE10",
    };
    $.ajax({
        type:'POST',
        url:'/cargueListadoSelectoresDTOATEL',
        data: datos_Origen_CIE10,
        success:function(data){
            // $("select[id^='lista_origenCie10_fila_']").empty();
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#lista_origenCie10_fila_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });

    let datos_Lateralidad_CIE10 = {
        '_token': token,
        'parametro' : "listado_LateralidadCIE10",
    };
    $.ajax({
        type:'POST',
        url:'/cargueListadoSelectoresDTOATEL',
        data: datos_Lateralidad_CIE10,
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
            url:'/cargueListadoSelectoresDTOATEL',
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
function funciones_elementos_fila_diagnosticos4(num_consecutivo) {
    // Inicializacion de select 2
    $("#lista_Cie10_fila_"+num_consecutivo).select2({
        width: '340px',
        placeholder: "Seleccione",
        allowClear: false
    });

    $("#lista_origenCie10_fila_"+num_consecutivo).select2({
        width: '100%',
        placeholder: "Seleccione",
        allowClear: false
    });

    $("#lista_lateralidadCie10_fila_"+num_consecutivo).select2({
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
        url:'/cargueListadoSelectoresDTOATEL',
        data: datos_CIE10,
        success:function(data){
            // $("select[id^='lista_Cie10_fila_']").empty();
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#lista_Cie10_fila_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Cie_diagnostico"]+'">'+data[claves[i]]["CIE10"]+' - '+data[claves[i]]["Descripcion_diagnostico"]+'</option>');
            }
        }
    });

    let datos_Origen_CIE10 = {
        '_token': token,
        'parametro' : "listado_OrigenCIE10",
    };
    $.ajax({
        type:'POST',
        url:'/cargueListadoSelectoresDTOATEL',
        data: datos_Origen_CIE10,
        success:function(data){
            // $("select[id^='lista_origenCie10_fila_']").empty();
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#lista_origenCie10_fila_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });

    let datos_Lateralidad_CIE10 = {
        '_token': token,
        'parametro' : "listado_LateralidadCIE10",
    };
    $.ajax({
        type:'POST',
        url:'/cargueListadoSelectoresDTOATEL',
        data: datos_Lateralidad_CIE10,
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
            url:'/cargueListadoSelectoresDTOATEL',
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

// Redondear Numero
function redondearNumero(numero) {
    return Math.round(numero * 100) / 100;
}