<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    
    <style>
        @page{
            margin: 3cm 1.3cm 2.5cm 1.3cm;
        }
        #header {
            position: fixed; 
            top: -2.8cm;
            left: 0cm;
            width: 100%;
            text-align: right; 
        }
        .codigo_qr{
            position: absolute;
            top: 5px; 
            left: 5px; 
            max-width: 90px; 
            max-height: 70px; 
        }
        .logo_header{
            position: absolute;
            max-width: 40%;
            height: auto;
            left: 530px;
            max-height: 80px; 
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
        #footer{
            position: fixed;
            /* esta ligado con el tercer valor del margin */
            bottom: -3cm;
            left: 0cm;
            width: 100%;
            height: 14%;

            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center; 
        }
        #footer .page{
            text-align: center;
        }
        .footer_image{
            max-width: 100%;
            max-height: 80%;
            margin-bottom: -5px;
        }
        .footer_content {
            position: relative;
            text-align: center;
        }

        #footer .page:after { content: counter(page, upper-decimal); } 

        #footer2 { 
            position: fixed; 
            left: -20px; 
            right: 0px; 
            width: 0px; 
            height: 0px; 
            color:black; 
            background-color: white; 
            transform: rotate(0deg); 
            top:300px;
        }
        .logo_footer{
            width: auto;
            height: 150px;
        }
        .tabla_footer{
            width: 100%;
            font-family: sf-pro-display-black, sans-serif;
            font-size: 12px;
        }
        .color_letras_alfa{
            color: #184F56;
            font-weight: bold;
        }
        .negrita{
            font-weight: bold;
        }
        .fuente_todo_texto{
            font-family: sans-serif;
            font-size: 12px;
        }        
        .tabla1{
            width: 100%;            
            margin-left: -3.5px;
        }
        .tabla2{
            width: 100%;
            margin-left: -3.5px;
        }
        section{
            text-align: justify;
        }
        .container{
            margin-top: -0.5cm;
            margin-left: 1.5cm;
            margin-right: 1.5cm;
        }
        .cuadro{
            border: 3px solid black;
            padding-left: 6px;  
            width: 5cm;
            height: 2cm;
        }      
        .tabla_cuerpo, .tabla_cuerpo td, .tabla_cuerpo th {
            border: 1px solid black;
            border-collapse: collapse;
        }  
    </style>
</head>
<body>
    <div id="header">    
        <table class="tabla_header">
            <tbody>
                <tr>
                    <td>
                        <img src="data:image/png;base64,{{ base64_encode($codigoQR) }}" class="codigo_qr" alt="Código QR">
                    </td>                    
                    <td>
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
        <?php if($footer == null): ?>
            <div style="text-align:center;">
                <span style="color: #3C3C3C; margin-top:2px;">{{$Nombre_afiliado}} - {{$T_documento_noti}} {{$NroIden_afiliado_noti}} - Siniestro: {{$N_siniestro}} </span>
            </div>
        <?php else: ?>
            <?php 
                $ruta_footer = "/footer_clientes/{$Id_cliente_ent}/{$footer}";
                $footer_path = public_path($ruta_footer);
                $footer_data = file_get_contents($footer_path);
                $footer_base64 = base64_encode($footer_data);
            ?>
            <div class="footer_content">
                <span style="color: #3C3C3C; margin-top:2px;">{{$Nombre_afiliado}} - {{$T_documento_noti}} {{$NroIden_afiliado_noti}} - Siniestro: {{$N_siniestro}} </span>
                <img src="data:image/png;base64,{{ $footer_base64 }}" class="footer_image">
            </div>
        <?php endif ?>
    </div>
    <div id="footer2">
        <?php
            $imagenPath_footer = public_path('/images/logos_preformas/vigilado.png');
            $imagenData_footer = file_get_contents($imagenPath_footer);
            $imagenBase64_footer = base64_encode($imagenData_footer);
        ?>
        <img src="data:image/png;base64,{{ $imagenBase64_footer }}" class="logo_footer">
    </div>
    <div class="container">
        <p class="fuente_todo_texto">{{$Ciudad_correspondencia}}, {{$F_correspondecia}}</p>
        <table class="tabla2">                        
            <tbody>
                <tr>
                    <td style="width:100%;">
                        <span class="fuente_todo_texto"><span class="negrita">Señor(a): </span><br>{{$Nombre_afiliado}}</span><br>
                        <span class="fuente_todo_texto">{{$Email_afiliado_noti}}</span><br>
                        <span class="fuente_todo_texto">{{$direccion_destinatario_principal}}</span><br>
                        <span class="fuente_todo_texto">{{$telefono_destinatario_principal}}</span><br>
                        <span class="fuente_todo_texto">{{$ciudad_destinatario_principal}}</span>
                    </td>
                    <td>
                        <div class="cuadro">
                            <span class="fuente_todo_texto"><span class="negrita">Nro. Radicado: <br>{{$Radicado_comuni}}</span></span><br>
                            <span class="fuente_todo_texto"><span class="negrita">{{$T_documento_noti}} {{$NroIden_afiliado_noti}}</span></span><br>
                            <span class="fuente_todo_texto"><span class="negrita">Siniestro: {{$N_siniestro}}</span></span><br>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <table class="tabla1">
            <tbody>
                <tr>
                    <td class="fuente_todo_texto">
                        <span class="negrita">Asunto: {{$Asunto_correspondencia}}</span><br> 
                        <div style="margin-left: 3cm;">
                            <span class="negrita">Ramo:</span> Previsionales<br>                        
                            {{$T_documento_noti.' '.$NroIden_afiliado_noti}}<br>
                            <span class="negrita">Siniestro: </span>{{$N_siniestro}}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <section class="fuente_todo_texto">            
            <?php
                $patron1 = '/\{\{\$Nombre_afiliado\}\}/';   
                $patron2 = '/\{\{\$PorcentajePcl_dp\}\}/'; 
                $patron3 = '/\{\{\$F_estructuracionPcl_dp\}\}/'; 
                $patron4 = '/\{\{\$OrigenPcl_dp\}\}/'; 
                if (preg_match($patron1, $Cuerpo_comunicado_correspondencia) && preg_match($patron2, $Cuerpo_comunicado_correspondencia) 
                    && preg_match($patron3, $Cuerpo_comunicado_correspondencia) && preg_match($patron4, $Cuerpo_comunicado_correspondencia)) {                    
                    $texto_modificado = str_replace('{{$Nombre_afiliado}}', $Nombre_afiliado, $Cuerpo_comunicado_correspondencia);
                    $texto_modificado = str_replace('{{$PorcentajePcl_dp}}', '<b>'.$PorcentajePcl_dp.'%'.'</b>', $texto_modificado);
                    $texto_modificado = str_replace('{{$F_estructuracionPcl_dp}}', '<b>'.$F_estructuracionPcl_dp.'</b>', $texto_modificado);
                    $texto_modificado = str_replace('{{$OrigenPcl_dp}}', '<b>'.strtoupper($OrigenPcl_dp).'</b>', $texto_modificado);
                    $Cuerpo_comunicado_correspondencia = $texto_modificado;
                } else {
                    $Cuerpo_comunicado_correspondencia = "";
                }                
                print_r($Cuerpo_comunicado_correspondencia);
            ?>
        </section>
        <section class="fuente_todo_texto">
            Para nosotros es un gusto servirle,
            <br>
            Cordialmente,
            <div class="firma">
                <?=$Firma_cliente?>
            </div>
            <div class="fuente_todo_texto">
                Departamento de medicina laboral 
                <br>
                Convenio Seguro de Vida Alfa
                <br>
                Seguro alfa S.A. y Seguro de Vida Alfa S.A.
            </div>
            {{-- <div class="fuente_todo_texto">
                <b>Anexos:</b> {{$Anexos_correspondecia}}
                <br>
                <b>Elaboró:</b> {{$Elaboro_correspondecia}}
            </div> --}}
        </section>  
        <p class="fuente_todo_texto" style="text-align: justify;">
            1 Según lo establecido en el Artículo 52 de la Ley 962 de 2005. <br>
            2 Decreto 1507 de 2014 <br>
            3 De acuerdo con lo establecido en el Artículo 38 de la Ley 100 de 1993 <br>
            4 Requisitos legales para acceder a la pensión por invalidez (Artículo 39 de la Ley 100 de 1993)
        </p>
        <p class="fuente_todo_texto" style="text-align: justify; font-size:11px">
            A través del Defensor del Consumidor Financiero, como vocero de los clientes, podrán ser atendidas las peticiones o requerimientos 
            referentes a los productos o servicios prestados por las Compañías, los cuales deberán ser radicados utilizando alguno de los medios 
            señalados a continuación: Correo electrónico: <br><a href="mailto:defensordelconsumidorfinanciero@segurosalfa.com.co">defensordelconsumidorfinanciero@segurosalfa.com.co</a>, dirección física: AV. CL. 26 59-15 locales 6 y 7, 
            dirección de correspondencia CRA. 10 # 18-36 Piso 4, Edificio José María Córdoba o vía fax al conmutador 743 53 33 Ext 14454 
            Fax: 743 53 33 Ext. 14440.
        </p>            
        <p class="fuente_todo_texto" style="text-align: justify;">
            * Para una adecuada apertura y visualización de los archivos anexos, se recomienda que sean 
            descargados en un equipo de cómputo, no desde celulares.
        </p>
        <section class="fuente_todo_texto">
            <table class="tabla1" style="text-align: justify;">                               
                @if (empty($Copia_afiliado_correspondencia) && empty($Copia_empleador_correspondecia) && empty($Copia_eps_correspondecia) && empty($Copia_afp_correspondecia) && empty($Copia_arl_correspondecia))
                    <tr>
                        <td><span class="negrita">Copia: </span>No se registran copias</td>                                                                                
                    </tr>
                @else
                    <tr>
                        <td class="justificado"><span class="negrita">Copia:</span></td>                            
                    </tr>  
                    <?php 
                        if (!empty($Copia_afiliado_correspondencia)) { ?>
                            <tr>
                                <td>
                                    <span class="negrita">Afiliado: </span><?php echo $Nombre_afiliado_copia.' - '.$Direccion_afiliado_copia.'; '.$Email_afiliado_noti.', Teléfono: '.$Telefono_afiliado_copia.', '.$Ciudad_departamento_afiliado_copia;?>
                                </td>
                            </tr>
                        <?php       
                        }
                    ?>  
                    <?php 
                        if (!empty($Copia_empleador_correspondecia)) { ?>
                            <tr>
                                <td>
                                    <span class="negrita">Empresa: </span><?php echo $copiaNombre_empresa_noti.' - '.$copiaDireccion_empresa_noti.'; '.$copiaEmail_empresa_noti.', Teléfono: '.$copiaTelefono_empresa_noti.', '.$copiaCiudad_departamento_empresa_noti;?>
                                </td>
                            </tr>
                        <?php       
                        }
                    ?>                  
                    <?php 
                        if (!empty($Copia_eps_correspondecia)) { ?>
                            <tr>
                                <td>
                                    <span class="negrita">EPS: </span><?php echo $Nombre_eps.' - '.$Direccion_eps.'; '.$Email_eps.', Teléfono: '.$Telefono_eps.', '.$Ciudad_departamento_eps;?>
                                </td>
                            </tr>
                        <?php       
                        }
                    ?>
                    <?php 
                        if (!empty($Copia_afp_correspondecia)) { ?>
                            <tr>
                                <td class="copias">
                                    <span class="negrita">AFP: </span><?php echo $Nombre_afp.' - '.$Direccion_afp.'; '.$Email_afp.', Teléfono: '.$Telefono_afp.', '.$Ciudad_departamento_afp;?>
                                </td>
                            </tr>
                        <?php       
                        }
                    ?>
                    <?php 
                        if (!empty($Copia_afp_conocimiento_correspondencia)) { ?>
                            <tr>
                                <td class="copias">
                                    <span class="negrita">AFP Conocimiento: </span><?php echo $Nombre_afp_conocimiento.' - '.$Direccion_afp_conocimiento.'; '.$Email_afp_conocimiento.', Teléfono: '.$Telefonos_afp_conocimiento.', '.$Ciudad_departamento_afp_conocimiento;?>
                                </td>
                            </tr>
                        <?php       
                        }
                    ?>
                    <?php 
                        if (!empty($Copia_arl_correspondecia)) { ?>
                            <tr>
                                <td class="copias">
                                    <span class="negrita">ARL: </span><?php echo $Nombre_arl.' - '.$Direccion_arl.'; '.$Email_arl.', Teléfono: '.$Telefono_arl.', '.$Ciudad_departamento_arl;?>
                                </td>
                            </tr>
                        <?php       
                        }
                    ?>                    
                @endif
            </table>
        </section>               
    </div>
    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->text(485, 70, "Página $PAGE_NUM de $PAGE_COUNT", $font, 10);
            ');
        }
	</script>
</body>
</html>