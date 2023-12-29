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
            /* top: -2cm; */
            top: -2.2cm;
            left: 0cm;
            width: 100%;
            text-align: right; 
        }
        .logo_header{
            width: 350px;
            height: auto;
        }
        #footer{
            position: fixed;
            /* esta ligado con el tercer valor del margin */
            /* bottom: -1.2cm; */
            bottom: -2.2cm;
            left: 0cm;
            width: 100%;
        }
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
        .cursiva{
            font-family: 'Times New Roman';
            /* font-size: 13.3px; */
            font-size: 15px;
            font-style: italic;
        }
        .tabla1{
            width: 80%;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
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
            background-color: yellow;
        } */
    </style>
</head>
<body>
    <div id="header">
        <?php
            $imagenPath_header = public_path('/images/logos_preformas/logo_arl_alfa.png');
            $imagenData_header = file_get_contents($imagenPath_header);
            $imagenBase64_header = base64_encode($imagenData_header);
        ?>
        <img src="data:image/png;base64,{{ $imagenBase64_header }}" class="logo_header">
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
                        <p class="cursiva">{{$nombreCiudad}}, {{$fechaFormateada}}</p>
                        <p class="cursiva" style="margin-top: 50px;"><span class="negrita">Señor (a):</span>
                            <br>
                            {{$nombre_afiliado}}
                        </p>
                        <p class="cursiva"><span class="negrita">Correo:</span>
                            <br>
                            {{$correo_afiliado}}
                        </p>
                        <p class="cursiva"><span class="negrita">Dirección:</span>
                            <br>
                            {{$direccion_afiliado}}
                        </p>
                        <p class="cursiva"><span class="negrita">Teléfono:</span>
                            <br>
                            {{$telefonos_afiliado}}
                        </p>
                        <p class="cursiva"><span class="negrita">Ciudad:</span>
                            <br>
                            {{$municipio_afiliado}} - {{$departamento_afiliado}}
                        </p>
                    </td>
                    <td>
                        <div class="cuadro">
                            <p class="cursiva"><span class="negrita">Nro. Radicado 12345678</span></p>
                            <p class="cursiva"><span class="negrita">Cc 1030651087</span></p>
                            <p class="cursiva"><span class="negrita">Siniestro: 987456321</span></p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <br>
        <table class="tabla1">
            <tbody>
                <tr>
                    <td class="cursiva"><span class="negrita">Asunto:</span></td>
                    <td class="cursiva"><span class="negrita">CALIFICACIÓN DE ORIGEN</span></td>
                </tr>
                <tr>
                    <td class="cursiva"><span class="negrita">Identificación</span></td>
                    <td class="cursiva">{{$identificacion}}</td>
                </tr>
                <tr>
                    <td class="cursiva"><span class="negrita">Fecha del Siniestro</span></td>
                    <td class="cursiva">{{$fecha_evento}}</td>
                </tr>
            </tbody>
        </table>
        <br><br>
        <section class="cursiva">
            Reciba usted un cordial saludo de Seguros de Vida Alfa S.A.
            <br><br>
            De la manera más atenta queremos informar el resultado de la calificación realizada por el Grupo Interdisciplinario de Calificación de Origen y
            Pérdida de la Capacidad Laboral adscrito a la Administradora de Riesgos Laborales de Seguros de Vida Alfa S.A, 
            según lo dispuesto en los Artículo 142 del Decreto 0019 de 2012, ha determinado que el  evento reportado ante 
            esta Administradora,  con las patologías.
            <br>
            <ul>
                @foreach($diagnosticos_cie10 as $diagnostico)
                    <li>{{ $diagnostico }}</li>
                @endforeach
            </ul>
        </section>
        <section class="cursiva">
            El dictamen de calificación del que anexó copia, puede ser apelado ante esta Administradora, dentro de los (10) diez días siguientes a partir de su notificación, de acuerdo al Decreto 0019  de 2012 artículo 142, en la Carrera 10 Nº 18 - 36 piso 4°, 
            Edificio José María Córdoba, Bogotá D.C. Favor informar en la carta el motivo de su desacuerdo y en el asunto manifestar que es una inconformidad al dictamen.
            <br><br>
        </section>
        <section class="cursiva">
            Cualquier información adicional con gusto será atendida por el Auditor Técnico en el teléfono 7435333 Ext. 14626 en Bogotá.
        </section>
        <br><br>
        <section class="cursiva">
            Cordialmente,
            <br><br>
            <div class="firma">
                <img src="data:image/png;base64,{{ $imagenBase64_header }}" class="logo_header">
            </div>
            <div class="cursiva">
                Dirección de Servicios Médicos de Seguridad Social
                <br>
                Convenio Codess Seguros de Vida  Alfa S.A
            </div>
        </section>
        <br>
        <section class="cursiva">
            <span class="negrita">Elboró:</span> {{$nombre_usuario}}
            <br><br>
            Copia:
            <br>
            <div class="copias">

            </div>
        </section>
        <section class="cursiva" style="color: #828282;">
            <br>
            “Finalmente, reiteramos que en nuestra Compañía contamos con la mejor disposición para atender sus quejas y
            reclamos a través del defensor consumidor financiero, en la Av. Calle 26 No 59-15, local 6 y 7. Conmutador:
            7435333 Extensión: 14454, Fax Ext. 14456 o Correo Electrónico:
            defensor del consumidor financiero@segurosdevidaalfa.com.co”.
        </section>
    </div>
</body>
</html>