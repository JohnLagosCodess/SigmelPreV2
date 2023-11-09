// script para Agregar agudeza auditiva
$(document).ready(function() {
    
    // Inicializacion selectores modal agudeza auditiva
    $(".oido_izquierdo").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".oido_derecho").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    // Cargue de data a los selectores
    let token = $("input[name='_token']").val();

    let datos_listado_oido_Izquierdo = {
        '_token': token,
        'parametro':"agudeza_auditiva"
    };
    
    $.ajax({
        type:'POST',
        url:'/selectoresCalificacionTecnicaPCL',
        data: datos_listado_oido_Izquierdo,
        success:function(data){
            //console.log(data);
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#oido_izquierdo').append('<option value="'+data[claves[i]]['Nombre_parametro']+'">'+data[claves[i]]['Nombre_parametro']+'</option>');
            }
        }
    });

    $.ajax({
        type:'POST',
        url:'/selectoresCalificacionTecnicaPCL',
        data: datos_listado_oido_Izquierdo,
        success:function(data){
            //console.log(data);
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#oido_derecho').append('<option value="'+data[claves[i]]['Nombre_parametro']+'">'+data[claves[i]]['Nombre_parametro']+'</option>');
            }
        }
    });

    $(document).ready(function() {
        var Agudeza_Auditivas = [];
        var registro = {};
        function calcularDeficiciasAuditivas() {
            var Izquierdoear = $(".oido_izquierdo option:selected").text();
            var Derechoear = $(".oido_derecho option:selected").text();  
            if (Derechoear == 'Seleccione una opción') {
                Derechoear = 0.0;
            }else{
                Derechoear = Derechoear;
            }
            if (Izquierdoear == 'Seleccione una opción') {                
                Izquierdoear = 0.0;
            } else {
                Izquierdoear = Izquierdoear;
            }
            var oidoIzquierdo = Izquierdoear;
            var oidoDerecho = Derechoear;
            var DeficienciaMonoIz = (((oidoIzquierdo-100)/4)*1.502);
            var DeficienciaMonoDe = (((oidoDerecho-100)/4)*1.502);
            if (DeficienciaMonoIz > DeficienciaMonoDe) {
                var DeficienciaBinaural = (((DeficienciaMonoDe*5)+(DeficienciaMonoIz*1.001))/6.01);
                var TotalDeficienciaBinaural;
                TotalDeficienciaBinaural = DeficienciaBinaural.toFixed(1);
                if (oidoIzquierdo == 370) {
                    DeficienciaMonoIz = 100.0
                    var DeficienciaBinaural = (((DeficienciaMonoDe*5)+(DeficienciaMonoIz*1.001))/6.01);
                    var TotalDeficienciaBinaural;
                    TotalDeficienciaBinaural = DeficienciaBinaural.toFixed(1);
                }
            }else if(DeficienciaMonoDe > DeficienciaMonoIz){
                var DeficienciaBinaural = (((DeficienciaMonoIz*5)+(DeficienciaMonoDe*1.001))/6.01); 
                var TotalDeficienciaBinaural;
                TotalDeficienciaBinaural = DeficienciaBinaural.toFixed(1);
                if (oidoDerecho == 370) {
                    DeficienciaMonoDe = 100.0
                    var DeficienciaBinaural = (((DeficienciaMonoIz*5)+(DeficienciaMonoDe*1.001))/6.01); 
                    var TotalDeficienciaBinaural;
                    TotalDeficienciaBinaural = DeficienciaBinaural.toFixed(1);
                }              
            }else if(DeficienciaMonoDe == 0.0 && DeficienciaMonoIz == 0.0){
                var DeficienciaBinaural = 0.0 
                var TotalDeficienciaBinaural;
                TotalDeficienciaBinaural = DeficienciaBinaural.toFixed(1);               
            }else if(oidoIzquierdo == 370 || oidoDerecho == 370){
                DeficienciaMonoDe = 100.0
                DeficienciaMonoIz = 100.0
                var DeficienciaBinaural = 99.9
                var TotalDeficienciaBinaural;
                TotalDeficienciaBinaural = DeficienciaBinaural.toFixed(1);               
            }else if(DeficienciaMonoDe = DeficienciaMonoIz){
                var DeficienciaBinaural = (((DeficienciaMonoDe*5)+(DeficienciaMonoIz*1.001))/6.01); 
                var TotalDeficienciaBinaural;
                TotalDeficienciaBinaural = DeficienciaBinaural.toFixed(1);               
            }            
            var DeficienciaTotal = (((DeficienciaBinaural*35)/100))                            
            $("#Agudeza_auditiva").empty();
            $('#td_deficiencia').empty();            
            $("#Agudeza_auditiva").append("<tr id='tr_deficiencias'>"+
                                            "<td>" + DeficienciaMonoIz.toFixed(1) + "</td>"+
                                            "<td>" + DeficienciaMonoDe.toFixed(1) + "</td>"+
                                            "<td>" + TotalDeficienciaBinaural + "</td>"+
                                            "<td>"+
                                                "<select class='oido_tinnitus form-control' id='tinnitus'>" +
                                                    "<option value='0' selected>Seleccione</option>" +
                                                    "<option value='3'>3</option>" +
                                                    "<option value='5'>5</option>" +
                                                "</select>"+                                                
                                            "</td>"+
                                        "</tr>");
            $(document).ready(function() {
                
                if ($("#oido_tinnitus option:selected").length == 0) {                    
                    $('#tr_deficiencias').append("<td id='td_deficiencia'>" + Math.round(DeficienciaTotal) + "</td>");                                        
                    
                    $('#calculo_Agudeza_auditiva tbody tr').each(function (){
                        $(this).children("td").each(function(index){
                            var columna = $(this);
                            var valor = columna.text();                
                            if (index === 3) {
                                var selectedValue = columna.find('select').val();
                                registro['columna' + index] = selectedValue;
                            } else {
                                registro['columna' + index] = valor;
                            }
                        });
                        Agudeza_Auditivas.push(registro);
                    });
                }
                
                $(".oido_tinnitus").change(function() {                     
                    var AdicionTinnitus = $(this).val();  
                    var Tinnitus = parseInt(AdicionTinnitus);  
                    $('#td_deficiencia').remove();    
                    if (Tinnitus > 0) {
                        var DeficienciaTotalTinnitus = (DeficienciaTotal + Tinnitus);                        
                        $('#tr_deficiencias').append("<td id='td_deficiencia'>" + Math.round(DeficienciaTotalTinnitus) + "</td>");

                        $('#calculo_Agudeza_auditiva tbody tr').each(function (){
                            $(this).children("td").each(function(index){
                                var columna = $(this);
                                var valor = columna.text();                
                                if (index === 3) {
                                    var selectedValue = columna.find('select').val();
                                    registro['columna' + index] = selectedValue;
                                } else {
                                    registro['columna' + index] = valor;
                                }
                            });
                            Agudeza_Auditivas.push(registro);
                        });

                    } else if(Tinnitus == 0){  
                        $('#tr_deficiencias').append("<td id='td_deficiencia'>" + Math.round(DeficienciaTotal) + "</td>");

                        $('#calculo_Agudeza_auditiva tbody tr').each(function (){
                            $(this).children("td").each(function(index){
                                var columna = $(this);
                                var valor = columna.text();                
                                if (index === 3) {
                                    var selectedValue = columna.find('select').val();
                                    registro['columna' + index] = selectedValue;
                                } else {
                                    registro['columna' + index] = valor;
                                }
                            });
                            Agudeza_Auditivas.push(registro);
                        });                         
                    }  
                });
            }); 

        }   
        $('#form_agregar_agudeza_auditiva').submit(function(e){            
            e.preventDefault();
            document.querySelector('#Guardar_Auditivo').disabled=true;
            var ID_evento = $('#ID_evento').val();
            var Id_Asignacion = $('#Id_Asignacion').val();
            var Id_proceso = $('#Id_proceso').val();
            var oido_izquierdo = $('#oido_izquierdo').val();
            var oido_derecho = $('#oido_derecho').val();
            let token = $("input[name='_token']").val();
            var envio_deficiencias_Auditivas = {
                '_token': token,
                'ID_evento': ID_evento,
                'Id_Asignacion': Id_Asignacion,
                'Id_proceso': Id_proceso,
                'oido_izquierdo': oido_izquierdo,
                'oido_derecho': oido_derecho,
                'Agudeza_Auditivas': Agudeza_Auditivas,
                'Estado_Recalificacion': 'Inactivo',
            };

            $.ajax({
                type: 'POST',
                url: '/guardarDeficienciaAgudezaAuditiva',
                data:envio_deficiencias_Auditivas,
                success:function(response){
                    // console.log(response);
                    if (response.parametro == "insertar_agudeza_auditiva") {                        
                        $('#resultado_insercion_auditiva').removeClass('d-none');
                        $('#resultado_insercion_auditiva').addClass('alert-success');
                        $('#resultado_insercion_auditiva').append('<strong>'+response.mensaje+'</strong>');
                        setTimeout(() => {
                            $('#resultado_insercion_auditiva').addClass('d-none');
                            $('#resultado_insercion_auditiva').removeClass('alert-success');
                            $('#resultado_insercion_auditiva').empty();
                            document.querySelector('#Guardar_Auditivo').disabled=false;
                            location.reload();
                        }, 3000);
                    }
                }
            });
        });   
      
        $(".oido_izquierdo").change(function() {
            Agudeza_Auditivas = [];
          calcularDeficiciasAuditivas();
        });

        $(".oido_derecho").change(function() {
            Agudeza_Auditivas = [];
            calcularDeficiciasAuditivas();
        });

        
    });
});

// script para Editar agudeza auditiva

$(document).ready(function() {
    
    // Inicializacion selectores modal agudeza auditiva
    $(".oido_izquierdo_editar").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".oido_derecho_editar").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    // Cargue de data a los selectores
    let token = $("input[name='_token']").val();

    /* let datos_listado_oido_Izquierdo = {
        '_token': token,
        'parametro':"agudeza_auditiva_editar"
    };
    
    $.ajax({
        type:'POST',
        url:'/selectoresCalificacionTecnicaPCL',
        data: datos_listado_oido_Izquierdo,
        success:function(data){
            //console.log(data);
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#oido_izquierdo').append('<option value="'+data[claves[i]]['Nombre_parametro']+'">'+data[claves[i]]['Nombre_parametro']+'</option>');
            }
        }
    });

    $.ajax({
        type:'POST',
        url:'/selectoresCalificacionTecnicaPCL',
        data: datos_listado_oido_Izquierdo,
        success:function(data){
            //console.log(data);
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#oido_derecho').append('<option value="'+data[claves[i]]['Nombre_parametro']+'">'+data[claves[i]]['Nombre_parametro']+'</option>');
            }
        }
    }); */

    $(document).ready(function() {
        var Agudeza_Auditivas_editar = [];
        var registro_editar = {};
        function calcularDeficiciasAuditivasEditar() {
            var Izquierdoear = $(".oido_izquierdo_editar option:selected").text();
            var Derechoear = $(".oido_derecho_editar option:selected").text();  
            if (Derechoear == 'Seleccione una opción') {
                Derechoear = 0.0;
            }else{
                Derechoear = Derechoear;
            }
            if (Izquierdoear == 'Seleccione una opción') {                
                Izquierdoear = 0.0;
            } else {
                Izquierdoear = Izquierdoear;
            }
            var oidoIzquierdo = Izquierdoear;
            var oidoDerecho = Derechoear;
            var DeficienciaMonoIz = (((oidoIzquierdo-100)/4)*1.502);
            var DeficienciaMonoDe = (((oidoDerecho-100)/4)*1.502);
            if (DeficienciaMonoIz > DeficienciaMonoDe) {
                var DeficienciaBinaural = (((DeficienciaMonoDe*5)+(DeficienciaMonoIz*1.001))/6.01);
                var TotalDeficienciaBinaural;
                TotalDeficienciaBinaural = DeficienciaBinaural.toFixed(1);
                if (oidoIzquierdo == 370) {
                    DeficienciaMonoIz = 100.0
                    var DeficienciaBinaural = (((DeficienciaMonoDe*5)+(DeficienciaMonoIz*1.001))/6.01);
                    var TotalDeficienciaBinaural;
                    TotalDeficienciaBinaural = DeficienciaBinaural.toFixed(1);
                }
            }else if(DeficienciaMonoDe > DeficienciaMonoIz){
                var DeficienciaBinaural = (((DeficienciaMonoIz*5)+(DeficienciaMonoDe*1.001))/6.01); 
                var TotalDeficienciaBinaural;
                TotalDeficienciaBinaural = DeficienciaBinaural.toFixed(1);
                if (oidoDerecho == 370) {
                    DeficienciaMonoDe = 100.0
                    var DeficienciaBinaural = (((DeficienciaMonoIz*5)+(DeficienciaMonoDe*1.001))/6.01); 
                    var TotalDeficienciaBinaural;
                    TotalDeficienciaBinaural = DeficienciaBinaural.toFixed(1);
                }              
            }else if(DeficienciaMonoDe == 0.0 && DeficienciaMonoIz == 0.0){
                var DeficienciaBinaural = 0.0 
                var TotalDeficienciaBinaural;
                TotalDeficienciaBinaural = DeficienciaBinaural.toFixed(1);               
            }else if(oidoIzquierdo == 370 || oidoDerecho == 370){
                DeficienciaMonoDe = 100.0
                DeficienciaMonoIz = 100.0
                var DeficienciaBinaural = 99.9
                var TotalDeficienciaBinaural;
                TotalDeficienciaBinaural = DeficienciaBinaural.toFixed(1);               
            }else if(DeficienciaMonoDe = DeficienciaMonoIz){
                var DeficienciaBinaural = (((DeficienciaMonoDe*5)+(DeficienciaMonoIz*1.001))/6.01); 
                var TotalDeficienciaBinaural;
                TotalDeficienciaBinaural = DeficienciaBinaural.toFixed(1);               
            }            
            var DeficienciaTotal = (((DeficienciaBinaural*35)/100))                            
            $("#Agudeza_auditiva_editar").empty();
            $('#td_deficiencia_editar').empty();            
            $("#Agudeza_auditiva_editar").append("<tr id='tr_deficiencias_editar'>"+
                                            "<td>" + DeficienciaMonoIz.toFixed(1) + "</td>"+
                                            "<td>" + DeficienciaMonoDe.toFixed(1) + "</td>"+
                                            "<td>" + TotalDeficienciaBinaural + "</td>"+
                                            "<td>"+
                                                "<select class='oido_tinnitus_editar form-control' id='tinnitus_editar'>" +
                                                    "<option value='0' selected>Seleccione</option>" +
                                                    "<option value='3'>3</option>" +
                                                    "<option value='5'>5</option>" +
                                                "</select>"+                                                
                                            "</td>"+
                                        "</tr>");
            $(document).ready(function() {
                
                if ($("#oido_tinnitus_editar option:selected").length == 0) {                    
                    $('#tr_deficiencias_editar').append("<td id='td_deficiencia_editar'>" + Math.round(DeficienciaTotal) + "</td>");                                        
                    
                    $('#calculo_Agudeza_auditiva_editar tbody tr').each(function (){
                        $(this).children("td").each(function(index){
                            var columna = $(this);
                            var valor = columna.text();                
                            if (index === 3) {
                                var selectedValue = columna.find('select').val();
                                registro_editar['columnaEditar' + index] = selectedValue;
                            } else {
                                registro_editar['columnaEditar' + index] = valor;
                            }
                        });
                        Agudeza_Auditivas_editar.push(registro_editar);
                    });
                }
                
                $(".oido_tinnitus_editar").change(function() {                     
                    var AdicionTinnitus = $(this).val();  
                    var Tinnitus = parseInt(AdicionTinnitus);  
                    $('#td_deficiencia_editar').remove();    
                    if (Tinnitus > 0) {
                        var DeficienciaTotalTinnitus = (DeficienciaTotal + Tinnitus);                        
                        $('#tr_deficiencias_editar').append("<td id='td_deficiencia_editar'>" + Math.round(DeficienciaTotalTinnitus) + "</td>");

                        $('#calculo_Agudeza_auditiva_editar tbody tr').each(function (){
                            $(this).children("td").each(function(index){
                                var columna = $(this);
                                var valor = columna.text();                
                                if (index === 3) {
                                    var selectedValue = columna.find('select').val();
                                    registro_editar['columnaEditar' + index] = selectedValue;
                                } else {
                                    registro_editar['columnaEditar' + index] = valor;
                                }
                            });
                            Agudeza_Auditivas_editar.push(registro_editar);
                        });

                    } else if(Tinnitus == 0){  
                        $('#tr_deficiencias_editar').append("<td id='td_deficiencia_editar'>" + Math.round(DeficienciaTotal) + "</td>");

                        $('#calculo_Agudeza_auditiva_editar tbody tr').each(function (){
                            $(this).children("td").each(function(index){
                                var columna = $(this);
                                var valor = columna.text();                
                                if (index === 3) {
                                    var selectedValue = columna.find('select').val();
                                    registro_editar['columnaEditar' + index] = selectedValue;
                                } else {
                                    registro_editar['columnaEditar' + index] = valor;
                                }
                            });
                            Agudeza_Auditivas_editar.push(registro_editar);
                        });                       
                    }  
                });
            }); 

        }   
        $('#form_agregar_agudeza_auditiva_editar').submit(function(e){            
            e.preventDefault();
            document.querySelector('#Guardar_Auditivo_editar').disabled=true;
            var ID_evento_editar = $('#ID_evento_editar').val();
            var Id_Asignacion_editar = $('#Id_Asignacion_editar').val();
            var Id_proceso_editar = $('#Id_proceso_editar').val();
            var oido_izquierdo_editar = $('#oido_izquierdo_editar').val();
            var oido_derecho_editar = $('#oido_derecho_editar').val();
            let token = $("input[name='_token']").val();
            var envio_deficiencias_Auditivas_editar = {
                '_token': token,
                'ID_evento_editar': ID_evento_editar,
                'Id_Asignacion_editar': Id_Asignacion_editar,
                'Id_proceso_editar': Id_proceso_editar,
                'oido_izquierdo_editar': oido_izquierdo_editar,
                'oido_derecho_editar': oido_derecho_editar,
                'Agudeza_Auditivas_editar': Agudeza_Auditivas_editar,
            };

            $.ajax({
                type: 'POST',
                url: '/actualizarDeficienciaAgudezaAuditiva',
                data:envio_deficiencias_Auditivas_editar,
                success:function(response){
                    // console.log(response);
                    if (response.parametro == "insertar_agudeza_auditiva_editar") {                        
                        $('#resultado_insercion_auditiva_editar').removeClass('d-none');
                        $('#resultado_insercion_auditiva_editar').addClass('alert-success');
                        $('#resultado_insercion_auditiva_editar').append('<strong>'+response.mensaje+'</strong>');
                        setTimeout(() => {
                            $('#resultado_insercion_auditiva_editar').addClass('d-none');
                            $('#resultado_insercion_auditiva_editar').removeClass('alert-success');
                            $('#resultado_insercion_auditiva_editar').empty();
                            document.querySelector('#Guardar_Auditivo_editar').disabled=false;
                            location.reload();
                        }, 3000);
                    }
                }
            });
        });   
      
        $(".oido_izquierdo_editar").change(function() {
            Agudeza_Auditivas_editar = [];
          calcularDeficiciasAuditivasEditar();
        });

        $(".oido_derecho_editar").change(function() {
            Agudeza_Auditivas_editar = [];
            calcularDeficiciasAuditivasEditar();
        });

        
    });
});