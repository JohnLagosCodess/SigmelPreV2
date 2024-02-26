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

        .codigo_qr{
            position: absolute;
            top: 5px; /* Ajusta la distancia desde la parte superior según tus necesidades */
            left: 5px; /* Ajusta la distancia desde la izquierda según tus necesidades */
            max-width: 90px; /* Ajusta el ancho máximo del código QR según tus necesidades */
            max-height: 70px; /* Ajusta la altura máxima del código QR según tus necesidades */
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
        /* .hijo{
            width: 2cm;
            height: 1cm;
            margin: 0.2cm;
            background-color: yellow;
        } */
        .firma_1, .firma_2, .firma_3{
            width: auto;
            height: 11.2%;
        }
    </style>
</head>
<body>
    <?php 
        $fecha_dictamenF = date("d-m-Y", strtotime($fecha_dictamen));
        $f_nacimiento_calificadoF = date("d-m-Y", strtotime($f_nacimiento_calificado));
        $fecha_eventoF = date("d-m-Y", strtotime($fecha_evento));
        $fecha_fallecimientoF = date("d-m-Y", strtotime($fecha_fallecimiento));    
    ?>
    <div id="header">
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
                    <td colspan="8" class="titulo_tablas">FORMULARIO DE CALIFICACIÓN PARA LA DETERMINACIÓN DE ORIGEN (ACCIDENTE O ENFERMEDAD).</td>
                </tr>
                <tr>
                    <td colspan="8" class="titulo_tablas">1. INFORMACIÓN GENERAL DEL DICTAMEN PERICIAL</td>
                </tr>
                <tr>
                    <td colspan="2" class="titulo_labels">Fecha dictamen:</td>
                    <td colspan="2" class="dato_dinamico">{{$fecha_dictamenF}}</td>
                    <td colspan="2" class="titulo_labels">Dictamen No</td>
                    <td colspan="2" class="dato_dinamico">{{$nro_dictamen}}</td>
                </tr>
                <tr>
                    <td colspan="2" class="titulo_labels">Motivo de solicitud:</td>
                    <td colspan="2" class="dato_dinamico">{{$motivo_solicitud}}</td>
                    <td colspan="2" class="titulo_labels">Solicitante:</td>
                    <td colspan="2" class="dato_dinamico">{{$solicitante}}</td>
                </tr>
                <tr>
                    <td colspan="2" class="titulo_labels">Nombre de solicitante:</td>
                    <td colspan="6" class="dato_dinamico">{{$nombre_solicitante}}</td>
                </tr>
                <tr>
                    <td colspan="2" class="titulo_labels">Nit/Documento de Identidad:</td>
                    <td class="dato_dinamico">{{$nit_solicitante}}</td>
                    <td class="titulo_labels">Teléfono</td>
                    <td class="dato_dinamico">{{$telefono_solicitante}}</td>
                    <td colspan="2" class="titulo_labels">Dirección solicitante:</td>
                    <td class="dato_dinamico">{{$direccion_solicitante}}</td>
                </tr>
                <tr>
                    <td colspan="2" class="titulo_labels">E-mail solicitante:</td>
                    <td colspan="2" class="dato_dinamico">{{$correo_solicitante}}</td>
                    <td colspan="2" class="titulo_labels">Ciudad solicitante:</td>
                    <td colspan="2" class="dato_dinamico">{{$ciudad_solicitante}}</td>
                </tr>
                <tr>
                    <td colspan="8" class="titulo_tablas">2. INFORMACIÓN GENERAL DE LA ENTIDAD CALIFICADORA</td>
                </tr>
                <tr>
                    <td colspan="2" class="titulo_labels">Nombre:</td>
                    <td colspan="1" class="dato_dinamico">{{$nombre}}</td>
                    <td class="titulo_labels">Nit</td>
                    <td colspan="2" class="dato_dinamico">{{$nit}}</td>
                    <td class="titulo_labels">Teléfono:</td>
                    <td class="dato_dinamico">{{$telefono}}</td>
                </tr>
                <tr>
                    <td colspan="2" class="titulo_labels">Dirección:</td>
                    <td colspan="2" class="dato_dinamico">{{$direccion}}</td>
                    <td colspan="2" class="titulo_labels">E-mail:</td>
                    <td colspan="2" class="dato_dinamico">{{$email}}</td>
                </tr>
                <tr>
                    <td colspan="8" class="titulo_tablas">3. DATOS PERSONALES DEL CALIFICADO</td>
                </tr>
                <tr>
                    <td colspan="2" class="titulo_labels">Afiliado:</td>
                    <td colspan="2" class="dato_dinamico">@if ($tipo_afiliado == "Cotizante") X @endif</td>
                    <td colspan="2" class="titulo_labels">Beneficiario:</td>
                    <td colspan="2" class="dato_dinamico">@if ($tipo_afiliado == "Beneficiario") X @endif</td>
                </tr>
                <tr>
                    <td colspan="2" class="titulo_labels">Nombres y Apellidos:</td>
                    <td colspan="6" class="dato_dinamico">{{$nombre_calificado}}</td>
                </tr>
                <tr>
                    <td colspan="1" class="titulo_labels">Tipo de Documento:</td>
                    <td colspan="1" class="dato_dinamico">{{$tipo_doc_calificado}}</td>
                    <td colspan="1" class="titulo_labels">No. Identificación:</td>
                    <td colspan="1" class="dato_dinamico">{{$nro_ident_calificado}}</td>
                    <td colspan="1" class="titulo_labels">Fecha nacimiento:</td>
                    <td colspan="1" class="dato_dinamico">{{$f_nacimiento_calificadoF}}</td>
                    <td colspan="1" class="titulo_labels">Edad:</td>
                    <td colspan="1" class="dato_dinamico">{{$edad_calificado}}</td>
                </tr>
                <tr>
                    <td colspan="1" class="titulo_labels">Género:</td>
                    <td colspan="2" class="dato_dinamico">{{$genero_calificado}}</td>
                    <td colspan="1" class="titulo_labels">Estado civil:</td>
                    <td colspan="2" class="dato_dinamico">{{$estado_civil_calificado}}</td>
                    <td colspan="1" class="titulo_labels">Escolaridad:</td>
                    <td colspan="1" class="dato_dinamico">{{$escolaridad_calificado}}</td>
                </tr>
                <tr>
                    <td colspan="3"><span class="titulo_labels">EPS:</span> <span class="dato_dinamico">{{$eps_calificado}}</span></td>
                    <td colspan="2"><span class="titulo_labels">ARL:</span> <span class="dato_dinamico">{{$arl_calificado}}</span></td>
                    <td colspan="3"><span class="titulo_labels">AFP:</span> <span class="dato_dinamico">{{$afp_calificado}}</span></td>
                </tr>

                <tr>
                    <td colspan="8" class="titulo_tablas">4. ANTECEDENTES LABORALES DEL CALIFICADO</td>
                </tr>
                @foreach ($informacion_antecedentes_laborales as $antecedente_laboral)
                    <tr>
                        <td colspan="4"><span class="titulo_labels">Empresa:</span> <span class="dato_dinamico">{{$antecedente_laboral['empresa']}}</span></td>
                        <td colspan="4"><span class="titulo_labels">Nit:</span> <span class="dato_dinamico">{{$antecedente_laboral['nit_cc']}}</span></td>
                    </tr>
                    <tr>
                        <td colspan="4"><span class="titulo_labels">Cargo:</span> <span class="dato_dinamico">{{$antecedente_laboral['cargo']}}</span></td>
                        <td colspan="4"><span class="titulo_labels">Antigüedad en cargo:</span> <span class="dato_dinamico">{{$antecedente_laboral['antiguedad']}}</span></td>
                    </tr>
                    <tr>
                        <td colspan="8"><span class="titulo_labels">Actividad económica:</span> <span class="dato_dinamico">{{$antecedente_laboral['actividad_economica']}}</span></td>
                    </tr>
                    <tr>
                        <td colspan="8"><span class="titulo_labels">Funciones del cargo:</span> <span class="dato_dinamico">{{$antecedente_laboral['funciones']}}</span></td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="8" class="titulo_tablas">5. FUNDAMENTOS DE LA CALIFICACIÓN</td>
                </tr>
                <tr>
                    <td colspan="8" class="titulo_tablas">5.1. JUSTIFICACION PARA REVISION DE ORIGEN DEL EVENTO</td>
                </tr>
                <tr>
                    <td colspan="8" class="dato_dinamico">{{$justi_revision_origen}}</td>
                </tr>
                <tr>
                    <td colspan="8" class="titulo_tablas">5.2. INFORMACIÓN DEL ACCIDENTE</td>
                </tr>
                <tr>
                    <td colspan="2" class="titulo_labels">Fecha del evento:</td>
                    <td colspan="2" class="dato_dinamico">{{$fecha_eventoF}}</td>
                    <td colspan="2" class="titulo_labels">Hora del evento (24hh:mm):</td>
                    <td colspan="2" class="dato_dinamico">{{$hora_evento}}</td>
                </tr>
                <tr>
                    <td colspan="2" class="titulo_labels">Descripción Furat: </td>
                    <td colspan="6" class="dato_dinamico">{{$furat}}</td>
                </tr>
                <tr>
                    <td colspan="8" class="titulo_tablas">5.3. RELACIÓN DE DOCUMENTOS</td>
                </tr>
                <tr>
                    <td colspan="2" class="titulo_labels">Fecha atención  o valoración  del documento</td>
                    <td colspan="2" class="titulo_labels">Nombre relacion de documentos</td>
                    <td colspan="4" class="titulo_labels">Descripción relacion de documento </td>
                </tr>
                @foreach ($documentos_relacionados as $documento)
                    <tr>
                        <td colspan="2" class="dato_dinamico">{{date('d/m/Y', strtotime($documento['fecha']))}}</td>
                        <td colspan="2" class="dato_dinamico">{{$documento['nombre']}}</td>
                        <td colspan="4" class="dato_dinamico">{{$documento['descripcion']}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="8" class="titulo_tablas">5.4. DIAGNOSTICO MOTIVO DE CALIFICACIÓN</td>
                </tr>
                <tr>
                    <td colspan="1" class="titulo_labels">CIE 10</td>
                    <td colspan="1" class="titulo_labels">Origen del Diagnóstico</td>
                    <td colspan="2" class="titulo_labels">Nombre del Diagnóstico</td>
                    <td colspan="2" class="titulo_labels">Descripción Complementaria del Diagnóstico</td>
                    <td colspan="2" class="titulo_labels">Lateralidad</td>
                </tr>
                @foreach ($dx_motivo_calificacion as $dx)
                    <tr>
                        <td colspan="1" class="dato_dinamico">{{$dx['cie10']}}</td>
                        <td colspan="1" class="dato_dinamico">{{$dx['origen']}}</td>
                        <td colspan="2" class="dato_dinamico">{{$dx['nombre']}}</td>
                        <td colspan="2" class="dato_dinamico">{{$dx['descripcion']}}</td>
                        <td colspan="2" class="dato_dinamico">{{$dx['lateralidad']}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="8" class="titulo_tablas">6. CALIFICACION DE ORIGEN</td>
                </tr>
                <tr>
                    <td colspan="1" class="titulo_labels">Evento:</td>
                    <td colspan="2" class="dato_dinamico">{{$nombre_evento}}</td>
                    <td colspan="1" class="titulo_labels">Mortal:</td>
                    <td colspan="1" class="dato_dinamico">{{$mortal}}</td>
                    <td colspan="1" class="titulo_labels">Fecha de fallecimiento:</td>
                    <td colspan="2" class="dato_dinamico">{{$fecha_fallecimientoF}}</td>
                </tr>
                <tr>
                    <td colspan="1" class="titulo_labels">Origen:</td>
                    <td colspan="7" class="dato_dinamico">{{$origen}}</td>
                </tr>
                <tr>
                    <td colspan="8" class="titulo_tablas">6.1 SUSTENTACIÓN</td>
                </tr>
                <tr>
                    <td colspan="8" class="dato_dinamico">{{$sustentacion}}</td>
                </tr>
                <tr>
                    <td colspan="8" class="dato_dinamico">
                        <b>Consideraciones legales:</b> Basados en Ley 100 de 1993, Ley 1562 de 2012, 
                        Decreto 1352 de 2013 y artículo 142 de la Ley 019 de 2012. 
                        En caso de no estar de acuerdo con la calificación realizada, 
                        los interesados podrán presentar su apelación o inconformidad por escrito, 
                        dentro de los 10 días hábiles siguientes a la notificación de la misma, 
                        de acuerdo al artículo 142 del Decreto Ley 019de 2012. 
                        Las controversias que surjan al respecto serán resueltas por las Juntas Regionales de Calificación de Invalidez, 
                        de conformidad con lo establecido en el Decreto 1352 de 2013.
                    </td>
                </tr>
                <tr>
                    <td colspan="8" class="titulo_tablas">7. RESPONSABLES DE LA CALIFICACIÓN COMITÉ INTERDISCIPLINARIO DE CALIFICACIÓN</td>
                </tr>
                <tr>
                    <td colspan="2" class="dato_dinamico">
                        <?php 
                            $ruta_firma_1 = "/Firmas_provisionales/firma_lina.png";
                            $imagenPath_firma_1 = public_path($ruta_firma_1);
                            $imagenData_firma_1 = file_get_contents($imagenPath_firma_1);
                            $imagenBase64_firma_1 = base64_encode($imagenData_firma_1);
                        ?>
                        <div style="text-align: center;">
                            <img src="data:image/png;base64,{{ $imagenBase64_firma_1 }}" class="firma_1">
                        </div>
                    </td>
                    <td colspan="3" class="dato_dinamico">
                        <?php 
                            $ruta_firma_2 = "/Firmas_provisionales/firma_julian.png";
                            $imagenPath_firma_2 = public_path($ruta_firma_2);
                            $imagenData_firma_2 = file_get_contents($imagenPath_firma_2);
                            $imagenBase64_firma_2 = base64_encode($imagenData_firma_2);
                        ?>
                        <div style="text-align: center;">
                            <img src="data:image/png;base64,{{ $imagenBase64_firma_2 }}" class="firma_2">
                        </div>
                    </td>
                    <td colspan="3" class="dato_dinamico">
                        <?php 
                            $ruta_firma_3 = "/Firmas_provisionales/firma_liliana.png";
                            $imagenPath_firma_3 = public_path($ruta_firma_3);
                            $imagenData_firma_3 = file_get_contents($imagenPath_firma_3);
                            $imagenBase64_firma_3 = base64_encode($imagenData_firma_3);
                        ?>
                        <div style="text-align: center;">
                            <img src="data:image/png;base64,{{ $imagenBase64_firma_3 }}" class="firma_3">
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>