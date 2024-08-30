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
            /* height: 100px; */
            text-align: right;
            /* background: green; */
        }

        .logo_header{
            max-width: 30%;
            max-height: 80px;
        }
        #footer{
            position: fixed;
            /* esta ligado con el tercer valor del margin */
            bottom: -2.2cm;
            left: 0cm;
            width: 100%;
        }

        .tabla_footer{
            width: 100%;
            font-family: sans-serif;
            font-size: 13px;
            text-align: center;
        }

        #footer .page:after { content: counter(page, upper-decimal); }   

        .content{
            /* position: fixed;  */
            /* margin-top: 10px; */
        }
        .tabla_dictamen{
            font-family: sans-serif;
            text-align: justify;
            width: 100%; /* Ancho total de la tabla */
            table-layout: fixed;
            border-collapse: collapse; /* Borde de celda colapsado para evitar espacios adicionales */
        }

        .tabla_dictamen, td, th {
            /* border: 1px solid black; */
            border-collapse: collapse;
            padding: 1.5px;
        }

        .titulo_tablas{
            font-weight: bold;
            font-size: 12px;
            background: #D9D9D9;
            color: black;
            text-align: center !important;
        }

        .titulo_labels{
            font-weight: bold;
            font-size: 11px;
        }

        .dato_dinamico{
            font-size: 11px !important;
        }
        .underline{
            border-bottom: 1px solid black;
            text-align: center;
        }

        .firma_1, .firma_2, .firma_3{
            width: auto;
            height: 11.2%;
        }

        .titulo_documento{
            font-weight: bold;
            font-size: 12px;
        }

        .tabla_inicio_documento{
            font-family: sans-serif;
            width: 100%;
            margin-left: -3.5px;
        }

        .tabla_inicio_documento td, .tabla_inicio_documento th {
            border: none;
        }

        .cuadro{
            border: 3px solid black;
            padding-left: 6px;
        }
        
        .negrita{
            font-weight: bold;
        }

        .derecha{
            float:right;
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
    {{-- <div id="header">
        <img src="data:image/png;base64,{{ base64_encode($codigoQR) }}" class="codigo_qr" alt="Código QR"> 
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
    </div> --}}
    <div id="footer">
        <table class="tabla_footer">
            <tbody>
                <tr>
                    <p class="page">Página </p>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="content">
        {{-- @for ($i=0; $i<40; $i++)
            <div class="hijo">{{$i}}</div>
        @endfor --}}
        <table class="tabla_inicio_documento">
            <tbody>
                <tr>
                    <td style="text-align: center; width: 100%;">
                       <span class="titulo_documento">JUNTA REGIONAL DE CALIFICACIÓN DE INVALIDEZ</span><br>
                       <span class="titulo_documento">Solicitud de calificación de invalidez</span>
                    </td>
                    {{-- <td>
                        <div class="cuadro">
                            <span class="negrita">Nro. Radicado {{$nro_radicado}}</span><br>
                            <span class="negrita">{{$tipo_doc_afiliado}} {{$num_identificacion_afiliado}}</span><br>
                            <span class="negrita">Siniestro: {{$N_siniestro}}</span><br>
                        </div>
                    </td> --}}
                </tr>
                <tr>
                    <td><span class="derecha"><span class="titulo_labels">Fecha de solicitud: </span><span class="dato_dinamico">{{$fecha}}</span></span></td>
                </tr>
            </tbody>
        </table>
        <br>
        <table class="tabla_dictamen">
            <tbody>
                <tr>
                    <td colspan="8" class="titulo_tablas">1. DATOS DE LA ENTIDAD REMITENTE</td>
                </tr>
                <br>
                <tr>
                    <td colspan="2"><span class="titulo_labels">Tipo de entidad:</span></td>
                    <td colspan="6"><span class="dato_dinamico">{{$nombre_cliente}}</span></td>
                </tr>
                <tr>
                    <td colspan="1"><span class="titulo_labels">Dirección:</span></td>
                    <td colspan="3"><span class="dato_dinamico">{{$direccion_cliente}}</span></td>
                    <td colspan="1"><span class="titulo_labels">Ciudad:</span></td>
                    <td colspan="3"><span class="dato_dinamico">{{$ciudad_cliente}}</span></td>
                </tr>
                <tr>
                    <td colspan="1"><span class="titulo_labels">Teléfono:</span></td>
                    <td colspan="3"><span class="dato_dinamico">{{$teleonos_cliente}}</span></td>
                    <td colspan="1"><span class="titulo_labels">Fax:</span></td>
                    <td colspan="3"><span class="dato_dinamico"></span></td>
                </tr>
                <tr>
                    <td colspan="2"><span class="titulo_labels">Número de Folios:</span></td>
                    <td colspan="6"><span class="dato_dinamico"></span></td>
                </tr>
                <tr>
                    <td colspan="3"><span class="titulo_labels">Descripción de documentos anexos:</span></td>
                    <td colspan="5"><span class="dato_dinamico"></span></td>
                </tr>
                <br>
                <tr>
                    <td colspan="8" class="titulo_tablas">2. DATOS DE LA PERSONA REMITIDA</td>
                </tr>
                <br>
                <tr>
                    <td colspan="1"><span class="titulo_labels">Nombres:</span></td>
                    <td colspan="7"><span class="dato_dinamico">{{$nombre_afiliado}}</span></td>
                </tr>
                <tr>
                    <td colspan="2"><span class="titulo_labels">Documento de identidad Nro:</span></td>
                    <td colspan="2"><span class="dato_dinamico">{{$num_identificacion_afiliado}}</span></td>
                    <td colspan="1"><span class="titulo_labels">Tipo</span></td>
                    <td colspan="1"><span class="titulo_labels">CC </span><span class="dato_dinamico"><?php if($tipo_doc_afiliado == "CC"){echo "X";}?></span></td>
                    <td colspan="1"><span class="titulo_labels">TI </span><span class="dato_dinamico"><?php if($tipo_doc_afiliado == "TI"){echo "X";}?></span></td>
                    <td colspan="1"><span class="titulo_labels">Otro </span><span class="dato_dinamico"><?php if($tipo_doc_afiliado != "CC" && $tipo_doc_afiliado != "TI"){echo "X";}?></span></td>
                </tr>
                <tr>
                    <td colspan="1"><span class="titulo_labels">Expedido en:</span></td>
                    <td colspan="2"><span class="dato_dinamico"></span></td>
                    <td colspan="1"><span class="titulo_labels">Edad:</span></td>
                    <td colspan="1"><span class="dato_dinamico">{{$edad_afiliado}}</td>
                    <td colspan="1"><span class="titulo_labels">Género:</span></td>
                    <td colspan="1"><span class="titulo_labels">M </span><span class="dato_dinamico"><?php if($genero_afiliado == "Masculino"){echo "X";}?></span></td>
                    <td colspan="1"><span class="titulo_labels">F </span><span class="dato_dinamico"><?php if($genero_afiliado == "Femenino"){echo "X";}?></span></td>
                </tr>
                <tr>
                    <td colspan="2"><span class="titulo_labels">Fecha de nacimiento:</span></td>
                    <td colspan="2"><span class="dato_dinamico">{{$fecha_nacimiento_afiliado}}</span></td>
                    <td colspan="1"><span class="titulo_labels">Ciudad:</span></td>
                    <td colspan="3"><span class="dato_dinamico">{{$departamento_afiliado}} - {{$ciudad_afiliado}}</span></td>
                </tr>
                <tr>
                    <td colspan="1"><span class="titulo_labels">Dirección:</span></td>
                    <td colspan="3"><span class="dato_dinamico">{{$direccion_afiliado}}</span></td>
                    <td colspan="1"><span class="titulo_labels">Ciudad:</span></td>
                    <td colspan="3"><span class="dato_dinamico">{{$departamento_afiliado}} - {{$ciudad_afiliado}}</span></td>
                </tr>
                <tr>
                    <td colspan="2"><span class="titulo_labels">Teléfono:</span></td>
                    <td colspan="2"><span class="dato_dinamico">{{$telefono_afiliado}}</span></td>
                    <td colspan="2"><span class="titulo_labels">Celular:</span></td>
                    <td colspan="2"><span class="dato_dinamico">{{$telefono_afiliado}}</span></td>
                </tr>
                <tr>
                    <td colspan="2"><span class="titulo_labels">Tipo de vinculación al sistema:</span></td>
                    <td colspan="2"><span class="titulo_labels">Cotizante: </span><span class="dato_dinamico"><?php if($tipo_vinculacion == "Empleado actual" || $tipo_vinculacion == "Independiente"){echo "X";}?></span></td>
                    <td colspan="2"><span class="titulo_labels">Beneficiario: </span><span class="dato_dinamico"><?php if($tipo_vinculacion == "Beneficiario"){echo "X";}?></span></td>
                    <td colspan="2"><span class="titulo_labels">Otro: </span><span class="dato_dinamico"><?php if($tipo_vinculacion == ""){echo "X";}?></span></td>
                </tr>
                <tr>
                    <td colspan="1"><span class="titulo_labels">AFP:</span></td>
                    <td colspan="3"><span class="dato_dinamico">{{$afp_afiliado}}</span></td>
                    <td colspan="1"><span class="titulo_labels">ARL:</span></td>
                    <td colspan="3"><span class="dato_dinamico">{{$arl_afiliado}}</span></td>
                </tr>
                <br>
                <tr>
                    <td colspan="8" class="titulo_tablas">3. DATOS LABORALES DE LA PERSONA REMITIDA</td>
                </tr>
                <br>
                <tr>
                    <td colspan="1"><span class="titulo_labels">Empresa:</span></td>
                    <td colspan="7"><span class="dato_dinamico">{{$nombre_empresa}}</span></td>
                </tr>
                <tr>
                    <td colspan="1"><span class="titulo_labels">Dirección:</span></td>
                    <td colspan="7"><span class="dato_dinamico">{{$direccion_empresa}}</span></td>
                </tr>
                <tr>
                    <td colspan="1"><span class="titulo_labels">Teléfono:</span></td>
                    <td colspan="3"><span class="dato_dinamico">{{$telefono_empresa}}</span></td>
                    <td colspan="1"><span class="titulo_labels">Ciudad:</span></td>
                    <td colspan="3"><span class="dato_dinamico">{{$departamento_empresa}} - {{$ciudad_empresa}}</span></td>
                </tr>
                <tr>
                    <td colspan="1"><span class="titulo_labels">Actividad</span></td>
                    <td colspan="7"><span class="dato_dinamico">{{$actividad_empresa}}</span></td>
                </tr>
                <tr>
                    <td colspan="1"><span class="titulo_labels">Cargo actual:</span></td>
                    <td colspan="7"><span class="dato_dinamico">{{$cargo_empresa}}</span></td>
                </tr>
                <br>
                <tr>
                    <td colspan="8" class="titulo_tablas">4. MOTIVO DE LA REMISIÓN</td>
                </tr>
                <br>
                <tr>
                    <td colspan="2" class="underline"><span class="dato_dinamico"><?php if($pcl == "% PCL"){echo "X";}?></span></td>
                    <td colspan="6" class="titulo_labels"> Calificación del grado de la pérdida de capacidad laboral</td>
                </tr>
                <tr>
                    <td colspan="2" class="underline"><span class="dato_dinamico"><?php if($origen == "Origen"){echo "X";}?></span></td>
                    <td colspan="6" class="titulo_labels"> Calificación de origen</td>
                </tr>
                {{-- <tr>
                    <td colspan="2" class="underline"><span class="dato_dinamico"><?php if($diagnosticos == "Diagnósticos"){echo "X";}?></span></td>
                    <td colspan="6" class="titulo_labels"> Diagnósticos</td>
                </tr> --}}
                <tr>
                    <td colspan="2" class="underline"><span class="dato_dinamico"><?php if($fecha_estructuracion == "Fecha estructuración"){echo "X";}?></span></td>
                    <td colspan="6" class="titulo_labels"> Fecha estructuración</td>
                </tr>
                <tr>
                    <td colspan="2" class="underline"><span class="dato_dinamico"><?php if($manual_calificacion == "Manual de calificación"){echo "X";}?></span></td>
                    <td colspan="6" class="titulo_labels">Manual de calificación</td>
                </tr>
                <tr>
                    <td colspan="2"><span class="titulo_labels">Especifique:</span></td>
                    <td colspan="6" class="titulo_labels">Controversia {{$parte_controvierte_califi}}</td>
                </tr>
                <br>
                <tr>
                    <td colspan="8" class="titulo_tablas">5. RESPONSABLE DE LA REMISIÓN</td>
                </tr>
                <br>
                <tr>
                    <td colspan="2"><span class="titulo_labels">Nombres:</span></td>
                    <td colspan="6" class="dato_dinamico">Liliana Montes Castañeda</td> {{-- {{$nombre_usuario}} --}}
                </tr>
                <tr>
                    <td colspan="2"><span class="titulo_labels">Documento identificación:</span></td>
                    <td colspan="6" class="dato_dinamico">LSO Resolución 4919</td>
                </tr>
                <tr>
                    <td colspan="2"><span class="titulo_labels">Cargo:</span></td>
                    <td colspan="6" class="dato_dinamico">Médico Laboral</td> {{-- {{$cargo_usuario}} --}}
                </tr>
                <tr>
                    <td colspan="2"><span class="titulo_labels">Firma:</span></td>
                    <td colspan="6" class="dato_dinamico">
                        <?php 
                            $ruta_firma_3 = "/Firmas_provisionales/firma_liliana_sin_texto.png";
                            $imagenPath_firma_3 = public_path($ruta_firma_3);
                            $imagenData_firma_3 = file_get_contents($imagenPath_firma_3);
                            $imagenBase64_firma_3 = base64_encode($imagenData_firma_3);
                        ?>
                        <div style="">
                            <img src="data:image/png;base64,{{ $imagenBase64_firma_3 }}" class="firma_3">
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>