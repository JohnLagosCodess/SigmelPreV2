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
                <td colspan="18" class="titulo_tablas">1. INFORMACIÓN GENERAL DEL DICTAMEN PERICIAL</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Fecha dictamen:</td>                  
                <td colspan="5" class="dato_dinamico">{{$Fecha_dictamenF}}</td>
                <td colspan="4" class="titulo_labels">Dictamen No</td>
                <td colspan="5" class="dato_dinamico">{{$DictamenNo}}</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Motivo de solicitud:</td>
                <td colspan="5" class="dato_dinamico">{{$Motivo_solicitud}}</td>
                <td colspan="4" class="titulo_labels">Solicitante:</td>
                <td colspan="5" class="dato_dinamico">{{$Solicitante_dic}}</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Nombre de solicitante: </td>
                <td colspan="14" class="dato_dinamico">{{$Nombre_entidad_dic}}</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Nit/Documento de Identidad:</td>
                <td colspan="5" class="dato_dinamico">{{$Nit_entidad}}</td>
                <td colspan="4" class="titulo_labels">Teléfono solicitante: </td>
                <td colspan="5" class="dato_dinamico">{{$Telefonos_dic}}</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Dirección solicitante: </td>
                <td colspan="14" class="dato_dinamico">{{$Direccion_dic}}</td>                
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">E-mail solicitante: </td>
                <td colspan="5" class="dato_dinamico">{{wordwrap($Emails_dic, 34, "\n", true);}}</td>
                <td colspan="4" class="titulo_labels">Ciudad solicitante: </td>
                <td colspan="5" class="dato_dinamico">{{$Nombre_municipio_dic}}</td>
            </tr>   
            <tr>
                <td colspan="18" class="titulo_tablas">2. INFORMACIÓN GENERAL DE LA ENTIDAD CALIFICADORA</td>
            </tr>
            <tr>
                <td colspan="2" class="titulo_labels">Nombre:</td>
                <td colspan="6" class="dato_dinamico">{{$Nombre_cliente_ent}}</td>
                <td colspan="1" class="titulo_labels">Nit</td>
                <td colspan="3" class="dato_dinamico">{{$Nit_ent}}</td>
                <td colspan="3" class="titulo_labels">Teléfono:</td>
                <td colspan="3" class="dato_dinamico">{{$Telefono_principal_ent}}</td>
            </tr>
            <tr>
                <td colspan="2" class="titulo_labels">Dirección:</td>
                <td colspan="6" class="dato_dinamico">{{$Direccion_ent}}</td>
                <td colspan="2" class="titulo_labels">E-mail:</td>
                <td colspan="8" class="dato_dinamico">{{$Email_principal_ent}}</td>
            </tr>
            <tr>
                <td colspan="18" class="titulo_tablas">3. DATOS GENERALES DE LA PERSONA CALIFICADA</td>
            </tr>
            <tr>
                <td colspan="2" class="titulo_labels">Afiliado:</td>
                <td colspan="7" class="centrar_dato_dinamico">{{$Afiliado_per_cal}}</td>
                <td colspan="2" class="titulo_labels">Beneficiario:</td>
                <td colspan="7" class="centrar_dato_dinamico">{{$Beneficiario_per_cal}}</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Nombres:</td>
                <td colspan="14" class="dato_dinamico">{{$ResultadoNombre_per_cal}}</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Documento de identificación:</td>
                <td colspan="5" class="dato_dinamico">{{$Tipo_documento_per_cal}}</td>
                <td colspan="4" class="titulo_labels">N° de identificación:</td>
                <td colspan="5" class="dato_dinamico">{{$NroIden_per_cal}}</td>
            </tr>
            <tr>                
                <td colspan="4" class="titulo_labels">Fecha nacimiento:</td>
                <td colspan="5" class="dato_dinamico">{{$F_nacimiento_per_calF}}</td>
                <td colspan="4" class="titulo_labels">Edad:</td>
                <td colspan="5" class="dato_dinamico">{{$Edad_per_cal}} años</td>
            </tr>
            <tr>
                <td colspan="2" class="titulo_labels">Escolaridad:</td>
                <td colspan="5" class="dato_dinamico">{{$Nivel_escolar_per_cal}}</td>
                <td colspan="2" class="titulo_labels">Estado civil:</td>
                <td colspan="4" class="dato_dinamico">{{$Estado_civil_per_cal}}</td>
                <td colspan="2" class="titulo_labels">Teléfono:</td>
                <td colspan="3" class="dato_dinamico">{{$Telefono_per_cal}}</td>
            </tr>
            <tr>
                <td colspan="2" class="titulo_labels">Dirección:</td>
                <td colspan="10" class="dato_dinamico">{{$Direccion_per_cal}}</td>
                <td colspan="2" class="titulo_labels">Ciudad:</td>
                <td colspan="4" class="dato_dinamico">{{$Ciudad_per_cal}}</td>
            </tr>
            <tr>
                <td colspan="2" class="titulo_labels">E-mail:</td>
                <td colspan="16" class="dato_dinamico">{{$Email_per_cal}}</td>
            </tr>
            <tr>
                <td colspan="18" class="dato_dinamico">En caso de calificar un beneficiario, anotar los datos del afiliado:</td>
            </tr>
            <tr>
                <td colspan="3" class="titulo_labels">Nombre y apellidos</td>
                <td colspan="6" class="dato_dinamico">{{$Nombre_ben}}</td>
                <td colspan="4" class="titulo_labels">Documento de identidad</td>
                <td colspan="5" class="dato_dinamico">{{$Documento_iden_ben}}</td>
            </tr>
            <tr>                
                <td colspan="3" class="titulo_labels">Teléfono</td>
                <td colspan="6" class="dato_dinamico">{{$Telefono_iden_ben}}</td>
                <td colspan="4" class="titulo_labels">Ciudad</td>
                <td colspan="5" class="dato_dinamico">{{$Ciudad_iden_ben}}</td>
            </tr> 
            <tr>
                <td colspan="18" class="titulo_tablas">ETAPAS DEL CICLO VITAL:</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Población en edad económicamente activa:</td>
                <td colspan="1" class="centrar_dato_dinamico">{{$Poblacion_edad_econo_activa}}</td>
                <td colspan="3" class="titulo_labels">Bebés y menores de 3 años:</td>
                <td colspan="1" class="centrar_dato_dinamico">{{$Bebe_menor3}}</td>
                <td colspan="4" class="titulo_labels">Niños y adolescentes:</td>
                <td colspan="1" class="centrar_dato_dinamico">{{$Ninos_adolecentes}}</td>
                <td colspan="3" class="titulo_labels">Adultos Mayores:</td>
                <td colspan="1" class="centrar_dato_dinamico">{{$Adultos_mayores}}</td>
            </tr>
            <tr>
                <td colspan="18" class="dato_dinamico">En caso de calificar un menor de edad, anotar los datos del acudiente o adulto responsable:</td>
            </tr>
            <tr>
                <td colspan="3" class="titulo_labels">Nombre y apellidos</td>
                <td colspan="6" class="dato_dinamico">{{$Nombre_acudiente}}</td>
                <td colspan="4" class="titulo_labels">Documento de identidad</td>
                <td colspan="5" class="dato_dinamico">{{$Documento_acudiente}}</td>
            </tr>
            <tr>
                <td colspan="3" class="titulo_labels">Teléfono</td>
                <td colspan="6" class="dato_dinamico">{{$Telefono_acudiente}}</td>
                <td colspan="4" class="titulo_labels">Ciudad</td>
                <td colspan="5" class="dato_dinamico">{{$Ciudad_acudiente}}</td>
            </tr>  
            <tr>
                <td colspan="18" class="titulo_tablas">AFILIACIÓN AL  SISS:</td>
            </tr>
            <tr>
                <td colspan="3" class="titulo_labels">Régimen en salud</td>
                <td colspan="4" class="dato_dinamico">Contributivo:</td>
                <td colspan="1" class="centrar_dato_dinamico">{{$Contributivo_ecv}}</td>
                <td colspan="4" class="dato_dinamico">Subsidiado:</td>
                <td colspan="1" class="centrar_dato_dinamico">{{$Subsidiado_ecv}}</td>
                <td colspan="4" class="dato_dinamico">No afiliado:</td>
                <td colspan="1" class="centrar_dato_dinamico">{{$No_afiliado_ecv}}</td>
            </tr>
            <tr>
                <td colspan="3" rowspan="2" class="titulo_labels">Administradoras:</td>
                <td colspan="4" class="centrar_dato_labels">EPS:</td>
                <td colspan="3" class="centrar_dato_labels">AFP:</td>
                <td colspan="4" class="centrar_dato_labels">ARL:</td>
                <td colspan="4" class="centrar_dato_labels">Otros:</td>
            </tr>
            <tr>
                <td colspan="4" class="dato_dinamico">{{$Entidad_eps}}</td>
                <td colspan="3" class="dato_dinamico">{{$Entidad_afp}}</td>
                <td colspan="4" class="dato_dinamico">{{$Entidad_arl}}</td>
                <td colspan="4" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="18" class="titulo_tablas">4. ANTECEDENTES LABORALES DEL CALIFICADO</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Independiente:</td>
                <td colspan="6" class="centrar_dato_dinamico">{{$Independiente_laboral}}</td>
                <td colspan="2" class="titulo_labels">Dependiente:</td>
                <td colspan="6" class="centrar_dato_dinamico">{{$Dedependiente_laboral}}</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Nombre del cargo:</td>
                <td colspan="14" class="dato_dinamico">{{$Nombre_cargo_laboral}}</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Ocupación:</td>
                <td colspan="6" class="dato_dinamico">{{$Ocupacion_afiliado}}</td>
                <td colspan="2" class="titulo_labels">Código CIUO:</td>
                <td colspan="6" class="dato_dinamico">{{$Codigo_ciuo_laboral}}</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Funciones del cargo:</td>
                <td colspan="14" class="dato_dinamico">{{$Funciones_cargo_laboral}}</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Nombre de la empresa:</td>
                <td colspan="10" class="dato_dinamico">{{$Empresa_laboral}}</td>
                <td colspan="1" class="titulo_labels">Nit:</td>
                <td colspan="3" class="dato_dinamico">{{$Nit_laboral}}</td>
            </tr>   
            <tr>
                <td colspan="18" class="titulo_tablas">5. RELACIÓN DE DOCUMENTOS/EXAMEN FÍSICO(Descripción)</td>
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
                <td colspan="18" class="titulo_tablas">6. FUNDAMENTOS PARA LA CALIFICACIÓN DE LA PERDIDA DE LA CAPACIDAD LABORAL Y OCUPACIONAL - TITULOS I Y II</td>
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
                <td colspan="18" class="titulo_tablas">TÍTULO I CLASIFICACIÓN / VALORACIÓN DE LAS DEFICIENCIAS</td>
            </tr>
            <tr>
                <td colspan="1" rowspan="1" class="centrar_titulo_labels">No.</td>
                <td colspan="1" rowspan="1" class="centrar_titulo_labels">CIE 10</td>
                <td colspan="6" rowspan="1" class="centrar_titulo_labels">Diagnóstico</td>
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
                        <td colspan="6" class="dato_dinamico">{{ $deficiencias_fc->Nombre_CIE10 }}</td>
                        <td colspan="2" class="dato_dinamico">{{ $deficiencias_fc->Nombre_lateralidad }}</td>
                        <td colspan="6" class="dato_dinamico">{{ $deficiencias_fc->Deficiencia_motivo_califi_condiciones }}</td>                                                                    
                        <td colspan="2" class="dato_dinamico">{{($deficiencias_fc->Nombre_origen)}}</td>
                    </tr>
                @endforeach             
            @else
                <tr>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="6" class="dato_dinamico"></td>
                    <td colspan="2" class="dato_dinamico"></td>
                    <td colspan="6" class="dato_dinamico"></td>
                    <td colspan="2" class="dato_dinamico"></td>
                </tr>         
            @endif         
            <tr>
                <td colspan="18"></td>
            </tr>     
            <tr>
                <td colspan="18" class="titulo_tablas">Deficiencias por Alteraciones de los Sistemas Generales cálculadas por factores</td>
            </tr>            
            <tr>
                <td colspan="1" rowspan="1" class="centrar_titulo_labels">No.</td>
                <td colspan="4" rowspan="1" class="centrar_titulo_labels">Nombre de deficiencia</td>
                <td colspan="1" rowspan="1" class="centrar_titulo_labels">No. Tabla</td>
                <td colspan="1" rowspan="1" class="centrar_titulo_labels">Clase / FP</td>
                <td colspan="1" rowspan="1" class="centrar_titulo_labels">FU</td>
                <td colspan="1" rowspan="1" class="centrar_titulo_labels">CFM1</td>
                <td colspan="1" rowspan="1" class="centrar_titulo_labels">CFM2</td>
                <td colspan="1" rowspan="1" class="centrar_titulo_labels">CFM3</td>
                <td colspan="1" class="centrar_titulo_labels">Clase final y Literal</td>
                <td colspan="1" rowspan="1" class="centrar_titulo_labels">MSD</td>
                <td colspan="1" class="centrar_titulo_labels">Defi.</td>
                <td colspan="1" rowspan="1" class="centrar_titulo_labels">CAT</td>
                <td colspan="1" rowspan="1" class="centrar_titulo_labels">Domi.</td>
                <td colspan="2" rowspan="1" class="centrar_titulo_labels">% Total Deficiencia ( F . Balthazar, sin ponderar )</td>
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
                        <td colspan="4" class="dato_dinamico">{{wordwrap($deficiencias_fc->Nombre_tabla, 35, "\n", true);}}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_fc->Ident_tabla }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_fc->FP }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_fc->FU }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_fc->CFM1 }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_fc->CFM2 }}</td>
                        <td colspan="1" class="centrar_dato_dinamico"></td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_fc->Clase_Final }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_fc->MSD }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_fc->Deficiencia }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_fc->CAT }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_fc->Dominancia }}</td>       
                        <td colspan="2" class="centrar_dato_dinamico">{{ $deficiencias_fc->Total_deficiencia }}</td>                        
                    </tr>
                @endforeach  
            @else
                <tr>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="4" class="dato_dinamico"></td>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="2" class="centrar_dato_dinamico"></td>
                </tr>
         
            @endif
            @if (count($array_deficiencia_auditiva) > 0)
                <tr>
                    <td colspan="18" class="titulo_tablas">Deficiencia por Alteraciones del Sistema Auditivo</td>
                </tr>      
                <tr>
                    <td colspan="3" class="centrar_titulo_labels">Nombre de deficiencia</td>
                    <td colspan="1" class="centrar_titulo_labels">No. Tabla</td>
                    <td colspan="3" class="centrar_titulo_labels">Deficiencia Monoaural Izquierda</td>
                    <td colspan="3" class="centrar_titulo_labels">Deficiencia Monoaural Derecha</td>
                    <td colspan="3" class="centrar_titulo_labels">Deficiencia Binaural</td>
                    <td colspan="2" class="centrar_titulo_labels">Adicion por Tinnitus</td>
                    <td colspan="3" class="centrar_titulo_labels">Deficiencia</td>
                </tr>            
                <?php $cumple_condicion++; ?>
                <?php $rowspan_total += count($array_deficiencia_auditiva); ?>
                @foreach ($array_deficiencia_auditiva as $index => $deficiencias_auditiva)
                    <?php $conteo_deficiencias = $conteo_deficiencias + 1; ?>
                    <tr>
                        <td colspan="3" class="dato_dinamico">Agudeza auditiva</td>
                        <td colspan="1" class="centrar_dato_dinamico">Tabla 9.3</td>
                        <td colspan="3" class="centrar_dato_dinamico">{{ $deficiencias_auditiva->Deficiencia_monoaural_izquierda }}</td>
                        <td colspan="3" class="centrar_dato_dinamico">{{ $deficiencias_auditiva->Deficiencia_monoaural_derecha }}</td>
                        <td colspan="3" class="centrar_dato_dinamico">{{ $deficiencias_auditiva->Deficiencia_binaural }}</td>
                        <td colspan="2" class="centrar_dato_dinamico">{{ $deficiencias_auditiva->Adicion_tinnitus }}</td>
                        <td colspan="3" class="centrar_dato_dinamico">{{ $deficiencias_auditiva->Deficiencia }}</td>
                    </tr>                                        
                @endforeach               
            @endif  
            @if (count($array_deficiencia_visual) > 0)
                <tr>
                    <td colspan="18" class="titulo_tablas">Deficiencias por Alteraciones del Sistema Visual</td>
                </tr> 
                <tr>
                    <td colspan="3" class="centrar_titulo_labels">Nombre de deficiencia</td>
                    <td colspan="1" class="centrar_titulo_labels">No. Tabla</td>
                    <td colspan="1" class="centrar_titulo_labels">AOI</td>
                    <td colspan="1" class="centrar_titulo_labels">AOD</td>
                    <td colspan="1" class="centrar_titulo_labels">AAO</td>
                    <td colspan="1" class="centrar_titulo_labels">PAVF</td>
                    <td colspan="1" class="centrar_titulo_labels">DAV</td>
                    <td colspan="1" class="centrar_titulo_labels">CVOI</td>
                    <td colspan="1" class="centrar_titulo_labels">CVOD</td>
                    <td colspan="1" class="centrar_titulo_labels">CVAO</td>
                    <td colspan="1" class="centrar_titulo_labels">CVF</td>
                    <td colspan="1" class="centrar_titulo_labels">DCV</td>
                    <td colspan="1" class="centrar_titulo_labels">DSV</td>
                    <td colspan="3" class="centrar_titulo_labels">Deficiencia</td>
                </tr>
            
                <?php $cumple_condicion++; ?>
                <?php $rowspan_total += count($array_deficiencia_visual); ?>
                @foreach ($array_deficiencia_visual as $index => $deficiencias_visual)
                    <?php $conteo_deficiencias = $conteo_deficiencias + 1; ?>
                    <tr>
                        <td colspan="3" class="dato_dinamico">Agudeza visual</td>
                        <td colspan="1" class="centrar_dato_dinamico">Tabla 11.3</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_visual->Agudeza_Ojo_Izq }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_visual->Agudeza_Ojo_Der }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_visual->Agudeza_Ambos_Ojos }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_visual->PAVF }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_visual->DAV }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_visual->Campo_Visual_Ojo_Izq }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_visual->Campo_Visual_Ojo_Der }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_visual->Campo_Visual_Ambos_Ojos }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_visual->CVF }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_visual->DCV }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_visual->DSV }}</td>
                        <td colspan="3" class="centrar_dato_dinamico">{{ $deficiencias_visual->Deficiencia }}</td>
                    </tr>                   
                @endforeach  
            @elseif (count($array_deficiencia_visualre) > 0)
                <tr>
                    <td colspan="18" class="titulo_tablas">Deficiencias por Alteraciones del Sistema Visual</td>
                </tr> 
                <tr>
                    <td colspan="3" class="centrar_titulo_labels">Nombre de deficiencia</td>
                    <td colspan="1" class="centrar_titulo_labels">No. Tabla</td>
                    <td colspan="1" class="centrar_titulo_labels">AOI</td>
                    <td colspan="1" class="centrar_titulo_labels">AOD</td>
                    <td colspan="1" class="centrar_titulo_labels">AAO</td>
                    <td colspan="1" class="centrar_titulo_labels">PAVF</td>
                    <td colspan="1" class="centrar_titulo_labels">DAV</td>
                    <td colspan="1" class="centrar_titulo_labels">CVOI</td>
                    <td colspan="1" class="centrar_titulo_labels">CVOD</td>
                    <td colspan="1" class="centrar_titulo_labels">CVAO</td>
                    <td colspan="1" class="centrar_titulo_labels">CVF</td>
                    <td colspan="1" class="centrar_titulo_labels">DCV</td>
                    <td colspan="1" class="centrar_titulo_labels">DSV</td>
                    <td colspan="3" class="centrar_titulo_labels">Deficiencia</td>
                </tr>
                
                <?php $cumple_condicion++; ?>
                <?php $rowspan_total += count($array_deficiencia_visualre); ?>
                @foreach ($array_deficiencia_visualre as $index => $deficiencias_visual)
                    <?php $conteo_deficiencias = $conteo_deficiencias + 1; ?>
                    <tr>
                        <td colspan="3" class="dato_dinamico">Agudeza visual</td>
                        <td colspan="1" class="centrar_dato_dinamico">Tabla 11.3</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_visual->Agudeza_Ojo_Izq_re }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_visual->Agudeza_Ojo_Der_re }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_visual->Agudeza_Ambos_Ojos_re }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_visual->PAVF_re }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_visual->DAV_re }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_visual->Campo_Visual_Ojo_Izq_re }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_visual->Campo_Visual_Ojo_Der_re }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_visual->Campo_Visual_Ambos_Ojos_re }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_visual->CVF_re }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_visual->DCV_re }}</td>
                        <td colspan="1" class="centrar_dato_dinamico">{{ $deficiencias_visual->DSV_re }}</td>
                        <td colspan="3" class="centrar_dato_dinamico">{{ $deficiencias_visual->Deficiencia_re }}</td>
                    </tr>                   
                @endforeach             
            @endif                                           
            <tr>
                <td colspan="6" class="explicacionFB sinborder"><b>CFP:</b> Clase Factor principal</td>
                <td colspan="6" class="explicacionFB sinborder"><b>CFM:</b> Clase Factor Modulador</td>
                <td colspan="6" class="explicacionFB sinborder"><b>CFU:</b> Clase Factor único</td>
            </tr>
            <tr>
                <td colspan="18" class="explicacionFB sinborder"><b>Formula Ajuste Total de Deficiencias por tabla:</b> (CFM1-CFP) + (CFM2-CFP) + (CFM3-CFP)</td>
            </tr>
            <tr>
                <td colspan="8" rowspan="1" class="explicacionFB sinborder"><b>Fórmula de Balthazar:</b> Obtiene el valor final de las deficiencias sin ponderar</td>
                <td colspan="5" class="explicacionFB sinborder"><b>A +  (100 -  A) * B</b><br><hr style="border: 0.1px solid black;"><b>100</b></td>
                <td colspan="5" class="explicacionFB sinborder">A: Deficiencia de mayor valor<br>B: Deficiencia de menor valor</td>
            </tr>
            <tr>
                <td colspan="7" class="right_dato_dinamico"><b>% Total Deficiencia (F. Balthazar, sin ponderar)</b></td>
                <td colspan="1" class="centrar_dato_dinamico">{{$Suma_combinada_fc}}</td>
                <td colspan="9" class="right_dato_dinamico"><b>CÁLCULO FINAL DE LA DEFICIENCIA PONDERADA (% Deficiencia sin ponderar X 0,5)</b>=</td>
                <td colspan="1" class="centrar_dato_dinamico">{{$Total_deficiencia50_fc}}</td>
            </tr>
            <tr>
                <td colspan="18"></td>
            </tr>
            @if (count($array_datos_laboralmente_activo) > 0)                
                <tr>
                    <td colspan="18" class="titulo_tablas">TITULO II VALORACIÓN DEL ROL LABORAL, ROL OCUPACIONAL Y OTRAS ÁREAS OCUPACIONALES</td>
                </tr>
                <tr>
                    <td colspan="18" class="dato_dinamico">Personas en edad económicamente activa (incluye menores trabajadores, jubilados, pensionados, adultos mayores que trabajan )</td>
                </tr>
                <tr>
                    <td colspan="18" class="titulo_tablas">ROL LABORAL</td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1" class="centrar_dato_labels">1</td>
                    <td colspan="11" rowspan="1" class="centrar_dato_labels">Restricciones del rol laboral</td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>0,0</b><br>
                        <?php if(!empty($array_datos_laboralmente_activo[0]->Restricciones_rol) && $array_datos_laboralmente_activo[0]->Restricciones_rol == 0.0) {echo 'X';} else{echo '--';}?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>5,0</b><br>
                        <?php if(!empty($array_datos_laboralmente_activo[0]->Restricciones_rol) && $array_datos_laboralmente_activo[0]->Restricciones_rol == 5.0) {echo 'X';} else{echo '--';}?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>10,0</b><br>
                        <?php if(!empty($array_datos_laboralmente_activo[0]->Restricciones_rol) && $array_datos_laboralmente_activo[0]->Restricciones_rol == 10) {echo 'X';} else{echo '--';}?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>15,0</b><br>
                        <?php if(!empty($array_datos_laboralmente_activo[0]->Restricciones_rol) && $array_datos_laboralmente_activo[0]->Restricciones_rol == 15) {echo 'X';} else{echo '--';}?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>20,0</b><br>
                        <?php if(!empty($array_datos_laboralmente_activo[0]->Restricciones_rol) && $array_datos_laboralmente_activo[0]->Restricciones_rol == 20) {echo 'X';} else{echo '--';}?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>25,0</b><br>
                        <?php if(!empty($array_datos_laboralmente_activo[0]->Restricciones_rol) && $array_datos_laboralmente_activo[0]->Restricciones_rol == 25) {echo 'X';} else{echo '--';}?>
                    </td>
                </tr>  
                <tr>
                    <td colspan="1" rowspan="1" class="centrar_dato_labels">2</td>
                    <td colspan="11" rowspan="1" class="centrar_dato_labels">Restricciones autosuficiencia económica</td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>0,0</b><br>
                        <?php if(!empty($array_datos_laboralmente_activo[0]->Autosuficiencia_economica) && $array_datos_laboralmente_activo[0]->Autosuficiencia_economica == 0.0) {echo 'X';} else{echo '--';}?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>1,0</b><br>
                        <?php if(!empty($array_datos_laboralmente_activo[0]->Autosuficiencia_economica) && $array_datos_laboralmente_activo[0]->Autosuficiencia_economica == 1.0) {echo 'X';} else{echo '--';}?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>1,5</b><br>
                        <?php if(!empty($array_datos_laboralmente_activo[0]->Autosuficiencia_economica) && $array_datos_laboralmente_activo[0]->Autosuficiencia_economica == 1.5) {echo 'X';} else{echo '--';}?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>2,0</b><br>
                        <?php if(!empty($array_datos_laboralmente_activo[0]->Autosuficiencia_economica) && $array_datos_laboralmente_activo[0]->Autosuficiencia_economica == 2.0) {echo 'X';} else{echo '--';}?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>2,5</b><br>
                        <?php if(!empty($array_datos_laboralmente_activo[0]->Autosuficiencia_economica) && $array_datos_laboralmente_activo[0]->Autosuficiencia_economica == 2.5) {echo 'X';} else{echo '--';}?>
                    </td>
                    <td colspan="1"></td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1" class="centrar_dato_labels">3</td>
                    <td colspan="11" rowspan="1" class="centrar_dato_labels">Restricciones en función de la edad cronológica</td>
                    <td colspan="1" class="centrar_dato_dinamico"><b>2,5</b><br>
                        <?php if(!empty($array_datos_laboralmente_activo[0]->Edad_cronologica_menor) && $array_datos_laboralmente_activo[0]->Edad_cronologica_menor == 2.5) {echo 'X';} else{echo '--';}?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>0,5</b><br>
                        <?php if(!empty($array_datos_laboralmente_activo[0]->Edad_cronologica) && $array_datos_laboralmente_activo[0]->Edad_cronologica == 0.5) {echo 'X';} else{echo '--';}?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>1,0</b><br>
                        <?php if(!empty($array_datos_laboralmente_activo[0]->Edad_cronologica) && $array_datos_laboralmente_activo[0]->Edad_cronologica == 1.0) {echo 'X';} else{echo '--';}?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>1,5</b><br>
                        <?php if(!empty($array_datos_laboralmente_activo[0]->Edad_cronologica) && $array_datos_laboralmente_activo[0]->Edad_cronologica == 1.5) {echo 'X';} else{echo '--';}?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>2,0</b><br>
                        <?php if(!empty($array_datos_laboralmente_activo[0]->Edad_cronologica) && $array_datos_laboralmente_activo[0]->Edad_cronologica == 2.0) {echo 'X';} else{echo '--';}?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>2,5</b><br>
                        <?php if(!empty($array_datos_laboralmente_activo[0]->Edad_cronologica) && $array_datos_laboralmente_activo[0]->Edad_cronologica == 2.5) {echo 'X';} else{echo '--';}?>
                    </td>
                </tr>
                <tr>
                    <td colspan="16" class="right_titulo_labels"><b>Sumatoria rol laboral, autosuficiencia económica y edad (30%)</b></td>
                    <td colspan="2" class="centrar_dato_dinamico"><?php if(!empty($array_datos_laboralmente_activo[0]->Total_rol_laboral)) {echo $array_datos_laboralmente_activo[0]->Total_rol_laboral;} else{echo '0.0';}?></td>
                </tr>                
                <tr>
                    <td colspan="18" class="titulo_tablas"><b>CALIFICACIÓN OTRAS ÁREAS OCUPACIONALES</b></td>
                </tr>
                <tr>
                    <td colspan="18" class="dato_dinamico">Valor según el grado de dificultad, ayuda y dependencia</td>
                </tr>
                <tr>
                    <td colspan="2" class="centrar_titulo_labels">CLASE</td>
                    <td colspan="2" class="centrar_titulo_labels">VALOR</td>
                    <td colspan="14" class="centrar_titulo_labels">CRITERIO CUALITATIVO</td>
                </tr>
                <tr>
                    <td colspan="2" class="centrar_dato_labels">A</td>
                    <td colspan="2" class="centrar_dato_labels">0,0</td>
                    <td colspan="14" class="centrar_dato_labels">No hay dificultad, No dependencia</td>
                </tr>
                <tr>
                    <td colspan="2" class="centrar_dato_labels">B</td>
                    <td colspan="2" class="centrar_dato_labels">0,1</td>
                    <td colspan="14" class="centrar_dato_labels">Dificultad leve, No dependencia</td>
                </tr>
                <tr>
                    <td colspan="2" class="centrar_dato_labels">C</td>
                    <td colspan="2" class="centrar_dato_labels">0,2</td>
                    <td colspan="14" class="centrar_dato_labels">Dificultad moderada, dependencia moderada</td>
                </tr>
                <tr>
                    <td colspan="2" class="centrar_dato_labels">D</td>
                    <td colspan="2" class="centrar_dato_labels">0,3</td>
                    <td colspan="14" class="centrar_dato_labels">Dificultad severa, dependencia severa</td>
                </tr>
                <tr>
                    <td colspan="2" class="centrar_dato_labels">E</td>
                    <td colspan="2" class="centrar_dato_labels">0,4</td>
                    <td colspan="14" class="centrar_dato_labels">Dificultad completa, dependencia completa</td>
                </tr>
                <tr>
                    <td colspan="18"></td>
                </tr>
                <tr>
                    <td colspan="1" class="label_area_ocupacional">COD</td>
                    <td colspan="15" class="label_area_ocupacional">OTRAS ÁREAS OCUPACIONALES</td>                    
                    <td colspan="2" rowspan="1" class="label_area_ocupacional">Total</td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1" class="centrar_dato_labels">d1</td>
                    <td colspan="1" rowspan="1" class="centrar_dato_labels">Tabla 6</td>
                    <td colspan="3" rowspan="1" class="centrar_dato_labels">Aprendizaje y aplicación del conocimiento</td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d110</b><br>
                        <b>1,1</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Aprendizaje_mirar) && $array_datos_laboralmente_activo[0]->Aprendizaje_mirar == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_mirar;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_mirar) && $array_datos_laboralmente_activo[0]->Aprendizaje_mirar == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_mirar;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_mirar) && $array_datos_laboralmente_activo[0]->Aprendizaje_mirar == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_mirar;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_mirar) && $array_datos_laboralmente_activo[0]->Aprendizaje_mirar == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_mirar;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_mirar) && $array_datos_laboralmente_activo[0]->Aprendizaje_mirar == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_mirar;                            
                            }else{
                                echo '0.0';
                            }                        
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d115</b><br>
                        <b>1,2</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Aprendizaje_escuchar) && $array_datos_laboralmente_activo[0]->Aprendizaje_escuchar == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_escuchar;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_escuchar) && $array_datos_laboralmente_activo[0]->Aprendizaje_escuchar == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_escuchar;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_escuchar) && $array_datos_laboralmente_activo[0]->Aprendizaje_escuchar == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_escuchar;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_escuchar) && $array_datos_laboralmente_activo[0]->Aprendizaje_escuchar == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_escuchar;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_escuchar) && $array_datos_laboralmente_activo[0]->Aprendizaje_escuchar == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_escuchar;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="2" class="centrar_dato_dinamico">
                        <b>d140-145</b><br>
                        <b>1,3</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Aprendizaje_aprender) && $array_datos_laboralmente_activo[0]->Aprendizaje_aprender == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_aprender;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_aprender) && $array_datos_laboralmente_activo[0]->Aprendizaje_aprender == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_aprender;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_aprender) && $array_datos_laboralmente_activo[0]->Aprendizaje_aprender == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_aprender;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_aprender) && $array_datos_laboralmente_activo[0]->Aprendizaje_aprender == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_aprender;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_aprender) && $array_datos_laboralmente_activo[0]->Aprendizaje_aprender == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_aprender;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d150</b><br>
                        <b>1,4</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Aprendizaje_calcular) && $array_datos_laboralmente_activo[0]->Aprendizaje_calcular == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_calcular;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_calcular) && $array_datos_laboralmente_activo[0]->Aprendizaje_calcular == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_calcular;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_calcular) && $array_datos_laboralmente_activo[0]->Aprendizaje_calcular == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_calcular;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_calcular) && $array_datos_laboralmente_activo[0]->Aprendizaje_calcular == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_calcular;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_calcular) && $array_datos_laboralmente_activo[0]->Aprendizaje_calcular == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_calcular;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d163</b><br>
                        <b>1,5</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Aprendizaje_pensar) && $array_datos_laboralmente_activo[0]->Aprendizaje_pensar == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_pensar;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_pensar) && $array_datos_laboralmente_activo[0]->Aprendizaje_pensar == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_pensar;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_pensar) && $array_datos_laboralmente_activo[0]->Aprendizaje_pensar == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_pensar;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_pensar) && $array_datos_laboralmente_activo[0]->Aprendizaje_pensar == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_pensar;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_pensar) && $array_datos_laboralmente_activo[0]->Aprendizaje_pensar == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_pensar;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d166</b><br>
                        <b>1,6</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Aprendizaje_leer) && $array_datos_laboralmente_activo[0]->Aprendizaje_leer == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_leer;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_leer) && $array_datos_laboralmente_activo[0]->Aprendizaje_leer == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_leer;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_leer) && $array_datos_laboralmente_activo[0]->Aprendizaje_leer == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_leer;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_leer) && $array_datos_laboralmente_activo[0]->Aprendizaje_leer == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_leer;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_leer) && $array_datos_laboralmente_activo[0]->Aprendizaje_leer == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_leer;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d170</b><br>
                        <b>1,7</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Aprendizaje_escribir) && $array_datos_laboralmente_activo[0]->Aprendizaje_escribir == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_escribir;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_escribir) && $array_datos_laboralmente_activo[0]->Aprendizaje_escribir == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_escribir;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_escribir) && $array_datos_laboralmente_activo[0]->Aprendizaje_escribir == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_escribir;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_escribir) && $array_datos_laboralmente_activo[0]->Aprendizaje_escribir == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_escribir;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_escribir) && $array_datos_laboralmente_activo[0]->Aprendizaje_escribir == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_escribir;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d172</b><br>
                        <b>1,8</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Aprendizaje_matematicos) && $array_datos_laboralmente_activo[0]->Aprendizaje_matematicos == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_matematicos;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_matematicos) && $array_datos_laboralmente_activo[0]->Aprendizaje_matematicos == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_matematicos;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_matematicos) && $array_datos_laboralmente_activo[0]->Aprendizaje_matematicos == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_matematicos;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_matematicos) && $array_datos_laboralmente_activo[0]->Aprendizaje_matematicos == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_matematicos;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_matematicos) && $array_datos_laboralmente_activo[0]->Aprendizaje_matematicos == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_matematicos;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d175</b><br>
                        <b>1,9</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Aprendizaje_resolver) && $array_datos_laboralmente_activo[0]->Aprendizaje_resolver == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_resolver;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_resolver) && $array_datos_laboralmente_activo[0]->Aprendizaje_resolver == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_resolver;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_resolver) && $array_datos_laboralmente_activo[0]->Aprendizaje_resolver == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_resolver;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_resolver) && $array_datos_laboralmente_activo[0]->Aprendizaje_resolver == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_resolver;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_resolver) && $array_datos_laboralmente_activo[0]->Aprendizaje_resolver == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_resolver;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d1751</b><br>
                        <b>1,10</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Aprendizaje_tareas) && $array_datos_laboralmente_activo[0]->Aprendizaje_tareas == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_tareas;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_tareas) && $array_datos_laboralmente_activo[0]->Aprendizaje_tareas == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_tareas;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_tareas) && $array_datos_laboralmente_activo[0]->Aprendizaje_tareas == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_tareas;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_tareas) && $array_datos_laboralmente_activo[0]->Aprendizaje_tareas == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_tareas;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Aprendizaje_tareas) && $array_datos_laboralmente_activo[0]->Aprendizaje_tareas == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_tareas;                            
                            }else{
                                echo '0.0';
                            }                        
                        ?>
                    </td>
                    <td colspan="2" class="centrar_dato_dinamico">
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Aprendizaje_total)){
                                echo $array_datos_laboralmente_activo[0]->Aprendizaje_total;
                            }else{
                                echo '0.0';
                            }                       
                        ?>                        
                    </td>                    
                </tr>
                <tr>
                    <td colspan="1" rowspan="1" class="centrar_dato_labels">d3</td>
                    <td colspan="1" rowspan="1" class="centrar_dato_labels">Tabla 7</td>
                    <td colspan="3" rowspan="1" class="centrar_dato_labels">Comunicación</td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d310</b><br>
                        <b>2,1</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Comunicacion_verbales) && $array_datos_laboralmente_activo[0]->Comunicacion_verbales == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_verbales;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_verbales) && $array_datos_laboralmente_activo[0]->Comunicacion_verbales == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_verbales;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_verbales) && $array_datos_laboralmente_activo[0]->Comunicacion_verbales == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_verbales;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_verbales) && $array_datos_laboralmente_activo[0]->Comunicacion_verbales == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_verbales;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_verbales) && $array_datos_laboralmente_activo[0]->Comunicacion_verbales == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_verbales;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d315</b><br>
                        <b>2,2</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Comunicacion_noverbales) && $array_datos_laboralmente_activo[0]->Comunicacion_noverbales == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_noverbales;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_noverbales) && $array_datos_laboralmente_activo[0]->Comunicacion_noverbales == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_noverbales;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_noverbales) && $array_datos_laboralmente_activo[0]->Comunicacion_noverbales == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_noverbales;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_noverbales) && $array_datos_laboralmente_activo[0]->Comunicacion_noverbales == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_noverbales;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_noverbales) && $array_datos_laboralmente_activo[0]->Comunicacion_noverbales == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_noverbales;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="2" class="centrar_dato_dinamico">
                        <b>d320</b><br>
                        <b>2,3</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Comunicacion_formal) && $array_datos_laboralmente_activo[0]->Comunicacion_formal == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_formal;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_formal) && $array_datos_laboralmente_activo[0]->Comunicacion_formal == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_formal;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_formal) && $array_datos_laboralmente_activo[0]->Comunicacion_formal == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_formal;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_formal) && $array_datos_laboralmente_activo[0]->Comunicacion_formal == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_formal;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_formal) && $array_datos_laboralmente_activo[0]->Comunicacion_formal == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_formal;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d325</b><br>
                        <b>2,4</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Comunicacion_escritos) && $array_datos_laboralmente_activo[0]->Comunicacion_escritos == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_escritos;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_escritos) && $array_datos_laboralmente_activo[0]->Comunicacion_escritos == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_escritos;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_escritos) && $array_datos_laboralmente_activo[0]->Comunicacion_escritos == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_escritos;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_escritos) && $array_datos_laboralmente_activo[0]->Comunicacion_escritos == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_escritos;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_escritos) && $array_datos_laboralmente_activo[0]->Comunicacion_escritos == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_escritos;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d330</b><br>
                        <b>2,5</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Comunicacion_habla) && $array_datos_laboralmente_activo[0]->Comunicacion_habla == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_habla;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_habla) && $array_datos_laboralmente_activo[0]->Comunicacion_habla == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_habla;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_habla) && $array_datos_laboralmente_activo[0]->Comunicacion_habla == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_habla;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_habla) && $array_datos_laboralmente_activo[0]->Comunicacion_habla == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_habla;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_habla) && $array_datos_laboralmente_activo[0]->Comunicacion_habla == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_habla;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d335</b><br>
                        <b>2,6</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Comunicacion_produccion) && $array_datos_laboralmente_activo[0]->Comunicacion_produccion == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_produccion;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_produccion) && $array_datos_laboralmente_activo[0]->Comunicacion_produccion == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_produccion;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_produccion) && $array_datos_laboralmente_activo[0]->Comunicacion_produccion == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_produccion;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_produccion) && $array_datos_laboralmente_activo[0]->Comunicacion_produccion == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_produccion;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_produccion) && $array_datos_laboralmente_activo[0]->Comunicacion_produccion == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_produccion;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d345</b><br>
                        <b>2,7</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Comunicacion_mensajes) && $array_datos_laboralmente_activo[0]->Comunicacion_mensajes == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_mensajes;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_mensajes) && $array_datos_laboralmente_activo[0]->Comunicacion_mensajes == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_mensajes;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_mensajes) && $array_datos_laboralmente_activo[0]->Comunicacion_mensajes == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_mensajes;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_mensajes) && $array_datos_laboralmente_activo[0]->Comunicacion_mensajes == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_mensajes;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_mensajes) && $array_datos_laboralmente_activo[0]->Comunicacion_mensajes == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_mensajes;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d350</b><br>
                        <b>2,8</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Comunicacion_conversacion) && $array_datos_laboralmente_activo[0]->Comunicacion_conversacion == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_conversacion;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_conversacion) && $array_datos_laboralmente_activo[0]->Comunicacion_conversacion == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_conversacion;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_conversacion) && $array_datos_laboralmente_activo[0]->Comunicacion_conversacion == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_conversacion;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_conversacion) && $array_datos_laboralmente_activo[0]->Comunicacion_conversacion == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_conversacion;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_conversacion) && $array_datos_laboralmente_activo[0]->Comunicacion_conversacion == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_conversacion;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d355</b><br>
                        <b>2,9</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Comunicacion_discusiones) && $array_datos_laboralmente_activo[0]->Comunicacion_discusiones == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_discusiones;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_discusiones) && $array_datos_laboralmente_activo[0]->Comunicacion_discusiones == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_discusiones;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_discusiones) && $array_datos_laboralmente_activo[0]->Comunicacion_discusiones == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_discusiones;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_discusiones) && $array_datos_laboralmente_activo[0]->Comunicacion_discusiones == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_discusiones;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_discusiones) && $array_datos_laboralmente_activo[0]->Comunicacion_discusiones == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_discusiones;                            
                            }else{
                                echo '0.0';
                            }                        
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d360</b><br>
                        <b>2,10</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Comunicacion_dispositivos) && $array_datos_laboralmente_activo[0]->Comunicacion_dispositivos == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_dispositivos;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_dispositivos) && $array_datos_laboralmente_activo[0]->Comunicacion_dispositivos == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_dispositivos;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_dispositivos) && $array_datos_laboralmente_activo[0]->Comunicacion_dispositivos == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_dispositivos;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_dispositivos) && $array_datos_laboralmente_activo[0]->Comunicacion_dispositivos == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_dispositivos;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Comunicacion_dispositivos) && $array_datos_laboralmente_activo[0]->Comunicacion_dispositivos == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_dispositivos;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="2" rowspan="1" class="centrar_dato_dinamico">
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Comunicacion_total)){
                                echo $array_datos_laboralmente_activo[0]->Comunicacion_total;
                            }else{
                                echo '0.0';
                            }                       
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1" class="centrar_dato_labels">d4</td>
                    <td colspan="1" rowspan="1" class="centrar_dato_labels">Tabla 8</td>
                    <td colspan="3" rowspan="1" class="centrar_dato_labels">Movilidad</td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d410</b><br>
                        <b>3,1</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Movilidad_cambiar_posturas) && $array_datos_laboralmente_activo[0]->Movilidad_cambiar_posturas == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_cambiar_posturas;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_cambiar_posturas) && $array_datos_laboralmente_activo[0]->Movilidad_cambiar_posturas == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_cambiar_posturas;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_cambiar_posturas) && $array_datos_laboralmente_activo[0]->Movilidad_cambiar_posturas == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_cambiar_posturas;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_cambiar_posturas) && $array_datos_laboralmente_activo[0]->Movilidad_cambiar_posturas == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_cambiar_posturas;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_cambiar_posturas) && $array_datos_laboralmente_activo[0]->Movilidad_cambiar_posturas == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_cambiar_posturas;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d415</b><br>
                        <b>3,2</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Movilidad_mantener_posicion) && $array_datos_laboralmente_activo[0]->Movilidad_mantener_posicion == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_mantener_posicion;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_mantener_posicion) && $array_datos_laboralmente_activo[0]->Movilidad_mantener_posicion == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_mantener_posicion;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_mantener_posicion) && $array_datos_laboralmente_activo[0]->Movilidad_mantener_posicion == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_mantener_posicion;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_mantener_posicion) && $array_datos_laboralmente_activo[0]->Movilidad_mantener_posicion == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_mantener_posicion;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_mantener_posicion) && $array_datos_laboralmente_activo[0]->Movilidad_mantener_posicion == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_mantener_posicion;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="2" class="centrar_dato_dinamico">
                        <b>d430</b><br>
                        <b>3,3</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Movilidad_objetos) && $array_datos_laboralmente_activo[0]->Movilidad_objetos == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_objetos;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_objetos) && $array_datos_laboralmente_activo[0]->Movilidad_objetos == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_objetos;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_objetos) && $array_datos_laboralmente_activo[0]->Movilidad_objetos == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_objetos;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_objetos) && $array_datos_laboralmente_activo[0]->Movilidad_objetos == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_objetos;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_objetos) && $array_datos_laboralmente_activo[0]->Movilidad_objetos == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_objetos;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d440</b><br>
                        <b>3,4</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Movilidad_uso_mano) && $array_datos_laboralmente_activo[0]->Movilidad_uso_mano == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_uso_mano;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_uso_mano) && $array_datos_laboralmente_activo[0]->Movilidad_uso_mano == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_uso_mano;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_uso_mano) && $array_datos_laboralmente_activo[0]->Movilidad_uso_mano == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_uso_mano;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_uso_mano) && $array_datos_laboralmente_activo[0]->Movilidad_uso_mano == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_uso_mano;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_uso_mano) && $array_datos_laboralmente_activo[0]->Movilidad_uso_mano == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_uso_mano;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d445</b><br>
                        <b>3,5</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Movilidad_mano_brazo) && $array_datos_laboralmente_activo[0]->Movilidad_mano_brazo == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_mano_brazo;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_mano_brazo) && $array_datos_laboralmente_activo[0]->Movilidad_mano_brazo == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_mano_brazo;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_mano_brazo) && $array_datos_laboralmente_activo[0]->Movilidad_mano_brazo == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_mano_brazo;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_mano_brazo) && $array_datos_laboralmente_activo[0]->Movilidad_mano_brazo == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_mano_brazo;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_mano_brazo) && $array_datos_laboralmente_activo[0]->Movilidad_mano_brazo == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_mano_brazo;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d455</b><br>
                        <b>3,6</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Movilidad_Andar) && $array_datos_laboralmente_activo[0]->Movilidad_Andar == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_Andar;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_Andar) && $array_datos_laboralmente_activo[0]->Movilidad_Andar == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_Andar;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_Andar) && $array_datos_laboralmente_activo[0]->Movilidad_Andar == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_Andar;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_Andar) && $array_datos_laboralmente_activo[0]->Movilidad_Andar == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_Andar;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_Andar) && $array_datos_laboralmente_activo[0]->Movilidad_Andar == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_Andar;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d460</b><br>
                        <b>3,7</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Movilidad_desplazarse) && $array_datos_laboralmente_activo[0]->Movilidad_desplazarse == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_desplazarse;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_desplazarse) && $array_datos_laboralmente_activo[0]->Movilidad_desplazarse == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_desplazarse;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_desplazarse) && $array_datos_laboralmente_activo[0]->Movilidad_desplazarse == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_desplazarse;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_desplazarse) && $array_datos_laboralmente_activo[0]->Movilidad_desplazarse == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_desplazarse;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_desplazarse) && $array_datos_laboralmente_activo[0]->Movilidad_desplazarse == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_desplazarse;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d465</b><br>
                        <b>3,8</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Movilidad_equipo) && $array_datos_laboralmente_activo[0]->Movilidad_equipo == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_equipo;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_equipo) && $array_datos_laboralmente_activo[0]->Movilidad_equipo == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_equipo;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_equipo) && $array_datos_laboralmente_activo[0]->Movilidad_equipo == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_equipo;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_equipo) && $array_datos_laboralmente_activo[0]->Movilidad_equipo == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_equipo;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_equipo) && $array_datos_laboralmente_activo[0]->Movilidad_equipo == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_equipo;                            
                            }else{
                                echo '0.0';
                            }                       
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d470</b><br>
                        <b>3,9</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Movilidad_transporte) && $array_datos_laboralmente_activo[0]->Movilidad_transporte == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_transporte;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_transporte) && $array_datos_laboralmente_activo[0]->Movilidad_transporte == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_transporte;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_transporte) && $array_datos_laboralmente_activo[0]->Movilidad_transporte == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_transporte;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_transporte) && $array_datos_laboralmente_activo[0]->Movilidad_transporte == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_transporte;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_transporte) && $array_datos_laboralmente_activo[0]->Movilidad_transporte == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_transporte;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d475</b><br>
                        <b>3,10</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Movilidad_conduccion) && $array_datos_laboralmente_activo[0]->Movilidad_conduccion == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_conduccion;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_conduccion) && $array_datos_laboralmente_activo[0]->Movilidad_conduccion == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_conduccion;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_conduccion) && $array_datos_laboralmente_activo[0]->Movilidad_conduccion == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_conduccion;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_conduccion) && $array_datos_laboralmente_activo[0]->Movilidad_conduccion == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_conduccion;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Movilidad_conduccion) && $array_datos_laboralmente_activo[0]->Movilidad_conduccion == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_conduccion;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="2" rowspan="1" class="centrar_dato_dinamico">
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Movilidad_total)){
                                echo $array_datos_laboralmente_activo[0]->Movilidad_total;
                            }else{
                                echo '0.0';
                            }                        
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1" class="centrar_dato_labels">d5</td>
                    <td colspan="1" rowspan="1" class="centrar_dato_labels">Tabla 9</td>
                    <td colspan="3" rowspan="1" class="centrar_dato_labels">Autocuidado - cuidado personal</td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d510</b><br>
                        <b>4,1</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Cuidado_lavarse) && $array_datos_laboralmente_activo[0]->Cuidado_lavarse == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_lavarse;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_lavarse) && $array_datos_laboralmente_activo[0]->Cuidado_lavarse == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_lavarse;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_lavarse) && $array_datos_laboralmente_activo[0]->Cuidado_lavarse == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_lavarse;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_lavarse) && $array_datos_laboralmente_activo[0]->Cuidado_lavarse == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_lavarse;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_lavarse) && $array_datos_laboralmente_activo[0]->Cuidado_lavarse == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_lavarse;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d520</b><br>
                        <b>4,2</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Cuidado_partes_cuerpo) && $array_datos_laboralmente_activo[0]->Cuidado_partes_cuerpo == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_partes_cuerpo;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_partes_cuerpo) && $array_datos_laboralmente_activo[0]->Cuidado_partes_cuerpo == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_partes_cuerpo;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_partes_cuerpo) && $array_datos_laboralmente_activo[0]->Cuidado_partes_cuerpo == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_partes_cuerpo;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_partes_cuerpo) && $array_datos_laboralmente_activo[0]->Cuidado_partes_cuerpo == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_partes_cuerpo;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_partes_cuerpo) && $array_datos_laboralmente_activo[0]->Cuidado_partes_cuerpo == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_partes_cuerpo;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="2" class="centrar_dato_dinamico">
                        <b>d530</b><br>
                        <b>4,3</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Cuidado_higiene) && $array_datos_laboralmente_activo[0]->Cuidado_higiene == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_higiene;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_higiene) && $array_datos_laboralmente_activo[0]->Cuidado_higiene == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_higiene;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_higiene) && $array_datos_laboralmente_activo[0]->Cuidado_higiene == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_higiene;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_higiene) && $array_datos_laboralmente_activo[0]->Cuidado_higiene == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_higiene;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_higiene) && $array_datos_laboralmente_activo[0]->Cuidado_higiene == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_higiene;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d540</b><br>
                        <b>4,4</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Cuidado_vestirse) && $array_datos_laboralmente_activo[0]->Cuidado_vestirse == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_vestirse;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_vestirse) && $array_datos_laboralmente_activo[0]->Cuidado_vestirse == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_vestirse;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_vestirse) && $array_datos_laboralmente_activo[0]->Cuidado_vestirse == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_vestirse;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_vestirse) && $array_datos_laboralmente_activo[0]->Cuidado_vestirse == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_vestirse;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_vestirse) && $array_datos_laboralmente_activo[0]->Cuidado_vestirse == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_vestirse;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d5401</b><br
                        ><b>4,5</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Cuidado_quitarse) && $array_datos_laboralmente_activo[0]->Cuidado_quitarse == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_quitarse;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_quitarse) && $array_datos_laboralmente_activo[0]->Cuidado_quitarse == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_quitarse;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_quitarse) && $array_datos_laboralmente_activo[0]->Cuidado_quitarse == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_quitarse;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_quitarse) && $array_datos_laboralmente_activo[0]->Cuidado_quitarse == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_quitarse;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_quitarse) && $array_datos_laboralmente_activo[0]->Cuidado_quitarse == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_quitarse;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d5402</b><br
                        ><b>4,6</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Cuidado_ponerse_calzado) && $array_datos_laboralmente_activo[0]->Cuidado_ponerse_calzado == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_ponerse_calzado;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_ponerse_calzado) && $array_datos_laboralmente_activo[0]->Cuidado_ponerse_calzado == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_ponerse_calzado;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_ponerse_calzado) && $array_datos_laboralmente_activo[0]->Cuidado_ponerse_calzado == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_ponerse_calzado;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_ponerse_calzado) && $array_datos_laboralmente_activo[0]->Cuidado_ponerse_calzado == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_ponerse_calzado;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_ponerse_calzado) && $array_datos_laboralmente_activo[0]->Cuidado_ponerse_calzado == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_ponerse_calzado;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d550</b><br>
                        <b>4,7</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Cuidado_comer) && $array_datos_laboralmente_activo[0]->Cuidado_comer == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_comer;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_comer) && $array_datos_laboralmente_activo[0]->Cuidado_comer == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_comer;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_comer) && $array_datos_laboralmente_activo[0]->Cuidado_comer == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_comer;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_comer) && $array_datos_laboralmente_activo[0]->Cuidado_comer == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_comer;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_comer) && $array_datos_laboralmente_activo[0]->Cuidado_comer == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_comer;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d560</b><br>
                        <b>4,8</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Cuidado_beber) && $array_datos_laboralmente_activo[0]->Cuidado_beber == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_beber;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_beber) && $array_datos_laboralmente_activo[0]->Cuidado_beber == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_beber;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_beber) && $array_datos_laboralmente_activo[0]->Cuidado_beber == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_beber;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_beber) && $array_datos_laboralmente_activo[0]->Cuidado_beber == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_beber;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_beber) && $array_datos_laboralmente_activo[0]->Cuidado_beber == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_beber;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d570</b><br>
                        <b>4,9</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Cuidado_salud) && $array_datos_laboralmente_activo[0]->Cuidado_salud == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_salud;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_salud) && $array_datos_laboralmente_activo[0]->Cuidado_salud == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_salud;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_salud) && $array_datos_laboralmente_activo[0]->Cuidado_salud == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_salud;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_salud) && $array_datos_laboralmente_activo[0]->Cuidado_salud == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_salud;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_salud) && $array_datos_laboralmente_activo[0]->Cuidado_salud == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_salud;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d5701</b><br
                        ><b>4,10</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Cuidado_dieta) && $array_datos_laboralmente_activo[0]->Cuidado_dieta == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_dieta;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_dieta) && $array_datos_laboralmente_activo[0]->Cuidado_dieta == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_dieta;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_dieta) && $array_datos_laboralmente_activo[0]->Cuidado_dieta == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_dieta;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_dieta) && $array_datos_laboralmente_activo[0]->Cuidado_dieta == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_dieta;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Cuidado_dieta) && $array_datos_laboralmente_activo[0]->Cuidado_dieta == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_dieta;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="2" rowspan="1" class="centrar_dato_dinamico">
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Cuidado_total)){
                                echo $array_datos_laboralmente_activo[0]->Cuidado_total;
                            }else{
                                echo '0.0';
                            }                       
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1" class="centrar_dato_labels">d6</td>
                    <td colspan="1" rowspan="1" class="centrar_dato_labels">Tabla 10</td>
                    <td colspan="3" rowspan="1" class="centrar_dato_labels">Vida doméstica</td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d610</b><br>
                        <b>5,1</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Domestica_vivir) && $array_datos_laboralmente_activo[0]->Domestica_vivir == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Domestica_vivir;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_vivir) && $array_datos_laboralmente_activo[0]->Domestica_vivir == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Domestica_vivir;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_vivir) && $array_datos_laboralmente_activo[0]->Domestica_vivir == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Domestica_vivir;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_vivir) && $array_datos_laboralmente_activo[0]->Domestica_vivir == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Domestica_vivir;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_vivir) && $array_datos_laboralmente_activo[0]->Domestica_vivir == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Domestica_vivir;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d620</b><br>
                        <b>5,2</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Domestica_bienes) && $array_datos_laboralmente_activo[0]->Domestica_bienes == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Domestica_bienes;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_bienes) && $array_datos_laboralmente_activo[0]->Domestica_bienes == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Domestica_bienes;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_bienes) && $array_datos_laboralmente_activo[0]->Domestica_bienes == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Domestica_bienes;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_bienes) && $array_datos_laboralmente_activo[0]->Domestica_bienes == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Domestica_bienes;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_bienes) && $array_datos_laboralmente_activo[0]->Domestica_bienes == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Domestica_bienes;                            
                            }else{
                                echo '0.0';
                            }                        
                        ?>
                    </td>
                    <td colspan="2" class="centrar_dato_dinamico">
                        <b>d6200</b><br>
                        <b>5,3</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Domestica_comprar) && $array_datos_laboralmente_activo[0]->Domestica_comprar == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Domestica_comprar;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_comprar) && $array_datos_laboralmente_activo[0]->Domestica_comprar == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Domestica_comprar;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_comprar) && $array_datos_laboralmente_activo[0]->Domestica_comprar == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Domestica_comprar;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_comprar) && $array_datos_laboralmente_activo[0]->Domestica_comprar == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Domestica_comprar;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_comprar) && $array_datos_laboralmente_activo[0]->Domestica_comprar == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Domestica_comprar;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d630</b><br>
                        <b>5,4</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Domestica_comidas) && $array_datos_laboralmente_activo[0]->Domestica_comidas == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Domestica_comidas;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_comidas) && $array_datos_laboralmente_activo[0]->Domestica_comidas == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Domestica_comidas;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_comidas) && $array_datos_laboralmente_activo[0]->Domestica_comidas == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Domestica_comidas;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_comidas) && $array_datos_laboralmente_activo[0]->Domestica_comidas == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Domestica_comidas;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_comidas) && $array_datos_laboralmente_activo[0]->Domestica_comidas == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Domestica_comidas;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d640</b><br>
                        <b>5,5</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Domestica_quehaceres) && $array_datos_laboralmente_activo[0]->Domestica_quehaceres == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Domestica_quehaceres;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_quehaceres) && $array_datos_laboralmente_activo[0]->Domestica_quehaceres == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Domestica_quehaceres;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_quehaceres) && $array_datos_laboralmente_activo[0]->Domestica_quehaceres == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Domestica_quehaceres;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_quehaceres) && $array_datos_laboralmente_activo[0]->Domestica_quehaceres == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Domestica_quehaceres;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_quehaceres) && $array_datos_laboralmente_activo[0]->Domestica_quehaceres == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Domestica_quehaceres;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d6402</b><br>
                        <b>5,6</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Domestica_limpieza) && $array_datos_laboralmente_activo[0]->Domestica_limpieza == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Domestica_limpieza;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_limpieza) && $array_datos_laboralmente_activo[0]->Domestica_limpieza == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Domestica_limpieza;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_limpieza) && $array_datos_laboralmente_activo[0]->Domestica_limpieza == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Domestica_limpieza;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_limpieza) && $array_datos_laboralmente_activo[0]->Domestica_limpieza == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Domestica_limpieza;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_limpieza) && $array_datos_laboralmente_activo[0]->Domestica_limpieza == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Domestica_limpieza;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d650</b><br>
                        <b>5,7</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Domestica_objetos) && $array_datos_laboralmente_activo[0]->Domestica_objetos == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Domestica_objetos;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_objetos) && $array_datos_laboralmente_activo[0]->Domestica_objetos == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Domestica_objetos;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_objetos) && $array_datos_laboralmente_activo[0]->Domestica_objetos == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Domestica_objetos;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_objetos) && $array_datos_laboralmente_activo[0]->Domestica_objetos == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Domestica_objetos;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_objetos) && $array_datos_laboralmente_activo[0]->Domestica_objetos == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Domestica_objetos;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d660</b><br>
                        <b>5,8</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Domestica_ayudar) && $array_datos_laboralmente_activo[0]->Domestica_ayudar == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Domestica_ayudar;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_ayudar) && $array_datos_laboralmente_activo[0]->Domestica_ayudar == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Domestica_ayudar;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_ayudar) && $array_datos_laboralmente_activo[0]->Domestica_ayudar == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Domestica_ayudar;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_ayudar) && $array_datos_laboralmente_activo[0]->Domestica_ayudar == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Domestica_ayudar;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_ayudar) && $array_datos_laboralmente_activo[0]->Domestica_ayudar == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Domestica_ayudar;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d6504</b><br>
                        <b>5,9</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Domestica_mantenimiento) && $array_datos_laboralmente_activo[0]->Domestica_mantenimiento == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Domestica_mantenimiento;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_mantenimiento) && $array_datos_laboralmente_activo[0]->Domestica_mantenimiento == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Domestica_mantenimiento;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_mantenimiento) && $array_datos_laboralmente_activo[0]->Domestica_mantenimiento == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Domestica_mantenimiento;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_mantenimiento) && $array_datos_laboralmente_activo[0]->Domestica_mantenimiento == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Domestica_mantenimiento;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_mantenimiento) && $array_datos_laboralmente_activo[0]->Domestica_mantenimiento == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Domestica_mantenimiento;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="1" class="centrar_dato_dinamico">
                        <b>d6506</b><br>
                        <b>5,10</b><br>
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Domestica_animales) && $array_datos_laboralmente_activo[0]->Domestica_animales == 0.0){
                                echo $array_datos_laboralmente_activo[0]->Domestica_animales;
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_animales) && $array_datos_laboralmente_activo[0]->Domestica_animales == 0.1){
                                echo $array_datos_laboralmente_activo[0]->Domestica_animales;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_animales) && $array_datos_laboralmente_activo[0]->Domestica_animales == 0.2){
                                echo $array_datos_laboralmente_activo[0]->Domestica_animales;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_animales) && $array_datos_laboralmente_activo[0]->Domestica_animales == 0.3){
                                echo $array_datos_laboralmente_activo[0]->Domestica_animales;                            
                            }elseif(!empty($array_datos_laboralmente_activo[0]->Domestica_animales) && $array_datos_laboralmente_activo[0]->Domestica_animales == 0.4){
                                echo $array_datos_laboralmente_activo[0]->Domestica_animales;                            
                            }else{
                                echo '0.0';
                            }                         
                        ?>
                    </td>
                    <td colspan="2" rowspan="1" class="centrar_dato_dinamico">
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Domestica_total)){
                                echo $array_datos_laboralmente_activo[0]->Domestica_total;
                            }else{
                                echo '0.0';
                            }                        
                        ?>
                    </td>
                </tr>   
                <tr>
                    <td colspan="16" class="right_titulo_labels"><b>Sumatoria total otras áreas ocupacionales (20%)</b></td>
                    <td colspan="2" class="centrar_dato_dinamico">
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Total_otras_areas)){
                                echo $array_datos_laboralmente_activo[0]->Total_otras_areas;
                            }else{
                                echo '0.0';
                            }                        
                        ?>
                    </td>
                </tr>  
                <tr>
                    <td colspan="18"></td>
                </tr>
                <tr>
                    <td colspan="3" class="left_titulo_labels"><b>CÁLCULO FINAL PCO</b></td>
                    <td colspan="13" class="right_titulo_labels"><b>Valor final de la segunda parte para las personas en edad económicamente activa</b></td>
                    <td colspan="2" class="centrar_dato_dinamico">
                        <?php
                            if (!empty($array_datos_laboralmente_activo[0]->Total_laboral_otras_areas)){
                                echo $array_datos_laboralmente_activo[0]->Total_laboral_otras_areas;
                            }else{
                                echo '0.0';
                            }                        
                        ?>
                    </td>
                </tr>
            @elseif(count($array_datos_rol_ocupacional) > 0)
                <tr>
                    <td colspan="18" class="titulo_tablas">TITULO II</td>
                </tr>
                @if (!empty($array_datos_rol_ocupacional[0]->Poblacion_calificar) && $array_datos_rol_ocupacional[0]->Poblacion_calificar == 75)
                    <tr>
                        <td colspan="18" class="sinborderinferior dato_dinamico">Asigne el valor según grado de dificultad</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="centrar_titulo_labels">CLASE</td>
                        <td colspan="2" class="centrar_titulo_labels">VALOR</td>
                        <td colspan="14" class="centrar_titulo_labels">CRITERIO CUALITATIVO</td>                        
                    </tr>
                    <tr>
                        <td colspan="2" class="centrar_dato_labels">A</td>
                        <td colspan="2" class="centrar_dato_labels">0,0</td>
                        <td colspan="14" class="centrar_dato_labels">No hay dificultad, no hay dependencia </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="centrar_dato_labels">B</td>
                        <td colspan="2" class="centrar_dato_labels">0,1</td>
                        <td colspan="14" class="centrar_dato_labels">Dificultad moderada, dependencia moderada </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="centrar_dato_labels">C</td>
                        <td colspan="2" class="centrar_dato_labels">0,2</td>
                        <td colspan="14" class="centrar_dato_labels">Dificultad completa, dependencia grave / completa </td>
                    </tr>                
                    <tr>
                        <td colspan="18" class="sinborder"></td>
                    </tr>
                    <tr>
                        <td colspan="18" class="titulo_tablas">TABLA 12 - Valoración para Niños y Niñas de 0 a 3 años</td>
                    </tr>
                    <tr>
                        <td colspan="18" rowspan="1" class="label_area_ocupacional">Actividad Motriz</td>                        
                    </tr>
                    <tr>
                        <td colspan="3" class="label_rol_laboral">Mantiene una postura simétrica o alineada</td>
                        <td colspan="2" class="label_rol_laboral">Tiene actividad espontánea</td>
                        <td colspan="3" class="label_rol_laboral">Sujeta la cabeza</td>
                        <td colspan="2" class="label_rol_laboral">Se sienta con apoyo</td>
                        <td colspan="3" class="label_rol_laboral">Gira sobre sí mismo</td>
                        <td colspan="3" class="label_rol_laboral">Se mantiene sentado sin apoyo</td>
                        <td colspan="2" class="label_rol_laboral">Pasa de tumbado a sentado</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Motriz_postura_simetrica) && $array_datos_rol_ocupacional[0]->Motriz_postura_simetrica == 0.0){
                                    echo $array_datos_rol_ocupacional[0]->Motriz_postura_simetrica;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_postura_simetrica) && $array_datos_rol_ocupacional[0]->Motriz_postura_simetrica == 1.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_postura_simetrica;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_postura_simetrica) && $array_datos_rol_ocupacional[0]->Motriz_postura_simetrica == 2.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_postura_simetrica;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                        <td colspan="2" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Motriz_actividad_espontanea) && $array_datos_rol_ocupacional[0]->Motriz_actividad_espontanea == 0.0){
                                    echo $array_datos_rol_ocupacional[0]->Motriz_actividad_espontanea;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_actividad_espontanea) && $array_datos_rol_ocupacional[0]->Motriz_actividad_espontanea == 1.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_actividad_espontanea;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_actividad_espontanea) && $array_datos_rol_ocupacional[0]->Motriz_actividad_espontanea == 2.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_actividad_espontanea;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                        <td colspan="3" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Motriz_sujeta_cabeza) && $array_datos_rol_ocupacional[0]->Motriz_sujeta_cabeza == 0.0){
                                    echo $array_datos_rol_ocupacional[0]->Motriz_sujeta_cabeza;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_sujeta_cabeza) && $array_datos_rol_ocupacional[0]->Motriz_sujeta_cabeza == 1.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_sujeta_cabeza;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_sujeta_cabeza) && $array_datos_rol_ocupacional[0]->Motriz_sujeta_cabeza == 2.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_sujeta_cabeza;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                        <td colspan="2" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Motriz_sentarse_apoyo) && $array_datos_rol_ocupacional[0]->Motriz_sentarse_apoyo == 0.0){
                                    echo $array_datos_rol_ocupacional[0]->Motriz_sentarse_apoyo;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_sentarse_apoyo) && $array_datos_rol_ocupacional[0]->Motriz_sentarse_apoyo == 1.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_sentarse_apoyo;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_sentarse_apoyo) && $array_datos_rol_ocupacional[0]->Motriz_sentarse_apoyo == 2.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_sentarse_apoyo;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                        <td colspan="3" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Motriz_gira_sobre_mismo) && $array_datos_rol_ocupacional[0]->Motriz_gira_sobre_mismo == 0.0){
                                    echo $array_datos_rol_ocupacional[0]->Motriz_gira_sobre_mismo;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_gira_sobre_mismo) && $array_datos_rol_ocupacional[0]->Motriz_gira_sobre_mismo == 1.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_gira_sobre_mismo;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_gira_sobre_mismo) && $array_datos_rol_ocupacional[0]->Motriz_gira_sobre_mismo == 2.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_gira_sobre_mismo;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                        <td colspan="3" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Motriz_sentanser_sin_apoyo) && $array_datos_rol_ocupacional[0]->Motriz_sentanser_sin_apoyo == 0.0){
                                    echo $array_datos_rol_ocupacional[0]->Motriz_sentanser_sin_apoyo;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_sentanser_sin_apoyo) && $array_datos_rol_ocupacional[0]->Motriz_sentanser_sin_apoyo == 1.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_sentanser_sin_apoyo;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_sentanser_sin_apoyo) && $array_datos_rol_ocupacional[0]->Motriz_sentanser_sin_apoyo == 2.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_sentanser_sin_apoyo;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                        <td colspan="2" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Motriz_pasa_tumbado_sentado) && $array_datos_rol_ocupacional[0]->Motriz_pasa_tumbado_sentado == 0.0){
                                    echo $array_datos_rol_ocupacional[0]->Motriz_pasa_tumbado_sentado;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_pasa_tumbado_sentado) && $array_datos_rol_ocupacional[0]->Motriz_pasa_tumbado_sentado == 1.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_pasa_tumbado_sentado;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_pasa_tumbado_sentado) && $array_datos_rol_ocupacional[0]->Motriz_pasa_tumbado_sentado == 2.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_pasa_tumbado_sentado;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="label_rol_laboral">Se pone de pie con apoyo</td>
                        <td colspan="2" class="label_rol_laboral">Da pasos con apoyo</td>
                        <td colspan="3" class="label_rol_laboral">Se mantiene de pie sin apoyo</td>
                        <td colspan="2" class="label_rol_laboral">Anda solo</td>
                        <td colspan="3" class="label_rol_laboral">Empuja una pelota con los pies</td>
                        <td colspan="5" class="label_rol_laboral">Anda sorteando obstáculos</td>
                        {{-- <td colspan="2" rowspan="1" class="centrar_dato_dinamico"></td> --}}
                    </tr>
                    <tr>
                        <td colspan="3" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Motriz_pararse_apoyo) && $array_datos_rol_ocupacional[0]->Motriz_pararse_apoyo == 0.0){
                                    echo $array_datos_rol_ocupacional[0]->Motriz_pararse_apoyo;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_pararse_apoyo) && $array_datos_rol_ocupacional[0]->Motriz_pararse_apoyo == 1.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_pararse_apoyo;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_pararse_apoyo) && $array_datos_rol_ocupacional[0]->Motriz_pararse_apoyo == 2.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_pararse_apoyo;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                        <td colspan="2" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Motriz_pasos_apoyo) && $array_datos_rol_ocupacional[0]->Motriz_pasos_apoyo == 0.0){
                                    echo $array_datos_rol_ocupacional[0]->Motriz_pasos_apoyo;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_pasos_apoyo) && $array_datos_rol_ocupacional[0]->Motriz_pasos_apoyo == 1.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_pasos_apoyo;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_pasos_apoyo) && $array_datos_rol_ocupacional[0]->Motriz_pasos_apoyo == 2.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_pasos_apoyo;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                        <td colspan="3" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Motriz_pararse_sin_apoyo) && $array_datos_rol_ocupacional[0]->Motriz_pararse_sin_apoyo == 0.0){
                                    echo $array_datos_rol_ocupacional[0]->Motriz_pararse_sin_apoyo;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_pararse_sin_apoyo) && $array_datos_rol_ocupacional[0]->Motriz_pararse_sin_apoyo == 1.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_pararse_sin_apoyo;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_pararse_sin_apoyo) && $array_datos_rol_ocupacional[0]->Motriz_pararse_sin_apoyo == 2.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_pararse_sin_apoyo;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                        <td colspan="2" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Motriz_anda_solo) && $array_datos_rol_ocupacional[0]->Motriz_anda_solo == 0.0){
                                    echo $array_datos_rol_ocupacional[0]->Motriz_anda_solo;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_anda_solo) && $array_datos_rol_ocupacional[0]->Motriz_anda_solo == 1.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_anda_solo;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_anda_solo) && $array_datos_rol_ocupacional[0]->Motriz_anda_solo == 2.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_anda_solo;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                        <td colspan="3" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Motriz_empujar_pelota_pies) && $array_datos_rol_ocupacional[0]->Motriz_empujar_pelota_pies == 0.0){
                                    echo $array_datos_rol_ocupacional[0]->Motriz_empujar_pelota_pies;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_empujar_pelota_pies) && $array_datos_rol_ocupacional[0]->Motriz_empujar_pelota_pies == 1.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_empujar_pelota_pies;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_empujar_pelota_pies) && $array_datos_rol_ocupacional[0]->Motriz_empujar_pelota_pies == 2.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_empujar_pelota_pies;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                        <td colspan="5" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Motriz_andar_obstaculos) && $array_datos_rol_ocupacional[0]->Motriz_andar_obstaculos == 0.0){
                                    echo $array_datos_rol_ocupacional[0]->Motriz_andar_obstaculos;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_andar_obstaculos) && $array_datos_rol_ocupacional[0]->Motriz_andar_obstaculos == 1.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_andar_obstaculos;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Motriz_andar_obstaculos) && $array_datos_rol_ocupacional[0]->Motriz_andar_obstaculos == 2.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Motriz_andar_obstaculos;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="18" rowspan="1" class="label_area_ocupacional">Actividad Adaptativa</td>                        
                    </tr>
                    <tr>
                        <td colspan="3" class="label_rol_laboral">Succiona</td>
                        <td colspan="2" class="label_rol_laboral">Fija la mirada</td>
                        <td colspan="3" class="label_rol_laboral">Sigue la trayectoria de un objeto</td>
                        <td colspan="2" class="label_rol_laboral">Sostiene un sonajero</td>
                        <td colspan="3" class="label_rol_laboral">Tiende la mano hacia un objeto</td>
                        <td colspan="5" class="label_rol_laboral">Sostiene un objeto en cada mano</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Adaptativa_succiona) && $array_datos_rol_ocupacional[0]->Adaptativa_succiona == 0.0){
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_succiona;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Adaptativa_succiona) && $array_datos_rol_ocupacional[0]->Adaptativa_succiona == 1.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_succiona;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Adaptativa_succiona) && $array_datos_rol_ocupacional[0]->Adaptativa_succiona == 2.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_succiona;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                        <td colspan="2" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Adaptativa_fija_mirada) && $array_datos_rol_ocupacional[0]->Adaptativa_fija_mirada == 0.0){
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_fija_mirada;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Adaptativa_fija_mirada) && $array_datos_rol_ocupacional[0]->Adaptativa_fija_mirada == 1.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_fija_mirada;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Adaptativa_fija_mirada) && $array_datos_rol_ocupacional[0]->Adaptativa_fija_mirada == 2.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_fija_mirada;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                        <td colspan="3" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Adaptativa_sigue_trayectoria_objeto) && $array_datos_rol_ocupacional[0]->Adaptativa_sigue_trayectoria_objeto == 0.0){
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_sigue_trayectoria_objeto;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Adaptativa_sigue_trayectoria_objeto) && $array_datos_rol_ocupacional[0]->Adaptativa_sigue_trayectoria_objeto == 1.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_sigue_trayectoria_objeto;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Adaptativa_sigue_trayectoria_objeto) && $array_datos_rol_ocupacional[0]->Adaptativa_sigue_trayectoria_objeto == 2.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_sigue_trayectoria_objeto;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                        <td colspan="2" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Adaptativa_sostiene_sonajero) && $array_datos_rol_ocupacional[0]->Adaptativa_sostiene_sonajero == 0.0){
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_sostiene_sonajero;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Adaptativa_sostiene_sonajero) && $array_datos_rol_ocupacional[0]->Adaptativa_sostiene_sonajero == 1.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_sostiene_sonajero;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Adaptativa_sostiene_sonajero) && $array_datos_rol_ocupacional[0]->Adaptativa_sostiene_sonajero == 2.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_sostiene_sonajero;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                        <td colspan="3" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Adaptativa_tiende_mano_hacia_objeto) && $array_datos_rol_ocupacional[0]->Adaptativa_tiende_mano_hacia_objeto == 0.0){
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_tiende_mano_hacia_objeto;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Adaptativa_tiende_mano_hacia_objeto) && $array_datos_rol_ocupacional[0]->Adaptativa_tiende_mano_hacia_objeto == 1.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_tiende_mano_hacia_objeto;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Adaptativa_tiende_mano_hacia_objeto) && $array_datos_rol_ocupacional[0]->Adaptativa_tiende_mano_hacia_objeto == 2.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_tiende_mano_hacia_objeto;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                        <td colspan="5" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Adaptativa_sostiene_objeto_manos) && $array_datos_rol_ocupacional[0]->Adaptativa_sostiene_objeto_manos == 0.0){
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_sostiene_objeto_manos;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Adaptativa_sostiene_objeto_manos) && $array_datos_rol_ocupacional[0]->Adaptativa_sostiene_objeto_manos == 1.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_sostiene_objeto_manos;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Adaptativa_sostiene_objeto_manos) && $array_datos_rol_ocupacional[0]->Adaptativa_sostiene_objeto_manos == 2.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_sostiene_objeto_manos;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="label_rol_laboral">Abre cajones</td>
                        <td colspan="2" class="label_rol_laboral">Bebe solo</td>
                        <td colspan="3" class="label_rol_laboral">Se quita una prenda de vestir</td>
                        <td colspan="2" class="label_rol_laboral">Reconoce la función de los espacios de la casa</td>
                        <td colspan="3" class="label_rol_laboral">Imita trazos con el lápiz</td>
                        <td colspan="5" class="label_rol_laboral">Abre una puerta</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Adaptativa_abre_cajones) && $array_datos_rol_ocupacional[0]->Adaptativa_abre_cajones == 0.0){
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_abre_cajones;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Adaptativa_abre_cajones) && $array_datos_rol_ocupacional[0]->Adaptativa_abre_cajones == 1.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_abre_cajones;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Adaptativa_abre_cajones) && $array_datos_rol_ocupacional[0]->Adaptativa_abre_cajones == 2.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_abre_cajones;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                        <td colspan="2" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Adaptativa_bebe_solo) && $array_datos_rol_ocupacional[0]->Adaptativa_bebe_solo == 0.0){
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_bebe_solo;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Adaptativa_bebe_solo) && $array_datos_rol_ocupacional[0]->Adaptativa_bebe_solo == 1.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_bebe_solo;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Adaptativa_bebe_solo) && $array_datos_rol_ocupacional[0]->Adaptativa_bebe_solo == 2.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_bebe_solo;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                        <td colspan="3" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Adaptativa_quitar_prenda_vestir) && $array_datos_rol_ocupacional[0]->Adaptativa_quitar_prenda_vestir == 0.0){
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_quitar_prenda_vestir;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Adaptativa_quitar_prenda_vestir) && $array_datos_rol_ocupacional[0]->Adaptativa_quitar_prenda_vestir == 1.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_quitar_prenda_vestir;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Adaptativa_quitar_prenda_vestir) && $array_datos_rol_ocupacional[0]->Adaptativa_quitar_prenda_vestir == 2.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_quitar_prenda_vestir;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                        <td colspan="2" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Adaptativa_reconoce_funcion_espacios_casa) && $array_datos_rol_ocupacional[0]->Adaptativa_reconoce_funcion_espacios_casa == 0.0){
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_reconoce_funcion_espacios_casa;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Adaptativa_reconoce_funcion_espacios_casa) && $array_datos_rol_ocupacional[0]->Adaptativa_reconoce_funcion_espacios_casa == 1.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_reconoce_funcion_espacios_casa;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Adaptativa_reconoce_funcion_espacios_casa) && $array_datos_rol_ocupacional[0]->Adaptativa_reconoce_funcion_espacios_casa == 2.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_reconoce_funcion_espacios_casa;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                        <td colspan="3" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Adaptativa_imita_trazo_lapiz) && $array_datos_rol_ocupacional[0]->Adaptativa_imita_trazo_lapiz == 0.0){
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_imita_trazo_lapiz;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Adaptativa_imita_trazo_lapiz) && $array_datos_rol_ocupacional[0]->Adaptativa_imita_trazo_lapiz == 1.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_imita_trazo_lapiz;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Adaptativa_imita_trazo_lapiz) && $array_datos_rol_ocupacional[0]->Adaptativa_imita_trazo_lapiz == 2.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_imita_trazo_lapiz;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                        <td colspan="5" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Adaptativa_abre_puerta) && $array_datos_rol_ocupacional[0]->Adaptativa_abre_puerta == 0.0){
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_abre_puerta;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Adaptativa_abre_puerta) && $array_datos_rol_ocupacional[0]->Adaptativa_abre_puerta == 1.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_abre_puerta;
                                }elseif(!empty($array_datos_rol_ocupacional[0]->Adaptativa_abre_puerta) && $array_datos_rol_ocupacional[0]->Adaptativa_abre_puerta == 2.0){ 
                                    echo $array_datos_rol_ocupacional[0]->Adaptativa_abre_puerta;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="centrar_titulo_labels">CÁLCULO FINAL PCO</td>
                        <td colspan="11" class="right_titulo_labels"><b>Sumatoria área motriz + Sumatoria área adaptativa:</b></td>
                        <td colspan="2" class="centrar_dato_dinamico">
                            <?php 
                                if(!empty($array_datos_rol_ocupacional[0]->Total_criterios_desarrollo)){
                                    echo $array_datos_rol_ocupacional[0]->Total_criterios_desarrollo;
                                }else {
                                    echo '0.0';
                                }                          
                            ?>
                        </td>
                    </tr>
                @elseif(!empty($array_datos_rol_ocupacional[0]->Poblacion_calificar) && $array_datos_rol_ocupacional[0]->Poblacion_calificar == 76)
                    <tr>
                        <td colspan="18" class="dato_dinamico">Asigne valor único del rol ocupacional e integrar la información de la ejecución de las áreas ocupacionales o AVD</td>
                    </tr>
                    <tr>
                        <td colspan="18" class="titulo_tablas">TABLA 13 - Valoración de los roles ocupacionales de juego-estudio en niños y niñas mayores de tres años y adolescentes</td>
                    </tr>
                    <tr>
                        <td colspan="14" class="centrar_titulo_labels">CRITERIO CUALITATIVO</td>
                        <td colspan="2" class="centrar_titulo_labels">CLASE</td>
                        <td colspan="2" class="centrar_titulo_labels">VALOR</td>
                    </tr>
                    <tr>
                        <td colspan="14" class="centrar_dato_labels">Rol Ocupacional sin dificultad-no dependencia</td>
                        <td colspan="2" class="centrar_dato_labels">A</td>
                        <td colspan="1" class="centrar_dato_labels">0,0</td>
                        <td colspan="1" class="centrar_dato_dinamico"><?php if(!empty($array_datos_rol_ocupacional[0]->Juego_estudio_clase) && $array_datos_rol_ocupacional[0]->Juego_estudio_clase == 0.0){echo 'X';}else{echo '';}?></td>
                    </tr>
                    <tr>
                        <td colspan="14" class="centrar_dato_labels">Rol ocupacional con dificultad leve-no dependencia </td>
                        <td colspan="2" class="centrar_dato_labels">B</td>
                        <td colspan="1" class="centrar_dato_labels">10,0</td>
                        <td colspan="1" class="centrar_dato_dinamico"><?php if(!empty($array_datos_rol_ocupacional[0]->Juego_estudio_clase) && $array_datos_rol_ocupacional[0]->Juego_estudio_clase == 10){echo 'X';}else{echo '';}?></td>
                    </tr>
                    <tr>
                        <td colspan="14" class="centrar_dato_labels">Rol ocupacional adaptado con dificultad moderada-dependencia moderada</td>
                        <td colspan="2" class="centrar_dato_labels">C</td>
                        <td colspan="1" class="centrar_dato_labels">25,0</td>
                        <td colspan="1" class="centrar_dato_dinamico"><?php if(!empty($array_datos_rol_ocupacional[0]->Juego_estudio_clase) && $array_datos_rol_ocupacional[0]->Juego_estudio_clase == 25){echo 'X';}else{echo '';}?></td>
                    </tr>
                    <tr>
                        <td colspan="14" class="centrar_dato_labels">Rol ocupacional con Dificultad severa-dependencia severa</td>
                        <td colspan="2" class="centrar_dato_labels">D</td>
                        <td colspan="1" class="centrar_dato_labels">35,0</td>
                        <td colspan="1" class="centrar_dato_dinamico"><?php if(!empty($array_datos_rol_ocupacional[0]->Juego_estudio_clase) && $array_datos_rol_ocupacional[0]->Juego_estudio_clase == 35){echo 'X';}else{echo '';}?></td>
                    </tr>
                    <tr>
                        <td colspan="14" class="centrar_dato_labels">Rol ocupacional con dificultad completa-dependencia Gravecompleta</td>
                        <td colspan="2" class="centrar_dato_labels">E</td>
                        <td colspan="1" class="centrar_dato_labels">50,0</td>
                        <td colspan="1" class="centrar_dato_dinamico"><?php if(!empty($array_datos_rol_ocupacional[0]->Juego_estudio_clase) && $array_datos_rol_ocupacional[0]->Juego_estudio_clase == 50){echo 'X';}else{echo '';}?></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="centrar_dato_labels"><b>CÁLCULO FINAL PCO</b></td>
                        <td colspan="12" class="right_titulo_labels"><b>Valor del rol asignado</b></td>
                        <td colspan="2" class="centrar_dato_dinamico"><?php if(!empty($array_datos_rol_ocupacional[0]->Total_rol_estudio_clase)){echo $array_datos_rol_ocupacional[0]->Total_rol_estudio_clase;}else{echo '0.0';}?></td>
                    </tr>
                @elseif(!empty($array_datos_rol_ocupacional[0]->Poblacion_calificar) && $array_datos_rol_ocupacional[0]->Poblacion_calificar == 77)
                    <tr>
                        <td colspan="18" class="dato_dinamico">Asigne valor único del rol ocupacional e integrar la información de la ejecución de las áreas ocupacionales o AVD</td>
                    </tr>
                    <tr>
                        <td colspan="18" class="titulo_tablas">TABLA 14 - Valoración de los roles ocupacional relacionado con el uso del tiempo libre y de esparcimiento en adultos mayores</td>
                    </tr>
                    <tr>
                        <td colspan="14" class="centrar_titulo_labels">CRITERIO CUALITATIVO</td>
                        <td colspan="2" class="centrar_titulo_labels">CLASE</td>
                        <td colspan="2" class="centrar_titulo_labels">VALOR</td>
                    </tr>
                    <tr>
                        <td colspan="14" class="centrar_dato_labels">Rol Ocupacional sin dificultad-no dependencia</td>
                        <td colspan="2" class="centrar_dato_labels">A</td>
                        <td colspan="1" class="centrar_dato_labels">0,0</td>
                        <td colspan="1" class="centrar_dato_dinamico"><?php if(!empty($array_datos_rol_ocupacional[0]->Adultos_mayores) && $array_datos_rol_ocupacional[0]->Adultos_mayores == 0.0){echo 'X';}else{echo '';}?></td>
                    </tr>
                    <tr>
                        <td colspan="14" class="centrar_dato_labels">Rol ocupacional con dificultad leve-no dependencia </td>
                        <td colspan="2" class="centrar_dato_labels">B</td>
                        <td colspan="1" class="centrar_dato_labels">10,0</td>
                        <td colspan="1" class="centrar_dato_dinamico"><?php if(!empty($array_datos_rol_ocupacional[0]->Adultos_mayores) && $array_datos_rol_ocupacional[0]->Adultos_mayores == 10){echo 'X';}else{echo '';}?></td>
                    </tr>
                    <tr>
                        <td colspan="14" class="centrar_dato_labels">Rol ocupacional adaptado con dificultad moderada-dependencia moderada</td>
                        <td colspan="2" class="centrar_dato_labels">C</td>
                        <td colspan="1" class="centrar_dato_labels">25,0</td>
                        <td colspan="1" class="centrar_dato_dinamico"><?php if(!empty($array_datos_rol_ocupacional[0]->Adultos_mayores) && $array_datos_rol_ocupacional[0]->Adultos_mayores == 25){echo 'X';}else{echo '';}?></td>
                    </tr>
                    <tr>
                        <td colspan="14" class="centrar_dato_labels">Rol ocupacional con Dificultad severa-dependencia severa</td>
                        <td colspan="2" class="centrar_dato_labels">D</td>
                        <td colspan="1" class="centrar_dato_labels">35,0</td>
                        <td colspan="1" class="centrar_dato_dinamico"><?php if(!empty($array_datos_rol_ocupacional[0]->Adultos_mayores) && $array_datos_rol_ocupacional[0]->Adultos_mayores == 35){echo 'X';}else{echo '';}?></td>
                    </tr>
                    <tr>
                        <td colspan="14" class="centrar_dato_labels">Rol ocupacional con dificultad completa-dependencia Gravecompleta</td>
                        <td colspan="2" class="centrar_dato_labels">E</td>
                        <td colspan="1" class="centrar_dato_labels">50,0</td>
                        <td colspan="1" class="centrar_dato_dinamico"><?php if(!empty($array_datos_rol_ocupacional[0]->Adultos_mayores) && $array_datos_rol_ocupacional[0]->Adultos_mayores == 50){echo 'X';}else{echo '';}?></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="centrar_dato_labels"><b>CÁLCULO FINAL PCO</b></td>
                        <td colspan="12" class="right_titulo_labels"><b>Valor del rol asignado</b></td>
                        <td colspan="2" class="centrar_dato_dinamico"><?php if(!empty($array_datos_rol_ocupacional[0]->Total_rol_adultos_ayores)){echo $array_datos_rol_ocupacional[0]->Total_rol_adultos_ayores;}else{echo '0.0';}?></td>
                    </tr>
                @endif
            @endif
            <tr>
                <td colspan="18"></td>
            </tr>
            <tr>
                <td colspan="18" class="titulo_tablas">7. CONCEPTO FINAL DEL DICTAMEN PERICIAL</td>
            </tr>
            <tr>
                <td colspan="18" class="centrar_dato_labels"><b>Pérdida de Capacidad Laboral</b> =  TÍTULO I -Valor Final Ponderada  +   TÍTULO II -Valor Final</td>
            </tr>
            <tr>
                <td colspan="16" class="label_valoracion_final">VALOR FINAL DE LA PCL / OCUPACIONAL %</td>
                <td colspan="2" class="centrar_dato_dinamico">{{$Porcentaje_Pcl_dp}}</td>
            </tr>
            <tr>
                <td colspan="18"></td>
            </tr>
            <tr>
                <td colspan="5" class="titulo_labels">FECHA DE ESTRUCTURACIÓN</td>
                <td colspan="4" class="dato_dinamico"> {{$F_estructuracion_dpF}}</td>
                <td colspan="4" class="titulo_labels">TIPO DE EVENTO</td>
                <td colspan="5" class="dato_dinamico"> {{$Tipo_evento_dp}}</td>
            </tr>
            <tr>
                <td colspan="5" class="titulo_labels">FECHA ACCIDENTE / ENFERMEDAD</td>
                <td colspan="4" class="dato_dinamico"> <?php if($F_evento_dpF<>'31-12-1969' || $F_evento_dpF<>'0000-00-00'){ echo $F_evento_dpF;}?></td>
                <td colspan="4" class="titulo_labels">ORIGEN</td>
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
                <td colspan="4" class="left_titulo_labels">Alto costo / Catastrófica:</td>
                <td colspan="5" class="dato_dinamico"><?php if ($Enfermedad_catastrofica_dp == 'Enfermedad Catastrófica'){echo 'SI';}else{echo 'NO';}?></td>
                <td colspan="4" class="left_titulo_labels">Congénita o cercana a nacimiento:</td>
                <td colspan="5" class="dato_dinamico"><?php if ($Enfermedad_congenita_dp == 'Enfermedad Congénita o cercana al nacimiento'){echo 'SI';}else{echo 'NO';}?></td>
            </tr>
            <tr>
                <td colspan="4" class="left_titulo_labels">Revisión pensión:</td>
                {{-- <td colspan="5" class="dato_dinamico"><?php if ($Revision_pension_dp == 8) {echo 'Si';} else { echo 'No';}?></td> --}}
                <td colspan="5" class="dato_dinamico"><?php if ($Revision_pension_dp == 'Require Revision Pension') {echo 'SI';} else { echo 'NO';}?></td>
                <td colspan="4" class="left_titulo_labels">Tipo de enfermedad / Deficiencia:</td>
                <td colspan="5" class="dato_dinamico"><?php if (!empty($Nombre_enfermedad_dp)){ echo $Nombre_enfermedad_dp;} else {echo 'N/A';}?></td>
            </tr>
            <tr>
                <td colspan="18" class="centrar_dato_labels"><b>Clasificación condición de salud - Tipo de enfermedad</b></td>
            </tr>
            <tr>
                <td colspan="16" class="right_titulo_labels"><b>Requiere de terceras personas para realizar sus actividades de la vida diaria (áreas ocupacionales):</b></td>
                <td colspan="2" class="dato_dinamico"><?php if ($Requiere_tercera_persona_dp == 'Requiere tercera persona') {echo 'SI';} else {echo 'No';}?></td>
            </tr>
            <tr>
                <td colspan="16" class="right_titulo_labels"><b>Se requiere de curador para la toma de decisiones:</b></td>
                <td colspan="2" class="dato_dinamico"><?php if ($Requiere_tercera_persona_decisiones_dp == 'Requiere de tercera persona para la toma de decisiones') {echo 'SI';} else {echo 'No';}?></td>
            </tr>
            <tr>
                <td colspan="16" class="right_titulo_labels"><b>Requiere de dispositivo de apoyo para realizar actividades de la vida diaria (áreas ocupacionales):</b></td>
                <td colspan="2" class="dato_dinamico"><?php if ($Requiere_dispositivo_apoyo_dp == 'Requiere de dispositivo de apoyo') {echo 'SI';} else {echo 'No';}?></td>
            </tr>
        </table>
        <div class="content2">
            <section class="border_section">
                <div class="dato_dinamico_font"><b>Justificación de dependencia:</b></div>
                <div class="dato_dinamico_font">{{$Justificacion_dependencia_dp}}</div>
            </section>
        </div> 
        <table class="tabla_dictamen">
            <tr>
                <td colspan="18">
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