<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        /* @font-face {
            font-family: 'Arial';
            font-style: normal;
            font-weight: normal;
            src: url(https://fonts.googleapis.com/css2?family=Arial&display=swap);
        } */
        @page{
            /* arriba  derecha  abajo  izquierda */
            margin: 3cm 1.3cm 2.5cm 1.3cm;
        }
        #header {
            position: fixed; 
            /* esta ligado con el primer valor del margin */
            top: -3cm;
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
            /* esta ligado con el tercer valor del margin */
            bottom: -2.4cm;
            left: 0cm;
            width: 100%;
            height: 10%;
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
        .paddingTexto{
            margin: 0;
            padding: 0;
        }
        .tabla1{
            width: 80%;
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
            border: 2px solid black;
            width: 4cm;
            padding: 1px;
            height: auto;
        }     
        .fuente_cuadro_inferior{
            font-family: sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }
        .derecha{
            float: right;
        }

        .copias{
            font-size: 10px;
        }
        /* .hijo{
            width: 2cm;
            height: 1cm;
            margin: 0.2cm;
            background-color: yellowgreen;
        } */
    </style>
</head>
<body>
    <div id="header">
        <?php
            // $imagenPath_header = public_path('/images/logos_preformas/logo_arl_alfa.png');
            // $imagenData_header = file_get_contents($imagenPath_header);
            // $imagenBase64_header = base64_encode($imagenData_header);
        ?>
        {{-- <img src="data:image/png;base64,{{ $imagenBase64_header }}" class="logo_header"> --}}
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
    </div>
    <div id="footer">
        <?php if($footer == null): ?>
            <div style="text-align:center;">
                <span style="color: #3C3C3C; margin-top:2px;">{{$nombre_afiliado}} - {{$tipo_identificacion}} {{$num_identificacion}} - Siniestro: {{$N_siniestro}} </span>
            </div>
        <?php else: ?>
            <?php 
                $ruta_footer = "/footer_clientes/{$id_cliente}/{$footer}";
                $footer_path = public_path($ruta_footer);
                $footer_data = file_get_contents($footer_path);
                $footer_base64 = base64_encode($footer_data);
            ?>
            <div class="footer_content">
                <span style="color: #3C3C3C; margin-top:2px;">{{$nombre_afiliado}} - {{$tipo_identificacion}} {{$num_identificacion}} - Siniestro: {{$N_siniestro}} </span>
                <br>
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
        {{-- @for ($i=0; $i<40; $i++)
            <div class="hijo">{{$i}}</div>
        @endfor --}}

        <p class="fuente_todo_texto derecha"><span class="negrita">Bogotá D.C. {{$fecha_sustentacion_jrci}}</span></p>
        <br><br>
        <table class="tabla2">
            <tbody>
                <tr>
                    <td style="width:100%;">
                        <span class="fuente_todo_texto negrita">Señores: <br>{{$nombre_junta}}</span><br>
                        <span class="fuente_todo_texto">{{$direccion_junta}}</span><br>
                        <span class="fuente_todo_texto">{{$telefono_junta}}</span><br>
                        <span class="fuente_todo_texto">{{$ciudad_junta}} - {{$departamento_junta}}</span>
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <table class="tabla1">
            <tbody>
                <tr>
                    <td>
                        <span class="fuente_todo_texto">
                            <span class="negrita">
                                Asunto: 
                                <?php
                                    $patron_asunto = '/\{\{\$NRO_DICTAMEN_ASUNTO\}\}/';
                                    if (preg_match($patron_asunto, $asunto)) {
                                        $asunto_modificado = str_replace('{{$NRO_DICTAMEN_ASUNTO}}', $nro_dictamen, $asunto);
                                        $asunto = $asunto_modificado;
                                    } else {
                                        $asunto = "";
                                    }
                                    print_r($asunto);
                                ?>
                            </span>
                        </span>
                        <br>
                        <span class="fuente_todo_texto"><span class="negrita">Siniestro: {{$N_siniestro}} {{$tipo_identificacion}} {{$num_identificacion}} {{$nombre_afiliado}}</span></span><br>
                    </td>
                </tr>
            </tbody>
        </table>
        <section class="fuente_todo_texto">
            <?php 
                $patron1 = '/\{\{\$nro_dictamen\}\}/';
                $patron2 = '/\{\{\$f_dictamen_jrci\}\}/';
                $patron3 = '/\{\{\$nombre_afiliado\}\}/';
                $patron4 = '/\{\{\$tipo_identificacion_afiliado\}\}/';
                $patron5 = '/\{\{\$num_identificacion_afiliado\}\}/';
                $patron6 = '/\{\{\$cie10_nombre_cie10_jrci\}\}/';
                $patron7 = '/\{\{\$pcl_jrci\}\}/';
                $patron8 = '/\{\{\$origen_dx_jrci\}\}/';
                $patron9 = '/\{\{\$f_estructuracion_jrci\}\}/';
                $patron10 = '/\{\{\$decreto_calificador_jrci\}\}/';
                $patron11 = '/\{\{\$sustentacion_jrci\}\}/';
                $patron12 = '/\{\{\$sustentacion_jrci1\}\}/';

                /* Evaluamos el tipo de controversia para saber que texto hay que insertar en la proforma. 12 es Contro Origen y 13 Contro PCL*/
                if($id_servicio == 12){
                    if (preg_match($patron1, $cuerpo) && preg_match($patron2, $cuerpo) && preg_match($patron3, $cuerpo) &&
                        preg_match($patron4, $cuerpo) && preg_match($patron5, $cuerpo) && preg_match($patron6, $cuerpo)                    
                    ) {
                        
                        $cuerpo_modificado = str_replace('{{$nro_dictamen}}', "<b>".$nro_dictamen."</b>", $cuerpo);
                        $cuerpo_modificado = str_replace('{{$f_dictamen_jrci}}', "<b>".date("d/m/Y", strtotime($f_dictamen_jrci_emitido))."</b>", $cuerpo_modificado);
                        $cuerpo_modificado = str_replace('{{$nombre_afiliado}}', "<b>".$nombre_afiliado."</b>", $cuerpo_modificado);
                        $cuerpo_modificado = str_replace('{{$tipo_identificacion_afiliado}}', "<b>".$tipo_identificacion."</b>", $cuerpo_modificado);
                        $cuerpo_modificado = str_replace('{{$num_identificacion_afiliado}}', "<b>".$num_identificacion."</b>", $cuerpo_modificado);
                        $cuerpo_modificado = str_replace('{{$cie10_nombre_cie10_jrci}}', $string_diagnosticos_cie10_jrci, $cuerpo_modificado);
                        // $cuerpo_modificado = str_replace('{{$pcl_jrci}}', "<b>".$porcentaje_pcl_jrci_emitido."</b>", $cuerpo_modificado);
                        // $cuerpo_modificado = str_replace('{{$origen_dx_jrci}}', "<b>".$origen_jrci_emitido."</b>", $cuerpo_modificado);
                        // $cuerpo_modificado = str_replace('{{$f_estructuracion_jrci}}', "<b>".$f_estructuracion_contro_jrci_emitido."</b>", $cuerpo_modificado);
                        // $cuerpo_modificado = str_replace('{{$decreto_calificador_jrci}}', "<b>".$manual_de_califi_jrci_emitido."</b>", $cuerpo_modificado);
                        $cuerpo_modificado = str_replace('ACUERDO', "<b>ACUERDO</b>", $cuerpo_modificado);

                        if (preg_match($patron11, $cuerpo_modificado) && preg_match($patron12, $cuerpo_modificado)) {
                            // Ambos patrones encontrados
                            $cuerpo_modificado = str_replace('{{$sustentacion_jrci}}', $sustentacion_concepto_jrci, $cuerpo_modificado);
                            $cuerpo_modificado = str_replace('{{$sustentacion_jrci1}}', $sustentacion_concepto_jrci1, $cuerpo_modificado);

                            $cuerpo = nl2br($cuerpo_modificado);
                        
                        } elseif (preg_match($patron11, $cuerpo_modificado)) {
                            // Solo patrón11 encontrado
                            $cuerpo_modificado = str_replace('{{$sustentacion_jrci}}', $sustentacion_concepto_jrci, $cuerpo_modificado);

                            $cuerpo = nl2br($cuerpo_modificado);
                        
                        } elseif (preg_match($patron12, $cuerpo_modificado)) {
                            // Solo patrón12 encontrado
                            $cuerpo_modificado = str_replace('{{$sustentacion_jrci1}}', $sustentacion_concepto_jrci1, $cuerpo_modificado);
                            
                            $cuerpo = nl2br($cuerpo_modificado);
                        } else {
                            // Ninguno de los patrones encontrados
                            $cuerpo = "";
                        }

                    } else {
                        $cuerpo = "";
                    }
                }else{

                    if (preg_match($patron1, $cuerpo) && preg_match($patron2, $cuerpo) && preg_match($patron3, $cuerpo) &&
                        preg_match($patron4, $cuerpo) && preg_match($patron5, $cuerpo) && preg_match($patron6, $cuerpo) &&
                        preg_match($patron7, $cuerpo) && preg_match($patron8, $cuerpo) && preg_match($patron9, $cuerpo) &&
                        preg_match($patron10, $cuerpo)
                    ){
                        
                        $cuerpo_modificado = str_replace('{{$nro_dictamen}}', "<b>".$nro_dictamen."</b>", $cuerpo);
                        $cuerpo_modificado = str_replace('{{$f_dictamen_jrci}}', "<b>".date("d/m/Y", strtotime($f_dictamen_jrci_emitido))."</b>", $cuerpo_modificado);
                        $cuerpo_modificado = str_replace('{{$nombre_afiliado}}', "<b>".$nombre_afiliado."</b>", $cuerpo_modificado);
                        $cuerpo_modificado = str_replace('{{$tipo_identificacion_afiliado}}', "<b>".$tipo_identificacion."</b>", $cuerpo_modificado);
                        $cuerpo_modificado = str_replace('{{$num_identificacion_afiliado}}', "<b>".$num_identificacion."</b>", $cuerpo_modificado);
                        $cuerpo_modificado = str_replace('{{$cie10_nombre_cie10_jrci}}', $string_diagnosticos_cie10_jrci, $cuerpo_modificado);
                        $cuerpo_modificado = str_replace('{{$pcl_jrci}}', "<b>".$porcentaje_pcl_jrci_emitido."</b>", $cuerpo_modificado);
                        $cuerpo_modificado = str_replace('{{$origen_dx_jrci}}', "<b>".$origen_jrci_emitido."</b>", $cuerpo_modificado);
                        $cuerpo_modificado = str_replace('{{$f_estructuracion_jrci}}', "<b>".date("d/m/Y", strtotime($f_estructuracion_contro_jrci_emitido))."</b>", $cuerpo_modificado);
                        $cuerpo_modificado = str_replace('{{$decreto_calificador_jrci}}', "<b>".$manual_de_califi_jrci_emitido."</b>", $cuerpo_modificado);
                        $cuerpo_modificado = str_replace('ACUERDO', "<b>ACUERDO</b>", $cuerpo_modificado);
    
                        if (preg_match($patron11, $cuerpo_modificado) && preg_match($patron12, $cuerpo_modificado)) {
                            // Ambos patrones encontrados
                            $cuerpo_modificado = str_replace('{{$sustentacion_jrci}}', $sustentacion_concepto_jrci, $cuerpo_modificado);
                            $cuerpo_modificado = str_replace('{{$sustentacion_jrci1}}', $sustentacion_concepto_jrci1, $cuerpo_modificado);
    
                            $cuerpo = nl2br($cuerpo_modificado);
                        
                        } elseif (preg_match($patron11, $cuerpo_modificado)) {
                            // Solo patrón11 encontrado
                            $cuerpo_modificado = str_replace('{{$sustentacion_jrci}}', $sustentacion_concepto_jrci, $cuerpo_modificado);
    
                            $cuerpo = nl2br($cuerpo_modificado);
                        
                        } elseif (preg_match($patron12, $cuerpo_modificado)) {
                            // Solo patrón12 encontrado
                            $cuerpo_modificado = str_replace('{{$sustentacion_jrci1}}', $sustentacion_concepto_jrci1, $cuerpo_modificado);
                            
                            $cuerpo = nl2br($cuerpo_modificado);
                        } else {
                            // Ninguno de los patrones encontrados
                            $cuerpo = "";
                        }
    
                    } else {
                        $cuerpo = "";
                    }
                }

                print_r($cuerpo);
            ?>
        </section>
        <p class="fuente_todo_texto">
            Cordialmente,
            <div class="firma">
                <?=$Firma_cliente?>
            </div>
            <div class="fuente_todo_texto">
                <span class="negrita">Departamento de medicina laboral</span>
                <br>
                <span class="negrita">Convenio Codess - Seguros de Vida Alfa S.A</span>
            </div>
        </p>
        <br>
        <section class="fuente_todo_texto">
            {{-- <span class="negrita">Elboró:</span> {{$nombre_usuario}} --}}
            <table style="text-align: justify; width:100%; margin-left: -3px;">
                @if (count($Agregar_copia) == 0)
                    <tr>
                        <td class="copias"><span class="negrita">Copia: </span>No se registran copias</td>                                                                                
                    </tr>
                @else
                    <tr>
                        <td class="justificado copias"><span class="negrita">Copia:</span></td>                            
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
                                <td class="copias">
                                    <span class="negrita">Afiliado: </span><?=$Agregar_copia['Afiliado'];?>
                                </td>
                            </tr>
                        <?php       
                        }
                    ?>
                    <?php 
                        if (isset($Agregar_copia[$Empleador])) { ?>
                            <tr>
                                <td class="copias">
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
        <br>
        <div class="cuadro fuente_cuadro_inferior" style="margin: 0 auto; page-break-before: always;">
            <span class="fuente_cuadro_inferior"><span class="negrita">Nro. Radicado: <br>{{$nro_radicado}}</span></span><br>
            <span class="fuente_cuadro_inferior"><span class="negrita">{{$tipo_identificacion}} {{$num_identificacion}}</span></span><br>
            <span class="fuente_cuadro_inferior"><span class="negrita">Siniestro: {{$N_siniestro}}</span></span><br>
        </div>
    </div>
    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->text(485, 50, "Página $PAGE_NUM de $PAGE_COUNT", $font, 10);
            ');
        }
	</script>
</body>
</html>