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
        .tabla_acuerdo_origen{
            font-family: sans-serif;
            text-align: justify;
            width: 100%;
            table-layout: fixed; 
            border-collapse: collapse;
        }

        .tabla_acuerdo_origen, .tabla_acuerdo_origen td, .tabla_acuerdo_origen th {
            border: 1px solid black;
            border-collapse: collapse;
            text-align: justify;
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
        <table class="tabla_footer">
            <tbody>
                <tr>
                    <td colspan="2" class="negrita" style="text-align: center;"><?php echo "{$nombre_afiliado} - {$tipo_identificacion} {$num_identificacion} - Siniestro: {$nro_siniestro}"; ?></td>
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
    <div class="content">
        {{-- @for ($i=0; $i<50; $i++)
            <div class="hijo">{{$i}}</div>
        @endfor --}}
        <p class="fuente_todo_texto">{{$ciudad}}, {{$fecha}}</p>
        <table class="tabla2">
            <tbody>
                <tr>
                    <td>
                        <span class="fuente_todo_texto"><span class="negrita">Señores: </span>{{$nombre_destinatario}}</span><br>
                        <span class="fuente_todo_texto"><span class="negrita">Dirección: </span>{{$direccion_destinatario}}</span><br>
                        <span class="fuente_todo_texto"><span class="negrita">Teléfono: </span>{{$telefono_destinatario}}</span><br>
                        <span class="fuente_todo_texto"><span class="negrita">Ciudad: </span>{{$ciudad_destinatario}}</span>
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
                        <span class="fuente_todo_texto"><span class="negrita">Asunto: {{$asunto}}</span></span><br>
                        <span class="fuente_todo_texto"><span class="negrita">Ramo: {{$ramo}}</span></span><br>
                        <span class="fuente_todo_texto"><span class="negrita">{{$tipo_identificacion}} {{$num_identificacion}}</span></span><br>
                        <span class="fuente_todo_texto"><span class="negrita">Siniestro: {{$nro_siniestro}}</span></span><br>
                    </td>
                </tr>
            </tbody>
        </table>
        <section class="fuente_todo_texto">
            <table class='tabla_acuerdo_origen'>
                <tbody>
                    <tr>
                        <td>NOMBRE: {{$nombre_afiliado}}</td>
                    </tr>
                    <tr>
                        <td>IDENTIFICACIÓN: {{$num_identificacion}}</td>
                    </tr>
                    <tr>
                        <td>SINIESTRO: {{$nro_siniestro}}</td>
                    </tr>
                    <tr>
                        <td>ENTIDAD CALIFICADORA:{{$nombre_entidad_calificadora}}</td>
                    </tr>
                    <tr>
                        <td>FECHA DEL DICTAMEN: {{$fecha_dictamen}}</td>
                    </tr>
                    <tr>
                        <td>PCL: N/A</td>
                    </tr>
                    <tr>
                        <td>FECHA DE ESTRUCTURACIÓN: N/A</td>
                    </tr>
                    <tr>
                        <td>SUSTENTACIÓN: {{$cuerpo}}</td>
                    </tr>
                </tbody>
            </table>
        </section>
        <br>
        <section class="fuente_todo_texto">
            Cordialmente,
            <div class="firma">
                <?=$Firma_cliente?>
            </div>
            <div class="fuente_todo_texto">
                <span class="negrita">Departamento de medicina laboral</span>
                <br>
                <span class="negrita">Convenio Seguro de Vida Alfa</span>
                <br>
                <span class="negrita">Seguro alfa S.A. y Seguro de Vida Alfa S.A.</span>
                <br>
                <span class="negrita">Anexo: {{$nro_anexos}}</span>
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
                        $JRCI = 'JRCI';
                        $JNCI = 'JNCI';
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
                    
                @endif
            </table>
        </section>
    </div>
</body>
</html>