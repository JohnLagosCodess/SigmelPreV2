<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    
    <style>
        @page{
            margin: 2.5cm 1.3cm 2.5cm 1.3cm;
        }
        #header {
            position: fixed; 
            top: -2.2cm;
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
            bottom: -2.4cm;
            left: 0cm;
            width: 100%;
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
        <table class="tabla_footer">
            <tbody>
                <tr>
                    <td colspan="2" class="negrita" style="text-align: center;"><?php echo "{$Nombre_afiliado_pie} - {$T_documento_noti} ({$NroIden_afiliado_noti}) - Siniestro ({$ID_evento})"; ?></td>
                </tr>
                <tr>
                    <td colspan="2" class="color_letras_alfa1">{{$footer_dato_1}}</td>
                </tr>
                <tr>
                    <td class="color_letras_alfa1">{{$footer_dato_2}}</td>
                    <td style="text-align: right;" class="color_letras_alfa1">{{$footer_dato_3}}</td>
                </tr>
                <tr>
                    <td colspan="2">{{$footer_dato_4}}</td>
                </tr>
                <tr>
                    <td colspan="2">{{$footer_dato_5}}</td>
                </tr>
                <tr>
                    <td style="text-align: center;" colspan="2"><span class="page">Página </span></td>
                </tr>
            </tbody>
        </table>
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
                    <td>
                        <span class="fuente_todo_texto"><span class="negrita">Señor(a): </span>{{$Nombre_afiliado}}</span><br>
                        <span class="fuente_todo_texto"><span class="negrita">Dirección: </span>{{$direccion_destinatario_principal}}</span><br>
                        <span class="fuente_todo_texto"><span class="negrita">Teléfono: </span>{{$telefono_destinatario_principal}}</span><br>
                        <span class="fuente_todo_texto"><span class="negrita">Ciudad: </span>{{$ciudad_destinatario_principal}}</span>
                    </td>
                    <td>
                        <div class="cuadro">
                            <span class="fuente_todo_texto"><span class="negrita">Nro. Radicado {{$Radicado_comuni}}</span></span><br>
                            <span class="fuente_todo_texto"><span class="negrita">{{$T_documento_noti}} {{$NroIden_afiliado_noti}}</span></span><br>
                            <span class="fuente_todo_texto"><span class="negrita">Siniestro: {{$ID_evento}}</span></span><br>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="tabla1">
            <tbody>
                <tr>
                    <td class="fuente_todo_texto">
                        <span class="negrita">Asunto: {{$Asunto_correspondencia}}</span><br> 
                        <span class="negrita">Ramo:</span> Previsionales<br>                        
                        {{$T_documento_noti.' '.$NroIden_afiliado_noti}}<br>
                        <span class="negrita">Siniestro: </span>{{$ID_evento}}
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
                    $texto_modificado = str_replace('{{$OrigenPcl_dp}}', '<b>'.$OrigenPcl_dp.'</b>', $texto_modificado);
                    $Cuerpo_comunicado_correspondencia = $texto_modificado;
                } else {
                    $Cuerpo_comunicado_correspondencia = "";
                }                
                print_r($Cuerpo_comunicado_correspondencia);
            ?>
        </section>
        <section class="fuente_todo_texto">
            Para nosotros es un gusto servirle,
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
            <div class="fuente_todo_texto">
                <b>Anexos:</b> {{$Anexos_correspondecia}}
                <br>
                <b>Elaboró:</b> {{$Elaboro_correspondecia}}
            </div>
        </section>  
        <p class="fuente_todo_texto" style="text-align: justify;">
            1 Según lo establecido en el Artículo 52 de la Ley 962 de 2005. <br>
            2 Decreto 1507 de 2014 <br>
            3 De acuerdo con lo establecido en el Artículo 38 de la Ley 100 de 1993 <br>
            4 Requisitos legales para acceder a la pensión por invalidez (Artículo 39 de la Ley 100 de 1993)
        </p>
        <p class="fuente_todo_texto" style="text-align: justify;">
            A través del Defensor del Consumidor Financiero, como vocero de los clientes, podr án ser atendidas las peticiones o requerimientos 
            referentes a los productos o servicios prestados por las Compañías, los cuales deberán ser radicados utilizando alguno de los medios 
            señalados a continuación: Correo electrónico: defensordelconsumidorfinanciero@segurosalfa.com.co, 
            defensordelconsumidorfinanciero@segurodevidasalfa.com.co dirección física: AV. CL. 26 59-15 locales 6 y 7, 
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
                                    <span class="negrita">Afiliado: </span><?php echo $Nombre_afiliado_copia.' - '.$Direccion_afiliado_copia.', Teléfono: '.$Telefono_afiliado_copia.', '.$Ciudad_departamento_afiliado_copia;?>
                                </td>
                            </tr>
                        <?php       
                        }
                    ?>  
                    <?php 
                        if (!empty($Copia_empleador_correspondecia)) { ?>
                            <tr>
                                <td>
                                    <span class="negrita">Empresa: </span><?php echo $copiaNombre_empresa_noti.' - '.$copiaDireccion_empresa_noti.', Teléfono: '.$copiaTelefono_empresa_noti.', '.$copiaCiudad_departamento_empresa_noti;?>
                                </td>
                            </tr>
                        <?php       
                        }
                    ?>                  
                    <?php 
                        if (!empty($Copia_eps_correspondecia)) { ?>
                            <tr>
                                <td>
                                    <span class="negrita">EPS: </span><?php echo $Nombre_eps.' - '.$Direccion_eps.', Teléfono: '.$Telefono_eps.', '.$Ciudad_departamento_eps;?>
                                </td>
                            </tr>
                        <?php       
                        }
                    ?>
                    <?php 
                        if (!empty($Copia_afp_correspondecia)) { ?>
                            <tr>
                                <td class="copias">
                                    <span class="negrita">AFP: </span><?php echo $Nombre_afp.' - '.$Direccion_afp.', Teléfono: '.$Telefono_afp.', '.$Ciudad_departamento_afp;?>
                                </td>
                            </tr>
                        <?php       
                        }
                    ?>
                    <?php 
                        if (!empty($Copia_afp_conocimiento_correspondencia)) { ?>
                            <tr>
                                <td class="copias">
                                    <span class="negrita">AFP Conocimiento: </span><?php echo $Nombre_afp_conocimiento.' - '.$Direccion_afp_conocimiento.', Teléfono: '.$Telefonos_afp_conocimiento.', '.$Ciudad_departamento_afp_conocimiento;?>
                                </td>
                            </tr>
                        <?php       
                        }
                    ?>
                    <?php 
                        if (!empty($Copia_arl_correspondecia)) { ?>
                            <tr>
                                <td class="copias">
                                    <span class="negrita">ARL: </span><?php echo $Nombre_arl.' - '.$Direccion_arl.', Teléfono: '.$Telefono_arl.', '.$Ciudad_departamento_arl;?>
                                </td>
                            </tr>
                        <?php       
                        }
                    ?>                    
                @endif
            </table>
        </section>               
    </div>
</body>
</html>