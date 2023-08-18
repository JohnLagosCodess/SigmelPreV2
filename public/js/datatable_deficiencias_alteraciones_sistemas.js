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
          '<select id="listado_tablas_fila_alteraciones_'+contador_alteraciones+'" class="form-comtrol custom-select listado_tablas_fila_alteraciones_'+contador_alteraciones+'" name="ident_tabla" style="width: 50% !important;"><option></option></select>',
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

    /* SELECT 2 LISTADO DE TABLAS */  
    $(".listado_tablas_fila_alteraciones_"+num_consecutivo_alteraciones).select2({
        width: '100%',
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
                        var select_FP = $('<select id="resultado_FP_'+num_consecutivo_alteraciones+'" class="resultado_FP_'+num_consecutivo_alteraciones+'">');
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

    });

};
