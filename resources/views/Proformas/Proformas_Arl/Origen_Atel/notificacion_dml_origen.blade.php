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
            font-family: Arial;
            font-size: 15px;
        }
        .tabla1{
            width: 80%;
            /* text-align: justify;
            margin-left: auto;
            margin-right: auto; */
        }
        .tabla2{
            width: 100%;
        }
        section{
            text-align: justify;
        }
        .container{
            margin-left: 1.5cm;
            margin-right: 1.5cm;
        }
        .cuadro{
            border: 3px solid black;
            padding-left: 6px;
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
        <table class="tabla_footer">
            <tbody>
                <tr>
                    <td colspan="2" class="color_letras_alfa">Seguros Alfa S.A. y Seguros de Vida Alfa S.A.</td>
                </tr>
                <tr>
                    <td class="color_letras_alfa">Líneas de atención al cliente</td>
                    <td style="text-align: right;" class="color_letras_alfa">www.segurosalfa.com.co</td>
                </tr>
                <tr>
                    <td colspan="2">Bogotá: 3077032, a nivel nacional: 018000122532</td>
                </tr>
                <tr>
                    <td colspan="2">habilitadas en jornada continua de lunes a viernes de 8:00 a.m. a 6:00 p.m.</td>
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
        {{-- @for ($i=0; $i<40; $i++)
            <div class="hijo">{{$i}}</div>
        @endfor --}}
        <table class="tabla2">
            <tbody>
                <tr>
                    <td>
                        <p class="fuente_todo_texto">{{$ciudad}}, {{$fecha}}</p>
                        <p class="fuente_todo_texto" style="margin-top: 50px;"><span class="negrita">Señor (a):</span>
                            <br>
                            {{$nombre_afiliado}}
                        </p>
                        {{-- <p class="fuente_todo_texto"><span class="negrita">Correo:</span>
                            <br>
                            {{$correo_afiliado}}
                        </p> --}}
                        <p class="fuente_todo_texto"><span class="negrita">Dirección:</span>
                            <br>
                            {{$direccion_afiliado}}
                        </p>
                        <p class="fuente_todo_texto"><span class="negrita">Teléfono:</span>
                            <br>
                            {{$telefonos_afiliado}}
                        </p>
                        <p class="fuente_todo_texto"><span class="negrita">Ciudad:</span>
                            <br>
                            {{$municipio_afiliado}} - {{$departamento_afiliado}}
                        </p>
                    </td>
                    <td>
                        <div class="cuadro">
                            <p class="fuente_todo_texto"><span class="negrita">Nro. Radicado {{$nro_radicado}}</span></p>
                            <p class="fuente_todo_texto"><span class="negrita">{{$tipo_identificacion}} {{$num_identificacion}}</span></p>
                            <p class="fuente_todo_texto"><span class="negrita">Siniestro: {{$nro_siniestro}}</span></p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        {{-- <br> --}}
        {{-- <table class="tabla1">
            <tbody >
                <tr>
                    <td class="fuente_todo_texto"><span class="negrita">Asunto:</span></td>
                    <td class="fuente_todo_texto"><span class="negrita">{{$asunto}}</span></td>
                </tr>
                <tr>
                    <td class="fuente_todo_texto"><span class="negrita">Identificación:</span></td>
                    <td class="fuente_todo_texto">{{$identificacion}}</td>
                </tr>
                <tr>
                    <td class="fuente_todo_texto"><span class="negrita">Fecha del Siniestro:</span></td>
                    <td class="fuente_todo_texto">{{$fecha_evento}}</td>
                </tr>
            </tbody>
        </table> --}}
        <table class="tabla1">
            <tbody>
                <tr>
                    <td>
                        <p class="fuente_todo_texto"><span class="negrita">Asunto: {{$asunto}}</span>
                        </p>
                        <p class="fuente_todo_texto"><span class="negrita">Identificación: </span>{{$identificacion}}</p>
                        <p class="fuente_todo_texto"><span class="negrita">Fecha del Siniestro: </span>{{$fecha_evento}}</p>
                    </td>
                </tr>
            </tbody>
        </table>
        {{-- <br> --}}
        <section class="fuente_todo_texto">
            {{-- Reciba usted un cordial saludo de Seguros de Vida Alfa S.A.
            <br><br>
            De la manera más atenta queremos informar el resultado de la calificación realizada por el Grupo Interdisciplinario de Calificación de Origen y
            Pérdida de la Capacidad Laboral adscrito a la Administradora de Riesgos Laborales de Seguros de Vida Alfa S.A, 
            según lo dispuesto en los Artículo 142 del Decreto 0019 de 2012, ha determinado que el evento reportado ante esta Administradora, con las patologías. --}}
            {{-- {{$cuerpo}} --}}
            {{-- <br> --}}
            {{-- <ul>
                @foreach($diagnosticos_cie10 as $diagnostico)
                    <li>{{ $diagnostico }}</li>
                @endforeach
            </ul> --}}
            <?php 
                $patron = '/\{\{\$diagnosticos_cie10\}\}/'; 
                if (preg_match($patron, $cuerpo)) {

                    $lista_diagnosticos = '<ul>'.PHP_EOL;
                    foreach ($diagnosticos_cie10 as $diagnostico) {
                        $lista_diagnosticos .= '<li>'.$diagnostico.'</li>'.PHP_EOL;
                    }
                    $lista_diagnosticos .= '</ul>';

                    $texto_modificado = str_replace('{{$diagnosticos_cie10}}', $lista_diagnosticos, $cuerpo);
                    $cuerpo = $texto_modificado;
                } else {
                    $cuerpo = "";
                }
                print_r($cuerpo);
            ?>
        </section>
        {{-- <section class="fuente_todo_texto">
            El dictamen de calificación del que anexó copia, puede ser apelado ante esta Administradora, dentro de los (10) diez días siguientes a partir de su notificación, de acuerdo al Decreto 0019 de 2012 artículo 142, en la Carrera 10 Nº 18 - 36 piso 4°, 
            Edificio José María Córdoba, Bogotá D.C. Favor informar en la carta el motivo de su desacuerdo y en el asunto manifestar que es una inconformidad al dictamen.
            <br><br>
        </section>
        <section class="fuente_todo_texto">
            Cualquier información adicional con gusto será atendida por el Auditor Técnico en el teléfono 7435333 Ext. 14626 en Bogotá.
        </section> --}}
        <br>
        <section class="fuente_todo_texto">
            Cordialmente,
            <br><br>
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
            <br><br>
            <table style="text-align: justify; width:100%;">
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
            <br>
            “Finalmente, reiteramos que en nuestra Compañía contamos con la mejor disposición para atender sus quejas y
            reclamos a través del defensor consumidor financiero, en la Av. Calle 26 No 59-15, local 6 y 7. Conmutador:
            7435333 Extensión: 14454, Fax Ext. 14456 o Correo Electrónico:
            defensor del consumidor financiero@segurosdevidaalfa.com.co”.
        </section>
    </div>
</body>
</html>