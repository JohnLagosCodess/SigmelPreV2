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
            /* esta ligado con el tercer valor del margin */
            bottom: -2.4cm;
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
        .tabla_cuerpo {
            font-family: sans-serif;
            text-align: center;
            width: 100%;
            table-layout: fixed; 
            border-collapse: collapse;
        }

        .tabla_cuerpo, .tabla_cuerpo td, .tabla_cuerpo th {
            border: 1px solid black;
            border-collapse: collapse;
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
                    {{-- <td>
                        <img src="data:image/png;base64,{{ base64_encode($codigoQR) }}" class="codigo_qr" alt="Código QR">
                    </td> --}}
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
            <p class="page" style="color: black;">Página </p>
        <?php else: ?>
            <?php 
                $ruta_footer = "/footer_clientes/{$Id_cliente_ent}/{$footer}";
                $footer_path = public_path($ruta_footer);
                $footer_data = file_get_contents($footer_path);
                $footer_base64 = base64_encode($footer_data);
            ?>
            <div class="footer_content">
                <img src="data:image/png;base64,{{ $footer_base64 }}" class="footer_image">
                <p class="page" style="color: black;">Página </p>
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
                $patron1 = '/\{\{\$PorcentajePcl_dp\}\}/'; 
                $patron2 = '/\{\{\$F_estructuracionPcl_dp\}\}/'; 
                $patron3 = '/\{\{\$OrigenPcl_dp\}\}/'; 
                $patron4 = '/\{\{\$Detalle_calificacion_Fbdp\}\}/';   
                if (preg_match($patron1, $Cuerpo_comunicado_correspondencia) && preg_match($patron2, $Cuerpo_comunicado_correspondencia) 
                    && preg_match($patron3, $Cuerpo_comunicado_correspondencia) && preg_match($patron4, $Cuerpo_comunicado_correspondencia)) {                    
                    $texto_modificado = str_replace('{{$PorcentajePcl_dp}}', '<b>'.$PorcentajePcl_dp.'</b>', $Cuerpo_comunicado_correspondencia);
                    $texto_modificado = str_replace('{{$F_estructuracionPcl_dp}}', '<b>'.$F_estructuracionPcl_dp.'</b>', $texto_modificado);
                    $texto_modificado = str_replace('{{$OrigenPcl_dp}}', '<b>'.$OrigenPcl_dp.'</b>', $texto_modificado);
                    $texto_modificado = str_replace('{{$Detalle_calificacion_Fbdp}}', $Detalle_calificacion_Fbdp, $texto_modificado);
                    $Cuerpo_comunicado_correspondencia = $texto_modificado;
                } else {
                    $Cuerpo_comunicado_correspondencia = "";
                }                
                print_r($Cuerpo_comunicado_correspondencia);
            ?>
        </section>
        <p class="fuente_todo_texto" style="text-align: justify;">
            Se realiza la calificación de la pérdida de capacidad laboral con base en el decreto {{$Nombre_decreto_dp}}.
        </p>
        <table class="tabla1">            
            <tbody>
                @if (count($deficiencias_calculadas_factores) > 0) 
                    <tr>
                        <td class="fuente_todo_texto">Se revisa la calificación de la pérdida de capacidad laboral:</td>
                    </tr>                   
                    <tr>
                        <td class="fuente_todo_texto">Título I.</td>
                    </tr>
                    @foreach ($deficiencias_calculadas_factores as $deficiencias)
                        <tr>
                            <td class="fuente_todo_texto"><?php echo $deficiencias->Ident_tabla.' '.$deficiencias->Nombre_tabla.' '.$deficiencias->Clase_Final.' - '.$deficiencias->Deficiencia ?></td>                           
                        </tr>                
                    @endforeach                
                @else
                    <tr>
                        <td class="fuente_todo_texto">Se revisa la calificación de la pérdida de capacidad laboral:</td>
                    </tr>                   
                    <tr>
                        <td class="fuente_todo_texto">Título I. No se registran Deficiencias por Alteraciones de los Sistemas Generales cálculadas por factores</td>
                    </tr>                                      
                @endif
            </tbody>
        </table>
        <table class="tabla1">            
            <tbody>
                <tr>
                    <td class="fuente_todo_texto">Deficiencia total: {{$Total_Deficiencia50_dp}}%. Valor ponderado: {{$Suma_combinada_dp}}</td>
                </tr>
                <tr>
                    <td class="fuente_todo_texto">Título II: Rol laboral con restricciones completas: {{$total_rol_ocupacional50}}</td>
                </tr>
                <tr>
                    <td class="fuente_todo_texto">Autosuficiencia económica reajustada: {{$Total_rol_laboral}} %</td>
                </tr>
                <tr>
                    <td class="fuente_todo_texto">Edad {{$Edad_afiliado}} años: {{$total_edad_cronologica}}</td>
                </tr>
                <tr>
                    <td class="fuente_todo_texto">Otras áreas ocupacionales: {{$Total_otras_areas}} %</td>
                </tr>
                <tr>
                    <td class="fuente_todo_texto">Total título II: {{$Total_laboral_otras_areas}} %</td>
                </tr>
                <tr>
                    <td class="fuente_todo_texto">Total calificación de pérdida de capacidad laboral: {{$PorcentajePcl_dp}} %.</td>
                </tr>
            </tbody>
        </table>        
        <p class="fuente_todo_texto" style="text-align: justify;">
            Como puede observarse, usted ahora se encuentra dentro del porcentaje establecido en el literal B del Artículo 40 de la Ley 100 de 1993, 
            por lo anterior, esta Aseguradora procederá a seguir efectuando el pago de su mesada pensional en los términos establecidos en la norma 
            anteriormente mencionada.
        </p>
        <p class="fuente_todo_texto" style="text-align: justify;">
            De la misma manera nos permitimos informar que de no encontrarse de acuerdo con la calificación de su estado de invalidez, usted tiene
            la posibilidad de manifestar a Seguros de Vida Alfa S.A. su inconformidad dentro de los diez (10) días siguientes a partir de la fecha
            de recibida la notificación, evento en el cual procederemos a remitir su caso a la respectiva Junta Regional de Calificación de Invalidez 
            para obtener una segunda calificación.
        </p>
        <p class="fuente_todo_texto" style="text-align: justify;">
            Cualquier inquietud o consulta al respecto, le invitamos a comunicarse a nuestras líneas de atención al cliente en Bogotá (601) 3 07 70 32
            o a la línea nacional gratuita 01 8000 122 532, de lunes a viernes, de 8:00 a. m. a 8:00 p. m. - sábados de 8:00 a.m. a 12 m., o escribanos
            a «servicio al cliente@segurosalfa.com.co» o a la dirección Carrera 10 # 18-36 piso 4 Edificio Jose maria Cordoba, Bogota D.C.
        </p>    
        <section class="fuente_todo_texto">
            Cordialmente,
            <div class="firma">
                <?=$Firma_cliente?>
            </div>
        </section>   
        <p class="fuente_todo_texto" style="text-align: justify;">
            Departamento de medicina laboral <br>
            Convenio Seguro de Vida Alfa <br>
            Seguro alfa S.A. y Seguro de Vida Alfa S.A.
        </p>
        <section>        
            <div class="fuente_todo_texto">                
                <b>Anexos:</b> {{$Anexos_correspondecia}}
                <br>
                <b>Elaboró:</b> {{$Elaboro_correspondecia}}
            </div>
        </section>          
        <section class="fuente_todo_texto">
            <table class="tabla1" style="text-align: justify;">                               
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
    </div>
</body>
</html>