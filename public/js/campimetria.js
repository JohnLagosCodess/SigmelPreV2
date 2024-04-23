$(document).ready(function() {
    
    /* INICIALIZACIÓN SELECT 2 SELECTOR Agudeza Ojo Izquierdo */
    $(".agudeza_ojo_izq").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    /* INICIALIZACIÓN SELECT 2 SELECTOR Agudeza Ojo Derecho */
    $(".agudeza_ojo_der").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    let token = $("input[name='_token']").val();

    let datos_listado_selectores_agudeza = {
        '_token': token,
        'parametro':"agudeza_visual"
    };

    /* LLENADO DE DATOS PARA EL SELECT2 DE AGUDEZA OJO IZQUIERDO */
    $.ajax({
        type:'POST',
        url:'/selectoresCalificacionTecnicaPCL',
        data: datos_listado_selectores_agudeza,
        success:function(data){
            //console.log(data);
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#agudeza_ojo_izq').append('<option value="'+data[claves[i]]['Nombre_parametro']+'">'+data[claves[i]]['Nombre_parametro']+'</option>');
            }
        }
    });

    /* LLENADO DE DATOS PARA EL SELECT2 DE AGUDEZA OJO DERECHO */
    $.ajax({
        type:'POST',
        url:'/selectoresCalificacionTecnicaPCL',
        data: datos_listado_selectores_agudeza,
        success:function(data){
            //console.log(data);
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#agudeza_ojo_der').append('<option value="'+data[claves[i]]['Nombre_parametro']+'">'+data[claves[i]]['Nombre_parametro']+'</option>');
            }
        }
    });

    /* LLAMADO DE DATOS PARA CONSTRUIR LA CAMPIMETRIA DE OJO IZQUIERDO Y DERECHO */
    let consulta_campimetria = {
        '_token': token,
        'parametro': "nuevo"
    };

    $.ajax({
        type:'POST',
        url:'/ConsultaCampimetriaXFila',
        data: consulta_campimetria,
        success:function(data) {

            var nombre_filas_bd = Object.keys(data[0]);
            var regex = /(\w)-(\d+\.\d+)/;
            // Construcción de la grilla para el ojo izquierdo
            for (let i = 0; i < 10; i++) {
                var conteo_izq = i + 1;
                let row = $('<tr class="ojo_izquierdo_fila_'+conteo_izq+'" ></tr>');

                for (let j = 0; j < 10; j++) {
                    var conteo_izq2 = j + 1;
                    var cell = $('<td class="text-center coordenadas_izq_'+conteo_izq+'_'+conteo_izq2+'"></td>');
                    var checkbox = $('<input type="checkbox" id="checkbox_izq_'+conteo_izq+'_'+conteo_izq2+'" class="checkbox_izq_'+conteo_izq+'_'+conteo_izq2+'" name="checkbox_izq_'+conteo_izq+'_'+conteo_izq2+'" style="transform: scale(1.2);">');

                    checkbox.val(data[i][nombre_filas_bd[j]]); // setear valores a inputs
                    
                    cell.append(checkbox); // Inserción del checkbox al td
                    row.append(cell);
                }

                $('#tabla_campimetria_ojo_izquierdo').append(row);
            }
            // Construcción de la grilla para el ojo derecho
            for (let a = 0; a < 10; a++) {
                var conteo_der = a + 1;
                let row = $('<tr class="ojo_derecho_fila_'+conteo_der+'" ></tr>');

                for (let m = 0; m < 10; m++) {
                    var conteo_der2 = m + 1;
                    var cell = $('<td class="text-center coordenadas_der_'+conteo_der+'_'+conteo_der2+'"></td>');
                    var checkbox = $('<input type="checkbox" id="checkbox_der_'+conteo_der+'_'+conteo_der2+'" class="checkbox_der_'+conteo_der+'_'+conteo_der2+'" name="checkbox_der_'+conteo_der+'_'+conteo_der2+'" style="transform: scale(1.2);">');
                    
                    checkbox.val(data[a][nombre_filas_bd[m]]); // setear valores a inputs
                    
                    cell.append(checkbox); // Inserción del checkbox al td
                    row.append(cell);
                }

                $('#tabla_campimetria_ojo_derecho').append(row);
            }
        }
    });
 
    /* AJUSTAR EL ANCHO DE LA TABLA LINEAL PARA LAS CAMPIMETRIAS DE OJO IZQUIERDO Y DERECHO */
    setTimeout(() => {
        var ancho_px = $(".coordenadas_izq_7_1").width();
        ancho_px = ancho_px + 2.0;
        $(".ajustar_ancho").css("width", ancho_px+"px");
    }, 2000);


    /* VALIDACIÓN SELECCIÓN CHECKBOX CEGUERA TOTAL */
    var confirmacion_ceguera_total;
    $("#ceguera_total").click(function(){
        
        /* Si fue seleccionado inhabilita lo siguiente:
            - Selector Agudeza Ojo Izquierdo y Selector Agudeza Ojo Derecho
            - Campo Agudeza Ambos Ojos
            - Checkbox Ojo Izquierdo y Checkbox Ojo Derecho
            - Grilla Ojo Izquierdo y Grilla Ojo Derecho
        */
        if ($(this).is(":checked")) {
            confirmacion_ceguera_total = "Si";
            $("#agudeza_ojo_izq").prop("disabled", true);
            $("#agudeza_ojo_izq").val(null).trigger("change");

            $("#agudeza_ojo_der").prop("disabled", true);
            $("#agudeza_ojo_der").val(null).trigger("change");

            $("#agudeza_ambos_ojos").val('');
            $("#agudeza_ambos_ojos").prop("disabled", true);

            $("#todo_ojo_izquierdo").prop("disabled", true);
            $("#todo_ojo_izquierdo").prop("checked", false);
            $("#todo_ojo_derecho").prop("disabled", true);
            $("#todo_ojo_derecho").prop("checked", false);

            $("input[class^='checkbox_izq_']").prop("disabled", true);
            $("input[class^='checkbox_izq_']").prop("checked", false);
            $("input[class^='checkbox_der_']").prop("disabled", true);
            $("input[class^='checkbox_der_']").prop("checked", false);
            suma_seleccion_todos_checkboxs_ojo_izq("setear_todo", ids_todos_checkboxs_ojo_izq, grilla_ojo_izq);
            suma_seleccion_todos_checkboxs_ojo_der("setear_todo", ids_todos_checkboxs_ojo_der, grilla_ojo_der);

            /* SETEO DE VALORES para la tabla de Resultados Ceguera Total: */
            $("#resultado_agudeza_ojo_izquierdo").val(0);
            $("#resultado_agudeza_ojo_derecho").val(0);
            $("#resultado_agudeza_ambos_ojos").val(0);
            $("#resultado_pavf").val(0);
            $("#resultado_dav").val(parseFloat(100 - $("#resultado_pavf").val()));
            $("#resultado_campo_visual_ojo_izq").val(0);
            $("#resultado_campo_visual_ojo_der").val(0);
            $("#resultado_campo_visual_ambos_ojos").val(0);
            $("#resultado_cvf").val(0);
            $("#resultado_dcv").val(100);
            if ($("#resultado_dav").val() > 0 && $("#resultado_dcv").val() > 0) {
                valor_dsv_ceguera = (100 - ((parseFloat($("#resultado_pavf").val()) * parseFloat($("#resultado_cvf").val()))/100));
                if (!isNaN(valor_dsv_ceguera)) {
                    $("#resultado_dsv").val(redondear(valor_dsv_ceguera));
                }
            }else{
                $("#resultado_dsv").val(0);
            }

            if ($("#resultado_dsv").val() > 0) {
                $("#resultado_deficiencia").val($("#resultado_dsv").val());
            } else {
                valor_deficiencia_ceguera = parseFloat($("#resultado_dav").val()) + parseFloat($("#resultado_dcv").val());
                $("#resultado_deficiencia").val(valor_deficiencia_ceguera);
            }

        } else {
            confirmacion_ceguera_total = "No";
            $("#agudeza_ojo_izq").prop("disabled", false);
            $("#agudeza_ojo_der").prop("disabled", false);

            $("#agudeza_ambos_ojos").prop("disabled", false);

            $("#todo_ojo_izquierdo").prop("disabled", false);
            $("#todo_ojo_derecho").prop("disabled", false);

            $("input[class^='checkbox_izq_']").prop("disabled", false);
            $("input[class^='checkbox_der_']").prop("disabled", false);

            /* SETEO DE VALORES para la tabla de Resultados Ceguera Total: */
            $("#resultado_agudeza_ojo_izquierdo").val('');
            $("#resultado_agudeza_ojo_derecho").val('');
            $("#resultado_agudeza_ambos_ojos").val('');
            $("#resultado_pavf").val(''); 
            $("#resultado_dav").val('');
            $("#resultado_campo_visual_ojo_izq").val('');
            $("#resultado_campo_visual_ojo_der").val('');
            $("#resultado_campo_visual_ambos_ojos").val('');
            $("#resultado_cvf").val('');
            $("#resultado_dcv").val('');
            $("#resultado_dsv").val('');
            $("#resultado_deficiencia").val('');
        }
    });

    /* FUNCIONALIDADES CON LOS SELECTORES DE AGUDEZA OJO IZQUIERDO Y DERECHO */
    var agudeza_izq_seleccionada, bandera_agudeza_izq_seleccionada = 0;
    var agudeza_der_seleccionada, bandera_agudeza_der_seleccionada = 0;
    var intervalo;

    $("#agudeza_ojo_izq").change(function(){
        agudeza_izq_seleccionada = parseInt($(this).val());
        $("#resultado_agudeza_ojo_izquierdo").val(agudeza_izq_seleccionada);
        bandera_agudeza_izq_seleccionada = 1;

        setTimeout(() => {
            if($("#resultado_pavf").val() > 100){
                $("#resultado_insercion").empty();
                $("#btn_guardar_agudeza").prop('disabled', true);
                $("#resultado_insercion").removeClass('d-none');
                $("#resultado_insercion").addClass('alert-warning');
                $("#resultado_insercion").append('<i class="fas fa-info-circle"></i> <strong>Importante:</strong> El máximo valor que se puede asignar a la PAVF es de 100');
            }else{
                $("#btn_guardar_agudeza").prop('disabled', false);
                $("#resultado_insercion").addClass('d-none');
                $("#resultado_insercion").removeClass('alert-warning');
                $("#resultado_insercion").empty();
                
            }
        }, 1000);
        
    });

    $("#agudeza_ojo_der").change(function(){
        agudeza_der_seleccionada = parseInt($(this).val());
        $("#resultado_agudeza_ojo_derecho").val(agudeza_der_seleccionada);
        bandera_agudeza_der_seleccionada = 1;
        if (bandera_agudeza_izq_seleccionada == 1 || bandera_agudeza_der_seleccionada == 1) {
            iniciarIntervalo();
        }
        setTimeout(() => {
            if($("#resultado_pavf").val() > 100){
                $("#resultado_insercion").empty();
                $("#btn_guardar_agudeza").prop('disabled', true);
                $("#resultado_insercion").removeClass('d-none');
                $("#resultado_insercion").addClass('alert-warning');
                $("#resultado_insercion").append('<i class="fas fa-info-circle"></i> <strong>Importante:</strong> El máximo valor que se puede asignar a la PAVF es de 100');
            }else{
                $("#btn_guardar_agudeza").prop('disabled', false);
                $("#resultado_insercion").addClass('d-none');
                $("#resultado_insercion").removeClass('alert-warning');
                $("#resultado_insercion").empty();
                
            }
        }, 1000);
    });
    
    /* FUNCIONALIDAD PARA EL CAMPO DE AGUDEZA DE AMBOS OJOS */
    $("#agudeza_ambos_ojos").keyup(function(){
        clearInterval(intervalo);
        bandera_agudeza_izq_seleccionada = 0;
        bandera_agudeza_der_seleccionada = 0;
        if ($(this).val() >= 0 && $(this).val() <= 110) {
            $(".mensaje_fuera_rango").addClass("d-none");
            $("#resultado_agudeza_ambos_ojos").val($(this).val());
            valor_mayor_agudeza = $(this).val();
        }else{
            $(".mensaje_fuera_rango").removeClass("d-none");
            $("#resultado_agudeza_ambos_ojos").val('');
        }
        
    });

    /* FUNCIÓN PARA CALCULAR LA AGUDEZA DE AMBOS OJOS */
    function iniciarIntervalo() {
        intervalo = setInterval(() => {
            // Agudeza ambos ojos
            valor_mayor_agudeza = Math.max(agudeza_der_seleccionada, agudeza_izq_seleccionada);
            if (!isNaN(valor_mayor_agudeza)) {
                $("#agudeza_ambos_ojos").val(redondear(valor_mayor_agudeza));
                $("#resultado_agudeza_ambos_ojos").val(parseFloat(redondear(valor_mayor_agudeza)));
            }
                
        }, 500);
    };
    

    /* FUNCIONALIDAD PARA REALIZAR LOS CALCULOS */
    var valor_mayor_agudeza,valor_pavf,valor_dav, campo_visual_ambos_ojos,valor_dcv,valor_cvf, valor_dsv, valor_deficiencia;

    setInterval(() => {

        if (!$("#ceguera_total").is(":checked")) {
            
            
            // Puntaje Agudeza Visual Funcional (PAVF)
            valor_pavf = (valor_mayor_agudeza*3 + agudeza_izq_seleccionada + agudeza_der_seleccionada)/5;
            if (!isNaN(valor_pavf)) {
                if (valor_pavf > 100) {
                    valor_pavf = 100;
                    $("#resultado_pavf").val(parseFloat(redondear(valor_pavf)));                    
                } else {
                    $("#resultado_pavf").val(parseFloat(redondear(valor_pavf)));                    
                }
            }

            // Deficiencia por Agudeza Visual (DAV)
            if ($("#resultado_agudeza_ojo_izquierdo").val() == "" && $("#resultado_agudeza_ojo_derecho").val() == "") {
                $("#resultado_dav").val(0);
            }else if(valor_pavf > 100){
                $("#resultado_dav").val(0);
            }else{
                valor_dav = 100 - $("#resultado_pavf").val();
                $("#resultado_dav").val(parseFloat(redondear(valor_dav)));
            }

            // Campo Visual Ojo Izquierdo
            $("#resultado_campo_visual_ojo_izq").val(suma_resultados_tabla_lineal_ojo_izq());
            // Campo Visual Ojo Derecho
            $("#resultado_campo_visual_ojo_der").val(suma_resultados_tabla_lineal_ojo_der());
            // Campo Visual Ambos Ojos
            var result_campo_visual_ojo_izq = $("#resultado_campo_visual_ojo_izq").val();
            var result_campo_visual_ojo_der = $("#resultado_campo_visual_ojo_der").val();
            campo_visual_ambos_ojos = Math.min(result_campo_visual_ojo_izq, result_campo_visual_ojo_der);
            if (!isNaN(campo_visual_ambos_ojos)) {
                $("#resultado_campo_visual_ambos_ojos").val(parseFloat(redondear(campo_visual_ambos_ojos)));
            }

            // Deficiencia por Campo Visual (DCV)
            var result_campo_visual_ambos_ojos = $("#resultado_campo_visual_ambos_ojos").val();
            valor_dcv = (parseFloat(result_campo_visual_ambos_ojos) * 3 + parseFloat(result_campo_visual_ojo_izq) + parseFloat(result_campo_visual_ojo_der))/5;
            if (!isNaN(valor_dcv)) {
                $("#resultado_dcv").val(redondear(valor_dcv));
            }

            // Puntaje Campo Visual Funcional (CVF)
            var result_dcv = $("#resultado_dcv").val();
            if (result_dcv == 0) {
                $("#resultado_cvf").val(0);
            } else {
                valor_cvf = 100 - result_dcv;
                $("#resultado_cvf").val(parseFloat(redondear(valor_cvf)));
            }

            // Deficiencia global del Sistema Visual (DSV)
            var result_dav = $("#resultado_dav").val();
            var result_pavf = $("#resultado_pavf").val();
            var result_cvf = $("#resultado_cvf").val();

            if (result_dav > 0 && result_dcv > 0) {
                valor_dsv = (100 - ((parseFloat(result_pavf) * parseFloat(result_cvf))/100));
                if (!isNaN(valor_dsv)) {
                    $("#resultado_dsv").val(redondear(valor_dsv));
                }
            }else{
                $("#resultado_dsv").val(0);
            }

            // Deficiencia
            var resultado_dsv = $("#resultado_dsv").val();
            if (resultado_dsv > 0) {
                $("#resultado_deficiencia").val(resultado_dsv);
            } else {
                valor_deficiencia = parseFloat(result_dav) + parseFloat(result_dcv);
                $("#resultado_deficiencia").val(valor_deficiencia);
            }
            
        } 

    }, 500);

    /* CREACIÓN DE GRILLAS OJO IZQ Y DER PARA ENVIARLAS A LA BASE DE DATOS (PARA TEMAS DE EDICIÓN) */
    var grilla_ojo_izq = [];
    var grilla_ojo_der = [];
    /* CREACIÓN DE ARRAYS CON TODOS LOS ID DE LOS CHECKBOX DE OJO IZQ Y OJO DER PARA USARLOS CUANDO SE SELCCIONEN TODOS DE UNA VEZ
    Y SIRVAN PARA INDICAR CUALES FUERON MARCADOS (PARA TEMAS DE EDICIÓN) */
    var ids_todos_checkboxs_ojo_izq = [];
    var ids_todos_checkboxs_ojo_der = [];
    setTimeout(() => {
        for (var x = 1; x <= 10; x++) {
            var fila_ojo_izq = {
                // "ID_evento": $("#ID_evento").val(),
                // "Id_Asignacion": $("#Id_Asignacion").val(),
                // "Id_proceso": $("#Id_proceso").val(),
            };
            var fila_ojo_der = {
                // "ID_evento": $("#ID_evento").val(),
                // "Id_Asignacion": $("#Id_Asignacion").val(),
                // "Id_proceso": $("#Id_proceso").val(),
            };
    
            for (var z = 1; z <= 10; z++) {
                var nombre_checkbox_ojo_izq = "checkbox_izq_" + x + "_" + z;
                var nombre_checkbox_ojo_der = "checkbox_der_" + x + "_" + z;
                var key_ojo_izq = "InfoFila"+z;
                var key_ojo_der = "InfoFila"+z;

                fila_ojo_izq[key_ojo_izq] = $("#"+nombre_checkbox_ojo_izq).val();
                fila_ojo_der[key_ojo_der] = $("#"+nombre_checkbox_ojo_der).val();
                
                ids_todos_checkboxs_ojo_izq.push(nombre_checkbox_ojo_izq);
                ids_todos_checkboxs_ojo_der.push(nombre_checkbox_ojo_der);

            }

            fila_ojo_izq["Nombre_usuario"] = $("#nombre_usuario").val();
            fila_ojo_izq["F_registro"] = $("#dia_actual").val();

            fila_ojo_der["Nombre_usuario"] = $("#nombre_usuario").val();
            fila_ojo_der["F_registro"] = $("#dia_actual").val();
    
            grilla_ojo_izq.push(fila_ojo_izq);
            grilla_ojo_der.push(fila_ojo_der);
        };
    }, 500);

    /* FUNCIONALIDAD SELECCIÓN DE TODOS LOS CHECKBOXS DEL GRID OJO IZQUIERDO */
    $("#todo_ojo_izquierdo").change(function(){
        if ($(this).is(":checked")) {
            $("input[class^='checkbox_izq_']").prop("checked", true);
            suma_seleccion_todos_checkboxs_ojo_izq("sumar_todo", ids_todos_checkboxs_ojo_izq, grilla_ojo_izq);
        } else {
            $("input[class^='checkbox_izq_']").prop("checked", false);
            suma_seleccion_todos_checkboxs_ojo_izq("setear_todo", ids_todos_checkboxs_ojo_izq, grilla_ojo_izq);
        }
    });

    /* FUNCIONALIDAD PARA SUMAR O RESTAR POR COLUMNAS DEPENDIENDO DE LA SELECCIÓN DE CHECKBOX GRILLA OJO IZQUIERDO */
    var resta_total_ojo_izq_col_1 = 0, resta_total_ojo_izq_col_2 = 0, resta_total_ojo_izq_col_3 = 0;
    var resta_total_ojo_izq_col_4 = 0, resta_total_ojo_izq_col_5 = 0, resta_total_ojo_izq_col_6 = 0;
    var resta_total_ojo_izq_col_7 = 0, resta_total_ojo_izq_col_8 = 0;
    var resta_total_ojo_izq_col_9 = 0, resta_total_ojo_izq_col_10 = 0;

    $(document).on('change', "input[id^='checkbox_izq_']", function(){
        if ($(this).is(":checked")) {
            var id_check_izq_seleccionado = $(this).attr("id");

            // COLUMNA N° 1
            if (id_check_izq_seleccionado == "checkbox_izq_4_1" || id_check_izq_seleccionado == "checkbox_izq_5_1" ||
                id_check_izq_seleccionado == "checkbox_izq_6_1" || id_check_izq_seleccionado == "checkbox_izq_7_1") {

                suma_por_columna(id_check_izq_seleccionado, "resultado_suma_ojo_izq_col_1");
                datos_grilla_ojo_izq(id_check_izq_seleccionado, grilla_ojo_izq, "editar");
            }

            // COLUMNA N° 2
            if (id_check_izq_seleccionado == "checkbox_izq_3_2" || id_check_izq_seleccionado == "checkbox_izq_4_2" ||
                id_check_izq_seleccionado == "checkbox_izq_5_2" || id_check_izq_seleccionado == "checkbox_izq_6_2" ||
                id_check_izq_seleccionado == "checkbox_izq_7_2" || id_check_izq_seleccionado == "checkbox_izq_8_2") {

                suma_por_columna(id_check_izq_seleccionado, "resultado_suma_ojo_izq_col_2");
                datos_grilla_ojo_izq(id_check_izq_seleccionado, grilla_ojo_izq, "editar");
            }
            
            // COLUMNA N° 3
            if (id_check_izq_seleccionado == "checkbox_izq_2_3" || id_check_izq_seleccionado == "checkbox_izq_3_3" || 
                id_check_izq_seleccionado == "checkbox_izq_4_3" || id_check_izq_seleccionado == "checkbox_izq_5_3" || 
                id_check_izq_seleccionado == "checkbox_izq_6_3" || id_check_izq_seleccionado == "checkbox_izq_7_3" || 
                id_check_izq_seleccionado == "checkbox_izq_8_3" || id_check_izq_seleccionado == "checkbox_izq_9_3") {
                
                suma_por_columna(id_check_izq_seleccionado, "resultado_suma_ojo_izq_col_3");
                datos_grilla_ojo_izq(id_check_izq_seleccionado, grilla_ojo_izq, "editar");
            }

            // COLUMNA N° 4
            if (id_check_izq_seleccionado == "checkbox_izq_1_4" || id_check_izq_seleccionado == "checkbox_izq_2_4" ||
                id_check_izq_seleccionado == "checkbox_izq_3_4" || id_check_izq_seleccionado == "checkbox_izq_4_4" ||
                id_check_izq_seleccionado == "checkbox_izq_5_4" || id_check_izq_seleccionado == "checkbox_izq_6_4" ||
                id_check_izq_seleccionado == "checkbox_izq_7_4" || id_check_izq_seleccionado == "checkbox_izq_8_4" ||
                id_check_izq_seleccionado == "checkbox_izq_9_4" || id_check_izq_seleccionado == "checkbox_izq_10_4") {
                
                suma_por_columna(id_check_izq_seleccionado, "resultado_suma_ojo_izq_col_4");
                datos_grilla_ojo_izq(id_check_izq_seleccionado, grilla_ojo_izq, "editar");
            }

            // COLUMNA N° 5
            if (id_check_izq_seleccionado == "checkbox_izq_1_5" || id_check_izq_seleccionado == "checkbox_izq_2_5" ||
                id_check_izq_seleccionado == "checkbox_izq_3_5" || id_check_izq_seleccionado == "checkbox_izq_4_5" ||
                id_check_izq_seleccionado == "checkbox_izq_5_5" || id_check_izq_seleccionado == "checkbox_izq_6_5" ||
                id_check_izq_seleccionado == "checkbox_izq_7_5" || id_check_izq_seleccionado == "checkbox_izq_8_5" ||
                id_check_izq_seleccionado == "checkbox_izq_9_5" || id_check_izq_seleccionado == "checkbox_izq_10_5") {

                suma_por_columna(id_check_izq_seleccionado, "resultado_suma_ojo_izq_col_5");
                datos_grilla_ojo_izq(id_check_izq_seleccionado, grilla_ojo_izq, "editar");
            }

            // COLUMNA N° 6
            if (id_check_izq_seleccionado == "checkbox_izq_1_6" ||id_check_izq_seleccionado == "checkbox_izq_2_6" ||
                id_check_izq_seleccionado == "checkbox_izq_3_6" || id_check_izq_seleccionado == "checkbox_izq_4_6" ||
                id_check_izq_seleccionado == "checkbox_izq_5_6" || id_check_izq_seleccionado == "checkbox_izq_6_6" ||
                id_check_izq_seleccionado == "checkbox_izq_7_6" || id_check_izq_seleccionado == "checkbox_izq_8_6" ||
                id_check_izq_seleccionado == "checkbox_izq_9_6" || id_check_izq_seleccionado == "checkbox_izq_10_6") {
                
                suma_por_columna(id_check_izq_seleccionado, "resultado_suma_ojo_izq_col_6");
                datos_grilla_ojo_izq(id_check_izq_seleccionado, grilla_ojo_izq, "editar");
            }
            
            // COLUMNA N° 7
            if (id_check_izq_seleccionado == "checkbox_izq_1_7" || id_check_izq_seleccionado == "checkbox_izq_2_7" ||
                id_check_izq_seleccionado == "checkbox_izq_3_7" || id_check_izq_seleccionado == "checkbox_izq_4_7" ||
                id_check_izq_seleccionado == "checkbox_izq_5_7" || id_check_izq_seleccionado == "checkbox_izq_6_7" ||
                id_check_izq_seleccionado == "checkbox_izq_7_7" || id_check_izq_seleccionado == "checkbox_izq_8_7" ||
                id_check_izq_seleccionado == "checkbox_izq_9_7" || id_check_izq_seleccionado == "checkbox_izq_10_7") {

                suma_por_columna(id_check_izq_seleccionado, "resultado_suma_ojo_izq_col_7");
                datos_grilla_ojo_izq(id_check_izq_seleccionado, grilla_ojo_izq, "editar");
            }

            // COLUMNA N° 8
            if (id_check_izq_seleccionado == "checkbox_izq_2_8" ||id_check_izq_seleccionado == "checkbox_izq_3_8" ||
                id_check_izq_seleccionado == "checkbox_izq_4_8" || id_check_izq_seleccionado == "checkbox_izq_5_8" ||
                id_check_izq_seleccionado == "checkbox_izq_6_8" || id_check_izq_seleccionado == "checkbox_izq_7_8" ||
                id_check_izq_seleccionado == "checkbox_izq_8_8" || id_check_izq_seleccionado == "checkbox_izq_9_8") {
                
                suma_por_columna(id_check_izq_seleccionado, "resultado_suma_ojo_izq_col_8");
                datos_grilla_ojo_izq(id_check_izq_seleccionado, grilla_ojo_izq, "editar");
            }
            
            // COLUMNA N° 9
            if (id_check_izq_seleccionado == "checkbox_izq_3_9" || id_check_izq_seleccionado == "checkbox_izq_4_9" ||
                id_check_izq_seleccionado == "checkbox_izq_5_9" || id_check_izq_seleccionado == "checkbox_izq_6_9" ||
                id_check_izq_seleccionado == "checkbox_izq_7_9" || id_check_izq_seleccionado == "checkbox_izq_8_9") {
                
                suma_por_columna(id_check_izq_seleccionado, "resultado_suma_ojo_izq_col_9");
                datos_grilla_ojo_izq(id_check_izq_seleccionado, grilla_ojo_izq, "editar");
            }

            // COLUMNA N° 10
            if (id_check_izq_seleccionado == "checkbox_izq_4_10" || id_check_izq_seleccionado == "checkbox_izq_5_10" ||
                id_check_izq_seleccionado == "checkbox_izq_6_10" || id_check_izq_seleccionado == "checkbox_izq_7_10") {
                
                suma_por_columna(id_check_izq_seleccionado, "resultado_suma_ojo_izq_col_10");
                datos_grilla_ojo_izq(id_check_izq_seleccionado, grilla_ojo_izq, "editar");
            }
            
        }else{

            var id_check_izq_seleccionado = $(this).attr("id");
            
            // COLUMNA N° 1
            if (id_check_izq_seleccionado == "checkbox_izq_4_1" || id_check_izq_seleccionado == "checkbox_izq_5_1" ||
                id_check_izq_seleccionado == "checkbox_izq_6_1" || id_check_izq_seleccionado == "checkbox_izq_7_1") {
                
                resta_por_coluna(id_check_izq_seleccionado, resta_total_ojo_izq_col_1, "resultado_suma_ojo_izq_col_1");
                datos_grilla_ojo_izq(id_check_izq_seleccionado, grilla_ojo_izq, "no_editar");
            }

            // COLUMNA N° 2
            if (id_check_izq_seleccionado == "checkbox_izq_3_2" || id_check_izq_seleccionado == "checkbox_izq_4_2" ||
                id_check_izq_seleccionado == "checkbox_izq_5_2" || id_check_izq_seleccionado == "checkbox_izq_6_2" ||
                id_check_izq_seleccionado == "checkbox_izq_7_2" || id_check_izq_seleccionado == "checkbox_izq_8_2") {

                resta_por_coluna(id_check_izq_seleccionado, resta_total_ojo_izq_col_2, "resultado_suma_ojo_izq_col_2");
                datos_grilla_ojo_izq(id_check_izq_seleccionado, grilla_ojo_izq, "no_editar");
            }

            // COLUMNA N° 3
            if (id_check_izq_seleccionado == "checkbox_izq_2_3" || id_check_izq_seleccionado == "checkbox_izq_3_3" || 
                id_check_izq_seleccionado == "checkbox_izq_4_3" || id_check_izq_seleccionado == "checkbox_izq_5_3" || 
                id_check_izq_seleccionado == "checkbox_izq_6_3" || id_check_izq_seleccionado == "checkbox_izq_7_3" || 
                id_check_izq_seleccionado == "checkbox_izq_8_3" || id_check_izq_seleccionado == "checkbox_izq_9_3") {
                
                resta_por_coluna(id_check_izq_seleccionado, resta_total_ojo_izq_col_3, "resultado_suma_ojo_izq_col_3");
                datos_grilla_ojo_izq(id_check_izq_seleccionado, grilla_ojo_izq, "no_editar");
            }

            // COLUMNA N° 4
            if (id_check_izq_seleccionado == "checkbox_izq_1_4" || id_check_izq_seleccionado == "checkbox_izq_2_4" ||
                id_check_izq_seleccionado == "checkbox_izq_3_4" || id_check_izq_seleccionado == "checkbox_izq_4_4" ||
                id_check_izq_seleccionado == "checkbox_izq_5_4" || id_check_izq_seleccionado == "checkbox_izq_6_4" ||
                id_check_izq_seleccionado == "checkbox_izq_7_4" || id_check_izq_seleccionado == "checkbox_izq_8_4" ||
                id_check_izq_seleccionado == "checkbox_izq_9_4" || id_check_izq_seleccionado == "checkbox_izq_10_4") {
                
                resta_por_coluna(id_check_izq_seleccionado, resta_total_ojo_izq_col_4, "resultado_suma_ojo_izq_col_4");
                datos_grilla_ojo_izq(id_check_izq_seleccionado, grilla_ojo_izq, "no_editar");
            }

            // COLUMNA N° 5
            if (id_check_izq_seleccionado == "checkbox_izq_1_5" || id_check_izq_seleccionado == "checkbox_izq_2_5" ||
                id_check_izq_seleccionado == "checkbox_izq_3_5" || id_check_izq_seleccionado == "checkbox_izq_4_5" ||
                id_check_izq_seleccionado == "checkbox_izq_5_5" || id_check_izq_seleccionado == "checkbox_izq_6_5" ||
                id_check_izq_seleccionado == "checkbox_izq_7_5" || id_check_izq_seleccionado == "checkbox_izq_8_5" ||
                id_check_izq_seleccionado == "checkbox_izq_9_5" || id_check_izq_seleccionado == "checkbox_izq_10_5") {
                
                resta_por_coluna(id_check_izq_seleccionado, resta_total_ojo_izq_col_5, "resultado_suma_ojo_izq_col_5");
                datos_grilla_ojo_izq(id_check_izq_seleccionado, grilla_ojo_izq, "no_editar");
            }

            // COLUMNA N° 6
            if (id_check_izq_seleccionado == "checkbox_izq_1_6" ||id_check_izq_seleccionado == "checkbox_izq_2_6" ||
                id_check_izq_seleccionado == "checkbox_izq_3_6" || id_check_izq_seleccionado == "checkbox_izq_4_6" ||
                id_check_izq_seleccionado == "checkbox_izq_5_6" || id_check_izq_seleccionado == "checkbox_izq_6_6" ||
                id_check_izq_seleccionado == "checkbox_izq_7_6" || id_check_izq_seleccionado == "checkbox_izq_8_6" ||
                id_check_izq_seleccionado == "checkbox_izq_9_6" || id_check_izq_seleccionado == "checkbox_izq_10_6") {
                
                resta_por_coluna(id_check_izq_seleccionado, resta_total_ojo_izq_col_6, "resultado_suma_ojo_izq_col_6");
                datos_grilla_ojo_izq(id_check_izq_seleccionado, grilla_ojo_izq, "no_editar");
            }

            // COLUMNA N° 7
            if (id_check_izq_seleccionado == "checkbox_izq_1_7" || id_check_izq_seleccionado == "checkbox_izq_2_7" ||
                id_check_izq_seleccionado == "checkbox_izq_3_7" || id_check_izq_seleccionado == "checkbox_izq_4_7" ||
                id_check_izq_seleccionado == "checkbox_izq_5_7" || id_check_izq_seleccionado == "checkbox_izq_6_7" ||
                id_check_izq_seleccionado == "checkbox_izq_7_7" || id_check_izq_seleccionado == "checkbox_izq_8_7" ||
                id_check_izq_seleccionado == "checkbox_izq_9_7" || id_check_izq_seleccionado == "checkbox_izq_10_7") {

                resta_por_coluna(id_check_izq_seleccionado, resta_total_ojo_izq_col_7, "resultado_suma_ojo_izq_col_7");
                datos_grilla_ojo_izq(id_check_izq_seleccionado, grilla_ojo_izq, "no_editar");
            }

            // COLUMNA N° 8
            if (id_check_izq_seleccionado == "checkbox_izq_2_8" ||id_check_izq_seleccionado == "checkbox_izq_3_8" ||
                id_check_izq_seleccionado == "checkbox_izq_4_8" || id_check_izq_seleccionado == "checkbox_izq_5_8" ||
                id_check_izq_seleccionado == "checkbox_izq_6_8" || id_check_izq_seleccionado == "checkbox_izq_7_8" ||
                id_check_izq_seleccionado == "checkbox_izq_8_8" || id_check_izq_seleccionado == "checkbox_izq_9_8") {
                
                resta_por_coluna(id_check_izq_seleccionado, resta_total_ojo_izq_col_8, "resultado_suma_ojo_izq_col_8");
                datos_grilla_ojo_izq(id_check_izq_seleccionado, grilla_ojo_izq, "no_editar");
            }

            // COLUMNA N° 9
            if (id_check_izq_seleccionado == "checkbox_izq_3_9" || id_check_izq_seleccionado == "checkbox_izq_4_9" ||
                id_check_izq_seleccionado == "checkbox_izq_5_9" || id_check_izq_seleccionado == "checkbox_izq_6_9" ||
                id_check_izq_seleccionado == "checkbox_izq_7_9" || id_check_izq_seleccionado == "checkbox_izq_8_9") {
                
                resta_por_coluna(id_check_izq_seleccionado, resta_total_ojo_izq_col_9, "resultado_suma_ojo_izq_col_9");
                datos_grilla_ojo_izq(id_check_izq_seleccionado, grilla_ojo_izq, "no_editar");
            }
            
            // COLUMNA N° 10
            if (id_check_izq_seleccionado == "checkbox_izq_4_10" || id_check_izq_seleccionado == "checkbox_izq_5_10" ||
                id_check_izq_seleccionado == "checkbox_izq_6_10" || id_check_izq_seleccionado == "checkbox_izq_7_10") {
                
                resta_por_coluna(id_check_izq_seleccionado, resta_total_ojo_izq_col_10, "resultado_suma_ojo_izq_col_10");
                datos_grilla_ojo_izq(id_check_izq_seleccionado, grilla_ojo_izq, "no_editar");
            }

        }

    });

    /* FUNCIONALIDAD SELECCIÓN DE TODOS LOS CHECKBOXS DEL GRID OJO DERECHO */
    $("#todo_ojo_derecho").change(function(){
        if ($(this).is(":checked")) {
            $("input[class^='checkbox_der_']").prop("checked", true);
            suma_seleccion_todos_checkboxs_ojo_der("sumar_todo", ids_todos_checkboxs_ojo_der, grilla_ojo_der);
        } else {
            $("input[class^='checkbox_der_']").prop("checked", false);
            suma_seleccion_todos_checkboxs_ojo_der("setear_todo", ids_todos_checkboxs_ojo_der, grilla_ojo_der);
        }
    });

    /* FUNCIONALIDAD PARA SUMAR O RESTAR POR COLUMNAS DEPENDIENDO DE LA SELECCIÓN DE CHECKBOX GRILLA OJO DERECHO */
    var resta_total_ojo_der_col_1 = 0, resta_total_ojo_der_col_2 = 0, resta_total_ojo_der_col_3 = 0;
    var resta_total_ojo_der_col_4 = 0, resta_total_ojo_der_col_5 = 0, resta_total_ojo_der_col_6 = 0;
    var resta_total_ojo_der_col_7 = 0, resta_total_ojo_der_col_8 = 0;
    var resta_total_ojo_der_col_9 = 0, resta_total_ojo_der_col_10 = 0;

    $(document).on('change', "input[id^='checkbox_der_']", function(){
        if ($(this).is(":checked")) {
            var id_check_der_seleccionado = $(this).attr("id");

            // COLUMNA N° 1
            if (id_check_der_seleccionado == "checkbox_der_4_1" || id_check_der_seleccionado == "checkbox_der_5_1" ||
                id_check_der_seleccionado == "checkbox_der_6_1" || id_check_der_seleccionado == "checkbox_der_7_1") {
                
                suma_por_columna(id_check_der_seleccionado, "resultado_suma_ojo_der_col_1");
                datos_grilla_ojo_der(id_check_der_seleccionado, grilla_ojo_der, "editar");
            }

            // COLUMNA N° 2
            if (id_check_der_seleccionado == "checkbox_der_3_2" || id_check_der_seleccionado == "checkbox_der_4_2" ||
                id_check_der_seleccionado == "checkbox_der_5_2" || id_check_der_seleccionado == "checkbox_der_6_2" ||
                id_check_der_seleccionado == "checkbox_der_7_2" || id_check_der_seleccionado == "checkbox_der_8_2") {
                
                suma_por_columna(id_check_der_seleccionado, "resultado_suma_ojo_der_col_2");
                datos_grilla_ojo_der(id_check_der_seleccionado, grilla_ojo_der, "editar");
            }
            
            // COLUMNA N° 3
            if (id_check_der_seleccionado == "checkbox_der_2_3" || id_check_der_seleccionado == "checkbox_der_3_3" || 
                id_check_der_seleccionado == "checkbox_der_4_3" || id_check_der_seleccionado == "checkbox_der_5_3" || 
                id_check_der_seleccionado == "checkbox_der_6_3" || id_check_der_seleccionado == "checkbox_der_7_3" || 
                id_check_der_seleccionado == "checkbox_der_8_3" || id_check_der_seleccionado == "checkbox_der_9_3") {
                
                suma_por_columna(id_check_der_seleccionado, "resultado_suma_ojo_der_col_3");
                datos_grilla_ojo_der(id_check_der_seleccionado, grilla_ojo_der, "editar");
            }

            // COLUMNA N° 4
            if (id_check_der_seleccionado == "checkbox_der_1_4" || id_check_der_seleccionado == "checkbox_der_2_4" ||
                id_check_der_seleccionado == "checkbox_der_3_4" || id_check_der_seleccionado == "checkbox_der_4_4" ||
                id_check_der_seleccionado == "checkbox_der_5_4" || id_check_der_seleccionado == "checkbox_der_6_4" ||
                id_check_der_seleccionado == "checkbox_der_7_4" || id_check_der_seleccionado == "checkbox_der_8_4" ||
                id_check_der_seleccionado == "checkbox_der_9_4" || id_check_der_seleccionado == "checkbox_der_10_4") {
                
                suma_por_columna(id_check_der_seleccionado, "resultado_suma_ojo_der_col_4");
                datos_grilla_ojo_der(id_check_der_seleccionado, grilla_ojo_der, "editar");
            }

            // COLUMNA N° 5
            if (id_check_der_seleccionado == "checkbox_der_1_5" || id_check_der_seleccionado == "checkbox_der_2_5" ||
                id_check_der_seleccionado == "checkbox_der_3_5" || id_check_der_seleccionado == "checkbox_der_4_5" ||
                id_check_der_seleccionado == "checkbox_der_5_5" || id_check_der_seleccionado == "checkbox_der_6_5" ||
                id_check_der_seleccionado == "checkbox_der_7_5" || id_check_der_seleccionado == "checkbox_der_8_5" ||
                id_check_der_seleccionado == "checkbox_der_9_5" || id_check_der_seleccionado == "checkbox_der_10_5") {

                suma_por_columna(id_check_der_seleccionado, "resultado_suma_ojo_der_col_5");
                datos_grilla_ojo_der(id_check_der_seleccionado, grilla_ojo_der, "editar");
            }

            // COLUMNA N° 6
            if (id_check_der_seleccionado == "checkbox_der_1_6" ||id_check_der_seleccionado == "checkbox_der_2_6" ||
                id_check_der_seleccionado == "checkbox_der_3_6" || id_check_der_seleccionado == "checkbox_der_4_6" ||
                id_check_der_seleccionado == "checkbox_der_5_6" || id_check_der_seleccionado == "checkbox_der_6_6" ||
                id_check_der_seleccionado == "checkbox_der_7_6" || id_check_der_seleccionado == "checkbox_der_8_6" ||
                id_check_der_seleccionado == "checkbox_der_9_6" || id_check_der_seleccionado == "checkbox_der_10_6") {
                
                suma_por_columna(id_check_der_seleccionado, "resultado_suma_ojo_der_col_6");
                datos_grilla_ojo_der(id_check_der_seleccionado, grilla_ojo_der, "editar");
            }
            
            // COLUMNA N° 7
            if (id_check_der_seleccionado == "checkbox_der_1_7" || id_check_der_seleccionado == "checkbox_der_2_7" ||
                id_check_der_seleccionado == "checkbox_der_3_7" || id_check_der_seleccionado == "checkbox_der_4_7" ||
                id_check_der_seleccionado == "checkbox_der_5_7" || id_check_der_seleccionado == "checkbox_der_6_7" ||
                id_check_der_seleccionado == "checkbox_der_7_7" || id_check_der_seleccionado == "checkbox_der_8_7" ||
                id_check_der_seleccionado == "checkbox_der_9_7" || id_check_der_seleccionado == "checkbox_der_10_7") {

                suma_por_columna(id_check_der_seleccionado, "resultado_suma_ojo_der_col_7");
                datos_grilla_ojo_der(id_check_der_seleccionado, grilla_ojo_der, "editar");
                
            }

            // COLUMNA N° 8
            if (id_check_der_seleccionado == "checkbox_der_2_8" ||id_check_der_seleccionado == "checkbox_der_3_8" ||
                id_check_der_seleccionado == "checkbox_der_4_8" || id_check_der_seleccionado == "checkbox_der_5_8" ||
                id_check_der_seleccionado == "checkbox_der_6_8" || id_check_der_seleccionado == "checkbox_der_7_8" ||
                id_check_der_seleccionado == "checkbox_der_8_8" || id_check_der_seleccionado == "checkbox_der_9_8") {
                
                suma_por_columna(id_check_der_seleccionado, "resultado_suma_ojo_der_col_8");
                datos_grilla_ojo_der(id_check_der_seleccionado, grilla_ojo_der, "editar");
            }
            
            // COLUMNA N° 9
            if (id_check_der_seleccionado == "checkbox_der_3_9" || id_check_der_seleccionado == "checkbox_der_4_9" ||
                id_check_der_seleccionado == "checkbox_der_5_9" || id_check_der_seleccionado == "checkbox_der_6_9" ||
                id_check_der_seleccionado == "checkbox_der_7_9" || id_check_der_seleccionado == "checkbox_der_8_9") {
                
                suma_por_columna(id_check_der_seleccionado, "resultado_suma_ojo_der_col_9");
                datos_grilla_ojo_der(id_check_der_seleccionado, grilla_ojo_der, "editar");
            }

            // COLUMNA N° 10
            if (id_check_der_seleccionado == "checkbox_der_4_10" || id_check_der_seleccionado == "checkbox_der_5_10" ||
                id_check_der_seleccionado == "checkbox_der_6_10" || id_check_der_seleccionado == "checkbox_der_7_10") {

                suma_por_columna(id_check_der_seleccionado, "resultado_suma_ojo_der_col_10");
                datos_grilla_ojo_der(id_check_der_seleccionado, grilla_ojo_der, "editar");
            }
            
        }else{

            var id_check_der_seleccionado = $(this).attr("id");
            
            // COLUMNA N° 1
            if (id_check_der_seleccionado == "checkbox_der_4_1" || id_check_der_seleccionado == "checkbox_der_5_1" ||
                id_check_der_seleccionado == "checkbox_der_6_1" || id_check_der_seleccionado == "checkbox_der_7_1") {
                
                resta_por_coluna(id_check_der_seleccionado, resta_total_ojo_der_col_1, "resultado_suma_ojo_der_col_1");
                datos_grilla_ojo_der(id_check_der_seleccionado, grilla_ojo_der, "no_editar");
            }

            // COLUMNA N° 2
            if (id_check_der_seleccionado == "checkbox_der_3_2" || id_check_der_seleccionado == "checkbox_der_4_2" ||
                id_check_der_seleccionado == "checkbox_der_5_2" || id_check_der_seleccionado == "checkbox_der_6_2" ||
                id_check_der_seleccionado == "checkbox_der_7_2" || id_check_der_seleccionado == "checkbox_der_8_2") {
                
                resta_por_coluna(id_check_der_seleccionado, resta_total_ojo_der_col_2, "resultado_suma_ojo_der_col_2");
                datos_grilla_ojo_der(id_check_der_seleccionado, grilla_ojo_der, "no_editar");
            }

            // COLUMNA N° 3
            if (id_check_der_seleccionado == "checkbox_der_2_3" || id_check_der_seleccionado == "checkbox_der_3_3" || 
                id_check_der_seleccionado == "checkbox_der_4_3" || id_check_der_seleccionado == "checkbox_der_5_3" || 
                id_check_der_seleccionado == "checkbox_der_6_3" || id_check_der_seleccionado == "checkbox_der_7_3" || 
                id_check_der_seleccionado == "checkbox_der_8_3" || id_check_der_seleccionado == "checkbox_der_9_3") {
                
                resta_por_coluna(id_check_der_seleccionado, resta_total_ojo_der_col_3, "resultado_suma_ojo_der_col_3");
                datos_grilla_ojo_der(id_check_der_seleccionado, grilla_ojo_der, "no_editar");
            }

            // COLUMNA N° 4
            if (id_check_der_seleccionado == "checkbox_der_1_4" || id_check_der_seleccionado == "checkbox_der_2_4" ||
                id_check_der_seleccionado == "checkbox_der_3_4" || id_check_der_seleccionado == "checkbox_der_4_4" ||
                id_check_der_seleccionado == "checkbox_der_5_4" || id_check_der_seleccionado == "checkbox_der_6_4" ||
                id_check_der_seleccionado == "checkbox_der_7_4" || id_check_der_seleccionado == "checkbox_der_8_4" ||
                id_check_der_seleccionado == "checkbox_der_9_4" || id_check_der_seleccionado == "checkbox_der_10_4") {
                
                resta_por_coluna(id_check_der_seleccionado, resta_total_ojo_der_col_4, "resultado_suma_ojo_der_col_4");
                datos_grilla_ojo_der(id_check_der_seleccionado, grilla_ojo_der, "no_editar");
            }

            // COLUMNA N° 5
            if (id_check_der_seleccionado == "checkbox_der_1_5" || id_check_der_seleccionado == "checkbox_der_2_5" ||
                id_check_der_seleccionado == "checkbox_der_3_5" || id_check_der_seleccionado == "checkbox_der_4_5" ||
                id_check_der_seleccionado == "checkbox_der_5_5" || id_check_der_seleccionado == "checkbox_der_6_5" ||
                id_check_der_seleccionado == "checkbox_der_7_5" || id_check_der_seleccionado == "checkbox_der_8_5" ||
                id_check_der_seleccionado == "checkbox_der_9_5" || id_check_der_seleccionado == "checkbox_der_10_5") {
                
                resta_por_coluna(id_check_der_seleccionado, resta_total_ojo_der_col_5, "resultado_suma_ojo_der_col_5");
                datos_grilla_ojo_der(id_check_der_seleccionado, grilla_ojo_der, "no_editar");
            }

            // COLUMNA N° 6
            if (id_check_der_seleccionado == "checkbox_der_1_6" ||id_check_der_seleccionado == "checkbox_der_2_6" ||
                id_check_der_seleccionado == "checkbox_der_3_6" || id_check_der_seleccionado == "checkbox_der_4_6" ||
                id_check_der_seleccionado == "checkbox_der_5_6" || id_check_der_seleccionado == "checkbox_der_6_6" ||
                id_check_der_seleccionado == "checkbox_der_7_6" || id_check_der_seleccionado == "checkbox_der_8_6" ||
                id_check_der_seleccionado == "checkbox_der_9_6" || id_check_der_seleccionado == "checkbox_der_10_6") {
                
                resta_por_coluna(id_check_der_seleccionado, resta_total_ojo_der_col_6, "resultado_suma_ojo_der_col_6");
                datos_grilla_ojo_der(id_check_der_seleccionado, grilla_ojo_der, "no_editar");
            }

            // COLUMNA N° 7
            if (id_check_der_seleccionado == "checkbox_der_1_7" || id_check_der_seleccionado == "checkbox_der_2_7" ||
                id_check_der_seleccionado == "checkbox_der_3_7" || id_check_der_seleccionado == "checkbox_der_4_7" ||
                id_check_der_seleccionado == "checkbox_der_5_7" || id_check_der_seleccionado == "checkbox_der_6_7" ||
                id_check_der_seleccionado == "checkbox_der_7_7" || id_check_der_seleccionado == "checkbox_der_8_7" ||
                id_check_der_seleccionado == "checkbox_der_9_7" || id_check_der_seleccionado == "checkbox_der_10_7") {

                resta_por_coluna(id_check_der_seleccionado, resta_total_ojo_der_col_7, "resultado_suma_ojo_der_col_7");
                datos_grilla_ojo_der(id_check_der_seleccionado, grilla_ojo_der, "no_editar");
            }

            // COLUMNA N° 8
            if (id_check_der_seleccionado == "checkbox_der_2_8" ||id_check_der_seleccionado == "checkbox_der_3_8" ||
                id_check_der_seleccionado == "checkbox_der_4_8" || id_check_der_seleccionado == "checkbox_der_5_8" ||
                id_check_der_seleccionado == "checkbox_der_6_8" || id_check_der_seleccionado == "checkbox_der_7_8" ||
                id_check_der_seleccionado == "checkbox_der_8_8" || id_check_der_seleccionado == "checkbox_der_9_8") {
                
                resta_por_coluna(id_check_der_seleccionado, resta_total_ojo_der_col_8, "resultado_suma_ojo_der_col_8");
                datos_grilla_ojo_der(id_check_der_seleccionado, grilla_ojo_der, "no_editar");
            }

            // COLUMNA N° 9
            if (id_check_der_seleccionado == "checkbox_der_3_9" || id_check_der_seleccionado == "checkbox_der_4_9" ||
                id_check_der_seleccionado == "checkbox_der_5_9" || id_check_der_seleccionado == "checkbox_der_6_9" ||
                id_check_der_seleccionado == "checkbox_der_7_9" || id_check_der_seleccionado == "checkbox_der_8_9") {
                
                resta_por_coluna(id_check_der_seleccionado, resta_total_ojo_der_col_9, "resultado_suma_ojo_der_col_9");
                datos_grilla_ojo_der(id_check_der_seleccionado, grilla_ojo_der, "no_editar");
            }
            
            // COLUMNA N° 10
            if (id_check_der_seleccionado == "checkbox_der_4_10" || id_check_der_seleccionado == "checkbox_der_5_10" ||
                id_check_der_seleccionado == "checkbox_der_6_10" || id_check_der_seleccionado == "checkbox_der_7_10") {
                
                resta_por_coluna(id_check_der_seleccionado, resta_total_ojo_der_col_10, "resultado_suma_ojo_der_col_10");
                datos_grilla_ojo_der(id_check_der_seleccionado, grilla_ojo_der, "no_editar");
            }

        }

    });

    /* ENVÍO DE DATOS DE AGUDEZA VISUAL */
    $(".modal-footer").remove();
    $(document).on('submit', "form[id^='form_agudeza_visual']", function(e){
        e.preventDefault();
        
        $("#btn_guardar_agudeza").prop('disabled', true);
        $("#btn_abrir_modal_agudeza").prop('disabled', true);
        $("#btn_abrir_modal_agudeza").hover(function(){
            $(this).css('cursor', 'not-allowed');
        });

        
        if (confirmacion_ceguera_total == "" || confirmacion_ceguera_total == undefined) {
            confirmacion_ceguera_total = "No";
        }

        let info_formulario = {
            "ID_evento": $("#ID_evento").val(),
            "Id_Asignacion": $("#Id_Asignacion").val(),
            "Id_proceso": $("#Id_proceso").val(),
            "Ceguera_Total": confirmacion_ceguera_total,
            "Agudeza_Ojo_Izq": $("#resultado_agudeza_ojo_izquierdo").val(),
            "Agudeza_Ojo_Der": $("#resultado_agudeza_ojo_derecho").val(),
            "Agudeza_Ambos_Ojos": $("#resultado_agudeza_ambos_ojos").val(),
            "PAVF": $("#resultado_pavf").val(),
            "DAV": $("#resultado_dav").val(),
            "Campo_Visual_Ojo_Izq": $("#resultado_campo_visual_ojo_izq").val(),
            "Campo_Visual_Ojo_Der": $("#resultado_campo_visual_ojo_der").val(),
            "Campo_Visual_Ambos_Ojos": $("#resultado_campo_visual_ambos_ojos").val(),
            "CVF": $("#resultado_cvf").val(),
            "DCV": $("#resultado_dcv").val(),
            "DSV": $("#resultado_dsv").val(),
            "Deficiencia": $("#resultado_deficiencia").val(),
            "Nombre_usuario": $("#nombre_usuario").val(),
            "F_registro": $("#dia_actual").val()
        };
        
        let datos_guardar_agudeza_visual = {
            '_token': token,
            'info_formulario': info_formulario,
            'grilla_ojo_izq': grilla_ojo_izq,
            'grilla_ojo_der': grilla_ojo_der
        };

        $.ajax({
            url: "/guardarAgudezaVisual",
            type: "post",
            data: datos_guardar_agudeza_visual,
            success:function(response){

                // MOstrar resultado de información de agudeza visual
                /* let info_agudeza_visual = {
                    '_token': token,
                    "ID_evento": $("#ID_evento").val(),
                };
                $.ajax({
                    url: "/infoAgudezaVisual",
                    type: "post",
                    data: info_agudeza_visual,
                    success:function(response){
                        // console.log(response);

                        // Datatable info agudeza visual
                        $.each(response, function(index, row) {
                            tr = $('<tr>').appendTo('#llenar_info_agudeza_visual');
                            tr.append('<td>' + row.Agudeza_Ojo_Izq + '</td>');
                            tr.append('<td>' + row.Agudeza_Ojo_Der + '</td>');
                            tr.append('<td>' + row.Agudeza_Ambos_Ojos + '</td>');
                            tr.append('<td>' + row.PAVF + '</td>');
                            tr.append('<td>' + row.DAV + '</td>');
                            tr.append('<td>' + row.Campo_Visual_Ojo_Izq + '</td>');
                            tr.append('<td>' + row.Campo_Visual_Ojo_Der + '</td>');
                            tr.append('<td>' + row.Campo_Visual_Ambos_Ojos + '</td>');
                            tr.append('<td>' + row.CVF + '</td>');
                            tr.append('<td>' + row.DCV + '</td>');
                            tr.append('<td>' + row.DSV + '</td>');
                            tr.append('<td><input type="checkbox" name="" id=""></td>');
                            tr.append('<td>' + row.Deficiencia + '</td>');
                            tr.append('<td><div style="text-align:center;">\
                                <a href="javascript:void(0);" id="btn_editar_agudeza_visual" class="text-dark text-md" label="Open Modal" data-toggle="modal" data-target="#modal_editar_agudeza_visual">\
                                    <i class="fa fa-pen text-primary"></i>\
                                </a>\
                                <a href="javascript:void(0);" id="btn_remover_fila_'+row.Id_agudeza+'" data-fila_agudeza="fila_visual_agudeza_'+row.Id_agudeza+'" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a>\
                                </div>\
                            </td>');
                        });
                      
                        var tabla_agudeza_visual = $('#listado_agudeza_visual').DataTable({
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
                    }         
                }); */
                
                if(response.parametro == "guardo"){
                    $("#resultado_insercion").removeClass('d-none');
                    $("#resultado_insercion").addClass('alert-success');
                    $("#resultado_insercion").append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $("#resultado_insercion").addClass('d-none');
                        $("#resultado_insercion").removeClass('alert-success');
                        $("#resultado_insercion").empty();
                        $("#btn_cerrar_modal_agudeza").trigger('click');
                        location.reload();
                    }, 4000);
                }
            }         
        });
        
    });
    
    //$(window).scrollTop(2758);
});

/* FUNCIÓN PARA REALIZAR LA SUMA POR COLUMNA DE CADA CHECKBOX SELECCIONADO: APLICA PARA GRILLA OJO IZQ Y OJO DER */
function suma_por_columna(id_check, td_colocar_resultado) {
    var valor_check_izq_seleccionado = parseFloat($("#"+id_check).val());
    var captura_sum_col = parseFloat($("#"+td_colocar_resultado).text());
    captura_sum_col += valor_check_izq_seleccionado;
    $("#"+td_colocar_resultado).text(redondear(captura_sum_col));

};

/* FUNCIÓN PARA REALIZAR LA RESTA POR COLUMNA DE CADA CHECKBOX SELECCIONADO: APLICA PARA GRILLA OJO IZQ Y OJO DER */
function resta_por_coluna(id_check, resta_total_col, td_colocar_resultado){
    var valor_check_izq_seleccionado = parseFloat($("#"+id_check).val());
    resta_total_col = parseFloat($("#"+td_colocar_resultado).text()) - valor_check_izq_seleccionado;
    $("#"+td_colocar_resultado).text(redondear(resta_total_col));
};

/* FUNCIÓN PARA SUMAR LOS RESULTADOS DE TODAS LAS FILAS DE LA TABLA LINEAL OJO IZQUIERDO */
function suma_resultados_tabla_lineal_ojo_izq(){
    var valor_final_col_1 = parseFloat($("#resultado_suma_ojo_izq_col_1").text());
    var valor_final_col_2 = parseFloat($("#resultado_suma_ojo_izq_col_2").text());
    var valor_final_col_3 = parseFloat($("#resultado_suma_ojo_izq_col_3").text());
    var valor_final_col_4 = parseFloat($("#resultado_suma_ojo_izq_col_4").text());
    var valor_final_col_5 = parseFloat($("#resultado_suma_ojo_izq_col_5").text());
    var valor_final_col_6 = parseFloat($("#resultado_suma_ojo_izq_col_6").text());
    var valor_final_col_7 = parseFloat($("#resultado_suma_ojo_izq_col_7").text());
    var valor_final_col_8 = parseFloat($("#resultado_suma_ojo_izq_col_8").text());
    var valor_final_col_9 = parseFloat($("#resultado_suma_ojo_izq_col_9").text());
    var valor_final_col_10 = parseFloat($("#resultado_suma_ojo_izq_col_10").text());

    if (valor_final_col_1 == 0.76 && valor_final_col_2 == 1.51 && valor_final_col_3 == 2.64 && valor_final_col_4 == 6.76 && valor_final_col_5 == 23.38
        && valor_final_col_6 == 23.38 && valor_final_col_7 == 6.76 && valor_final_col_8 == 2.64 && valor_final_col_9 == 1.51 && valor_final_col_10 == 0.76) {
        var suma_final = 100;        
    } else {
        var suma_final = valor_final_col_1 + valor_final_col_2 + valor_final_col_3 + valor_final_col_4 + valor_final_col_5 + 
        valor_final_col_6 + valor_final_col_7 + valor_final_col_8 + valor_final_col_9 + valor_final_col_10;        
    }

    return redondear(suma_final);
};

/* FUNCIÓN PARA SUMAR LOS RESULTADOS DE TODAS LAS FILAS DE LA TABLA LINEAL OJO DERECHO */
function suma_resultados_tabla_lineal_ojo_der(){
    var valor_final_col_1 = parseFloat($("#resultado_suma_ojo_der_col_1").text());
    var valor_final_col_2 = parseFloat($("#resultado_suma_ojo_der_col_2").text());
    var valor_final_col_3 = parseFloat($("#resultado_suma_ojo_der_col_3").text());
    var valor_final_col_4 = parseFloat($("#resultado_suma_ojo_der_col_4").text());
    var valor_final_col_5 = parseFloat($("#resultado_suma_ojo_der_col_5").text());
    var valor_final_col_6 = parseFloat($("#resultado_suma_ojo_der_col_6").text());
    var valor_final_col_7 = parseFloat($("#resultado_suma_ojo_der_col_7").text());
    var valor_final_col_8 = parseFloat($("#resultado_suma_ojo_der_col_8").text());
    var valor_final_col_9 = parseFloat($("#resultado_suma_ojo_der_col_9").text());
    var valor_final_col_10 = parseFloat($("#resultado_suma_ojo_der_col_10").text());

    if (valor_final_col_1 == 0.76 && valor_final_col_2 == 1.51 && valor_final_col_3 == 2.64 && valor_final_col_4 == 6.76 && valor_final_col_5 == 23.38
        && valor_final_col_6 == 23.38 && valor_final_col_7 == 6.76 && valor_final_col_8 == 2.64 && valor_final_col_9 == 1.51 && valor_final_col_10 == 0.76) {
        var suma_final = 100;        
    } else {
        var suma_final = valor_final_col_1 + valor_final_col_2 + valor_final_col_3 + valor_final_col_4 + valor_final_col_5 + 
        valor_final_col_6 + valor_final_col_7 + valor_final_col_8 + valor_final_col_9 + valor_final_col_10;        
    } 

    return redondear(suma_final);
};

/* FUNCIÓN PARA SUMAR LOS CHECKBOX DE CADA COLUMNA CUANDO SE SETEEN TODOS CON LA OPCIÓN OJO IZQUIERDO */
function suma_seleccion_todos_checkboxs_ojo_izq(parametro, ids_todos_checkboxs_ojo_izq, grilla_ojo_izq){
    if (parametro == "sumar_todo") {
        // COLUMNA N° 1
        var checkbox_izq_4_1 = parseFloat($("#checkbox_izq_4_1").val()); var checkbox_izq_5_1 = parseFloat($("#checkbox_izq_5_1").val());
        var checkbox_izq_6_1 = parseFloat($("#checkbox_izq_6_1").val()); var checkbox_izq_7_1 = parseFloat($("#checkbox_izq_7_1").val());
        
        var suma_col_1_ojo_izq = checkbox_izq_4_1 + checkbox_izq_5_1 + checkbox_izq_6_1 + checkbox_izq_7_1;
        $("#resultado_suma_ojo_izq_col_1").text(redondear(parseFloat(suma_col_1_ojo_izq)));
    
        // COLUMNA N° 2
        var checkbox_izq_3_2 = parseFloat($("#checkbox_izq_3_2").val()); var checkbox_izq_4_2 = parseFloat($("#checkbox_izq_4_2").val());
        var checkbox_izq_5_2 = parseFloat($("#checkbox_izq_5_2").val()); var checkbox_izq_6_2 = parseFloat($("#checkbox_izq_6_2").val());
        var checkbox_izq_7_2 = parseFloat($("#checkbox_izq_7_2").val()); var checkbox_izq_8_2 = parseFloat($("#checkbox_izq_8_2").val());
    
        var suma_col_2_ojo_izq = checkbox_izq_3_2 + checkbox_izq_4_2 + checkbox_izq_5_2 + checkbox_izq_6_2 + checkbox_izq_7_2 + checkbox_izq_8_2;
        $("#resultado_suma_ojo_izq_col_2").text(redondear(parseFloat(suma_col_2_ojo_izq)));
    
        // COLUMNA N° 3
        var checkbox_izq_2_3 = parseFloat($("#checkbox_izq_2_3").val()); var checkbox_izq_3_3 = parseFloat($("#checkbox_izq_3_3").val());
        var checkbox_izq_4_3 = parseFloat($("#checkbox_izq_4_3").val()); var checkbox_izq_5_3 = parseFloat($("#checkbox_izq_5_3").val());
        var checkbox_izq_6_3 = parseFloat($("#checkbox_izq_6_3").val()); var checkbox_izq_7_3 = parseFloat($("#checkbox_izq_7_3").val());
        var checkbox_izq_8_3 = parseFloat($("#checkbox_izq_8_3").val()); var checkbox_izq_9_3 = parseFloat($("#checkbox_izq_9_3").val());
    
        var suma_col_3_ojo_izq = checkbox_izq_2_3 + checkbox_izq_3_3 + checkbox_izq_4_3 + checkbox_izq_5_3 + 
        checkbox_izq_6_3 + checkbox_izq_7_3 + checkbox_izq_8_3 + checkbox_izq_9_3;
    
        $("#resultado_suma_ojo_izq_col_3").text(redondear(parseFloat(suma_col_3_ojo_izq)));
    
        // COLUMNA N° 4
        var checkbox_izq_1_4 = parseFloat($("#checkbox_izq_1_4").val()); var checkbox_izq_2_4 = parseFloat($("#checkbox_izq_2_4").val());
        var checkbox_izq_3_4 = parseFloat($("#checkbox_izq_3_4").val()); var checkbox_izq_4_4 = parseFloat($("#checkbox_izq_4_4").val());
        var checkbox_izq_5_4 = parseFloat($("#checkbox_izq_5_4").val()); var checkbox_izq_6_4 = parseFloat($("#checkbox_izq_6_4").val());
        var checkbox_izq_7_4 = parseFloat($("#checkbox_izq_7_4").val()); var checkbox_izq_8_4 = parseFloat($("#checkbox_izq_8_4").val());
        var checkbox_izq_9_4 = parseFloat($("#checkbox_izq_9_4").val()); var checkbox_izq_10_4 = parseFloat($("#checkbox_izq_10_4").val());
    
        var suma_col_4_ojo_izq = checkbox_izq_1_4 + checkbox_izq_2_4 + checkbox_izq_3_4 + checkbox_izq_4_4 + checkbox_izq_5_4 + checkbox_izq_6_4 +
        checkbox_izq_7_4 + checkbox_izq_8_4 + checkbox_izq_9_4 + checkbox_izq_10_4;
        $("#resultado_suma_ojo_izq_col_4").text(redondear(parseFloat(suma_col_4_ojo_izq)));
    
        // COLUMNA N° 5
        var checkbox_izq_1_5 = parseFloat($("#checkbox_izq_1_5").val()); var checkbox_izq_2_5 = parseFloat($("#checkbox_izq_2_5").val());
        var checkbox_izq_3_5 = parseFloat($("#checkbox_izq_3_5").val()); var checkbox_izq_4_5 = parseFloat($("#checkbox_izq_4_5").val());
        var checkbox_izq_5_5 = parseFloat($("#checkbox_izq_5_5").val()); var checkbox_izq_6_5 = parseFloat($("#checkbox_izq_6_5").val());
        var checkbox_izq_7_5 = parseFloat($("#checkbox_izq_7_5").val()); var checkbox_izq_8_5 = parseFloat($("#checkbox_izq_8_5").val());
        var checkbox_izq_9_5 = parseFloat($("#checkbox_izq_9_5").val()); var checkbox_izq_10_5 = parseFloat($("#checkbox_izq_10_5").val());
    
        var suma_col_5_ojo_izq = checkbox_izq_1_5 + checkbox_izq_2_5 + checkbox_izq_3_5 + checkbox_izq_4_5 + checkbox_izq_5_5 + checkbox_izq_6_5 + 
        checkbox_izq_7_5 + checkbox_izq_8_5 + checkbox_izq_9_5 + checkbox_izq_10_5;
        $("#resultado_suma_ojo_izq_col_5").text(redondear(parseFloat(suma_col_5_ojo_izq)));
    
        // COLUMNA N° 6
        var checkbox_izq_1_6 = parseFloat($("#checkbox_izq_1_6").val()); var checkbox_izq_2_6 = parseFloat($("#checkbox_izq_2_6").val());
        var checkbox_izq_3_6 = parseFloat($("#checkbox_izq_3_6").val()); var checkbox_izq_4_6 = parseFloat($("#checkbox_izq_4_6").val());
        var checkbox_izq_5_6 = parseFloat($("#checkbox_izq_5_6").val()); var checkbox_izq_6_6 = parseFloat($("#checkbox_izq_6_6").val());
        var checkbox_izq_7_6 = parseFloat($("#checkbox_izq_7_6").val()); var checkbox_izq_8_6 = parseFloat($("#checkbox_izq_8_6").val());
        var checkbox_izq_9_6 = parseFloat($("#checkbox_izq_9_6").val()); var checkbox_izq_10_6 = parseFloat($("#checkbox_izq_10_6").val());
        
        var suma_col_6_ojo_izq = checkbox_izq_1_6 + checkbox_izq_2_6 + checkbox_izq_3_6 + checkbox_izq_4_6 + checkbox_izq_5_6 + checkbox_izq_6_6 +
        checkbox_izq_7_6 + checkbox_izq_8_6 + checkbox_izq_9_6 + checkbox_izq_10_6;
        $("#resultado_suma_ojo_izq_col_6").text(redondear(parseFloat(suma_col_6_ojo_izq)));
    
        // COLUMNA N° 7
        var checkbox_izq_1_7 = parseFloat($("#checkbox_izq_1_7").val()); var checkbox_izq_2_7 = parseFloat($("#checkbox_izq_2_7").val());
        var checkbox_izq_3_7 = parseFloat($("#checkbox_izq_3_7").val()); var checkbox_izq_4_7 = parseFloat($("#checkbox_izq_4_7").val());
        var checkbox_izq_5_7 = parseFloat($("#checkbox_izq_5_7").val()); var checkbox_izq_6_7 = parseFloat($("#checkbox_izq_6_7").val());
        var checkbox_izq_7_7 = parseFloat($("#checkbox_izq_7_7").val()); var checkbox_izq_8_7 = parseFloat($("#checkbox_izq_8_7").val());
        var checkbox_izq_9_7 = parseFloat($("#checkbox_izq_9_7").val()); var checkbox_izq_10_7 = parseFloat($("#checkbox_izq_10_7").val());
    
        var suma_col_7_ojo_izq = checkbox_izq_1_7 + checkbox_izq_2_7 + checkbox_izq_3_7 + checkbox_izq_4_7 + checkbox_izq_5_7 + checkbox_izq_6_7 +
        checkbox_izq_7_7 + checkbox_izq_8_7 + checkbox_izq_9_7 + checkbox_izq_10_7;
        $("#resultado_suma_ojo_izq_col_7").text(redondear(parseFloat(suma_col_7_ojo_izq)));
        
        var checkbox_izq_2_8 = parseFloat($("#checkbox_izq_2_8").val()); var checkbox_izq_3_8 = parseFloat($("#checkbox_izq_3_8").val());
        var checkbox_izq_4_8 = parseFloat($("#checkbox_izq_4_8").val()); var checkbox_izq_5_8 = parseFloat($("#checkbox_izq_5_8").val());
        var checkbox_izq_6_8 = parseFloat($("#checkbox_izq_6_8").val()); var checkbox_izq_7_8 = parseFloat($("#checkbox_izq_7_8").val());
        var checkbox_izq_8_8 = parseFloat($("#checkbox_izq_8_8").val()); var checkbox_izq_9_8 = parseFloat($("#checkbox_izq_9_8").val());
        
        var suma_col_8_ojo_izq = checkbox_izq_2_8 + checkbox_izq_3_8 + checkbox_izq_4_8 + checkbox_izq_5_8 + checkbox_izq_6_8 + checkbox_izq_7_8 +
        checkbox_izq_8_8 + checkbox_izq_9_8;
        $("#resultado_suma_ojo_izq_col_8").text(redondear(parseFloat(suma_col_8_ojo_izq)));
    
        // COLUMNA N° 9
        var checkbox_izq_3_9 = parseFloat($("#checkbox_izq_3_9").val()); var checkbox_izq_4_9 = parseFloat($("#checkbox_izq_4_9").val());
        var checkbox_izq_5_9 = parseFloat($("#checkbox_izq_5_9").val()); var checkbox_izq_6_9 = parseFloat($("#checkbox_izq_6_9").val());
        var checkbox_izq_7_9 = parseFloat($("#checkbox_izq_7_9").val()); var checkbox_izq_8_9 = parseFloat($("#checkbox_izq_8_9").val());
        
        var suma_col_9_ojo_izq = checkbox_izq_3_9 + checkbox_izq_4_9 + checkbox_izq_5_9 + checkbox_izq_6_9 + checkbox_izq_7_9 + checkbox_izq_8_9;
        $("#resultado_suma_ojo_izq_col_9").text(redondear(parseFloat(suma_col_9_ojo_izq)));
    
        // COLUMNA N° 10
        var checkbox_izq_4_10 = parseFloat($("#checkbox_izq_4_10").val()); var checkbox_izq_5_10 = parseFloat($("#checkbox_izq_5_10").val());
        var checkbox_izq_6_10 = parseFloat($("#checkbox_izq_6_10").val()); var checkbox_izq_7_10 = parseFloat($("#checkbox_izq_7_10").val());
        
        var suma_col_10_ojo_izq = checkbox_izq_4_10 + checkbox_izq_5_10 + checkbox_izq_6_10 + checkbox_izq_7_10;
        $("#resultado_suma_ojo_izq_col_10").text(redondear(parseFloat(suma_col_10_ojo_izq)));

        for (let h = 0; h < ids_todos_checkboxs_ojo_izq.length; h++) {
            datos_grilla_ojo_izq(ids_todos_checkboxs_ojo_izq[h], grilla_ojo_izq, "editar_todos");
        }

    }else{
        $("#resultado_suma_ojo_izq_col_1").text(0);
        $("#resultado_suma_ojo_izq_col_2").text(0);
        $("#resultado_suma_ojo_izq_col_3").text(0);
        $("#resultado_suma_ojo_izq_col_4").text(0);
        $("#resultado_suma_ojo_izq_col_5").text(0);
        $("#resultado_suma_ojo_izq_col_6").text(0);
        $("#resultado_suma_ojo_izq_col_7").text(0);
        $("#resultado_suma_ojo_izq_col_8").text(0);
        $("#resultado_suma_ojo_izq_col_9").text(0);
        $("#resultado_suma_ojo_izq_col_10").text(0);

        for (let h = 0; h < ids_todos_checkboxs_ojo_izq.length; h++) {
            datos_grilla_ojo_izq(ids_todos_checkboxs_ojo_izq[h], grilla_ojo_izq, "no_editar_todos");
        }
    }

    
};

/* FUNCIÓN PARA SUMAR LOS CHECKBOX DE CADA COLUMNA CUANDO SE SETEEN TODOS CON LA OPCIÓN OJO DERECHO */
function suma_seleccion_todos_checkboxs_ojo_der(parametro, ids_todos_checkboxs_ojo_der, grilla_ojo_der){
    if (parametro == "sumar_todo") {
        // COLUMNA N° 1
        var checkbox_der_4_1 = parseFloat($("#checkbox_der_4_1").val()); var checkbox_der_5_1 = parseFloat($("#checkbox_der_5_1").val());
        var checkbox_der_6_1 = parseFloat($("#checkbox_der_6_1").val()); var checkbox_der_7_1 = parseFloat($("#checkbox_der_7_1").val());
        
        var suma_col_1_ojo_der = checkbox_der_4_1 + checkbox_der_5_1 + checkbox_der_6_1 + checkbox_der_7_1;
        $("#resultado_suma_ojo_der_col_1").text(redondear(parseFloat(suma_col_1_ojo_der)));
    
        // COLUMNA N° 2
        var checkbox_der_3_2 = parseFloat($("#checkbox_der_3_2").val()); var checkbox_der_4_2 = parseFloat($("#checkbox_der_4_2").val());
        var checkbox_der_5_2 = parseFloat($("#checkbox_der_5_2").val()); var checkbox_der_6_2 = parseFloat($("#checkbox_der_6_2").val());
        var checkbox_der_7_2 = parseFloat($("#checkbox_der_7_2").val()); var checkbox_der_8_2 = parseFloat($("#checkbox_der_8_2").val());
    
        var suma_col_2_ojo_der = checkbox_der_3_2 + checkbox_der_4_2 + checkbox_der_5_2 + checkbox_der_6_2 + checkbox_der_7_2 + checkbox_der_8_2;
        $("#resultado_suma_ojo_der_col_2").text(redondear(parseFloat(suma_col_2_ojo_der)));
    
        // COLUMNA N° 3
        var checkbox_der_2_3 = parseFloat($("#checkbox_der_2_3").val()); var checkbox_der_3_3 = parseFloat($("#checkbox_der_3_3").val());
        var checkbox_der_4_3 = parseFloat($("#checkbox_der_4_3").val()); var checkbox_der_5_3 = parseFloat($("#checkbox_der_5_3").val());
        var checkbox_der_6_3 = parseFloat($("#checkbox_der_6_3").val()); var checkbox_der_7_3 = parseFloat($("#checkbox_der_7_3").val());
        var checkbox_der_8_3 = parseFloat($("#checkbox_der_8_3").val()); var checkbox_der_9_3 = parseFloat($("#checkbox_der_9_3").val());
    
        var suma_col_3_ojo_der = checkbox_der_2_3 + checkbox_der_3_3 + checkbox_der_4_3 + checkbox_der_5_3 + 
        checkbox_der_6_3 + checkbox_der_7_3 + checkbox_der_8_3 + checkbox_der_9_3;
    
        $("#resultado_suma_ojo_der_col_3").text(redondear(parseFloat(suma_col_3_ojo_der)));
    
        // COLUMNA N° 4
        var checkbox_der_1_4 = parseFloat($("#checkbox_der_1_4").val()); var checkbox_der_2_4 = parseFloat($("#checkbox_der_2_4").val());
        var checkbox_der_3_4 = parseFloat($("#checkbox_der_3_4").val()); var checkbox_der_4_4 = parseFloat($("#checkbox_der_4_4").val());
        var checkbox_der_5_4 = parseFloat($("#checkbox_der_5_4").val()); var checkbox_der_6_4 = parseFloat($("#checkbox_der_6_4").val());
        var checkbox_der_7_4 = parseFloat($("#checkbox_der_7_4").val()); var checkbox_der_8_4 = parseFloat($("#checkbox_der_8_4").val());
        var checkbox_der_9_4 = parseFloat($("#checkbox_der_9_4").val()); var checkbox_der_10_4 = parseFloat($("#checkbox_der_10_4").val());
    
        var suma_col_4_ojo_der = checkbox_der_1_4 + checkbox_der_2_4 + checkbox_der_3_4 + checkbox_der_4_4 + checkbox_der_5_4 + checkbox_der_6_4 +
        checkbox_der_7_4 + checkbox_der_8_4 + checkbox_der_9_4 + checkbox_der_10_4;
        $("#resultado_suma_ojo_der_col_4").text(redondear(parseFloat(suma_col_4_ojo_der)));
    
        // COLUMNA N° 5
        var checkbox_der_1_5 = parseFloat($("#checkbox_der_1_5").val()); var checkbox_der_2_5 = parseFloat($("#checkbox_der_2_5").val());
        var checkbox_der_3_5 = parseFloat($("#checkbox_der_3_5").val()); var checkbox_der_4_5 = parseFloat($("#checkbox_der_4_5").val());
        var checkbox_der_5_5 = parseFloat($("#checkbox_der_5_5").val()); var checkbox_der_6_5 = parseFloat($("#checkbox_der_6_5").val());
        var checkbox_der_7_5 = parseFloat($("#checkbox_der_7_5").val()); var checkbox_der_8_5 = parseFloat($("#checkbox_der_8_5").val());
        var checkbox_der_9_5 = parseFloat($("#checkbox_der_9_5").val()); var checkbox_der_10_5 = parseFloat($("#checkbox_der_10_5").val());
    
        var suma_col_5_ojo_der = checkbox_der_1_5 + checkbox_der_2_5 + checkbox_der_3_5 + checkbox_der_4_5 + checkbox_der_5_5 + checkbox_der_6_5 + 
        checkbox_der_7_5 + checkbox_der_8_5 + checkbox_der_9_5 + checkbox_der_10_5;
        $("#resultado_suma_ojo_der_col_5").text(redondear(parseFloat(suma_col_5_ojo_der)));
    
        // COLUMNA N° 6
        var checkbox_der_1_6 = parseFloat($("#checkbox_der_1_6").val()); var checkbox_der_2_6 = parseFloat($("#checkbox_der_2_6").val());
        var checkbox_der_3_6 = parseFloat($("#checkbox_der_3_6").val()); var checkbox_der_4_6 = parseFloat($("#checkbox_der_4_6").val());
        var checkbox_der_5_6 = parseFloat($("#checkbox_der_5_6").val()); var checkbox_der_6_6 = parseFloat($("#checkbox_der_6_6").val());
        var checkbox_der_7_6 = parseFloat($("#checkbox_der_7_6").val()); var checkbox_der_8_6 = parseFloat($("#checkbox_der_8_6").val());
        var checkbox_der_9_6 = parseFloat($("#checkbox_der_9_6").val()); var checkbox_der_10_6 = parseFloat($("#checkbox_der_10_6").val());
        
        var suma_col_6_ojo_der = checkbox_der_1_6 + checkbox_der_2_6 + checkbox_der_3_6 + checkbox_der_4_6 + checkbox_der_5_6 + checkbox_der_6_6 +
        checkbox_der_7_6 + checkbox_der_8_6 + checkbox_der_9_6 + checkbox_der_10_6;
        $("#resultado_suma_ojo_der_col_6").text(redondear(parseFloat(suma_col_6_ojo_der)));
    
        // COLUMNA N° 7
        var checkbox_der_1_7 = parseFloat($("#checkbox_der_1_7").val()); var checkbox_der_2_7 = parseFloat($("#checkbox_der_2_7").val());
        var checkbox_der_3_7 = parseFloat($("#checkbox_der_3_7").val()); var checkbox_der_4_7 = parseFloat($("#checkbox_der_4_7").val());
        var checkbox_der_5_7 = parseFloat($("#checkbox_der_5_7").val()); var checkbox_der_6_7 = parseFloat($("#checkbox_der_6_7").val());
        var checkbox_der_7_7 = parseFloat($("#checkbox_der_7_7").val()); var checkbox_der_8_7 = parseFloat($("#checkbox_der_8_7").val());
        var checkbox_der_9_7 = parseFloat($("#checkbox_der_9_7").val()); var checkbox_der_10_7 = parseFloat($("#checkbox_der_10_7").val());
    
        var suma_col_7_ojo_der = checkbox_der_1_7 + checkbox_der_2_7 + checkbox_der_3_7 + checkbox_der_4_7 + checkbox_der_5_7 + checkbox_der_6_7 +
        checkbox_der_7_7 + checkbox_der_8_7 + checkbox_der_9_7 + checkbox_der_10_7;
        $("#resultado_suma_ojo_der_col_7").text(redondear(parseFloat(suma_col_7_ojo_der)));
        
        var checkbox_der_2_8 = parseFloat($("#checkbox_der_2_8").val()); var checkbox_der_3_8 = parseFloat($("#checkbox_der_3_8").val());
        var checkbox_der_4_8 = parseFloat($("#checkbox_der_4_8").val()); var checkbox_der_5_8 = parseFloat($("#checkbox_der_5_8").val());
        var checkbox_der_6_8 = parseFloat($("#checkbox_der_6_8").val()); var checkbox_der_7_8 = parseFloat($("#checkbox_der_7_8").val());
        var checkbox_der_8_8 = parseFloat($("#checkbox_der_8_8").val()); var checkbox_der_9_8 = parseFloat($("#checkbox_der_9_8").val());
        
        var suma_col_8_ojo_der = checkbox_der_2_8 + checkbox_der_3_8 + checkbox_der_4_8 + checkbox_der_5_8 + checkbox_der_6_8 + checkbox_der_7_8 +
        checkbox_der_8_8 + checkbox_der_9_8;
        $("#resultado_suma_ojo_der_col_8").text(redondear(parseFloat(suma_col_8_ojo_der)));
    
        // COLUMNA N° 9
        var checkbox_der_3_9 = parseFloat($("#checkbox_der_3_9").val()); var checkbox_der_4_9 = parseFloat($("#checkbox_der_4_9").val());
        var checkbox_der_5_9 = parseFloat($("#checkbox_der_5_9").val()); var checkbox_der_6_9 = parseFloat($("#checkbox_der_6_9").val());
        var checkbox_der_7_9 = parseFloat($("#checkbox_der_7_9").val()); var checkbox_der_8_9 = parseFloat($("#checkbox_der_8_9").val());
        
        var suma_col_9_ojo_der = checkbox_der_3_9 + checkbox_der_4_9 + checkbox_der_5_9 + checkbox_der_6_9 + checkbox_der_7_9 + checkbox_der_8_9;
        $("#resultado_suma_ojo_der_col_9").text(redondear(parseFloat(suma_col_9_ojo_der)));
    
        // COLUMNA N° 10
        var checkbox_der_4_10 = parseFloat($("#checkbox_der_4_10").val()); var checkbox_der_5_10 = parseFloat($("#checkbox_der_5_10").val());
        var checkbox_der_6_10 = parseFloat($("#checkbox_der_6_10").val()); var checkbox_der_7_10 = parseFloat($("#checkbox_der_7_10").val());
        
        var suma_col_10_ojo_der = checkbox_der_4_10 + checkbox_der_5_10 + checkbox_der_6_10 + checkbox_der_7_10;
        $("#resultado_suma_ojo_der_col_10").text(redondear(parseFloat(suma_col_10_ojo_der)));

        for (let h = 0; h < ids_todos_checkboxs_ojo_der.length; h++) {
            datos_grilla_ojo_der(ids_todos_checkboxs_ojo_der[h], grilla_ojo_der, "editar_todos");
        }

    }else{
        $("#resultado_suma_ojo_der_col_1").text(0);
        $("#resultado_suma_ojo_der_col_2").text(0);
        $("#resultado_suma_ojo_der_col_3").text(0);
        $("#resultado_suma_ojo_der_col_4").text(0);
        $("#resultado_suma_ojo_der_col_5").text(0);
        $("#resultado_suma_ojo_der_col_6").text(0);
        $("#resultado_suma_ojo_der_col_7").text(0);
        $("#resultado_suma_ojo_der_col_8").text(0);
        $("#resultado_suma_ojo_der_col_9").text(0);
        $("#resultado_suma_ojo_der_col_10").text(0);

        for (let h = 0; h < ids_todos_checkboxs_ojo_der.length; h++) {
            datos_grilla_ojo_der(ids_todos_checkboxs_ojo_der[h], grilla_ojo_der, "no_editar_todos");
        }
    }

    
};

/* FUNCIÓN PARA SABER QUE CHECKBOX FUE SELECCIONADO (OJO IZQ) Y PODER MODIFICAR LOS DATOS DE LA GRILLA FINAL PARA INSERTARLOS EN LA BD (PARA TEMAS DE EDICIÓN) */
function datos_grilla_ojo_izq(id_check, grilla_ojo_izq, parametro){
    
    // Capturamos el valor del checkbox dependiendo del id
    var valor_check_izq_seleccionado = parseFloat($("#" + id_check).val());

    // Buscamos de que fila pertenece el checkbox seleccionado
    var regex_num_fila = id_check.match(/_([0-9]+)/);
    var num_fila = regex_num_fila ? regex_num_fila[1] : null;
    var num_fila = num_fila - 1;
    // Buscamos el numero de checkbox que fue seleccionado
    var regex_num_checkbox = id_check.match(/_[0-9]+_([0-9]+)/);
    var num_checkbox = regex_num_checkbox ? regex_num_checkbox[1] : null;

    switch (parametro) {
        case "editar":
            grilla_ojo_izq[num_fila]["InfoFila"+num_checkbox] = "x-" + valor_check_izq_seleccionado;
        break;
        case "no_editar":
            grilla_ojo_izq[num_fila]["InfoFila"+num_checkbox] = valor_check_izq_seleccionado;
        break;
        case "editar_todos":
            if ($("#" + id_check).val() == "N/A") {
                grilla_ojo_izq[num_fila]["InfoFila"+num_checkbox] = $("#" + id_check).val();
            } else {
                grilla_ojo_izq[num_fila]["InfoFila"+num_checkbox] = "x-" + valor_check_izq_seleccionado;
            }
        break;
        case "no_editar_todos":
            if ($("#" + id_check).val() == "N/A") {
                grilla_ojo_izq[num_fila]["InfoFila"+num_checkbox] = $("#" + id_check).val();
            } else {
                grilla_ojo_izq[num_fila]["InfoFila"+num_checkbox] = valor_check_izq_seleccionado;
            }
        break;
        default:
        break;
    }

};

/* FUNCIÓN PARA SABER QUE CHECKBOX FUE SELECCIONADO (OJO DER) Y PODER MODIFICAR LOS DATOS DE LA GRILLA FINAL PARA INSERTARLOS EN LA BD (PARA TEMAS DE EDICIÓN) */
function datos_grilla_ojo_der(id_check, grilla_ojo_der, parametro){
    
    // Capturamos el valor del checkbox dependiendo del id
    var valor_check_der_seleccionado = parseFloat($("#" + id_check).val());

    // Buscamos de que fila pertenece el checkbox seleccionado
    var regex_num_fila = id_check.match(/_([0-9]+)/);
    var num_fila = regex_num_fila ? regex_num_fila[1] : null;
    var num_fila = num_fila - 1;
    // Buscamos el numero de checkbox que fue seleccionado
    var regex_num_checkbox = id_check.match(/_[0-9]+_([0-9]+)/);
    var num_checkbox = regex_num_checkbox ? regex_num_checkbox[1] : null;

    switch (parametro) {
        case "editar":
            grilla_ojo_der[num_fila]["InfoFila"+num_checkbox] = "x-" + valor_check_der_seleccionado;
        break;
        case "no_editar":
            grilla_ojo_der[num_fila]["InfoFila"+num_checkbox] = valor_check_der_seleccionado;
        break;
        case "editar_todos":
            if ($("#" + id_check).val() == "N/A") {
                grilla_ojo_der[num_fila]["InfoFila"+num_checkbox] = $("#" + id_check).val();
            } else {
                grilla_ojo_der[num_fila]["InfoFila"+num_checkbox] = "x-" + valor_check_der_seleccionado;
            }
        break;
        case "no_editar_todos":
            if ($("#" + id_check).val() == "N/A") {
                grilla_ojo_der[num_fila]["InfoFila"+num_checkbox] = $("#" + id_check).val();
            } else {
                grilla_ojo_der[num_fila]["InfoFila"+num_checkbox] = valor_check_der_seleccionado;
            }
        break;
        default:
        break;
    }

};

/* FUNCIÓN PARA REDONDEO DE NÚMEROS */
function redondear(numero) {
    return Math.round(numero * 100) / 100;
}
