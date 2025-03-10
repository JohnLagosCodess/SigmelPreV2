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
            text-align: center; 
            /* background: green; */
        }
        .codigo_qr{
            /* position: absolute; */
            /* top: 5px;  */
            /* left: 5px;  */
            max-width: 90px; 
            max-height: 70px; 
        }

        .logo_header{
            /* position: absolute; */
            max-width: 100%;
            height: auto;
            /* left: 535px; */
            max-height: 75px; 
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
            margin-top: 10px;
        }
        .tabla_dictamen{
            font-family: sans-serif;
            text-align: justify;
            width: 100%; /* Ancho total de la tabla */
            table-layout: fixed;
            border-collapse: collapse; /* Borde de celda colapsado para evitar espacios adicionales */
        }

        .tabla_dictamen, td, th {
            border: 1px solid black;
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

        .titulos_labels_pequeños{
            background: #D9D9D9;
            font-weight: bold;
            color: black;
        }

        .dato_dinamico{
            font-size: 11px !important;
        }
        .firma_1, .firma_2, .firma_3{
            width: auto;
            height: 11.2%;
        }

        .consideracion_legal{
            font-size: 10px;
            font-style:italic;
            text-align: justify;
        }

        .quitar_borde_derecho{
            border-right: none !important;
        }

        .quitar_borde_izquierdo{
            border-left: none !important;
        }

        .texto_finalmente{
            font-size: 9px;
            text-align: center;
            border:none;
        }

        .firma_visado{
            text-align: center;
            font-size: 8px;
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
        .largos_textos{
            margin-left: 1px;
            width: 99%;
            border: 1.5px solid black;
            padding: 1.5px;
            border-collapse: collapse;
            font-family: sans-serif;
            text-align: justify;
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
        <table class="tabla_header">
            <tbody>
                <tr>
                    <!-- Columna izquierda (alineada a la izquierda) -->
                    <td style="width:50%; text-align: left;">
                        <img src="data:image/png;base64,{{ base64_encode($codigoQR) }}" class="codigo_qr" alt="Código QR">
                    </td>
                    <!-- Columna derecha (alineada a la derecha) -->
                    <td style="width:50%; text-align: right;">
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
                    <p class="page">Página </p>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="content">
        {{-- @for ($i=0; $i<40; $i++)
            <div class="hijo">{{$i}}</div>
        @endfor --}}
        <table class="tabla_dictamen">
            <tbody>
                <tr>
                    <td colspan="8" class="titulo_tablas">DICTAMEN DE ORIGEN</td>
                </tr>
                <tr>
                    <td colspan="8" class="titulo_tablas">1. INFORMACIÓN GENERAL</td>
                </tr>
                <tr>
                    <td colspan="3"><span class="titulo_labels">No Siniestro. </span><span class="dato_dinamico">{{$N_siniestro}}</span></td>
                    <td colspan="2"><span class="titulo_labels">Fecha de solicitud: </span><span class="dato_dinamico">{{$fecha_solicitud}}</span></td>
                    <td colspan="3"><span class="titulo_labels">Fecha concepto: </span><span class="dato_dinamico">{{$fecha_concepto}}</span></td>
                </tr>
                <tr>
                    <td colspan="8"><span class="titulo_labels">Ciudad: </span><span class="dato_dinamico">{{$ciudad}}</span></td>
                </tr>
                <tr>
                    <td colspan="8" class="titulo_tablas">2. DATOS PERSONALES</td>
                </tr>
                <tr>
                    <td colspan="8"><span class="titulo_labels">Nombres: </span><span class="dato_dinamico">{{$nombre_afiliado}}</span></td>
                </tr>
                <tr>
                    <td colspan="2"><span class="titulo_labels">Tipo Documento: </span><span class="dato_dinamico">{{$tipo_doc_afiliado}}</span></td>
                    <td colspan="2"><span class="titulo_labels">No. Identificación: </span><span class="dato_dinamico">{{$nro_ident_afiliado}}</span></td>
                    <td colspan="2"><span class="titulo_labels">Fecha nacimiento: </span><span class="dato_dinamico">{{$fecha_nacimiento_afiliado}}</span></td>
                    <td colspan="2"><span class="titulo_labels">Edad: </span><span class="dato_dinamico">{{$edad_afiliado}} años</span></td>
                </tr>
                <tr>
                    <td colspan="3"><span class="titulo_labels">Género: </span><span class="dato_dinamico">{{$genero_afiliado}}</span></td>
                    <td colspan="2"><span class="titulo_labels">Estado civil: </span><span class="dato_dinamico">{{$estado_civil_afiliado}}</span></td>
                    <td colspan="3"><span class="titulo_labels">Escolaridad: </span><span class="dato_dinamico">{{$escolaridad_afiliado}}</span></td>
                </tr>
                <tr>
                    <td colspan="3"><span class="titulo_labels">EPS: </span><span class="dato_dinamico">{{$eps_afiliado}}</span></td>
                    <td colspan="2"><span class="titulo_labels">ARL: </span><span class="dato_dinamico">{{$arl_afiliado}}</span></td>
                    <td colspan="3"><span class="titulo_labels">AFP: </span><span class="dato_dinamico">{{$afp_afiliado}}</span></td>
                </tr>
                <tr>
                    <td colspan="8" class="titulo_tablas">3. ANTECEDENTES LABORALES</td>
                </tr>

                <tr>
                    <td colspan="4"><span class="titulo_labels">Empresa: </span><span class="dato_dinamico">{{$empresa_laboral}}</span></td>
                    <td colspan="4"><span class="titulo_labels">Nit: </span><span class="dato_dinamico">{{$nit_cc_laboral}}</span></td>
                </tr>
                <tr>
                    <td colspan="4"><span class="titulo_labels">Cargo: </span><span class="dato_dinamico">{{$cargo_laboral}}</span></td>
                    <td colspan="4"><span class="titulo_labels">Antigüedad en cargo: </span><span class="dato_dinamico">{{$antiguedad_cargo_laboral}}</span></td>
                </tr>
                <tr>
                    <td colspan="8"><span class="titulo_labels">Actividad económica: </span><span class="dato_dinamico">{{$act_economica_laboral}}</span></td>
                </tr>

                <tr>
                    <td colspan="8" class="titulo_tablas">4. FUNDAMENTOS DE HECHO</td>
                </tr>
                <tr>
                    <td colspan="8" class="titulo_tablas">4.1. JUSTIFICACIÓN PARA EMISIÓN DE CONCEPTO DE PRESUNTO ORIGEN DE EVENTO</td>
                </tr>
            </tbody>
        </table>
        <div class="largos_textos dato_dinamico">
            {!! nl2br(e($justificacion_revision_origen)) !!}
        </div>
        <table class="tabla_dictamen">
            <tbody>
                <tr>
                    <td colspan="8" class="titulo_tablas">4.2 RELACIÓN DE DOCUMENTOS</td>
                </tr>
                <tr>
                    <td colspan="4" class="titulo_labels" style="text-align: center;">Nombre del documento</td>
                    <td colspan="4" class="titulo_labels" style="text-align: center;">Descripción del documento</td>
                </tr>
                @foreach ($documentos_relacionados as $documento)
                    <tr>
                        <td colspan="4" class="dato_dinamico">{{$documento['nombre']}}</td>
                        <td colspan="4" class="dato_dinamico">{{$documento['descripcion']}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="8" class="titulo_tablas">5. CONCEPTO DE PRESUNTO ORIGEN</td>
                </tr>
                <tr>
                    <td colspan="8"><span class="titulo_labels">TIPO DE EVENTO: </span><span class="dato_dinamico">{{$nombre_evento}}</span></td>
                </tr>
                <tr>
                    <td colspan="8"><span class="titulo_labels">ORIGEN: </span><span class="dato_dinamico">{{$origen}}</span></td>
                </tr>
                <tr>
                    <td colspan="8"><span class="titulo_labels">FECHA DE EVENTO: </span><span class="dato_dinamico">{{$fecha_evento}}</span></td>
                </tr>
                <tr>
                    <td colspan="8"><span class="titulo_labels">FECHA DE FALLECIMIENTO: </span><span class="dato_dinamico">{{$fecha_fallecimiento}}</span></td>
                </tr>
            </tbody>
        </table>
        <div class="largos_textos dato_dinamico">
            <span class="titulo_labels">SUSTENTACIÓN: </span>{!! nl2br(e($sustentacion_califi_origen)) !!}
        </div>
        <table class="tabla_dictamen">
            <tbody>
                <tr>
                    <td colspan="8">
                        <span class="titulo_labels">FUNDAMENTOS DE DERECHO: </span>
                        <span class="dato_dinamico">Como quiere que se trata de la emisión de un concepto sobre el presunto origen de un evento, 
                            más no un dictamen del mismo, se conceptúa con fundamento en la experticia del Equipo Interdisciplinario, 
                            siempre cumpliendo los parámetros que dispone las normas vigentes en la materia. 
                            (Ley 100 de 1993, decreto 776 de 2002, Ley 1562 del 2012)
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="8" class="consideracion_legal">
                        <b>CONSIDERACIONES LEGALES:</b> Basados en Ley 100 de 1993, Ley 1562 de 2012, 
                        Decreto 1352 de 2013 y artículo 142 de la Ley 019 de 2012. 
                        En caso de no estar de acuerdo con la calificación realizada, 
                        los interesados podrán presentar su apelación o inconformidad por escrito, 
                        dentro de los 10 días hábiles siguientes a la notificación de la misma, 
                        de acuerdo al artículo 142 del Decreto Ley 019 de 2012. 
                        Las controversias que surjan al respecto serán resueltas por las Juntas Regionales de Calificación de Invalidez, 
                        de conformidad con lo establecido en el Decreto 1352 de 2013.
                    </td>
                </tr>
                <tr>
                    <td colspan="8" class="titulo_tablas">6. RESPONSABLES DE LA CALIFICACIÓN COMITÉ INTERDISCIPLINARIO DE CALIFICACIÓN</td>
                </tr>
                @if (count($validacion_visado) > 0)
                    <?php $f_visado = date("m/d/Y", strtotime($validacion_visado[0]->F_visado_comite));?>
                    <tr>
                        <td colspan="4" class="dato_dinamico" style="text-align: center;">
                            <span style="font-weight: 700;">LINA MARCELA MAYORGA CULMA</span><br>
                            <span>Medicina Física y Rehabilitación - E.S.O<br>
                            RM 250623/09 - LSO 16640/22</span>
                            <p class="firma_visado"><b>Fecha de firma: {{$f_visado}}</b></p>
                        </td>
                        <td colspan="4" class="dato_dinamico">
                            <?php 
                                $ruta_firma_1 = "/Firmas_provisionales/firma_comite_lina_origen.png";
                                $imagenPath_firma_1 = public_path($ruta_firma_1);
                                $imagenData_firma_1 = file_get_contents($imagenPath_firma_1);
                                $imagenBase64_firma_1 = base64_encode($imagenData_firma_1);
                            ?>
                            <div style="text-align: center;">
                                <img src="data:image/png;base64,{{ $imagenBase64_firma_1 }}" class="firma_1">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="dato_dinamico" style="text-align: center;">
                            <span style="font-weight: 700;">JULIAN ENRIQUE CAMACHO GONZALEZ</span><br>
                            <span>Médico Laboral<br>
                                RM. 04036/2010 - Lic. S.O 28239 del 13/12/2022</span>
                            <p class="firma_visado"><b>Fecha de firma: {{$f_visado}}</b></p>
                        </td>
                        <td colspan="4" class="dato_dinamico">
                            <?php 
                                $ruta_firma_2 = "/Firmas_provisionales/firma_comite_julian_origen.png";
                                $imagenPath_firma_2 = public_path($ruta_firma_2);
                                $imagenData_firma_2 = file_get_contents($imagenPath_firma_2);
                                $imagenBase64_firma_2 = base64_encode($imagenData_firma_2);
                            ?>
                            <div style="text-align: center;">
                                <img src="data:image/png;base64,{{ $imagenBase64_firma_2 }}" class="firma_2">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="dato_dinamico" style="text-align: center;">
                            <span style="font-weight: 700;">LILIANA MONTES CASTAÑEDA</span><br>
                            <span>Fisioterapeuta<br>
                            LSO Resolución 4919 del 08/05/2012</span>
                            <p class="firma_visado"><b>Fecha de firma: {{$f_visado}}</b></p>
                        </td>
                        <td colspan="4" class="dato_dinamico">
                            <?php 
                                $ruta_firma_3 = "/Firmas_provisionales/firma_comite_liliana_origen.png";
                                $imagenPath_firma_3 = public_path($ruta_firma_3);
                                $imagenData_firma_3 = file_get_contents($imagenPath_firma_3);
                                $imagenBase64_firma_3 = base64_encode($imagenData_firma_3);
                            ?>
                            <div style="text-align: center;">
                                <img src="data:image/png;base64,{{ $imagenBase64_firma_3 }}" class="firma_3">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" class="quitar_borde_derecho"></td>
                        <td colspan="6" class="texto_finalmente">
                            <b>“Finalmente, reiteramos que en nuestra Compañía contamos con la mejor disposición para atender sus quejas y reclamos a 
                                través del defensor consumidor financiero, en la Av. Calle 26 No 59-15, local 6 y 7. Conmutador: 7435333 Extensión: 14454, Fax Ext. 14456 o Correo Electrónico: 
                                defensordelconsumidorfinanciero@segurosdevidaalfa.com.co”.
                            </b>
                        </td>
                        <td colspan="1" class="quitar_borde_izquierdo"></td>
                    </tr>
                @else
                    <tr>
                        <td colspan="4" class="dato_dinamico" style="text-align: center;"></td>
                        <td colspan="4" class="dato_dinamico"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="dato_dinamico" style="text-align: center;"></td>
                        <td colspan="4" class="dato_dinamico"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="dato_dinamico" style="text-align: center;"></td>
                        <td colspan="4" class="dato_dinamico"></td>
                    </tr>
                    <tr>
                        <td colspan="1" class="quitar_borde_derecho"></td>
                        <td colspan="6" class="texto_finalmente">
                            <b>“Finalmente, reiteramos que en nuestra Compañía contamos con la mejor disposición para atender sus quejas y reclamos a 
                                través del defensor consumidor financiero, en la Av. Calle 26 No 59-15, local 6 y 7. Conmutador: 7435333 Extensión: 14454, Fax Ext. 14456 o Correo Electrónico: 
                                defensordelconsumidorfinanciero@segurosdevidaalfa.com.co”.
                            </b>
                        </td>
                        <td colspan="1" class="quitar_borde_izquierdo"></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</body>
</html>