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
        .logo_header{
            width: 150px;
            height: auto;
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
            font-family: Arial;
            font-size: 15px;
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
    </div>
    <div id="footer">
        <table class="tabla_footer">            
            <tbody>
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
                    <td style="text-align: center;" colspan="2"><p class="page">Página </p></td>
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
                        <span class="fuente_todo_texto"><span class="negrita">Señor(a): </span>{{$Nombre_afiliado_noti}}</span><br>
                        <span class="fuente_todo_texto"><span class="negrita">Dirección: </span>{{$Direccion_afiliado_noti}}</span><br>
                        <span class="fuente_todo_texto"><span class="negrita">Teléfono: </span>{{$Telefono_afiliado_noti}}</span><br>
                        <span class="fuente_todo_texto"><span class="negrita">Ciudad: </span>{{$Ciudad_afiliado_noti}} - {{$Departamento_afiliado_noti}}</span>
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
                        <span class="negrita">Caso: </span>{{$ID_evento}}<br>
                        <span class="negrita">Identificación: </span>{{$NroIden_afiliado_noti}}
                    </td>
                </tr>
            </tbody>
        </table>
        <section class="fuente_todo_texto">            
            <?php
                $patron1 = '/\{\{\$PorcentajePcl_cero\}\}/';
                $patron2 = '/\{\{\$CIE10Nombres_cero\}\}/';
                if (preg_match($patron1, $Cuerpo_comunicado_correspondencia) && preg_match($patron2, $Cuerpo_comunicado_correspondencia)) {                    
                    $texto_modificado = str_replace('{{$PorcentajePcl_cero}}', '<b>'.$PorcentajePcl_cero.'%'.'</b>', $Cuerpo_comunicado_correspondencia);
                    $texto_modificado = str_replace('{{$CIE10Nombres_cero}}', '<b>'.$CIE10Nombres_cero.'</b>', $texto_modificado);
                    $Cuerpo_comunicado_correspondencia = $texto_modificado;
                } else {
                    $Cuerpo_comunicado_correspondencia = "";
                }                
                print_r($Cuerpo_comunicado_correspondencia);
            ?>
        </section>
        <section class="fuente_todo_texto">
            Cordialmente,
            <div class="firma">
                <?=$Firma_cliente?>
            </div>
            <div class="fuente_todo_texto">
                Dirección de Servicios Médicos de Seguridad Social
                <br>
                Convenio Codess Seguros de Vida  Alfa S.A
            </div>
            <div class="fuente_todo_texto">
                <b>Anexos:</b> {{$Anexos_correspondecia}}
                <br>
                <b>Elaboró:</b> {{$Elaboro_correspondecia}}
            </div>
        </section>
        <section class="fuente_todo_texto">            
            <table class="tabla1" style="text-align: justify;">    
                {{$Copia_eps_correspondecia}}            
                @if (empty($Copia_empleador_correspondecia) && empty($Copia_eps_correspondecia) && empty($Copia_afp_correspondecia) && empty($Copia_arl_correspondecia))
                    <tr>
                        <td><span class="negrita">Copia: </span>No se registran copias</td>                                                                                
                    </tr>
                @else
                    <tr>
                        <td class="justificado"><span class="negrita">Copia:</span></td>                            
                    </tr>  
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
        <p class="fuente_todo_texto" style="color: #828282;">
            “Finalmente, reiteramos que en nuestra Compañía contamos con la mejor disposición para atender sus quejas y
            reclamos a través del defensor consumidor financiero, en la Av. Calle 26 No 59-15, local 6 y 7. Conmutador:
            7435333 Extensión: 14454, Fax Ext. 14456 o Correo Electrónico:
            defensor del consumidor financiero@segurosdevidaalfa.com.co”.
        </p>
    </div>
</body>
</html>