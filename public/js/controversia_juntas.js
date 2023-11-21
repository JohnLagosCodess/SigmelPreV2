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
    // Guardar Datos Dictamen Controvertido
    $('#form_guardarControvertido').submit(function (e) {
        e.preventDefault();
        let token = $('input[name=_token]').val();  
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

});
/* Función para añadir los controles de cada elemento de cada fila en la tabla Diagnostico motivo de calificación*/
/*Para Diagnosticos Controvertido*/
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
                $("#lista_Cie10_fila_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Cie_diagnostico"]+'">'+data[claves[i]]["CIE10"]+'</option>');
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
        width: '100%',
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
                $("#lista_Cie10_fila_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Cie_diagnostico"]+'">'+data[claves[i]]["CIE10"]+'</option>');
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