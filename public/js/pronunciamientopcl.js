$(document).ready(function(){

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
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".reviso").select2({
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
                if (data[primercali[i]]['Id_juntaR'] != IdJunta) {  
                    $('#junta_regional_cual').append('<option value="'+data[primercali[i]]["Id_juntaR"]+'">'+data[primercali[i]]["Ciudad_Junta"]+'</option>');
                }
            }
        }
    });

    /* VALIDACIÓN MOSTRAR FECHA EVENTO DE ACUERDO A TIPO EVENTO  */ 
    var opt_tipo_evento;
    $("#tipo_evento").change(function(){
        opt_tipo_evento = parseInt($(this).val());
        $("#tipo_evento").val(opt_tipo_evento);
        iniciarIntervalo_tEvento();
    });
    // Función para validar items a mostrar
    const tiempoDeslizamiento = 'slow';
    function iniciarIntervalo_tEvento() {
         // Selección de los elementos que se deslizarán
         const elementosDeslizar = [
            '.m_fecha_evento'
        ];
        intervalo = setInterval(() => {
            switch (opt_tipo_evento) {
                case 1:
                    elementosDeslizar.forEach(elemento => {
                        $(elemento).slideDown(tiempoDeslizamiento);
                    }); 
                    $('#fecha_evento').prop('required', true);
                break;
                
                case 2: 
                    elementosDeslizar.forEach(elemento => {
                        $(elemento).slideUp(tiempoDeslizamiento);
                    });
                    $('#fecha_evento').prop('required', false);
                break;

                default:
                    // Deslizar hacia arriba (ocultar) los elementos
                    elementosDeslizar.forEach(elemento => {
                        $(elemento).slideUp(tiempoDeslizamiento);
                    });
                    $('#fecha_evento').prop('required', false);
                break;
            }

        }, 500);

    }

    /* VALIDACIÓN MOSTRAR RANGO PCL */
    var opt_rango_pcl= 0;
    $("#porcentaje_pcl").on("input", function(){
        opt_rango_pcl = parseInt($(this).val());
        $("#porcentaje_pcl").val(opt_rango_pcl);
        iniciarIntervalo_rangoPcl();
    });
    function iniciarIntervalo_rangoPcl() {
        var resul_rango=0;
        //clearInterval(intervaloRango);
        intervaloRango = setInterval(() => {
            if(opt_rango_pcl=='isNaN'){
                resul_rango = '0';
            }else if(opt_rango_pcl < 15){
                resul_rango = 'Entre 1 y 14,99%';
            } else if (opt_rango_pcl >= 15 && opt_rango_pcl < 30){
                resul_rango = 'Entre 15 y 29,99%';
            } else if (opt_rango_pcl >= 30 && opt_rango_pcl < 50){
                resul_rango = 'Entre 30 y 49,99%';
            } else if (opt_rango_pcl >= 50){
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
    var opt_cual_junta;
    $("#junta_regional").on("change", function(){
        opt_cual_junta = $(this).val();
        $(this).val(opt_cual_junta);
        iniciarIntervalo_junta();
    });
    // Función para validar items a mostrar
    const tiempoDeslizamiento3 = 'slow';
    function iniciarIntervalo_junta() {
         // Selección de los elementos que se deslizarán
         const elementosDeslizar3 = [
            '.c_cualJunta'
        ];
        intervaloJu = setInterval(() => {
            //console.log(opt_cual_junta)
            switch (opt_cual_junta) {
                case "junta_regi":
                    elementosDeslizar3.forEach(elemento => {
                        $(elemento).slideDown(tiempoDeslizamiento3);
                    }); 
                    $('#junta_regional_cual').prop('required', true);
                break;
                default:
                    // Deslizar hacia arriba (ocultar) los elementos
                    elementosDeslizar3.forEach(elemento => {
                        $(elemento).slideUp(tiempoDeslizamiento3);
                    });
                    $('#junta_regional_cual').prop('required', false);
                break;
            }

        }, 500);

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
        formData.append('fecha_estruturacion', $('#fecha_estruturacion').val());
        formData.append('porcentaje_pcl', $('#porcentaje_pcl').val());
        formData.append('rango_pcl', $('#rango_pcl').val());
        formData.append('decision_pr', $("[id^='di_']").filter(":checked").val());
        formData.append('fecha_pronuncia2', $('#fecha_pronuncia2').val());
        formData.append('asunto_cali', $('#asunto_cali').val());
        formData.append('sustenta_cali', $('#sustenta_cali').val());
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
            url:'/guardarInfoServiPronuncia',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                if (response.parametro == 'agregar_pronunciamiento') {
                    document.querySelector('#GuardarPronuncia').disabled=true;
                    $('#div_alerta_pronuncia').removeClass('d-none');
                    $('.alerta_pronucia').append('<strong>'+response.mensaje+'</strong>');                                            
                    setTimeout(function(){
                        $('#div_alerta_pronuncia').addClass('d-none');
                        $('.alerta_pronucia').empty();   
                        location.reload();
                    }, 3000);   
                }else if(response.parametro == 'update_pronunciamiento'){
                    document.querySelector('#ActualizarPronuncia').disabled=true;
                    $('#div_alerta_pronuncia').removeClass('d-none');
                    $('.alerta_pronucia').append('<strong>'+response.mensaje2+'</strong>');                                           
                    setTimeout(function(){
                        $('#div_alerta_pronuncia').addClass('d-none');
                        $('.alerta_pronucia').empty();
                        document.querySelector('#ActualizarPronuncia').disabled=false;
                        location.reload();
                    }, 3000);
                }
            }
        })
    })
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