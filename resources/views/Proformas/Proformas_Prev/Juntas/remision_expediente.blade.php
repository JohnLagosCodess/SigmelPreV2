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
            margin: 2.5cm 1.3cm 2.5cm 1.3cm;
        }
        #header {
            position: fixed; 
            /* esta ligado con el primer valor del margin */
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
            /* esta ligado con el tercer valor del margin */
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
            border: 3px solid black;
            padding-left: 6px;
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
        <table class="tabla_footer">
            <tbody>
                <tr>
                    <td colspan="2" class="negrita" style="text-align: center;"><?php echo "{$nombre_afiliado} - {$tipo_doc_afiliado} ({$num_identificacion_afiliado}) - Siniestro: ({$ID_evento})"; ?></td>
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
        {{-- @for ($i=0; $i<40; $i++)
            <div class="hijo">{{$i}}</div>
        @endfor --}}
        <p class="fuente_todo_texto"><span class="negrita">Bogotá D.C., {{$fecha}}</span></p>
        <table class="tabla2">
            <tbody>
                <tr>
                    <td>
                        <span class="fuente_todo_texto"><span class="negrita">Señores: {{$nombre_junta}}</span></span><br>
                        <span class="fuente_todo_texto"><span class="negrita">Dirección: {{$direccion_junta}}</span></span><br>
                        <span class="fuente_todo_texto"><span class="negrita">Teléfono: {{$telefono_junta}}</span></span><br>
                        <span class="fuente_todo_texto"><span class="negrita">{{$ciudad_junta}} - {{$departamento_junta}}</span></span>
                    </td>
                    <td>
                        <div class="cuadro">
                            <span class="fuente_todo_texto"><span class="negrita">Nro. Radicado {{$nro_radicado}}</span></span><br>
                            <span class="fuente_todo_texto"><span class="negrita">{{$tipo_doc_afiliado}} {{$num_identificacion_afiliado}}</span></span><br>
                            <span class="fuente_todo_texto"><span class="negrita">Siniestro: {{$ID_evento}}</span></span><br>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="tabla1">
            <tbody>
                <tr>
                    <td>
                        <span class="fuente_todo_texto"><span class="negrita">Asunto: <?php echo $asunto;?></span></span>
                        <br>
                        <span class="fuente_todo_texto"><span class="negrita">Siniestro: {{$ID_evento}} {{$tipo_doc_afiliado}} {{$num_identificacion_afiliado}} {{$nombre_afiliado}}</span></span>
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
                

                if (preg_match($patron1,$cuerpo) && preg_match($patron2,$cuerpo) && preg_match($patron3,$cuerpo)
                    && preg_match($patron4,$cuerpo) && preg_match($patron5,$cuerpo) && preg_match($patron6,$cuerpo) 
                    && preg_match($patron7,$cuerpo) && preg_match($patron8,$cuerpo) && preg_match($patron9,$cuerpo)
                    && preg_match($patron10,$cuerpo) && preg_match($patron11,$cuerpo) && preg_match($patron12,$cuerpo)
                ) {

                    $texto_modificado = str_replace('{{$nro_orden_pago}}', "<b>".$nro_orden_pago."</b>", $cuerpo);
                    $texto_modificado = str_replace('{{$fecha_notificacion_afiliado}}', "<b>".$f_notifi_afiliado_act."</b>", $texto_modificado);
                    $texto_modificado = str_replace('{{$fecha_radicacion_controversia_primera_calificacion}}', "<b>".$f_radicacion_contro_pri_cali_act."</b>", $texto_modificado);
                    $texto_modificado = str_replace('{{$tipo_documento_afiliado}}', $tipo_doc_afiliado, $texto_modificado);
                    $texto_modificado = str_replace('{{$documento_afiliado}}', $num_identificacion_afiliado, $texto_modificado);
                    $texto_modificado = str_replace('{{$nombre_afiliado}}', $nombre_afiliado, $texto_modificado);
                    $texto_modificado = str_replace('{{$fecha_estructuracion}}', $f_estructuracion_act, $texto_modificado);
                    $texto_modificado = str_replace('{{$tipo_evento}}', $tipo_evento, $texto_modificado);
                    $texto_modificado = str_replace('{{$nombres_cie10}}', $string_diagnosticos_cie10_jrci, $texto_modificado);
                    $texto_modificado = str_replace('{{$tipo_controversia_primera_calificacion}}', $string_tipos_controversia, $texto_modificado);
                    $texto_modificado = str_replace('{{$direccion_afiliado}}', $direccion_afiliado, $texto_modificado);
                    $texto_modificado = str_replace('{{$telefono_afiliado}}', $telefono_afiliado, $texto_modificado);

                    $texto_modificado = str_replace('PÉRDIDA DE CAPACIDAD LABORAL', "<b>PÉRDIDA DE CAPACIDAD LABORAL</b>", $texto_modificado);
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
            <span class="negrita">Elboró:</span> {{$nombre_usuario}}
            <table style="text-align: justify; width:100%; margin-left: -3px;">
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