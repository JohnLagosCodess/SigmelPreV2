<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
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
            top:700px;
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
            margin-bottom: 0.5cm;
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

        .tabla_cuerpo_remision_expediente {
            font-size: 12px;
            text-align: justify;
            width: 100%;
            table-layout: fixed; 
            border-collapse: collapse;
        }

        .tabla_cuerpo_remision_expediente, .tabla_cuerpo_remision_expediente td, .tabla_cuerpo_remision_expediente th {
            border: 1px solid black;
            border-collapse: collapse;
            padding-left: 5px;
            padding-bottom: 0px;
        }
        
        .bg{
            background-color: rgb(204, 204, 204);
        }

        ul > li{
            font-family: sans-serif;
            font-size: 12px !important;
        }

        .derecha{
            float:right;
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
                <span style="color: #3C3C3C; margin-top:2px;">{{$nombre_afiliado}} - {{$tipo_doc_afiliado}} {{$num_identificacion_afiliado}} - Siniestro: {{$N_siniestro}} </span>
            </div>
        <?php else: ?>
            <?php 
                $ruta_footer = "/footer_clientes/{$id_cliente}/{$footer}";
                $footer_path = public_path($ruta_footer);
                $footer_data = file_get_contents($footer_path);
                $footer_base64 = base64_encode($footer_data);
            ?>
            <div class="footer_content">
                <span style="color: #3C3C3C; margin-top:2px;">{{$nombre_afiliado}} - {{$tipo_doc_afiliado}} {{$num_identificacion_afiliado}} - Siniestro: {{$N_siniestro}} </span>
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
        <p class="fuente_todo_texto derecha"><span class="negrita">Bogotá D.C., {{$fecha}}</span></p>
        <br><br>
        <table class="tabla2">
            <tbody>
                <tr>
                    <td style="width: 100%;">
                        <span class="fuente_todo_texto paddingTexto"><span class="negrita">Señores: <br>{{$nombre_junta}}</span></span><br>
                        <span class="fuente_todo_texto paddingTexto">Dirección: {{$direccion_junta}}</span><br>
                        <span class="fuente_todo_texto paddingTexto">Teléfono: {{$telefono_junta}}</span><br>
                        @if($ciudad_junta == 'Bogota D.C.' || $ciudad_junta == 'Bogotá D.C.')
                            <span class="fuente_todo_texto paddingTexto">Bogotá D.C.</span>
                        @else
                            <span class="fuente_todo_texto paddingTexto">{{$ciudad_junta.' - '.$departamento_junta}}</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <table class="tabla1">
            <tbody>
                <tr>
                    <td>
                        <span class="fuente_todo_texto"><span class="negrita">Asunto: <?php echo $asunto;?></span></span>
                        <br>
                        <span class="fuente_todo_texto" style="display: inline-block; margin-left: 50px;">
                            <span class="negrita">Siniestro: {{$N_siniestro}} {{$tipo_doc_afiliado}} {{$num_identificacion_afiliado}} {{$nombre_afiliado}}</span>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
        <section class="fuente_todo_texto">
            <?php 
                $patron1 = '/\{\{\$nro_orden_pago\}\}/';
                $patron2 = '/\{\{\$fecha_notificacion_afiliado\}\}/';
                $patron3 = '/\{\{\$fecha_radicacion_controversia_primera_calificacion\}\}/';
                $patron4 = '/\{\{\$tipo_documento_afiliado\}\}/';
                $patron5 = '/\{\{\$documento_afiliado\}\}/';
                $patron6 = '/\{\{\$nombre_afiliado\}\}/';
                $patron7 = '/\{\{\$fecha_estructuracion\}\}/';
                $patron8 = '/\{\{\$tipo_evento\}\}/';
                $patron9 = '/\{\{\$nombres_cie10\}\}/';
                $patron10 = '/\{\{\$tipo_controversia_primera_calificacion\}\}/';
                $patron11 = '/\{\{\$direccion_afiliado\}\}/';
                $patron12 = '/\{\{\$telefono_afiliado\}\}/';
                $patron13 = '/\{\{\$observaciones\}\}/';
                $patron14 = '/\{\{\$porcentaje_pcl\}\}/';

                if (preg_match($patron1,$cuerpo) && preg_match($patron2,$cuerpo) && preg_match($patron3,$cuerpo)
                    && preg_match($patron4,$cuerpo) && preg_match($patron5,$cuerpo) && preg_match($patron6,$cuerpo) 
                    && preg_match($patron7,$cuerpo) && preg_match($patron8,$cuerpo) && preg_match($patron9,$cuerpo)
                    && preg_match($patron10,$cuerpo) && preg_match($patron11,$cuerpo) && preg_match($patron12,$cuerpo)
                    && preg_match($patron13,$cuerpo)
                ) {
                    $texto_modificado = str_replace('{{$nro_orden_pago}}', "<b>".$nro_orden_pago."</b>", $cuerpo);
                    $texto_modificado = str_replace('{{$fecha_notificacion_afiliado}}', "<b>".$f_notifi_afiliado_act."</b>", $texto_modificado);
                    $texto_modificado = str_replace('{{$fecha_radicacion_controversia_primera_calificacion}}', "<b>".$f_radicacion_contro_pri_cali_act."</b>", $texto_modificado);
                    $texto_modificado = str_replace('{{$tipo_documento_afiliado}}', $tipo_doc_afiliado, $texto_modificado);
                    $texto_modificado = str_replace('{{$documento_afiliado}}', $num_identificacion_afiliado, $texto_modificado);
                    $texto_modificado = str_replace('{{$nombre_afiliado}}', $nombre_afiliado, $texto_modificado);
                    $texto_modificado = str_replace('{{$fecha_estructuracion}}', $fecha_estructuración, $texto_modificado);
                    $texto_modificado = str_replace('{{$tipo_evento}}', strtoupper($tipo_evento), $texto_modificado);
                    $texto_modificado = str_replace('{{$nombres_cie10}}', $string_diagnosticos_cie10_jrci, $texto_modificado);
                    $texto_modificado = str_replace('{{$tipo_controversia_primera_calificacion}}', $string_tipos_controversia, $texto_modificado);
                    $texto_modificado = str_replace('{{$direccion_afiliado}}', $direccion_afiliado, $texto_modificado);
                    $texto_modificado = str_replace('{{$telefono_afiliado}}', $telefono_afiliado, $texto_modificado);
                    $texto_modificado = str_replace('{{$observaciones}}', $observaciones_controversia, $texto_modificado);

                    $texto_modificado = str_replace('PÉRDIDA DE CAPACIDAD LABORAL', "<b>PÉRDIDA DE CAPACIDAD LABORAL</b>", $texto_modificado);
                    if(preg_match($patron14,$cuerpo)){
                        $texto_modificado = str_replace('{{$porcentaje_pcl}}', $porcentaje_pcl.'%', $texto_modificado);
                    }
                    else{
                        $texto_modificado = str_replace('{{$porcentaje_pcl}}','', $texto_modificado);
                    }
                    $cuerpo = $texto_modificado;
                } else {
                    $cuerpo = "";
                }
                
                print_r($cuerpo);
            ?>
        </section>
        <section class="fuente_todo_texto">
            Atentamente,
            <div class="firma">
                <?=$Firma_cliente?>
            </div>
            <div class="fuente_todo_texto">
                <span class="negrita">Departamento de medicina laboral</span>
                <br>
                <span class="negrita">Convenio Codess - Seguros de Vida Alfa</span>
                <br>
                <span class="negrita">Anexo: Lo enunciado en (1) expediente ( ) folios</span>
            </div>
        </section>
        <br>
        <section class="fuente_todo_texto">
            <table style="text-align: justify; width:100%; margin-left: -3px;">
                @if (count($Agregar_copia) == 0)
                    <tr>
                        <td class="copias"><span class="negrita ">Copia: </span>No se registran copias</td>                                                                                
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
                        $JRCI = 'JRCI';
                        $JNCI = 'JNCI';
                        $AFP_Conocimiento = 'AFP_Conocimiento';
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
                    <?php 
                        if (isset($Agregar_copia[$JRCI])) { ?>
                            <tr>
                                <td class="copias">
                                    <span class="negrita">JRCI: </span><?=$Agregar_copia['JRCI'];?>
                                </td>
                            </tr>
                        <?php       
                        }
                    ?>
                    <?php 
                        if (isset($Agregar_copia[$JNCI])) { ?>
                            <tr>
                                <td class="copias">
                                    <span class="negrita">JNCI: </span><?=$Agregar_copia['JNCI'];?>
                                </td>
                            </tr>
                        <?php       
                        }
                    ?>
                    <?php 
                        if (isset($Agregar_copia[$AFP_Conocimiento])) { ?>
                                <?=$Agregar_copia['AFP_Conocimiento'];?>
                            <?php       
                        }
                    ?>
                @endif
            </table>
        </section>
        <br>
        <div class="cuadro fuente_cuadro_inferior" style="margin: 0 auto">
            <span class="fuente_cuadro_inferior"><span class="negrita">Nro. Radicado: <br> {{$nro_radicado}}</span></span><br>
            <span class="fuente_cuadro_inferior"><span class="negrita">{{$tipo_doc_afiliado}} {{$num_identificacion_afiliado}}</span></span><br>
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