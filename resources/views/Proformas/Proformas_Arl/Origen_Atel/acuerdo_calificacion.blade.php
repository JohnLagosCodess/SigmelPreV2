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
            width: 100%;font-family: sf-pro-display-black, sans-serif;font-size: 12px;
        }

        .color_letras_alfa{
            color: #184F56; font-weight: bold;
        }

        .negrita{
            font-weight: bold;
        }

        .fuente_todo_texto{
            font-family: Arial;
            font-size: 15px;
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

        .cuadro{
            border: 3px solid black;
            padding-left: 6px;
        }

        .content{
            margin-top: -0.5cm;
            margin-left: 0.5cm;
            margin-right: 0.5cm;
        }

        /* .hijo{
            width: 2cm;
            height: 1cm;
            margin: 0.2cm;
            background-color: red;
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
            <p class="page" style="color: black;">Página </p>
        <?php else: ?>
            <?php 
                $ruta_footer = "/footer_clientes/{$id_cliente}/{$footer}";
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
    <div class="content">
        {{-- @for ($i=0; $i<50; $i++)
            <div class="hijo">{{$i}}</div>
        @endfor --}}
        <p class="fuente_todo_texto">{{$ciudad}}, {{$fecha}}</p>
        <table class="tabla2">
            <tbody>
                <tr>
                    <td>
                        <span class="fuente_todo_texto"><span class="negrita">Señor(a): </span>{{$nombre_afiliado}}</span><br>
                        <span class="fuente_todo_texto"><span class="negrita">Dirección: </span>{{$direccion_afiliado}}</span><br>
                        <span class="fuente_todo_texto"><span class="negrita">Teléfono: </span>{{$telefonos_afiliado}}</span><br>
                        <span class="fuente_todo_texto"><span class="negrita">Ciudad: </span>{{$municipio_afiliado}} - {{$departamento_afiliado}}</span>
                    </td>
                    <td>
                        <div class="cuadro">
                            <span class="fuente_todo_texto"><span class="negrita">Nro. Radicado {{$nro_radicado}}</span></span><br>
                            <span class="fuente_todo_texto"><span class="negrita">{{$tipo_identificacion}} {{$num_identificacion}}</span></span><br>
                            <span class="fuente_todo_texto"><span class="negrita">Siniestro: {{$nro_siniestro}}</span></span><br>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="tabla1">
            <tbody>
                <tr>
                    <td>
                        <span class="fuente_todo_texto"><span class="negrita">Asunto: </span>{{$asunto}}</span><br>
                        <span class="fuente_todo_texto"><span class="negrita">Identificación: </span>{{$identificacion}}</span><br>
                        <span class="fuente_todo_texto"><span class="negrita">Fecha del Siniestro: </span>{{$fecha_evento}}</span><br>
                    </td>
                </tr>
            </tbody>
        </table>
        <section class="fuente_todo_texto">
            <?php
                $patron1 = '/\{\{\$diagnosticos_cie10\}\}/'; 
                $patron2 = '/\{\{\$origen\}\}/'; 
                if (preg_match($patron1, $cuerpo) && preg_match($patron2, $cuerpo)) {
                    # code...
                    $texto_modificado = str_replace('{{$diagnosticos_cie10}}', $string_diagnosticos_cie10, $cuerpo);
                    $texto_modificado = str_replace('{{$origen}}', $origen, $texto_modificado);
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
            <div class="fuente_todo_texto">
                Dirección de Servicios Médicos de Seguridad Social
                <br>
                Convenio Codess Seguros de Vida Alfa S.A
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
        <section class="fuente_todo_texto" style="color: #828282;">
            “Finalmente, reiteramos que en nuestra Compañía contamos con la mejor disposición para atender sus quejas y
            reclamos a través del defensor consumidor financiero, en la Av. Calle 26 No 59-15, local 6 y 7. Conmutador:
            7435333 Extensión: 14454, Fax Ext. 14456 o Correo Electrónico:
            defensor del consumidor financiero@segurosdevidaalfa.com.co”.
        </section>
    </div>
</body>
</html>