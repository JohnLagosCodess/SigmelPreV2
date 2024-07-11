<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        /* Estilo general del documento */
        @page {
            margin: 2.5cm 1.3cm;
            /* Márgenes: arriba, derecha, abajo, izquierda */
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        /* Estilo de la tabla principal */
        table {
            width: 100%;
            /* Ancho total de la tabla */
            border-spacing: 0;
            border-collapse: separate;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 0.1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        /* Alineación específica de celdas en la tabla */
        tbody td:first-of-type {
            text-align: left;
        }

        /* Estilos del encabezado y pie de página */
        .header,
        .footer {
            width: 100%;
            position: fixed;
            text-align: center;
        }

        .header {
            top: -2.2cm;
            width: 100%;
            text-align: right;
            /* Ajuste al margen superior */
        }

        .footer {
            bottom: -2.4cm;
            /* Ajuste al margen inferior */
            height: 14%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center;
            padding: 10px 0;
        }

        .footer .page {
            text-align: center;
        }

        .footer_image {
            max-width: 100%;
            max-height: 80%;
            margin-bottom: -5px;
        }

        /* Estilo del contenido del pie de página */
        .footer_content {
            margin-top: 10px;
            position: absolute;
            text-align: center;
        }

        .footer .page:after {
            content: counter(page, upper-decimal);
        }

        /* Otros estilos y clases específicos */
        .logo_header {
            width: 150px;
            height: auto;
        }

        .container {
            width: 100%;
            padding: 20px;
        }

        .info_cliente {
            width: 100%;
            margin-bottom: 20px;
        }

        .info_wrapper {
            display: flex;
            justify-content: space-between;
        }

        .n_cliente,
        .c_cliente {
            width: 50%;
            /* O ajusta el ancho según sea necesario */
            box-sizing: border-box;
            /* Para incluir el padding en el ancho */
        }

        .n_cliente {
            padding-left: 30px;
            width: 70%;
        }

        .c_cliente {
            width: 25%;
            float: right;
        }

        .seccion_2_tabla {
            text-align: center;
        }
        .info_adicional{
            width: 100%;
            margin-top: 40px;
            
        }
        .info_adicional label{
            padding: 30px;
        }
        .col-3{
            width: 30%;
        }
    </style>
</head>

<body>
    <div class="header">
        @if ($logo_header == 'Sin logo')
            <p>Sin logo</p>
        @else
            @php
                $ruta_logo = "/logos_clientes/{$id_cliente}/{$logo_header}";
                $imagenPath_header = public_path($ruta_logo);
                $imagenData_header = file_get_contents($imagenPath_header);
                $imagenBase64_header = base64_encode($imagenData_header);
            @endphp
            <img src="data:image/png;base64,{{ $imagenBase64_header }}" class="logo_header" @endif
    </div>

    <div class="info_cliente" style="width: 100%">
        <div class="info_wrapper">
            <label class="c_cliente">
                <strong>Cedula:</strong> {{ $identificacion }}
            </label>
            <label class="n_cliente">
                <strong>Nombre:</strong> {{ $afiliado }}
            </label>
        </div>
    </div>

    <div class="footer">
        @if ($footer == null)
            <p class="page">Página </p>
        @else
            @php
                $ruta_footer = "/footer_clientes/{$id_cliente}/{$footer}";
                $footer_path = public_path($ruta_footer);
                $footer_data = file_get_contents($footer_path);
                $footer_base64 = base64_encode($footer_data);
            @endphp

            <div class="footer_content">
                <img src="data:image/png;base64,{{ $footer_base64 }}" class="footer_image">
                <p class="page" style="color: black;">Página </p>
            </div>
        @endif
    </div>
    {{-- Container --}}
    <div id="container" class="container">
        <table class="table table-bordered table-fixed">
            <tr>
                <th rowspan="2" style="width: 370px;">Requerimientos mínimos</th>
                <th colspan="3">EVENTO</th>
                <th colspan="2">ENVIADO</th>
            </tr>
            <tr>
                <th>AT</th>
                <th>EL</th>
                <th>MUERTE</th>
                <th>SI</th>
                <th>NO</th>
            </tr>
            <tr>
                <th colspan="12">Responsabilidad del Empleador</th>
            </tr>
            <tbody>
                <tr>
                    <td>Formato Único de Reporte de Accidente de Trabajo FURA T o el que lo sustituya o adicione,
                        debidamente diligenciado por la entidad o persona responsable, o en su defecto, el aviso dado
                        por el representante del trabajador o por cualquiera de los interesados.</td>
                    <td>X</td>
                    <td>X</td>
                    <td>X</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>El informe del resultado de la investigación sobre el accidente realizado por el empleador
                        conforme lo exija la legislación laboral y seguridad social.</td>
                    <td>X</td>
                    <td>N/A</td>
                    <td>X</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Evaluaciones médicas ocupacionales de ingreso, periódicas o de egreso o retiro. Si el empleador
                        no contó con alguna de ellas deberá reposar en el expediente certificado por escrito de la no
                        existencia de la misma, caso en el cual la entidad de seguridad social debió informar esta
                        anomalía a la Dirección Territorial del Ministerio del Trabajo para la investigación y sanciones
                        a que hubiese lugar.</td>
                    <td>N/A</td>
                    <td>X</td>
                    <td>N/A</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Notificación al Usuario.</td>
                    <td>N/A</td>
                    <td>X</td>
                    <td>N/A</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Información ocupacional con descripción de la exposición ocupacional que incluyera la
                        Información referente a la exposición a factores de riesgo con mínimo los siguientes datos:</td>
                    <td>N/A</td>
                    <td>X</td>
                    <td>N/A</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>1. Definición de los factores de riesgo a los cuales se encontraba o encuentra expuesto el
                        trabajador, conforme al sistema de gestión de seguridad y salud en el trabajo.</td>
                    <td>N/A</td>
                    <td>X</td>
                    <td>N/A</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>2. Tiempo de exposición al riesgo o peligro durante su jornada laboral y/o durante el periodo de
                        trabajo, conforme al sistema de gestión de seguridad y salud en el trabajo.</td>
                    <td>N/A</td>
                    <td>X</td>
                    <td>N/A</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>3. Tipo de labor u oficio desempeñados durante el tiempo de exposición, teniendo en cuenta el
                        factor de riesgos que se está analizando como causal.</td>
                    <td>N/A</td>
                    <td>X</td>
                    <td>N/A</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>4. Jornada laboral real del trabajador.</td>
                    <td>N/A</td>
                    <td>X</td>
                    <td>N/A</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>5. Análisis de exposición al factor de riesgo al que se encuentra asociado la patología, lo cual
                        podrá estar en el análisis o evaluación de puestos de trabajo relacionado con la enfermedad en.
                    </td>
                    <td>N/A</td>
                    <td>X</td>
                    <td>N/A</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>6. Descripción del uso de determinadas herramientas, aparatos, equipos o elementos, si se
                        requiere.</td>
                    <td>N/A</td>
                    <td>X</td>
                    <td>N/A</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr class="seccion_2_tabla">
                    <td colspan="12" style="text-align: center";>Responsabilidad Entidad Primera Oportunidad</td>
                </tr>
                <tr>
                    <td>{{$lista_chequeo['Orden de pago honorarios']['Descripcion_documento']}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                        <td>@php echo $lista_chequeo['Orden de pago honorarios']['Incluido'] ?  'X' : ''; @endphp</td>
                        <td>@php echo $lista_chequeo['Orden de pago honorarios']['Incluido'] ? '' :  'X'; @endphp</td>  
                </tr>
                <tr>
                    <td>{{$lista_chequeo['Dictamen de Calificación']['Descripcion_documento']}}</td>
                    <td>X</td>
                    <td>X</td>
                    <td>X</td>
                        <td>@php echo $lista_chequeo['Dictamen de Calificación']['Incluido'] ?  'X' : ''; @endphp</td>
                        <td>@php echo $lista_chequeo['Dictamen de Calificación']['Incluido'] ? '' :  'X'; @endphp</td>
                </tr>
                <tr>
                    <td>{{$lista_chequeo['Notificación al usuario']['Descripcion_documento']}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                        <td>@php echo $lista_chequeo['Notificación al usuario']['Incluido'] ?  'X' : ''; @endphp</td>
                        <td>@php echo $lista_chequeo['Notificación al usuario']['Incluido'] ? '' :    'X'; @endphp</td>
                    
                </tr>
                <tr>
                    <td>{{$lista_chequeo['Apelación al dictamen']['Descripcion_documento']}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                        <td>@php echo $lista_chequeo['Apelación al dictamen']['Incluido'] ?   'X' : ''; @endphp</td>
                        <td>@php echo $lista_chequeo['Apelación al dictamen']['Incluido'] ? '' :  'X'; @endphp</td>
                   
                </tr>
                <tr>
                    <td>{{$lista_chequeo['Anexo G (Datos Generales)']['Descripcion_documento']}}</td>
                    <td>X</td>
                    <td>X</td>
                    <td>X</td>
                        <td>@php echo $lista_chequeo['Anexo G (Datos Generales)']['Incluido'] ?  'X' : ''; @endphp</td>
                        <td>@php echo $lista_chequeo['Anexo G (Datos Generales)']['Incluido'] ? '' :  'X'; @endphp</td>
                </tr>
                <tr>
                    <td>{{$lista_chequeo['Fotocopia Documento Identidad']['Descripcion_documento']}}</td>
                    <td>X</td>
                    <td>X</td>
                    <td>N/A</td>
                        <td>@php echo $lista_chequeo['Fotocopia Documento Identidad']['Incluido'] ?  'X' : ''; @endphp</td>
                        <td>@php echo $lista_chequeo['Fotocopia Documento Identidad']['Incluido'] ? '' :  'X'; @endphp</td>
                </tr>
                <tr>
                    <td>{{$lista_chequeo['Autorización historia clínica']['Descripcion_documento']}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                        <td>@php echo $lista_chequeo['Autorización historia clínica']['Incluido'] ?   'X' : ''; @endphp</td>
                        <td>@php echo $lista_chequeo['Autorización historia clínica']['Incluido'] ? '' :   'X'; @endphp</td>
                    
                </tr>
                <tr>
                    <td>{{$lista_chequeo['Historia clínica completa']['Descripcion_documento']}}</td>
                    <td>X</td>
                    <td>X</td>
                    <td>X</td>
                        <td>@php echo $lista_chequeo['Historia clínica completa']['Incluido'] ?   'X' : ''; @endphp</td>
                        <td>@php echo $lista_chequeo['Historia clínica completa']['Incluido'] ? '' :   'X'; @endphp</td>
                </tr>
                <tr>
                    <td>{{$lista_chequeo['Concepto de rehabilitación']['Descripcion_documento']}}</td>
                    <td>X</td>
                    <td>X</td>
                    <td>N/A</td>
                        <td>@php echo $lista_chequeo['Concepto de rehabilitación']['Incluido'] ?   'X' : ''; @endphp</td>
                        <td>@php echo $lista_chequeo['Concepto de rehabilitación']['Incluido'] ? '' :   'X'; @endphp</td>
                </tr>
                <tr>
                    <td>{{$lista_chequeo['Conceptos o recomendaciones y/o restricciones ocupacionales']['Descripcion_documento']}}</td>
                    <td>X</td>
                    <td>X</td>
                    <td>N/A</td>
                        <td>@php echo $lista_chequeo['Conceptos o recomendaciones y/o restricciones ocupacionales']['Incluido'] ?   'X' : ''; @endphp</td>
                        <td>@php echo $lista_chequeo['Conceptos o recomendaciones y/o restricciones ocupacionales']['Incluido'] ? '' :   'X'; @endphp</td>
                </tr>
                <tr>
                    <td>{{$lista_chequeo['Registro civil de defunción']['Descripcion_documento']}}</td>
                    <td>N/A</td>
                    <td>N/A</td>
                    <td>X</td>
                        <td>@php echo $lista_chequeo['Registro civil de defunción']['Incluido'] ?   'X' : ''; @endphp</td>
                        <td>@php echo $lista_chequeo['Registro civil de defunción']['Incluido'] ? '' :   'X'; @endphp</td>
                </tr>
                <tr>
                    <td>{{$lista_chequeo['Acta de levantamiento del cadáver']['Descripcion_documento']}}</td>
                    <td>N/A</td>
                    <td>N/A</td>
                    <td>X</td>
                        <td>@php echo $lista_chequeo['Acta de levantamiento del cadáver']['Incluido'] ?   'X' : ''; @endphp</td>
                        <td>@php echo $lista_chequeo['Acta de levantamiento del cadáver']['Incluido'] ? '' :   'X'; @endphp</td>
                </tr>
                <tr>
                    <td>{{$lista_chequeo['Protocolo de necropsia']['Descripcion_documento']}}</td>
                    <td>N/A</td>
                    <td>N/A</td>
                    <td>X</td>
                        <td>@php echo $lista_chequeo['Protocolo de necropsia']['Incluido'] ?   'X' : ''; @endphp</td>
                        <td>@php echo $lista_chequeo['Protocolo de necropsia']['Incluido'] ? '' :   'X'; @endphp</td>
                </tr>
                <tr>
                    <td>{{$lista_chequeo['Exámenes complementarios']['Descripcion_documento']}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                        <td>@php echo $lista_chequeo['Exámenes complementarios']['Incluido'] ?   'X' : ''; @endphp</td>
                        <td>@php echo $lista_chequeo['Exámenes complementarios']['Incluido'] ? '' :   'X'; @endphp</td>
                
                </tr>
                <tr>
                    <td>{{$lista_chequeo['Relación de incapacidades']['Descripcion_documento']}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                        <td>@php echo $lista_chequeo['Relación de incapacidades']['Incluido'] ?   'X' : ''; @endphp</td>
                        <td>@php echo $lista_chequeo['Relación de incapacidades']['Incluido'] ? '' :   'X'; @endphp</td>
                    
                </tr>
                <tr>
                    <td>{{$lista_chequeo['Dictamen Junta Regional']['Descripcion_documento']}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                        <td>@php echo $lista_chequeo['Dictamen Junta Regional']['Incluido'] ?   'X' : ''; @endphp</td>
                        <td>@php echo $lista_chequeo['Dictamen Junta Regional']['Incluido'] ? '' :   'X'; @endphp</td>
                    
                </tr>
                <tr>
                    <td>{{$lista_chequeo['Origen de la patología']['Descripcion_documento']}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                        <td>@php echo $lista_chequeo['Origen de la patología']['Incluido'] ?   'X' : ''; @endphp</td>
                        <td>@php echo $lista_chequeo['Origen de la patología']['Incluido'] ? '' :  'X'; @endphp</td>
                    
                </tr>
                <tr>
                    <td>{{$lista_chequeo['Guía Afiliado']['Descripcion_documento']}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                        <td>@php echo $lista_chequeo['Guía Afiliado']['Incluido'] ?   'X' : ''; @endphp</td>
                        <td>@php echo $lista_chequeo['Guía Afiliado']['Incluido'] ? '' :   'X'; @endphp</td>
                    
                </tr>
                <tr>
                    <td>{{$lista_chequeo['Guía Empleador']['Descripcion_documento']}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                        <td>@php echo $lista_chequeo['Guía Empleador']['Incluido'] ?   'X' : ''; @endphp</td>
                        <td>@php echo $lista_chequeo['Guía Empleador']['Incluido'] ? '' :   'X'; @endphp</td>
                    
                </tr>
                <tr>
                    <td>{{$lista_chequeo['Guía ARL']['Descripcion_documento']}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                        <td>@php echo $lista_chequeo['Guía ARL']['Incluido'] ?   'X' : ''; @endphp</td>
                        <td>@php echo $lista_chequeo['Guía ARL']['Incluido'] ? '' :   'X'; @endphp</td>
                
                </tr>
                <tr>
                    <td>{{$lista_chequeo['Guía AFP']['Descripcion_documento']}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                        <td>@php echo $lista_chequeo['Guía AFP']['Incluido'] ?   'X' : ''; @endphp</td>
                        <td>@php echo $lista_chequeo['Guía AFP']['Incluido'] ? '' :    'X'; @endphp</td>
                    
                </tr>
                <tr>
                    <td>{{$lista_chequeo['Guía EPS']['Descripcion_documento']}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                        <td>@php echo $lista_chequeo['Guía EPS']['Incluido'] ? 'X' : ''; @endphp</td>
                        <td>@php echo $lista_chequeo['Guía EPS']['Incluido'] ? '' : 'X'; @endphp</td>
                    
                </tr>
               {{-- @foreach ($lista_chequeo['comunicados'] as $comunicado)
                    <tr>
                        <td>{{$comunicado['Descripcion_documento']}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>X</td>
                        <td></td>
                    </tr>
                @endforeach --}}
                <tr>
                    <td>{{$lista_chequeo['Lista de chequeo']['Descripcion_documento']}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>X</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <div class="info_adicional">
            <div class="info_wrapper">
                <label><strong>AT:</strong> Accidente de trabajo</label>
                <label><strong>EL:</strong> Enfermedad laboral</label>
                <label><strong>NA:</strong> No aplica</label>
                <label><strong>X:</strong> Se requiere</label>
            </div>
        </div>
    </div>

</body>

</html>
