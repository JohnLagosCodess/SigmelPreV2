var valor_FP_selecciondo,
valor_CFM1_seleccionado,
valor_CFM2_seleccionado,
valor_FU_seleccionado,
valor_CAT_seleccionado,
fila_pertenece, id_tabla_pertenece, nombre_tabla_pertenece;
$(document).ready(function(){
    $(".centrar").css('text-align', 'center');

    if ($('#listado_deficiencia_porfactor').length > 0) {
        // Si existe, ejecutar el código
        /* GENERACIÓN DEL DATATABLE */
        var tabla_alteraciones_sistemas = $('#listado_deficiencia_porfactor').DataTable({
            "responsive": true,
            "info": false,
            "searching": false,
            "ordering": false,
            "scrollCollapse": true,
            "scrollX": true,
            "scrollY": "30vh",
            "paging": false,
            "language":{
                "emptyTable": "No se encontró información"
            }
        });
    
        /* FUNCIÓN PARA AJUSTAR COLUMNAS */
        autoAdjustColumns(tabla_alteraciones_sistemas);
        //tabla_alteraciones_sistemas.columns.adjust();
    
        /* REALIZAR LA INSERCIÓN DEL CONTENIDO EN LA FILA */
        var contador_alteraciones = 0;
        $("#btn_agregar_deficiencia_porfactor").click(function(){
            
            $('#guardar_datos_deficiencia_alteraciones').removeClass('d-none');
            contador_alteraciones = contador_alteraciones + 1;
            // 11
            var nueva_fila_alteraciones = [
              '<select id="listado_tablas_fila_alteraciones_'+contador_alteraciones+'" class="form-comtrol custom-select listado_tablas_fila_alteraciones_'+contador_alteraciones+'" name="ident_tabla"><option></option></select>',
              '<div style="width: 140px;" id="titulo_tabla_fila_alteraciones_'+contador_alteraciones+'"></div>',
              '<div style="width: 100px;" id="FP_fila_alteraciones_'+contador_alteraciones+'"></div>',
              '<div style="width: 100px;" id="CFM1_fila_alteraciones_'+contador_alteraciones+'"></div>',
              '<div style="width: 100px;" id="CFM2_fila_alteraciones_'+contador_alteraciones+'"></div>',
              '<div style="width: 100px;" id="FU_fila_alteraciones_'+contador_alteraciones+'"></div>',
              '<div style="width: 100px;" id="CAT_fila_alteraciones_'+contador_alteraciones+'"></div>',
              '<div style="width: 100px;" id="ClaseFinal_fila_alteraciones_'+contador_alteraciones+'"></div>',
              //   '<input type="checkbox" id="checkbox_dx_principal_DefiAlteraciones_'+contador_alteraciones+'" class="checkbox_dx_principal_DefiAlteraciones_'+contador_alteraciones+'" data-id_fila_checkbox_dx_principal_DefiAlteraciones="'+contador_alteraciones+'" style="transform: scale(1.2);">',
              '<div id="MSD_fila_alteraciones_'+contador_alteraciones+'"></div>',
              '<div id="Dominancia_fila_alteraciones_'+contador_alteraciones+'"></div>',
              '<div style="width: 100px;" id="Deficiencia_fila_alteraciones_'+contador_alteraciones+'"></div>',
              '<div id="Total_deficiencia_fila_alteraciones_'+contador_alteraciones+'"></div>',
              '<div style="text-align:center;"><a href="javascript:void(0);" id="btn_quitar_fila_alteraciones" class="text-info" data-fila="fila_alteraciones_'+contador_alteraciones+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
              'fila_alteraciones_'+contador_alteraciones
            ];
    
            var agregar_fila_alteraciones = tabla_alteraciones_sistemas.row.add(nueva_fila_alteraciones).draw().node();
            $(agregar_fila_alteraciones).addClass('fila_alteraciones_'+contador_alteraciones);
            $(agregar_fila_alteraciones).attr("id", 'fila_alteraciones_'+contador_alteraciones);
    
    
            // Esta función realiza los controles de cada elemento por fila
            funciones_elementos_fila_alteraciones(contador_alteraciones);
        });
    
        $(document).on('click', '#btn_quitar_fila_alteraciones', function(){
            var nombre_fila_alteraciones = $(this).data("fila");
            tabla_alteraciones_sistemas.row("."+nombre_fila_alteraciones).remove().draw();
        });
    
        $(document).on('click', "a[id^='btn_remover_deficiencia_alteraciones']", function(){
            var nombre_fila_alteraciones = $(this).data("clase_fila");
            tabla_alteraciones_sistemas.row("."+nombre_fila_alteraciones).remove().draw();
        });
    }

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

                        $("#FP_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input type="hidden" id="guardar_FP_fila_'+num_consecutivo_alteraciones+'">');
                        $("#FP_fila_alteraciones_"+num_consecutivo_alteraciones).append(select_FP);

                        /* SELECT 2 LISTADO FP */  
                        $(".resultado_FP_"+num_consecutivo_alteraciones).select2({
                            width: '100px',
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
        
                        $("#CFM1_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input type="hidden" id="guardar_CFM1_fila_'+num_consecutivo_alteraciones+'">');
                        $("#CFM1_fila_alteraciones_"+num_consecutivo_alteraciones).append(select_CFM1);
        
                        /* SELECT 2 LISTADO CFM1 */  
                        $(".resultado_CFM1_"+num_consecutivo_alteraciones).select2({
                            width: '100px',
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
        
                        $("#CFM2_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input type="hidden" id="guardar_CFM2_fila_'+num_consecutivo_alteraciones+'">');
                        $("#CFM2_fila_alteraciones_"+num_consecutivo_alteraciones).append(select_CFM2);
        
                        /* SELECT 2 LISTADO CFM2 */  
                        $(".resultado_CFM2_"+num_consecutivo_alteraciones).select2({
                            width: '100px',
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
        
                        $("#FU_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input type="hidden" id="guardar_FU_fila_'+num_consecutivo_alteraciones+'">');
                        $("#FU_fila_alteraciones_"+num_consecutivo_alteraciones).append(select_FU);
        
                        /* SELECT 2 LISTADO FU */  
                        $(".resultado_FU_"+num_consecutivo_alteraciones).select2({
                            width: '100px',
                            placeholder: "Seleccione",
                            allowClear: false
                        });

                        /* INSERTAR CAMPOS DE CLASE Y DEFICIENCIAS PARA LAS TABLAS ABIERTAS  13.2A hasta  la 13.7*/
                        if (id_tabla_seleccionado == 89 || id_tabla_seleccionado == 90 || id_tabla_seleccionado == 91 || id_tabla_seleccionado == 92 || id_tabla_seleccionado == 93 || 
                            id_tabla_seleccionado == 94 || id_tabla_seleccionado == 95 || id_tabla_seleccionado == 96 || id_tabla_seleccionado == 97){
                            let dominancias_sumas = 0.00;                  
                            $("#ClaseFinal_fila_alteraciones_"+num_consecutivo_alteraciones).empty();            
                            $("#Dominancia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
                            $("#Deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
                            $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
                            $("#Dominancia_fila_alteraciones_"+num_consecutivo_alteraciones).append(dominancias_sumas.toFixed(2));
                            $("#ClaseFinal_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input type="text" class="form-control" id="resultado_ClaseFinal_'+num_consecutivo_alteraciones+'">');
                              
                            setInterval(() => {
                                var valueresultado_FU = $("#guardar_FU_fila_"+num_consecutivo_alteraciones).val();   
                                // console.log(valueresultado_FU);
                                $("#Deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
                                $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();                                                                                                                        
                                let resultados_deficiencias = parseFloat(valueresultado_FU);
                                if (isNaN(resultados_deficiencias)) {
                                    $("#Deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append(dominancias_sumas.toFixed(2));
                                    $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append(dominancias_sumas.toFixed(2));                                  
                                } else {
                                    $("#Deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append(resultados_deficiencias.toFixed(2));
                                    $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append(resultados_deficiencias.toFixed(2));                    
                                }                                                                        
                            }, 250);
                            
                        };
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
        
                        $("#CAT_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input type="hidden" id="guardar_CAT_fila_'+num_consecutivo_alteraciones+'">');
                        $("#CAT_fila_alteraciones_"+num_consecutivo_alteraciones).append(select_CAT);
        
                        /* SELECT 2 LISTADO CAT */  
                        $(".resultado_CAT_"+num_consecutivo_alteraciones).select2({
                            width: '100px',
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
                        // Asignar evento de cambio al checkbox recién creado
                        $(".resultado_MSD_"+num_consecutivo_alteraciones).prop('disabled', true);
                        $(".resultado_MSD_"+num_consecutivo_alteraciones).change(ValidarMSD);
                        let MSD_checkeds = $("#resultado_MSD_"+num_consecutivo_alteraciones);
                        // IF TABLA 12.2 Y ELSE TABLAS 14
                        if (id_tabla_seleccionado == 65) {
                            $(".resultado_FU_"+num_consecutivo_alteraciones).change(function(){
                                $valorFU = $(this).val();
                                if ($valorFU == 80) {
                                    $(".resultado_MSD_"+num_consecutivo_alteraciones).addClass('d-none');
                                    $("#MSD_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
                                    $("#MSD_fila_alteraciones_"+num_consecutivo_alteraciones).append("N/A");
                                } else {
                                    $("#MSD_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
                                    $("#MSD_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input type="checkbox" class="resultado_MSD_'+num_consecutivo_alteraciones+'" id="resultado_MSD_'+num_consecutivo_alteraciones+'" style="transform: scale(1.2);">');
                                    $(".resultado_MSD_"+num_consecutivo_alteraciones).removeClass('d-none');
                                    setTimeout(function() {                                    
                                        let deficiencia_resultado = $("#Deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).text();
                                        // console.log(deficiencia_resultado);
                                        if (deficiencia_resultado == '') {                                
                                            $(".resultado_MSD_"+num_consecutivo_alteraciones).prop('checked', false);
                                            $(".resultado_MSD_"+num_consecutivo_alteraciones).prop('disabled', true);
                                            $("#Dominancia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
                                            $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();                
                                        } else {
                                            $(".resultado_MSD_"+num_consecutivo_alteraciones).prop('disabled', false);
                                            $("#Dominancia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
                                            $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();   
                                            ValidarMSD();
                                        }
                                    }, 2000);
                                }
                            })
                            function ValidarMSD() {   
                                // console.log(MSD_checkeds);             
                                let dominancia_suma = 0.00;
                                let deficienci_global = 0.2;
                                let resultado_Deficiencia = parseFloat($("#Deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).text());
                                if (MSD_checkeds.is(":checked")) {
                                    // console.log(resultado_Deficiencia);
                                    if (isNaN(resultado_Deficiencia)) {
                                        resultado_Deficiencia = dominancia_suma;
                                    }
                                    // console.log('No está marcado');  
                                    $("#Dominancia_fila_alteraciones_"+num_consecutivo_alteraciones).append(dominancia_suma.toFixed(2));
                                    $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append(resultado_Deficiencia.toFixed(2));                               
                                } else {
                                    if (isNaN(resultado_Deficiencia)) {
                                        resultado_Deficiencia = dominancia_suma;
                                    }
                                    // console.log('No está marcado');  
                                    $("#Dominancia_fila_alteraciones_"+num_consecutivo_alteraciones).append(dominancia_suma.toFixed(2));
                                    $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append(resultado_Deficiencia.toFixed(2));                                              
                                }                            
                            }
                            setInterval(() => {
                                $("#Dominancia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
                                $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();                                                    
                                ValidarMSD();
                            }, 250);
                        } else if(id_tabla_seleccionado == 100 || id_tabla_seleccionado == 101 || id_tabla_seleccionado == 102 || id_tabla_seleccionado == 103 ||
                            id_tabla_seleccionado == 104 || id_tabla_seleccionado == 105 || id_tabla_seleccionado == 106 || id_tabla_seleccionado == 107 ||
                            id_tabla_seleccionado == 108 || id_tabla_seleccionado == 109 ){
                            
                            $("#resultado_Deficiencia_"+num_consecutivo_alteraciones).change(function(){
                                deficiencia_resultado = $(this).val();
                                if (deficiencia_resultado == '') {                                
                                    $(".resultado_MSD_"+num_consecutivo_alteraciones).prop('checked', false);
                                    $(".resultado_MSD_"+num_consecutivo_alteraciones).prop('disabled', true);
                                    $("#Dominancia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
                                    $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();                
                                } else {
                                    $(".resultado_MSD_"+num_consecutivo_alteraciones).prop('disabled', false);                                                                
                                }
                            })
                            function ValidarMSD() {   
                                // console.log(MSD_checkeds);             
                                let dominancia_suma = 0.00;
                                let deficienci_global = 0.2;
                                let resultado_Deficiencia = parseFloat($("#resultado_Deficiencia_"+num_consecutivo_alteraciones).val());
                                if (MSD_checkeds.is(":checked")) {
                                    // console.log(resultado_Deficiencia);
                                    let nuevoValor = resultado_Deficiencia * deficienci_global;
                                    let a = resultado_Deficiencia;
                                    let b = nuevoValor;
                                    let resultadoMSD = a +((100 - a) * b / 100);
                                    // let dominancia = (resultadoMSD - a);
                                    let dominancia = nuevoValor;
                                    // console.log(dominancia);
                                    let total_deficiencia = resultadoMSD;
                                    // console.log(total_deficiencia);
                                    $("#Dominancia_fila_alteraciones_"+num_consecutivo_alteraciones).append(dominancia.toFixed(2));
                                    $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append(total_deficiencia.toFixed(2));                                
                                } else {
                                    if (isNaN(resultado_Deficiencia)) {
                                        resultado_Deficiencia = dominancia_suma;
                                    }
                                    // console.log('No está marcado');  
                                    $("#Dominancia_fila_alteraciones_"+num_consecutivo_alteraciones).append(dominancia_suma.toFixed(2));
                                    $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append(resultado_Deficiencia.toFixed(2));                                              
                                    // $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append(dominancia_suma.toFixed(2));
                                }                            
                            }
                            setInterval(() => {
                                $("#Dominancia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
                                $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();                                                    
                                ValidarMSD();
                            }, 250);
                        }
                    break;
                    default:
                        $("#MSD_fila_alteraciones_"+num_consecutivo_alteraciones).append('');
                    break;
                }
            }
        });      
       
        /* INSERTAR CAMPOS DE CLASE Y DEFICIENCIAS PARA LAS TABLAS 14.1 hasta la 14.5 y 14.7 ABIERTAS */
        if (id_tabla_seleccionado == 100 || id_tabla_seleccionado == 101 || id_tabla_seleccionado == 102 || id_tabla_seleccionado == 103 || 
            id_tabla_seleccionado == 104 || id_tabla_seleccionado == 105 || id_tabla_seleccionado == 106 || id_tabla_seleccionado == 107 || 
            id_tabla_seleccionado == 109 
            ) {
            $("#ClaseFinal_fila_alteraciones_"+num_consecutivo_alteraciones).empty();            
            $("#Dominancia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
            $("#Deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
            $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
            $("#ClaseFinal_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input type="text" class="form-control" id="resultado_ClaseFinal_'+num_consecutivo_alteraciones+'">');
            $("#Deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input type="number" class="form-control" id="resultado_Deficiencia_'+num_consecutivo_alteraciones+'">');
        };

        /* INSERTAR CAMPOS DE CLASE Y DEFICIENCIAS PARA LA TABLAS 14.6  ABIERTAS */
        if (id_tabla_seleccionado == 108) {
            $("#ClaseFinal_fila_alteraciones_"+num_consecutivo_alteraciones).empty();            
            $("#Dominancia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
            $("#Deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
            $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
            $("#ClaseFinal_fila_alteraciones_"+num_consecutivo_alteraciones).append('N/A');
            $("#Deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input type="number" class="form-control" id="resultado_Deficiencia_'+num_consecutivo_alteraciones+'">');
        };

        /* INSERTAR CAMPOS DE CLASE Y DEFICIENCIAS PARA LA TABLA ABIERTAS 6.3 */
        if (id_tabla_seleccionado == 37) {
            let dominancias_sumas = 0.00;                
            $("#ClaseFinal_fila_alteraciones_"+num_consecutivo_alteraciones).empty();            
            $("#Dominancia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
            // $("#Deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
            $("#Deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input type="number" class="form-control" id="resultado_Deficiencia_'+num_consecutivo_alteraciones+'">');
            $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
            $("#Dominancia_fila_alteraciones_"+num_consecutivo_alteraciones).append(dominancias_sumas.toFixed(2));
            $("#ClaseFinal_fila_alteraciones_"+num_consecutivo_alteraciones).append('<select id="resultado_Clase_'+num_consecutivo_alteraciones+'" class="custom-select resultado_Clase_'+num_consecutivo_alteraciones+'" data-fila_pertenece="'+num_consecutivo_alteraciones+'">');

            var selectTabla6_3 = $("#resultado_Clase_"+num_consecutivo_alteraciones);
            
            // nuevas Opciones
            var nuevasOpciones = [
                { value: '', text: 'Seleccione' },
                { value: 'A', text: 'A' },
                { value: 'AB', text: 'AB' },
                { value: 'B', text: 'B' },
            ];
            // Obtener las opciones existentes (si las hay)
            var opcionesExist = selectTabla6_3.children('option');

            // Filtrar las nuevas opciones para incluir solo las que no existen aún
            var nuevasOpcionesFiltradas = nuevasOpciones.filter(function (nuevaOpcion) {
                return !opcionesExist.filter(function () {
                return this.value === nuevaOpcion.value;
                }).length;
            });

            // Agregar solo las nuevas opciones al select
            nuevasOpcionesFiltradas.forEach(function (nuevaOpcion) {
                selectTabla6_3.append('<option value="' + nuevaOpcion.value + '">' + nuevaOpcion.text + '</option>');
            }); 

            /* SELECT 2 LISTADO CAT */  
            $(".resultado_Clase_"+num_consecutivo_alteraciones).select2({
                width: '100px',
                placeholder: "Seleccione",
                allowClear: false
            });

            var value_selectTabla6_3 = $("#resultado_Clase_"+num_consecutivo_alteraciones);
            let value_Tabla6_3_A = 25.00;
            let value_Tabla6_3_B = 50.00;
            value_selectTabla6_3.change(function (){
                var value_Tabla6_3 = $(this).val();
                // $("#resultado_Deficiencia_"+num_consecutivo_alteraciones).empty();
                // $("#Deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();                
                clearInterval(intervalo_B);
                clearInterval(intervalo_opc_a);
                clearInterval(intervalo_opc_ab);

                // console.log(value_Tabla6_3);
                if (value_Tabla6_3 == 'A') {
                    // $("#Deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
                    // $("#Deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append(value_Tabla6_3_A.toFixed(2));                                                    
                    $("#resultado_Deficiencia_"+num_consecutivo_alteraciones).val(value_Tabla6_3_A.toFixed(2));
                    var intervalo_opc_a = setInterval(() => {
                        $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
                        let resultados_deficiencias = parseFloat($("#resultado_Deficiencia_"+num_consecutivo_alteraciones).val());
                        if (isNaN(resultados_deficiencias)) {
                            $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append(dominancias_sumas.toFixed(2));                                  
                        }else{
                            $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append(resultados_deficiencias.toFixed(2));                                        
                        }
                    }, 250);                                        
                }else if(value_Tabla6_3 == 'AB'){
                    // $("#Deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
                    // $("#Deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append(value_Tabla6_3_B.toFixed(2));                                                    
                    $("#resultado_Deficiencia_"+num_consecutivo_alteraciones).val(value_Tabla6_3_B.toFixed(2));

                    var intervalo_opc_ab = setInterval(() => {
                        $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();                
                        let resultados_deficiencias = parseFloat($("#resultado_Deficiencia_"+num_consecutivo_alteraciones).val());
                        if (isNaN(resultados_deficiencias)) {
                            $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append(dominancias_sumas.toFixed(2));                                  
                        }else{
                            $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append(resultados_deficiencias.toFixed(2));                    
                        }
                    }, 250);                    
                } 
                
                if(value_Tabla6_3 == 'B'){
                    // $("#Deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input type="number" class="form-control" id="resultado_Deficiencia_'+num_consecutivo_alteraciones+'">');                                                    
                    $("#resultado_Deficiencia_"+num_consecutivo_alteraciones).empty();                                                                                                                                               
                    var intervalo_B = setInterval(() => {
                        $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();                                                                                                                        
                        let resultados_deficiencias = parseFloat($("#resultado_Deficiencia_"+num_consecutivo_alteraciones).val());
                        if (isNaN(resultados_deficiencias)) {
                            $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append(dominancias_sumas.toFixed(2));                                  
                        } else {
                            $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append(resultados_deficiencias.toFixed(2));                    
                        } 
                    }, 250);
                }                    
                
            })
            
        };

        /* INSERTAR CAMPOS DE CLASE Y DEFICIENCIAS PARA LA TABLA ABIERTAS 12.10 */
        if (id_tabla_seleccionado == 75) {
            let dominancias_sumas = 0.00;                
            $("#ClaseFinal_fila_alteraciones_"+num_consecutivo_alteraciones).empty();            
            $("#Dominancia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
            $("#Deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
            $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
            $("#Dominancia_fila_alteraciones_"+num_consecutivo_alteraciones).append(dominancias_sumas.toFixed(2));
            $("#ClaseFinal_fila_alteraciones_"+num_consecutivo_alteraciones).append('');
            $("#Deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input type="number" class="form-control" id="resultado_Deficiencia_'+num_consecutivo_alteraciones+'">');            
            setInterval(() => {
                $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();                                                                                                                        
                let resultados_deficiencias = parseFloat($("#resultado_Deficiencia_"+num_consecutivo_alteraciones).val());
                if (isNaN(resultados_deficiencias)) {
                    $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append(dominancias_sumas.toFixed(2));                                  
                } else {
                    $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append(resultados_deficiencias.toFixed(2));                    
                } 
            }, 250);
        };

        /* INSERTAR CAMPOS DE CLASE Y DEFICIENCIAS PARA LA TABLA 14.14 ABIERTAS */
        if (id_tabla_seleccionado == 116) {
            let dominancias_sumas = 0.00;                
            $("#ClaseFinal_fila_alteraciones_"+num_consecutivo_alteraciones).empty();            
            $("#Dominancia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
            $("#Deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
            $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
            $("#Dominancia_fila_alteraciones_"+num_consecutivo_alteraciones).append(dominancias_sumas.toFixed(2));
            $("#ClaseFinal_fila_alteraciones_"+num_consecutivo_alteraciones).append('N/A');
            $("#Deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input type="number" class="form-control" id="resultado_Deficiencia_'+num_consecutivo_alteraciones+'">');            
            setInterval(() => {
                $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();                                                                                                                        
                let resultados_deficiencias = parseFloat($("#resultado_Deficiencia_"+num_consecutivo_alteraciones).val());
                if (isNaN(resultados_deficiencias)) {
                    $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append(dominancias_sumas.toFixed(2));                                  
                } else {
                    $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append(resultados_deficiencias.toFixed(2));                    
                } 
            }, 250);
        };

        /* INSERTAR CAMPOS DE CLASE Y DEFICIENCIAS PARA LAS TABLAS ABIERTAS */
        if (id_tabla_seleccionado == 58 || id_tabla_seleccionado == 59 || id_tabla_seleccionado == 61 || id_tabla_seleccionado == 76 || id_tabla_seleccionado == 77 || 
            id_tabla_seleccionado == 78 || id_tabla_seleccionado == 79 || id_tabla_seleccionado == 80 || id_tabla_seleccionado == 81 || id_tabla_seleccionado == 82 || 
            id_tabla_seleccionado == 99 || id_tabla_seleccionado == 110 || id_tabla_seleccionado == 111 || id_tabla_seleccionado == 112 || id_tabla_seleccionado == 113 || 
            id_tabla_seleccionado == 114 || id_tabla_seleccionado == 115 || id_tabla_seleccionado == 128
            ) {
            let dominancias_sumas = 0.00;                
            $("#ClaseFinal_fila_alteraciones_"+num_consecutivo_alteraciones).empty();            
            $("#Dominancia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
            $("#Deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
            $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();
            $("#Dominancia_fila_alteraciones_"+num_consecutivo_alteraciones).append(dominancias_sumas.toFixed(2));
            $("#ClaseFinal_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input type="text" class="form-control" id="resultado_ClaseFinal_'+num_consecutivo_alteraciones+'">');
            $("#Deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append('<input type="number" class="form-control" id="resultado_Deficiencia_'+num_consecutivo_alteraciones+'">');            
            setInterval(() => {
                $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).empty();                                                                                                                        
                let resultados_deficiencias = parseFloat($("#resultado_Deficiencia_"+num_consecutivo_alteraciones).val());
                if (isNaN(resultados_deficiencias)) {
                    $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append(dominancias_sumas.toFixed(2));                                  
                } else {
                    $("#Total_deficiencia_fila_alteraciones_"+num_consecutivo_alteraciones).append(resultados_deficiencias.toFixed(2));                    
                } 
            }, 250);
        };
                
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

}, 500);

function calculosDeficienciasAlteracionesSistemas(id_fila_insertar_dato, id_tabla, tabla) {
    let token = $("input[name='_token']").val();    
    let dominancia_suma = 0.00;    
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
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();  
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();                        
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_1_3);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_1_3]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_1_3]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
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
                
                let datos_consulta_deficiencia_tabla_2_1 = {
                    '_token': token,
                    'columna': clase_final_tabla_2_1,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_2_1,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty(); 
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_2_1);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_2_1]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_2_1]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 2.2":
            // Calculo del Ajuste
            var valor_FP_tabla_2_2 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_2_2 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_2_2 = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_2_2 = (parseInt(valor_CFM1_tabla_2_2) - parseInt(valor_FP_tabla_2_2)) + (parseInt(valor_CFM2_tabla_2_2) - parseInt(valor_FP_tabla_2_2));
            // Calculo del Literal
            var literal_tabla_2_2;
            
            if (ajuste_tabla_2_2 <= -2) {
                literal_tabla_2_2 = "A";
            }else if (ajuste_tabla_2_2 == -1) {
                literal_tabla_2_2 = "B";
            }else if (ajuste_tabla_2_2 == 0) {
                literal_tabla_2_2 = "C";
            }else if(ajuste_tabla_2_2 == 1){
                literal_tabla_2_2 = "D";
            }
            else if(ajuste_tabla_2_2 >= 2){
                literal_tabla_2_2 = "E";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_2_2;
            if (parseInt(valor_FP_tabla_2_2) == 4 && parseInt(valor_CFM1_tabla_2_2) == 4 && parseInt(valor_CFM2_tabla_2_2) == 4) {
                clase_final_tabla_2_2 = "4E"; 
            }else{
                clase_final_tabla_2_2 = valor_FP_tabla_2_2+literal_tabla_2_2;
            }
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_2_2) && literal_tabla_2_2 != undefined) {
                
                let datos_consulta_deficiencia_tabla_2_2 = {
                    '_token': token,
                    'columna': clase_final_tabla_2_2,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_2_2,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_2_2);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_2_2]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_2_2]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 2.3":
            // Calculo del Ajuste
            var valor_FP_tabla_2_3 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_2_3 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_2_3 = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_2_3 = (parseInt(valor_CFM1_tabla_2_3) - parseInt(valor_FP_tabla_2_3)) + (parseInt(valor_CFM2_tabla_2_3) - parseInt(valor_FP_tabla_2_3));
            // Calculo del Literal
            var literal_tabla_2_3;
            
            if (ajuste_tabla_2_3 <= -2) {
                literal_tabla_2_3 = "A";
            }else if (ajuste_tabla_2_3 == -1) {
                literal_tabla_2_3 = "B";
            }else if (ajuste_tabla_2_3 == 0) {
                literal_tabla_2_3 = "C";
            }else if(ajuste_tabla_2_3 == 1){
                literal_tabla_2_3 = "D";
            }
            else if(ajuste_tabla_2_3 >= 2){
                literal_tabla_2_3 = "E";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_2_3;
            if (parseInt(valor_FP_tabla_2_3) == 4 && parseInt(valor_CFM1_tabla_2_3) == 4 && parseInt(valor_CFM2_tabla_2_3) == 4) {
                clase_final_tabla_2_3 = "4E"; 
            }else{
                clase_final_tabla_2_3 = valor_FP_tabla_2_3+literal_tabla_2_3;
            }
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_2_3) && literal_tabla_2_3 != undefined) {
                
                let datos_consulta_deficiencia_tabla_2_3 = {
                    '_token': token,
                    'columna': clase_final_tabla_2_3,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_2_3,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_2_3);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_2_3]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_2_3]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 2.4":
            // Calculo del Ajuste
            var valor_FP_tabla_2_4 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_2_4 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_2_4 = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_2_4 = (parseInt(valor_CFM1_tabla_2_4) - parseInt(valor_FP_tabla_2_4)) + (parseInt(valor_CFM2_tabla_2_4) - parseInt(valor_FP_tabla_2_4));
            // Calculo del Literal
            var literal_tabla_2_4;
            
            if (ajuste_tabla_2_4 <= -2) {
                literal_tabla_2_4 = "A";
            }else if (ajuste_tabla_2_4 == -1) {
                literal_tabla_2_4 = "B";
            }else if (ajuste_tabla_2_4 == 0) {
                literal_tabla_2_4 = "C";
            }else if(ajuste_tabla_2_4 == 1){
                literal_tabla_2_4 = "D";
            }
            else if(ajuste_tabla_2_4 >= 2){
                literal_tabla_2_4 = "E";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_2_4;
            if (parseInt(valor_FP_tabla_2_4) == 4 && parseInt(valor_CFM1_tabla_2_4) == 4 && parseInt(valor_CFM2_tabla_2_4) == 4) {
                clase_final_tabla_2_4 = "4E"; 
            }else{
                clase_final_tabla_2_4 = valor_FP_tabla_2_4+literal_tabla_2_4;
            }
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_2_4) && literal_tabla_2_4 != undefined) {
                
                let datos_consulta_deficiencia_tabla_2_4 = {
                    '_token': token,
                    'columna': clase_final_tabla_2_4,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_2_4,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_2_4);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_2_4]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_2_4]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 2.5":
            // Calculo del Ajuste
            var valor_FP_tabla_2_5 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_2_5 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_2_5 = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_2_5 = (parseInt(valor_CFM1_tabla_2_5) - parseInt(valor_FP_tabla_2_5)) + (parseInt(valor_CFM2_tabla_2_5) - parseInt(valor_FP_tabla_2_5));
            // Calculo del Literal
            var literal_tabla_2_5;
            
            if (ajuste_tabla_2_5 <= -2) {
                literal_tabla_2_5 = "A";
            }else if (ajuste_tabla_2_5 == -1) {
                literal_tabla_2_5 = "B";
            }else if (ajuste_tabla_2_5 == 0) {
                literal_tabla_2_5 = "C";
            }else if(ajuste_tabla_2_5 == 1){
                literal_tabla_2_5 = "D";
            }
            else if(ajuste_tabla_2_5 >= 2){
                literal_tabla_2_5 = "E";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_2_5;
            if (parseInt(valor_FP_tabla_2_5) == 4 && parseInt(valor_CFM1_tabla_2_5) == 4 && parseInt(valor_CFM2_tabla_2_5) == 4) {
                clase_final_tabla_2_5 = "4E"; 
            }else{
                clase_final_tabla_2_5 = valor_FP_tabla_2_5+literal_tabla_2_5;
            }
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_2_5) && literal_tabla_2_5 != undefined) {
                
                let datos_consulta_deficiencia_tabla_2_5 = {
                    '_token': token,
                    'columna': clase_final_tabla_2_5,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_2_5,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_2_5);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_2_5]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_2_5]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 2.6":
            // Calculo del Ajuste
            var valor_FP_tabla_2_6 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_2_6 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_2_6 = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_2_6 = (parseInt(valor_CFM1_tabla_2_6) - parseInt(valor_FP_tabla_2_6)) + (parseInt(valor_CFM2_tabla_2_6) - parseInt(valor_FP_tabla_2_6));
            // Calculo del Literal
            var literal_tabla_2_6;
            
            if (ajuste_tabla_2_6 <= -2) {
                literal_tabla_2_6 = "A";
            }else if (ajuste_tabla_2_6 == -1) {
                literal_tabla_2_6 = "B";
            }else if (ajuste_tabla_2_6 == 0) {
                literal_tabla_2_6 = "C";
            }else if(ajuste_tabla_2_6 == 1){
                literal_tabla_2_6 = "D";
            }
            else if(ajuste_tabla_2_6 >= 2){
                literal_tabla_2_6 = "E";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_2_6;
            if (parseInt(valor_FP_tabla_2_6) == 4 && parseInt(valor_CFM1_tabla_2_6) == 4 && parseInt(valor_CFM2_tabla_2_6) == 4) {
                clase_final_tabla_2_6 = "4E"; 
            }else{
                clase_final_tabla_2_6 = valor_FP_tabla_2_6+literal_tabla_2_6;
            }
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_2_6) && literal_tabla_2_6 != undefined) {
                
                let datos_consulta_deficiencia_tabla_2_6 = {
                    '_token': token,
                    'columna': clase_final_tabla_2_6,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_2_6,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_2_6);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_2_6]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_2_6]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 2.7":
            // Calculo del Ajuste
            var valor_FP_tabla_2_7 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_2_7 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_2_7 = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_2_7 = (parseInt(valor_CFM1_tabla_2_7) - parseInt(valor_FP_tabla_2_7)) + (parseInt(valor_CFM2_tabla_2_7) - parseInt(valor_FP_tabla_2_7));
            // Calculo del Literal
            var literal_tabla_2_7;
            
            if (ajuste_tabla_2_7 <= -2) {
                literal_tabla_2_7 = "A";
            }else if (ajuste_tabla_2_7 == -1) {
                literal_tabla_2_7 = "B";
            }else if (ajuste_tabla_2_7 == 0) {
                literal_tabla_2_7 = "C";
            }else if(ajuste_tabla_2_7 == 1){
                literal_tabla_2_7 = "D";
            }
            else if(ajuste_tabla_2_7 >= 2){
                literal_tabla_2_7 = "E";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_2_7;
            if (parseInt(valor_FP_tabla_2_7) == 4 && parseInt(valor_CFM1_tabla_2_7) == 4 && parseInt(valor_CFM2_tabla_2_7) == 4) {
                clase_final_tabla_2_7 = "4E"; 
            }else{
                clase_final_tabla_2_7 = valor_FP_tabla_2_7+literal_tabla_2_7;
            }
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_2_7) && literal_tabla_2_7 != undefined) {
                
                let datos_consulta_deficiencia_tabla_2_7 = {
                    '_token': token,
                    'columna': clase_final_tabla_2_7,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_2_7,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_2_7);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_2_7]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_2_7]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 2.8":
            // Calculo del Ajuste
            var valor_FP_tabla_2_8 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_2_8 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_2_8 = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_2_8 = (parseInt(valor_CFM1_tabla_2_8) - parseInt(valor_FP_tabla_2_8)) + (parseInt(valor_CFM2_tabla_2_8) - parseInt(valor_FP_tabla_2_8));
            // Calculo del Literal
            var literal_tabla_2_8;
            
            if (ajuste_tabla_2_8 <= -2) {
                literal_tabla_2_8 = "A";
            }else if (ajuste_tabla_2_8 == -1) {
                literal_tabla_2_8 = "B";
            }else if (ajuste_tabla_2_8 == 0) {
                literal_tabla_2_8 = "C";
            }else if(ajuste_tabla_2_8 == 1){
                literal_tabla_2_8 = "D";
            }
            else if(ajuste_tabla_2_8 >= 2){
                literal_tabla_2_8 = "E";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_2_8;
            if (parseInt(valor_FP_tabla_2_8) == 4 && parseInt(valor_CFM1_tabla_2_8) == 4 && parseInt(valor_CFM2_tabla_2_8) == 4) {
                clase_final_tabla_2_8 = "4E"; 
            }else{
                clase_final_tabla_2_8 = valor_FP_tabla_2_8+literal_tabla_2_8;
            }
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_2_8) && literal_tabla_2_8 != undefined) {
                
                let datos_consulta_deficiencia_tabla_2_8 = {
                    '_token': token,
                    'columna': clase_final_tabla_2_8,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_2_8,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_2_8);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_2_8]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_2_8]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 2.9":
            // Calculo del Ajuste
            var valor_FP_tabla_2_9 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_2_9 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_2_9 = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_2_9 = (parseInt(valor_CFM1_tabla_2_9) - parseInt(valor_FP_tabla_2_9)) + (parseInt(valor_CFM2_tabla_2_9) - parseInt(valor_FP_tabla_2_9));
            // Calculo del Literal
            var literal_tabla_2_9;
            
            if (ajuste_tabla_2_9 <= -2) {
                literal_tabla_2_9 = "A";
            }else if (ajuste_tabla_2_9 == -1) {
                literal_tabla_2_9 = "B";
            }else if (ajuste_tabla_2_9 == 0) {
                literal_tabla_2_9 = "C";
            }else if(ajuste_tabla_2_9 == 1){
                literal_tabla_2_9 = "D";
            }
            else if(ajuste_tabla_2_9 >= 2){
                literal_tabla_2_9 = "E";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_2_9;
            if (parseInt(valor_FP_tabla_2_9) == 4 && parseInt(valor_CFM1_tabla_2_9) == 4 && parseInt(valor_CFM2_tabla_2_9) == 4) {
                clase_final_tabla_2_9 = "4E"; 
            }else{
                clase_final_tabla_2_9 = valor_FP_tabla_2_9+literal_tabla_2_9;
            }
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_2_9) && literal_tabla_2_9 != undefined) {
                
                let datos_consulta_deficiencia_tabla_2_9 = {
                    '_token': token,
                    'columna': clase_final_tabla_2_9,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_2_9,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_2_9);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_2_9]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_2_9]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 3.2":
            // Calculo del Ajuste
            var valor_FP_tabla_3_2 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_3_2 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_3_2 = parseInt(valor_CFM1_tabla_3_2) - parseInt(valor_FP_tabla_3_2);
            // Calculo del Literal
            var literal_tabla_3_2;
            
            if (ajuste_tabla_3_2 <= -1) {
                literal_tabla_3_2 = "A";
            }else if (ajuste_tabla_3_2 == 0) {
                literal_tabla_3_2 = "B";
            }else if (ajuste_tabla_3_2 >= 1) {
                literal_tabla_3_2 = "C";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_3_2 = valor_FP_tabla_3_2+literal_tabla_3_2;
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_3_2) && literal_tabla_3_2 != undefined) {
                
                let datos_consulta_deficiencia_tabla_3_2 = {
                    '_token': token,
                    'columna': clase_final_tabla_3_2,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_3_2,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_3_2);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_3_2]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_3_2]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 3.3":
            // Calculo del Ajuste
            var valor_FP_tabla_3_3 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_3_3 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_3_3 = parseInt(valor_CFM1_tabla_3_3) - parseInt(valor_FP_tabla_3_3);
            // Calculo del Literal
            var literal_tabla_3_3;
            
            if (ajuste_tabla_3_3 <= -1) {
                literal_tabla_3_3 = "A";
            }else if (ajuste_tabla_3_3 == 0) {
                literal_tabla_3_3 = "B";
            }else if (ajuste_tabla_3_3 >= 1) {
                literal_tabla_3_3 = "C";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_3_3 = valor_FP_tabla_3_3+literal_tabla_3_3;
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_3_3) && literal_tabla_3_3 != undefined) {
                
                let datos_consulta_deficiencia_tabla_3_3 = {
                    '_token': token,
                    'columna': clase_final_tabla_3_3,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_3_3,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_3_3);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_3_3]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_3_3]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 3.4":
            // Calculo del Ajuste
            var valor_FP_tabla_3_4 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_3_4 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_3_4 = parseInt(valor_CFM1_tabla_3_4) - parseInt(valor_FP_tabla_3_4);
            // Calculo del Literal
            var literal_tabla_3_4;
            
            if (ajuste_tabla_3_4 <= -1) {
                literal_tabla_3_4 = "A";
            }else if (ajuste_tabla_3_4 == 0) {
                literal_tabla_3_4 = "B";
            }else if (ajuste_tabla_3_4 >= 1) {
                literal_tabla_3_4 = "C";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_3_4 = valor_FP_tabla_3_4+literal_tabla_3_4;
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_3_4) && literal_tabla_3_4 != undefined) {
                
                let datos_consulta_deficiencia_tabla_3_4 = {
                    '_token': token,
                    'columna': clase_final_tabla_3_4,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_3_4,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_3_4);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_3_4]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_3_4]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 4.5":
            // Calculo del Ajuste
            var valor_FP_tabla_4_5 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_4_5 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_4_5 = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_4_5 = (parseInt(valor_CFM1_tabla_4_5) - parseInt(valor_FP_tabla_4_5)) + (parseInt(valor_CFM2_tabla_4_5) - parseInt(valor_FP_tabla_4_5));
            // Calculo del Literal
            var literal_tabla_4_5;
            
            if (ajuste_tabla_4_5 <= -2) {
                literal_tabla_4_5 = "A";
            }else if (ajuste_tabla_4_5 == -1) {
                literal_tabla_4_5 = "B";
            }else if (ajuste_tabla_4_5 == 0) {
                literal_tabla_4_5 = "C";
            }else if(ajuste_tabla_4_5 == 1){
                literal_tabla_4_5 = "D";
            }
            else if(ajuste_tabla_4_5 >= 2){
                literal_tabla_4_5 = "E";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_4_5 = valor_FP_tabla_4_5+literal_tabla_4_5;
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_4_5) && literal_tabla_4_5 != undefined) {
                
                let datos_consulta_deficiencia_tabla_4_5 = {
                    '_token': token,
                    'columna': clase_final_tabla_4_5,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_4_5,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_4_5);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_4_5]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_4_5]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 4.6":
            // Calculo del Ajuste
            var valor_FP_tabla_4_6 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_4_6 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_4_6 = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_4_6 = (parseInt(valor_CFM1_tabla_4_6) - parseInt(valor_FP_tabla_4_6)) + (parseInt(valor_CFM2_tabla_4_6) - parseInt(valor_FP_tabla_4_6));
            // Calculo del Literal
            var literal_tabla_4_6;
            
            if (ajuste_tabla_4_6 <= -2) {
                literal_tabla_4_6 = "A";
            }else if (ajuste_tabla_4_6 == -1) {
                literal_tabla_4_6 = "B";
            }else if (ajuste_tabla_4_6 == 0) {
                literal_tabla_4_6 = "C";
            }else if(ajuste_tabla_4_6 == 1){
                literal_tabla_4_6 = "D";
            }
            else if(ajuste_tabla_4_6 >= 2){
                literal_tabla_4_6 = "E";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_4_6;
            if (parseInt(valor_FP_tabla_4_6) == 4 && parseInt(valor_CFM1_tabla_4_6) == 4 && parseInt(valor_CFM2_tabla_4_6) == 4) {
                clase_final_tabla_4_6 = valor_FP_tabla_4_6+"E"; 
            }else if(parseInt(valor_FP_tabla_4_6) == 4 && parseInt(valor_CFM1_tabla_4_6) == 4){
                clase_final_tabla_4_6 = valor_FP_tabla_4_6+"A"; 
            }else if(parseInt(valor_FP_tabla_4_6) == 4 && parseInt(valor_CFM2_tabla_4_6) == 4){
                clase_final_tabla_4_6 = valor_FP_tabla_4_6+"A"; 
            }
            else{
                clase_final_tabla_4_6 = valor_FP_tabla_4_6+literal_tabla_4_6;
            }
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_4_6) && literal_tabla_4_6 != undefined) {
                
                let datos_consulta_deficiencia_tabla_4_6 = {
                    '_token': token,
                    'columna': clase_final_tabla_4_6,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_4_6,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_4_6);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_4_6]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_4_6]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 4.7":
            // Calculo del Ajuste
            var valor_FP_tabla_4_7 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_4_7 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_4_7 = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_4_7 = (parseInt(valor_CFM1_tabla_4_7) - parseInt(valor_FP_tabla_4_7)) + (parseInt(valor_CFM2_tabla_4_7) - parseInt(valor_FP_tabla_4_7));
            // Calculo del Literal
            var literal_tabla_4_7;
            
            if (ajuste_tabla_4_7 <= -2) {
                literal_tabla_4_7 = "A";
            }else if (ajuste_tabla_4_7 == -1) {
                literal_tabla_4_7 = "B";
            }else if (ajuste_tabla_4_7 == 0) {
                literal_tabla_4_7 = "C";
            }else if(ajuste_tabla_4_7 == 1){
                literal_tabla_4_7 = "D";
            }
            else if(ajuste_tabla_4_7 >= 2){
                literal_tabla_4_7 = "E";
            }

            // Calculo de la Clase Final
            var clase_final_tabla_4_7;
            if (parseInt(valor_CFM1_tabla_4_7) == 4 || parseInt(valor_CFM2_tabla_4_7) == 4) {
                clase_final_tabla_4_7 = valor_FP_tabla_4_7+"E"; 
                //clase_final_tabla_4_7 = "4E"; 
            }else{
                clase_final_tabla_4_7 = valor_FP_tabla_4_7+literal_tabla_4_7;
            }
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_4_7) && literal_tabla_4_7 != undefined) {
                
                let datos_consulta_deficiencia_tabla_4_7 = {
                    '_token': token,
                    'columna': clase_final_tabla_4_7,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_4_7,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_4_7);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_4_7]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_4_7]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 4.8":
            // Calculo del Ajuste
            var valor_FP_tabla_4_8 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_4_8 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_4_8 = parseInt(valor_CFM1_tabla_4_8) - parseInt(valor_FP_tabla_4_8);
            // Calculo del Literal
            var literal_tabla_4_8;
            
            if (ajuste_tabla_4_8 <= -1) {
                literal_tabla_4_8 = "A";
            }else if (ajuste_tabla_4_8 == 0) {
                literal_tabla_4_8 = "B";
            }else if (ajuste_tabla_4_8 >= 1) {
                literal_tabla_4_8 = "C";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_4_8;
            
            clase_final_tabla_4_8 = valor_FP_tabla_4_8+literal_tabla_4_8;
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_4_8) && literal_tabla_4_8 != undefined) {
                
                let datos_consulta_deficiencia_tabla_4_8 = {
                    '_token': token,
                    'columna': clase_final_tabla_4_8,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_4_8,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_4_8);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_4_8]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_4_8]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 4.9":
            // deficiencia
            var valor_FU_tabla_4_9 = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

            // Calculo clase final
            var clase_final_tabla_4_9;
            if (valor_FU_tabla_4_9 == 20 || valor_FU_tabla_4_9 == 30) {
                clase_final_tabla_4_9 = "";
            }

            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_4_9);
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            let deficiencias_4_9 = parseFloat(valor_FU_tabla_4_9);
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias_4_9.toFixed(2));
            let suma_total_deficiencias_4_9 = parseFloat(valor_FU_tabla_4_9) + dominancia_suma;
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias_4_9.toFixed(2));

            // let datos_consulta_deficiencia_tabla_4_9 = {
            //     '_token': token,
            //     'columna': clase_final_tabla_4_9,
            //     'Id_tabla': id_tabla
            // };
            // $.ajax({
            //     url: "/consultaValorDeficiencia",
            //     type: "post",
            //     data: datos_consulta_deficiencia_tabla_4_9,
            //     success:function(response){
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_4_9);
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            //         let deficiencias = parseFloat(response[0][clase_final_tabla_4_9]);
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
            //         let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_4_9]) + dominancia_suma;
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
            //     }         
            // });

        break;
        
        case "Tabla 4.10":
            // Calculo del Ajuste
            var valor_FP_tabla_4_10 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_4_10 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_4_10 = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_4_10 = (parseInt(valor_CFM1_tabla_4_10) - parseInt(valor_FP_tabla_4_10)) + (parseInt(valor_CFM2_tabla_4_10) - parseInt(valor_FP_tabla_4_10));
            // Calculo del Literal
            var literal_tabla_4_10;

            if (ajuste_tabla_4_10 <= -1) {
                literal_tabla_4_10 = "A";
            }else if (ajuste_tabla_4_10 == 0) {
                literal_tabla_4_10 = "B";
            }else if (ajuste_tabla_4_10 >= 1) {
                literal_tabla_4_10 = "C";
            }
            
            // if (ajuste_tabla_4_10 <= -2) {
            //     literal_tabla_4_10 = "A";
            // }else if (ajuste_tabla_4_10 == -1) {
            //     literal_tabla_4_10 = "B";
            // }else if (ajuste_tabla_4_10 == 0) {
            //     literal_tabla_4_10 = "C";
            // }else if(ajuste_tabla_4_10 == 1){
            //     literal_tabla_4_10 = "D";
            // }
            // else if(ajuste_tabla_4_10 >= 2){
            //     literal_tabla_4_10 = "E";
            // }
  
            // Calculo de la Clase Final
            var clase_final_tabla_4_10;
            
            // Si los FM=4 se toma el valor más alto dentro de la clase

            if (parseInt(valor_CFM1_tabla_4_10) == 4 && parseInt(valor_CFM2_tabla_4_10) == 4) {
                clase_final_tabla_4_10 = valor_FP_tabla_4_10+"C";                 
            }
            else{
                clase_final_tabla_4_10 = valor_FP_tabla_4_10+literal_tabla_4_10;
            }
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_4_10) && literal_tabla_4_10 != undefined) {
                
                let datos_consulta_deficiencia_tabla_4_10 = {
                    '_token': token,
                    'columna': clase_final_tabla_4_10,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_4_10,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_4_10);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_4_10]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_4_10]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 4.11":
            // Calculo del Ajuste
            var valor_FP_tabla_4_11 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_4_11 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_4_11 = parseInt(valor_CFM1_tabla_4_11) - parseInt(valor_FP_tabla_4_11);
            // Calculo del Literal
            var literal_tabla_4_11;
            
            if (ajuste_tabla_4_11 <= -1) {
                literal_tabla_4_11 = "A";
            }else if (ajuste_tabla_4_11 == 0) {
                literal_tabla_4_11 = "B";
            }else if (ajuste_tabla_4_11 >= 1) {
                literal_tabla_4_11 = "C";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_4_11;
            
            clase_final_tabla_4_11 = valor_FP_tabla_4_11+literal_tabla_4_11;
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_4_11) && literal_tabla_4_11 != undefined) {
                
                let datos_consulta_deficiencia_tabla_4_11 = {
                    '_token': token,
                    'columna': clase_final_tabla_4_11,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_4_11,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_4_11);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_4_11]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_4_11]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 4.12":
            // deficiencia
            var valor_FU_tabla_4_12 = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

            // Calculo clase final
            var clase_final_tabla_4_12;

            if (valor_FU_tabla_4_12 == 5) {
                clase_final_tabla_4_12 = "1A";
            }else if(valor_FU_tabla_4_12 == 10){
                clase_final_tabla_4_12 = "1B";
            }else if(valor_FU_tabla_4_12 == 15){
                clase_final_tabla_4_12 = "1C";
            }else if(valor_FU_tabla_4_12 == 20){
                clase_final_tabla_4_12 = "1D";
            }else if(valor_FU_tabla_4_12 == 40){
                clase_final_tabla_4_12 = "1E";
            }else if(valor_FU_tabla_4_12 == 50){
                clase_final_tabla_4_12 = "2A";
            }
            

            let datos_consulta_deficiencia_tabla_4_12 = {
                '_token': token,
                'columna': clase_final_tabla_4_12,
                'Id_tabla': id_tabla
            };
            $.ajax({
                url: "/consultaValorDeficiencia",
                type: "post",
                data: datos_consulta_deficiencia_tabla_4_12,
                success:function(response){
                    $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                    $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                    $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                    $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                    $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_4_12);
                    $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                    let deficiencias = parseFloat(response[0][clase_final_tabla_4_12]);
                    $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                    let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_4_12]) + dominancia_suma;
                    $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                }         
            });

        break;

        case "Tabla 5.2A":
            // Calculo del Ajuste
            var valor_FP_tabla_5_2A = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_5_2A = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_5_2A = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_5_2A = (parseInt(valor_CFM1_tabla_5_2A) - parseInt(valor_FP_tabla_5_2A)) + (parseInt(valor_CFM2_tabla_5_2A) - parseInt(valor_FP_tabla_5_2A));
            // Calculo del Literal
            var literal_tabla_5_2A;
            
            if (ajuste_tabla_5_2A <= -2) {
                literal_tabla_5_2A = "A";
            }else if (ajuste_tabla_5_2A == -1) {
                literal_tabla_5_2A = "B";
            }else if (ajuste_tabla_5_2A == 0) {
                literal_tabla_5_2A = "C";
            }else if(ajuste_tabla_5_2A == 1){
                literal_tabla_5_2A = "D";
            }
            else if(ajuste_tabla_5_2A >= 2){
                literal_tabla_5_2A = "E";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_5_2A;
            
            // SI el FP y los FM=4, se asigna el mayor valor de deficiencia

            if (parseInt(valor_FP_tabla_5_2A) == 4 && parseInt(valor_CFM1_tabla_5_2A) == 4 && parseInt(valor_CFM2_tabla_5_2A) == 4) {
                clase_final_tabla_5_2A = "4E"; 
            }
            else{
                clase_final_tabla_5_2A = valor_FP_tabla_5_2A+literal_tabla_5_2A;
            }
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_5_2A) && literal_tabla_5_2A != undefined) {
                
                let datos_consulta_deficiencia_tabla_5_2A = {
                    '_token': token,
                    'columna': clase_final_tabla_5_2A,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_5_2A,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_5_2A);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_5_2A]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_5_2A]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 5.2B":
            // deficiencia
            var valor_FU_tabla_5_2B = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());
            var valor_CAT_tabla_5_2B = parseInt($("#resultado_CAT_"+id_fila_insertar_dato).val());

            // Calculo clase final
            var clase_final_tabla_5_2B;
            		
            if (valor_FU_tabla_5_2B == 60) {
                clase_final_tabla_5_2B = "4A";
            }else if(valor_FU_tabla_5_2B == 75){
                clase_final_tabla_5_2B = "4C";
            }else if(valor_FU_tabla_5_2B == 90){
                clase_final_tabla_5_2B = "4E";
            }
            
            // calculo deficiencia
            let datos_consulta_deficiencia_tabla_5_2B = {
                '_token': token,
                'columna': clase_final_tabla_5_2B,
                'Id_tabla': id_tabla
            };
            $.ajax({
                url: "/consultaValorDeficiencia",
                type: "post",
                data: datos_consulta_deficiencia_tabla_5_2B,
                success:function(response){
                    $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                    $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                    $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                    $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                    $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_5_2B);
                    
                    if (!isNaN(valor_CAT_tabla_5_2B)) {
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        var defi_tabla_5_2_B = parseInt(response[0][clase_final_tabla_5_2B]) + parseInt(valor_CAT_tabla_5_2B);                        
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(defi_tabla_5_2_B);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(defi_tabla_5_2_B) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    } else {
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_5_2B]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_5_2B]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }

                }         
            });

        break;

        case "Tabla 5.3":
            // deficiencia
            var valor_FU_tabla_5_3 = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

            // Calculo clase final
            var clase_final_tabla_5_3;
            if (valor_FU_tabla_5_3 == 20 || valor_FU_tabla_5_3 == 30) {
                clase_final_tabla_5_3 = "";
            }

            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_5_3);
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            let deficiencias_5_3 = parseFloat(valor_FU_tabla_5_3);
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias_5_3.toFixed(2));
            let suma_total_deficiencias_5_3 = parseFloat(valor_FU_tabla_5_3) + dominancia_suma;
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias_5_3.toFixed(2));

            // let datos_consulta_deficiencia_tabla_5_3 = {
            //     '_token': token,
            //     'columna': clase_final_tabla_5_3,
            //     'Id_tabla': id_tabla
            // };
            // $.ajax({
            //     url: "/consultaValorDeficiencia",
            //     type: "post",
            //     data: datos_consulta_deficiencia_tabla_5_3,
            //     success:function(response){
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_5_3);
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            //         let deficiencias = parseFloat(response[0][clase_final_tabla_5_3]);
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
            //         let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_5_3]) + dominancia_suma;
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
            //     }         
            // });

        break;

        case "Tabla 5.4":
            // Calculo del Ajuste
            var valor_FP_tabla_5_4 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_5_4 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_5_4 = parseInt(valor_CFM1_tabla_5_4) - parseInt(valor_FP_tabla_5_4);
            // Calculo del Literal
            var literal_tabla_5_4;
            
            if (ajuste_tabla_5_4 <= -1) {
                literal_tabla_5_4 = "A";
            }else if (ajuste_tabla_5_4 == 0) {
                literal_tabla_5_4 = "B";
            }else if (ajuste_tabla_5_4 >= 1) {
                literal_tabla_5_4 = "C";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_5_4;
            
            if (parseInt(valor_FP_tabla_5_4) < 4 ) {
                clase_final_tabla_5_4 = valor_FP_tabla_5_4+literal_tabla_5_4;
            }else{
                clase_final_tabla_5_4 = "4C";
            }
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_5_4) && literal_tabla_5_4 != undefined) {
                
                let datos_consulta_deficiencia_tabla_5_4 = {
                    '_token': token,
                    'columna': clase_final_tabla_5_4,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_5_4,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_5_4);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_5_4]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_5_4]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 5.5":
            // Calculo del Ajuste
            var valor_FP_tabla_5_5 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_5_5 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_5_5 = parseInt(valor_CFM1_tabla_5_5) - parseInt(valor_FP_tabla_5_5);
            // Calculo del Literal
            var literal_tabla_5_5;
            
            if (ajuste_tabla_5_5 <= -1) {
                literal_tabla_5_5 = "A";
            }else if (ajuste_tabla_5_5 == 0) {
                literal_tabla_5_5 = "B";
            }else if (ajuste_tabla_5_5 >= 1) {
                literal_tabla_5_5 = "C";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_5_5;
            // SI el FP y el FM=4, se asigna el mayor valor de deficiencia
            if (parseInt(valor_FP_tabla_5_5) == 4 && parseInt(valor_CFM1_tabla_5_5) == 4) {
                clase_final_tabla_5_5 = "4C";
            }else{
                clase_final_tabla_5_5 = valor_FP_tabla_5_5+literal_tabla_5_5;
            }
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_5_5) && literal_tabla_5_5 != undefined) {
                
                let datos_consulta_deficiencia_tabla_5_5 = {
                    '_token': token,
                    'columna': clase_final_tabla_5_5,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_5_5,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_5_5);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_5_5]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_5_5]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 5.6":
            // Calculo del Ajuste
            var valor_FP_tabla_5_6 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_5_6 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_5_6 = parseInt(valor_CFM1_tabla_5_6) - parseInt(valor_FP_tabla_5_6);
            // Calculo del Literal
            var literal_tabla_5_6;
            
            if (ajuste_tabla_5_6 <= -1) {
                literal_tabla_5_6 = "A";
            }else if (ajuste_tabla_5_6 == 0) {
                literal_tabla_5_6 = "B";
            }else if (ajuste_tabla_5_6 >= 1) {
                literal_tabla_5_6 = "C";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_5_6 = valor_FP_tabla_5_6+literal_tabla_5_6;
            
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_5_6) && literal_tabla_5_6 != undefined) {
                
                let datos_consulta_deficiencia_tabla_5_6 = {
                    '_token': token,
                    'columna': clase_final_tabla_5_6,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_5_6,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_5_6);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_5_6]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_5_6]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 5.7":
            // Calculo del Ajuste
            var valor_FP_tabla_5_7 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_5_7 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_5_7 = parseInt(valor_CFM1_tabla_5_7) - parseInt(valor_FP_tabla_5_7);
            // Calculo del Literal
            var literal_tabla_5_7;
            
            if (ajuste_tabla_5_7 <= -1) {
                literal_tabla_5_7 = "A";
            }else if (ajuste_tabla_5_7 == 0) {
                literal_tabla_5_7 = "B";
            }else if (ajuste_tabla_5_7 >= 1) {
                literal_tabla_5_7 = "C";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_5_7;
            
            if (parseInt(valor_FP_tabla_5_7) == 3 && parseInt(valor_CFM1_tabla_5_7) == 3) {
                clase_final_tabla_5_7 = "3C";
            }else{
                clase_final_tabla_5_7 = valor_FP_tabla_5_7+literal_tabla_5_7;
            }
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_5_7) && literal_tabla_5_7 != undefined) {
                
                let datos_consulta_deficiencia_tabla_5_7 = {
                    '_token': token,
                    'columna': clase_final_tabla_5_7,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_5_7,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_5_7);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_5_7]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_5_7]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 5.8":
            // Calculo del Ajuste
            var valor_FP_tabla_5_8 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_5_8 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_5_8 = parseInt(valor_CFM1_tabla_5_8) - parseInt(valor_FP_tabla_5_8);
            // Calculo del Literal
            var literal_tabla_5_8;
            
            if (ajuste_tabla_5_8 <= -1) {
                literal_tabla_5_8 = "A";
            }else if (ajuste_tabla_5_8 == 0) {
                literal_tabla_5_8 = "B";
            }else if (ajuste_tabla_5_8 >= 1) {
                literal_tabla_5_8 = "C";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_5_8;
            
            // parseInt(valor_FP_tabla_5_8) == 3 && parseInt(valor_CFM1_tabla_5_8) == 3
            if (parseInt(valor_FP_tabla_5_8) == 3) {
                clase_final_tabla_5_8 = "3A";
            }else{
                clase_final_tabla_5_8 = valor_FP_tabla_5_8+literal_tabla_5_8;
            }
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_5_8) && literal_tabla_5_8 != undefined) {
                
                let datos_consulta_deficiencia_tabla_5_8 = {
                    '_token': token,
                    'columna': clase_final_tabla_5_8,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_5_8,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_5_8);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_5_8]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_5_8]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 5.9":
            // Calculo del Ajuste
            var valor_FP_tabla_5_9 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_5_9 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_5_9 = parseInt(valor_CFM1_tabla_5_9) - parseInt(valor_FP_tabla_5_9);
            // Calculo del Literal
            var literal_tabla_5_9;
            
            if (ajuste_tabla_5_9 <= -1) {
                literal_tabla_5_9 = "A";
            }else if (ajuste_tabla_5_9 == 0) {
                literal_tabla_5_9 = "B";
            }else if (ajuste_tabla_5_9 >= 1) {
                literal_tabla_5_9 = "C";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_5_9;
            
            // parseInt(valor_FP_tabla_5_9) == 3 && parseInt(valor_CFM1_tabla_5_9) == 3
            if (parseInt(valor_FP_tabla_5_9) == 3) {
                clase_final_tabla_5_9 = "3A";
            }else{
                clase_final_tabla_5_9 = valor_FP_tabla_5_9+literal_tabla_5_9;
            }
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_5_9) && literal_tabla_5_9 != undefined) {
                
                let datos_consulta_deficiencia_tabla_5_9 = {
                    '_token': token,
                    'columna': clase_final_tabla_5_9,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_5_9,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_5_9);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_5_9]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_5_9]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 5.10A":
            // Calculo del Ajuste
            var valor_FP_tabla_5_10A = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_5_10A = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_5_10A = parseInt(valor_CFM1_tabla_5_10A) - parseInt(valor_FP_tabla_5_10A);
            // Calculo del Literal
            var literal_tabla_5_10A;
            
            if (ajuste_tabla_5_10A <= -1) {
                literal_tabla_5_10A = "A";
            }else if (ajuste_tabla_5_10A == 0) {
                literal_tabla_5_10A = "B";
            }else if (ajuste_tabla_5_10A >= 1) {
                literal_tabla_5_10A = "C";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_5_10A = valor_FP_tabla_5_10A+literal_tabla_5_10A;
            
            // if (parseInt(valor_FP_tabla_5_10A) == 2 && parseInt(valor_CFM1_tabla_5_10A) == 3) {
            //     clase_final_tabla_5_10A = "2C";
            // }else{
            //     clase_final_tabla_5_10A = valor_FP_tabla_5_10A+literal_tabla_5_10A;
            // }
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_5_10A) && literal_tabla_5_10A != undefined) {
                
                let datos_consulta_deficiencia_tabla_5_10A = {
                    '_token': token,
                    'columna': clase_final_tabla_5_10A,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_5_10A,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_5_10A);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_5_10A]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_5_10A]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 5.10B":
            // deficiencia
            var valor_FU_tabla_5_11B = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

            // Calculo clase final
            var clase_final_tabla_5_11B;
            if (valor_FU_tabla_5_11B == 11) {
                clase_final_tabla_5_11B = "2A";
            }else if(valor_FU_tabla_5_11B == 25) {
                clase_final_tabla_5_11B = "2C";
            }else{
                clase_final_tabla_5_11B = "3A";
            }

            let datos_consulta_deficiencia_tabla_5_11B = {
                '_token': token,
                'columna': clase_final_tabla_5_11B,
                'Id_tabla': id_tabla
            };
            $.ajax({
                url: "/consultaValorDeficiencia",
                type: "post",
                data: datos_consulta_deficiencia_tabla_5_11B,
                success:function(response){
                    $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                    $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                    $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                    $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                    $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_5_11B);
                    $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                    let deficiencias = parseFloat(response[0][clase_final_tabla_5_11B]);
                    $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                    let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_5_11B]) + dominancia_suma;
                    $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                }         
            });

        break;

        case "Tabla 5.11":
            // Calculo del Ajuste
            var valor_FP_tabla_5_11 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_5_11 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_5_11 = parseInt(valor_CFM1_tabla_5_11) - parseInt(valor_FP_tabla_5_11);
            // Calculo del Literal
            var literal_tabla_5_11;
            
            if (ajuste_tabla_5_11 <= -1) {
                literal_tabla_5_11 = "A";
            }else if (ajuste_tabla_5_11 == 0) {
                literal_tabla_5_11 = "B";
            }else if (ajuste_tabla_5_11 >= 1) {
                literal_tabla_5_11 = "C";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_5_11 = valor_FP_tabla_5_11+literal_tabla_5_11;
            
            // if (parseInt(valor_FP_tabla_5_11) == 2 && parseInt(valor_CFM1_tabla_5_11) == 3) {
            //     clase_final_tabla_5_11 = "2C";
            // }else{
            //     clase_final_tabla_5_11 = valor_FP_tabla_5_11+literal_tabla_5_11;
            // }
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_5_11) && literal_tabla_5_11 != undefined) {
                
                let datos_consulta_deficiencia_tabla_5_11 = {
                    '_token': token,
                    'columna': clase_final_tabla_5_11,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_5_11,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_5_11);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_5_11]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_5_11]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 5.12":
            // Calculo del Ajuste
            var valor_FP_tabla_5_12 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_5_12 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_5_12 = parseInt(valor_CFM1_tabla_5_12) - parseInt(valor_FP_tabla_5_12);
            // Calculo del Literal
            var literal_tabla_5_12;
            
            if (ajuste_tabla_5_12 <= -1) {
                literal_tabla_5_12 = "A";
            }else if (ajuste_tabla_5_12 == 0) {
                literal_tabla_5_12 = "B";
            }else if (ajuste_tabla_5_12 >= 1) {
                literal_tabla_5_12 = "C";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_5_12 = valor_FP_tabla_5_12+literal_tabla_5_12;
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_5_12) && literal_tabla_5_12 != undefined) {
                
                let datos_consulta_deficiencia_tabla_5_12 = {
                    '_token': token,
                    'columna': clase_final_tabla_5_12,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_5_12,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_5_12);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_5_12]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_5_12]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 6.1":
            // Calculo del Ajuste
            var valor_FP_tabla_6_1 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_6_1 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_6_1 = parseInt($("#guardar_CFM2_fila_"+id_fila_insertar_dato).val());

            var ajuste_tabla_6_1 = ((parseInt(valor_CFM2_tabla_6_1) - parseInt(valor_FP_tabla_6_1)) + (parseInt(valor_CFM1_tabla_6_1) - parseInt(valor_FP_tabla_6_1)));
            //console.log(ajuste_tabla_6_1);
            // Calculo clase final
            var literal_tabla_6_1;
            
            if (ajuste_tabla_6_1 <= -2) {
                literal_tabla_6_1 = "A";
            }else if (ajuste_tabla_6_1 == -1) {
                literal_tabla_6_1 = "B";
            }else if (ajuste_tabla_6_1 == 0) {
                literal_tabla_6_1 = "C";
            }else if(ajuste_tabla_6_1 == 1){
                literal_tabla_6_1 = "D";
            }
            else if(ajuste_tabla_6_1 >= 2){
                literal_tabla_6_1 = "E";
            }

            // Calculo de la Clase Final
            var clase_final_tabla_6_1 = valor_FP_tabla_6_1+literal_tabla_6_1;

            // calculo deficiencia
            if (!isNaN(ajuste_tabla_6_1) && literal_tabla_6_1 != undefined) {
                let datos_consulta_deficiencia_tabla_6_1 = {
                    '_token': token,
                    'columna': clase_final_tabla_6_1,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_6_1,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_6_1);
    
                        /* if (valor_CAT_tabla_6_1 != "") {
                            var defi_tabla_6_1 = parseInt(response[0][clase_final_tabla_6_1]) + parseInt(valor_CAT_tabla_6_1);
                            console.log();
                            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(defi_tabla_6_1);
                        } else {
                        } */
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_6_1]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_6_1]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
    
                    }         
                });
            }

        break;

        case "Tabla 6.2":
            // Calculo del Ajuste
            var valor_FP_tabla_6_2 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_6_2 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_6_2 = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_6_2 = (parseInt(valor_CFM1_tabla_6_2) - parseInt(valor_FP_tabla_6_2)) + (parseInt(valor_CFM2_tabla_6_2) - parseInt(valor_FP_tabla_6_2));
            // Calculo del Literal
            var literal_tabla_6_2;
            
            if (ajuste_tabla_6_2 <= -2) {
                literal_tabla_6_2 = "A";
            }else if (ajuste_tabla_6_2 == -1) {
                literal_tabla_6_2 = "B";
            }else if (ajuste_tabla_6_2 == 0) {
                literal_tabla_6_2 = "C";
            }else if(ajuste_tabla_6_2 == 1){
                literal_tabla_6_2 = "D";
            }
            else if(ajuste_tabla_6_2 >= 2){
                literal_tabla_6_2 = "E";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_6_2 = valor_FP_tabla_6_2+literal_tabla_6_2;
            
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_6_2) && literal_tabla_6_2 != undefined) {
                
                let datos_consulta_deficiencia_tabla_6_2 = {
                    '_token': token,
                    'columna': clase_final_tabla_6_2,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_6_2,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_6_2);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_6_2]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_6_2]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 7.2":
            // Calculo del Ajuste
            var valor_FP_tabla_7_2 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_7_2 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_FU_tabla_7_2 = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());
            var valor_CAT_tabla_7_2 = parseInt($("#resultado_CAT_"+id_fila_insertar_dato).val());

            var ajuste_tabla_7_2 = parseInt(valor_CFM1_tabla_7_2) - parseInt(valor_FP_tabla_7_2);
            //console.log(ajuste_tabla_7_2);
            // Calculo clase final
            var literal_tabla_7_2;

            if (ajuste_tabla_7_2 <= -1) {
                literal_tabla_7_2 = "A";
            }else if (ajuste_tabla_7_2 == 0) {
                literal_tabla_7_2 = "B";
            }else if (ajuste_tabla_7_2 >= 1) {
                literal_tabla_7_2 = "C";
            }            
            // if (ajuste_tabla_7_2 <= -2) {
            //     literal_tabla_7_2 = "A";
            // }else if (ajuste_tabla_7_2 == -1) {
            //     literal_tabla_7_2 = "B";
            // }else if (ajuste_tabla_7_2 == 0) {
            //     literal_tabla_7_2 = "C";
            // }else if(ajuste_tabla_7_2 == 1){
            //     literal_tabla_7_2 = "D";
            // }
            // else if(ajuste_tabla_7_2 >= 2){
            //     literal_tabla_7_2 = "E";
            // }
            // Calculo de la Clase Final
            var clase_final_tabla_7_2 = valor_FP_tabla_7_2+literal_tabla_7_2;
            // calculo deficiencia
            if (!isNaN(ajuste_tabla_7_2) && literal_tabla_7_2 != undefined) {
                $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                if ((parseInt(valor_FP_tabla_7_2) == 4) && (parseInt(valor_CFM1_tabla_7_2) == 3) && (parseInt(valor_FU_tabla_7_2) == 80) || 
                    (parseInt(valor_FP_tabla_7_2) == 4) && (parseInt(valor_CFM1_tabla_7_2) == 4) && (parseInt(valor_FU_tabla_7_2) == 80)) {
                    clase_final_tabla_7_2 = "4B";    
                    $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_7_2);
                }else if((parseInt(valor_FP_tabla_7_2) == 4) && (parseInt(valor_CFM1_tabla_7_2) == 3) && (parseInt(valor_FU_tabla_7_2) == 90) ||
                    (parseInt(valor_FP_tabla_7_2) == 4) && (parseInt(valor_CFM1_tabla_7_2) == 4) && (parseInt(valor_FU_tabla_7_2) == 90)){
                    clase_final_tabla_7_2 = "4C";      
                    $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_7_2);
                }else{
                    $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_7_2);
                }
                $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();  
                $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                var selectTabla7_2 = $("#resultado_FU_"+id_fila_insertar_dato);
                if ((parseInt(valor_FP_tabla_7_2) == 4) && (parseInt(valor_CFM1_tabla_7_2) == 4 || (parseInt(valor_CFM1_tabla_7_2) == 3))) {
                    
                    //Habilitar y Deshabilitar el FU
                    $("#resultado_FU_"+id_fila_insertar_dato).prop("disabled", false);  
                    // nuevas Opciones
                    var nuevasOpciones = [
                        { value: '', text: 'Seleccione' },
                        { value: '80', text: '80' },
                        { value: '90', text: '90' },
                    ];
                    // Obtener las opciones existentes (si las hay)
                    var opcionesExist = selectTabla7_2.children('option');

                    // Filtrar las nuevas opciones para incluir solo las que no existen aún
                    var nuevasOpcionesFiltradas = nuevasOpciones.filter(function (nuevaOpcion) {
                        return !opcionesExist.filter(function () {
                        return this.value === nuevaOpcion.value;
                        }).length;
                    });

                    // Agregar solo las nuevas opciones al select
                    nuevasOpcionesFiltradas.forEach(function (nuevaOpcion) {
                        selectTabla7_2.append('<option value="' + nuevaOpcion.value + '">' + nuevaOpcion.text + '</option>');
                    }); 
                    
                    // console.log(valor_FU_tabla_7_2);   
                    // console.log(valor_CAT_tabla_7_2);   

                    if (!isNaN(valor_FU_tabla_7_2)) {         
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();  
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        // $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(parseInt(valor_FU_tabla_7_2));
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(valor_FU_tabla_7_2);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(valor_FU_tabla_7_2) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }
                    else if(isNaN(valor_FU_tabla_7_2)){
                        $("#resultado_CAT_"+id_fila_insertar_dato).val('');
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();  
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                    }
                    if (!isNaN(valor_CAT_tabla_7_2)) {                        
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty(); 
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        var defi_tabla_7_2 = parseInt(valor_FU_tabla_7_2) + parseInt(valor_CAT_tabla_7_2);
                        // $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(defi_tabla_7_2);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(defi_tabla_7_2);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(defi_tabla_7_2) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }                    
                    // else {
                    //     $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(parseInt(valor_FU_tabla_7_2));
                    // }                    
                }else{
                    $("#resultado_FU_"+id_fila_insertar_dato).empty();
                    selectTabla7_2.append('<option value="">Seleccione</option>');
                    $("#guardar_FU_fila_"+id_fila_insertar_dato).val("");
                    //console.log(valor_FU_tabla_7_2);                  
                    $("#resultado_FU_"+id_fila_insertar_dato).prop("disabled", true);
                     
                    let datos_consulta_deficiencia_tabla_7_2 = {
                        '_token': token,
                        'columna': clase_final_tabla_7_2,
                        'Id_tabla': id_tabla
                    };
                    $.ajax({
                        url: "/consultaValorDeficiencia",
                        type: "post",
                        data: datos_consulta_deficiencia_tabla_7_2,
                        success:function(response){                            
                            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                            // $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(response[0][clase_final_tabla_7_2]);
                            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                            let deficiencias = parseFloat(response[0][clase_final_tabla_7_2]);
                            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                            let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_7_2]) + dominancia_suma;
                            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                            if (!isNaN(valor_CAT_tabla_7_2)) {
                                $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                                if (response[0][clase_final_tabla_7_2] != "") {
                                    $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                                    $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                                    var defi_tabla_7_2 = parseInt(response[0][clase_final_tabla_7_2]) + parseInt(valor_CAT_tabla_7_2);
                                    // $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(defi_tabla_7_2);
                                    $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                                    let deficiencias = parseFloat(defi_tabla_7_2);
                                    $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                                    let suma_total_deficiencias = parseFloat(defi_tabla_7_2) + dominancia_suma;
                                    $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                                } else {
                                    $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                                    $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                                    $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                                    // $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(parseInt(valor_CAT_tabla_7_2));
                                    $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                                    let deficiencias = parseFloat(valor_CAT_tabla_7_2);
                                    $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                                    let suma_total_deficiencias = parseFloat(valor_CAT_tabla_7_2) + dominancia_suma;
                                    $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                                }
                            }
                        }         
                    });
                };

            }
        break;

        case "Tabla 7.3":
            // Calculo del Ajuste
            var valor_FP_tabla_7_3 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_7_3 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_FU_tabla_7_3 = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());
            var valor_CAT_tabla_7_3 = parseInt($("#resultado_CAT_"+id_fila_insertar_dato).val());
           
            var ajuste_tabla_7_3 = parseInt(valor_CFM1_tabla_7_3) - parseInt(valor_FP_tabla_7_3);
            //console.log(ajuste_tabla_7_3);
            // Calculo clase final
            var literal_tabla_7_3;

            if (ajuste_tabla_7_3 <= -1) {
                literal_tabla_7_3 = "A";
            }else if (ajuste_tabla_7_3 == 0) {
                literal_tabla_7_3 = "B";
            }else if (ajuste_tabla_7_3 >= 1) {
                literal_tabla_7_3 = "C";
            }  
            //console.log(literal_tabla_7_3);

            // if (ajuste_tabla_7_3 <= -2) {
            //     literal_tabla_7_3 = "A";
            // }else if (ajuste_tabla_7_3 == -1) {
            //     literal_tabla_7_3 = "B";
            // }else if (ajuste_tabla_7_3 == 0) {
            //     literal_tabla_7_3 = "C";
            // }else if(ajuste_tabla_7_3 == 1){
            //     literal_tabla_7_3 = "D";
            // }
            // else if(ajuste_tabla_7_3 >= 2){
            //     literal_tabla_7_3 = "E";
            // }

            // Calculo de la Clase Final
            var clase_final_tabla_7_3 = valor_FP_tabla_7_3+literal_tabla_7_3;
            //console.log(clase_final_tabla_7_3);
            // calculo deficiencia
            if (!isNaN(ajuste_tabla_7_3) && literal_tabla_7_3 != undefined) {
                $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                if ((parseInt(valor_FP_tabla_7_3) == 4) && (parseInt(valor_CFM1_tabla_7_3) == 3) && (parseInt(valor_FU_tabla_7_3) == 70) ||
                    (parseInt(valor_FP_tabla_7_3) == 4) && (parseInt(valor_CFM1_tabla_7_3) == 4) && (parseInt(valor_FU_tabla_7_3) == 70)) {
                    clase_final_tabla_7_3 = "4B";    
                    $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_7_3);
                }else if((parseInt(valor_FP_tabla_7_3) == 4) && (parseInt(valor_CFM1_tabla_7_3) == 4) && (parseInt(valor_FU_tabla_7_3) == 80) ||
                    (parseInt(valor_FP_tabla_7_3) == 4) && (parseInt(valor_CFM1_tabla_7_3) == 3) && (parseInt(valor_FU_tabla_7_3) == 80)){
                    clase_final_tabla_7_3 = "4C";      
                    $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_7_3);
                }else{
                    $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_7_3);
                }
                $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();                
                var selectTabla7_3 = $("#resultado_FU_"+id_fila_insertar_dato);
                if ((parseInt(valor_FP_tabla_7_3) == 4) && (parseInt(valor_CFM1_tabla_7_3) == 4 || parseInt(valor_CFM1_tabla_7_3) == 3)) {

                    //Habilitar y Deshabilitar el FU
                    $("#resultado_FU_"+id_fila_insertar_dato).prop("disabled", false);  
                    // nuevas Opciones
                    var nuevasOpciones = [
                        { value: '', text: 'Seleccione' },
                        { value: '70', text: '70' },
                        { value: '80', text: '80' },
                    ];
                    // Obtener las opciones existentes (si las hay)
                    var opcionesExist = selectTabla7_3.children('option');

                    // Filtrar las nuevas opciones para incluir solo las que no existen aún
                    var nuevasOpcionesFiltradas = nuevasOpciones.filter(function (nuevaOpcion) {
                        return !opcionesExist.filter(function () {
                        return this.value === nuevaOpcion.value;
                        }).length;
                    });

                    // Agregar solo las nuevas opciones al select
                    nuevasOpcionesFiltradas.forEach(function (nuevaOpcion) {
                        selectTabla7_3.append('<option value="' + nuevaOpcion.value + '">' + nuevaOpcion.text + '</option>');
                    }); 
                    
                    //console.log(valor_FU_tabla_7_3); 

                    if (!isNaN(valor_FU_tabla_7_3)) {
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        // $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(parseInt(valor_FU_tabla_7_3));
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(valor_FU_tabla_7_3);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(valor_FU_tabla_7_3) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));

                    }
                    else if(isNaN(valor_FU_tabla_7_3)){
                        $("#resultado_CAT_"+id_fila_insertar_dato).val('');
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();  
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                    }
                    // console.log(valor_CAT_tabla_7_3);
                    if (!isNaN(valor_CAT_tabla_7_3)) {
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        var defi_tabla_7_3 = parseInt(valor_FU_tabla_7_3) + parseInt(valor_CAT_tabla_7_3);
                        // $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(defi_tabla_7_3);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(defi_tabla_7_3);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(defi_tabla_7_3) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    } 
                    // else {
                    //     $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(parseInt(valor_FU_tabla_7_3));
                    // }                    
                }else{      
                    $("#resultado_FU_"+id_fila_insertar_dato).empty();
                    selectTabla7_3.append('<option value="">Seleccione</option>');
                    $("#guardar_FU_fila_"+id_fila_insertar_dato).val("");
                    //console.log(valor_FU_tabla_7_3);                  
                    $("#resultado_FU_"+id_fila_insertar_dato).prop("disabled", true);

                    let datos_consulta_deficiencia_tabla_7_3 = {
                        '_token': token,
                        'columna': clase_final_tabla_7_3,
                        'Id_tabla': id_tabla
                    };
                    $.ajax({
                        url: "/consultaValorDeficiencia",
                        type: "post",
                        data: datos_consulta_deficiencia_tabla_7_3,
                        success:function(response){
                            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                            // $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(response[0][clase_final_tabla_7_3]);
                            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                            let deficiencias = parseFloat(response[0][clase_final_tabla_7_3]);
                            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                            let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_7_3]) + dominancia_suma;
                            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                            if (!isNaN(valor_CAT_tabla_7_3)) {
                                $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();                                
                                if (response[0][clase_final_tabla_7_3] != "") {                                    
                                    $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                                    $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                                    var defi_tabla_7_3 = parseInt(response[0][clase_final_tabla_7_3]) + parseInt(valor_CAT_tabla_7_3);
                                    // $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(defi_tabla_7_3);
                                    $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                                    let deficiencias = parseFloat(defi_tabla_7_3);
                                    $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                                    let suma_total_deficiencias = parseFloat(defi_tabla_7_3) + dominancia_suma;
                                    $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                                } else {                                    
                                    $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                                    $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                                    // $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(parseInt(valor_CAT_tabla_7_3));
                                    $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                                    let deficiencias = parseFloat(valor_CAT_tabla_7_3);
                                    $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                                    let suma_total_deficiencias = parseFloat(valor_CAT_tabla_7_3) + dominancia_suma;
                                    $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                                }
                            }
                        }         
                    });
                };

            }
        break;

        case "Tabla 7.4":
            // Calculo del Ajuste
            var valor_FP_tabla_7_4 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_7_4 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_FU_tabla_7_4 = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());
            var valor_CAT_tabla_7_4 = parseInt($("#resultado_CAT_"+id_fila_insertar_dato).val());

            var ajuste_tabla_7_4 = parseInt(valor_CFM1_tabla_7_4) - parseInt(valor_FP_tabla_7_4);

            // Calculo clase final
            var literal_tabla_7_4;

            if (ajuste_tabla_7_4 <= -1) {
                literal_tabla_7_4 = "A";
            }else if (ajuste_tabla_7_4 == 0) {
                literal_tabla_7_4 = "B";
            }else if (ajuste_tabla_7_4 >= 1) {
                literal_tabla_7_4 = "C";
            }  
            
            // if (ajuste_tabla_7_4 <= -2) {
            //     literal_tabla_7_4 = "A";
            // }else if (ajuste_tabla_7_4 == -1) {
            //     literal_tabla_7_4 = "B";
            // }else if (ajuste_tabla_7_4 == 0) {
            //     literal_tabla_7_4 = "C";
            // }else if(ajuste_tabla_7_4 == 1){
            //     literal_tabla_7_4 = "D";
            // }
            // else if(ajuste_tabla_7_4 >= 2){
            //     literal_tabla_7_4 = "E";
            // }

            // Calculo de la Clase Final
            var clase_final_tabla_7_4 = valor_FP_tabla_7_4+literal_tabla_7_4;
            
            // calculo deficiencia
            if (!isNaN(ajuste_tabla_7_4) && literal_tabla_7_4 != undefined) {
                $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                if ((parseInt(valor_FP_tabla_7_4) == 4) && (parseInt(valor_CFM1_tabla_7_4) == 3) && (parseInt(valor_FU_tabla_7_4) == 80)||
                    (parseInt(valor_FP_tabla_7_4) == 4) && (parseInt(valor_CFM1_tabla_7_4) == 4) && (parseInt(valor_FU_tabla_7_4) == 80)) {
                    clase_final_tabla_7_4 = "4A";    
                    $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_7_4);
                }else if((parseInt(valor_FP_tabla_7_4) == 4) && (parseInt(valor_CFM1_tabla_7_4) == 3) && (parseInt(valor_FU_tabla_7_4) == 90)||
                    (parseInt(valor_FP_tabla_7_4) == 4) && (parseInt(valor_CFM1_tabla_7_4) == 4) && (parseInt(valor_FU_tabla_7_4) == 90)) {
                    clase_final_tabla_7_4 = "4B";    
                    $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_7_4);
                }else if((parseInt(valor_FP_tabla_7_4) == 4) && (parseInt(valor_CFM1_tabla_7_4) == 3) && (parseInt(valor_FU_tabla_7_4) == 100)||
                    (parseInt(valor_FP_tabla_7_4) == 4) && (parseInt(valor_CFM1_tabla_7_4) == 4) && (parseInt(valor_FU_tabla_7_4) == 100)){
                    clase_final_tabla_7_4 = "4C";      
                    $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_7_4);
                }else{
                    $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_7_4);
                }
                $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                var selectTabla7_4 = $("#resultado_FU_"+id_fila_insertar_dato);
                if ((parseInt(valor_FP_tabla_7_4) == 4) && (parseInt(valor_CFM1_tabla_7_4) == 4 || parseInt(valor_CFM1_tabla_7_4) == 3)) {

                    //Habilitar y Deshabilitar el FU
                    $("#resultado_FU_"+id_fila_insertar_dato).prop("disabled", false);  
                    // nuevas Opciones
                    var nuevasOpciones = [
                        { value: '', text: 'Seleccione' },
                        { value: '90', text: '90' },
                        { value: '100', text: '100' },
                    ];
                    // Obtener las opciones existentes (si las hay)
                    var opcionesExist = selectTabla7_4.children('option');

                    // Filtrar las nuevas opciones para incluir solo las que no existen aún
                    var nuevasOpcionesFiltradas = nuevasOpciones.filter(function (nuevaOpcion) {
                        return !opcionesExist.filter(function () {
                        return this.value === nuevaOpcion.value;
                        }).length;
                    });

                    // Agregar solo las nuevas opciones al select
                    nuevasOpcionesFiltradas.forEach(function (nuevaOpcion) {
                        selectTabla7_4.append('<option value="' + nuevaOpcion.value + '">' + nuevaOpcion.text + '</option>');
                    }); 
                    
                    //console.log(valor_FU_tabla_7_4); 

                    if (!isNaN(valor_FU_tabla_7_4)) {
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        // $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(parseInt(valor_FU_tabla_7_4));
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(valor_FU_tabla_7_4);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(valor_FU_tabla_7_4) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));

                    }
                    else if(isNaN(valor_FU_tabla_7_4)){
                        $("#resultado_CAT_"+id_fila_insertar_dato).val('');
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();  
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                    }
                    // console.log(valor_CAT_tabla_7_4);
                    if (!isNaN(valor_CAT_tabla_7_4)) {
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        var defi_tabla_7_4 = parseInt(valor_FU_tabla_7_4) + parseInt(valor_CAT_tabla_7_4);
                        // $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(defi_tabla_7_4);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(defi_tabla_7_4);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(defi_tabla_7_4) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    } 
                    // else {
                    //     $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(parseInt(valor_FU_tabla_7_4));
                    // }
                }else{
                    $("#resultado_FU_"+id_fila_insertar_dato).empty();
                    selectTabla7_4.append('<option value="">Seleccione</option>');
                    $("#guardar_FU_fila_"+id_fila_insertar_dato).val("");
                    //console.log(valor_FU_tabla_7_4);                  
                    $("#resultado_FU_"+id_fila_insertar_dato).prop("disabled", true);

                    let datos_consulta_deficiencia_tabla_7_4 = {
                        '_token': token,
                        'columna': clase_final_tabla_7_4,
                        'Id_tabla': id_tabla
                    };
                    $.ajax({
                        url: "/consultaValorDeficiencia",
                        type: "post",
                        data: datos_consulta_deficiencia_tabla_7_4,
                        success:function(response){
                            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                            // $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(response[0][clase_final_tabla_7_4]);
                            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                            let deficiencias = parseFloat(response[0][clase_final_tabla_7_4]);
                            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                            let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_7_4]) + dominancia_suma;
                            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                            if (!isNaN(valor_CAT_tabla_7_4)) {
                                $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                                $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                                $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                                if (response[0][clase_final_tabla_7_4] != "") {
                                    var defi_tabla_7_4 = parseInt(response[0][clase_final_tabla_7_4]) + parseInt(valor_CAT_tabla_7_4);
                                    // $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(defi_tabla_7_4);
                                    $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                                    let deficiencias = parseFloat(defi_tabla_7_4);
                                    $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                                    let suma_total_deficiencias = parseFloat(defi_tabla_7_4) + dominancia_suma;
                                    $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                                } else {
                                    // $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(parseInt(valor_CAT_tabla_7_4));
                                    $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                                    let deficiencias = parseFloat(valor_CAT_tabla_7_4);
                                    $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                                    let suma_total_deficiencias = parseFloat(valor_CAT_tabla_7_4) + dominancia_suma;
                                    $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                                }
                            }
                        }         
                    });
                };

            }
        break;

        case "Tabla 7.5":
            // Calculo del Ajuste
            var valor_FP_tabla_7_5 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_7_5 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_FU_tabla_7_5 = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());
            var valor_CAT_tabla_7_5 = parseInt($("#resultado_CAT_"+id_fila_insertar_dato).val());

            var ajuste_tabla_7_5 = parseInt(valor_CFM1_tabla_7_5) - parseInt(valor_FP_tabla_7_5);

            // Calculo clase final
            var literal_tabla_7_5;

            if (ajuste_tabla_7_5 <= -1) {
                literal_tabla_7_5 = "A";
            }else if (ajuste_tabla_7_5 == 0) {
                literal_tabla_7_5 = "B";
            }else if (ajuste_tabla_7_5 >= 1) {
                literal_tabla_7_5 = "C";
            }  
            
            // if (ajuste_tabla_7_5 <= -2) {
            //     literal_tabla_7_5 = "A";
            // }else if (ajuste_tabla_7_5 == -1) {
            //     literal_tabla_7_5 = "B";
            // }else if (ajuste_tabla_7_5 == 0) {
            //     literal_tabla_7_5 = "C";
            // }else if(ajuste_tabla_7_5 == 1){
            //     literal_tabla_7_5 = "D";
            // }
            // else if(ajuste_tabla_7_5 >= 2){
            //     literal_tabla_7_5 = "E";
            // }

            // Calculo de la Clase Final
            var clase_final_tabla_7_5 = valor_FP_tabla_7_5+literal_tabla_7_5;
            
            // calculo deficiencia
            if (!isNaN(ajuste_tabla_7_5) && literal_tabla_7_5 != undefined) {
                $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                if ((parseInt(valor_FP_tabla_7_5) == 4) && (parseInt(valor_CFM1_tabla_7_5) == 3) && (parseInt(valor_FU_tabla_7_5) == 45)||
                    (parseInt(valor_FP_tabla_7_5) == 4) && (parseInt(valor_CFM1_tabla_7_5) == 4) && (parseInt(valor_FU_tabla_7_5) == 45)) {
                    clase_final_tabla_7_5 = "4A";    
                    $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_7_5);
                }else if((parseInt(valor_FP_tabla_7_5) == 4) && (parseInt(valor_CFM1_tabla_7_5) == 3) && (parseInt(valor_FU_tabla_7_5) == 55)||
                    (parseInt(valor_FP_tabla_7_5) == 4) && (parseInt(valor_CFM1_tabla_7_5) == 4) && (parseInt(valor_FU_tabla_7_5) == 55)) {
                    clase_final_tabla_7_5 = "4B";    
                    $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_7_5);
                }else if((parseInt(valor_FP_tabla_7_5) == 4) && (parseInt(valor_CFM1_tabla_7_5) == 3) && (parseInt(valor_FU_tabla_7_5) == 65)||
                    (parseInt(valor_FP_tabla_7_5) == 4) && (parseInt(valor_CFM1_tabla_7_5) == 4) && (parseInt(valor_FU_tabla_7_5) == 65)){
                    clase_final_tabla_7_5 = "4C";      
                    $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_7_5);
                }else{
                    $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_7_5);
                }
                $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                var selectTabla7_5 = $("#resultado_FU_"+id_fila_insertar_dato);
                if ((parseInt(valor_FP_tabla_7_5) == 4) && (parseInt(valor_CFM1_tabla_7_5) == 4 || parseInt(valor_CFM1_tabla_7_5) == 3)) {

                    //Habilitar y Deshabilitar el FU
                    $("#resultado_FU_"+id_fila_insertar_dato).prop("disabled", false);  
                    // nuevas Opciones
                    var nuevasOpciones = [
                        { value: '', text: 'Seleccione' },
                        { value: '55', text: '55' },
                        { value: '65', text: '65' },
                    ];
                    // Obtener las opciones existentes (si las hay)
                    var opcionesExist = selectTabla7_5.children('option');

                    // Filtrar las nuevas opciones para incluir solo las que no existen aún
                    var nuevasOpcionesFiltradas = nuevasOpciones.filter(function (nuevaOpcion) {
                        return !opcionesExist.filter(function () {
                        return this.value === nuevaOpcion.value;
                        }).length;
                    });

                    // Agregar solo las nuevas opciones al select
                    nuevasOpcionesFiltradas.forEach(function (nuevaOpcion) {
                        selectTabla7_5.append('<option value="' + nuevaOpcion.value + '">' + nuevaOpcion.text + '</option>');
                    }); 
                    
                    //console.log(valor_FU_tabla_7_5);

                    if (!isNaN(valor_FU_tabla_7_5)) {
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        // $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(parseInt(valor_FU_tabla_7_5));
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(valor_FU_tabla_7_5);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(valor_FU_tabla_7_5) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));

                    }
                    else if(isNaN(valor_FU_tabla_7_5)){
                        $("#resultado_CAT_"+id_fila_insertar_dato).val('');
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();  
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                    }
                    // console.log(valor_CAT_tabla_7_5);
                    if (!isNaN(valor_CAT_tabla_7_5)) {
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        var defi_tabla_7_5 = parseInt(valor_FU_tabla_7_5) + parseInt(valor_CAT_tabla_7_5);
                        // $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(defi_tabla_7_5);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(defi_tabla_7_5);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(defi_tabla_7_5) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    } 
                    // else {
                    //     $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(parseInt(valor_FU_tabla_7_5));
                    // }
                }else{
                    $("#resultado_FU_"+id_fila_insertar_dato).empty();
                    selectTabla7_5.append('<option value="">Seleccione</option>');
                    $("#guardar_FU_fila_"+id_fila_insertar_dato).val("");
                    //console.log(valor_FU_tabla_7_4);                  
                    $("#resultado_FU_"+id_fila_insertar_dato).prop("disabled", true);

                    let datos_consulta_deficiencia_tabla_7_5 = {
                        '_token': token,
                        'columna': clase_final_tabla_7_5,
                        'Id_tabla': id_tabla
                    };
                    $.ajax({
                        url: "/consultaValorDeficiencia",
                        type: "post",
                        data: datos_consulta_deficiencia_tabla_7_5,
                        success:function(response){
                            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                            // $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(response[0][clase_final_tabla_7_5]);
                            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                            let deficiencias = parseFloat(response[0][clase_final_tabla_7_5]);
                            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                            let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_7_5]) + dominancia_suma;
                            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                            if (!isNaN(valor_CAT_tabla_7_5)) {
                                $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                                $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                                $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                                if (response[0][clase_final_tabla_7_5] != "") {
                                    var defi_tabla_7_5 = parseInt(response[0][clase_final_tabla_7_5]) + parseInt(valor_CAT_tabla_7_5);
                                    // $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(defi_tabla_7_5);
                                    $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                                    let deficiencias = parseFloat(defi_tabla_7_5);
                                    $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                                    let suma_total_deficiencias = parseFloat(defi_tabla_7_5) + dominancia_suma;
                                    $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                                } else {
                                    // $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(parseInt(valor_CAT_tabla_7_5));
                                    $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                                    let deficiencias = parseFloat(valor_CAT_tabla_7_5);
                                    $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                                    let suma_total_deficiencias = parseFloat(valor_CAT_tabla_7_5) + dominancia_suma;
                                    $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                                }
                            }
                        }         
                    });
                };

            }
        break;

        case "Tabla 7.6":
            // Calculo del Ajuste
            var valor_FP_tabla_7_6 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_7_6 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_7_6 = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();
            var valor_FU_tabla_7_6 = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());
            var valor_CAT_tabla_7_6 = parseInt($("#resultado_CAT_"+id_fila_insertar_dato).val());

            var ajuste_tabla_7_6 = (parseInt(valor_CFM1_tabla_7_6) - parseInt(valor_FP_tabla_7_6)) + (parseInt(valor_CFM2_tabla_7_6) - parseInt(valor_FP_tabla_7_6));

            // Calculo clase final
            var literal_tabla_7_6;

            if (ajuste_tabla_7_6 <= -1) {
                literal_tabla_7_6 = "A";
            }else if (ajuste_tabla_7_6 == 0) {
                literal_tabla_7_6 = "B";
            }else if (ajuste_tabla_7_6 >= 1) {
                literal_tabla_7_6 = "C";
            } 
            
            // if (ajuste_tabla_7_6 <= -2) {
            //     literal_tabla_7_6 = "A";
            // }else if (ajuste_tabla_7_6 == -1) {
            //     literal_tabla_7_6 = "B";
            // }else if (ajuste_tabla_7_6 == 0) {
            //     literal_tabla_7_6 = "C";
            // }else if(ajuste_tabla_7_6 == 1){
            //     literal_tabla_7_6 = "D";
            // }
            // else if(ajuste_tabla_7_6 >= 2){
            //     literal_tabla_7_6 = "E";
            // }

            // Calculo de la Clase Final
            var clase_final_tabla_7_6 = valor_FP_tabla_7_6+literal_tabla_7_6;
            
            // calculo deficiencia
            if (!isNaN(ajuste_tabla_7_6) && literal_tabla_7_6 != undefined) {
                $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                if ((parseInt(valor_FP_tabla_7_6) == 3) && (parseInt(valor_CFM1_tabla_7_6) == 2) && (parseInt(valor_CFM2_tabla_7_6) == 3) && (parseInt(valor_FU_tabla_7_6) == 55)||
                    (parseInt(valor_FP_tabla_7_6) == 3) && (parseInt(valor_CFM1_tabla_7_6) == 3) && (parseInt(valor_CFM2_tabla_7_6) == 2) && (parseInt(valor_FU_tabla_7_6) == 55)||
                    (parseInt(valor_FP_tabla_7_6) == 3) && (parseInt(valor_CFM1_tabla_7_6) == 3) && (parseInt(valor_CFM2_tabla_7_6) == 3) && (parseInt(valor_FU_tabla_7_6) == 55)) {
                    clase_final_tabla_7_6 = "3B";    
                    $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_7_6);
                }else if((parseInt(valor_FP_tabla_7_6) == 3) && (parseInt(valor_CFM1_tabla_7_6) == 2) && (parseInt(valor_CFM2_tabla_7_6) == 3) && (parseInt(valor_FU_tabla_7_6) == 65)||
                        (parseInt(valor_FP_tabla_7_6) == 3) && (parseInt(valor_CFM1_tabla_7_6) == 3) && (parseInt(valor_CFM2_tabla_7_6) == 2) && (parseInt(valor_FU_tabla_7_6) == 65)||
                        (parseInt(valor_FP_tabla_7_6) == 3) && (parseInt(valor_CFM1_tabla_7_6) == 3) && (parseInt(valor_CFM2_tabla_7_6) == 3) && (parseInt(valor_FU_tabla_7_6) == 65)){
                    clase_final_tabla_7_6 = "3C";      
                    $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_7_6);
                }else{
                    $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_7_6);
                }
                $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                var selectTabla7_6 = $("#resultado_FU_"+id_fila_insertar_dato);
                if (parseInt(valor_FP_tabla_7_6) == 3 && parseInt(valor_CFM1_tabla_7_6) == 3 && parseInt(valor_CFM2_tabla_7_6) == 3) {

                    //Habilitar y Deshabilitar el FU
                    $("#resultado_FU_"+id_fila_insertar_dato).prop("disabled", false);  
                    // nuevas Opciones
                    var nuevasOpciones = [
                        { value: '', text: 'Seleccione' },                        
                        { value: '55', text: '55' },
                        { value: '65', text: '65' },
                    ];
                    // Obtener las opciones existentes (si las hay)
                    var opcionesExist = selectTabla7_6.children('option');

                    // Filtrar las nuevas opciones para incluir solo las que no existen aún
                    var nuevasOpcionesFiltradas = nuevasOpciones.filter(function (nuevaOpcion) {
                        return !opcionesExist.filter(function () {
                        return this.value === nuevaOpcion.value;
                        }).length;
                    });

                    // Agregar solo las nuevas opciones al select
                    nuevasOpcionesFiltradas.forEach(function (nuevaOpcion) {
                        selectTabla7_6.append('<option value="' + nuevaOpcion.value + '">' + nuevaOpcion.text + '</option>');
                    }); 
                    
                    //console.log(valor_FU_tabla_7_6);

                    if (!isNaN(valor_FU_tabla_7_6)) {
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        // $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(parseInt(valor_FU_tabla_7_6));
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(valor_FU_tabla_7_6);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(valor_FU_tabla_7_6) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }
                    else if(isNaN(valor_FU_tabla_7_6)){
                        $("#resultado_CAT_"+id_fila_insertar_dato).val('');
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();  
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                    }

                    if (!isNaN(valor_CAT_tabla_7_6)) {
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        var defi_tabla_7_6 = parseInt(valor_FU_tabla_7_6) + parseInt(valor_CAT_tabla_7_6);
                        // $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(defi_tabla_7_6);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(defi_tabla_7_6);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(defi_tabla_7_6) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    } 
                    // else {
                    //     $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(parseInt(valor_FU_tabla_7_6));
                    // }
                }else{
                    if ((parseInt(valor_FP_tabla_7_6) == 3) && (parseInt(valor_CFM1_tabla_7_6) == 3) && (parseInt(valor_CFM2_tabla_7_6) == 2) ||
                    (parseInt(valor_FP_tabla_7_6) == 3) && (parseInt(valor_CFM1_tabla_7_6) == 2) && (parseInt(valor_CFM2_tabla_7_6) == 3)) {
                        //Habilitar y Deshabilitar el FU
                        $("#resultado_FU_"+id_fila_insertar_dato).prop("disabled", false);  
                        // nuevas Opciones
                        var nuevasOpciones = [
                            { value: '', text: 'Seleccione' },                        
                            { value: '55', text: '55' },
                            { value: '65', text: '65' },
                        ];
                        // Obtener las opciones existentes (si las hay)
                        var opcionesExist = selectTabla7_6.children('option');

                        // Filtrar las nuevas opciones para incluir solo las que no existen aún
                        var nuevasOpcionesFiltradas = nuevasOpciones.filter(function (nuevaOpcion) {
                            return !opcionesExist.filter(function () {
                            return this.value === nuevaOpcion.value;
                            }).length;
                        });

                        // Agregar solo las nuevas opciones al select
                        nuevasOpcionesFiltradas.forEach(function (nuevaOpcion) {
                            selectTabla7_6.append('<option value="' + nuevaOpcion.value + '">' + nuevaOpcion.text + '</option>');
                        }); 
                    } else {
                        $("#resultado_FU_"+id_fila_insertar_dato).empty();
                        selectTabla7_6.append('<option value="">Seleccione</option>');
                        $("#guardar_FU_fila_"+id_fila_insertar_dato).val("");
                        $("#resultado_FU_"+id_fila_insertar_dato).prop("disabled", true);
                    }

                    let datos_consulta_deficiencia_tabla_7_6 = {
                        '_token': token,
                        'columna': clase_final_tabla_7_6,
                        'Id_tabla': id_tabla
                    };
                    $.ajax({
                        url: "/consultaValorDeficiencia",
                        type: "post",
                        data: datos_consulta_deficiencia_tabla_7_6,
                        success:function(response){
                            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                            // $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(response[0][clase_final_tabla_7_6]);
                            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                            let deficiencias = parseFloat(response[0][clase_final_tabla_7_6]);
                            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                            let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_7_6]) + dominancia_suma;
                            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                            if (!isNaN(valor_CAT_tabla_7_6)) {
                                $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                                $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                                $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                                if (response[0][clase_final_tabla_7_6] != "") {
                                    var defi_tabla_7_6 = parseInt(response[0][clase_final_tabla_7_6]) + parseInt(valor_CAT_tabla_7_6);
                                    // $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(defi_tabla_7_6);
                                    $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                                    let deficiencias = parseFloat(defi_tabla_7_6);
                                    $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                                    let suma_total_deficiencias = parseFloat(defi_tabla_7_6) + dominancia_suma;
                                    $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                                } else {
                                    // $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(parseInt(valor_CAT_tabla_7_6));
                                    $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                                    let deficiencias = parseFloat(valor_CAT_tabla_7_6);
                                    $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                                    let suma_total_deficiencias = parseFloat(valor_CAT_tabla_7_6) + dominancia_suma;
                                    $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                                }
                            }
                        }         
                    });
                };

            }
        break;

        case "Tabla 7.7":
            // Calculo del Ajuste
            var valor_FP_tabla_7_7 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_7_7 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CAT_tabla_7_7 = parseInt($("#resultado_CAT_"+id_fila_insertar_dato).val());

            var ajuste_tabla_7_7;
            if (parseInt(valor_CFM1_tabla_7_7) == "" && parseInt(valor_FP_tabla_7_7) == 4) {
                ajuste_tabla_7_7 = -1;
            } else {
                ajuste_tabla_7_7 = parseInt(valor_CFM1_tabla_7_7) - parseInt(valor_FP_tabla_7_7);
            }

            // Calculo clase final
            var literal_tabla_7_7;
            

            if (ajuste_tabla_7_7 <= -1) {
                literal_tabla_7_7 = "A";
            }else if (ajuste_tabla_7_7 == 0) {
                literal_tabla_7_7 = "B";
            }else if (ajuste_tabla_7_7 >= 1) {
                literal_tabla_7_7 = "C";
            }

            // Calculo de la Clase Final
            var clase_final_tabla_7_7 = valor_FP_tabla_7_7+literal_tabla_7_7;

            if (!isNaN(ajuste_tabla_7_7) && literal_tabla_7_7 != undefined) {
                // calculo deficiencia
                let datos_consulta_deficiencia_tabla_7_7 = {
                    '_token': token,
                    'columna': clase_final_tabla_7_7,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_7_7,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_7_7);
    
                        if (!isNaN(valor_CAT_tabla_7_7)) {
                            var defi_tabla_7_7 = parseInt(response[0][clase_final_tabla_7_7]) + valor_CAT_tabla_7_7;                            
                            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                            let deficiencias = parseFloat(defi_tabla_7_7);
                            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                            let suma_total_deficiencias = parseFloat(defi_tabla_7_7) + dominancia_suma;
                            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                        } else {
                            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                            let deficiencias = parseFloat(response[0][clase_final_tabla_7_7]);
                            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                            let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_7_7]) + dominancia_suma;
                            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                        }
                    }         
                });
            }
        break;

        case "Tabla 7.8":
            // Calculo del Ajuste
            var valor_FP_tabla_7_8 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_7_8 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CAT_tabla_7_8 = parseInt($("#resultado_CAT_"+id_fila_insertar_dato).val());

            var ajuste_tabla_7_8;
            if (parseInt(valor_CFM1_tabla_7_8) == "" && parseInt(valor_FP_tabla_7_8) == 3) {
                ajuste_tabla_7_8 = -1;
            } else {
                ajuste_tabla_7_8 = parseInt(valor_CFM1_tabla_7_8) - parseInt(valor_FP_tabla_7_8);
            }

            // Calculo clase final
            var literal_tabla_7_8;
            

            if (ajuste_tabla_7_8 <= -1) {
                literal_tabla_7_8 = "A";
            }else if (ajuste_tabla_7_8 == 0) {
                literal_tabla_7_8 = "B";
            }else if (ajuste_tabla_7_8 >= 1) {
                literal_tabla_7_8 = "C";
            }

            // Calculo de la Clase Final
            var clase_final_tabla_7_8 = valor_FP_tabla_7_8+literal_tabla_7_8;

            if (!isNaN(ajuste_tabla_7_8) && literal_tabla_7_8 != undefined) {
                // calculo deficiencia
                let datos_consulta_deficiencia_tabla_7_8 = {
                    '_token': token,
                    'columna': clase_final_tabla_7_8,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_7_8,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
    
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_7_8);
    
                        if (!isNaN(valor_CAT_tabla_7_8)) {
                            var defi_tabla_7_8 = parseInt(response[0][clase_final_tabla_7_8]) + parseInt(valor_CAT_tabla_7_8);                            
                            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                            let deficiencias = parseFloat(defi_tabla_7_8);
                            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                            let suma_total_deficiencias = parseFloat(defi_tabla_7_8) + dominancia_suma;
                            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                        } else {
                            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                            let deficiencias = parseFloat(response[0][clase_final_tabla_7_8]);
                            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                            let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_7_8]) + dominancia_suma;
                            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                        }
    
                    }         
                });
            }
        break;

        case "Tabla 8.5":
            // Calculo del Ajuste
            var valor_FP_tabla_8_5 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_8_5 = parseInt($("#guardar_CFM1_fila_"+id_fila_insertar_dato).val());

            var ajuste_tabla_8_5 = parseInt(valor_CFM1_tabla_8_5) - parseInt(valor_FP_tabla_8_5);

            // Calculo clase final
            var literal_tabla_8_5;
            
            if (ajuste_tabla_8_5 <= -1) {
                literal_tabla_8_5 = "A";
            }else if (ajuste_tabla_8_5 == 0) {
                literal_tabla_8_5 = "B";
            }else if (ajuste_tabla_8_5 >= 1) {
                literal_tabla_8_5 = "C";
            }
  

            // Calculo de la Clase Final
            var clase_final_tabla_8_5 = valor_FP_tabla_8_5+literal_tabla_8_5;
            
            if (!isNaN(ajuste_tabla_8_5) && literal_tabla_8_5 != undefined) {
                // calculo deficiencia
                let datos_consulta_deficiencia_tabla_8_5 = {
                    '_token': token,
                    'columna': clase_final_tabla_8_5,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_8_5,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_8_5);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_8_5]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_8_5]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
    
                    }         
                });
            }

        break;

        case "Tabla 8.6":
            // Calculo del Ajuste
            var valor_FP_tabla_8_6 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_8_6 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_8_6 = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_8_6 = (parseInt(valor_CFM1_tabla_8_6) - parseInt(valor_FP_tabla_8_6)) + (parseInt(valor_CFM2_tabla_8_6) - parseInt(valor_FP_tabla_8_6));

            // Calculo clase final
            var literal_tabla_8_6;
            
            if (ajuste_tabla_8_6 <= -2) {
                literal_tabla_8_6 = "A";
            }else if (ajuste_tabla_8_6 == -1) {
                literal_tabla_8_6 = "B";
            }else if (ajuste_tabla_8_6 == 0) {
                literal_tabla_8_6 = "C";
            }else if(ajuste_tabla_8_6 == 1){
                literal_tabla_8_6 = "D";
            }
            else if(ajuste_tabla_8_6 >= 2){
                literal_tabla_8_6 = "E";
            }

            // Calculo de la Clase Final
            var clase_final_tabla_8_6 = valor_FP_tabla_8_6+literal_tabla_8_6;
            
            if (!isNaN(ajuste_tabla_8_6) && literal_tabla_8_6 != undefined) {
                // calculo deficiencia
                let datos_consulta_deficiencia_tabla_8_6 = {
                    '_token': token,
                    'columna': clase_final_tabla_8_6,
                    'Id_tabla': id_tabla
                };
                
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_8_6,
                    success:function(response){
                        
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_8_6);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_8_6]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_8_6]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
    
                    }         
                });
            }
        break;

        case "Tabla 8.7":
            // Calculo del Ajuste
            var valor_FP_tabla_8_7 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_8_7 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_8_7 = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_8_7 = (parseInt(valor_CFM1_tabla_8_7) - parseInt(valor_FP_tabla_8_7)) + (parseInt(valor_CFM2_tabla_8_7) - parseInt(valor_FP_tabla_8_7));

            // Calculo clase final
            var literal_tabla_8_7;
            
            if (ajuste_tabla_8_7 <= -2) {
                literal_tabla_8_7 = "A";
            }else if (ajuste_tabla_8_7 == -1) {
                literal_tabla_8_7 = "B";
            }else if (ajuste_tabla_8_7 == 0) {
                literal_tabla_8_7 = "C";
            }else if(ajuste_tabla_8_7 == 1){
                literal_tabla_8_7 = "D";
            }
            else if(ajuste_tabla_8_7 >= 2){
                literal_tabla_8_7 = "E";
            }

            // Calculo de la Clase Final
            var clase_final_tabla_8_7 = valor_FP_tabla_8_7+literal_tabla_8_7;
            
            if (!isNaN(ajuste_tabla_8_7) && literal_tabla_8_7 != undefined) {
                // calculo deficiencia
                let datos_consulta_deficiencia_tabla_8_7 = {
                    '_token': token,
                    'columna': clase_final_tabla_8_7,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_8_7,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_8_7);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_8_7]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_8_7]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
    
                    }         
                });
            }
        break;

        case "Tabla 8.8":
            // Calculo del Ajuste
            var valor_FP_tabla_8_8 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_8_8 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_8_8 = parseInt(valor_CFM1_tabla_8_8) - parseInt(valor_FP_tabla_8_8);

            // Calculo clase final
            var literal_tabla_8_8;
            
            if (ajuste_tabla_8_8 <= -1) {
                literal_tabla_8_8 = "A";
            }else if (ajuste_tabla_8_8 == 0) {
                literal_tabla_8_8 = "B";
            }else if (ajuste_tabla_8_8 >= 1) {
                literal_tabla_8_8 = "C";
            }
  

            // Calculo de la Clase Final
            var clase_final_tabla_8_8 = valor_FP_tabla_8_8+literal_tabla_8_8;
            
            if (!isNaN(ajuste_tabla_8_8) && literal_tabla_8_8 != undefined) {
                // calculo deficiencia
                let datos_consulta_deficiencia_tabla_8_8 = {
                    '_token': token,
                    'columna': clase_final_tabla_8_8,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_8_8,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_8_8);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_8_8]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_8_8]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
    
                    }         
                });
            }
        break;

        case "Tabla 8.9":
            // Calculo del Ajuste
            var valor_FP_tabla_8_9 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_8_9 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_8_9 = parseInt(valor_CFM1_tabla_8_9) - parseInt(valor_FP_tabla_8_9);

            // Calculo clase final
            var literal_tabla_8_9;
            
            if (ajuste_tabla_8_9 <= -1) {
                literal_tabla_8_9 = "A";
            }else if (ajuste_tabla_8_9 == 0) {
                literal_tabla_8_9 = "B";
            }else if (ajuste_tabla_8_9 >= 1) {
                literal_tabla_8_9 = "C";
            }
  

            // Calculo de la Clase Final
            var clase_final_tabla_8_9 = valor_FP_tabla_8_9+literal_tabla_8_9;
            
            if (!isNaN(ajuste_tabla_8_9) && literal_tabla_8_9 != undefined) {
                // calculo deficiencia
                let datos_consulta_deficiencia_tabla_8_9 = {
                    '_token': token,
                    'columna': clase_final_tabla_8_9,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_8_9,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
    
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_8_9);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_8_9]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_8_9]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
    
                    }         
                });
            }
        break;

        case "Tabla 8.10":
            // Calculo del Ajuste
            var valor_FP_tabla_8_10 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_8_10 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_8_10 = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_8_10 = (parseInt(valor_CFM1_tabla_8_10) - parseInt(valor_FP_tabla_8_10)) + (parseInt(valor_CFM2_tabla_8_10) - parseInt(valor_FP_tabla_8_10));

            // Calculo clase final
            var literal_tabla_8_10;
            
            if (ajuste_tabla_8_10 <= -2) {
                literal_tabla_8_10 = "A";
            }else if (ajuste_tabla_8_10 == -1) {
                literal_tabla_8_10 = "B";
            }else if (ajuste_tabla_8_10 == 0) {
                literal_tabla_8_10 = "C";
            }else if(ajuste_tabla_8_10 == 1){
                literal_tabla_8_10 = "D";
            }
            else if(ajuste_tabla_8_10 >= 2){
                literal_tabla_8_10 = "E";
            }

            // Calculo de la Clase Final
            var clase_final_tabla_8_10 = valor_FP_tabla_8_10+literal_tabla_8_10;
            
            if (!isNaN(ajuste_tabla_8_10) && literal_tabla_8_10 != undefined) {
                // calculo deficiencia
                let datos_consulta_deficiencia_tabla_8_10 = {
                    '_token': token,
                    'columna': clase_final_tabla_8_10,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_8_10,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
    
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_8_10);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_8_10]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_8_10]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
    
                    }         
                });
            }
        break;

        case "Tabla 8.11":
            // Calculo del Ajuste
            var valor_FP_tabla_8_11 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_8_11 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_8_11 = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_8_11 = (parseInt(valor_CFM1_tabla_8_11) - parseInt(valor_FP_tabla_8_11)) + (parseInt(valor_CFM2_tabla_8_11) - parseInt(valor_FP_tabla_8_11));

            // Calculo clase final
            var literal_tabla_8_11;

            if (ajuste_tabla_8_11 <= -1) {
                literal_tabla_8_11 = "A";
            }else if (ajuste_tabla_8_11 == 0) {
                literal_tabla_8_11 = "B";
            }else if (ajuste_tabla_8_11 >= 1) {
                literal_tabla_8_11 = "C";
            }
            
            // if (ajuste_tabla_8_11 <= -2) {
            //     literal_tabla_8_11 = "A";
            // }else if (ajuste_tabla_8_11 == -1) {
            //     literal_tabla_8_11 = "B";
            // }else if (ajuste_tabla_8_11 == 0) {
            //     literal_tabla_8_11 = "C";
            // }else if(ajuste_tabla_8_11 == 1){
            //     literal_tabla_8_11 = "D";
            // }
            // else if(ajuste_tabla_8_11 >= 2){
            //     literal_tabla_8_11 = "E";
            // }

            // Calculo de la Clase Final
            var clase_final_tabla_8_11 = valor_FP_tabla_8_11+literal_tabla_8_11;
            
            if (!isNaN(ajuste_tabla_8_11) && literal_tabla_8_11 != undefined) {
                // calculo deficiencia
                let datos_consulta_deficiencia_tabla_8_11 = {
                    '_token': token,
                    'columna': clase_final_tabla_8_11,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_8_11,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();    
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_8_11);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_8_11]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_8_11]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
    
                    }         
                });
            }
        break;

        case "Tabla 8.12":
            // Calculo del Ajuste
            var valor_FP_tabla_8_12 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_8_12 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_8_12 = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_8_12 = (parseInt(valor_CFM1_tabla_8_12) - parseInt(valor_FP_tabla_8_12)) + (parseInt(valor_CFM2_tabla_8_12) - parseInt(valor_FP_tabla_8_12));
            //console.log(ajuste_tabla_8_12);
            // Calculo clase final
            var literal_tabla_8_12;

            // Si FP=1 sólo se cuenta con 3 opciones de deficiencia a diferencia de las demás clases
            /*
            SI(Y(I55=1;N55<=-1);"A";
                SI(Y(I55=1;N55=0);"B";
                    SI(Y(I55=1;N55>=1);"C";
                        SI(N55<=-2;"A";
                            SI(N55=-1;"B";
                                SI(N55=0;"C";
                                    SI(N55=1;"D";
                                        SI(N55>=2;"E")
                                    )
                                )
                            )
                        )
                    )
                )
            ) 
            */
            if (valor_FP_tabla_8_12 == 1) {
                if (ajuste_tabla_8_12 <= -1) {
                    literal_tabla_8_12 = "A";
                }else if (ajuste_tabla_8_12 == 0) {
                    literal_tabla_8_12 = "B";
                }else if (ajuste_tabla_8_12 >= 1) {
                    literal_tabla_8_12 = "C";
                } 
            } else {
                if (ajuste_tabla_8_12 <= -2) {
                    literal_tabla_8_12 = "A";
                }else if (ajuste_tabla_8_12 == -1) {
                    literal_tabla_8_12 = "B";
                }else if (ajuste_tabla_8_12 == 0) {
                    literal_tabla_8_12 = "C";
                }else if(ajuste_tabla_8_12 == 1){
                    literal_tabla_8_12 = "D";
                }
                else if(ajuste_tabla_8_12 >= 2){
                    literal_tabla_8_12 = "E";
                }                
            }
            
            // Calculo de la Clase Final
            var clase_final_tabla_8_12 = valor_FP_tabla_8_12+literal_tabla_8_12;
            
            if (!isNaN(ajuste_tabla_8_12) && literal_tabla_8_12 != undefined) {
                // calculo deficiencia
                let datos_consulta_deficiencia_tabla_8_12 = {
                    '_token': token,
                    'columna': clase_final_tabla_8_12,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_8_12,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_8_12);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_8_12]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_8_12]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
    
                    }         
                });
            }
        break;

        case "Tabla 8.13":
            // Calculo del Ajuste
            var valor_FP_tabla_8_13 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_8_13 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_8_13 = parseInt(valor_CFM1_tabla_8_13) - parseInt(valor_FP_tabla_8_13);

            // Calculo del Literal
            var literal_tabla_8_13;
            if (ajuste_tabla_8_13 <= -1) {
                literal_tabla_8_13 = "A";
            }else if (ajuste_tabla_8_13 == 0) {
                literal_tabla_8_13 = "B";
            }else if (ajuste_tabla_8_13 >= 1) {
                literal_tabla_8_13 = "C";
            }

            // Calculo de la Clase Final
            var clase_final_tabla_8_13;
            if (parseInt(valor_FP_tabla_8_13) == 2 &&  parseInt(valor_CFM1_tabla_8_13) == 1) {
                clase_final_tabla_8_13 = valor_FP_tabla_8_13+"A";
            }else if(parseInt(valor_FP_tabla_8_13) == 2 &&  parseInt(valor_CFM1_tabla_8_13) == 2){
                clase_final_tabla_8_13 = valor_FP_tabla_8_13+"C";
            }else{
                clase_final_tabla_8_13 = valor_FP_tabla_8_13+literal_tabla_8_13;
            }
            
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_8_13) && literal_tabla_8_13 != undefined) {
                
                let datos_consulta_deficiencia_tabla_8_13 = {
                    '_token': token,
                    'columna': clase_final_tabla_8_13,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_8_13,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_8_13);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_8_13]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_8_13]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 8.14":
            // Calculo del Ajuste
            var valor_FP_tabla_8_14 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_8_14 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_8_14 = parseInt(valor_CFM1_tabla_8_14) - parseInt(valor_FP_tabla_8_14);

            // Calculo del Literal
            var literal_tabla_8_14;
            if (ajuste_tabla_8_14 <= -1) {
                literal_tabla_8_14 = "A";
            }else if (ajuste_tabla_8_14 == 0) {
                literal_tabla_8_14 = "B";
            }else if (ajuste_tabla_8_14 >= 1) {
                literal_tabla_8_14 = "C";
            }

            // Calculo de la Clase Final
            var clase_final_tabla_8_14;
            if (parseInt(valor_FP_tabla_8_14) == 2 &&  parseInt(valor_CFM1_tabla_8_14) == 1) {
                clase_final_tabla_8_14 = valor_FP_tabla_8_14+"A";
            }else if(parseInt(valor_FP_tabla_8_14) == 2 &&  parseInt(valor_CFM1_tabla_8_14) == 2){
                clase_final_tabla_8_14 = valor_FP_tabla_8_14+"C";
            }else{
                clase_final_tabla_8_14 = valor_FP_tabla_8_14+literal_tabla_8_14;
            }
            
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_8_14) && literal_tabla_8_14 != undefined) {
                
                let datos_consulta_deficiencia_tabla_8_14 = {
                    '_token': token,
                    'columna': clase_final_tabla_8_14,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_8_14,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_8_14);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_8_14]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_8_14]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 9.4":
            // Calculo del Ajuste
            var valor_FP_tabla_9_4 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_9_4 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_9_4 = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_9_4 = (parseInt(valor_CFM1_tabla_9_4) - parseInt(valor_FP_tabla_9_4)) + (parseInt(valor_CFM2_tabla_9_4) - parseInt(valor_FP_tabla_9_4));
            // Calculo del Literal
            var literal_tabla_9_4;
            
            if (ajuste_tabla_9_4 <= -2) {
                literal_tabla_9_4 = "A";
            }else if (ajuste_tabla_9_4 == -1) {
                literal_tabla_9_4 = "B";
            }else if (ajuste_tabla_9_4 == 0) {
                literal_tabla_9_4 = "C";
            }else if(ajuste_tabla_9_4 == 1){
                literal_tabla_9_4 = "D";
            }
            else if(ajuste_tabla_9_4 >= 1){
                literal_tabla_9_4 = "E";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_9_4 = valor_FP_tabla_9_4+literal_tabla_9_4;
            
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_9_4) && literal_tabla_9_4 != undefined) {
                
                let datos_consulta_deficiencia_tabla_9_4 = {
                    '_token': token,
                    'columna': clase_final_tabla_9_4,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_9_4,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_9_4);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_9_4]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_9_4]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 10.1":
            // deficiencia
            var valor_FU_tabla_10_1 = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

            // Calculo clase final
            var clase_final_tabla_10_1;
            if (valor_FU_tabla_10_1 == 3 || valor_FU_tabla_10_1 == 6) {
                clase_final_tabla_10_1 = "";
            }

            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_10_1);
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            let deficiencias_10_1 = parseFloat(valor_FU_tabla_10_1);
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias_10_1.toFixed(2));
            let suma_total_deficiencias_10_1 = parseFloat(valor_FU_tabla_10_1) + dominancia_suma;
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias_10_1.toFixed(2));

            // let datos_consulta_deficiencia_tabla_10_1 = {
            //     '_token': token,
            //     'columna': clase_final_tabla_10_1,
            //     'Id_tabla': id_tabla
            // };
            // $.ajax({
            //     url: "/consultaValorDeficiencia",
            //     type: "post",
            //     data: datos_consulta_deficiencia_tabla_10_1,
            //     success:function(response){
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_10_1);
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            //         let deficiencias = parseFloat(response[0][clase_final_tabla_10_1]);
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
            //         let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_10_1]) + dominancia_suma;
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
            //     }         
            // });
        break;

        case "Tabla 10.2":
            // deficiencia
            var valor_FU_tabla_10_2 = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

            // Calculo clase final
            var clase_final_tabla_10_2;
            if (valor_FU_tabla_10_2 == 3 || valor_FU_tabla_10_2 == 6) {
                clase_final_tabla_10_2 = "";
            }

            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_10_2);
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            let deficiencias_10_2 = parseFloat(valor_FU_tabla_10_2);
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias_10_2.toFixed(2));
            let suma_total_deficiencias_10_2 = parseFloat(valor_FU_tabla_10_2) + dominancia_suma;
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias_10_2.toFixed(2));

            // let datos_consulta_deficiencia_tabla_10_2 = {
            //     '_token': token,
            //     'columna': clase_final_tabla_10_2,
            //     'Id_tabla': id_tabla
            // };
            // $.ajax({
            //     url: "/consultaValorDeficiencia",
            //     type: "post",
            //     data: datos_consulta_deficiencia_tabla_10_2,
            //     success:function(response){
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_10_2);
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            //         let deficiencias = parseFloat(response[0][clase_final_tabla_10_2]);
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
            //         let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_10_2]) + dominancia_suma;
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
            //     }         
            // });

        break;

        case "Tabla 10.5":
            // Calculo del Ajuste
            var valor_FP_tabla_10_5 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_10_5 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_10_5 = parseInt(valor_CFM1_tabla_10_5) - parseInt(valor_FP_tabla_10_5);

            // Calculo del Literal
            var literal_tabla_10_5;
            
            if (ajuste_tabla_10_5 <= -1) {
                literal_tabla_10_5 = "A";
            }else if (ajuste_tabla_10_5 == 0) {
                literal_tabla_10_5 = "B";
            }else if (ajuste_tabla_10_5 >= 1) {
                literal_tabla_10_5 = "C";
            }

            // Calculo de la Clase Final
            var clase_final_tabla_10_5 = valor_FP_tabla_10_5+literal_tabla_10_5;
  
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_10_5) && literal_tabla_10_5 != undefined) {
                
                let datos_consulta_deficiencia_tabla_10_5 = {
                    '_token': token,
                    'columna': clase_final_tabla_10_5,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_10_5,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_10_5);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_10_5]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_10_5]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 11.4":
            // deficiencia
            var valor_FU_tabla_11_4 = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

            // Calculo clase final
            var clase_final_tabla_11_4;

            if (valor_FU_tabla_11_4 == 8 || valor_FU_tabla_11_4 ==  15 || valor_FU_tabla_11_4 == 18 || valor_FU_tabla_11_4 == 23) {
                clase_final_tabla_11_4 = "";
            }

            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_11_4);
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            let deficiencias_11_4 = parseFloat(valor_FU_tabla_11_4);
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias_11_4.toFixed(2));
            let suma_total_deficiencias_11_4 = parseFloat(valor_FU_tabla_11_4) + dominancia_suma;
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias_11_4.toFixed(2));

            // let datos_consulta_deficiencia_tabla_11_4 = {
            //     '_token': token,
            //     'columna': clase_final_tabla_11_4,
            //     'Id_tabla': id_tabla
            // };
            // $.ajax({
            //     url: "/consultaValorDeficiencia",
            //     type: "post",
            //     data: datos_consulta_deficiencia_tabla_11_4,
            //     success:function(response){
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_11_4);
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            //         let deficiencias = parseFloat(response[0][clase_final_tabla_11_4]);
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
            //         let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_11_4]) + dominancia_suma;
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
            //     }         
            // });

        break;

        case "Tabla 11.5":
            // deficiencia
            var valor_FU_tabla_11_5 = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

            // Calculo clase final
            var clase_final_tabla_11_5;

            if (valor_FU_tabla_11_5 == 5 || valor_FU_tabla_11_5 == 7 || valor_FU_tabla_11_5 == 10 || valor_FU_tabla_11_5 == 15 ||
                valor_FU_tabla_11_5 == 20 || valor_FU_tabla_11_5 == 23 || valor_FU_tabla_11_5 == 30) {
                clase_final_tabla_11_5 = "";
            }

            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_11_5);
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            let deficiencias_11_5 = parseFloat(valor_FU_tabla_11_5);
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias_11_5.toFixed(2));
            let suma_total_deficiencias_11_5 = parseFloat(valor_FU_tabla_11_5) + dominancia_suma;
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias_11_5.toFixed(2));

            // let datos_consulta_deficiencia_tabla_11_5 = {
            //     '_token': token,
            //     'columna': clase_final_tabla_11_5,
            //     'Id_tabla': id_tabla
            // };
            // $.ajax({
            //     url: "/consultaValorDeficiencia",
            //     type: "post",
            //     data: datos_consulta_deficiencia_tabla_11_5,
            //     success:function(response){
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_11_5);
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            //         let deficiencias = parseFloat(response[0][clase_final_tabla_11_5]);
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
            //         let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_11_5]) + dominancia_suma;
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
            //     }         
            // });

        break;

        case "Tabla 12.1":
            // deficiencia
            var valor_FU_tabla_12_1 = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

            // Calculo clase final
            var clase_final_tabla_12_1;
            
            if (valor_FU_tabla_12_1 == 25) {
                clase_final_tabla_12_1 = "1";
            }else if (valor_FU_tabla_12_1 == 50) {
                clase_final_tabla_12_1 = "2";
            }else if (valor_FU_tabla_12_1 == 75) {
                clase_final_tabla_12_1 = "3";
            }else if (valor_FU_tabla_12_1 == 100) {
                clase_final_tabla_12_1 = "4";
            }

            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_1);
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            let deficiencias_12_1 = parseFloat(valor_FU_tabla_12_1);
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias_12_1.toFixed(2));
            let suma_total_deficiencias_12_1 = parseFloat(valor_FU_tabla_12_1) + dominancia_suma;
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias_12_1.toFixed(2));

            // let datos_consulta_deficiencia_tabla_12_1 = {
            //     '_token': token,
            //     'columna': clase_final_tabla_12_1,
            //     'Id_tabla': id_tabla
            // };
            // $.ajax({
            //     url: "/consultaValorDeficiencia",
            //     type: "post",
            //     data: datos_consulta_deficiencia_tabla_12_1,
            //     success:function(response){
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_1);
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            //         let deficiencias = parseFloat(response[0][clase_final_tabla_12_1]);
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
            //         let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_12_1]) + dominancia_suma;
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
            //     }         
            // });

        break;

        case "Tabla 12.2":
            // deficiencia
            var valor_FU_tabla_12_2 = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

            // Calculo clase final
            var clase_final_tabla_12_2;

            if (valor_FU_tabla_12_2 == 20 || valor_FU_tabla_12_2 == 25) {
                clase_final_tabla_12_2 = "1";
            }else if (valor_FU_tabla_12_2 == 40 || valor_FU_tabla_12_2 == 45) {
                clase_final_tabla_12_2 = "2";
            }else if (valor_FU_tabla_12_2 == 50 || valor_FU_tabla_12_2 == 55) {
                clase_final_tabla_12_2 = "3";
            }else if (valor_FU_tabla_12_2 == 60 || valor_FU_tabla_12_2 == 65) {
                clase_final_tabla_12_2 = "4";
            }else if (valor_FU_tabla_12_2 == 80) {
                clase_final_tabla_12_2 = "N/A";
            }

            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();

            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_2);                    
            let deficiencias_12_2 = parseFloat(valor_FU_tabla_12_2);
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias_12_2.toFixed(2));

            // Auto seleccionado de la columna MSD cuando cumple las siguientes validaciones
            if (valor_FU_tabla_12_2 == 25 || valor_FU_tabla_12_2 == 45 ||
                valor_FU_tabla_12_2 == 55 || valor_FU_tabla_12_2 == 65
            ) {
                $("#resultado_MSD_"+id_fila_insertar_dato).prop("checked", true);                    
            } else {
                $("#resultado_MSD_"+id_fila_insertar_dato).prop("checked", false); 
            }

            // let datos_consulta_deficiencia_tabla_12_2 = {
            //     '_token': token,
            //     'columna': clase_final_tabla_12_2,
            //     'Id_tabla': id_tabla
            // };
            // $.ajax({
            //     url: "/consultaValorDeficiencia",
            //     type: "post",
            //     data: datos_consulta_deficiencia_tabla_12_2,
            //     success:function(response){
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();

            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_2);                    
            //         let deficiencias = parseFloat(response[0][clase_final_tabla_12_2]);
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
            //     }         
            // });

        break;

        case "Tabla 12.3":
            // deficiencia
            var valor_FU_tabla_12_3 = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

            // Calculo clase final
            var clase_final_tabla_12_3;
            
            if (valor_FU_tabla_12_3 == 10) {
                clase_final_tabla_12_3 = "1";
            }else if (valor_FU_tabla_12_3 == 20) {
                clase_final_tabla_12_3 = "2";
            }else if (valor_FU_tabla_12_3 == 35) {
                clase_final_tabla_12_3 = "3";
            }else if (valor_FU_tabla_12_3 == 50) {
                clase_final_tabla_12_3 = "4";
            }

            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_3);
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            let deficiencias_12_3 = parseFloat(valor_FU_tabla_12_3);
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias_12_3.toFixed(2));
            let suma_total_deficiencias_12_3 = parseFloat(valor_FU_tabla_12_3) + dominancia_suma;
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias_12_3.toFixed(2));

            // let datos_consulta_deficiencia_tabla_12_3 = {
            //     '_token': token,
            //     'columna': clase_final_tabla_12_3,
            //     'Id_tabla': id_tabla
            // };
            // $.ajax({
            //     url: "/consultaValorDeficiencia",
            //     type: "post",
            //     data: datos_consulta_deficiencia_tabla_12_3,
            //     success:function(response){
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_3);
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            //         let deficiencias = parseFloat(response[0][clase_final_tabla_12_3]);
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
            //         let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_12_3]) + dominancia_suma;
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
            //     }         
            // });

        break;

        case "Tabla 12.4A":
            // deficiencia
            var valor_FU_tabla_12_4A = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

            // Calculo clase final
            var clase_final_tabla_12_4A;
            	
            if (valor_FU_tabla_12_4A == 10) {
                clase_final_tabla_12_4A = "1";
            }else if (valor_FU_tabla_12_4A == 15) {
                clase_final_tabla_12_4A = "2";
            }else if (valor_FU_tabla_12_4A == 20) {
                clase_final_tabla_12_4A = "3";
            }else if (valor_FU_tabla_12_4A == 25) {
                clase_final_tabla_12_4A = "4";
            }

            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_4A);
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            let deficiencias_12_4A = parseFloat(valor_FU_tabla_12_4A);
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias_12_4A.toFixed(2));
            let suma_total_deficiencias_12_4A = parseFloat(valor_FU_tabla_12_4A) + dominancia_suma;
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias_12_4A.toFixed(2));

            // let datos_consulta_deficiencia_tabla_12_4A = {
            //     '_token': token,
            //     'columna': clase_final_tabla_12_4A,
            //     'Id_tabla': id_tabla
            // };
            // $.ajax({
            //     url: "/consultaValorDeficiencia",
            //     type: "post",
            //     data: datos_consulta_deficiencia_tabla_12_4A,
            //     success:function(response){
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_4A);
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            //         let deficiencias = parseFloat(response[0][clase_final_tabla_12_4A]);
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
            //         let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_12_4A]) + dominancia_suma;
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
            //     }         
            // });

        break;

        case "Tabla 12.4B":
            // deficiencia
            var valor_FU_tabla_12_4B = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

            // Calculo clase final
            var clase_final_tabla_12_4B;
            
            if (valor_FU_tabla_12_4B == 5) {
                clase_final_tabla_12_4B = "1";
            }else if (valor_FU_tabla_12_4B == 10) {
                clase_final_tabla_12_4B = "2";
            }else if (valor_FU_tabla_12_4B == 15) {
                clase_final_tabla_12_4B = "3";
            }else if (valor_FU_tabla_12_4B == 20) {
                clase_final_tabla_12_4B = "4";
            }

            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_4B);
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            let deficiencias_12_4B = parseFloat(valor_FU_tabla_12_4B);
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias_12_4B.toFixed(2));
            let suma_total_deficiencias_12_4B = parseFloat(valor_FU_tabla_12_4B) + dominancia_suma;
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias_12_4B.toFixed(2));

            // let datos_consulta_deficiencia_tabla_12_4B = {
            //     '_token': token,
            //     'columna': clase_final_tabla_12_4B,
            //     'Id_tabla': id_tabla
            // };
            // $.ajax({
            //     url: "/consultaValorDeficiencia",
            //     type: "post",
            //     data: datos_consulta_deficiencia_tabla_12_4B,
            //     success:function(response){
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_4B);
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            //         let deficiencias = parseFloat(response[0][clase_final_tabla_12_4B]);
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
            //         let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_12_4B]) + dominancia_suma;
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
            //     }         
            // });

        break;

        case "Tabla 12.4C":
            // deficiencia
            var valor_FU_tabla_12_4C = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

            // Calculo clase final
            var clase_final_tabla_12_4C;
            
            if (valor_FU_tabla_12_4C == 5) {
                clase_final_tabla_12_4C = "1";
            }else if (valor_FU_tabla_12_4C == 10) {
                clase_final_tabla_12_4C = "2";
            }else if (valor_FU_tabla_12_4C == 15) {
                clase_final_tabla_12_4C = "3";
            }

            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_4C);
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            let deficiencias_12_C = parseFloat(valor_FU_tabla_12_4C);
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias_12_C.toFixed(2));
            let suma_total_deficiencias_12_C = parseFloat(valor_FU_tabla_12_4C) + dominancia_suma;
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias_12_C.toFixed(2));

            // if (clase_final_tabla_12_4C == "NADA") {
            //     $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //     $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //     $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //     $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //     let calculo_final = 0.00;
            //     $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(calculo_final.toFixed(2));
            //     $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(calculo_final.toFixed(2));
            //     $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(calculo_final.toFixed(2));
            // }else{
            //     let datos_consulta_deficiencia_tabla_12_4C = {
            //         '_token': token,
            //         'columna': clase_final_tabla_12_4C,
            //         'Id_tabla': id_tabla
            //     };
            //     $.ajax({
            //         url: "/consultaValorDeficiencia",
            //         type: "post",
            //         data: datos_consulta_deficiencia_tabla_12_4C,
            //         success:function(response){
            //             $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //             $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //             $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //             $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //             $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_4C);
            //             $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            //             let deficiencias = parseFloat(response[0][clase_final_tabla_12_4C]);
            //             $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
            //             let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_12_4C]) + dominancia_suma;
            //             $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
            //         }         
            //     });
            // }

        break;

        case "Tabla 12.4D":
            // deficiencia
            var valor_FU_tabla_12_4D = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

            // Calculo clase final
            var clase_final_tabla_12_4D;
            
            if (valor_FU_tabla_12_4D == 10) {
                clase_final_tabla_12_4D = "1";
            }else if (valor_FU_tabla_12_4D == 20) {
                clase_final_tabla_12_4D = "2";
            }else if (valor_FU_tabla_12_4D == 40) {
                clase_final_tabla_12_4D = "3";
            }else if (valor_FU_tabla_12_4D == 60) {
                clase_final_tabla_12_4D = "4";
            }else if (valor_FU_tabla_12_4D == 80) {
                clase_final_tabla_12_4D = "5";                
            }

            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_4D);
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            let deficiencias_12_4D = parseFloat(valor_FU_tabla_12_4D);
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias_12_4D.toFixed(2));
            let suma_total_deficiencias_12_4D = parseFloat(valor_FU_tabla_12_4D) + dominancia_suma;
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias_12_4D.toFixed(2));

            // let datos_consulta_deficiencia_tabla_12_4D = {
            //     '_token': token,
            //     'columna': clase_final_tabla_12_4D,
            //     'Id_tabla': id_tabla
            // };
            // $.ajax({
            //     url: "/consultaValorDeficiencia",
            //     type: "post",
            //     data: datos_consulta_deficiencia_tabla_12_4D,
            //     success:function(response){
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_4D);
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            //         let deficiencias = parseFloat(response[0][clase_final_tabla_12_4D]);
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
            //         let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_12_4D]) + dominancia_suma;
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
            //     }         
            // });

        break;

        case "Tabla 12.5":
            // deficiencia
            var valor_FU_tabla_12_5 = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

            // Calculo clase final
            var clase_final_tabla_12_5;
            
            if (valor_FU_tabla_12_5 == 10) {
                clase_final_tabla_12_5 = "1";
            }else if (valor_FU_tabla_12_5 == 15) {
                clase_final_tabla_12_5 = "2";
            }else if (valor_FU_tabla_12_5 == 20) {
                clase_final_tabla_12_5 = "3";
            }

            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_5);
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            let deficiencias_12_5 = parseFloat(valor_FU_tabla_12_5);
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias_12_5.toFixed(2));
            let suma_total_deficiencias_12_5 = parseFloat(valor_FU_tabla_12_5) + dominancia_suma;
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias_12_5.toFixed(2));

            // let datos_consulta_deficiencia_tabla_12_5 = {
            //     '_token': token,
            //     'columna': clase_final_tabla_12_5,
            //     'Id_tabla': id_tabla
            // };
            // $.ajax({
            //     url: "/consultaValorDeficiencia",
            //     type: "post",
            //     data: datos_consulta_deficiencia_tabla_12_5,
            //     success:function(response){
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_5);
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            //         let deficiencias = parseFloat(response[0][clase_final_tabla_12_5]);
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
            //         let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_12_5]) + dominancia_suma;
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
            //     }         
            // });

        break;

        case "Tabla 12.6":
            // deficiencia
            var valor_FU_tabla_12_6 = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

            // Calculo clase final
            var clase_final_tabla_12_6;
            
            if (valor_FU_tabla_12_6 == 2) {
                clase_final_tabla_12_6 = "1";
            }else if (valor_FU_tabla_12_6 == 3) {
                clase_final_tabla_12_6 = "2";
            }else if (valor_FU_tabla_12_6 == 4) {
                clase_final_tabla_12_6 = "3";
            }else if (valor_FU_tabla_12_6 == 5) {
                clase_final_tabla_12_6 = "4";
            }

            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_6);
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            let deficiencias_12_6 = parseFloat(valor_FU_tabla_12_6);
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias_12_6.toFixed(2));
            let suma_total_deficiencias_12_6 = parseFloat(valor_FU_tabla_12_6) + dominancia_suma;
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias_12_6.toFixed(2));

            // let datos_consulta_deficiencia_tabla_12_6 = {
            //     '_token': token,
            //     'columna': clase_final_tabla_12_6,
            //     'Id_tabla': id_tabla
            // };
            // $.ajax({
            //     url: "/consultaValorDeficiencia",
            //     type: "post",
            //     data: datos_consulta_deficiencia_tabla_12_6,
            //     success:function(response){
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_6);
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            //         let deficiencias = parseFloat(response[0][clase_final_tabla_12_6]);
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
            //         let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_12_6]) + dominancia_suma;
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
            //     }         
            // });

        break;

        case "Tabla 12.7":
            // deficiencia
            var valor_FU_tabla_12_7 = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

            // Calculo clase final
            var clase_final_tabla_12_7;
            // 		
            if (valor_FU_tabla_12_7 == 1) {
                clase_final_tabla_12_7 = "1";
            }else if (valor_FU_tabla_12_7 == 5) {
                clase_final_tabla_12_7 = "2";
            }else if (valor_FU_tabla_12_7 == 10) {
                clase_final_tabla_12_7 = "3";
            }

            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_7);
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            let deficiencias_12_7 = parseFloat(valor_FU_tabla_12_7);
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias_12_7.toFixed(2));
            let suma_total_deficiencias_12_7 = parseFloat(valor_FU_tabla_12_7) + dominancia_suma;
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias_12_7.toFixed(2));

            // let datos_consulta_deficiencia_tabla_12_7 = {
            //     '_token': token,
            //     'columna': clase_final_tabla_12_7,
            //     'Id_tabla': id_tabla
            // };
            // $.ajax({
            //     url: "/consultaValorDeficiencia",
            //     type: "post",
            //     data: datos_consulta_deficiencia_tabla_12_7,
            //     success:function(response){
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_7);
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            //         let deficiencias = parseFloat(response[0][clase_final_tabla_12_7]);
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
            //         let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_12_7]) + dominancia_suma;
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
            //     }         
            // });

        break;

        case "Tabla 12.8":
            // deficiencia
            var valor_FU_tabla_12_8 = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

            // Calculo clase final
            var clase_final_tabla_12_8;
            // 		
            if (valor_FU_tabla_12_8 == 1) {
                clase_final_tabla_12_8 = "1";
            }else if (valor_FU_tabla_12_8 == 5) {
                clase_final_tabla_12_8 = "2";
            }else if (valor_FU_tabla_12_8 == 10) {
                clase_final_tabla_12_8 = "3";
            }

            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_8);
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            let deficiencias_12_8 = parseFloat(valor_FU_tabla_12_8);
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias_12_8.toFixed(2));
            let suma_total_deficiencias_12_8 = parseFloat(valor_FU_tabla_12_8) + dominancia_suma;
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias_12_8.toFixed(2));

            // let datos_consulta_deficiencia_tabla_12_8 = {
            //     '_token': token,
            //     'columna': clase_final_tabla_12_8,
            //     'Id_tabla': id_tabla
            // };
            // $.ajax({
            //     url: "/consultaValorDeficiencia",
            //     type: "post",
            //     data: datos_consulta_deficiencia_tabla_12_8,
            //     success:function(response){
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_8);
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            //         let deficiencias = parseFloat(response[0][clase_final_tabla_12_8]);
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
            //         let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_12_8]) + dominancia_suma;
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
            //     }         
            // });

        break;

        case "Tabla 12.18":
            // Calculo del Ajuste
            var valor_FP_tabla_12_18 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_12_18 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_12_18 = parseInt(valor_CFM1_tabla_12_18) - parseInt(valor_FP_tabla_12_18);

            // Calculo del Literal
            var literal_tabla_12_18;
            
            if (ajuste_tabla_12_18 <= -1) {
                literal_tabla_12_18 = "A";
            }else if (ajuste_tabla_12_18 == 0) {
                literal_tabla_12_18 = "B";
            }else if (ajuste_tabla_12_18 >= 1) {
                literal_tabla_12_18 = "C";
            }

            // Calculo de la Clase Final
            var clase_final_tabla_12_18 = valor_FP_tabla_12_18+literal_tabla_12_18;
            
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_12_18) && literal_tabla_12_18 != undefined) {
                
                let datos_consulta_deficiencia_tabla_12_18 = {
                    '_token': token,
                    'columna': clase_final_tabla_12_18,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_12_18,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_18);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_12_18]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_12_18]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 12.19":
            // Calculo del Ajuste
            var valor_FP_tabla_12_19 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_12_19 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_12_19 = parseInt(valor_CFM1_tabla_12_19) - parseInt(valor_FP_tabla_12_19);

            // Calculo del Literal
            var literal_tabla_12_19;
            
            if (ajuste_tabla_12_19 <= -1) {
                literal_tabla_12_19 = "A";
            }else if (ajuste_tabla_12_19 == 0) {
                literal_tabla_12_19 = "B";
            }else if (ajuste_tabla_12_19 >= 1) {
                literal_tabla_12_19 = "C";
            }

            // Calculo de la Clase Final
            var clase_final_tabla_12_19 = valor_FP_tabla_12_19+literal_tabla_12_19;
            
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_12_19) && literal_tabla_12_19 != undefined) {
                
                let datos_consulta_deficiencia_tabla_12_19 = {
                    '_token': token,
                    'columna': clase_final_tabla_12_19,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_12_19,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_19);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_12_19]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_12_19]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 12.20A":
            // deficiencia
            var valor_FU_tabla_12_20A = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

            // Calculo clase final
            var clase_final_tabla_12_20A;

            if (valor_FU_tabla_12_20A == 1 || valor_FU_tabla_12_20A == 3) {
                clase_final_tabla_12_20A = "";
            }

            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_20A);
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            let deficiencias_12_20A = parseFloat(valor_FU_tabla_12_20A);
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias_12_20A.toFixed(2));
            let suma_total_deficiencias_12_20A = parseFloat(valor_FU_tabla_12_20A) + dominancia_suma;
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias_12_20A.toFixed(2));

            // let datos_consulta_deficiencia_tabla_12_20A = {
            //     '_token': token,
            //     'columna': clase_final_tabla_12_20A,
            //     'Id_tabla': id_tabla
            // };
            // $.ajax({
            //     url: "/consultaValorDeficiencia",
            //     type: "post",
            //     data: datos_consulta_deficiencia_tabla_12_20A,
            //     success:function(response){
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_20A);
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            //         let deficiencias = parseFloat(response[0][clase_final_tabla_12_20A]);
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
            //         let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_12_20A]) + dominancia_suma;
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
            //     }         
            // });

        break;

        case "Tabla 12.20B":
            // deficiencia
            var valor_FU_tabla_12_20B = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

            // Calculo clase final
            var clase_final_tabla_12_20B;

            if (valor_FU_tabla_12_20B == 5 || valor_FU_tabla_12_20B == 10 || valor_FU_tabla_12_20B == 35 || valor_FU_tabla_12_20B == 45) {
                clase_final_tabla_12_20B = "";
            }

            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_20B);
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            let deficiencias_12_20B = parseFloat(valor_FU_tabla_12_20B);
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias_12_20B.toFixed(2));
            let suma_total_deficiencias_12_20B = parseFloat(valor_FU_tabla_12_20B) + dominancia_suma;
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias_12_20B.toFixed(2));

            // let datos_consulta_deficiencia_tabla_12_20B = {
            //     '_token': token,
            //     'columna': clase_final_tabla_12_20B,
            //     'Id_tabla': id_tabla
            // };
            // $.ajax({
            //     url: "/consultaValorDeficiencia",
            //     type: "post",
            //     data: datos_consulta_deficiencia_tabla_12_20B,
            //     success:function(response){
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_20B);
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            //         let deficiencias = parseFloat(response[0][clase_final_tabla_12_20B]);
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
            //         let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_12_20B]) + dominancia_suma;
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
            //     }         
            // });

        break;

        case "Tabla 12.20C":
            // deficiencia
            var valor_FU_tabla_12_20C = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

            // Calculo clase final
            var clase_final_tabla_12_20C;
            	

            if (valor_FU_tabla_12_20C == 15 || valor_FU_tabla_12_20C == 45) {
                clase_final_tabla_12_20C = "";
            }

            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_20C);
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            let deficiencias_12_20C = parseFloat(valor_FU_tabla_12_20C);
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias_12_20C.toFixed(2));
            let suma_total_deficiencias_12_20C = parseFloat(valor_FU_tabla_12_20C) + dominancia_suma;
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias_12_20C.toFixed(2));

            // let datos_consulta_deficiencia_tabla_12_20C = {
            //     '_token': token,
            //     'columna': clase_final_tabla_12_20C,
            //     'Id_tabla': id_tabla
            // };
            // $.ajax({
            //     url: "/consultaValorDeficiencia",
            //     type: "post",
            //     data: datos_consulta_deficiencia_tabla_12_20C,
            //     success:function(response){
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_20C);
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            //         let deficiencias = parseFloat(response[0][clase_final_tabla_12_20C]);
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
            //         let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_12_20C]) + dominancia_suma;
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
            //     }         
            // });

        break;

        case "Tabla 12.20D":
            // deficiencia
            var valor_FU_tabla_12_20D = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

            // Calculo clase final
            var clase_final_tabla_12_20D;

            if (valor_FU_tabla_12_20D == 5 || valor_FU_tabla_12_20D == 7) {
                clase_final_tabla_12_20D = "";
            }

            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_20D);
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            let deficiencias_12_20D = parseFloat(valor_FU_tabla_12_20D);
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias_12_20D.toFixed(2));
            let suma_total_deficiencias_12_20D = parseFloat(valor_FU_tabla_12_20D) + dominancia_suma;
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias_12_20D.toFixed(2));

            // let datos_consulta_deficiencia_tabla_12_20D = {
            //     '_token': token,
            //     'columna': clase_final_tabla_12_20D,
            //     'Id_tabla': id_tabla
            // };
            // $.ajax({
            //     url: "/consultaValorDeficiencia",
            //     type: "post",
            //     data: datos_consulta_deficiencia_tabla_12_20D,
            //     success:function(response){
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_12_20D);
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            //         let deficiencias = parseFloat(response[0][clase_final_tabla_12_20D]);
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
            //         let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_12_20D]) + dominancia_suma;
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
            //     }         
            // });

        break;

        // case "Tabla 13.2A":
        //     // deficiencia
        //     var valor_FU_tabla_13_2A = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

        //     // Calculo clase final
        //     var clase_final_tabla_13_2A;

        //     if (valor_FU_tabla_13_2A == 20) {
        //         clase_final_tabla_13_2A = "1A";
        //     }else if (valor_FU_tabla_13_2A == 40) {
        //         clase_final_tabla_13_2A = "1B";
        //     }else if (valor_FU_tabla_13_2A == 60) {
        //         clase_final_tabla_13_2A = "1C";
        //     }else if (valor_FU_tabla_13_2A == 80) {
        //         clase_final_tabla_13_2A = "1D";
        //     }else if (valor_FU_tabla_13_2A == 100) {
        //         clase_final_tabla_13_2A = "1E";
        //     }

        //     let datos_consulta_deficiencia_tabla_13_2A = {
        //         '_token': token,
        //         'columna': clase_final_tabla_13_2A,
        //         'Id_tabla': id_tabla
        //     };
        //     $.ajax({
        //         url: "/consultaValorDeficiencia",
        //         type: "post",
        //         data: datos_consulta_deficiencia_tabla_13_2A,
        //         success:function(response){
        //             $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_13_2A);
        //             $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
        //             let deficiencias = parseFloat(response[0][clase_final_tabla_13_2A]);
        //             $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
        //             let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_13_2A]) + dominancia_suma;
        //             $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
        //         }         
        //     });

        // break;

        // case "Tabla 13.2B":
        //     // deficiencia
        //     var valor_FU_tabla_13_2B = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

        //     // Calculo clase final
        //     var clase_final_tabla_13_2B;

        //     if (valor_FU_tabla_13_2B == 20) {
        //         clase_final_tabla_13_2B = "1A";
        //     }else if (valor_FU_tabla_13_2B == 40) {
        //         clase_final_tabla_13_2B = "1B";
        //     }else if (valor_FU_tabla_13_2B == 60) {
        //         clase_final_tabla_13_2B = "1C";
        //     }else if (valor_FU_tabla_13_2B == 80) {
        //         clase_final_tabla_13_2B = "1D";
        //     }else if (valor_FU_tabla_13_2B == 100) {
        //         clase_final_tabla_13_2B = "1E";
        //     }

        //     let datos_consulta_deficiencia_tabla_13_2B = {
        //         '_token': token,
        //         'columna': clase_final_tabla_13_2B,
        //         'Id_tabla': id_tabla
        //     };
        //     $.ajax({
        //         url: "/consultaValorDeficiencia",
        //         type: "post",
        //         data: datos_consulta_deficiencia_tabla_13_2B,
        //         success:function(response){
        //             $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_13_2B);
        //             $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
        //             let deficiencias = parseFloat(response[0][clase_final_tabla_13_2B]);
        //             $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
        //             let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_13_2B]) + dominancia_suma;
        //             $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
        //         }         
        //     });

        // break;

        // case "Tabla 13.3A":
        //     // deficiencia
        //     var valor_FU_tabla_13_3A = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

        //     // Calculo clase final
        //     var clase_final_tabla_13_3A;

        //     if (valor_FU_tabla_13_3A == 20) {
        //         clase_final_tabla_13_3A = "1A";
        //     }else if (valor_FU_tabla_13_3A == 40) {
        //         clase_final_tabla_13_3A = "1B";
        //     }

        //     let datos_consulta_deficiencia_tabla_13_3A = {
        //         '_token': token,
        //         'columna': clase_final_tabla_13_3A,
        //         'Id_tabla': id_tabla
        //     };
        //     $.ajax({
        //         url: "/consultaValorDeficiencia",
        //         type: "post",
        //         data: datos_consulta_deficiencia_tabla_13_3A,
        //         success:function(response){
        //             $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_13_3A);
        //             $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
        //             let deficiencias = parseFloat(response[0][clase_final_tabla_13_3A]);
        //             $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
        //             let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_13_3A]) + dominancia_suma;
        //             $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
        //         }         
        //     });

        // break;

        // case "Tabla 13.3B":
        //     // deficiencia
        //     var valor_FU_tabla_13_3B = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

        //     // Calculo clase final
        //     var clase_final_tabla_13_3B;

        //     if (valor_FU_tabla_13_3B == 20) {
        //         clase_final_tabla_13_3B = "1A";
        //     }else if (valor_FU_tabla_13_3B == 40) {
        //         clase_final_tabla_13_3B = "1B";
        //     }

        //     let datos_consulta_deficiencia_tabla_13_3B = {
        //         '_token': token,
        //         'columna': clase_final_tabla_13_3B,
        //         'Id_tabla': id_tabla
        //     };
        //     $.ajax({
        //         url: "/consultaValorDeficiencia",
        //         type: "post",
        //         data: datos_consulta_deficiencia_tabla_13_3B,
        //         success:function(response){
        //             $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_13_3B);
        //             $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
        //             let deficiencias = parseFloat(response[0][clase_final_tabla_13_3B]);
        //             $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
        //             let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_13_3B]) + dominancia_suma;
        //             $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
        //         }         
        //     });

        // break;

        // case "Tabla 13.4A":
        //     // deficiencia
        //     var valor_FU_tabla_13_4A = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

        //     // Calculo clase final
        //     var clase_final_tabla_13_4A;

        //     if (valor_FU_tabla_13_4A == 20) {
        //         clase_final_tabla_13_4A = "1A";
        //     }else if (valor_FU_tabla_13_4A == 40) {
        //         clase_final_tabla_13_4A = "1B";
        //     }

        //     let datos_consulta_deficiencia_tabla_13_4A = {
        //         '_token': token,
        //         'columna': clase_final_tabla_13_4A,
        //         'Id_tabla': id_tabla
        //     };
        //     $.ajax({
        //         url: "/consultaValorDeficiencia",
        //         type: "post",
        //         data: datos_consulta_deficiencia_tabla_13_4A,
        //         success:function(response){
        //             $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_13_4A);
        //             $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
        //             let deficiencias = parseFloat(response[0][clase_final_tabla_13_4A]);
        //             $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
        //             let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_13_4A]) + dominancia_suma;
        //             $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
        //         }         
        //     });

        // break;

        // case "Tabla 13.4B":
        //     // deficiencia
        //     var valor_FU_tabla_13_4B = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

        //     // Calculo clase final
        //     var clase_final_tabla_13_4B;

        //     if (valor_FU_tabla_13_4B == 20) {
        //         clase_final_tabla_13_4B = "1A";
        //     }else if (valor_FU_tabla_13_4B == 40) {
        //         clase_final_tabla_13_4B = "1B";
        //     }

        //     let datos_consulta_deficiencia_tabla_13_4B = {
        //         '_token': token,
        //         'columna': clase_final_tabla_13_4B,
        //         'Id_tabla': id_tabla
        //     };
        //     $.ajax({
        //         url: "/consultaValorDeficiencia",
        //         type: "post",
        //         data: datos_consulta_deficiencia_tabla_13_4B,
        //         success:function(response){
        //             $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_13_4B);
        //             $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
        //             let deficiencias = parseFloat(response[0][clase_final_tabla_13_4B]);
        //             $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
        //             let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_13_4B]) + dominancia_suma;
        //             $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
        //         }         
        //     });

        // break;

        // case "Tabla 13.5":
        //     // deficiencia
        //     var valor_FU_tabla_13_5 = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

        //     // Calculo clase final
        //     var clase_final_tabla_13_5;

        //     if (valor_FU_tabla_13_5 == 20) {
        //         clase_final_tabla_13_5 = "1A";
        //     }else if (valor_FU_tabla_13_5 == 40) {
        //         clase_final_tabla_13_5 = "1B";
        //     }

        //     let datos_consulta_deficiencia_tabla_13_5 = {
        //         '_token': token,
        //         'columna': clase_final_tabla_13_5,
        //         'Id_tabla': id_tabla
        //     };
        //     $.ajax({
        //         url: "/consultaValorDeficiencia",
        //         type: "post",
        //         data: datos_consulta_deficiencia_tabla_13_5,
        //         success:function(response){
        //             $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_13_5);
        //             $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
        //             let deficiencias = parseFloat(response[0][clase_final_tabla_13_5]);
        //             $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
        //             let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_13_5]) + dominancia_suma;
        //             $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
        //         }         
        //     });

        // break;

        // case "Tabla 13.6":
        //     // deficiencia
        //     var valor_FU_tabla_13_6 = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

        //     // Calculo clase final
        //     var clase_final_tabla_13_6;

        //     if (valor_FU_tabla_13_6 == 20) {
        //         clase_final_tabla_13_6 = "1A";
        //     }else if (valor_FU_tabla_13_6 == 40) {
        //         clase_final_tabla_13_6 = "1B";
        //     }else if (valor_FU_tabla_13_6 == 70) {
        //         clase_final_tabla_13_6 = "1C";
        //     }else if (valor_FU_tabla_13_6 == 90) {
        //         clase_final_tabla_13_6 = "1D";
        //     }else if (valor_FU_tabla_13_6 == 100) {
        //         clase_final_tabla_13_6 = "1E";
        //     }

        //     let datos_consulta_deficiencia_tabla_13_6 = {
        //         '_token': token,
        //         'columna': clase_final_tabla_13_6,
        //         'Id_tabla': id_tabla
        //     };
        //     $.ajax({
        //         url: "/consultaValorDeficiencia",
        //         type: "post",
        //         data: datos_consulta_deficiencia_tabla_13_6,
        //         success:function(response){
        //             $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_13_6);
        //             $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
        //             let deficiencias = parseFloat(response[0][clase_final_tabla_13_6]);
        //             $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
        //             let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_13_6]) + dominancia_suma;
        //             $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
        //         }         
        //     });

        // break;

        // case "Tabla 13.7":
        //     // deficiencia
        //     var valor_FU_tabla_13_7 = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

        //     // Calculo clase final
        //     var clase_final_tabla_13_7;
            			
        //     if (valor_FU_tabla_13_7 == 20) {
        //         clase_final_tabla_13_7 = "1A";
        //     }else if (valor_FU_tabla_13_7 == 40) {
        //         clase_final_tabla_13_7 = "1B";
        //     }else if (valor_FU_tabla_13_7 == 60) {
        //         clase_final_tabla_13_7 = "1C";
        //     }else if (valor_FU_tabla_13_7 == 70) {
        //         clase_final_tabla_13_7 = "1D";
        //     }

        //     let datos_consulta_deficiencia_tabla_13_7 = {
        //         '_token': token,
        //         'columna': clase_final_tabla_13_7,
        //         'Id_tabla': id_tabla
        //     };
        //     $.ajax({
        //         url: "/consultaValorDeficiencia",
        //         type: "post",
        //         data: datos_consulta_deficiencia_tabla_13_7,
        //         success:function(response){
        //             $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
        //             $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_13_7);
        //             $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
        //             let deficiencias = parseFloat(response[0][clase_final_tabla_13_7]);
        //             $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
        //             let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_13_7]) + dominancia_suma;
        //             $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
        //         }         
        //     });

        // break;

        case "Tabla 13.8":
            // deficiencia
            var valor_FU_tabla_13_8 = parseInt($("#guardar_FU_fila_"+id_fila_insertar_dato).val());

            // Calculo clase final
            var clase_final_tabla_13_8;
            			
            if (valor_FU_tabla_13_8 == 20) {
                clase_final_tabla_13_8 = "Única";
            }

            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_13_8);
            $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            let deficiencias_13_8 = parseFloat(valor_FU_tabla_13_8);
            $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias_13_8.toFixed(2));
            let suma_total_deficiencias_13_8 = parseFloat(valor_FU_tabla_13_8) + dominancia_suma;
            $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias_13_8.toFixed(2));

            // let datos_consulta_deficiencia_tabla_13_8 = {
            //     '_token': token,
            //     'columna': clase_final_tabla_13_8,
            //     'Id_tabla': id_tabla
            // };
            // $.ajax({
            //     url: "/consultaValorDeficiencia",
            //     type: "post",
            //     data: datos_consulta_deficiencia_tabla_13_8,
            //     success:function(response){
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
            //         $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_13_8);
            //         $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
            //         let deficiencias = parseFloat(response[0][clase_final_tabla_13_8]);
            //         $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
            //         let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_13_8]) + dominancia_suma;
            //         $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
            //     }         
            // });

        break;

        case "Tabla 14.15":
            // Calculo del Ajuste
            var valor_FP_tabla_14_15 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_14_15 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_14_15 = parseInt(valor_CFM1_tabla_14_15) - parseInt(valor_FP_tabla_14_15);

            // Calculo del Literal
            var literal_tabla_14_15;
            
            if (ajuste_tabla_14_15 <= -1) {
                literal_tabla_14_15 = "A";
            }else if (ajuste_tabla_14_15 == 0) {
                literal_tabla_14_15 = "B";
            }else if (ajuste_tabla_14_15 >= 1) {
                literal_tabla_14_15 = "C";
            }

            // Calculo de la Clase Final
            var clase_final_tabla_14_15 = valor_FP_tabla_14_15+literal_tabla_14_15;
            
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_14_15) && literal_tabla_14_15 != undefined) {
                
                let datos_consulta_deficiencia_tabla_14_15 = {
                    '_token': token,
                    'columna': clase_final_tabla_14_15,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_14_15,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_14_15);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_14_15]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_14_15]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 15.1A":
            // Calculo del Ajuste
            var valor_FP_tabla_15_1A = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_15_1A = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_15_1A = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            if (valor_CFM2_tabla_15_1A == 'N/A') {
                var ajuste_tabla_15_1A = (parseInt(valor_CFM1_tabla_15_1A) - parseInt(valor_FP_tabla_15_1A));                                
            } else {
                var ajuste_tabla_15_1A = (parseInt(valor_CFM1_tabla_15_1A) - parseInt(valor_FP_tabla_15_1A)) + (parseInt(valor_CFM2_tabla_15_1A) - parseInt(valor_FP_tabla_15_1A));                
            }
            // Calculo del Literal
            var literal_tabla_15_1A;
            
            if (ajuste_tabla_15_1A <= -2) {
                literal_tabla_15_1A = "A";
            }else if (ajuste_tabla_15_1A == -1) {
                literal_tabla_15_1A = "B";
            }else if (ajuste_tabla_15_1A == 0) {
                literal_tabla_15_1A = "C";
            }else if(ajuste_tabla_15_1A == 1){
                literal_tabla_15_1A = "D";
            }
            else if(ajuste_tabla_15_1A >= 2){
                literal_tabla_15_1A = "E";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_15_1A = valor_FP_tabla_15_1A+literal_tabla_15_1A;
            
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_15_1A) && literal_tabla_15_1A != undefined) {
                
                let datos_consulta_deficiencia_tabla_15_1A = {
                    '_token': token,
                    'columna': clase_final_tabla_15_1A,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_15_1A,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_15_1A);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_15_1A]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_15_1A]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 15.1B":
            // Calculo del Ajuste
            var valor_FP_tabla_15_1B = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_15_1B = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_15_1B = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            if (valor_CFM2_tabla_15_1B == 'N/A') {
                var ajuste_tabla_15_1B = (parseInt(valor_CFM1_tabla_15_1B) - parseInt(valor_FP_tabla_15_1B));                                
            } else {
                var ajuste_tabla_15_1B = (parseInt(valor_CFM1_tabla_15_1B) - parseInt(valor_FP_tabla_15_1B)) + (parseInt(valor_CFM2_tabla_15_1B) - parseInt(valor_FP_tabla_15_1B));                
            }
            // Calculo del Literal
            var literal_tabla_15_1B;
            
            if (ajuste_tabla_15_1B <= -2) {
                literal_tabla_15_1B = "A";
            }else if (ajuste_tabla_15_1B == -1) {
                literal_tabla_15_1B = "B";
            }else if (ajuste_tabla_15_1B == 0) {
                literal_tabla_15_1B = "C";
            }else if(ajuste_tabla_15_1B == 1){
                literal_tabla_15_1B = "D";
            }
            else if(ajuste_tabla_15_1B >= 2){
                literal_tabla_15_1B = "E";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_15_1B = valor_FP_tabla_15_1B+literal_tabla_15_1B;
            
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_15_1B) && literal_tabla_15_1B != undefined) {
                
                let datos_consulta_deficiencia_tabla_15_1B = {
                    '_token': token,
                    'columna': clase_final_tabla_15_1B,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_15_1B,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_15_1B);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_15_1B]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_15_1B]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 15.1C":
            // Calculo del Ajuste
            var valor_FP_tabla_15_1C = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_15_1C = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_15_1C = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            if (valor_CFM2_tabla_15_1C == 'N/A') {
                var ajuste_tabla_15_1C = (parseInt(valor_CFM1_tabla_15_1C) - parseInt(valor_FP_tabla_15_1C));                                
            } else {
                var ajuste_tabla_15_1C = (parseInt(valor_CFM1_tabla_15_1C) - parseInt(valor_FP_tabla_15_1C)) + (parseInt(valor_CFM2_tabla_15_1C) - parseInt(valor_FP_tabla_15_1C));                
            }
            // Calculo del Literal
            var literal_tabla_15_1C;
            
            if (ajuste_tabla_15_1C <= -2) {
                literal_tabla_15_1C = "A";
            }else if (ajuste_tabla_15_1C == -1) {
                literal_tabla_15_1C = "B";
            }else if (ajuste_tabla_15_1C == 0) {
                literal_tabla_15_1C = "C";
            }else if(ajuste_tabla_15_1C == 1){
                literal_tabla_15_1C = "D";
            }
            else if(ajuste_tabla_15_1C >= 2){
                literal_tabla_15_1C = "E";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_15_1C = valor_FP_tabla_15_1C+literal_tabla_15_1C;
            
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_15_1C) && literal_tabla_15_1C != undefined) {
                
                let datos_consulta_deficiencia_tabla_15_1C = {
                    '_token': token,
                    'columna': clase_final_tabla_15_1C,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_15_1C,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_15_1C);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_15_1C]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_15_1C]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 15.2A":
            // Calculo del Ajuste
            var valor_FP_tabla_15_2A = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_15_2A = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_15_2A = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            if (valor_CFM2_tabla_15_2A == 'N/A') {
                var ajuste_tabla_15_2A = (parseInt(valor_CFM1_tabla_15_2A) - parseInt(valor_FP_tabla_15_2A));                                
            } else {
                var ajuste_tabla_15_2A = (parseInt(valor_CFM1_tabla_15_2A) - parseInt(valor_FP_tabla_15_2A)) + (parseInt(valor_CFM2_tabla_15_2A) - parseInt(valor_FP_tabla_15_2A));                
            }
            // Calculo del Literal
            var literal_tabla_15_2A;
            
            if (ajuste_tabla_15_2A <= -2) {
                literal_tabla_15_2A = "A";
            }else if (ajuste_tabla_15_2A == -1) {
                literal_tabla_15_2A = "B";
            }else if (ajuste_tabla_15_2A == 0) {
                literal_tabla_15_2A = "C";
            }else if(ajuste_tabla_15_2A == 1){
                literal_tabla_15_2A = "D";
            }
            else if(ajuste_tabla_15_2A >= 2){
                literal_tabla_15_2A = "E";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_15_2A = valor_FP_tabla_15_2A+literal_tabla_15_2A;
            
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_15_2A) && literal_tabla_15_2A != undefined) {
                
                let datos_consulta_deficiencia_tabla_15_2A = {
                    '_token': token,
                    'columna': clase_final_tabla_15_2A,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_15_2A,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_15_2A);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_15_2A]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_15_2A]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 15.2B":
            // Calculo del Ajuste
            var valor_FP_tabla_15_2B = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_15_2B = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_15_2B = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            if (valor_CFM2_tabla_15_2B == 'N/A') {
                var ajuste_tabla_15_2B = (parseInt(valor_CFM1_tabla_15_2B) - parseInt(valor_FP_tabla_15_2B));                                
            } else {
                var ajuste_tabla_15_2B = (parseInt(valor_CFM1_tabla_15_2B) - parseInt(valor_FP_tabla_15_2B)) + (parseInt(valor_CFM2_tabla_15_2B) - parseInt(valor_FP_tabla_15_2B));                
            }
            // Calculo del Literal
            var literal_tabla_15_2B;
            
            if (ajuste_tabla_15_2B <= -2) {
                literal_tabla_15_2B = "A";
            }else if (ajuste_tabla_15_2B == -1) {
                literal_tabla_15_2B = "B";
            }else if (ajuste_tabla_15_2B == 0) {
                literal_tabla_15_2B = "C";
            }else if(ajuste_tabla_15_2B == 1){
                literal_tabla_15_2B = "D";
            }
            else if(ajuste_tabla_15_2B >= 2){
                literal_tabla_15_2B = "E";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_15_2B = valor_FP_tabla_15_2B+literal_tabla_15_2B;
            
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_15_2B) && literal_tabla_15_2B != undefined) {
                
                let datos_consulta_deficiencia_tabla_15_2B = {
                    '_token': token,
                    'columna': clase_final_tabla_15_2B,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_15_2B,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_15_2B);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_15_2B]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_15_2B]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 15.2C":
            // Calculo del Ajuste
            var valor_FP_tabla_15_2C = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_15_2C = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_15_2C = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            if (valor_CFM2_tabla_15_2C == 'N/A') {
                var ajuste_tabla_15_2C = (parseInt(valor_CFM1_tabla_15_2C) - parseInt(valor_FP_tabla_15_2C));                                
            } else {
                var ajuste_tabla_15_2C = (parseInt(valor_CFM1_tabla_15_2C) - parseInt(valor_FP_tabla_15_2C)) + (parseInt(valor_CFM2_tabla_15_2C) - parseInt(valor_FP_tabla_15_2C));                
            }
            // Calculo del Literal
            var literal_tabla_15_2C;
            
            if (ajuste_tabla_15_2C <= -2) {
                literal_tabla_15_2C = "A";
            }else if (ajuste_tabla_15_2C == -1) {
                literal_tabla_15_2C = "B";
            }else if (ajuste_tabla_15_2C == 0) {
                literal_tabla_15_2C = "C";
            }else if(ajuste_tabla_15_2C == 1){
                literal_tabla_15_2C = "D";
            }
            else if(ajuste_tabla_15_2C >= 2){
                literal_tabla_15_2C = "E";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_15_2C = valor_FP_tabla_15_2C+literal_tabla_15_2C;
            
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_15_2C) && literal_tabla_15_2C != undefined) {
                
                let datos_consulta_deficiencia_tabla_15_2C = {
                    '_token': token,
                    'columna': clase_final_tabla_15_2C,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_15_2C,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_15_2C);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_15_2C]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_15_2C]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 15.3A":
            // Calculo del Ajuste
            var valor_FP_tabla_15_3A = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_15_3A = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_15_3A = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            if (valor_CFM2_tabla_15_3A == 'N/A') {
                var ajuste_tabla_15_3A = (parseInt(valor_CFM1_tabla_15_3A) - parseInt(valor_FP_tabla_15_3A));                                
            } else {
                var ajuste_tabla_15_3A = (parseInt(valor_CFM1_tabla_15_3A) - parseInt(valor_FP_tabla_15_3A)) + (parseInt(valor_CFM2_tabla_15_3A) - parseInt(valor_FP_tabla_15_3A));                
            }
            // Calculo del Literal
            var literal_tabla_15_3A;
            
            if (ajuste_tabla_15_3A <= -2) {
                literal_tabla_15_3A = "A";
            }else if (ajuste_tabla_15_3A == -1) {
                literal_tabla_15_3A = "B";
            }else if (ajuste_tabla_15_3A == 0) {
                literal_tabla_15_3A = "C";
            }else if(ajuste_tabla_15_3A == 1){
                literal_tabla_15_3A = "D";
            }
            else if(ajuste_tabla_15_3A >= 2){
                literal_tabla_15_3A = "E";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_15_3A = valor_FP_tabla_15_3A+literal_tabla_15_3A;
            
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_15_3A) && literal_tabla_15_3A != undefined) {
                
                let datos_consulta_deficiencia_tabla_15_3A = {
                    '_token': token,
                    'columna': clase_final_tabla_15_3A,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_15_3A,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_15_3A);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_15_3A]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_15_3A]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 15.3B":
            // Calculo del Ajuste
            var valor_FP_tabla_15_3B = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_15_3B = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_15_3B = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            if (valor_CFM2_tabla_15_3B == 'N/A') {
                var ajuste_tabla_15_3B = (parseInt(valor_CFM1_tabla_15_3B) - parseInt(valor_FP_tabla_15_3B));                                
            } else {
                var ajuste_tabla_15_3B = (parseInt(valor_CFM1_tabla_15_3B) - parseInt(valor_FP_tabla_15_3B)) + (parseInt(valor_CFM2_tabla_15_3B) - parseInt(valor_FP_tabla_15_3B));                
            }
            // Calculo del Literal
            var literal_tabla_15_3B;
            
            if (ajuste_tabla_15_3B <= -2) {
                literal_tabla_15_3B = "A";
            }else if (ajuste_tabla_15_3B == -1) {
                literal_tabla_15_3B = "B";
            }else if (ajuste_tabla_15_3B == 0) {
                literal_tabla_15_3B = "C";
            }else if(ajuste_tabla_15_3B == 1){
                literal_tabla_15_3B = "D";
            }
            else if(ajuste_tabla_15_3B >= 2){
                literal_tabla_15_3B = "E";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_15_3B = valor_FP_tabla_15_3B+literal_tabla_15_3B;
            
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_15_3B) && literal_tabla_15_3B != undefined) {
                
                let datos_consulta_deficiencia_tabla_15_3B = {
                    '_token': token,
                    'columna': clase_final_tabla_15_3B,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_15_3B,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_15_3B);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_15_3B]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_15_3B]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 15.3C":
            // Calculo del Ajuste
            var valor_FP_tabla_15_3C = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_15_3C = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_15_3C = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            if (valor_CFM2_tabla_15_3C == 'N/A') {
                var ajuste_tabla_15_3C = (parseInt(valor_CFM1_tabla_15_3C) - parseInt(valor_FP_tabla_15_3C));                                
            } else {
                var ajuste_tabla_15_3C = (parseInt(valor_CFM1_tabla_15_3C) - parseInt(valor_FP_tabla_15_3C)) + (parseInt(valor_CFM2_tabla_15_3C) - parseInt(valor_FP_tabla_15_3C));                
            }
            // Calculo del Literal
            var literal_tabla_15_3C;
            
            if (ajuste_tabla_15_3C <= -2) {
                literal_tabla_15_3C = "A";
            }else if (ajuste_tabla_15_3C == -1) {
                literal_tabla_15_3C = "B";
            }else if (ajuste_tabla_15_3C == 0) {
                literal_tabla_15_3C = "C";
            }else if(ajuste_tabla_15_3C == 1){
                literal_tabla_15_3C = "D";
            }
            else if(ajuste_tabla_15_3C >= 2){
                literal_tabla_15_3C = "E";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_15_3C = valor_FP_tabla_15_3C+literal_tabla_15_3C;
            
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_15_3C) && literal_tabla_15_3C != undefined) {
                
                let datos_consulta_deficiencia_tabla_15_3C = {
                    '_token': token,
                    'columna': clase_final_tabla_15_3C,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_15_3C,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_15_3C);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_15_3C]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_15_3C]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;

        case "Tabla 15.4":
            // Calculo del Ajuste
            var valor_FP_tabla_15_4 = $("#guardar_FP_fila_"+id_fila_insertar_dato).val();
            var valor_CFM1_tabla_15_4 = $("#guardar_CFM1_fila_"+id_fila_insertar_dato).val();
            var valor_CFM2_tabla_15_4 = $("#guardar_CFM2_fila_"+id_fila_insertar_dato).val();

            var ajuste_tabla_15_4 = (parseInt(valor_CFM1_tabla_15_4) - parseInt(valor_FP_tabla_15_4)) + (parseInt(valor_CFM2_tabla_15_4) - parseInt(valor_FP_tabla_15_4));
            // Calculo del Literal
            var literal_tabla_15_4;
            
            if (ajuste_tabla_15_4 <= -2) {
                literal_tabla_15_4 = "A";
            }else if (ajuste_tabla_15_4 == -1) {
                literal_tabla_15_4 = "B";
            }else if (ajuste_tabla_15_4 == 0) {
                literal_tabla_15_4 = "C";
            }else if(ajuste_tabla_15_4 == 1){
                literal_tabla_15_4 = "D";
            }
            else if(ajuste_tabla_15_4 >= 2){
                literal_tabla_15_4 = "E";
            }
  
            // Calculo de la Clase Final
            var clase_final_tabla_15_4 = valor_FP_tabla_15_4+literal_tabla_15_4;
            
            // Calculo de la deficiencia
            if (!isNaN(ajuste_tabla_15_4) && literal_tabla_15_4 != undefined) {
                
                let datos_consulta_deficiencia_tabla_15_4 = {
                    '_token': token,
                    'columna': clase_final_tabla_15_4,
                    'Id_tabla': id_tabla
                };
                $.ajax({
                    url: "/consultaValorDeficiencia",
                    type: "post",
                    data: datos_consulta_deficiencia_tabla_15_4,
                    success:function(response){
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).empty();
                        $("#ClaseFinal_fila_alteraciones_"+id_fila_insertar_dato).append(clase_final_tabla_15_4);
                        $("#Dominancia_fila_alteraciones_"+id_fila_insertar_dato).append(dominancia_suma.toFixed(2));
                        let deficiencias = parseFloat(response[0][clase_final_tabla_15_4]);
                        $("#Deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(deficiencias.toFixed(2));
                        let suma_total_deficiencias = parseFloat(response[0][clase_final_tabla_15_4]) + dominancia_suma;
                        $("#Total_deficiencia_fila_alteraciones_"+id_fila_insertar_dato).append(suma_total_deficiencias.toFixed(2));
                    }         
                });
            }
        break;
    }
    
};


/* FUNCIONALIDAD PARA RECOPILAR LOS DATOS POR CADA FILA DE LA TABLA */
$(document).ready(function(){
    $('#guardar_datos_deficiencia_alteraciones').click(function(){
        $("#guardar_datos_deficiencia_alteraciones").prop('disabled', true);
        $("#mostrar_barra_guardar_deficiencias").removeClass('d-none');
        let token = $("input[name='_token']").val();
        var guardar_datos_alteraciones = [];
        var datos_finales_deficiencias_alteraciones = [];
        $("#listado_deficiencia_porfactor tbody tr").each(function (index){
            $(this).children("td").each(function (index2) {

                // extraemos todos los id
                var nombres_ids_alteraciones = $(this).find('*').attr("id");

                if (nombres_ids_alteraciones != undefined) {
                    
                    // Extraemos el id de la tabla
                    if (nombres_ids_alteraciones.startsWith("listado_tablas_fila_alteraciones_")) {
                        var idtabla = $("#"+nombres_ids_alteraciones).val();
                        // console.log(idtabla);
                        guardar_datos_alteraciones.push(idtabla);
                    }

                    // Analizamos si existe un select, input o text para extraer la información.
                    if ($("#"+nombres_ids_alteraciones).val() == "") {
                        var hay_select = '#'+nombres_ids_alteraciones+' select';
                        var hay_input = '#'+nombres_ids_alteraciones+' input';
                        var hay_msd = '#'+nombres_ids_alteraciones+' input';

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

                        // Se extrae la info si se eligió o no el checkbox MSD
                        if ($(hay_msd).attr("id") != undefined) {
                            var check_msd = $(hay_msd).attr("id");
                            if (check_msd.startsWith("resultado_MSD_")) {
                                if($("#"+check_msd).is(':checked')){
                                    // console.log("si msd");
                                    guardar_datos_alteraciones.push("Si");
                                }else{
                                    // console.log("no msd");
                                    guardar_datos_alteraciones.push("No");
                                }
                            }
                        }
                      
                    }
                

                    // Se extrae la info si se eligió o no el checkbox DX PRINCIPAL
                    if (nombres_ids_alteraciones.startsWith("checkbox_dx_principal_DefiAlteraciones_")) {
                        if($("#"+nombres_ids_alteraciones).is(':checked')){
                            // console.log("si dx");
                            guardar_datos_alteraciones.push("Si");
                        }else{
                            // console.log("no dx");
                            guardar_datos_alteraciones.push("No");
                        }
                    }
                    
                }

                if((index2+1) % 12 === 0){
                    guardar_datos_alteraciones.splice(1,1);
                    datos_finales_deficiencias_alteraciones.push(guardar_datos_alteraciones);
                    guardar_datos_alteraciones = [];
                }
            });
        });

        let envio_datos_alteraciones = {
            '_token': token,
            'datos_finales_deficiencias_alteraciones' : datos_finales_deficiencias_alteraciones,
            'Id_evento': $('#Id_Evento_decreto').val(),
            'Id_Asignacion': $('#Id_Asignacion_decreto').val(),
            'Id_proceso': $('#Id_Proceso_decreto').val(),
            'Estado': 'Inactivo',
        };

        $.ajax({
            type:'POST',
            url:'/GuardarDeficienciaAlteracionesRe',
            data: envio_datos_alteraciones,
            success:function(response){
                //console.log(response);
                if (response.parametro == "inserto_informacion_deficiencias") {
                    $('#resultado_insercion_deficiencia').removeClass('d-none');
                    $('#resultado_insercion_deficiencia').addClass('alert-success');
                    $('#resultado_insercion_deficiencia').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(() => {
                        $("#guardar_datos_deficiencia_alteraciones").prop('disabled', false);
                        $("#mostrar_barra_guardar_deficiencias").addClass('d-none');   
                        $('#resultado_insercion_deficiencia').addClass('d-none');
                        $('#resultado_insercion_deficiencia').removeClass('alert-success');
                        $('#resultado_insercion_deficiencia').empty();
                        location.reload();
                    }, 3000);
                }
            }
        });
    });
});

$(document).ready(function(){
    $(document).on('click', "a[id^='btn_remover_deficiencia_alteraciones']", function(){

        let token = $("input[name='_token']").val();
        var datos_fila_quitar_examen = {
            '_token': token,
            'fila' : $(this).data("id_fila_quitar"),
            'Id_evento': $('#Id_Evento_decreto').val(),
            'Id_Asignacion': $('#Id_Asignacion_decreto').val(),
            'Id_proceso': $('#Id_Proceso_decreto').val()
        };
        $.ajax({
            type:'POST',
            url:'/eliminarDeficienciasAteracionesRe',
            data: datos_fila_quitar_examen,
            success:function(response){
                // console.log(response);
                if (response.parametro == "fila_deficiencia_alteracion_eliminada") {
                    $('#resultado_insercion_deficiencia').empty();
                    $('#resultado_insercion_deficiencia').removeClass('d-none');
                    $('#resultado_insercion_deficiencia').addClass('alert-success');
                    $('#resultado_insercion_deficiencia').append('<strong>'+response.mensaje+'</strong>');
                    
                    setTimeout(() => {
                        $('#resultado_insercion_deficiencia').addClass('d-none');
                        $('#resultado_insercion_deficiencia').removeClass('alert-success');
                        $('#resultado_insercion_deficiencia').empty();
                        location.reload();
                    }, 3000);
                }
                /* if (response.total_registros == 0) {
                    $("#conteo_listado_deficiencia_alteraciones").val(response.total_registros);
                } */
            }
        });        

    });
});
// DX principal deficiencias
$(document).ready(function(){
    $(document).on('click', "input[id^='dx_principal_deficiencia_alteraciones_']", function(){        
        var fila = $(this).data("id_fila_dx_principal");
        var checkboxDxPrincipal = document.getElementById('dx_principal_deficiencia_alteraciones_'+fila);        
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
                url:'/actualizarDxPrincipalDeficienciaAlteracionesRe',
                data: datos_actualizar_dxPrincial_deficiencias_alteraciones,
                success:function(response){
                    // console.log(response);
                    if (response.parametro == "fila_dxPrincipalDeficienciaAlteracion_agregado") {
                        $('#resultado_insercion_deficiencia').empty();
                        $('#resultado_insercion_deficiencia').removeClass('d-none');
                        $('#resultado_insercion_deficiencia').addClass('alert-success');
                        $('#resultado_insercion_deficiencia').append('<strong>'+response.mensaje+'</strong>');
                        
                        setTimeout(() => {
                            $('#resultado_insercion_deficiencia').addClass('d-none');
                            $('#resultado_insercion_deficiencia').removeClass('alert-success');
                            $('#resultado_insercion_deficiencia').empty();
                            $('#banderaDxPrincipalDA').val("");
                            location.reload();
                        }, 3000);
                    }                
                }
            });
        }else {     
            banderaDxPrincipalDA = 'NoDxPrincipal_deficiencia_alteraciones';            
            var datos_actualizar_dxPrincial_deficiencias_alteraciones = {
                '_token': token,
                'fila':fila,
                'banderaDxPrincipalDA': banderaDxPrincipalDA,
                'Id_evento': $('#Id_Evento_decreto').val()
            };      
            
            $.ajax({
                type:'POST',
                url:'/actualizarDxPrincipalDeficienciaAlteracionesRe',
                data: datos_actualizar_dxPrincial_deficiencias_alteraciones,
                success:function(response){
                    // console.log(response);
                    if (response.parametro == "fila_dxPrincipalDeficienciaAlteracion_eliminado") {
                        $('#resultado_insercion_deficiencia').empty();
                        $('#resultado_insercion_deficiencia').removeClass('d-none');
                        $('#resultado_insercion_deficiencia').addClass('alert-success');
                        $('#resultado_insercion_deficiencia').append('<strong>'+response.mensaje+'</strong>');
                        
                        setTimeout(() => {
                            $('#resultado_insercion_deficiencia').addClass('d-none');
                            $('#resultado_insercion_deficiencia').removeClass('alert-success');
                            $('#resultado_insercion_deficiencia').empty();
                            $('#banderaDxPrincipalDA').val("");
                            location.reload();
                        }, 3000);
                    }                
                }
            }); 
        }

    });
});
