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
            top:300px;
        }

        .logo_footer{
            width: auto;
            height: 150px;
        }

        .tabla_footer{
            width: 100%;font-family: sf-pro-display-black, sans-serif;font-size: 12px;
        }

        .color_letras_alfa{
            color: #184F56; font-weight: bold;
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
        .content{
            margin-top: -0.5cm;
            margin-left: 0.5cm;
            margin-right: 0.5cm;
        }

        .copias{
            font-size: 10px;
        }

        .derecha{
            float: right;
        }
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
                <span style="color: #3C3C3C; margin-top:2px;">{{$nombre}} - {{$tipo_identificacion}} {{$num_identificacion}} - Siniestro: {{$N_siniestro}} </span>
            </div>
        <?php else: ?>
            <?php 
                $ruta_footer = "/footer_clientes/{$id_cliente}/{$footer}";
                $footer_path = public_path($ruta_footer);
                $footer_data = file_get_contents($footer_path);
                $footer_base64 = base64_encode($footer_data);
            ?>
            <div class="footer_content">
                <span style="color: #3C3C3C; margin-top:2px;">{{$nombre}} - {{$tipo_identificacion}} {{$num_identificacion}} - Siniestro: {{$N_siniestro}} </span>
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
    <div class="content">
        <table class="tabla2">            
            <tbody>
                <tr>
                    <td style="width:100%; display:table; justify-content: space-between;">
                        <p class="fuente_todo_texto paddingTexto derecha"><span class="negrita">{{$ciudad}} {{$fecha}}</span></p>
                        <div>
                            <div class="fuente_todo_texto paddingTexto">
                                <span class="negrita">Señor(a): </span><br>
                                {{$nombre}}
                            </div>
                            <div class="fuente_todo_texto paddingTexto">{{$Email_afiliado}}</div>
                            <div class="fuente_todo_texto paddingTexto">{{$direccion}}</div>
                            <div class="fuente_todo_texto paddingTexto">{{$telefono}}</div>
                            <div class="fuente_todo_texto paddingTexto">{{$municipio.' - '.$departamento}}</div>
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
                        <span class="negrita">Asunto: {{$asunto}}</span><br> 
                        <div style="margin-left: 3cm;"> 
                            <span class="negrita">Ramo:</span> Previsionales<br>
                            {{$tipo_identificacion.' '.$num_identificacion}}<br>
                            <span class="negrita">Siniestro: </span>{{$N_siniestro}}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <section class="fuente_todo_texto">
            <span>Hola, ¡{{$nombre}}!</span>
            <?php
                $patron1 = '/\{\{\$documentos_solicitados\}\}/'; 
                if (!empty($cuerpo)) {      
                    if(preg_match($patron1, $cuerpo)){
                        $texto_modificado = str_replace('{{$documentos_solicitados}}', $Documentos_solicitados, $cuerpo);
                        $cuerpo = $texto_modificado;
                    }
                    else {
                        $texto_modificado = $cuerpo;
                        $cuerpo = $texto_modificado;
                    }     
                } else {
                    $cuerpo = "";
                }                
                print_r($cuerpo);
                // if (!empty($cuerpo)) {                    
                //     $texto_modificado = $cuerpo;
                //     $cuerpo = $texto_modificado;
                // } else {
                //     $cuerpo = "";
                // }
                
                // print_r($cuerpo);
            ?>
        </section>
        <section class="fuente_todo_texto">
            Para nosotros es un gusto servirle,
            <div class="firma">
                <?=$Firma_cliente?>
            </div>
        </section>
        <p class="fuente_todo_texto" style="text-align: justify;">
            Departamento de medicina laboral <br>
            Convenio Seguro de Vida Alfa <br>
            Seguro Alfa S.A. y Seguro de Vida Alfa S.A.
        </p>
        {{-- <p class="fuente_todo_texto" style="text-align: justify;">            
            <b>Anexos:</b> {{$Anexos}}
            <br>
            <b>Elaboró:</b> {{$nombre_usuario}}
        </p> --}}
        <section class="fuente_todo_texto">
            <table class="tabla1" style="text-align: justify; width:100%;">
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
        <div class="cuadro fuente_cuadro_inferior" style="margin: 0 auto">
            <span class="fuente_cuadro_inferior"><span class="negrita">Nro. Radicado: <br>{{$nro_radicado}}</span></span><br>
            <span class="fuente_cuadro_inferior"><span class="negrita">{{$tipo_identificacion.' '.$num_identificacion}}</span></span><br>
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