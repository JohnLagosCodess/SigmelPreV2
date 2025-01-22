//  SCRIPT PARA ELIMINAR LA FILA DE LA AGUDEZA VISUAL CUANDO EL USUARIO REALICE LA ACCIÓN
$(document).ready(function(){
    var tabla_agudeza_visual = $('#listado_agudeza_visual').DataTable({
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

    autoAdjustColumns(tabla_agudeza_visual);
    $(document).on('click', "a[id^='btn_remover_fila_']", function(){
        var nombre_fila_agudeza = $(this).data("fila_agudeza");
        var regex =  /\d+/;
        var id_agudeza = nombre_fila_agudeza.match(regex)[0];
        
        let datos_eliminar_info_agudeza = {
            '_token':  $("input[name='_token']").val(),
            'Id_agudeza': id_agudeza,
            'ID_evento': $("#id_evento").val()
        };

        $.ajax({
            url: "/eliminarAgudezaVisual",
            type: "post",
            data: datos_eliminar_info_agudeza,
            success:function(response){
                tabla_agudeza_visual.row("."+nombre_fila_agudeza).remove().draw();
                if(response.parametro == "borro"){
                    $("#btn_abrir_modal_agudeza").prop('disabled', false);
                    $("#btn_abrir_modal_agudeza").hover(function(){
                        $(this).css('cursor', 'pointer');
                    });
                    $("#btn_abrir_modal_agudeza").attr("data-target", "#modal_nueva_agudeza_visual");
                    location.reload();
                }
            }         
        });

        
        
    });

    //$(window).scrollTop(2758);

});