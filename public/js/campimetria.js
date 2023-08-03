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

    // LLamado de datos de la tabla de campimetria
    let token = $("input[name='_token']").val();
    let consulta_campimetria = {
        '_token': token,
    };

    $.ajax({
        type:'POST',
        url:'/ConsultaCampimetriaXFila',
        data: consulta_campimetria,
        success:function(data) {

            var nombre_filas_bd = Object.keys(data[0]);

            // Construcción de la grilla para el ojo izquierdo
            for (let i = 0; i < 10; i++) {
                var conteo_izq = i + 1;
                let row = $('<tr class="ojo_izquierdo_fila_'+conteo_izq+'" ></tr>');

                for (let j = 0; j < 10; j++) {
                    var conteo_izq2 = j + 1;
                    var cell = $('<td class="text-center coordenadas_izq_'+conteo_izq+'_'+conteo_izq2+'"></td>');
                    var checkbox = $('<input type="checkbox" class="checkbox_izq_'+conteo_izq+'_'+conteo_izq2+'" style="transform: scale(1.2);">');
                    
                    checkbox.val(data[i][nombre_filas_bd[j]]); // Extract the value from data
                    
                    cell.append(checkbox); // Append the checkbox to the cell
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
                    var checkbox = $('<input type="checkbox" class="checkbox_der_'+conteo_der+'_'+conteo_der2+'" style="transform: scale(1.2);">');
                    
                    checkbox.val(data[a][nombre_filas_bd[m]]); // Extract the value from data
                    
                    cell.append(checkbox); // Append the checkbox to the cell
                    row.append(cell);
                }

                $('#tabla_campimetria_ojo_derecho').append(row);
            }
        }
    });
 
    /* VALIDACIÓN SELECCIÓN CHECKBOX CEGUERA TOTAL */
    $("#ceguera_total").click(function(){
        
        /* Si fue seleccionado inhabilita lo siguiente:
            - Selector Agudeza Ojo Izquierdo y Selector Agudeza Ojo Derecho
            - Campo Agudeza Ambos Ojos
            - Checkbox Ojo Izquierdo y Checkbox Ojo Derecho
            - Grilla Ojo Izquierdo y Grilla Ojo Derecho
        */
        if ($(this).is(":checked")) {
            $("#agudeza_ojo_izq").prop("disabled", true);
            $("#agudeza_ojo_der").prop("disabled", true);
            $("#agudeza_ambos_ojos").prop("disabled", true);
            $("#ojo_izquierdo").prop("disabled", true);
            $("#ojo_derecho").prop("disabled", true);
            $("input[class^='checkbox_izq_']").prop("disabled", true);
            $("input[class^='checkbox_der_']").prop("disabled", true);

            /* SETEO DE VALORES para la tabla de Resultados Ceguera Total: */
            $("#resultado_agudeza_ojo_izquierdo").val(0);


        } else {
            $("#agudeza_ojo_izq").prop("disabled", false);
            $("#agudeza_ojo_der").prop("disabled", false);
            $("#agudeza_ambos_ojos").prop("disabled", false);
            $("#ojo_izquierdo").prop("disabled", false);
            $("#ojo_derecho").prop("disabled", false);
            $("input[class^='checkbox_izq_']").prop("disabled", false);
            $("input[class^='checkbox_der_']").prop("disabled", false);

            /* SETEO DE VALORES para la tabla de Resultados Ceguera Total: */
            $("#resultado_agudeza_ojo_izquierdo").val($("#agudeza_ojo_izq option:selected").val());
        }
    });
    
});