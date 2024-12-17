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
                            FORMULARIO DE DICTAMEN PARA LA CALIFICACIÓN DE LA PÉRDIDA 
                            DE CAPACIDAD LABORAL Y DETERMINACIÓN DE 
                            LA INVALIDEZ DECRETO 917 DE 1999
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
                <td colspan="13" class="dato_dinamico">{{$Nombre_cargo_laboral}}</td>
            </tr>            
            <tr>
                <td colspan="4" class="titulo_labels">Ocupación:</td>
                <td colspan="5" class="dato_dinamico">{{$Ocupacion_afiliado}}</td>
                <td colspan="2" class="titulo_labels">Código CIUO:</td>
                <td colspan="6" class="dato_dinamico">{{$Codigo_ciuo_laboral}}</td>
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
                <td colspan="17" class="titulo_tablas">6. FUNDAMENTOS PARA LA CALIFICACIÓN DE LA PERDIDA DE LA CAPACIDAD LABORAL Y OCUPACIONAL - LIBROS I, II Y III</td>
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
                <td colspan="17" class="titulo_tablas">LIBRO I CLASIFICACIÓN / VALORACIÓN DE LAS DEFICIENCIAS</td>
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
                <td colspan="1" class="centrar_titulo_labels">No.</td>
                <td colspan="12" class="centrar_titulo_labels">Nombre de deficiencia</td>
                <td colspan="2" class="centrar_titulo_labels">No. Tabla</td>
                <td colspan="2" class="centrar_titulo_labels">Deficiencia</td>
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
                        <td colspan="12" class="dato_dinamico">{{ $deficiencias_fc->Titulo_tabla1999 }}</td>
                        <td colspan="2" class="centrar_dato_dinamico">{{ $deficiencias_fc->Tabla1999 }}</td>
                        <td colspan="2" class="centrar_dato_dinamico">{{ $deficiencias_fc->Total_deficiencia }}</td>                       
                    </tr>
                @endforeach  
            @else
                <tr>
                    <td colspan="1" class="centrar_dato_dinamico"></td>
                    <td colspan="12" class="dato_dinamico"></td>
                    <td colspan="2" class="centrar_dato_dinamico"></td>
                    <td colspan="2" class="centrar_dato_dinamico"></td> 
                </tr>
         
            @endif                                                       
            {{-- <tr>
                <td colspan="6" class="explicacionFB sinborder"><b>CFP:</b> Clase Factor principal</td>
                <td colspan="6" class="explicacionFB sinborder"><b>CFM:</b> Clase Factor Modulador</td>
                <td colspan="5" class="explicacionFB sinborder"><b>CFU:</b> Clase Factor único</td>
            </tr>
            <tr>
                <td colspan="17" class="explicacionFB sinborder"><b>Formula Ajuste Total de Deficiencias por tabla:</b> (CFM1-CFP) + (CFM2-CFP) + (CFM3-CFP)</td>
            </tr> --}}
            <tr>
                <td colspan="8" rowspan="1" class="explicacionFB sinborder"><b>Fórmula de Balthazar:</b> Obtiene el valor final de las deficiencias sin ponderar</td>
                <td colspan="5" class="explicacionFB sinborder"><b>A +  (100 -  A) * B</b><br><hr style="border: 0.1px solid black;"><b>100</b></td>
                <td colspan="4" class="explicacionFB sinborder">A: Deficiencia de mayor valor<br>B: Deficiencia de menor valor</td>
            </tr>          
            <tr>
                {{-- <td colspan="7" class="right_dato_dinamico"><b>% Total Deficiencia (F. Balthazar, sin ponderar)</b></td>
                <td colspan="1" class="centrar_dato_dinamico">{{$Suma_combinada_fc}}</td> --}}
                <td colspan="16" class="right_dato_dinamico"><b>CÁLCULO FINAL DE DEFICIENCIA (50%)</b></td>
                <td colspan="1" class="centrar_dato_dinamico">{{$Total_deficiencia50_fc}}</td>
            </tr>
            <tr>
                <td colspan="17"></td>
            </tr>
            <tr>
                <td colspan="17" class="titulo_tablas">LIBRO II CALIFICACIÓN / VALORACIÓN DE LAS DISCAPACIDADES</td>
            </tr>
            <tr>
                <td colspan="17" class="dato_dinamico">Asigne valor según el grado de discapacidad</td>
            </tr>
            <tr>
                <td colspan="3" class="centrar_titulo_labels">VALOR</td>
                <td colspan="14" class="centrar_titulo_labels">CRITERIO CUALITATIVO</td>
            </tr>
            <tr>
                <td colspan="3" class="centrar_dato_labels">0,0</td>
                <td colspan="14" class="centrar_dato_labels">No discapacitado</td>
            </tr>
            <tr>
                <td colspan="3" class="centrar_dato_labels">0,1</td>
                <td colspan="14" class="centrar_dato_labels">Dificultad en la ejecución</td>
            </tr>
            <tr>
                <td colspan="3" class="centrar_dato_labels">0,2</td>
                <td colspan="14" class="centrar_dato_labels">Ejecución Ayudada</td>
            </tr>
            <tr>
                <td colspan="3" class="centrar_dato_labels">0,3</td>
                <td colspan="14" class="centrar_dato_labels">Ejecución Asistida, dependiente o incrementada</td>
            </tr>                        
            <tr>
                <td colspan="17"></td>
            </tr>
            <tr>
                <td colspan="6" class="centrar_titulo_labels">Discapacidad</td>
                <td colspan="10" class="centrar_titulo_labels">Número de  la discapacidad</td>
                <td colspan="1" class="centrar_titulo_labels">Total</td>
            </tr>
            <tr>
                <td colspan="6" rowspan="1" class="centrar_titulo_labels">Conducta</td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>10</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Conducta10)) {echo $array_datos_libros23[0]->Conducta10;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>11</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Conducta11)) {echo $array_datos_libros23[0]->Conducta11;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>12</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Conducta12)) {echo $array_datos_libros23[0]->Conducta12;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>13</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Conducta13)) {echo $array_datos_libros23[0]->Conducta13;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>14</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Conducta14)) {echo $array_datos_libros23[0]->Conducta14;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>15</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Conducta15)) {echo $array_datos_libros23[0]->Conducta15;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>16</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Conducta16)) {echo $array_datos_libros23[0]->Conducta16;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>17</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Conducta17)) {echo $array_datos_libros23[0]->Conducta17;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>18</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Conducta18)) {echo $array_datos_libros23[0]->Conducta18;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>19</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Conducta19)) {echo $array_datos_libros23[0]->Conducta19;}else{ echo '0.0';}?>
                </td>
                <td colspan="1"rowspan="1" class="centrar_dato_dinamico"><?php if (!empty($array_datos_libros23[0]->Total_conducta)) {echo $array_datos_libros23[0]->Total_conducta;}else{ echo '0.0';}?></td>
            </tr>
            <tr>
                <td colspan="6" rowspan="1" class="centrar_titulo_labels">Comunicación</td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>20</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Comunicacion20)) {echo $array_datos_libros23[0]->Comunicacion20;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>21</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Comunicacion21)) {echo $array_datos_libros23[0]->Comunicacion21;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>22</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Comunicacion22)) {echo $array_datos_libros23[0]->Comunicacion22;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>23</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Comunicacion23)) {echo $array_datos_libros23[0]->Comunicacion23;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>24</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Comunicacion24)) {echo $array_datos_libros23[0]->Comunicacion24;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>25</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Comunicacion25)) {echo $array_datos_libros23[0]->Comunicacion25;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>26</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Comunicacion26)) {echo $array_datos_libros23[0]->Comunicacion26;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>27</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Comunicacion27)) {echo $array_datos_libros23[0]->Comunicacion27;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>28</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Comunicacion28)) {echo $array_datos_libros23[0]->Comunicacion28;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>29</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Comunicacion29)) {echo $array_datos_libros23[0]->Comunicacion29;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" rowspan="1" class="centrar_dato_dinamico"><?php if (!empty($array_datos_libros23[0]->Total_comunicacion)) {echo $array_datos_libros23[0]->Total_comunicacion;}else{ echo '0.0';}?></td>
            </tr>
            <tr>
                <td colspan="6" rowspan="1" class="centrar_titulo_labels">Cuidado personal</td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>30</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Personal30)) {echo $array_datos_libros23[0]->Personal30;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>31</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Personal31)) {echo $array_datos_libros23[0]->Personal31;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>32</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Personal32)) {echo $array_datos_libros23[0]->Personal32;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>33</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Personal33)) {echo $array_datos_libros23[0]->Personal33;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>34</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Personal34)) {echo $array_datos_libros23[0]->Personal34;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>35</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Personal35)) {echo $array_datos_libros23[0]->Personal35;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>36</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Personal36)) {echo $array_datos_libros23[0]->Personal36;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>37</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Personal37)) {echo $array_datos_libros23[0]->Personal37;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>38</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Personal38)) {echo $array_datos_libros23[0]->Personal38;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>39</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Personal39)) {echo $array_datos_libros23[0]->Personal39;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" rowspan="1" class="centrar_dato_dinamico"><?php if (!empty($array_datos_libros23[0]->Total_personal)) {echo $array_datos_libros23[0]->Total_personal;}else{ echo '0.0';}?></td>
            </tr>
            <tr>
                <td colspan="6" rowspan="1" class="centrar_titulo_labels">Locomoción</td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>40</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Locomocion40)) {echo $array_datos_libros23[0]->Locomocion40;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>41</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Locomocion41)) {echo $array_datos_libros23[0]->Locomocion41;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>42</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Locomocion42)) {echo $array_datos_libros23[0]->Locomocion42;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>43</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Locomocion43)) {echo $array_datos_libros23[0]->Locomocion43;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>44</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Locomocion44)) {echo $array_datos_libros23[0]->Locomocion44;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>45</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Locomocion45)) {echo $array_datos_libros23[0]->Locomocion45;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>46</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Locomocion46)) {echo $array_datos_libros23[0]->Locomocion46;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>47</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Locomocion47)) {echo $array_datos_libros23[0]->Locomocion47;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>48</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Locomocion48)) {echo $array_datos_libros23[0]->Locomocion48;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>49</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Locomocion49)) {echo $array_datos_libros23[0]->Locomocion49;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" rowspan="1" class="centrar_dato_dinamico"><?php if (!empty($array_datos_libros23[0]->Total_locomocion)) {echo $array_datos_libros23[0]->Total_locomocion;}else{ echo '0.0';}?></td>
            </tr>
            <tr>
                <td colspan="6" rowspan="1" class="centrar_titulo_labels">Disposición del cuerpo</td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>50</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Disposicion50)) {echo $array_datos_libros23[0]->Disposicion50;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>51</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Disposicion51)) {echo $array_datos_libros23[0]->Disposicion51;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>52</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Disposicion52)) {echo $array_datos_libros23[0]->Disposicion52;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>53</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Disposicion53)) {echo $array_datos_libros23[0]->Disposicion53;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>54</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Disposicion54)) {echo $array_datos_libros23[0]->Disposicion54;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>55</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Disposicion55)) {echo $array_datos_libros23[0]->Disposicion55;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>56</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Disposicion56)) {echo $array_datos_libros23[0]->Disposicion56;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>57</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Disposicion57)) {echo $array_datos_libros23[0]->Disposicion57;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>58</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Disposicion58)) {echo $array_datos_libros23[0]->Disposicion58;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>59</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Disposicion59)) {echo $array_datos_libros23[0]->Disposicion59;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" rowspan="1" class="centrar_dato_dinamico"><?php if (!empty($array_datos_libros23[0]->Total_disposicion)) {echo $array_datos_libros23[0]->Total_disposicion;}else{ echo '0.0';}?></td>
            </tr>
            <tr>
                <td colspan="6" rowspan="1" class="centrar_titulo_labels">Destreza</td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>60</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Destreza60)) {echo $array_datos_libros23[0]->Destreza60;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>61</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Destreza61)) {echo $array_datos_libros23[0]->Destreza61;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>62</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Destreza62)) {echo $array_datos_libros23[0]->Destreza62;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>63</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Destreza63)) {echo $array_datos_libros23[0]->Destreza63;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>64</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Destreza64)) {echo $array_datos_libros23[0]->Destreza64;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>65</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Destreza65)) {echo $array_datos_libros23[0]->Destreza65;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>66</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Destreza66)) {echo $array_datos_libros23[0]->Destreza66;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>67</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Destreza67)) {echo $array_datos_libros23[0]->Destreza67;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>68</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Destreza68)) {echo $array_datos_libros23[0]->Destreza68;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>69</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Destreza69)) {echo $array_datos_libros23[0]->Destreza69;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" rowspan="1" class="centrar_dato_dinamico"><?php if (!empty($array_datos_libros23[0]->Total_destreza)) {echo $array_datos_libros23[0]->Total_destreza;}else{ echo '0.0';}?></td>
            </tr>
            <tr>
                <td colspan="6" rowspan="1" class="centrar_titulo_labels">Situación</td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>70</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Situacion70)) {echo $array_datos_libros23[0]->Situacion70;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>71</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Situacion71)) {echo $array_datos_libros23[0]->Situacion71;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>72</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Situacion72)) {echo $array_datos_libros23[0]->Situacion72;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>73</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Situacion73)) {echo $array_datos_libros23[0]->Situacion73;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>74</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Situacion74)) {echo $array_datos_libros23[0]->Situacion74;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>75</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Situacion75)) {echo $array_datos_libros23[0]->Situacion75;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>76</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Situacion76)) {echo $array_datos_libros23[0]->Situacion76;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>77</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Situacion77)) {echo $array_datos_libros23[0]->Situacion77;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico">
                    <b>78</b><br>
                    <?php if (!empty($array_datos_libros23[0]->Situacion78)) {echo $array_datos_libros23[0]->Situacion78;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" class="centrar_dato_dinamico"></td>
                <td colspan="1" rowspan="1" class="centrar_dato_dinamico"><?php if (!empty($array_datos_libros23[0]->Total_situacion)) {echo $array_datos_libros23[0]->Total_situacion;}else{ echo '0.0';}?></td>
            </tr>            
            <tr>
                <td colspan="16" class="right_titulo_labels"><b>CÁLCULO FINAL SUMATORIA DE DISCAPACIDADES (20%)</b></td>
                <td colspan="1" class="centrar_dato_dinamico"><?php if (!empty($array_datos_libros23[0]->Total_discapacidad)) {echo $array_datos_libros23[0]->Total_discapacidad;}else{ echo '0.0';}?></td>
            </tr>
            <tr>
                <td colspan="17"></td>
            </tr>
            <tr>
                <td colspan="17" class="titulo_tablas">LIBRO III CALIFICACIÓN / VALORACIÓN DE LAS MINUSVALÍAS</td>
            </tr>
            <tr>
                <td colspan="17" class="dato_dinamico">Asigne valor según el grado de minusvalía</td>
            </tr>
            <tr>
                <td colspan="2" class="centrar_titulo_labels">Minusvalía</td>                
                <td colspan="14" class="centrar_titulo_labels">Criterio de Minusvalía</td>                
                <td colspan="1" class="centrar_titulo_labels">Total %</td>
            </tr>
            <tr>
                <td colspan="2" rowspan="1" class="centrar_titulo_labels">Orientación</td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Orientado<br>
                    <?php if (!empty($array_datos_libros23[0]->Orientacion) && $array_datos_libros23[0]->Orientacion == 0.0) {echo $array_datos_libros23[0]->Orientacion;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Compensado<br>
                    <?php if (!empty($array_datos_libros23[0]->Orientacion) && $array_datos_libros23[0]->Orientacion == 0.5) {echo $array_datos_libros23[0]->Orientacion;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Compensado requiere ayuda<br>
                    <?php if (!empty($array_datos_libros23[0]->Orientacion) && $array_datos_libros23[0]->Orientacion == 1.0) {echo $array_datos_libros23[0]->Orientacion;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    No compensado<br>
                    <?php if (!empty($array_datos_libros23[0]->Orientacion) && $array_datos_libros23[0]->Orientacion == 1.5) {echo $array_datos_libros23[0]->Orientacion;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Ausencia<br>
                    <?php if (!empty($array_datos_libros23[0]->Orientacion) && $array_datos_libros23[0]->Orientacion == 2.0) {echo $array_datos_libros23[0]->Orientacion;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Inconsciencia <br>
                    <?php if (!empty($array_datos_libros23[0]->Orientacion) && $array_datos_libros23[0]->Orientacion == 2.5) {echo $array_datos_libros23[0]->Orientacion;}else{ echo '0.0';}?>
                </td>
                <td colspan="2"></td>
                <td colspan="1" rowspan="1" class="centrar_dato_dinamico"><?php if (!empty($array_datos_libros23[0]->Orientacion)) {echo $array_datos_libros23[0]->Orientacion;}else{ echo '0.0';}?></td>
            </tr>
            <tr>
                <td colspan="2" rowspan="1" class="centrar_titulo_labels">Independencia física</td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Independiente<br>
                    <?php if (!empty($array_datos_libros23[0]->Idenpendencia_fisica) && $array_datos_libros23[0]->Idenpendencia_fisica == 0.0) {echo $array_datos_libros23[0]->Idenpendencia_fisica;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Independencia con ayuda<br>
                    <?php if (!empty($array_datos_libros23[0]->Idenpendencia_fisica) && $array_datos_libros23[0]->Idenpendencia_fisica == 0.5) {echo $array_datos_libros23[0]->Idenpendencia_fisica;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Independencia adaptada<br>
                    <?php if (!empty($array_datos_libros23[0]->Idenpendencia_fisica) && $array_datos_libros23[0]->Idenpendencia_fisica == 1.0) {echo $array_datos_libros23[0]->Idenpendencia_fisica;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Dependencia situacional<br>
                    <?php if (!empty($array_datos_libros23[0]->Idenpendencia_fisica) && $array_datos_libros23[0]->Idenpendencia_fisica == 1.5) {echo $array_datos_libros23[0]->Idenpendencia_fisica;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Dependencia asistida<br>
                    <?php if (!empty($array_datos_libros23[0]->Idenpendencia_fisica) && $array_datos_libros23[0]->Idenpendencia_fisica == 2.0) {echo $array_datos_libros23[0]->Idenpendencia_fisica;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Dependencia cuidados esp./perm.<br>
                    <?php if (!empty($array_datos_libros23[0]->Idenpendencia_fisica) && $array_datos_libros23[0]->Idenpendencia_fisica == 2.5) {echo $array_datos_libros23[0]->Idenpendencia_fisica;}else{ echo '0.0';}?>
                </td>
                <td colspan="2"></td>
                <td colspan="1" rowspan="1" class="centrar_dato_dinamico"><?php if (!empty($array_datos_libros23[0]->Idenpendencia_fisica)) {echo $array_datos_libros23[0]->Idenpendencia_fisica;}else{ echo '0.0';}?></td>
            </tr>
            <tr>
                <td colspan="2" rowspan="1" class="centrar_titulo_labels">Desplazamiento</td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Pleno<br>
                    <?php if (!empty($array_datos_libros23[0]->Desplazamiento) && $array_datos_libros23[0]->Desplazamiento == 0.0) {echo $array_datos_libros23[0]->Desplazamiento;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Restricciones intermitentes<br>
                    <?php if (!empty($array_datos_libros23[0]->Desplazamiento) && $array_datos_libros23[0]->Desplazamiento == 0.5) {echo $array_datos_libros23[0]->Desplazamiento;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Deficiente<br>
                    <?php if (!empty($array_datos_libros23[0]->Desplazamiento) && $array_datos_libros23[0]->Desplazamiento == 1.0) {echo $array_datos_libros23[0]->Desplazamiento;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Reducido al ámbito de la vecindad <br>
                    <?php if (!empty($array_datos_libros23[0]->Desplazamiento) && $array_datos_libros23[0]->Desplazamiento == 1.5) {echo $array_datos_libros23[0]->Desplazamiento;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Reducido al ámbito del domicilio<br>
                    <?php if (!empty($array_datos_libros23[0]->Desplazamiento) && $array_datos_libros23[0]->Desplazamiento == 2.0) {echo $array_datos_libros23[0]->Desplazamiento;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Confinami ento silla / cama<br>
                    <?php if (!empty($array_datos_libros23[0]->Desplazamiento) && $array_datos_libros23[0]->Desplazamiento == 2.5) {echo $array_datos_libros23[0]->Desplazamiento;}else{ echo '0.0';}?>
                </td>
                <td colspan="2"></td>
                <td colspan="1" rowspan="1" class="centrar_dato_dinamico"><?php if (!empty($array_datos_libros23[0]->Desplazamiento)) {echo $array_datos_libros23[0]->Desplazamiento;}else{ echo '0.0';}?></td>
            </tr>
            <tr>
                <td colspan="2" rowspan="1" class="centrar_titulo_labels">Ocupacional</td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Habitualmente ocupado<br>
                    <?php if (!empty($array_datos_libros23[0]->Ocupacional) && $array_datos_libros23[0]->Ocupacional == 0.0) {echo $array_datos_libros23[0]->Ocupacional;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Ocupación recortada<br>
                    <?php if (!empty($array_datos_libros23[0]->Ocupacional) && $array_datos_libros23[0]->Ocupacional == 2.5) {echo $array_datos_libros23[0]->Ocupacional;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Ocupación adaptada<br>
                    <?php if (!empty($array_datos_libros23[0]->Ocupacional) && $array_datos_libros23[0]->Ocupacional == 5.0) {echo $array_datos_libros23[0]->Ocupacional;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Cambio de ocupación<br>
                    <?php if (!empty($array_datos_libros23[0]->Ocupacional) && $array_datos_libros23[0]->Ocupacional == 7.5) {echo $array_datos_libros23[0]->Ocupacional;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Ocupación reducida<br>
                    <?php if (!empty($array_datos_libros23[0]->Ocupacional) && $array_datos_libros23[0]->Ocupacional == 10.0) {echo $array_datos_libros23[0]->Ocupacional;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Ocupación restringida<br>
                    <?php if (!empty($array_datos_libros23[0]->Ocupacional) && $array_datos_libros23[0]->Ocupacional == 12.5) {echo $array_datos_libros23[0]->Ocupacional;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Sin posibilidad de ocupación<br>
                    <?php if (!empty($array_datos_libros23[0]->Ocupacional) && $array_datos_libros23[0]->Ocupacional == 15.0) {echo $array_datos_libros23[0]->Ocupacional;}else{ echo '0.0';}?>
                </td>
                <td colspan="1" rowspan="1" class="centrar_dato_dinamico"><?php if (!empty($array_datos_libros23[0]->Ocupacional)) {echo $array_datos_libros23[0]->Ocupacional;}else{ echo '0.0';}?></td>
            </tr>
            <tr>
                <td colspan="2" rowspan="1" class="centrar_titulo_labels">Integración social</td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Socialmente integrado<br>
                    <?php if (!empty($array_datos_libros23[0]->Integracion) && $array_datos_libros23[0]->Integracion == 0.0) {echo $array_datos_libros23[0]->Integracion;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Participación inhibida<br>
                    <?php if (!empty($array_datos_libros23[0]->Integracion) && $array_datos_libros23[0]->Integracion == 0.5) {echo $array_datos_libros23[0]->Integracion;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Participación disminuida<br>
                    <?php if (!empty($array_datos_libros23[0]->Integracion) && $array_datos_libros23[0]->Integracion == 1.0) {echo $array_datos_libros23[0]->Integracion;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Participación empobrecida<br>
                    <?php if (!empty($array_datos_libros23[0]->Integracion) && $array_datos_libros23[0]->Integracion == 1.5) {echo $array_datos_libros23[0]->Integracion;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Relaciones reducidas<br>
                    <?php if (!empty($array_datos_libros23[0]->Integracion) && $array_datos_libros23[0]->Integracion == 2.0) {echo $array_datos_libros23[0]->Integracion;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Aislamiento social<br>
                    <?php if (!empty($array_datos_libros23[0]->Integracion) && $array_datos_libros23[0]->Integracion == 2.5) {echo $array_datos_libros23[0]->Integracion;}else{ echo '0.0';}?>
                </td>
                <td colspan="2"></td>
                <td colspan="1" rowspan="1" class="centrar_dato_dinamico"><?php if (!empty($array_datos_libros23[0]->Integracion)) {echo $array_datos_libros23[0]->Integracion;}else{ echo '0.0';}?></td>
            </tr>
            <tr>
                <td colspan="2" rowspan="1" class="centrar_titulo_labels">Autosuficiencia económica</td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Plenamente autosuficiente<br>
                    <?php if (!empty($array_datos_libros23[0]->Autosuficiencia) && $array_datos_libros23[0]->Autosuficiencia == 0.0) {echo $array_datos_libros23[0]->Autosuficiencia;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Autosuficiente<br>
                    <?php if (!empty($array_datos_libros23[0]->Autosuficiencia) && $array_datos_libros23[0]->Autosuficiencia == 0.5) {echo $array_datos_libros23[0]->Autosuficiencia;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Autosuficiencia reajustada<br>
                    <?php if (!empty($array_datos_libros23[0]->Autosuficiencia) && $array_datos_libros23[0]->Autosuficiencia == 1.0) {echo $array_datos_libros23[0]->Autosuficiencia;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Precariamente autosuficiente<br>
                    <?php if (!empty($array_datos_libros23[0]->Autosuficiencia) && $array_datos_libros23[0]->Autosuficiencia == 1.5) {echo $array_datos_libros23[0]->Autosuficiencia;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Económica.. débil<br>
                    <?php if (!empty($array_datos_libros23[0]->Autosuficiencia) && $array_datos_libros23[0]->Autosuficiencia == 2.0) {echo $array_datos_libros23[0]->Autosuficiencia;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Inactivo económica..<br>
                    <?php if (!empty($array_datos_libros23[0]->Autosuficiencia) && $array_datos_libros23[0]->Autosuficiencia == 2.5) {echo $array_datos_libros23[0]->Autosuficiencia;}else{ echo '0.0';}?>
                </td>
                <td colspan="2"></td>
                <td colspan="1" rowspan="1" class="centrar_dato_dinamico"><?php if (!empty($array_datos_libros23[0]->Autosuficiencia)) {echo $array_datos_libros23[0]->Autosuficiencia;}else{ echo '0.0';}?></td>
            </tr>            
            <tr>
                <td colspan="2" rowspan="1" class="centrar_titulo_labels">Edad cronológica</td>
                <td colspan="2" class="centrar_dato_dinamico">
                    Menor de 18 años<br>
                    <?php if (!empty($array_datos_libros23[0]->Edad_cronologica_menor) && $array_datos_libros23[0]->Edad_cronologica_menor == 2.5) {echo $array_datos_libros23[0]->Edad_cronologica_menor;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    De 18 a 29 años<br>
                    <?php if (!empty($array_datos_libros23[0]->Edad_cronologica_adulto) && $array_datos_libros23[0]->Edad_cronologica_adulto == 1.3) {echo $array_datos_libros23[0]->Edad_cronologica_adulto;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    De 30 a 39 años<br>
                    <?php if (!empty($array_datos_libros23[0]->Edad_cronologica_adulto) && $array_datos_libros23[0]->Edad_cronologica_adulto == 1.8) {echo $array_datos_libros23[0]->Edad_cronologica_adulto;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    De 40 a 49 años<br>
                    <?php if (!empty($array_datos_libros23[0]->Edad_cronologica_adulto) && $array_datos_libros23[0]->Edad_cronologica_adulto == 2) {echo $array_datos_libros23[0]->Edad_cronologica_adulto;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    De 50 a 54 años<br>
                    <?php if (!empty($array_datos_libros23[0]->Edad_cronologica_adulto) && $array_datos_libros23[0]->Edad_cronologica_adulto == 2.3) {echo $array_datos_libros23[0]->Edad_cronologica_adulto;}else{ echo '0.0';}?>
                </td>
                <td colspan="2" class="centrar_dato_dinamico">
                    De 55 o más años<br>
                    <?php if (!empty($array_datos_libros23[0]->Edad_cronologica_adulto) && $array_datos_libros23[0]->Edad_cronologica_adulto == 2.5) {echo $array_datos_libros23[0]->Edad_cronologica_adulto;}else{ echo '0.0';}?>
                </td>
                <td colspan="2"></td>
                <td colspan="1" rowspan="1" class="centrar_dato_dinamico">
                    <?php 
                        if (!empty($array_datos_libros23[0]->Edad_cronologica_menor)) {
                            echo $array_datos_libros23[0]->Edad_cronologica_menor;
                        }elseif(!empty($array_datos_libros23[0]->Edad_cronologica_adulto)){
                            echo $array_datos_libros23[0]->Edad_cronologica_adulto;                            
                        }else {
                            echo '0.0';
                        }
                    ?>
                </td>
            </tr>                       
            <tr>
                <td colspan="16" class="right_titulo_labels"><b>CÁLCULO FINAL SUMATORIA DE MINUSVALÍAS (30%)</b></td>
                <td colspan="1" class="centrar_dato_dinamico"><?php if (!empty($array_datos_libros23[0]->Total_minusvalia)) {echo $array_datos_libros23[0]->Total_minusvalia;}else{ echo '0.0';}?></td>
            </tr>
            <tr>
                <td colspan="17" class="titulo_tablas">7. CONCEPTO FINAL DEL DICTAMEN PERICIAL</td>
            </tr>
            <tr>
                <td colspan="13" class="left_titulo_labels"><b>Pérdida de Capacidad Laboral =  LIBRO I -Deficiencias-Valor Final + LIBRO II -Discapacidades-Valor Final + LIBRO III -Minusvalías-Valor Final</b></td>
                <td colspan="4" class="centrar_dato_dinamico"><b>{{$Total_deficiencia50_fc}}% + <?php if (!empty($array_datos_libros23[0]->Total_discapacidad)) {echo $array_datos_libros23[0]->Total_discapacidad;}else{ echo '0.0';}?>% + <?php if (!empty($array_datos_libros23[0]->Total_minusvalia)) {echo $array_datos_libros23[0]->Total_minusvalia;}else{ echo '0.0';}?>%</b></td>
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
                <td colspan="4" class="left_titulo_labels">Alto costo / Catastrófica:</td>
                <td colspan="5" class="dato_dinamico"><?php if ($Enfermedad_catastrofica_dp == 'Enfermedad Catastrófica'){echo 'SI';}else{echo 'NO';}?></td>
                <td colspan="3" class="left_titulo_labels">Congénita o cercana a nacimiento:</td>
                <td colspan="5" class="dato_dinamico"><?php if ($Enfermedad_congenita_dp == 'Enfermedad Congénita o cercana al nacimiento'){echo 'SI';}else{echo 'NO';}?></td>
            </tr>
            <tr>
                <td colspan="4" class="left_titulo_labels">Revisión pensión:</td>
                {{-- <td colspan="5" class="dato_dinamico"><?php if ($Revision_pension_dp == 8) {echo 'X';} else { echo '';}?></td> --}}
                <td colspan="5" class="dato_dinamico"><?php if ($Revision_pension_dp == 'Require Revision Pension') {echo 'SI';} else { echo 'NO';}?></td>
                <td colspan="3" class="left_titulo_labels">Tipo de enfermedad / Deficiencia:</td>
                <td colspan="5" class="dato_dinamico"><?php if (!empty($Nombre_enfermedad_dp)){ echo $Nombre_enfermedad_dp;} else {echo 'N/A';}?></td>
            </tr>
            <tr>
                <td colspan="17" class="centrar_dato_labels"><b>Clasificación condición de salud - Tipo de enfermedad</b></td>
            </tr>
            <tr>
                <td colspan="16" class="right_titulo_labels"><b>Requiere de terceras personas para realizar sus actividades de la vida diaria (áreas ocupacionales):</b></td>
                <td colspan="1" class="dato_dinamico"><?php if ($Requiere_tercera_persona_dp == 'Requiere tercera persona') {echo 'SI';} else {echo 'No';}?></td>
            </tr>
            <tr>
                <td colspan="16" class="right_titulo_labels"><b>Se requiere de curador para la toma de decisiones:</b></td>
                <td colspan="1" class="dato_dinamico"><?php if ($Requiere_tercera_persona_decisiones_dp == 'Requiere de tercera persona para la toma de decisiones') {echo 'SI';} else {echo 'No';}?></td>
            </tr>
            <tr>
                <td colspan="16" class="right_titulo_labels"><b>Requiere de dispositivo de apoyo para realizar actividades de la vida diaria (áreas ocupacionales):</b></td>
                <td colspan="1" class="dato_dinamico"><?php if ($Requiere_dispositivo_apoyo_dp == 'Requiere de dispositivo de apoyo') {echo 'SI';} else {echo 'No';}?></td>
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