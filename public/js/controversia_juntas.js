$(document).ready(function(){

    var idRol = $("#id_rol").val();
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
            
            $("#reviso").prop("selectedIndex", 1);
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
            // Verificar si alguna de las variables es falsa o indefinida, y establecerlas como cero en ese caso
            var deficiencia = total_deficiencia ? parseFloat(total_deficiencia.replace(',', '.')) : 0;
            var rolOcupacional = total_rol_ocupacional ? parseFloat(total_rol_ocupacional.replace(',', '.')) : 0;
            var discapacidad = total_discapacidad ? parseFloat(total_discapacidad.replace(',', '.')) : 0;
            var minusvalia = total_minusvalia ? parseFloat(total_minusvalia.replace(',', '.')) : 0;

            // Sumar los valores y asignarlos a opt_sumaTotal_pcl
            var opt_sumaTotal_pcl = deficiencia + rolOcupacional + discapacidad + minusvalia;
            var sumaTotal_pcl = opt_sumaTotal_pcl.toFixed(2);
            if (!isNaN(sumaTotal_pcl)){
                $('#porcentaje_pcl').val(sumaTotal_pcl);
            }
            if(sumaTotal_pcl=='isNaN'){
                rango_pcl = '0';
            }else if(sumaTotal_pcl < "14.99"){
                rango_pcl = 'Entre 1 y 14,99%';
            } else if (sumaTotal_pcl >= "14.99" && sumaTotal_pcl < "29.99"){
                rango_pcl = 'Entre 15 y 29,99%';
            } else if (sumaTotal_pcl >= "29.99" && sumaTotal_pcl < "49.99"){
                rango_pcl = 'Entre 30 y 49,99%';
            } else if (sumaTotal_pcl >= "49.99"){
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
            var deficiencia_jrci = total_deficiencia_jrci_emitido ? parseFloat(total_deficiencia_jrci_emitido.replace(',', '.')) : 0;
            var rolOcupacional_jrci = total_rol_ocupacional_jrci_emitido ? parseFloat(total_rol_ocupacional_jrci_emitido.replace(',', '.')) : 0;
            var discapacidad_jrci = total_discapacidad_jrci_emitido ? parseFloat(total_discapacidad_jrci_emitido.replace(',', '.')) : 0;
            var minusvalia_jrci = total_minusvalia_jrci_emitido ? parseFloat(total_minusvalia_jrci_emitido.replace(',', '.')) : 0;
            // Sumar los valores y asignarlos a opt_sumaTotal_pcl
            opt_sumaTotal_pcl_jrci_emitido = deficiencia_jrci + rolOcupacional_jrci + discapacidad_jrci + minusvalia_jrci;
            var sumaTotal_pcl_jrci = opt_sumaTotal_pcl_jrci_emitido.toFixed(2);
            if (!isNaN(sumaTotal_pcl_jrci)){
                $('#porcentaje_pcl_jrci_emitido').val(sumaTotal_pcl_jrci);
            }
            console.log(sumaTotal_pcl_jrci);
            if(sumaTotal_pcl_jrci=='isNaN'){
                rango_pcl_jrci_emitido = '0';
            }else if(sumaTotal_pcl_jrci < "14,99"){
                rango_pcl_jrci_emitido = 'Entre 1 y 14,99%';
            } else if (sumaTotal_pcl_jrci >= "14,99" && sumaTotal_pcl_jrci < "29,99"){
                rango_pcl_jrci_emitido = 'Entre 15 y 29,99%';
            } else if (sumaTotal_pcl_jrci >= "29,99" && sumaTotal_pcl_jrci < "49,99"){
                rango_pcl_jrci_emitido = 'Entre 30 y 49,99%';
            } else if (sumaTotal_pcl_jrci >= "49,99"){
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
            var deficiencia_re_jrci = total_deficiencia_reposicion_jrci ? parseFloat(total_deficiencia_reposicion_jrci.replace(',', '.')) : 0;
            var rolOcupacional_re_jrci = total_rol_reposicion_jrci ? parseFloat(total_rol_reposicion_jrci.replace(',', '.')) : 0;
            var discapacidad_re_jrci = total_discapacidad_reposicion_jrci ? parseFloat(total_discapacidad_reposicion_jrci.replace(',', '.')) : 0;
            var minusvalia_re_jrci = total_minusvalia_reposicion_jrci ? parseFloat(total_minusvalia_reposicion_jrci.replace(',', '.')) : 0;

            // Sumar los valores y asignarlos a opt_sumaTotal_pcl
            opt_sumaTotal_pcl_jrci_reposicion = deficiencia_re_jrci + rolOcupacional_re_jrci + discapacidad_re_jrci + minusvalia_re_jrci;
            var sumaTotal_pcl_jrci_reposicion = opt_sumaTotal_pcl_jrci_reposicion.toFixed(2);
            if (!isNaN(sumaTotal_pcl_jrci_reposicion)){
                $('#porcentaje_pcl_reposicion_jrci').val(sumaTotal_pcl_jrci_reposicion);
            }
            if(sumaTotal_pcl_jrci_reposicion=='isNaN'){
                rango_pcl_reposicion_jrci = '0';
            }else if(sumaTotal_pcl_jrci_reposicion < 15){
                rango_pcl_reposicion_jrci = 'Entre 1 y 14,99%';
            } else if (sumaTotal_pcl_jrci_reposicion >= 15 && sumaTotal_pcl_jrci_reposicion < 30){
                rango_pcl_reposicion_jrci = 'Entre 15 y 29,99%';
            } else if (sumaTotal_pcl_jrci_reposicion >= 30 && sumaTotal_pcl_jrci_reposicion < 50){
                rango_pcl_reposicion_jrci = 'Entre 30 y 49,99%';
            } else if (sumaTotal_pcl_jrci_reposicion >= 50){
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
            var deficiencia_jnci_emi = total_deficiencia_jnci_emitido ? parseFloat(total_deficiencia_jnci_emitido.replace(',', '.')) : 0;
            var rolOcupacional_jnci_emi = total_rol_ocupacional_jnci_emitido ? parseFloat(total_rol_ocupacional_jnci_emitido.replace(',', '.')) : 0;
            var discapacidad_jnci_emi = total_discapacidad_jnci_emitido ? parseFloat(total_discapacidad_jnci_emitido.replace(',', '.')) : 0;
            var minusvalia_jnci_emi = total_minusvalia_jnci_emitido ? parseFloat(total_minusvalia_jnci_emitido.replace(',', '.')) : 0;
            // Sumar los valores y asignarlos a opt_sumaTotal_pcl_jnci_emitido
            opt_sumaTotal_pcl_jnci_emitido = deficiencia_jnci_emi + rolOcupacional_jnci_emi + discapacidad_jnci_emi + minusvalia_jnci_emi;
            var sumaTotal_pcl_jnci_emitido = opt_sumaTotal_pcl_jnci_emitido.toFixed(2);
            if (!isNaN(sumaTotal_pcl_jnci_emitido)){
                $('#porcentaje_pcl_jnci_emitido').val(redondearNumero(sumaTotal_pcl_jnci_emitido) );
            }
            if(sumaTotal_pcl_jnci_emitido=='isNaN'){
                rango_pcl_jnci_emitido = '0';
            }else if(sumaTotal_pcl_jnci_emitido < 15){
                rango_pcl_jnci_emitido = 'Entre 1 y 14,99%';
            } else if (sumaTotal_pcl_jnci_emitido >= 15 && sumaTotal_pcl_jnci_emitido < 30){
                rango_pcl_jnci_emitido = 'Entre 15 y 29,99%';
            } else if (sumaTotal_pcl_jnci_emitido >= 30 && sumaTotal_pcl_jnci_emitido < 50){
                rango_pcl_jnci_emitido = 'Entre 30 y 49,99%';
            } else if (sumaTotal_pcl_jnci_emitido >= 50){
                rango_pcl_jnci_emitido = 'Mayor o igual 50%';
            }else{
                rango_pcl_jnci_emitido = '0';
            }
            $('#rango_pcl_jnci_emitido').val(rango_pcl_jnci_emitido); //Coloca resultado Rango PCL

        }, 500);
    }

    // Insertar textos predeterminados en la sección de correspondencia pero por defecto al momento de entrar al submodulo
    $("#cuerpo_comunicado").summernote({
        height: 'auto',
        toolbar: false
    });

    $('.note-editing-area').css("background", "white");
    $('.note-editor').css("border", "1px solid black");

    var predeterminado_concepto_jrci = $('input[name=decision_dictamen_jrci]').filter(":checked").val();
    if (predeterminado_concepto_jrci == "Desacuerdo") {
        // Aplica para Controversia Pcl (id servicio 13) y Controversia Origen (id servicio 12)

        $("#tipo_descarga").html('');
        $("#tipo_descarga").html('(dentro del word)');
        $("#tipo_descarga_cuerpo").html('');
        $("#tipo_descarga_cuerpo").html('(dentro del word)');

        $("#etiqueta_asunto").html('');
        $("#etiqueta_asunto").html('Fecha de Dictamen JRCI ');

        $("#btn_insertar_fecha_dictamen_jrci_asunto").prop("disabled", false);
        $("#btn_insertar_nro_dictamen_jrci_asunto").prop("disabled", true);

        $("#etiquetas_cuerpo").html('');
        
        $("#btn_insertar_nro_dictamen_jrci").prop("disabled", true);
        $("#btn_insertar_fecha_dictamen_jrci").prop("disabled", true);
        $("#btn_insertar_nombre_afiliado").prop("disabled", true);
        $("#btn_insertar_tipo_documento_afiliado").prop("disabled", true);
        $("#btn_insertar_documento_afiliado").prop("disabled", true);
        $("#btn_insertar_cie_nombre_jrci").prop("disabled", true);
        $("#btn_insertar_pcl_jrci").prop("disabled", true);
        $("#btn_insertar_origen_dx_jrci").prop("disabled", true);
        $("#btn_insertar_f_estructuracion_jrci").prop("disabled", true);
        $("#btn_insertar_decreto_calificador_jrci").prop("disabled", true);

        $("#Asunto").val("RECURSO DE REPOSICIÓN Y EN SUBSIDIO DE APELACIÓN AL DICTAMEN DEL {{$f_dictamen_jrci_asunto}}");
        var texto_insertar = "<p>Respetados señores, cordial saludo:</p><p>HUGO IGNACIO GÓMEZ DAZA, identificado como aparece al pie de mi firma, actuando en nombre y representación de SEGUROS DE VIDA ALFA S.A. en el ramo de Riesgos Laborales, debidamente facultado para ello; en atención al dictamen de la referencia y estando dentro de los términos de ley, me permito interponer RECURSO DE REPOSICIÓN Y EN SUBSIDIO EL DE APELACIÓN ante la Junta Nacional de Calificación de Invalidez, por los siguientes motivos:</p><p>{{$sustentacion_jrci}}</p><p>De acuerdo con lo anteriormente expuesto, solicitamos se modifique la calificación de ORIGEN, de acuerdo con la información aportada y la historia clínica de la paciente.</p><p>Esperamos haber sustentado claramente nuestra inconformidad, por lo que solicitamos se revoque el dictamen y en su lugar se expida el que se adapte a las circunstancias fácticas de la paciente. En caso de que no se revoque, solicitamos se de curso a la apelación ante la Junta Nacional de Calificación e informarnos con el fin de consignar los honorarios respectivos.</p><p>ANEXO:</p><p>Certificado de existencia y representación legal expedido por la Superintendencia Financiera.</p><p>NOTIFICACIONES:</p><p>Cualquier inquietud o consulta al respecto, le invitamos a comunicarse a nuestras líneas de atención al cliente en Bogotá (601) 3 07 70 32 o a la línea naciona gratuita 01 8000 122 532, de lunes a viernes,de 8:00 a. m. a 8:00 p. m. - sábados de 8:00 a.m. a 12 m., o escribanos a «servicioalcliente@segurosalfa.com.co» o a la dirección Carrera 10 # 18-36 piso 4 Edificio Jose maria Cordoba, Bogota D.C.</p>";
        $("#cuerpo_comunicado").summernote('code', texto_insertar);

        $("a[id^='generar_proforma_desacuerdo_']").removeClass('d-none');
        $("a[id^='generar_proforma_acuerdo_']").addClass('d-none');

        // Seteo automático del nro de anexos:
        var seteo_nro_anexos = 0;
        $("#anexos").val(seteo_nro_anexos);

        // Selección automática de las copias a partes interesadas: Eps
        $("#afiliado").prop('checked', true);

        // Selección automática del checkbox firmar
        $("#firmar").prop('checked', false);
       
    } 
    else if(predeterminado_concepto_jrci == "Acuerdo"){
        // Aplica para Controversia Pcl (id servicio 13) y Controversia Origen (id servicio 12)
        $("#tipo_descarga").html('');
        $("#tipo_descarga").html('(dentro del pdf)');
        $("#tipo_descarga_cuerpo").html('');
        $("#tipo_descarga_cuerpo").html('(dentro del pdf)');
        
        $("#etiqueta_asunto").html('');
        $("#etiqueta_asunto").html('Número de Dictamen JRCI ');

        $("#btn_insertar_nro_dictamen_jrci_asunto").prop("disabled", false);
        $("#btn_insertar_fecha_dictamen_jrci_asunto").prop("disabled", true);

        $("#etiquetas_cuerpo").html('');
        $("#etiquetas_cuerpo").html('Número de Dictamen JRCI, Fecha de Dictamen JRCI, Nombre Afiliado, Tipo Documento Afiliado, Número Documento Afiliado, CIE-10 - Nombre CIE-10 JRCI, %Pcl JRCI, Origen Dx JRCI, Fecha Estructuracion JRCI, Decreto Calificador JRCI, ');

        $("#btn_insertar_nro_dictamen_jrci").prop("disabled", false);
        $("#btn_insertar_fecha_dictamen_jrci").prop("disabled", false);
        $("#btn_insertar_nombre_afiliado").prop("disabled", false);
        $("#btn_insertar_tipo_documento_afiliado").prop("disabled", false);
        $("#btn_insertar_documento_afiliado").prop("disabled", false);
        $("#btn_insertar_cie_nombre_jrci").prop("disabled", false);
        $("#btn_insertar_pcl_jrci").prop("disabled", false);
        $("#btn_insertar_origen_dx_jrci").prop("disabled", false);
        $("#btn_insertar_f_estructuracion_jrci").prop("disabled", false);
        $("#btn_insertar_decreto_calificador_jrci").prop("disabled", false);

        $("#Asunto").val("PRONUNCIAMIENTO FRENTE A DICTAMEN {{$nro_dictamen_asunto}}");
        var texto_insertar = "<p>Respetados señores</p><p>Con atento saludo les informamos que fuimos notificados del dictamen número {{$nro_dictamen}} de fecha {{$f_dictamen_jrci}}, correspondiente al afiliado {{$nombre_afiliado}}, quien se identifica con la {{$tipo_identificacion_afiliado}} número {{$num_identificacion_afiliado}}, mediante el cual califican las patologías: {{$cie10_nombre_cie10_jrci}}, determinando un porcentaje de Pérdida De Capacidad Laboral de {{$pcl_jrci}}, de origen {{$origen_dx_jrci}} y Fecha de estructuración {{$f_estructuracion_jrci}}.</p><p>Una vez estudiado el dictamen por parte del equipo interdisciplinario de medicina laboral, esta aseguradora se manifiesta en ACUERDO respecto al Porcentaje de PCL, determinado(s) en el dictamen de calificación, toda vez que los elementos determinados se ajustan al Decreto {{$decreto_calificador_jrci}} (Manual Único de Calificación de Invalidez).</p><p>Dicho acuerdo se fundamenta en: Una vez revisado el dictamen proferido por la junta, esta aseguradora se manifiesta en ACUERDO con la calificación asignada, {{$sustentacion_jrci}}.</p><p>En caso de que las demás partes interesadas no interpongan el recurso de reposición en subsidio de apelación, amablemente solicitamos nos sea remitida la CONSTANCIA EJECUTORIA del dictamen emitido por su entidad.</p><p>Cualquier inquietud o consulta al respecto, le invitamos a comunicarse a nuestras líneas de atención al cliente en Bogotá (601) 3 07 70 32 o a la línea naciona gratuita 01 8000 122 532, de lunes a viernes,de 8:00 a. m. a 8:00 p. m. - sábados de 8:00 a.m. a 12 m., o escribanos a «servicioalcliente@segurosalfa.com.co» o a la dirección Carrera 10 # 18-36 piso 4 Edificio Jose maria Cordoba, Bogota D.C.</p>";
        $("#cuerpo_comunicado").summernote('code', texto_insertar);

        $("a[id^='generar_proforma_acuerdo_']").removeClass('d-none');
        $("a[id^='generar_proforma_desacuerdo_']").addClass('d-none');

        // Seteo automático del nro de anexos:
        var seteo_nro_anexos = 0;
        $("#anexos").val(seteo_nro_anexos);

        // Deselección automática de las copias a partes interesadas: Eps
        $("#afiliado").prop('checked', false);

        // Selección automática del checkbox firmar
        $("#firmar").prop('checked', true);

    }
    else{
        $("#Asunto").val("");
        $("#cuerpo_comunicado").summernote('code', '');

        // Seteo automático del nro de anexos:
        var seteo_nro_anexos = 0;
        $("#anexos").val(seteo_nro_anexos);

        // Deselección automática de las copias a partes interesadas: Eps
        $("#afiliado").prop('checked', false);

        // Selección automática del checkbox firmar
        $("#firmar").prop('checked', false);
    }
    

    // Mostrar Modulo y Selector de acuerdo al pronunciamiento DICTAMEN
    var opt_concepto_jrci;
    $("[name='decision_dictamen_jrci']").on("change", function(){
        opt_concepto_jrci = $(this).val();
        $(this).val(opt_concepto_jrci);
        iniciarIntervalo_concepto_jrci();

        // Insertar textos predeterminados en la sección de correspondencia
        if (opt_concepto_jrci == "Desacuerdo") {
            // Aplica para Controversia Pcl (id servicio 13) y Controversia Origen (id servicio 12)
            $("#tipo_descarga").html('');
            $("#tipo_descarga").html('(dentro del word)');
            $("#tipo_descarga_cuerpo").html('');
            $("#tipo_descarga_cuerpo").html('(dentro del word)');

            $("#etiqueta_asunto").html('');
            $("#etiqueta_asunto").html('Fecha de Dictamen JRCI ');

            $("#btn_insertar_fecha_dictamen_jrci_asunto").prop("disabled", false);
            $("#btn_insertar_nro_dictamen_jrci_asunto").prop("disabled", true);

            $("#etiquetas_cuerpo").html('');

            $("#btn_insertar_nro_dictamen_jrci").prop("disabled", true);
            $("#btn_insertar_fecha_dictamen_jrci").prop("disabled", true);
            $("#btn_insertar_nombre_afiliado").prop("disabled", true);
            $("#btn_insertar_tipo_documento_afiliado").prop("disabled", true);
            $("#btn_insertar_documento_afiliado").prop("disabled", true);
            $("#btn_insertar_cie_nombre_jrci").prop("disabled", true);
            $("#btn_insertar_pcl_jrci").prop("disabled", true);
            $("#btn_insertar_origen_dx_jrci").prop("disabled", true);
            $("#btn_insertar_f_estructuracion_jrci").prop("disabled", true);
            $("#btn_insertar_decreto_calificador_jrci").prop("disabled", true);

            $("#Asunto").val("RECURSO DE REPOSICIÓN Y EN SUBSIDIO DE APELACIÓN AL DICTAMEN DEL {{$f_dictamen_jrci_asunto}}");
            var texto_insertar = "<p>Respetados señores, cordial saludo:</p><p>HUGO IGNACIO GÓMEZ DAZA, identificado como aparece al pie de mi firma, actuando en nombre y representación de SEGUROS DE VIDA ALFA S.A. en el ramo de Riesgos Laborales, debidamente facultado para ello; en atención al dictamen de la referencia y estando dentro de los términos de ley, me permito interponer RECURSO DE REPOSICIÓN Y EN SUBSIDIO EL DE APELACIÓN ante la Junta Nacional de Calificación de Invalidez, por los siguientes motivos:</p><p>{{$sustentacion_jrci}}</p><p>De acuerdo con lo anteriormente expuesto, solicitamos se modifique la calificación de ORIGEN, de acuerdo con la información aportada y la historia clínica de la paciente.</p><p>Esperamos haber sustentado claramente nuestra inconformidad, por lo que solicitamos se revoque el dictamen y en su lugar se expida el que se adapte a las circunstancias fácticas de la paciente. En caso de que no se revoque, solicitamos se de curso a la apelación ante la Junta Nacional de Calificación e informarnos con el fin de consignar los honorarios respectivos.</p><p>ANEXO:</p><p>Certificado de existencia y representación legal expedido por la Superintendencia Financiera.</p><p>NOTIFICACIONES:</p><p>Cualquier inquietud o consulta al respecto, le invitamos a comunicarse a nuestras líneas de atención al cliente en Bogotá (601) 3 07 70 32 o a la línea naciona gratuita 01 8000 122 532, de lunes a viernes,de 8:00 a. m. a 8:00 p. m. - sábados de 8:00 a.m. a 12 m., o escribanos a «servicioalcliente@segurosalfa.com.co» o a la dirección Carrera 10 # 18-36 piso 4 Edificio Jose maria Cordoba, Bogota D.C.</p>";
            $("#cuerpo_comunicado").summernote('code', texto_insertar);

            $("a[id^='generar_proforma_desacuerdo_']").removeClass('d-none');
            $("a[id^='generar_proforma_acuerdo_']").addClass('d-none');
            
            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos").val(seteo_nro_anexos);

            // Selección automática de las copias a partes interesadas: Eps
            $("#afiliado").prop('checked', true);

            // Selección automática del checkbox firmar
            $("#firmar").prop('checked', false);

        }
        else if(opt_concepto_jrci == "Acuerdo"){
            // Aplica para Controversia Pcl (id servicio 13) y Controversia Origen (id servicio 12)
            $("#tipo_descarga").html('');
            $("#tipo_descarga").html('(dentro del pdf)');
            $("#tipo_descarga_cuerpo").html('');
            $("#tipo_descarga_cuerpo").html('(dentro del pdf)');

            $("#etiqueta_asunto").html('');
            $("#etiqueta_asunto").html('Número de Dictamen JRCI ');

            $("#btn_insertar_nro_dictamen_jrci_asunto").prop("disabled", false);
            $("#btn_insertar_fecha_dictamen_jrci_asunto").prop("disabled", true);

            $("#etiquetas_cuerpo").html('');
            $("#etiquetas_cuerpo").html('Número de Dictamen JRCI, Fecha de Dictamen JRCI, Nombre Afiliado, Tipo Documento Afiliado, Número Documento Afiliado, CIE-10 - Nombre CIE-10 JRCI, %Pcl JRCI, Origen Dx JRCI, Fecha Estructuracion JRCI, Decreto Calificador JRCI, ');

            $("#btn_insertar_nro_dictamen_jrci").prop("disabled", false);
            $("#btn_insertar_fecha_dictamen_jrci").prop("disabled", false);
            $("#btn_insertar_nombre_afiliado").prop("disabled", false);
            $("#btn_insertar_tipo_documento_afiliado").prop("disabled", false);
            $("#btn_insertar_documento_afiliado").prop("disabled", false);
            $("#btn_insertar_cie_nombre_jrci").prop("disabled", false);
            $("#btn_insertar_pcl_jrci").prop("disabled", false);
            $("#btn_insertar_origen_dx_jrci").prop("disabled", false);
            $("#btn_insertar_f_estructuracion_jrci").prop("disabled", false);
            $("#btn_insertar_decreto_calificador_jrci").prop("disabled", false);

            $("#Asunto").val("PRONUNCIAMIENTO FRENTE A DICTAMEN {{$nro_dictamen_asunto}}");
            var texto_insertar = "<p>Respetados señores</p><p>Con atento saludo les informamos que fuimos notificados del dictamen número {{$nro_dictamen}} de fecha {{$f_dictamen_jrci}}, correspondiente al afiliado {{$nombre_afiliado}}, quien se identifica con la {{$tipo_identificacion_afiliado}} número {{$num_identificacion_afiliado}}, mediante el cual califican las patologías: {{$cie10_nombre_cie10_jrci}}, determinando un porcentaje de Pérdida De Capacidad Laboral de {{$pcl_jrci}}, de origen {{$origen_dx_jrci}} y Fecha de estructuración {{$f_estructuracion_jrci}}.</p><p>Una vez estudiado el dictamen por parte del equipo interdisciplinario de medicina laboral, esta aseguradora se manifiesta en ACUERDO respecto al Porcentaje de PCL, determinado(s) en el dictamen de calificación, toda vez que los elementos determinados se ajustan al Decreto {{$decreto_calificador_jrci}} (Manual Único de Calificación de Invalidez).</p><p>Dicho acuerdo se fundamenta en: Una vez revisado el dictamen proferido por la junta, esta aseguradora se manifiesta en ACUERDO con la calificación asignada, {{$sustentacion_jrci}}.</p><p>En caso de que las demás partes interesadas no interpongan el recurso de reposición en subsidio de apelación, amablemente solicitamos nos sea remitida la CONSTANCIA EJECUTORIA del dictamen emitido por su entidad.</p><p>Cualquier inquietud o consulta al respecto, le invitamos a comunicarse a nuestras líneas de atención al cliente en Bogotá (601) 3 07 70 32 o a la línea naciona gratuita 01 8000 122 532, de lunes a viernes,de 8:00 a. m. a 8:00 p. m. - sábados de 8:00 a.m. a 12 m., o escribanos a «servicioalcliente@segurosalfa.com.co» o a la dirección Carrera 10 # 18-36 piso 4 Edificio Jose maria Cordoba, Bogota D.C.</p>";
            $("#cuerpo_comunicado").summernote('code', texto_insertar);

            $("a[id^='generar_proforma_acuerdo_']").removeClass('d-none');
            $("a[id^='generar_proforma_desacuerdo_']").addClass('d-none');

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos").val(seteo_nro_anexos);

            // Deselección automática de las copias a partes interesadas: Eps
            $("#afiliado").prop('checked', false);

            // Selección automática del checkbox firmar
            $("#firmar").prop('checked', true);
        }
        else{
            $("#Asunto").val("");
            $("#cuerpo_comunicado").summernote('code', '');

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos").val(seteo_nro_anexos);

           // Deselección automática de las copias a partes interesadas: Eps
           $("#afiliado").prop('checked', false);

            // Selección automática del checkbox firmar
            $("#firmar").prop('checked', false);
        }
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
                 case "Informativo":
                    elementosDeslizar_concepto.forEach(elemento => {
                         $(elemento).slideUp(tiempoDeslizamiento_concepto);
                    }); 
                    elementosDeslizar_concepto2.forEach(elemento => {
                        $(elemento).slideDown(tiempoDeslizamiento_concepto);
                    }); 
                    elementosDeslizar_concepto3.forEach(elemento => {
                        $(elemento).slideUp(tiempoDeslizamiento_concepto);
                    });
                    $("#causal_decision").empty();
                    $('#causal_decision').prop('required', false);
                    $('#sustentacion_concepto_jrci').prop('required', true);
                 break;
                 case "Silencio":
                    elementosDeslizar_concepto.forEach(elemento => {
                         $(elemento).slideUp(tiempoDeslizamiento_concepto);
                    }); 
                    elementosDeslizar_concepto2.forEach(elemento => {
                        $(elemento).slideDown(tiempoDeslizamiento_concepto);
                    }); 
                    elementosDeslizar_concepto3.forEach(elemento => {
                        $(elemento).slideUp(tiempoDeslizamiento_concepto);
                    }); 
                    $("#causal_decision").empty();
                    $('#causal_decision').prop('required', false);
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
            $("#div_rev_recur_repo").removeClass('d-none');
            //$('#parte_contro_ante_jrci').prop('required', true);
        }else{
            $("#row_repo_dictamen").addClass('d-none');
            $("#div_rev_recur_repo").addClass('d-none');
            //$('#parte_contro_ante_jrci').prop('required', false);
            //$("#f_transferencia_enfermedad").val("");
        }
    });

    //Mantener Habilitado Revisión ante recurso de reposición de la Junta Regional despues de marcar Reposición del Dictamen por parte de la JRCI
    
    if ($('#firmeza_reposicion_jrci').prop('checked')) {
        $("#div_rev_recur_repo").removeClass('d-none');        
    }

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

        // Insertar textos predeterminados en la sección de correspondencia
        if (opt_concepto_repo_jrci == "Desacuerdo") {
            // Aplica para Controversia Pcl (id servicio 13) y Controversia Origen (id servicio 12)
            $("#tipo_descarga").html('');
            $("#tipo_descarga").html('(dentro del word)');
            $("#tipo_descarga_cuerpo").html('');
            $("#tipo_descarga_cuerpo").html('(dentro del word)');

            $("#etiqueta_asunto").html('');
            $("#etiqueta_asunto").html('Fecha de Dictamen JRCI ');

            $("#btn_insertar_fecha_dictamen_jrci_asunto").prop("disabled", false);
            $("#btn_insertar_nro_dictamen_jrci_asunto").prop("disabled", true);

            $("#etiquetas_cuerpo").html('');

            $("#btn_insertar_nro_dictamen_jrci").prop("disabled", true);
            $("#btn_insertar_fecha_dictamen_jrci").prop("disabled", true);
            $("#btn_insertar_nombre_afiliado").prop("disabled", true);
            $("#btn_insertar_tipo_documento_afiliado").prop("disabled", true);
            $("#btn_insertar_documento_afiliado").prop("disabled", true);
            $("#btn_insertar_cie_nombre_jrci").prop("disabled", true);
            $("#btn_insertar_pcl_jrci").prop("disabled", true);
            $("#btn_insertar_origen_dx_jrci").prop("disabled", true);
            $("#btn_insertar_f_estructuracion_jrci").prop("disabled", true);
            $("#btn_insertar_decreto_calificador_jrci").prop("disabled", true);

            $("#Asunto").val("RECURSO DE REPOSICIÓN Y EN SUBSIDIO DE APELACIÓN AL DICTAMEN DEL {{$f_dictamen_jrci_asunto}}");
            var texto_insertar = "<p>Respetados señores, cordial saludo:</p><p>HUGO IGNACIO GÓMEZ DAZA, identificado como aparece al pie de mi firma, actuando en nombre y representación de SEGUROS DE VIDA ALFA S.A. en el ramo de Riesgos Laborales, debidamente facultado para ello; en atención al dictamen de la referencia y estando dentro de los términos de ley, me permito interponer RECURSO DE REPOSICIÓN Y EN SUBSIDIO EL DE APELACIÓN ante la Junta Nacional de Calificación de Invalidez, por los siguientes motivos:</p><p>{{$sustentacion_jrci}}</p><p>De acuerdo con lo anteriormente expuesto, solicitamos se modifique la calificación de ORIGEN, de acuerdo con la información aportada y la historia clínica de la paciente.</p><p>Esperamos haber sustentado claramente nuestra inconformidad, por lo que solicitamos se revoque el dictamen y en su lugar se expida el que se adapte a las circunstancias fácticas de la paciente. En caso de que no se revoque, solicitamos se de curso a la apelación ante la Junta Nacional de Calificación e informarnos con el fin de consignar los honorarios respectivos.</p><p>ANEXO:</p><p>Certificado de existencia y representación legal expedido por la Superintendencia Financiera.</p><p>NOTIFICACIONES:</p><p>Cualquier inquietud o consulta al respecto, le invitamos a comunicarse a nuestras líneas de atención al cliente en Bogotá (601) 3 07 70 32 o a la línea naciona gratuita 01 8000 122 532, de lunes a viernes,de 8:00 a. m. a 8:00 p. m. - sábados de 8:00 a.m. a 12 m., o escribanos a «servicioalcliente@segurosalfa.com.co» o a la dirección Carrera 10 # 18-36 piso 4 Edificio Jose maria Cordoba, Bogota D.C.</p>";
            $("#cuerpo_comunicado").summernote('code', texto_insertar);

            $("a[id^='generar_proforma_desacuerdo_']").removeClass('d-none');
            $("a[id^='generar_proforma_acuerdo_']").addClass('d-none');
            
            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos").val(seteo_nro_anexos);

            // Selección automática de las copias a partes interesadas: Eps
            $("#afiliado").prop('checked', true);

            // Selección automática del checkbox firmar
            $("#firmar").prop('checked', false);

        }
        else if(opt_concepto_repo_jrci == "Acuerdo"){
            // Aplica para Controversia Pcl (id servicio 13) y Controversia Origen (id servicio 12)
            $("#tipo_descarga").html('');
            $("#tipo_descarga").html('(dentro del pdf)');
            $("#tipo_descarga_cuerpo").html('');
            $("#tipo_descarga_cuerpo").html('(dentro del pdf)');

            $("#etiqueta_asunto").html('');
            $("#etiqueta_asunto").html('Número de Dictamen JRCI ');

            $("#btn_insertar_nro_dictamen_jrci_asunto").prop("disabled", false);
            $("#btn_insertar_fecha_dictamen_jrci_asunto").prop("disabled", true);

            $("#etiquetas_cuerpo").html('');
            $("#etiquetas_cuerpo").html('Número de Dictamen JRCI, Fecha de Dictamen JRCI, Nombre Afiliado, Tipo Documento Afiliado, Número Documento Afiliado, CIE-10 - Nombre CIE-10 JRCI, %Pcl JRCI, Origen Dx JRCI, Fecha Estructuracion JRCI, Decreto Calificador JRCI, ');

            $("#btn_insertar_nro_dictamen_jrci").prop("disabled", false);
            $("#btn_insertar_fecha_dictamen_jrci").prop("disabled", false);
            $("#btn_insertar_nombre_afiliado").prop("disabled", false);
            $("#btn_insertar_tipo_documento_afiliado").prop("disabled", false);
            $("#btn_insertar_documento_afiliado").prop("disabled", false);
            $("#btn_insertar_cie_nombre_jrci").prop("disabled", false);
            $("#btn_insertar_pcl_jrci").prop("disabled", false);
            $("#btn_insertar_origen_dx_jrci").prop("disabled", false);
            $("#btn_insertar_f_estructuracion_jrci").prop("disabled", false);
            $("#btn_insertar_decreto_calificador_jrci").prop("disabled", false);

            $("#Asunto").val("PRONUNCIAMIENTO FRENTE A DICTAMEN {{$nro_dictamen_asunto}}");
            var texto_insertar = "<p>Respetados señores</p><p>Con atento saludo les informamos que fuimos notificados del dictamen número {{$nro_dictamen}} de fecha {{$f_dictamen_jrci}}, correspondiente al afiliado {{$nombre_afiliado}}, quien se identifica con la {{$tipo_identificacion_afiliado}} número {{$num_identificacion_afiliado}}, mediante el cual califican las patologías: {{$cie10_nombre_cie10_jrci}}, determinando un porcentaje de Pérdida De Capacidad Laboral de {{$pcl_jrci}}, de origen {{$origen_dx_jrci}} y Fecha de estructuración {{$f_estructuracion_jrci}}.</p><p>Una vez estudiado el dictamen por parte del equipo interdisciplinario de medicina laboral, esta aseguradora se manifiesta en ACUERDO respecto al Porcentaje de PCL, determinado(s) en el dictamen de calificación, toda vez que los elementos determinados se ajustan al Decreto {{$decreto_calificador_jrci}} (Manual Único de Calificación de Invalidez).</p><p>Dicho acuerdo se fundamenta en: Una vez revisado el dictamen proferido por la junta, esta aseguradora se manifiesta en ACUERDO con la calificación asignada, {{$sustentacion_jrci}}.</p><p>En caso de que las demás partes interesadas no interpongan el recurso de reposición en subsidio de apelación, amablemente solicitamos nos sea remitida la CONSTANCIA EJECUTORIA del dictamen emitido por su entidad.</p><p>Cualquier inquietud o consulta al respecto, le invitamos a comunicarse a nuestras líneas de atención al cliente en Bogotá (601) 3 07 70 32 o a la línea naciona gratuita 01 8000 122 532, de lunes a viernes,de 8:00 a. m. a 8:00 p. m. - sábados de 8:00 a.m. a 12 m., o escribanos a «servicioalcliente@segurosalfa.com.co» o a la dirección Carrera 10 # 18-36 piso 4 Edificio Jose maria Cordoba, Bogota D.C.</p>";
            $("#cuerpo_comunicado").summernote('code', texto_insertar);

            $("a[id^='generar_proforma_acuerdo_']").removeClass('d-none');
            $("a[id^='generar_proforma_desacuerdo_']").addClass('d-none');

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos").val(seteo_nro_anexos);

            // Deselección automática de las copias a partes interesadas: Eps
            $("#afiliado").prop('checked', false);

            // Selección automática del checkbox firmar
            $("#firmar").prop('checked', true);
        }
        else{
            $("#Asunto").val("");
            $("#cuerpo_comunicado").summernote('code', '');

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos").val(seteo_nro_anexos);

            // Deselección automática de las copias a partes interesadas: Eps
            $("#afiliado").prop('checked', false);

            // Selección automática del checkbox firmar
            $("#firmar").prop('checked', false);
        }
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
                 case "Informativo":
                    elementosDeslizar_repo_concepto.forEach(elemento => {
                         $(elemento).slideUp(tiempoDeslizamiento_concepto_repo);
                    }); 
                    elementosDeslizar_repo_concepto2.forEach(elemento => {
                        $(elemento).slideDown(tiempoDeslizamiento_concepto_repo);
                    }); 
                    $("#causal_decision_repo").empty();
                    $('#causal_decision_repo').prop('required', false);
                    $('#sustentacion_concepto_repo_jrci').prop('required', true);
                 break;
                 case "Silencio":
                    elementosDeslizar_repo_concepto.forEach(elemento => {
                         $(elemento).slideUp(tiempoDeslizamiento_concepto_repo);
                    }); 
                    elementosDeslizar_repo_concepto2.forEach(elemento => {
                        $(elemento).slideDown(tiempoDeslizamiento_concepto_repo);
                    }); 
                    $("#causal_decision_repo").empty();
                    $('#causal_decision_repo').prop('required', false);
                    $('#sustentacion_concepto_repo_jrci').prop('required', true);
                 break;
                 default:
                    // Deslizar hacia arriba (ocultar) los elementos
                    elementosDeslizar_repo_concepto.forEach(elemento => {
                         $(elemento).slideUp(tiempoDeslizamiento_concepto_repo);
                    });
                    $("#causal_decision_repo").empty();
                    $('#causal_decision_repo').prop('required', false);
                    $('#sustentacion_concepto_repo_jrci').prop('required', true);
                   
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

    // Dehabilitar los requiered si el checked de pcl no esta esta checked
    var checkboxcontro_pcl = $('#contro_pcl');

    if (!checkboxcontro_pcl.prop('checked')) {
        $('#manual_de_califi').prop('required', false);
        $('#total_deficiencia').prop('required', false);
        $('#f_estructuracion_contro').prop('required', false);        
    }   

    //Mostrar o Ocultar la seccion de Firmeza o controversia por otra parte interesada del Dictamen Junta Regional de Calificación de Invalidez (JRCI) 
    // si los Pronunciamiento ante Dictamen de JRCI estan checked

    // var radioacuerdo_revision_jrci = $('#acuerdo_revision_jrci');
    // var radiodesacuerdo_revision_jrci = $('#desacuerdo_revision_jrci');
    // var radiosilecion_revision_jrci = $('#silecion_revision_jrci');
    // var radioinformativo_revision_jrci = $('#informativo_revision_jrci');


    // radioacuerdo_revision_jrci.change(function () {
    //     $('#div_Firmeza_controversiaJRCI').removeClass('d-none');
    // })

    // radiodesacuerdo_revision_jrci.change(function () {
    //     $('#div_Firmeza_controversiaJRCI').removeClass('d-none');
    // })

    // radiosilecion_revision_jrci.change(function () {
    //     $('#div_Firmeza_controversiaJRCI').removeClass('d-none');
    // })

    // radioinformativo_revision_jrci.change(function () {
    //     $('#div_Firmeza_controversiaJRCI').removeClass('d-none');
    // })


    // if (radioacuerdo_revision_jrci.prop('checked')) {
    //     $('#div_Firmeza_controversiaJRCI').removeClass('d-none');        
    // }

    // if (radiodesacuerdo_revision_jrci.prop('checked')) {
    //     $('#div_Firmeza_controversiaJRCI').removeClass('d-none');        
    // }

    // if (radiosilecion_revision_jrci.prop('checked')) {
    //     $('#div_Firmeza_controversiaJRCI').removeClass('d-none');        
    // }

    // if (radioinformativo_revision_jrci.prop('checked')) {
    //     $('#div_Firmeza_controversiaJRCI').removeClass('d-none');        
    // }

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
            'n_siniestro': $('#n_siniestro').val(),
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
            'f_envio_jrci': $('#f_envio_jrci').val(),
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
            'f_envio_jnci': $('#f_envio_jnci').val(),
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
    
    /* VALIDACIONES CORRESPONDENCIA */

    /* Checkbox Otro Destinatario Principal */
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
            
            // Comparacion del id del tipo de destinatario para mostrar los campos del formulario
            var destinatario_principal_select = $('#db_tipo_destinatario_principal').val();
            // opcion 8: otro
            if (destinatario_principal_select == 8) {
                $('#div_tipo_destinatario_principal').slideDown('slow');            
                $('#div_datos_otro_destinatario').slideDown('slow');
                $('#div_nombre_destinatariopri').slideUp('up');                
            }
            // opcion 4: afiliado
            else if(destinatario_principal_select == 4) {
                $('#div_tipo_destinatario_principal').slideDown('slow');            
                $('#div_datos_otro_destinatario').slideUp('up');
                $('#div_nombre_destinatariopri').slideUp('up');
                $('#div_nombre_destinatariopri_afi_').slideDown('slow');
            }
            // opcion 5: empleador
            else if(destinatario_principal_select == 5) {
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

    // Validaciones para el selector de tipo de destinatario principal
    var select_destinatario_principal = $('#tipo_destinatario_principal');
    var select_destinatario_principal2 = $('#db_tipo_destinatario_principal').val();
    
    select_destinatario_principal.change(function() {
        // opcion 8: otro
       if ($(this).val() == 8) {
            $('#nombre_destinatariopri').prop('required', false); 
            $('#div_nombre_destinatariopri').slideUp('up');
            $('#div_datos_otro_destinatario').slideDown('slow');
            $('#div_nombre_destinatariopri_afi_').slideUp('up');
            $('#div_nombre_destinatariopri_empl').slideUp('up');
       }
        //  opción 4: afiliado
       else if ($(this).val() == 4) {
            $('#nombre_destinatariopri').prop('required', false); 
            $('#div_nombre_destinatariopri').slideUp('up');
            $('#div_datos_otro_destinatario').slideUp('up');
            $('#div_nombre_destinatariopri_afi_').slideDown('slow');
            $('#div_nombre_destinatariopri_empl').slideUp('up');
        }
        // opcion 5: empleador
        else if ($(this).val() == 5) {
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

    // Validación del Checkbox Otro Destinatario Principal pero desde cuaando carga la pagina por primera vez
    if(otrodestinariop.prop('checked')){           
        $('#div_tipo_destinatario_principal').slideDown('slow');
        // opcion 8: otro
        if (select_destinatario_principal2 == 8) {
            $('#div_datos_otro_destinatario').slideDown('slow');
            $('#div_nombre_destinatariopri').slideUp('up');
        }
        // opcion 4: afiliado
        else if(select_destinatario_principal2 == 4) {
            $('#div_datos_otro_destinatario').slideUp('up');
            $('#div_nombre_destinatariopri').slideUp('up');
            $('#div_nombre_destinatariopri_afi_').slideDown('slow');
        }
        // opcion 5: empleador
        else if(select_destinatario_principal2 == 5) {
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

    /* funcionalidad etiquetas para el tema de la proforma decisión: ACUERDO */
    $("#btn_insertar_nro_dictamen_jrci_asunto").click(function(e){
        e.preventDefault();
        var cursorPos = $("#Asunto").prop('selectionStart');
        var currentValue = $("#Asunto").val();
        var newValue = currentValue.slice(0, cursorPos) + '{{$nro_dictamen_asunto}}' + currentValue.slice(cursorPos);
        // Actualiza el valor del input
        $("#Asunto").val(newValue);
        // Coloca el cursor después de la etiqueta
        $("#Asunto").prop('selectionStart', cursorPos + 25);
        $("#Asunto").prop('selectionEnd', cursorPos + 25);
        $("#Asunto").focus();
    });

    $("#btn_insertar_nro_dictamen_jrci").click(function(e){
        e.preventDefault();
        var etiqueta_nro_dictamen = "{{$nro_dictamen}}";
        $("#cuerpo_comunicado").summernote('editor.insertText', etiqueta_nro_dictamen);
    });

    $("#btn_insertar_fecha_dictamen_jrci").click(function(e){
        e.preventDefault();
        var etiqueta_f_dictamen = "{{$f_dictamen_jrci}}";
        $("#cuerpo_comunicado").summernote('editor.insertText', etiqueta_f_dictamen);
    });

    $("#btn_insertar_fecha_dictamen_jrci_asunto").click(function(e){
        e.preventDefault();
        var cursorPos = $("#Asunto").prop('selectionStart');
        var currentValue = $("#Asunto").val();
        var newValue = currentValue.slice(0, cursorPos) + '{{$f_dictamen_jrci_asunto}}' + currentValue.slice(cursorPos);
        // Actualiza el valor del input
        $("#Asunto").val(newValue);
        // Coloca el cursor después de la etiqueta
        $("#Asunto").prop('selectionStart', cursorPos + 28);
        $("#Asunto").prop('selectionEnd', cursorPos + 28);
        $("#Asunto").focus();
    });

    $("#btn_insertar_nombre_afiliado").click(function(e){
        e.preventDefault();
        var etiqueta_nombre_afiliado = "{{$nombre_afiliado}}";
        $("#cuerpo_comunicado").summernote('editor.insertText', etiqueta_nombre_afiliado);
    });

    $("#btn_insertar_tipo_documento_afiliado").click(function(e){
        e.preventDefault();
        var etiqueta_tipo_doc_afiliado = "{{$tipo_identificacion_afiliado}}";
        $("#cuerpo_comunicado").summernote('editor.insertText', etiqueta_tipo_doc_afiliado);
    });

    $("#btn_insertar_documento_afiliado").click(function(e){
        e.preventDefault();
        var etiqueta_doc_afiliado = "{{$num_identificacion_afiliado}}";
        $("#cuerpo_comunicado").summernote('editor.insertText', etiqueta_doc_afiliado);
    });

    $("#btn_insertar_cie_nombre_jrci").click(function(e){
        e.preventDefault();
        var etiqueta_cie_nombre_jrci = "{{$cie10_nombre_cie10_jrci}}";
        $("#cuerpo_comunicado").summernote('editor.insertText', etiqueta_cie_nombre_jrci);
    });

    $("#btn_insertar_pcl_jrci").click(function(e){
        e.preventDefault();
        var etiqueta_pcl_jrci = "{{$pcl_jrci}}";
        $("#cuerpo_comunicado").summernote('editor.insertText', etiqueta_pcl_jrci);
    });

    $("#btn_insertar_origen_dx_jrci").click(function(e){
        e.preventDefault();
        var etiqueta_origen_dx_jrci = "{{$origen_dx_jrci}}";
        $("#cuerpo_comunicado").summernote('editor.insertText', etiqueta_origen_dx_jrci);
    });

    $("#btn_insertar_f_estructuracion_jrci").click(function(e){
        e.preventDefault();
        var etiqueta_f_estructuracion_jrci = "{{$f_estructuracion_jrci}}";
        $("#cuerpo_comunicado").summernote('editor.insertText', etiqueta_f_estructuracion_jrci);
    });

    $("#btn_insertar_decreto_calificador_jrci").click(function(e){
        e.preventDefault();
        var etiqueta_decreto_calificador_jrci = "{{$decreto_calificador_jrci}}";
        $("#cuerpo_comunicado").summernote('editor.insertText', etiqueta_decreto_calificador_jrci);
    });

    // Sustentación Concepto JRCI (Revisión ante concepto de la Junta Regional)
    $("#btn_insertar_sustentacion_jrci").click(function(e){
        e.preventDefault();
        var etiqueta_sustentacion_jrci = "{{$sustentacion_jrci}}";
        $("#cuerpo_comunicado").summernote('editor.insertText', etiqueta_sustentacion_jrci);
    });

    // Sustentación Concepto JRCI (Revisión ante recurso de reposición de la Junta Regional)
    $("#btn_insertar_sustentacion_jrci1").click(function(e){
        e.preventDefault();
        var etiqueta_sustentacion_jrci = "{{$sustentacion_jrci1}}";
        $("#cuerpo_comunicado").summernote('editor.insertText', etiqueta_sustentacion_jrci);
    });

    var tabla_comunicados_juntas = $('#tabla_comunicados_juntas').DataTable({
        "responsive": true,
        "info": false,
        "searching": false,
        "ordering": false,
        "scrollCollapse": true,
        "scrollY": "20vh",
        "paging": false,
        "language":{
            "emptyTable": "No se encontró información"
        }
    });
    autoAdjustColumns(tabla_comunicados_juntas);

    // Funcionalidad botón editar correspondencia (lapiz)
    if($("#hay_datos_form_corres").val() == 0){
        $("#div_correspondencia").removeClass('d-none');
    }

    // Evento click: para mostrar el formulario de la correspondencia.
    $("a[id^='editar_correspondencia_']").click(function(){
        $("#div_correspondencia").addClass('d-none');
        
        setTimeout(() => {
            $("#div_correspondencia").removeClass('d-none');
        }, 2000);
    });

    // Evento hover: Para realizar el cargue de los datos segun corresponda
    $("a[id^='editar_correspondencia_']").hover(function(){
        var id_comite_inter = $(this).data("id_comite_inter");
        // var tupla_comunicado = $(this).data("tupla_comunicado");
        var id_evento = $(this).data("id_evento");
        var id_asignacion = $(this).data("id_asignacion");

        let token = $("input[name='_token']").val();

        /* cargue de datos controvertido */
        let datos_controvertido = {
            '_token': token,
            // 'tupla_comunicado' :tupla_comunicado,
            'id_evento' :id_evento,
            'id_asignacion' :id_asignacion,
            'parametro': "controvertido"
        };
        $.ajax({
            type:'POST',
            url:'/CargueInformacionCorrespondencia',
            data: datos_controvertido,
            success:function(data){
                $("#destinatario_principal").val('');
                $("#destinatario_principal").val(data["destinatario_principal"]);
            }
        });

        /* cargue Datos correspondencia */
        let datos_correspondencia = {
            '_token': token,
            // 'tupla_comunicado': tupla_comunicado,
            'id_evento': id_evento,
            'id_asignacion': id_asignacion,
            'id_comite_inter': id_comite_inter,
            'parametro': "correspondencia"
        };
        $.ajax({
            type:'POST',
            url:'/CargueInformacionCorrespondencia',
            data: datos_correspondencia,
            success:function(data_correspondencia){

                // Validación del Checkbox Otro Destinatario Principal para mostrar u ocultar los campos relacionados a el
                if(data_correspondencia["checkeado_otro_destinatario"] == "Si"){
                    $("#otrodestinariop").prop("checked", true);
                    $('#div_tipo_destinatario_principal').slideDown('slow');

                    $("#tipo_destinatario_principal").prop('required', true);
                    
                    // opcion 4: afiliado
                    if(data_correspondencia["db_tipo_destinatario_principal"] == 4) {
                        $('#div_datos_otro_destinatario').slideUp('up');
                        $('#div_nombre_destinatariopri').slideUp('up');
                        $('#div_nombre_destinatariopri_afi_').slideDown('slow');
                        $('#div_nombre_destinatariopri_empl').slideUp('up');
                        $("#nombre_destinatario_afi").prop('required', true);
                        $("#nombre_destinatario_emp").prop('required', false);
                        $("#nombre_destinatariopri").prop('required', false);
                    }
                    // opcion 5: empleador
                    else if(data_correspondencia["db_tipo_destinatario_principal"] == 5) {
                        $('#div_datos_otro_destinatario').slideUp('up');
                        $('#div_nombre_destinatariopri').slideUp('up');
                        $('#div_nombre_destinatariopri_empl').slideDown('slow');
                        $('#div_nombre_destinatariopri_afi_').slideUp('up');
                        $("#nombre_destinatario_emp").prop('required', true);
                        $("#nombre_destinatario_afi").prop('required', false);
                        $("#nombre_destinatariopri").prop('required', false);
                    }
                    // opcion 8: otro
                    else if (data_correspondencia["db_tipo_destinatario_principal"] == 8) {
                        $('#div_datos_otro_destinatario').slideDown('slow');
                        $('#div_nombre_destinatariopri').slideUp('up');
                        $('#div_nombre_destinatariopri_afi_').slideUp('up');
                        $('#div_nombre_destinatariopri_empl').slideUp('slow');
                        $("#nombre_destinatario_afi").prop('required', false);
                        $("#nombre_destinatario_emp").prop('required', false);
                        $("#nombre_destinatariopri").prop('required', false);
                    }

                    else {  
                        $('#div_nombre_destinatariopri').slideDown('slow');
                        $('#div_datos_otro_destinatario').slideUp('up');
                        $("#nombre_destinatariopri").prop('required', true);
                    } 
                }else{
                    $("#otrodestinariop").prop("checked", false);
                    $('#div_tipo_destinatario_principal').slideUp('slow');
                    $('#div_nombre_destinatariopri').slideUp('up'); 
                    $('#div_datos_otro_destinatario').slideUp('up');
                    $('#div_nombre_destinatariopri_afi_').slideUp('up');
                    $('#div_nombre_destinatariopri_empl').slideUp('up');

                    $("#tipo_destinatario_principal").prop('required', false);
                    $('#tipo_destinatario_principal').empty();
                    $("#nombre_destinatariopri").prop('required', false);
                    $("#nombre_destinatario_afi").prop('required', false);
                    $("#nombre_destinatario_emp").prop('required', false);
                }

                /* Carga de los datos del selector de tipo de destinatario y el id */
                $("#db_tipo_destinatario_principal").val(data_correspondencia["db_tipo_destinatario_principal"]);
                $('#tipo_destinatario_principal').empty();
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
                        let destini_pricipal = Object.keys(data);
                        for (let i = 0; i < destini_pricipal.length; i++) {
                            if (data[destini_pricipal[i]]['Id_solicitante'] == data_correspondencia["db_tipo_destinatario_principal"]) {  
                                $('#tipo_destinatario_principal').append('<option value="'+data[destini_pricipal[i]]["Id_solicitante"]+'" selected>'+data[destini_pricipal[i]]["Solicitante"]+'</option>');
                            }else{                    
                                $('#tipo_destinatario_principal').append('<option value="'+data[destini_pricipal[i]]["Id_solicitante"]+'">'+data[destini_pricipal[i]]["Solicitante"]+'</option>');
                            }
                        }
                    }
                });

                /* Carga de los datos del selector del Nombre del destinatario */
                $("#db_nombre_destinatariopri").val(data_correspondencia["db_nombre_destinatariopri"]);
                $('#nombre_destinatariopri').empty();
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

                /* Carga Datos de la opcion otro del tipo de destinatario principal */
                $("#nombre_destinatario").empty();
                $("#nitcc_destinatario").empty();
                $("#direccion_destinatario").empty();
                $("#telefono_destinatario").empty();
                $("#email_destinatario").empty();
                $("#departamento_destinatario").empty();
                $("#ciudad_destinatario").empty();

                $("#nombre_destinatario").val(data_correspondencia["nombre_destinatario"]);
                $("#nitcc_destinatario").val(data_correspondencia["nitcc_destinatario"]);
                $("#direccion_destinatario").val(data_correspondencia["direccion_destinatario"]);
                $("#telefono_destinatario").val(data_correspondencia["telefono_destinatario"]);
                $("#email_destinatario").val(data_correspondencia["email_destinatario"]);
                $("#departamento_destinatario").val(data_correspondencia["departamento_destinatario"]);
                $("#ciudad_destinatario").val(data_correspondencia["ciudad_destinatario"]);

                /* Carga del Asunto */
                $("#Asunto").val();
                $("#Asunto").val(data_correspondencia["Asunto"]);

                /* Cuerpo del Comunicado */
                $("#cuerpo_comunicado").summernote('code', '');
                $("#cuerpo_comunicado").summernote('code', data_correspondencia["cuerpo_comunicado"]);

                /* Copias partes interesadas */
                if (data_correspondencia["checkeado_afiliado"] == "Si") {
                    $("#afiliado").prop("checked", true);
                }else{
                    $("#afiliado").prop("checked", false);
                }

                if (data_correspondencia["checkeado_empleador"] == "Si") {
                    $("#empleador").prop("checked", true);
                }else{
                    $("#empleador").prop("checked", false);
                }

                if (data_correspondencia["checkeado_eps"] == "Si") {
                    $("#eps").prop("checked", true);
                }else{
                    $("#eps").prop("checked", false);
                }

                if (data_correspondencia["checkeado_afp"] == "Si") {
                    $("#afp").prop("checked", true);
                }else{
                    $("#afp").prop("checked", false);
                }

                if (data_correspondencia["checkeado_arl"] == "Si") {
                    $("#arl").prop("checked", true);
                }else{
                    $("#arl").prop("checked", false);
                }

                if (data_correspondencia["checkeado_copia_jr"] == "Si") {
                    $("#jrci").prop("checked", true);
                    $('#div_cual').slideDown('slow');
                    $('#cual').prop('required', true);
                    $("#bd_cual_jr").val(data_correspondencia["bd_cual_jr"]);
                }else{
                    $("#jrci").prop("checked", false);
                    $('#div_cual').slideUp('up');  
                    $('#cual').prop('required', false);
                }

                //Listado juntas regional Correspondencia
                $('#cual').empty();
                let datos_lista_regional_junta = {
                    '_token': token,
                    'parametro':"lista_regional_junta"
                };
                $.ajax({
                    type:'POST',
                    url:'/selectoresCalificacionTecnicaPCL',
                    data: datos_lista_regional_junta,
                    success:function(data) {
                        $('#cual').append('<option value="">Seleccione una opción</option>');
                        let primercali = Object.keys(data);
                        for (let i = 0; i < primercali.length; i++) {
                            if (data[primercali[i]]['Ciudad_Junta'] == data_correspondencia["bd_cual_jr"]) {  
                                $('#cual').append('<option value="'+data[primercali[i]]["Ciudad_Junta"]+'" selected>'+data[primercali[i]]["Ciudad_Junta"]+'</option>');
                            }else{
                                $('#cual').append('<option value="'+data[primercali[i]]["Ciudad_Junta"]+'">'+data[primercali[i]]["Ciudad_Junta"]+'</option>');
                            }
                        }
                    }
                });

                if (data_correspondencia["checkeado_copia_jn"] == "Si") {
                    $("#jnci").prop("checked", true);
                }else{
                    $("#jnci").prop("checked", false);
                }

                // anexos
                $("#anexos").empty();
                $("#anexos").val(data_correspondencia["anexos"]);

                // Elaboró
                $("#elaboro").empty();
                $("#elaboro").val(data_correspondencia["elaboro"]);

                // Reviso
                $('#reviso').empty();
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
                        $('#reviso').append('<option value="">Seleccione una opción</option>');
                        let nombreRevisoPcl = Object.keys(data);
                        for (let i = 0; i < nombreRevisoPcl.length; i++) {
                            if (data[nombreRevisoPcl[i]]['name'] == data_correspondencia["bd_reviso"]) {                    
                                $('#reviso').append('<option value="'+data[nombreRevisoPcl[i]]['name']+'" selected>'+data[nombreRevisoPcl[i]]['name']+'</option>');
                            }else{
                                $('#reviso').append('<option value="'+data[nombreRevisoPcl[i]]['name']+'">'+data[nombreRevisoPcl[i]]['name']+'</option>');
                            }
                        }
                    }
                });

                // Checkbox Firmar
                if (data_correspondencia["firmar"] == "Si") {
                    $("#firmar").prop("checked", true);
                }else{
                    $("#firmar").prop("checked", false);
                }

                // Ciudad
                $("#ciudad").empty();
                $("#ciudad").val(data_correspondencia["ciudad"]);

                // Fecha Correspondencia
                $("#f_correspondencia").empty();
                $("#f_correspondencia").val(data_correspondencia["f_correspondencia"])

            }
        });

        /* cargue Datos Controversia Juntas */
        let datos_controversia_juntas = {
            '_token': token,
            // 'tupla_comunicado' :tupla_comunicado,
            'id_evento' :id_evento,
            'id_asignacion' :id_asignacion,
            'parametro': "controversia_juntas"
        };
        $.ajax({
            type:'POST',
            url:'/CargueInformacionCorrespondencia',
            data: datos_controversia_juntas,
            success:function(data_contro_juntas){

                /* Nombre destinatario opcion afiliado */
                $("#nombre_destinatario_afi").val(data_contro_juntas["nombre_destinatario_afi"]);
                /* Nombre destinatario opcion empleador */
                $("#nombre_destinatario_emp").val(data_contro_juntas["nombre_destinatario_emp"]);

                /* id servicio ya sea controversia pcl o controversia origen */
                /* $("#llenar_completo").html('');
                // controversia pcl
                if(data_contro_juntas["id_servicio"] == 13){
                    $("#llenar_completo").html(' Nombre CIE-10 JRCI, %Pcl JRCI, Fecha Estructuración JRCI, ');
                }
                // controversia origen
                else if(data_contro_juntas["id_servicio"] == 12){
                    $("#llenar_completo").html(' Origen Dx JRCI, CIE-10 - Nombre CIE-10 JRCI, ');
                } */

            }
        });

    });

    //Captura Formulario Correspondencia
    $('#form_correspondencia').submit(function (e){
        e.preventDefault();              
        
        var newId_evento = $('#newId_evento').val();
        var Id_proceso = $('#Id_proceso').val();
        var newId_asignacion  = $('#newId_asignacion').val();
        
        if ($('#destinatario_principal').val() == '') {
            var destinatario_principal = $("#nombre_afiliado").val();
        }else{
            var destinatario_principal = $('#destinatario_principal').val();
        }

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
        var afiliado = $('input[name="afiliado"]:checked').val();
        var empleador = $('input[name="empleador"]:checked').val();
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
            'afiliado': afiliado,
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
        });
    });

    /* Generacion de Proforma en PDF para decisión ACUERDO en la sección Revisión ante concepto de la Junta Regional */

    // llenado de datos con el hover para el ojito
    $("a[id^='generar_proforma_acuerdo_']").hover(function(){
        var id_comite_inter = $(this).data("id_comite_inter");
        // var tupla_comunicado = $(this).data("tupla_comunicado");
        var id_evento = $(this).data("id_evento");
        var id_asignacion = $(this).data("id_asignacion");

        let token = $("input[name='_token']").val();

        /* cargue de datos controvertido */
        let datos_controvertido = {
            '_token': token,
            // 'tupla_comunicado' :tupla_comunicado,
            'id_evento' :id_evento,
            'id_asignacion' :id_asignacion,
            'parametro': "controvertido"
        };
        $.ajax({
            type:'POST',
            url:'/CargueInformacionCorrespondencia',
            data: datos_controvertido,
            success:function(data){
                $("#destinatario_principal").val('');
                $("#destinatario_principal").val(data["destinatario_principal"]);
            }
        });

        /* cargue Datos correspondencia */
        let datos_correspondencia = {
            '_token': token,
            // 'tupla_comunicado': tupla_comunicado,
            'id_evento': id_evento,
            'id_asignacion': id_asignacion,
            'id_comite_inter': id_comite_inter,
            'parametro': "correspondencia"
        };
        $.ajax({
            type:'POST',
            url:'/CargueInformacionCorrespondencia',
            data: datos_correspondencia,
            success:function(data_correspondencia){

                // Validación del Checkbox Otro Destinatario Principal para mostrar u ocultar los campos relacionados a el
                if(data_correspondencia["checkeado_otro_destinatario"] == "Si"){
                    $("#otrodestinariop").prop("checked", true);
                    $('#div_tipo_destinatario_principal').slideDown('slow');

                    $("#tipo_destinatario_principal").prop('required', true);
                    
                    // opcion 4: afiliado
                    if(data_correspondencia["db_tipo_destinatario_principal"] == 4) {
                        $('#div_datos_otro_destinatario').slideUp('up');
                        $('#div_nombre_destinatariopri').slideUp('up');
                        $('#div_nombre_destinatariopri_afi_').slideDown('slow');
                        $('#div_nombre_destinatariopri_empl').slideUp('up');
                        $("#nombre_destinatario_afi").prop('required', true);
                        $("#nombre_destinatario_emp").prop('required', false);
                        $("#nombre_destinatariopri").prop('required', false);
                    }
                    // opcion 5: empleador
                    else if(data_correspondencia["db_tipo_destinatario_principal"] == 5) {
                        $('#div_datos_otro_destinatario').slideUp('up');
                        $('#div_nombre_destinatariopri').slideUp('up');
                        $('#div_nombre_destinatariopri_empl').slideDown('slow');
                        $('#div_nombre_destinatariopri_afi_').slideUp('up');
                        $("#nombre_destinatario_emp").prop('required', true);
                        $("#nombre_destinatario_afi").prop('required', false);
                        $("#nombre_destinatariopri").prop('required', false);
                    }
                    // opcion 8: otro
                    else if (data_correspondencia["db_tipo_destinatario_principal"] == 8) {
                        $('#div_datos_otro_destinatario').slideDown('slow');
                        $('#div_nombre_destinatariopri').slideUp('up');
                        $('#div_nombre_destinatariopri_afi_').slideUp('up');
                        $('#div_nombre_destinatariopri_empl').slideUp('slow');
                        $("#nombre_destinatario_afi").prop('required', false);
                        $("#nombre_destinatario_emp").prop('required', false);
                        $("#nombre_destinatariopri").prop('required', false);
                    }

                    else {  
                        $('#div_nombre_destinatariopri').slideDown('slow');
                        $('#div_datos_otro_destinatario').slideUp('up');
                        $("#nombre_destinatariopri").prop('required', true);
                    } 
                }else{
                    $("#otrodestinariop").prop("checked", false);
                    $('#div_tipo_destinatario_principal').slideUp('slow');
                    $('#div_nombre_destinatariopri').slideUp('up'); 
                    $('#div_datos_otro_destinatario').slideUp('up');
                    $('#div_nombre_destinatariopri_afi_').slideUp('up');
                    $('#div_nombre_destinatariopri_empl').slideUp('up');

                    $("#tipo_destinatario_principal").prop('required', false);
                    $('#tipo_destinatario_principal').empty();
                    $("#nombre_destinatariopri").prop('required', false);
                    $("#nombre_destinatario_afi").prop('required', false);
                    $("#nombre_destinatario_emp").prop('required', false);
                }

                /* Carga de los datos del selector de tipo de destinatario y el id */
                $("#db_tipo_destinatario_principal").val(data_correspondencia["db_tipo_destinatario_principal"]);
                $('#tipo_destinatario_principal').empty();
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
                        let destini_pricipal = Object.keys(data);
                        for (let i = 0; i < destini_pricipal.length; i++) {
                            if (data[destini_pricipal[i]]['Id_solicitante'] == data_correspondencia["db_tipo_destinatario_principal"]) {  
                                $('#tipo_destinatario_principal').append('<option value="'+data[destini_pricipal[i]]["Id_solicitante"]+'" selected>'+data[destini_pricipal[i]]["Solicitante"]+'</option>');
                            }else{                    
                                $('#tipo_destinatario_principal').append('<option value="'+data[destini_pricipal[i]]["Id_solicitante"]+'">'+data[destini_pricipal[i]]["Solicitante"]+'</option>');
                            }
                        }
                    }
                });

                /* Carga de los datos del selector del Nombre del destinatario */
                $("#db_nombre_destinatariopri").val(data_correspondencia["db_nombre_destinatariopri"]);
                $('#nombre_destinatariopri').empty();
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

                /* Carga Datos de la opcion otro del tipo de destinatario principal */
                $("#nombre_destinatario").empty();
                $("#nitcc_destinatario").empty();
                $("#direccion_destinatario").empty();
                $("#telefono_destinatario").empty();
                $("#email_destinatario").empty();
                $("#departamento_destinatario").empty();
                $("#ciudad_destinatario").empty();
                
                $("#nombre_destinatario").val(data_correspondencia["nombre_destinatario"]);
                $("#nitcc_destinatario").val(data_correspondencia["nitcc_destinatario"]);
                $("#direccion_destinatario").val(data_correspondencia["direccion_destinatario"]);
                $("#telefono_destinatario").val(data_correspondencia["telefono_destinatario"]);
                $("#email_destinatario").val(data_correspondencia["email_destinatario"]);
                $("#departamento_destinatario").val(data_correspondencia["departamento_destinatario"]);
                $("#ciudad_destinatario").val(data_correspondencia["ciudad_destinatario"]);

                /* Carga del Asunto */
                $("#Asunto").val();
                $("#Asunto").val(data_correspondencia["Asunto"]);

                /* Cuerpo del Comunicado */
                $("#cuerpo_comunicado").summernote('code', '');
                $("#cuerpo_comunicado").summernote('code', data_correspondencia["cuerpo_comunicado"]);

                /* Copias partes interesadas */
                if (data_correspondencia["checkeado_afiliado"] == "Si") {
                    $("#afiliado").prop("checked", true);
                }else{
                    $("#afiliado").prop("checked", false);
                }

                if (data_correspondencia["checkeado_empleador"] == "Si") {
                    $("#empleador").prop("checked", true);
                }else{
                    $("#empleador").prop("checked", false);
                }

                if (data_correspondencia["checkeado_eps"] == "Si") {
                    $("#eps").prop("checked", true);
                }else{
                    $("#eps").prop("checked", false);
                }

                if (data_correspondencia["checkeado_afp"] == "Si") {
                    $("#afp").prop("checked", true);
                }else{
                    $("#afp").prop("checked", false);
                }

                if (data_correspondencia["checkeado_arl"] == "Si") {
                    $("#arl").prop("checked", true);
                }else{
                    $("#arl").prop("checked", false);
                }

                if (data_correspondencia["checkeado_copia_jr"] == "Si") {
                    $("#jrci").prop("checked", true);
                    $('#div_cual').slideDown('slow');
                    $('#cual').prop('required', true);
                    $("#bd_cual_jr").val(data_correspondencia["bd_cual_jr"]);
                }else{
                    $("#jrci").prop("checked", false);
                    $('#div_cual').slideUp('up');  
                    $('#cual').prop('required', false);
                }

                //Listado juntas regional Correspondencia
                $('#cual').empty();
                let datos_lista_regional_junta = {
                    '_token': token,
                    'parametro':"lista_regional_junta"
                };
                $.ajax({
                    type:'POST',
                    url:'/selectoresCalificacionTecnicaPCL',
                    data: datos_lista_regional_junta,
                    success:function(data) {
                        $('#cual').append('<option value="">Seleccione una opción</option>');
                        let primercali = Object.keys(data);
                        for (let i = 0; i < primercali.length; i++) {
                            if (data[primercali[i]]['Ciudad_Junta'] == data_correspondencia["bd_cual_jr"]) {  
                                $('#cual').append('<option value="'+data[primercali[i]]["Ciudad_Junta"]+'" selected>'+data[primercali[i]]["Ciudad_Junta"]+'</option>');
                            }else{
                                $('#cual').append('<option value="'+data[primercali[i]]["Ciudad_Junta"]+'">'+data[primercali[i]]["Ciudad_Junta"]+'</option>');
                            }
                        }
                    }
                });

                if (data_correspondencia["checkeado_copia_jn"] == "Si") {
                    $("#jnci").prop("checked", true);
                }else{
                    $("#jnci").prop("checked", false);
                }

                // anexos
                $("#anexos").empty();
                $("#anexos").val(data_correspondencia["anexos"]);

                // Elaboró
                $("#elaboro").empty();
                $("#elaboro").val(data_correspondencia["elaboro"]);

                // Reviso
                $('#reviso').empty();
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
                        $('#reviso').append('<option value="">Seleccione una opción</option>');
                        let nombreRevisoPcl = Object.keys(data);
                        for (let i = 0; i < nombreRevisoPcl.length; i++) {
                            if (data[nombreRevisoPcl[i]]['name'] == data_correspondencia["bd_reviso"]) {                    
                                $('#reviso').append('<option value="'+data[nombreRevisoPcl[i]]['name']+'" selected>'+data[nombreRevisoPcl[i]]['name']+'</option>');
                            }else{
                                $('#reviso').append('<option value="'+data[nombreRevisoPcl[i]]['name']+'">'+data[nombreRevisoPcl[i]]['name']+'</option>');
                            }
                        }
                    }
                });

                // Checkbox Firmar
                if (data_correspondencia["firmar"] == "Si") {
                    $("#firmar").prop("checked", true);
                }else{
                    $("#firmar").prop("checked", false);
                }

                // Ciudad
                $("#ciudad").empty();
                $("#ciudad").val(data_correspondencia["ciudad"]);

                // Fecha Correspondencia
                $("#f_correspondencia").empty();
                $("#f_correspondencia").val(data_correspondencia["f_correspondencia"])

            }
        });

        /* cargue Datos Controversia Juntas */
        let datos_controversia_juntas = {
            '_token': token,
            // 'tupla_comunicado' :tupla_comunicado,
            'id_evento' :id_evento,
            'id_asignacion' :id_asignacion,
            'parametro': "controversia_juntas"
        };
        $.ajax({
            type:'POST',
            url:'/CargueInformacionCorrespondencia',
            data: datos_controversia_juntas,
            success:function(data_contro_juntas){

                /* Nombre destinatario opcion afiliado */
                $("#nombre_destinatario_afi").val(data_contro_juntas["nombre_destinatario_afi"]);
                /* Nombre destinatario opcion empleador */
                $("#nombre_destinatario_emp").val(data_contro_juntas["nombre_destinatario_emp"]);

                /* id servicio ya sea controversia pcl o controversia origen */
                /* $("#llenar_completo").html('');
                // controversia pcl
                if(data_contro_juntas["id_servicio"] == 13){
                    $("#llenar_completo").html(' Nombre CIE-10 JRCI, %Pcl JRCI, Fecha Estructuración JRCI, ');
                }
                // controversia origen
                else if(data_contro_juntas["id_servicio"] == 12){
                    $("#llenar_completo").html(' Origen Dx JRCI, CIE-10 - Nombre CIE-10 JRCI, ');
                } */

            }
        });

    });

    // Ejecución para descarga de proforma con el ojito
    $("a[id^='generar_proforma_acuerdo_']").click(function(event){
        event.preventDefault();
        
        // Recopilación de datos
        var token = $('input[name=_token]').val();
        var id_comite_inter = $(this).data("id_comite_inter");
        var id_cliente = $("#id_cliente").val();
        var id_asignacion = $("#newId_asignacion").val();
        var id_proceso = $("#Id_proceso").val();
        var id_servicio = $("#id_servicio").val();
        var nro_radicado = $(this).data("tupla_nro_radicado");
        var tipo_identificacion = $("#tipo_documento").val();
        var num_identificacion = $("#identificacion").val();
        var id_evento = $("#newId_evento").val();
        var id_Jrci_califi_invalidez = $("#id_Jrci_califi_invalidez").val();
        var nombre_junta_regional = $("#jrci_califi_invalidez").val();
        var nro_dictamen = $("#n_dictamen_jrci_emitido").val();
        var nombre_afiliado = $("#nombre_afiliado").val();
        var f_dictamen_jrci_emitido = $("#f_dictamen_jrci_emitido").val();
        var porcentaje_pcl_jrci_emitido = $("#porcentaje_pcl_jrci_emitido").val();
        var origen_jrci_emitido = $("#origen_jrci_emitido option:selected").text();
        var f_estructuracion_contro_jrci_emitido = $("#f_estructuracion_contro_jrci_emitido").val();
        var manual_de_califi_jrci_emitido = $("#manual_de_califi_jrci_emitido option:selected").text();
        var sustentacion_concepto_jrci = $("#sustentacion_concepto_jrci").val();
        var sustentacion_concepto_jrci1 = $("#sustentacion_concepto_repo_jrci").val();
        var copia_afiliado = $('#afiliado').filter(":checked").val();
        var copia_empleador = $('#empleador').filter(":checked").val();
        var copia_eps = $('#eps').filter(":checked").val();
        var copia_afp = $('#afp').filter(":checked").val();
        var copia_arl = $('#arl').filter(":checked").val();
        var asunto = $("#Asunto").val();
        var cuerpo = $("#cuerpo_comunicado").summernote('code');
        var firmar = $('#firmar').filter(":checked").val();

        var datos_proforma_acuerdo = {
            '_token': token,
            'id_comite_inter': id_comite_inter,
            'id_cliente': id_cliente,
            'id_asignacion': id_asignacion,
            'id_proceso': id_proceso,
            'id_servicio': id_servicio,
            'nro_radicado': nro_radicado,
            'tipo_identificacion': tipo_identificacion,
            'num_identificacion': num_identificacion,
            'id_evento': id_evento,
            'id_Jrci_califi_invalidez': id_Jrci_califi_invalidez,
            'nombre_junta_regional': nombre_junta_regional,
            'nro_dictamen': nro_dictamen,
            'nombre_afiliado': nombre_afiliado,
            'f_dictamen_jrci_emitido': f_dictamen_jrci_emitido,
            'porcentaje_pcl_jrci_emitido': porcentaje_pcl_jrci_emitido,
            'origen_jrci_emitido': origen_jrci_emitido,
            'f_estructuracion_contro_jrci_emitido': f_estructuracion_contro_jrci_emitido,
            'manual_de_califi_jrci_emitido': manual_de_califi_jrci_emitido,
            'sustentacion_concepto_jrci': sustentacion_concepto_jrci,
            'sustentacion_concepto_jrci1': sustentacion_concepto_jrci1,
            'copia_afiliado': copia_afiliado,
            'copia_empleador': copia_empleador,
            'copia_eps': copia_eps,
            'copia_afp': copia_afp,
            'copia_arl': copia_arl,
            'asunto': asunto,
            'cuerpo': cuerpo,
            'firmar': firmar,
        }

        setTimeout(() => {
            $.ajax({    
                type:'POST',
                url:'/DescargarProformaPronunDictaAcuerdo',
                data: datos_proforma_acuerdo,
                xhrFields: {
                    responseType: 'blob' // Indica que la respuesta es un blob
                },
                success: function (response, status, xhr) {
                    var blob = new Blob([response], { type: xhr.getResponseHeader('content-type') });
            
                    // Crear un enlace de descarga similar al ejemplo anterior
                    
                    var nombre_documento = "JUN_ACUERDO_"+id_asignacion+"_"+num_identificacion+"_"+nro_radicado+".pdf";                    
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
                    console.error('Error al descargar el word:', error);
                }       
            });
        }, 2000);

    });

    /* Generacion de Proforma en WORD para decisión DESACUERDO en la sección Revisión ante concepto de la Junta Regional */

    // llenado de datos con el hover para el ojito
    $("a[id^='generar_proforma_desacuerdo_']").hover(function(){
        var id_comite_inter = $(this).data("id_comite_inter");
        // var tupla_comunicado = $(this).data("tupla_comunicado");
        var id_evento = $(this).data("id_evento");
        var id_asignacion = $(this).data("id_asignacion");

        let token = $("input[name='_token']").val();

        /* cargue de datos controvertido */
        let datos_controvertido = {
            '_token': token,
            // 'tupla_comunicado' :tupla_comunicado,
            'id_evento' :id_evento,
            'id_asignacion' :id_asignacion,
            'parametro': "controvertido"
        };
        $.ajax({
            type:'POST',
            url:'/CargueInformacionCorrespondencia',
            data: datos_controvertido,
            success:function(data){
                $("#destinatario_principal").val('');
                $("#destinatario_principal").val(data["destinatario_principal"]);
            }
        });

        /* cargue Datos correspondencia */
        let datos_correspondencia = {
            '_token': token,
            // 'tupla_comunicado': tupla_comunicado,
            'id_evento': id_evento,
            'id_asignacion': id_asignacion,
            'id_comite_inter': id_comite_inter,
            'parametro': "correspondencia"
        };
        
        $.ajax({
            type:'POST',
            url:'/CargueInformacionCorrespondencia',
            data: datos_correspondencia,
            success:function(data_correspondencia){

                // Validación del Checkbox Otro Destinatario Principal para mostrar u ocultar los campos relacionados a el
                if(data_correspondencia["checkeado_otro_destinatario"] == "Si"){
                    $("#otrodestinariop").prop("checked", true);
                    $('#div_tipo_destinatario_principal').slideDown('slow');

                    $("#tipo_destinatario_principal").prop('required', true);
                    
                    // opcion 4: afiliado
                    if(data_correspondencia["db_tipo_destinatario_principal"] == 4) {
                        $('#div_datos_otro_destinatario').slideUp('up');
                        $('#div_nombre_destinatariopri').slideUp('up');
                        $('#div_nombre_destinatariopri_afi_').slideDown('slow');
                        $('#div_nombre_destinatariopri_empl').slideUp('up');
                        $("#nombre_destinatario_afi").prop('required', true);
                        $("#nombre_destinatario_emp").prop('required', false);
                        $("#nombre_destinatariopri").prop('required', false);
                    }
                    // opcion 5: empleador
                    else if(data_correspondencia["db_tipo_destinatario_principal"] == 5) {
                        $('#div_datos_otro_destinatario').slideUp('up');
                        $('#div_nombre_destinatariopri').slideUp('up');
                        $('#div_nombre_destinatariopri_empl').slideDown('slow');
                        $('#div_nombre_destinatariopri_afi_').slideUp('up');
                        $("#nombre_destinatario_emp").prop('required', true);
                        $("#nombre_destinatario_afi").prop('required', false);
                        $("#nombre_destinatariopri").prop('required', false);
                    }
                    // opcion 8: otro
                    else if (data_correspondencia["db_tipo_destinatario_principal"] == 8) {
                        $('#div_datos_otro_destinatario').slideDown('slow');
                        $('#div_nombre_destinatariopri').slideUp('up');
                        $('#div_nombre_destinatariopri_afi_').slideUp('up');
                        $('#div_nombre_destinatariopri_empl').slideUp('slow');
                        $("#nombre_destinatario_afi").prop('required', false);
                        $("#nombre_destinatario_emp").prop('required', false);
                        $("#nombre_destinatariopri").prop('required', false);
                    }

                    else {  
                        $('#div_nombre_destinatariopri').slideDown('slow');
                        $('#div_datos_otro_destinatario').slideUp('up');
                        $("#nombre_destinatariopri").prop('required', true);
                    } 
                }else{
                    $("#otrodestinariop").prop("checked", false);
                    $('#div_tipo_destinatario_principal').slideUp('slow');
                    $('#div_nombre_destinatariopri').slideUp('up'); 
                    $('#div_datos_otro_destinatario').slideUp('up');
                    $('#div_nombre_destinatariopri_afi_').slideUp('up');
                    $('#div_nombre_destinatariopri_empl').slideUp('up');

                    $("#tipo_destinatario_principal").prop('required', false);
                    $('#tipo_destinatario_principal').empty();
                    $("#nombre_destinatariopri").prop('required', false);
                    $("#nombre_destinatario_afi").prop('required', false);
                    $("#nombre_destinatario_emp").prop('required', false);
                }

                /* Carga de los datos del selector de tipo de destinatario y el id */
                $("#db_tipo_destinatario_principal").val(data_correspondencia["db_tipo_destinatario_principal"]);
                $('#tipo_destinatario_principal').empty();
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
                        let destini_pricipal = Object.keys(data);
                        for (let i = 0; i < destini_pricipal.length; i++) {
                            if (data[destini_pricipal[i]]['Id_solicitante'] == data_correspondencia["db_tipo_destinatario_principal"]) {  
                                $('#tipo_destinatario_principal').append('<option value="'+data[destini_pricipal[i]]["Id_solicitante"]+'" selected>'+data[destini_pricipal[i]]["Solicitante"]+'</option>');
                            }else{                    
                                $('#tipo_destinatario_principal').append('<option value="'+data[destini_pricipal[i]]["Id_solicitante"]+'">'+data[destini_pricipal[i]]["Solicitante"]+'</option>');
                            }
                        }
                    }
                });

                /* Carga de los datos del selector del Nombre del destinatario */
                $("#db_nombre_destinatariopri").val(data_correspondencia["db_nombre_destinatariopri"]);
                $('#nombre_destinatariopri').empty();
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

                /* Carga Datos de la opcion otro del tipo de destinatario principal */
                $("#nombre_destinatario").empty();
                $("#nitcc_destinatario").empty();
                $("#direccion_destinatario").empty();
                $("#telefono_destinatario").empty();
                $("#email_destinatario").empty();
                $("#departamento_destinatario").empty();
                $("#ciudad_destinatario").empty();
                
                $("#nombre_destinatario").val(data_correspondencia["nombre_destinatario"]);
                $("#nitcc_destinatario").val(data_correspondencia["nitcc_destinatario"]);
                $("#direccion_destinatario").val(data_correspondencia["direccion_destinatario"]);
                $("#telefono_destinatario").val(data_correspondencia["telefono_destinatario"]);
                $("#email_destinatario").val(data_correspondencia["email_destinatario"]);
                $("#departamento_destinatario").val(data_correspondencia["departamento_destinatario"]);
                $("#ciudad_destinatario").val(data_correspondencia["ciudad_destinatario"]);

                /* Carga del Asunto */
                $("#Asunto").val();
                $("#Asunto").val(data_correspondencia["Asunto"]);

                /* Cuerpo del Comunicado */
                $("#cuerpo_comunicado").summernote('code', '');
                $("#cuerpo_comunicado").summernote('code', data_correspondencia["cuerpo_comunicado"]);

                /* Copias partes interesadas */
                if (data_correspondencia["checkeado_afiliado"] == "Si") {
                    $("#afiliado").prop("checked", true);
                }else{
                    $("#afiliado").prop("checked", false);
                }

                if (data_correspondencia["checkeado_empleador"] == "Si") {
                    $("#empleador").prop("checked", true);
                }else{
                    $("#empleador").prop("checked", false);
                }

                if (data_correspondencia["checkeado_eps"] == "Si") {
                    $("#eps").prop("checked", true);
                }else{
                    $("#eps").prop("checked", false);
                }

                if (data_correspondencia["checkeado_afp"] == "Si") {
                    $("#afp").prop("checked", true);
                }else{
                    $("#afp").prop("checked", false);
                }

                if (data_correspondencia["checkeado_arl"] == "Si") {
                    $("#arl").prop("checked", true);
                }else{
                    $("#arl").prop("checked", false);
                }

                if (data_correspondencia["checkeado_copia_jr"] == "Si") {
                    $("#jrci").prop("checked", true);
                    $('#div_cual').slideDown('slow');
                    $('#cual').prop('required', true);
                    $("#bd_cual_jr").val(data_correspondencia["bd_cual_jr"]);
                }else{
                    $("#jrci").prop("checked", false);
                    $('#div_cual').slideUp('up');  
                    $('#cual').prop('required', false);
                }

                //Listado juntas regional Correspondencia
                $('#cual').empty();
                let datos_lista_regional_junta = {
                    '_token': token,
                    'parametro':"lista_regional_junta"
                };
                $.ajax({
                    type:'POST',
                    url:'/selectoresCalificacionTecnicaPCL',
                    data: datos_lista_regional_junta,
                    success:function(data) {
                        $('#cual').append('<option value="">Seleccione una opción</option>');
                        let primercali = Object.keys(data);
                        for (let i = 0; i < primercali.length; i++) {
                            if (data[primercali[i]]['Ciudad_Junta'] == data_correspondencia["bd_cual_jr"]) {  
                                $('#cual').append('<option value="'+data[primercali[i]]["Ciudad_Junta"]+'" selected>'+data[primercali[i]]["Ciudad_Junta"]+'</option>');
                            }else{
                                $('#cual').append('<option value="'+data[primercali[i]]["Ciudad_Junta"]+'">'+data[primercali[i]]["Ciudad_Junta"]+'</option>');
                            }
                        }
                    }
                });

                if (data_correspondencia["checkeado_copia_jn"] == "Si") {
                    $("#jnci").prop("checked", true);
                }else{
                    $("#jnci").prop("checked", false);
                }

                // anexos
                $("#anexos").empty();
                $("#anexos").val(data_correspondencia["anexos"]);

                // Elaboró
                $("#elaboro").empty();
                $("#elaboro").val(data_correspondencia["elaboro"]);

                // Reviso
                $('#reviso').empty();
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
                        $('#reviso').append('<option value="">Seleccione una opción</option>');
                        let nombreRevisoPcl = Object.keys(data);
                        for (let i = 0; i < nombreRevisoPcl.length; i++) {
                            if (data[nombreRevisoPcl[i]]['name'] == data_correspondencia["bd_reviso"]) {                    
                                $('#reviso').append('<option value="'+data[nombreRevisoPcl[i]]['name']+'" selected>'+data[nombreRevisoPcl[i]]['name']+'</option>');
                            }else{
                                $('#reviso').append('<option value="'+data[nombreRevisoPcl[i]]['name']+'">'+data[nombreRevisoPcl[i]]['name']+'</option>');
                            }
                        }
                    }
                });

                // Checkbox Firmar
                if (data_correspondencia["firmar"] == "Si") {
                    $("#firmar").prop("checked", true);
                }else{
                    $("#firmar").prop("checked", false);
                }

                // Ciudad
                $("#ciudad").empty();
                $("#ciudad").val(data_correspondencia["ciudad"]);

                // Fecha Correspondencia
                $("#f_correspondencia").empty();
                $("#f_correspondencia").val(data_correspondencia["f_correspondencia"])

            }
        });

        /* cargue Datos Controversia Juntas */
        let datos_controversia_juntas = {
            '_token': token,
            // 'tupla_comunicado' :tupla_comunicado,
            'id_evento' :id_evento,
            'id_asignacion' :id_asignacion,
            'parametro': "controversia_juntas"
        };
        $.ajax({
            type:'POST',
            url:'/CargueInformacionCorrespondencia',
            data: datos_controversia_juntas,
            success:function(data_contro_juntas){

                /* Nombre destinatario opcion afiliado */
                $("#nombre_destinatario_afi").val(data_contro_juntas["nombre_destinatario_afi"]);
                /* Nombre destinatario opcion empleador */
                $("#nombre_destinatario_emp").val(data_contro_juntas["nombre_destinatario_emp"]);

                /* id servicio ya sea controversia pcl o controversia origen */
                /* $("#llenar_completo").html('');
                // controversia pcl
                if(data_contro_juntas["id_servicio"] == 13){
                    $("#llenar_completo").html(' Nombre CIE-10 JRCI, %Pcl JRCI, Fecha Estructuración JRCI, ');
                }
                // controversia origen
                else if(data_contro_juntas["id_servicio"] == 12){
                    $("#llenar_completo").html(' Origen Dx JRCI, CIE-10 - Nombre CIE-10 JRCI, ');
                } */

            }
        });

    });

    // Ejecución para descarga de proforma con el ojito
    $("a[id^='generar_proforma_desacuerdo_']").click(function(event){
        event.preventDefault();
        
        // Recopilación de datos
        var token = $('input[name=_token]').val();
        var id_comite_inter = $(this).data("id_comite_inter");
        var id_cliente = $("#id_cliente").val();
        var id_asignacion = $("#newId_asignacion").val();
        var id_proceso = $("#Id_proceso").val();
        var id_servicio = $("#id_servicio").val();
        var nro_radicado = $(this).data("tupla_nro_radicado");
        var nombre_afiliado = $("#nombre_afiliado").val();
        var tipo_identificacion = $("#tipo_documento").val();
        var num_identificacion = $("#identificacion").val();
        var id_evento = $("#newId_evento").val();
        var id_Jrci_califi_invalidez = $("#id_Jrci_califi_invalidez").val();
        var nombre_junta_regional = $("#jrci_califi_invalidez").val();
        var f_dictamen_jrci_emitido = $("#f_dictamen_jrci_emitido").val();
        var sustentacion_concepto_jrci = $("#sustentacion_concepto_jrci").val();
        var sustentacion_concepto_jrci1 = $("#sustentacion_concepto_repo_jrci").val();
        var copia_afiliado = $('#afiliado').filter(":checked").val();
        var copia_empleador = $('#empleador').filter(":checked").val();
        var copia_eps = $('#eps').filter(":checked").val();
        var copia_afp = $('#afp').filter(":checked").val();
        var copia_arl = $('#arl').filter(":checked").val();
        var asunto = $("#Asunto").val();
        var cuerpo = $("#cuerpo_comunicado").summernote('code');
        var firmar = $('#firmar').filter(":checked").val();

        var datos_proforma_desacuerdo = {
            '_token': token,
            'id_comite_inter': id_comite_inter,
            'id_cliente': id_cliente,
            'id_asignacion': id_asignacion,
            'id_proceso': id_proceso,
            'id_servicio': id_servicio,
            'nro_radicado': nro_radicado,
            'nombre_afiliado': nombre_afiliado,
            'tipo_identificacion': tipo_identificacion,
            'num_identificacion': num_identificacion,
            'id_evento': id_evento,
            'id_Jrci_califi_invalidez': id_Jrci_califi_invalidez,
            'nombre_junta_regional': nombre_junta_regional,
            'f_dictamen_jrci_emitido': f_dictamen_jrci_emitido,
            'sustentacion_concepto_jrci': sustentacion_concepto_jrci,
            'sustentacion_concepto_jrci1': sustentacion_concepto_jrci1,
            'copia_afiliado': copia_afiliado,
            'copia_empleador': copia_empleador,
            'copia_eps': copia_eps,
            'copia_afp': copia_afp,
            'copia_arl': copia_arl,
            'asunto': asunto,
            'cuerpo': cuerpo,
            'firmar': firmar
        }

        setTimeout(() => {
            $.ajax({    
                type:'POST',
                url:'/DescargarProformaRecursoReposicion',
                data: datos_proforma_desacuerdo,
                xhrFields: {
                    responseType: 'blob' // Indica que la respuesta es un blob
                },
                success: function (response, status, xhr) {
                    var blob = new Blob([response], { type: xhr.getResponseHeader('content-type') });
            
                    // Crear un enlace de descarga similar al ejemplo anterior
                    
                    var nombre_documento = "JUN_DESACUERDO_"+id_asignacion+"_"+num_identificacion+"_"+nro_radicado+".docx";                    
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
                    console.error('Error al descargar el word:', error);
                }       
            });
        }, 2000);

    });

    /* Funcionalidad para mostrar solo la tabla de comunicados para el rol de Consulta */
    if (idRol == 7) {
        $("#form_DTO_ATEL").addClass('d-none');
        $("#div_info_afiliado").addClass('d-none');
        $("#div_info_dic_cotrover").addClass('d-none');
        $("#div_dx_dicta_contro").addClass('d-none');
        $("#div_dicta_jun_regio").addClass('d-none');
        $("#div_rev_concep_jun_regio").addClass('d-none');
        $(".row_recurso_ante_jrci").addClass('d-none');
        $("#div_Firmeza_controversiaJRCI").addClass('d-none');
        $("#row_firmeza_intere").addClass('d-none');
        $("#row_repo_dictamen").addClass('d-none');
        $("#div_rev_recur_repo").addClass('d-none');
        $("#apela_recu").addClass('d-none');
        $("#row_acta_ejecutoria").addClass('d-none');
        $("#row_emitido_jnci").addClass('d-none');
        $("#div_correspondencia").addClass('d-none');
        $("#msg_alerta").addClass('d-none');
        $("a[id^='editar_correspondencia_']").addClass('d-none');
    }

    /* Códigos para el tema del rol administrador (modelo a seguir) */
    // A los usuarios que no tengan el rol Administrador se les aplica los siguientes controles en el formulario de correspondencia:
    // inhabilita los campos nro anexos, asunto, etiquetas, cuerpo comunicado, firmar
    if (idRol != 6) {
        $("#anexos").prop('readonly', true);
        $("#Asunto").prop('readonly', true);

        $("#btn_insertar_nro_dictamen_jrci_asunto").prop('disabled', true);
        $("#btn_insertar_fecha_dictamen_jrci_asunto").prop('disabled', true);

        $("#btn_insertar_nro_dictamen_jrci").prop('disabled', true);
        $("#btn_insertar_fecha_dictamen_jrci").prop('disabled', true);
        $("#btn_insertar_nombre_afiliado").prop('disabled', true);
        $("#btn_insertar_tipo_documento_afiliado").prop('disabled', true);
        $("#btn_insertar_documento_afiliado").prop('disabled', true);
        $("#btn_insertar_cie_nombre_jrci").prop('disabled', true);
        $("#btn_insertar_pcl_jrci").prop('disabled', true);
        $("#btn_insertar_origen_dx_jrci").prop('disabled', true);
        $("#btn_insertar_f_estructuracion_jrci").prop('disabled', true);
        $("#btn_insertar_decreto_calificador_jrci").prop('disabled', true);
        $("#btn_insertar_sustentacion_jrci").prop('disabled', true);
        $("#btn_insertar_sustentacion_jrci1").prop('disabled', true);
        
        $(".note-editable").attr("contenteditable", false);
        $("#firmar").prop('disabled', true);
    }


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