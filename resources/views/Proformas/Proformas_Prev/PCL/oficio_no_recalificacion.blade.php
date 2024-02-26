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

        .cuerpo_doc_revPen {
            padding-left: 25px;
            padding-right: 25px;
            text-align: justify;
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
                                $ruta_logo = "/logos_clientes/{$id_cliente}/{$logo_header}";
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
                    <td colspan="2" class="negrita" style="text-align: center;"><?php echo "{$Nombre_afiliado} - {$T_documento} ({$N_identificacion}) - Siniestro ({$nro_siniestro})"; ?></td>
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
        <p class="fuente_todo_texto">{{$ciudad}}, {{$fecha}}</p>
        <table class="tabla2">                        
            <tbody>
                <tr>
                    <td>
                        <span class="fuente_todo_texto"><span class="negrita">Señor(a): </span>{{$nombre}}</span><br>
                        <span class="fuente_todo_texto"><span class="negrita">Dirección: </span>{{$direccion}}</span><br>
                        <span class="fuente_todo_texto"><span class="negrita">Teléfono: </span>{{$telefono}}</span><br>
                        <span class="fuente_todo_texto"><span class="negrita">Ciudad: </span>{{$municipio.' - '.$departamento}}</span>
                    </td>
                    <td>
                        <div class="cuadro">
                            <span class="fuente_todo_texto"><span class="negrita">Nro. Radicado {{$nro_radicado}}</span></span><br>
                            <span class="fuente_todo_texto"><span class="negrita">{{$tipo_identificacion.' '.$num_identificacion}}</span></span><br>
                            <span class="fuente_todo_texto"><span class="negrita">Siniestro: {{$nro_siniestro}}</span></span><br>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="tabla1">
            <tbody>
                <tr>
                    <td class="fuente_todo_texto">
                        <span class="negrita">Asunto: {{$asunto}}</span><br> 
                        <span class="negrita">Ramo:</span> Previsionales<br>                        
                        {{$tipo_identificacion.' '.$num_identificacion}}<br>
                        <span class="negrita">Siniestro: </span>{{$nro_siniestro}}
                    </td>
                </tr>
            </tbody>
        </table>
        <section class="fuente_todo_texto">                
            <?php       
                $patron1 = '/\{\{\$OrigenPcl_dp\}\}/';   
                $patron2 = '/\{\{\$CIE10Nombres\}\}/'; 
                $patron3 = '/\{\{\$PorcentajePcl_dp\}\}/'; 
                $patron4 = '/\{\{\$F_estructuracionPcl_dp\}\}/'; 
                if (preg_match($patron1, $cuerpo) && preg_match($patron2, $cuerpo) 
                    && preg_match($patron3, $cuerpo) && preg_match($patron4, $cuerpo)) {                    
                    $texto_modificado = str_replace('{{$OrigenPcl_dp}}', '<b>'.$OrigenPcl_dp.'</b>', $cuerpo);
                    $texto_modificado = str_replace('{{$CIE10Nombres}}', $CIE10Nombres, $texto_modificado);
                    $texto_modificado = str_replace('{{$PorcentajePcl_dp}}', '<b>'.$PorcentajePcl_dp.'</b>', $texto_modificado);
                    $texto_modificado = str_replace('{{$F_estructuracionPcl_dp}}', '<b>'.$F_estructuracionPcl_dp.'</b>', $texto_modificado);
                    $cuerpo = $texto_modificado;
                } else {
                    $cuerpo = "";
                }                
                print_r($cuerpo);  
            ?>
        </section>
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
        <p class="fuente_todo_texto" style="text-align: justify;">            
            <b>Anexos:</b> {{$Anexos}}
            <br>
            <b>Elaboró:</b> {{$nombre_usuario}}
        </p>
        <section class="fuente_todo_texto">
            <table class="tabla1" style="text-align: justify;">                               
                @if (count($Agregar_copia) == 0)
                    <tr>
                        <td><span class="negrita">Copia: </span>No se registran copias</td>                                                                                
                    </tr>
                @else
                    <tr>
                        <td class="justificado"><span class="negrita">Copia:</span></td>                            
                    </tr>
                    <?php 
                        $Afiliado = 'Afiliado';
                        $Empleador = 'Empleador';
                        $EPS = 'EPS';
                        $AFP = 'AFP';
                        $ARL = 'ARL';
                    ?>
                    <?php
                    if (isset($Agregar_copia[$Afiliado])) { ?>
                            <tr>
                                <td>
                                    <span class="negrita">Afiliado: </span><?=$Agregar_copia['Afiliado'];?>
                                </td>
                            </tr>
                        <?php       
                        }
                    ?>
                    <?php 
                        if (isset($Agregar_copia[$Empleador])) { ?>
                            <tr>
                                <td>
                                    <span class="negrita">Empleador: </span><?=$Agregar_copia['Empleador'];?>
                                </td>
                            </tr>
                        <?php       
                        }
                    ?>
                    <?php 
                        if (isset($Agregar_copia[$EPS])) { ?>
                            <tr>
                                <td class="copias">
                                    <span class="negrita">EPS: </span><?=$Agregar_copia['EPS'];?>
                                </td>
                            </tr>
                        <?php       
                        }
                    ?>
                    <?php 
                        if (isset($Agregar_copia[$AFP])) { ?>
                            <tr>
                                <td class="copias">
                                    <span class="negrita">AFP: </span><?=$Agregar_copia['AFP'];?>
                                </td>
                            </tr>
                        <?php       
                        }
                    ?>
                    <?php 
                        if (isset($Agregar_copia[$ARL])) { ?>
                            <tr>
                                <td class="copias">
                                    <span class="negrita">ARL: </span><?=$Agregar_copia['ARL'];?>
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