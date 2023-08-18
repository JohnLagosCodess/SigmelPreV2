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
          '',
          '',
          '',
          '',
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
    var ident_tabla;
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
                ident_tabla = data[1]["Ident_tabla"];
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
                        var select_FP = $('<select id="resultado_FP_'+num_consecutivo_alteraciones+'" class="custom-select resultado_FP_'+num_consecutivo_alteraciones+'">');
                        select_FP.append($("<option>").val("").text(""));
                        $.each(opciones_FP, function(index, insertar_opcion_FP) {
                            var option_FP = $("<option>")
                                .val(insertar_opcion_FP)
                                .text(insertar_opcion_FP);
                            select_FP.append(option_FP);
                        });

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
                        var select_CFM1 = $('<select id="resultado_CFM1_'+num_consecutivo_alteraciones+'" class="custom-select resultado_CFM1_'+num_consecutivo_alteraciones+'">');
                        select_CFM1.append($("<option>").val("").text(""));
                        $.each(opciones_CFM1, function(index, insertar_opcion_CFM1) {
                            var option_CFM1 = $("<option>")
                                .val(insertar_opcion_CFM1)
                                .text(insertar_opcion_CFM1);
                            select_CFM1.append(option_CFM1);
                        });
        
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
                        var select_CFM2 = $('<select id="resultado_CFM2_'+num_consecutivo_alteraciones+'" class="custom-select resultado_CFM2_'+num_consecutivo_alteraciones+'">');
                        select_CFM2.append($("<option>").val("").text(""));
                        $.each(opciones_CFM2, function(index, insertar_opcion_CFM2) {
                            var option_CFM2 = $("<option>")
                                .val(insertar_opcion_CFM2)
                                .text(insertar_opcion_CFM2);
                            select_CFM2.append(option_CFM2);
                        });
        
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
                        var select_FU = $('<select id="resultado_FU_'+num_consecutivo_alteraciones+'" class="custom-select resultado_FU_'+num_consecutivo_alteraciones+'">');
                        select_FU.append($("<option>").val("").text(""));
                        $.each(opciones_FU, function(index, insertar_opcion_FU) {
                            var option_FU = $("<option>")
                                .val(insertar_opcion_FU)
                                .text(insertar_opcion_FU);
                            select_FU.append(option_FU);
                        });
        
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
                        var select_CAT = $('<select id="resultado_CAT_'+num_consecutivo_alteraciones+'" class="custom-select resultado_CAT_'+num_consecutivo_alteraciones+'">');
                        select_CAT.append($("<option>").val("").text(""));
                        $.each(opciones_CAT, function(index, insertar_opcion_CAT) {
                            var option_CAT = $("<option>")
                                .val(insertar_opcion_CAT)
                                .text(insertar_opcion_CAT);
                            select_CAT.append(option_CAT);
                        });
        
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

    });


    /* DISEÑO DE CÁLCULOS */
    var valor_FP_selecciondo;
    $("#resultado_FP_"+num_consecutivo_alteraciones).change(function(){
        valor_FP_selecciondo = $(this).val();
    });

    var valor_CFM1_seleccionado;
    $(".resultado_CFM1_"+num_consecutivo_alteraciones).change(function(){
        valor_CFM1_seleccionado = $(this).val();
    });

    var valor_CFM2_seleccionado;
    $(".resultado_CFM2_"+num_consecutivo_alteraciones).change(function(){
        valor_CFM2_seleccionado = $(this).val();
    });

    var valor_FU_seleccionado;
    $(".resultado_FU_"+num_consecutivo_alteraciones).change(function(){
        valor_FU_seleccionado = $(this).val();
    });

    var valor_CAT_seleccionado;
    $(".resultado_CAT_"+num_consecutivo_alteraciones).change(function(){
        valor_CAT_seleccionado = $(this).val();
    });

    // Tabla 1.3
    setInterval(() => {
        
    }, interval);

};
