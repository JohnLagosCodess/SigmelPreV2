$(document).ready(function(){
    // Inicializacion del select2 de listados  Módulo Calificacion Tecnica PCL
    $(".origen_firme").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });
    $(".origen_cobertura").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });
    $(".origen_cobertura").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });
    $(".motivo_solicitud").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    // llenado de selectores
    let token = $('input[name=_token]').val();

    //Listado de origen firmeza
    let datos_lista_origen_firme = {
        '_token': token,
        'parametro':"lista_origen_firme_pcl"
    };

    $.ajax({
        type:'POST',
        url:'/selectoresCalificacionTecnicaPCL',
        data: datos_lista_origen_firme,
        success:function(data){
            //console.log(data);
            let NombrefirmezaCalificacionPcl = $('select[name=origen_firme]').val();
            let firmezaCalificacionPcl = Object.keys(data);
            for (let i = 0; i < firmezaCalificacionPcl.length; i++) {
                if (data[firmezaCalificacionPcl[i]]['Id_Parametro'] != NombrefirmezaCalificacionPcl) {                    
                    $('#origen_firme').append('<option value="'+data[firmezaCalificacionPcl[i]]['Id_Parametro']+'">'+data[firmezaCalificacionPcl[i]]['Nombre_parametro']+'</option>');
                }
            }
        }
    });

    //Listado de origen cobertura
    let datos_lista_origen_cobertura = {
        '_token': token,
        'parametro':"lista_origen_cobertura_pcl"
    };

    $.ajax({
        type:'POST',
        url:'/selectoresCalificacionTecnicaPCL',
        data: datos_lista_origen_cobertura,
        success:function(data){
            //console.log(data);
            let NombrecoberturaCalificacionPcl = $('select[name=origen_cobertura]').val();
            let coberturaCalificacionPcl = Object.keys(data);
            for (let i = 0; i < coberturaCalificacionPcl.length; i++) {
                if (data[coberturaCalificacionPcl[i]]['Id_Parametro'] != NombrecoberturaCalificacionPcl) {                    
                    $('#origen_cobertura').append('<option value="'+data[coberturaCalificacionPcl[i]]['Id_Parametro']+'">'+data[coberturaCalificacionPcl[i]]['Nombre_parametro']+'</option>');
                }
            }
        }
    });

    //Listado de origen cobertura
    let datos_lista_cali_decreto = {
        '_token': token,
        'parametro':"lista_cali_decreto_pcl"
    };

    $.ajax({
        type:'POST',
        url:'/selectoresCalificacionTecnicaPCL',
        data: datos_lista_cali_decreto,
        success:function(data){
            //console.log(data);
            let NombredecretoCalificacionPcl = $('select[name=decreto_califi]').val();
            let decretoCalificacionPcl = Object.keys(data);
            for (let i = 0; i < decretoCalificacionPcl.length; i++) {
                if (data[decretoCalificacionPcl[i]]['Id_Decreto'] != NombredecretoCalificacionPcl) {                    
                    $('#decreto_califi').append('<option value="'+data[decretoCalificacionPcl[i]]['Id_Decreto']+'">'+data[decretoCalificacionPcl[i]]['Nombre_decreto']+'</option>');
                }
            }
        }
    });

    //Listado de motivo solicitud
    let datos_lista_motivo_solicitud = {
        '_token': token,
        'parametro':"lista_motivo_solicitud"
    };

    $.ajax({
        type:'POST',
        url:'/selectoresCalificacionTecnicaPCL',
        data: datos_lista_motivo_solicitud,
        success:function(data){
            //console.log(data);
            let NombremotivoCalificacionPcl = $('select[name=motivo_solicitud]').val();
            let motivoSolicitudPcl = Object.keys(data);
            for (let i = 0; i < motivoSolicitudPcl.length; i++) {
                if (data[motivoSolicitudPcl[i]]['Id_Solicitud'] != NombremotivoCalificacionPcl) {                    
                    $('#motivo_solicitud').append('<option value="'+data[motivoSolicitudPcl[i]]['Id_Solicitud']+'">'+data[motivoSolicitudPcl[i]]['Nombre_solicitud']+'</option>');
                }
            }
        }
    });

});