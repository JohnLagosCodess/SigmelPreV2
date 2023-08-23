var valor_FP_selecciondo,
valor_CFM1_seleccionado,
valor_CFM2_seleccionado,
valor_FU_seleccionado,
valor_CAT_seleccionado,
fila_pertenece, id_tabla_pertenece, nombre_tabla_pertenece;
$(document).ready(function(){
    $(".centrar").css('text-align', 'center');
    /* GENERACIÓN DEL DATATABLE */
    var tabla_alteraciones_sistemas = $('#listado_deficiencia_porfactor').DataTable({
        "responsive": true,
        "info": false,
        "searching": false,
        "ordering": false,
        "scrollCollapse": true,
        "scrollY": "30vh",
        "paging": false,
        "language":{
            "emptyTable": "No se encontró información"
        }
    });

    /* FUNCIÓN PARA AJUSTAR COLUMNAS */
    autoAdjustColumns(tabla_alteraciones_sistemas);

    /* REALIZAR LA INSERCIÓN DEL CONTENIDO EN LA FILA */
    var contador_alteraciones = 0;
    $("#btn_agregar_deficiencia_porfactor").click(function(){
        
        $('#guardar_datos_deficiencia_alteraciones').removeClass('d-none');
        contador_alteraciones = contador_alteraciones + 1;
        // 11
        var nueva_fila_alteraciones = [
          '<select id="listado_tablas_fila_alteraciones_'+contador_alteraciones+'" class="form-comtrol custom-select listado_tablas_fila_alteraciones_'+contador_alteraciones+'" name="ident_tabla"><option></option></select>',
          '<div id="titulo_tabla_fila_alteraciones_'+contador_alteraciones+'"></div>',
          '<div id="FP_fila_alteraciones_'+contador_alteraciones+'"></div>',
          '<div id="CFM1_fila_alteraciones_'+contador_alteraciones+'"></div>',
          '<div id="CFM2_fila_alteraciones_'+contador_alteraciones+'"></div>',
          '<div id="FU_fila_alteraciones_'+contador_alteraciones+'"></div>',
          '<div id="CAT_fila_alteraciones_'+contador_alteraciones+'"></div>',
          '<div id="ClaseFinal_fila_alteraciones_'+contador_alteraciones+'"></div>',
          '<input type="checkbox" id="checkbox_dx_principal_DefiAlteraciones_'+contador_alteraciones+'" class="checkbox_dx_principal_DefiAlteraciones_'+contador_alteraciones+'" style="transform: scale(1.2);">',
          '<div id="MSD_fila_alteraciones_'+contador_alteraciones+'"></div>',
          '<div id="Deficiencia_fila_alteraciones_'+contador_alteraciones+'"></div>',
          '<div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_fila_alteraciones" class="text-info" data-fila="fila_alteraciones_'+contador_alteraciones+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
          'fila_alteraciones_'+contador_alteraciones
        ];

        var agregar_fila_alteraciones = tabla_alteraciones_sistemas.row.add(nueva_fila_alteraciones).draw().node();
        $(agregar_fila_alteraciones).addClass('fila_alteraciones_'+contador_alteraciones);
        $(agregar_fila_alteraciones).attr("id", 'fila_alteraciones_'+contador_alteraciones);


        // Esta función realiza los controles de cada elemento por fila
        funciones_elementos_fila_alteraciones(contador_alteraciones);
    });
});

function funciones_elementos_fila_alteraciones(num_consecutivo_alteraciones) {
    let token = $("input[name='_token']").val();
    // var ident_tabla;
    /* SELECT 2 LISTADO DE TABLAS */  
    $(".listado_tablas_fila_alteraciones_"+num_consecutivo_alteraciones).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* Cargue de datos LISTADO DE TABLAS */
    let listado_tablas = {
        '_token': token,
        'parametro' : "listado_tablas_decreto",
    }
    $.ajax({
        type:'POST',
        url:'/ListadoSelectoresDefiAlteraciones',
        data: listado_tablas,
        success:function(data){
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#listado_tablas_fila_alteraciones_"+num_consecutivo_alteraciones).append('<option value="'+data[claves[i]]["Id_tabla"]+'">'+data[claves[i]]["Ident_tabla"]+' - '+data[claves[i]]["Nombre_tabla"]+'</option>');
            }
        }
    });

    /* FUNCIONALIDAD PARA INSERTAR EL NOMBRE DE LA TABLA Y CREAR LOS SELECTORES FP, CFM1, CFM2, FU, CAT DEPENDIENDO DE LA SELECCIÓN DE LA TABLA*/
    $("#listado_tablas_fila_alteraciones_"+num_consecutivo_alteraciones).change(function(){
        var id_tabla_seleccionado = $(this).val();

        // Nombre de la tabla
        let listado_nombre_tabla = {
            '_token': token,
            'parametro' : "nombre_tabla",
            'Id_tabla': id_tabla_seleccionado
        }
        $.ajax({
            type:'POST',
            url:'/ListadoSelectoresDefiAlteraciones',
            data: listado_nombre_tabla,
            success:function(data){
                $("#titulo_tabla_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
                $("#titulo_tabla_fila_alteraciones_"+num_consecutivo_alteraciones).append(data[0]["Nombre_tabla"]);
                
                /* sessionStorage.removeItem("consecutivo")
                sessionStorage.removeItem("num_tabla")
                sessionStorage.removeItem("id_tabla")
                sessionStorage.setItem("consecutivo", num_consecutivo_alteraciones);
                sessionStorage.setItem("num_tabla", data[0]["Ident_tabla"]);
                sessionStorage.setItem("id_tabla", id_tabla_seleccionado); */
            }
        });

        // Selector FP
        let listado_FP = {
            '_token': token,
            'parametro' : "selector_FP",
            'Id_tabla': id_tabla_seleccionado
        }
        $.ajax({
            type:'POST',
            url:'/ListadoSelectoresDefiAlteraciones',
            data: listado_FP,
            success:function(data){
                $("#FP_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
                switch (data[0]["FP"]) {
                    case "Desactivar":
                        $("#FP_fila_alteraciones_"+num_consecutivo_alteraciones).append("N/A");
                    break;
                    case "Abierto":
                        $("#FP_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input type="number" class="form-control" id="resultado_FP_'+num_consecutivo_alteraciones+'">');
                    break;
                    default:
                        var opciones_FP = data[0]["FP"].split(",");
                        var select_FP = $('<select id="resultado_FP_'+num_consecutivo_alteraciones+'" class="custom-select resultado_FP_'+num_consecutivo_alteraciones+'"\
                        data-fila_pertenece="'+num_consecutivo_alteraciones+'" data-id_tabla_pertenece="'+data[0]["Id_tabla"]+'" data-nombre_tabla_pertenece="'+data[0]["Ident_tabla"]+'">');

                        select_FP.append($("<option>").val("").text(""));
                        $.each(opciones_FP, function(index, insertar_opcion_FP) {
                            var option_FP = $("<option>")
                                .val(insertar_opcion_FP)
                                .text(insertar_opcion_FP);
                            select_FP.append(option_FP);
                        });

                        $("#FP_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input id="guardar_FP_fila_'+num_consecutivo_alteraciones+'">');
                        $("#FP_fila_alteraciones_"+num_consecutivo_alteraciones).append(select_FP);

                        /* SELECT 2 LISTADO FP */  
                        $(".resultado_FP_"+num_consecutivo_alteraciones).select2({
                            width: '100%',
                            placeholder: "Seleccione",
                            allowClear: false
                        });
                    break;
                }
            }
        });

        // Selector CFM1
        let listado_CFM1 = {
            '_token': token,
            'parametro' : "selector_CFM1",
            'Id_tabla': id_tabla_seleccionado
        }
        $.ajax({
            type:'POST',
            url:'/ListadoSelectoresDefiAlteraciones',
            data: listado_CFM1,
            success:function(data){
                $("#CFM1_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
                switch (data[0]["CFM1"]) {
                    case "Desactivar":
                        $("#CFM1_fila_alteraciones_"+num_consecutivo_alteraciones).append("N/A");
                    break;
                    case "Abierto":
                        $("#CFM1_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input type="number" class="form-control" id="resultado_CFM1_'+num_consecutivo_alteraciones+'">');
                    break;
                    default:
                        var opciones_CFM1 = data[0]["CFM1"].split(",");
                        var select_CFM1 = $('<select id="resultado_CFM1_'+num_consecutivo_alteraciones+'" class="custom-select resultado_CFM1_'+num_consecutivo_alteraciones+'"\
                        data-fila_pertenece="'+num_consecutivo_alteraciones+'" data-id_tabla_pertenece="'+data[0]["Id_tabla"]+'" data-nombre_tabla_pertenece="'+data[0]["Ident_tabla"]+'">');
                        select_CFM1.append($("<option>").val("").text(""));
                        $.each(opciones_CFM1, function(index, insertar_opcion_CFM1) {
                            var option_CFM1 = $("<option>")
                                .val(insertar_opcion_CFM1)
                                .text(insertar_opcion_CFM1);
                            select_CFM1.append(option_CFM1);
                        });
        
                        $("#CFM1_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input id="guardar_CFM1_fila_'+num_consecutivo_alteraciones+'">');
                        $("#CFM1_fila_alteraciones_"+num_consecutivo_alteraciones).append(select_CFM1);
        
                        /* SELECT 2 LISTADO CFM1 */  
                        $(".resultado_CFM1_"+num_consecutivo_alteraciones).select2({
                            width: '100%',
                            placeholder: "Seleccione",
                            allowClear: false
                        });
                    break;
                }
            }
        });

        // Selector CFM2
        let listado_CFM2 = {
            '_token': token,
            'parametro' : "selector_CFM2",
            'Id_tabla': id_tabla_seleccionado
        }
        $.ajax({
            type:'POST',
            url:'/ListadoSelectoresDefiAlteraciones',
            data: listado_CFM2,
            success:function(data){
                $("#CFM2_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
                switch (data[0]["CFM2"]) {
                    case "Desactivar":
                        $("#CFM2_fila_alteraciones_"+num_consecutivo_alteraciones).append("N/A");
                    break;
                    case "Abierto":
                        $("#CFM2_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input type="number" class="form-control" id="resultado_CFM2_'+num_consecutivo_alteraciones+'">');
                    break;
                    default:
                        var opciones_CFM2 = data[0]["CFM2"].split(",");
                        var select_CFM2 = $('<select id="resultado_CFM2_'+num_consecutivo_alteraciones+'" class="custom-select resultado_CFM2_'+num_consecutivo_alteraciones+'"\
                        data-fila_pertenece="'+num_consecutivo_alteraciones+'" data-id_tabla_pertenece="'+data[0]["Id_tabla"]+'" data-nombre_tabla_pertenece="'+data[0]["Ident_tabla"]+'">');
                        select_CFM2.append($("<option>").val("").text(""));
                        $.each(opciones_CFM2, function(index, insertar_opcion_CFM2) {
                            var option_CFM2 = $("<option>")
                                .val(insertar_opcion_CFM2)
                                .text(insertar_opcion_CFM2);
                            select_CFM2.append(option_CFM2);
                        });
        
                        $("#CFM2_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input id="guardar_CFM2_fila_'+num_consecutivo_alteraciones+'">');
                        $("#CFM2_fila_alteraciones_"+num_consecutivo_alteraciones).append(select_CFM2);
        
                        /* SELECT 2 LISTADO CFM2 */  
                        $(".resultado_CFM2_"+num_consecutivo_alteraciones).select2({
                            width: '100%',
                            placeholder: "Seleccione",
                            allowClear: false
                        });
                    break;
                }
            }
        });

        // Selector FU
        let listado_FU = {
            '_token': token,
            'parametro' : "selector_FU",
            'Id_tabla': id_tabla_seleccionado
        }
        $.ajax({
            type:'POST',
            url:'/ListadoSelectoresDefiAlteraciones',
            data: listado_FU,
            success:function(data){
                $("#FU_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
                switch (data[0]["FU"]) {
                    case "Desactivar":
                        $("#FU_fila_alteraciones_"+num_consecutivo_alteraciones).append("N/A");
                    break;
                    case "Abierto":
                        $("#FU_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input type="number" class="form-control" id="resultado_FU_'+num_consecutivo_alteraciones+'">');
                    break;
                    default:
                        var opciones_FU = data[0]["FU"].split(",");
                        var select_FU = $('<select id="resultado_FU_'+num_consecutivo_alteraciones+'" class="custom-select resultado_FU_'+num_consecutivo_alteraciones+'"\
                        data-fila_pertenece="'+num_consecutivo_alteraciones+'" data-id_tabla_pertenece="'+data[0]["Id_tabla"]+'" data-nombre_tabla_pertenece="'+data[0]["Ident_tabla"]+'">');
                        select_FU.append($("<option>").val("").text(""));
                        $.each(opciones_FU, function(index, insertar_opcion_FU) {
                            var option_FU = $("<option>")
                                .val(insertar_opcion_FU)
                                .text(insertar_opcion_FU);
                            select_FU.append(option_FU);
                        });
        
                        $("#FU_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input id="guardar_FU_fila_'+num_consecutivo_alteraciones+'">');
                        $("#FU_fila_alteraciones_"+num_consecutivo_alteraciones).append(select_FU);
        
                        /* SELECT 2 LISTADO FU */  
                        $(".resultado_FU_"+num_consecutivo_alteraciones).select2({
                            width: '100%',
                            placeholder: "Seleccione",
                            allowClear: false
                        });
                    break;
                }
            }
        });

        // Selector CAT
        let listado_CAT = {
            '_token': token,
            'parametro' : "selector_CAT",
            'Id_tabla': id_tabla_seleccionado
        }
        $.ajax({
            type:'POST',
            url:'/ListadoSelectoresDefiAlteraciones',
            data: listado_CAT,
            success:function(data){
                $("#CAT_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
                switch (data[0]["CAT"]) {
                    case "Desactivar":
                        $("#CAT_fila_alteraciones_"+num_consecutivo_alteraciones).append("N/A");
                    break;
                    case "Abierto":
                        $("#CAT_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input type="number" class="form-control" id="resultado_CAT_'+num_consecutivo_alteraciones+'">');
                    break;
                    default:
                        var opciones_CAT = data[0]["CAT"].split(",");
                        var select_CAT = $('<select id="resultado_CAT_'+num_consecutivo_alteraciones+'" class="custom-select resultado_CAT_'+num_consecutivo_alteraciones+'"\
                        data-fila_pertenece="'+num_consecutivo_alteraciones+'" data-id_tabla_pertenece="'+data[0]["Id_tabla"]+'" data-nombre_tabla_pertenece="'+data[0]["Ident_tabla"]+'">');
                        select_CAT.append($("<option>").val("").text(""));
                        $.each(opciones_CAT, function(index, insertar_opcion_CAT) {
                            var option_CAT = $("<option>")
                                .val(insertar_opcion_CAT)
                                .text(insertar_opcion_CAT);
                            select_CAT.append(option_CAT);
                        });
        
                        $("#CAT_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input id="guardar_CAT_fila_'+num_consecutivo_alteraciones+'">');
                        $("#CAT_fila_alteraciones_"+num_consecutivo_alteraciones).append(select_CAT);
        
                        /* SELECT 2 LISTADO CAT */  
                        $(".resultado_CAT_"+num_consecutivo_alteraciones).select2({
                            width: '100%',
                            placeholder: "Seleccione",
                            allowClear: false
                        });
                    break;
                }
            }
        });

        // MSD
        let listado_MSD = {
            '_token': token,
            'parametro' : "MSD",
            'Id_tabla': id_tabla_seleccionado
        }
        $.ajax({
            type:'POST',
            url:'/ListadoSelectoresDefiAlteraciones',
            data: listado_MSD,
            success:function(data){
                $("#MSD_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
                switch (data[0]["MSD"]) {
                    case "N/A":
                        $("#MSD_fila_alteraciones_"+num_consecutivo_alteraciones).append("N/A");
                    break;
                    case "VERDADERO":
                        $("#MSD_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input type="checkbox" class="resultado_MSD_'+num_consecutivo_alteraciones+'" id="resultado_MSD_'+num_consecutivo_alteraciones+'" style="transform: scale(1.2);">');
                    break;
                    default:
                        $("#MSD_fila_alteraciones_"+num_consecutivo_alteraciones).append('');
                    break;
                }
            }
        });


    });

};



$(document).on('change', "select[id^='resultado_FP_']", function(){
    var id_FP_seleccionado = $(this).attr("id");
    valor_FP_selecciondo = $("#"+id_FP_seleccionado).val();
    fila_pertenece = $(this).data("fila_pertenece");
    id_tabla_pertenece = $(this).data("id_tabla_pertenece");
    nombre_tabla_pertenece = $(this).data("nombre_tabla_pertenece");
    $("#guardar_FP_fila_"+fila_pertenece).val(valor_FP_selecciondo);
});

$(document).on('change', "select[id^='resultado_CFM1_']", function(){
    var id_CFM1_seleccionado = $(this).attr("id");
    valor_CFM1_seleccionado = $("#"+id_CFM1_seleccionado).val();
    fila_pertenece = $(this).data("fila_pertenece");
    id_tabla_pertenece = $(this).data("id_tabla_pertenece");
    nombre_tabla_pertenece = $(this).data("nombre_tabla_pertenece");
    $("#guardar_CFM1_fila_"+fila_pertenece).val(valor_CFM1_seleccionado);
});

$(document).on('change', "select[id^='resultado_CFM2_']", function(){
    var id_CFM2_seleccionado = $(this).attr("id");
    valor_CFM2_seleccionado = $("#"+id_CFM2_seleccionado).val();
    fila_pertenece = $(this).data("fila_pertenece");
    id_tabla_pertenece = $(this).data("id_tabla_pertenece");
    nombre_tabla_pertenece = $(this).data("nombre_tabla_pertenece");
    $("#guardar_CFM2_fila_"+fila_pertenece).val(valor_CFM2_seleccionado);
});

$(document).on('change', "select[id^='resultado_FU_']", function(){
    var id_FU_seleccionado = $(this).attr("id");
    valor_FU_seleccionado = $("#"+id_FU_seleccionado).val();
    fila_pertenece = $(this).data("fila_pertenece");
    id_tabla_pertenece = $(this).data("id_tabla_pertenece");
    nombre_tabla_pertenece = $(this).data("nombre_tabla_pertenece");
    $("#guardar_FU_fila_"+fila_pertenece).val(valor_FU_seleccionado);
});

$(document).on('change', "select[id^='resultado_CAT_']", function(){
    var id_CAT_seleccionado = $(this).attr("id");
    valor_CAT_seleccionado = $("#"+id_CAT_seleccionado).val();
    fila_pertenece = $(this).data("fila_pertenece");
    id_tabla_pertenece = $(this).data("id_tabla_pertenece");
    nombre_tabla_pertenece = $(this).data("nombre_tabla_pertenece");
    $("#guardar_CAT_fila_"+fila_pertenece).val(valor_CAT_seleccionado);
});

setInterval(() => {
    // var nombre_tabla_seleccionada = sessionStorage.getItem("num_tabla");
    // var id_tabla_seleccionada = sessionStorage.getItem("id_tabla");

    // console.log("FILA A LA QUE PERTENECE: "+fila_pertenece);
    // console.log("ID TABLA: "+id_tabla_pertenece);
    // console.log("NOMBRE TABLA: "+nombre_tabla_pertenece);
    // console.log("FP: ", valor_FP_selecciondo);
    // console.log("CFM1: ", valor_CFM1_seleccionado);
    // console.log("CFM2: ", valor_CFM2_seleccionado);
    // console.log("FU: ", valor_FU_seleccionado);
    // console.log("CAT: ", valor_CAT_seleccionado);

    // calculosDeficienciasAlteracionesSistemas(fila_pertenece, id_tabla_pertenece, nombre_tabla_pertenece, valor_FP_selecciondo, valor_CFM1_seleccionado, valor_CFM2_seleccionado, valor_FU_seleccionado, valor_CAT_seleccionado);
    calculosDeficienciasAlteracionesSistemas(fila_pertenece, id_tabla_pertenece, nombre_tabla_pertenece);

}, 5000);

function calculosDeficienciasAlteracionesSistemas(id_fila_insertar_dato, id_tabla, tabla) {
    let token = $("input[name='_token']").val();
    /* Tabla 1.3 */
    switch (tabla) {
        case "Tabla 1.3":
            // Calculo del Ajuste
            var valor_FP_tabla_1_3 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_1_3 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_1_3 = parseInt(valor_CFM1_tabla_1_3) - parseInt(valor_FP_tabla_1_3);
            // Calculo del Literal
            var literal_tabla_1_3;
            if (ajuste_tabla_1_3 <= -1) {
                literal_tabla_1_3 = "A";
            }else if (ajuste_tabla_1_3 == 0) {
                literal_tabla_1_3 = "B";
            }else if (ajuste_tabla_1_3 >= 1) {
                literal_tabla_1_3 = "C";
            }
            // Calculo de la Clase Final
            var clase_final_tabla_1_3 = valor_FP_tabla_1_3+literal_tabla_1_3;
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_1_3) && literal_tabla_1_3 != undefined) {
                
                let datos_consulta_deficiencia_tabla_1_3 = {
                    '_token': token,
                    'columna': clase_final_tabla_1_3,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_1_3,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
  
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_1_3);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(response[0][clase_final_tabla_1_3]);
                    }         
                });
            }
        break;
  
        case "Tabla 2.1":
            // Calculo del Ajuste
            var valor_FP_tabla_2_1 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_2_1 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_2_1 = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_2_1 = (parseInt(valor_CFM1_tabla_2_1) - parseInt(valor_FP_tabla_2_1)) + (parseInt(valor_CFM2_tabla_2_1) - parseInt(valor_FP_tabla_2_1));
            // Calculo del Literal
            var literal_tabla_2_1;
  
            if (ajuste_tabla_2_1 <= -2) {
                literal_tabla_2_1 = "A";
            }else if (ajuste_tabla_2_1 == -1) {
                literal_tabla_2_1 = "B";
            }else if (ajuste_tabla_2_1 == 0) {
                literal_tabla_2_1 = "C";
            }else if(ajuste_tabla_2_1 == 1){
                literal_tabla_2_1 = "D";
            }
            else if(ajuste_tabla_2_1 >= 2){
                literal_tabla_2_1 = "E";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_2_1;
            if (parseInt(valor_FP_tabla_2_1) == 4 && parseInt(valor_CFM1_tabla_2_1) == 4 && parseInt(valor_CFM2_tabla_2_1) == 4) {
                clase_final_tabla_2_1 = "4E"; 
            }else{
                clase_final_tabla_2_1 = valor_FP_tabla_2_1+literal_tabla_2_1;
            }
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_2_1) && literal_tabla_2_1 != undefined) {
                
                let datos_consulta_deficiencia = {
                    '_token': token,
                    'columna': clase_final_tabla_2_1,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia,
                    success:function(response){
  
                        // console.log(id_fila_insertar_dato);
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
  
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_2_1);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(response[0][clase_final_tabla_2_1]);
                    }         
                });
            }
        break;
    
        default:
        break;
    }
    
};



