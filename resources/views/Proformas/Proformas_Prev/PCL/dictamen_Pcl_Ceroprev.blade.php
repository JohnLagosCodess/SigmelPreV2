<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>                
        @page { margin: 2.5cm 1.3cm 2.5cm 1.3cm; }        
        #header {
            position: fixed; 
            top: -2.3cm;
            left: 0cm;
            width: 100%;
            /* height: 100px; */
            text-align: center; 
        }

        .codigo_qr{
            /* position: absolute;
            top: 5px; 
            left: 5px;  */
            max-width: 90px; 
            max-height: 70px;  
        }

        .titulo_header{            
            font-weight: bold;
            left: 15px; 
            max-width: 540px; 
            font-size: 13px;            
            color: black;
            text-align: center !important;
        }

        .logo_header{
            /* position: absolute; */
            max-width: 100%;
            height: auto;
            /* left: 547px; */
            max-height: 75px;
        } 
        
        .sinborder{
            border: none;
        }

        .sinborderlaterales{
            border-top: 1px solid black;
            border-bottom: none;
            border-left: none;
            border-right: none;
        }

        .sinborderinferior{
            border-bottom: none;
        }

        #footer{
            position: fixed;
            bottom: -2.2cm;
            left: 0cm;
            width: 100%;
        }

        .tabla_header{
            width: 100%;
            font-family: sans-serif;
            font-size: 13px;
            text-align: center;            
        }

        .tabla_header td {
            border: none;
        }

        .tabla_footer{
            width: 100%;
            font-family: sans-serif;
            font-size: 13px;
            text-align: center;            
        }

        .tabla_footer td {
            border: none;
        }
        
        .tabla_dictamen{
            font-family: sans-serif;
            text-align: justify;
            width: 100%;
            table-layout: fixed; 
            border-collapse: collapse;
        }

        .tabla_dictamen, td, th {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 1.5px
        }

        .tabla_diagnosticos{
            font-family: sans-serif;
            text-align: justify;
            width: 100%;
            table-layout: fixed; 
            border-collapse: collapse;
        }

        .tabla_diagnosticos, td, th {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 1.5px
        }

        .titulo_tablas{
            font-weight: bold;
            font-size: 12px;
            background: #D9D9D9;
            color: black;
            text-align: center !important;
        }

        .titulo_labels{
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }

        .centrar_titulo_labels{
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            background: #D9D9D9;
            color: black;
        }

        .centrar_dato_labels{
            text-align: center;
            font-size: 11px;
        }

        .right_titulo_labels{
            text-align: right;
            font-size: 11px;
        }

        .left_titulo_labels{
            text-align: left;
            font-size: 11px;
        }

        .titulos_labels_pequeños{
            background: #D9D9D9;
            font-weight: bold;
            color: black;
        }

        .dato_dinamico{
            text-align: justify;
            font-size: 11px !important;
        }

        .dato_dinamico_font{
            font-family: sans-serif;
            text-align: justify;
            font-size: 11px !important;  
            padding-left: 2px;          
        }

        .border_section{
            border: 1px solid #000;
        }

        .centrar_dato_dinamico{
            text-align: center;
            font-size: 11px !important;
        }

        .label_rol_laboral{
            text-align: center;
            font-size: 11px !important;
            background: #D9D9D9;
        }

        .label_area_ocupacional{
            text-align: center;
            font-size: 11px !important;
            font-weight: bold;            
            background: #D9D9D9;
        }

        .label_valoracion_final{
            text-align: right;
            font-size: 11px !important;
            font-weight: bold;            
            background: #D9D9D9;
        }

        .explicacionFB{
            text-align: center;
            font-size: 11px;
        }

        .right_dato_dinamico{
            text-align: right;
            font-size: 11px;
        }

        .decreto1352{
            text-align: justify;
            font-size: 10px;
            font-family: sans-serif;
            font-style: italic;
            color: #000;            
        }

        .seccion8{
            font-family: sans-serif;
            font-weight: bold;
            font-size: 12px;
            background: #D9D9D9;
            color: black;
            text-align: center !important;
            border: 1px solid #000;
        }

        .div_firmas{
            text-align: justify;
            font-size: 11px !important;
            border: 1px solid #000;
        }

        .table-cell {
            display: table-cell;
            width: 33.33%;
            border: 1px solid black;
            padding: 10px;
            text-align: center;
            vertical-align: middle;
        }
        .table-row {
            display: table;
            width: 100%;
            table-layout: fixed; 
        }

        .fecha_firma{
            font-size: 8px;
        }  

        .firma_1, .firma_2, .firma_3{           
            width: auto;
            max-width: 100%; 
            height: auto; 
        }

        #footer .page:after { content: counter(page, upper-decimal); }   
        #content { margin-top: 10px; }         
        .content2 { padding-left: 1.5px; padding-right: 1.5px; }         
    </style>    
</head>
<body>
    <?php
        if ($Fecha_dictamen == ''){
            $Fecha_dictamenF = '';
            $Fecha_Firma = '';
        } else {            
            $Fecha_dictamenF = date("d/m/Y", strtotime($Fecha_dictamen));
            $Fecha_Firma = date("d/m/Y", strtotime($Fecha_dictamen));
        }    
        $F_nacimiento_per_calF = date("d/m/Y", strtotime($F_nacimiento_per_cal));
        $F_estructuracion_dpF = date("d/m/Y", strtotime($F_estructuracion_dp));
        if ($F_evento_dp == '0000-00-00' || $F_evento_dp == '') {
            $F_evento_dpF = '';
        } else {
            $F_evento_dpF = date("d/m/Y", strtotime($F_evento_dp));            
        }
    ?>
    <div id="header">
        <table class="tabla_header">
            <tbody>
                <tr>
                    <td style="width:25%; text-align: left;">
                        <img src="data:image/png;base64,{{ base64_encode($codigoQR) }}" class="codigo_qr" alt="Código QR">
                    </td>
                    <td style="width:50%; text-align: center;">
                        <p class="titulo_header">
                            FORMULARIO DE CALIFICACIÓN DE LA PERDIDA 
                            DE LA CAPACIDAD LABORAL Y OCUPACIONAL
                            DECRETO 1507 agosto 12 de 2014
                        </p> 
                    </td>
                    <td style="width:25%; text-align: right;">
                        <?php if($logo_header == "Sin logo"): ?>
                            <p>No logo</p>
                        <?php else: ?>
                            <?php 
                                $ruta_logo = "/logos_clientes/{$Id_cliente_ent}/{$logo_header}";
                                $imagenPath_header = public_path($ruta_logo);
                                $imagenData_header = file_get_contents($imagenPath_header);
                                $imagenBase64_header = base64_encode($imagenData_header);
                            ?>
                            <img src="data:image/png;base64,{{ $imagenBase64_header }}" class="logo_header">
                        <?php endif ?>                    
                    </td>
                </tr>
            </tbody>
        </table>
    </div>         
    <div id="footer">
        <table class="tabla_footer">
            <tbody>
                <tr>                    
                    <td>
                        <p class="centrar">{{$Nombre_afiliado_pre}} - {{$Documento_afiliado}} {{$Numero_documento_afiliado}} - SINIESTRO {{$N_siniestro}}</p>
                    </td>
                    <td>
                        <p class="page">Página </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>                     
    <div id="content">        
        <table class="tabla_dictamen">
            <tr>
                <td colspan="17" class="titulo_tablas">1. INFORMACIÓN GENERAL DEL DICTAMEN PERICIAL</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Fecha dictamen:</td>  
                <td colspan="5" class="dato_dinamico">{{$Fecha_dictamenF}}</td>
                <td colspan="3" class="titulo_labels">Dictamen No</td>
                <td colspan="5" class="dato_dinamico">{{$DictamenNo}}</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Motivo de solicitud:</td>
                <td colspan="5" class="dato_dinamico">{{$Motivo_solicitud}}</td>
                <td colspan="3" class="titulo_labels">Solicitante:</td>
                <td colspan="5" class="dato_dinamico">{{$Solicitante_dic}}</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Nombre de solicitante: </td>
                <td colspan="13" class="dato_dinamico">{{$Nombre_entidad_dic}}</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Nit/Documento de Identidad:</td>
                <td colspan="5" class="dato_dinamico">{{$Nit_entidad}}</td>
                <td colspan="3" class="titulo_labels">Teléfono solicitante: </td>
                <td colspan="5" class="dato_dinamico">{{$Telefonos_dic}}</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Dirección solicitante: </td>
                <td colspan="13" class="dato_dinamico">{{$Direccion_dic}}</td>                
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">E-mail solicitante: </td>
                <td colspan="5" class="dato_dinamico">{{wordwrap($Emails_dic, 34, "\n", true);}}</td>
                <td colspan="3" class="titulo_labels">Ciudad solicitante: </td>
                <td colspan="5" class="dato_dinamico">{{$Nombre_municipio_dic}}</td>
            </tr>   
            <tr>
                <td colspan="17" class="titulo_tablas">2. INFORMACIÓN GENERAL DE LA ENTIDAD CALIFICADORA</td>
            </tr>
            <tr>
                <td colspan="2" class="titulo_labels">Nombre:</td>
                <td colspan="5" class="dato_dinamico">{{$Nombre_cliente_ent}}</td>
                <td colspan="1" class="titulo_labels">Nit</td>
                <td colspan="3" class="dato_dinamico">{{$Nit_ent}}</td>
                <td colspan="3" class="titulo_labels">Teléfono:</td>
                <td colspan="3" class="dato_dinamico">{{$Telefono_principal_ent}}</td>
            </tr>
            <tr>
                <td colspan="2" class="titulo_labels">Dirección:</td>
                <td colspan="6" class="dato_dinamico">{{$Direccion_ent}}</td>
                <td colspan="2" class="titulo_labels">E-mail:</td>
                <td colspan="7" class="dato_dinamico">{{$Email_principal_ent}}</td>
            </tr>
            <tr>
                <td colspan="17" class="titulo_tablas">3. DATOS GENERALES DE LA PERSONA CALIFICADA</td>
            </tr>
            <tr>
                <td colspan="2" class="titulo_labels">Afiliado:</td>
                <td colspan="6" class="centrar_dato_dinamico">{{$Afiliado_per_cal}}</td>
                <td colspan="2" class="titulo_labels">Beneficiario:</td>
                <td colspan="7" class="centrar_dato_dinamico">{{$Beneficiario_per_cal}}</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Nombres:</td>
                <td colspan="13" class="dato_dinamico">{{$ResultadoNombre_per_cal}}</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Documento de identificación:</td>
                <td colspan="5" class="dato_dinamico">{{$Tipo_documento_per_cal}}</td>
                <td colspan="3" class="titulo_labels">N° de identificación:</td>
                <td colspan="5" class="dato_dinamico">{{$NroIden_per_cal}}</td>
            </tr>
            <tr>                
                <td colspan="4" class="titulo_labels">Fecha nacimiento:</td>
                <td colspan="5" class="dato_dinamico">{{$F_nacimiento_per_calF}}</td>
                <td colspan="3" class="titulo_labels">Edad:</td>
                <td colspan="5" class="dato_dinamico">{{$Edad_per_cal}} años</td>
            </tr>
            <tr>
                <td colspan="2" class="titulo_labels">Escolaridad:</td>
                <td colspan="4" class="dato_dinamico">{{$Nivel_escolar_per_cal}}</td>
                <td colspan="2" class="titulo_labels">Estado civil:</td>
                <td colspan="4" class="dato_dinamico">{{$Estado_civil_per_cal}}</td>
                <td colspan="2" class="titulo_labels">Teléfono:</td>
                <td colspan="3" class="dato_dinamico">{{$Telefono_per_cal}}</td>
            </tr>
            <tr>
                <td colspan="2" class="titulo_labels">Dirección:</td>
                <td colspan="9" class="dato_dinamico">{{$Direccion_per_cal}}</td>
                <td colspan="2" class="titulo_labels">Ciudad</td>
                <td colspan="4" class="dato_dinamico">{{$Ciudad_per_cal}}</td>                
            </tr>
            <tr>
                <td colspan="2" class="titulo_labels">E-mail:</td>
                <td colspan="15" class="dato_dinamico">{{$Email_per_cal}}</td>
            </tr>
            <tr>
                <td colspan="17" class="dato_dinamico">En caso de calificar un beneficiario, anotar los datos del afiliado:</td>
            </tr>
            <tr>
                <td colspan="3" class="titulo_labels">Nombre y apellidos</td>
                <td colspan="5" class="dato_dinamico">{{$Nombre_ben}}</td>
                <td colspan="4" class="titulo_labels">Documento de identidad</td>
                <td colspan="5" class="dato_dinamico">{{$Documento_iden_ben}}</td>
            </tr>
            <tr>                
                <td colspan="3" class="titulo_labels">Teléfono</td>
                <td colspan="5" class="dato_dinamico">{{$Telefono_iden_ben}}</td>
                <td colspan="4" class="titulo_labels">Ciudad</td>
                <td colspan="5" class="dato_dinamico">{{$Ciudad_iden_ben}}</td>
            </tr> 
            <tr>
                <td colspan="17" class="titulo_tablas">ETAPAS DEL CICLO VITAL:</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Población en edad económicamente activa:</td>
                <td colspan="1" class="centrar_dato_dinamico">{{$Poblacion_edad_econo_activa}}</td>                
                <td colspan="3" class="titulo_labels">Bebés y menores de 3 años:</td>
                <td colspan="1" class="centrar_dato_dinamico">{{$Bebe_menor3}}</td>
                <td colspan="3" class="titulo_labels">Niños y adolescentes:</td>
                <td colspan="1" class="centrar_dato_dinamico">{{$Ninos_adolecentes}}</td>
                <td colspan="3" class="titulo_labels">Adultos Mayores:</td>
                <td colspan="1" class="centrar_dato_dinamico">{{$Adultos_mayores}}</td>
            </tr>
            <tr>
                <td colspan="17" class="dato_dinamico">En caso de calificar un menor de edad, anotar los datos del acudiente o adulto responsable:</td>
            </tr>
            <tr>
                <td colspan="3" class="titulo_labels">Nombre y apellidos</td>
                <td colspan="5" class="dato_dinamico">{{$Nombre_acudiente}}</td>
                <td colspan="4" class="titulo_labels">Documento de identidad</td>
                <td colspan="5" class="dato_dinamico">{{$Documento_acudiente}}</td>
            </tr>
            <tr>
                <td colspan="3" class="titulo_labels">Teléfono</td>
                <td colspan="5" class="dato_dinamico">{{$Telefono_acudiente}}</td>
                <td colspan="4" class="titulo_labels">Ciudad</td>
                <td colspan="5" class="dato_dinamico">{{$Ciudad_acudiente}}</td>
            </tr>  
            <tr>
                <td colspan="17" class="titulo_tablas">AFILIACIÓN AL  SISS:</td>
            </tr>
            <tr>
                <td colspan="3" class="titulo_labels">Régimen en salud</td>
                <td colspan="4" class="dato_dinamico">Contributivo:</td>
                <td colspan="1" class="centrar_dato_dinamico">{{$Contributivo_ecv}}</td>
                <td colspan="4" class="dato_dinamico">Subsidiado:</td>
                <td colspan="1" class="centrar_dato_dinamico">{{$Subsidiado_ecv}}</td>
                <td colspan="3" class="dato_dinamico">No afiliado:</td>
                <td colspan="1" class="centrar_dato_dinamico">{{$No_afiliado_ecv}}</td>
            </tr>
            <tr>
                <td colspan="3" rowspan="2" class="titulo_labels">Administradoras:</td>
                <td colspan="4" class="centrar_dato_labels">EPS:</td>
                <td colspan="3" class="centrar_dato_labels">AFP:</td>
                <td colspan="4" class="centrar_dato_labels">ARL:</td>
                <td colspan="3" class="centrar_dato_labels">Otros:</td>
            </tr>
            <tr>
                <td colspan="4" class="dato_dinamico">{{$Entidad_eps}}</td>
                <td colspan="3" class="dato_dinamico">{{$Entidad_afp}}</td>
                <td colspan="4" class="dato_dinamico">{{$Entidad_arl}}</td>
                <td colspan="3" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="17" class="titulo_tablas">4. ANTECEDENTES LABORALES DEL CALIFICADO</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Independiente:</td>
                <td colspan="5" class="centrar_dato_dinamico">{{$Independiente_laboral}}</td>
                <td colspan="2" class="titulo_labels">Dependiente:</td>
                <td colspan="6" class="centrar_dato_dinamico">{{$Dedependiente_laboral}}</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Nombre del cargo:</td>
                <td colspan="5" class="dato_dinamico">{{$Nombre_cargo_laboral}}</td>
                <td colspan="2" class="titulo_labels">Ocupación:</td>
                <td colspan="6" class="dato_dinamico">{{$Ocupacion_afiliado}}</td>
            </tr>
            <tr>
                <td colspan="2" class="titulo_labels">Código CIUO:</td>
                <td colspan="5" class="dato_dinamico">{{$Codigo_ciuo_laboral}}</td>
                <td colspan="3" class="titulo_labels">Actividad económica:</td>
                <td colspan="5" class="dato_dinamico">{{$Actividad_econo_laboral}}</td>
                <td colspan="1" class="titulo_labels">Clase:</td>
                <td colspan="1" class="centrar_dato_dinamico">{{$Clase_laboral}}</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Funciones del cargo:</td>
                <td colspan="13" class="dato_dinamico">{{$Funciones_cargo_laboral}}</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Nombre de la empresa:</td>
                <td colspan="9" class="dato_dinamico">{{$Empresa_laboral}}</td>
                <td colspan="1" class="titulo_labels">Nit:</td>
                <td colspan="3" class="dato_dinamico">{{$Nit_laboral}}</td>
            </tr>   
            <tr>
                <td colspan="17" class="titulo_tablas">5. RELACIÓN DE DOCUMENTOS/EXAMEN FÍSICO(Descripción)</td>
            </tr>
        </table>                
        <div class="content2">
            @if (count($array_datos_relacion_examentes) > 0)
                @foreach ($array_datos_relacion_examentes->sortBy('F_examen_interconsulta') as $examenes_interconsultas)
                    <section class="border_section">
                        <div class="dato_dinamico_font"><b>Fecha:</b> <?php echo date('d/m/Y', strtotime($examenes_interconsultas->F_examen_interconsulta))?> - <b>Nombre del documento:</b> <?php echo $examenes_interconsultas->Nombre_examen_interconsulta?></div>
                        <div class="dato_dinamico_font"><b>Descripción:</b></div>
                        <div class="dato_dinamico_font"><?php echo $examenes_interconsultas->Descripcion_resultado?></div>
                    </section>                    
                @endforeach                            
            @endif            
        </div> 
        <table class="tabla_dictamen">                
            <tr>
                <td colspan="17" class="titulo_tablas">6. FUNDAMENTOS PARA LA CALIFICACIÓN DE LA PERDIDA DE LA CAPACIDAD LABORAL Y OCUPACIONAL - TITULOS I Y II</td>
            </tr>
        </table> 
        <div class="content2">
            <section class="border_section">
                <div class="dato_dinamico_font"><b>Descripción de la enfermedad Actual:</b></div>
                <div class="dato_dinamico_font">{{$Descripcion_enfermedad_actual}}</div>
            </section>
        </div>  
        <table class="tabla_dictamen">            
            <tr>
                <td colspan="17" class="titulo_tablas">TÍTULO I CLASIFICACIÓN / VALORACIÓN DE LAS DEFICIENCIAS</td>
            </tr>
            <tr>
                <td colspan="1" rowspan="1" class="centrar_titulo_labels">No.</td>
                <td colspan="1" rowspan="1" class="centrar_titulo_labels">CIE 10</td>
                <td colspan="5" rowspan="1" class="centrar_titulo_labels">Diagnóstico</td>
                <td colspan="2" rowspan="1" class="centrar_titulo_labels">Lateralidad</td>
                <td colspan="6" rowspan="1" class="centrar_titulo_labels">Deficiencia(s) motivo de calificación / condiciones de salud</td>                
                <td colspan="2" rowspan="1" class="centrar_titulo_labels">Origen</td>
            </tr>
            <?php $conteo_deficienciass = 0; ?>
            <?php $cumple_condicions = 0; ?>
            <?php $rowspan_totals = 0; ?>            
            @if (count($array_diagnosticos_fc) > 0)
                <?php $cumple_condicions++; ?>
                <?php $rowspan_totals += count($array_diagnosticos_fc); ?>
                @foreach ($array_diagnosticos_fc as $index => $deficiencias_fc)                    
                    <?php $conteo_deficienciass = $conteo_deficienciass + 1; ?>                   
                    <tr>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $conteo_deficienciass }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_fc->Codigo_cie10 }}</td>
                        <td colspan="5" class="dato_dinamico">{{ $deficiencias_fc->Nombre_CIE10 }}</td>
                        <td colspan="2" class="dato_dinamico">{{ $deficiencias_fc->Nombre_lateralidad }}</td>
                        <td colspan="6" class="dato_dinamico">{{ $deficiencias_fc->Deficiencia_motivo_califi_condiciones }}</td>                                                                    
                        <td colspan="2" class="dato_dinamico">{{($deficiencias_fc->Nombre_origen)}}</td>
                    </tr>
                @endforeach             
            @else
                <tr>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="5" class="dato_dinamico"></td>
                    <td colspan="2" class="dato_dinamico"></td>
                    <td colspan="6" class="dato_dinamico"></td>
                    <td colspan="2" class="dato_dinamico"></td>
                </tr>         
            @endif        
            <tr>
                <td colspan="17"></td>
            </tr>    
            <tr>
                <td colspan="17" class="titulo_tablas">Deficiencias por Alteraciones de los Sistemas Generales cálculadas por factores</td>
            </tr>            
            <tr>
                <td colspan="1" rowspan="1" class="centrar_titulo_labels">No.</td>
                <td colspan="2" rowspan="1" class="centrar_titulo_labels">Nombre de deficiencia</td>
                <td colspan="1" rowspan="1" class="centrar_titulo_labels">No. Tabla</td>
                <td colspan="1" rowspan="1" class="centrar_titulo_labels">Clase / FP</td>
                <td colspan="1" rowspan="1" class="centrar_titulo_labels">FU</td>
                <td colspan="1" rowspan="1" class="centrar_titulo_labels">CFM1</td>
                <td colspan="1" rowspan="1" class="centrar_titulo_labels">CFM2</td>
                <td colspan="1" rowspan="1" class="centrar_titulo_labels">CFM3</td>
                <td colspan="2" class="centrar_titulo_labels">Clase final y Literal</td>
                <td colspan="1" rowspan="1" class="centrar_titulo_labels">MSD</td>
                <td colspan="2" class="centrar_titulo_labels">% Deficiencia.</td>
                <td colspan="1" rowspan="1" class="centrar_titulo_labels">CAT</td>
                <td colspan="2" rowspan="1" class="centrar_titulo_labels">Dominancia</td>
            </tr>                        
            <?php $conteo_deficiencias = 0; ?>  
            <?php $cumple_condicion = 0; ?>
            <?php $rowspan_total = 0; ?>            
            @if (count($array_deficiencias_alteraciones) > 0)
                <?php $cumple_condicion++; ?>
                <?php $rowspan_total += count($array_deficiencias_alteraciones); ?>
                @foreach ($array_deficiencias_alteraciones as $index => $deficiencias_fc)                    
                    <?php $conteo_deficiencias = $conteo_deficiencias + 1; ?>                   
                    <tr>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $conteo_deficiencias }}</td>
                        <td colspan="2" class="dato_dinamico">{{ $deficiencias_fc->Nombre_tabla }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_fc->Ident_tabla }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_fc->FP }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_fc->FU }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_fc->CFM1 }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_fc->CFM2 }}</td>
                        <td colspan="1" class="centrar_dato_dinamico"></td>
                        <td colspan="2" class="centrar_dato_dinamico">{{ $deficiencias_fc->Clase_Final }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_fc->MSD }}</td>
                        <td colspan="2" class="centrar_dato_dinamico">{{ $deficiencias_fc->Total_deficiencia }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_fc->CAT }}</td>
                        <td colspan="2" class="centrar_dato_dinamico">{{ $deficiencias_fc->MSD }}</td>                        
                    </tr>
                @endforeach  
            @else
                <tr>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="2" class="dato_dinamico"></td>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="2" class="centrar_dato_dinamico"></td>
                    <td colspan="2" class="centrar_dato_dinamico"></td>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="2" class="centrar_dato_dinamico"></td>
                </tr>
         
            @endif                 
            <tr>
                <td colspan="6" class="explicacionFB sinborder"><b>CFP:</b> Clase Factor principal</td>
                <td colspan="6" class="explicacionFB sinborder"><b>CFM:</b> Clase Factor Modulador</td>
                <td colspan="5" class="explicacionFB sinborder"><b>CFU:</b> Clase Factor único</td>
            </tr>
            <tr>
                <td colspan="17" class="explicacionFB sinborder"><b>Formula Ajuste Total de Deficiencias por tabla:</b> (CFM1-CFP) + (CFM2-CFP) + (CFM3-CFP)</td>
            </tr>
            <tr>
                <td colspan="8" rowspan="1" class="explicacionFB sinborder"><b>Fórmula de Balthazar:</b> Obtiene el valor final de las deficiencias sin ponderar</td>
                <td colspan="5" class="explicacionFB sinborder"><b>A +  (100 -  A) * B</b><br><hr style="border: 0.1px solid black;"><b>100</b></td>
                <td colspan="4" class="explicacionFB sinborder">A: Deficiencia de mayor valor<br>B: Deficiencia de menor valor</td>
            </tr>
            <tr>
                <td colspan="7" class="right_dato_dinamico"><b>% Total Deficiencia (F. Balthazar, sin ponderar)</b></td>
                <td colspan="1" class="centrar_dato_dinamico">{{$Suma_combinada_fc}}</td>
                <td colspan="8" class="right_dato_dinamico"><b>CÁLCULO FINAL DE LA DEFICIENCIA PONDERADA (% Deficiencia sin ponderar X 0,5)</b>=</td>
                <td colspan="1" class="centrar_dato_dinamico">{{$Total_deficiencia50_fc}}</td>
            </tr>
            <tr>
                <td colspan="17"></td>
            </tr>
            <tr>
                <td colspan="17" class="titulo_tablas">Título II Valoración del Rol Laboral, Rol ocupacional y otras Áreas ocupacionales (50%)</td>
            </tr>
            <tr>
                <td colspan="16" class="right_dato_dinamico"><b>Total valoración del Rol Laboral, Rol Ocupacional y otras Áreas ocupacionales(50%)</b></td>
                <td colspan="1" class="centrar_dato_dinamico">0</td>
            </tr>            
            <tr>
                <td colspan="17"></td>
            </tr>
            <tr>
                <td colspan="17" class="titulo_tablas">7. CONCEPTO FINAL DEL DICTAMEN PERICIAL</td>
            </tr>
            <tr>
                <td colspan="17" class="centrar_dato_labels"><b>Pérdida de Capacidad Laboral</b> =  TÍTULO I -Valor Final Ponderada  +   TÍTULO II -Valor Final</td>
            </tr>
            <tr>
                <td colspan="16" class="label_valoracion_final">VALOR FINAL DE LA PCL / OCUPACIONAL %</td>
                <td class="centrar_dato_dinamico">{{$Porcentaje_Pcl_dp}}</td>
            </tr>
            <tr>
                <td colspan="17"></td>
            </tr>
            <tr>
                <td colspan="5" class="titulo_labels">FECHA DE ESTRUCTURACIÓN</td>
                <td colspan="4" class="dato_dinamico"> {{$F_estructuracion_dpF}}</td>
                <td colspan="3" class="titulo_labels">TIPO DE EVENTO</td>
                <td colspan="5" class="dato_dinamico"> {{$Tipo_evento_dp}}</td>
            </tr>
            <tr>
                <td colspan="5" class="titulo_labels">FECHA ACCIDENTE / ENFERMEDAD</td>
                <td colspan="4" class="dato_dinamico"> <?php if($F_evento_dpF<>'31-12-1969' || $F_evento_dpF<>'0000-00-00'){ echo $F_evento_dpF;}?></td>
                <td colspan="3" class="titulo_labels">ORIGEN</td>
                <td colspan="5" class="dato_dinamico"> {{$Origen_dp}}</td>
            </tr>
        </table>
        <div class="content2">
            <section class="border_section">
                <div class="dato_dinamico_font"><b>Sustentación de la Fecha de estructuración:</b></div>
                <div class="dato_dinamico_font">{{$Sustentacion_F_estructuracion_dp}}</div>
            </section>
        </div>
        <div class="content2">
            <section class="border_section">
                <div class="dato_dinamico_font"><b>Detalle de la calificación:</b></div>
                <div class="dato_dinamico_font"><?php echo nl2br($Detalle_calificacion_dp); ?></div>
            </section>
        </div>
        <table class="tabla_dictamen">
            <tr>
                <td colspan="17">
                    <p class="decreto1352">
                        Esta calificación de pérdida de capacidad laboral es producto de la información suministrada por el usuario y se realiza bajo el entendido que no existe un primer dictamen, no obstante, si se llega a conocer que hay uno que se encuentre en firme por el o los mismos 
                        diagnósticos o en trámite ante alguna de las entidades competentes y/o en cualquier instancia, es importante indicar que esta segunda calificación no tendría validez y tampoco sería posible de controversia ante las Juntas Regionales de Calificación de Invalidez ni de 
                        demanda ante la Jurisdicción Ordinaria Laboral. La anterior aclaración, por cuanto es nuestro interés prestar el servicio requerido sin llegar a incurrir en la conducta irregular de que trata el artículo 32 del Decreto 1352 de 2013.
                    </p>
                    <div class="seccion8">
                        8. GRUPO CALIFICADOR
                    </div>   
                    @if (count($validacion_visado) > 0)
                        <div class="table-row">
                            <div class="table-cell">
                                <?php 
                                $ruta_firma_1 = "/Firmas_provisionales/firma_comite_lina_pcl.png";
                                $imagenPath_firma_1 = public_path($ruta_firma_1);
                                $imagenData_firma_1 = file_get_contents($imagenPath_firma_1);
                                $imagenBase64_firma_1 = base64_encode($imagenData_firma_1);
                                ?>
                                <div style="text-align: center;">
                                    <img src="data:image/png;base64,{{ $imagenBase64_firma_1 }}" class="firma_1">
                                    <p class="fecha_firma"><b>Fecha de firma: <?= $Fecha_Firma; ?></b></p>
                                </div>
                            </div>
                            <div class="table-cell">
                                <?php 
                                $ruta_firma_2 = "/Firmas_provisionales/firma_comite_julian_pcl.png";
                                $imagenPath_firma_2 = public_path($ruta_firma_2);
                                $imagenData_firma_2 = file_get_contents($imagenPath_firma_2);
                                $imagenBase64_firma_2 = base64_encode($imagenData_firma_2);
                                ?>
                                <div style="text-align: center;">
                                    <img src="data:image/png;base64,{{ $imagenBase64_firma_2 }}" class="firma_2">
                                    <p class="fecha_firma"><b>Fecha de firma: <?= $Fecha_Firma; ?></b></p>
                                </div>
                            </div>
                            <div class="table-cell">
                                <?php 
                                $ruta_firma_3 = "/Firmas_provisionales/firma_comite_liliana_pcl.png";
                                $imagenPath_firma_3 = public_path($ruta_firma_3);
                                $imagenData_firma_3 = file_get_contents($imagenPath_firma_3);
                                $imagenBase64_firma_3 = base64_encode($imagenData_firma_3);
                                ?>
                                <div style="text-align: center;">
                                    <img src="data:image/png;base64,{{ $imagenBase64_firma_3 }}" class="firma_3">
                                    <p class="fecha_firma"><b>Fecha de firma: <?= $Fecha_Firma; ?></b></p>
                                </div>
                            </div>
                        </div>  
                    @else
                        <div class="table-row">
                            <div class="table-cell">                                
                            </div>
                            <div class="table-cell">                                
                            </div>
                            <div class="table-cell">                                
                            </div>
                        </div>
                    @endif
                </td>
            </tr>            
        </table>        
    </div>    
</body>
</html>