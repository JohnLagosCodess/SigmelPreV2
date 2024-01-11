<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>        
        /*.justificado{
            text-align: justify;
            padding-left: 0px;                
            white-space: pre-wrap;
            font-family: sans-serif;
            color: black;
        }        
        .firma{
            text-align: justify;
            padding-left: 40px;
        }
        .copias{
            text-align: justify;
            padding-left: 40px;
        }
        .justificados{                
            padding-left: 40px;
            white-space: pre-wrap;
        }
        .ajustetd{
            text-align: justify;
            padding-left: 40px;
            width: 410px;
        }
        .row {
            display: flex;
            justify-content: : space-between;
        }
        .col {
            float: left;
            box-sizing: border-box;
        }
        .col-1 { width: 8.33%; }
        .col-2 { width: 16.66%; }
        .col-3 { width: 25%; }
        .col-4 { width: 33.33%; }
        .col-5 { width: 41.66%; }
        .col-6 { width: 50%; }
        .col-7 { width: 58.33%; }
        .col-8 { width: 66.66%; }
        .col-9 { width: 75%; }
        .col-10 { width: 83.33%; }
        .col-11 { width: 91.66%; }
        .col-12 { width: 100%; }
        .card-body{padding: 1rem;} */
        @page { margin: 2.5cm 1.3cm 2.5cm 1.3cm; }        
        #header {
            position: fixed; 
            top: -2.2cm;
            left: 0cm;
            width: 100%;
            /* height: 100px; */
            text-align: center; 
        }

        .codigo_qr{
            position: absolute;
            top: 5px; 
            left: 5px; 
            max-width: 90px; 
            max-height: 70px; 
        }

        .titulo_header{            
            font-weight: bold;
            left: 15px; 
            max-width: 540px; 
            font-size: 15px;            
            color: black;
            text-align: center !important;
        }

        .logo_header{
            position: absolute;
            max-width: 40%;
            height: auto;
            left: 530px;
            max-height: 80px; 
        } 
        
        .sinborder{
            border: none;
        }

        .sinborderlaterales{
            border-top: 1px solid black;
            border-bottom: none;
            border-left: none;
            border-right: none;
        }

        .sinborderinferior{
            border-bottom: none;
        }

        #footer{
            position: fixed;
            bottom: -2.2cm;
            left: 0cm;
            width: 100%;
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

        .tabla_footer{
            width: 100%;
            font-family: sans-serif;
            font-size: 13px;
            text-align: center;            
        }

        .tabla_footer td {
            border: none;
        }
        
        .tabla_dictamen{
            font-family: sans-serif;
            text-align: justify;
            width: 100%;
            table-layout: fixed; 
            border-collapse: collapse;
        }

        .tabla_dictamen, td, th {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 1.5px
        }

        .titulo_tablas{
            font-weight: bold;
            font-size: 12px;
            background: #D9D9D9;
            color: black;
            text-align: center !important;
        }

        .titulo_labels{
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }

        .centrar_titulo_labels{
            text-align: center;
            font-weight: bold;
            font-size: 11px;
        }

        .centrar_dato_labels{
            text-align: center;
            font-size: 11px;
        }

        .right_titulo_labels{
            text-align: right;
            font-size: 11px;
        }

        .left_titulo_labels{
            text-align: left;
            font-size: 11px;
        }

        .titulos_labels_pequeños{
            background: #D9D9D9;
            font-weight: bold;
            color: black;
        }

        .dato_dinamico{
            text-align: justify;
            font-size: 11px !important;
        }

        .centrar_dato_dinamico{
            text-align: center;
            font-size: 11px !important;
        }

        .label_rol_laboral{
            text-align: center;
            font-size: 11px !important;
            background: #D9D9D9;
        }

        .label_area_ocupacional{
            text-align: center;
            font-size: 11px !important;
            font-weight: bold;            
            background: #D9D9D9;
        }

        .label_valoracion_final{
            text-align: right;
            font-size: 11px !important;
            font-weight: bold;            
            background: #D9D9D9;
        }

        .explicacionFB{
            text-align: center;
            font-size: 11px;
        }

        #footer .page:after { content: counter(page, upper-decimal); }   
        #content { margin-top: 10px; }         
    </style>    
</head>
<body>
    <div id="header">
        <table class="tabla_header">
            <tbody>
                <tr>
                    <td>
                        <img src="data:image/png;base64,{{ base64_encode($codigoQR) }}" class="codigo_qr" alt="Código QR">
                    </td>
                    <td>
                        <p class="titulo_header">
                            FORMULARIO DE CALIFICACIÓN DE LA PERDIDA 
                            DE LA CAPACIDAD LABORAL Y OCUPACIONAL
                            DECRETO 1507 agosto 12 de 2014
                        </p> 
                    </td>
                    <td>
                        <?php if($logo_header == "Sin logo"): ?>
                            <p>No logo</p>
                        <?php else: ?>
                            <?php 
                                $ruta_logo = "/logos_clientes/{$Id_cliente_ent}/{$logo_header}";
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
                    <td>
                        <p class="centrar">{{$ID_evento}} - {{$Id_Asignacion}} {{$Id_proceso}} - {{$Radicado_comuni}}</p>
                    </td>
                    <td>
                        <p class="page">Página </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>                     
    <div id="content">        
        <table class="tabla_dictamen">
            <tr>
                <td colspan="17" class="titulo_tablas">1. INFORMACIÓN GENERAL DEL DICTAMEN PERICIAL</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Fecha dictamen:</td>  
                <td colspan="5" class="dato_dinamico">{{$Fecha_dictamen}}</td>
                <td colspan="3" class="titulo_labels">Dictamen No</td>
                <td colspan="5" class="dato_dinamico">{{$DictamenNo}}</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Motivo de solicitud:</td>
                <td colspan="5" class="dato_dinamico">{{$Motivo_solicitud}}</td>
                <td colspan="3" class="titulo_labels">Solicitante:</td>
                <td colspan="5" class="dato_dinamico">{{$Solicitante_dic}}</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Nombre de solicitante: </td>
                <td colspan="13" class="dato_dinamico">{{$Nombre_entidad_dic}}</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Nit/Documento de Identidad:</td>
                <td colspan="5" class="dato_dinamico">{{$Nit_entidad}}</td>
                <td colspan="3" class="titulo_labels">Teléfono solicitante: </td>
                <td colspan="5" class="dato_dinamico">{{$Telefonos_dic}}</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Dirección solicitante: </td>
                <td colspan="13" class="dato_dinamico">{{$Direccion_dic}}</td>                
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">E-mail solicitante: </td>
                <td colspan="5" class="dato_dinamico">{{$Emails_dic}}</td>
                <td colspan="3" class="titulo_labels">Ciudad solicitante: </td>
                <td colspan="5" class="dato_dinamico">{{$Nombre_municipio_dic}}</td>
            </tr>   
            <tr>
                <td colspan="17" class="titulo_tablas">2. INFORMACIÓN GENERAL DE LA ENTIDAD CALIFICADORA</td>
            </tr>
            <tr>
                <td colspan="2" class="titulo_labels">Nombre:</td>
                <td colspan="5" class="dato_dinamico">{{$Nombre_cliente_ent}}</td>
                <td colspan="1" class="titulo_labels">Nit</td>
                <td colspan="3" class="dato_dinamico">{{$Nit_ent}}</td>
                <td colspan="3" class="titulo_labels">Teléfono:</td>
                <td colspan="3" class="dato_dinamico">{{$Telefono_principal_ent}}</td>
            </tr>
            <tr>
                <td colspan="2" class="titulo_labels">Dirección:</td>
                <td colspan="6" class="dato_dinamico">{{$Direccion_ent}}</td>
                <td colspan="2" class="titulo_labels">E-mail:</td>
                <td colspan="7" class="dato_dinamico">{{$Email_principal_ent}}</td>
            </tr>
            <tr>
                <td colspan="17" class="titulo_tablas">3. DATOS GENERALES DE LA PERSONA CALIFICADA</td>
            </tr>
            <tr>
                <td colspan="2" class="titulo_labels">Afiliado:</td>
                <td colspan="6" class="centrar_dato_dinamico">{{$Afiliado_per_cal}}</td>
                <td colspan="2" class="titulo_labels">Beneficiario:</td>
                <td colspan="7" class="centrar_dato_dinamico">{{$Beneficiario_per_cal}}</td>
            </tr>
            <tr>
                <td colspan="2" class="titulo_labels">Apellidos:</td>
                <td colspan="6" class="dato_dinamico"></td>
                <td colspan="2" class="titulo_labels">Nombre:</td>
                <td colspan="7" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Documento de identificación:</td>
                <td colspan="5" class="dato_dinamico"></td>
                <td colspan="3" class="titulo_labels">N° de identificación:</td>
                <td colspan="5" class="dato_dinamico"></td>
            </tr>
            <tr>                
                <td colspan="4" class="titulo_labels">Fecha nacimiento:</td>
                <td colspan="5" class="dato_dinamico"></td>
                <td colspan="3" class="titulo_labels">Edad:</td>
                <td colspan="5" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="2" class="titulo_labels">Escolaridad:</td>
                <td colspan="4" class="dato_dinamico"></td>
                <td colspan="2" class="titulo_labels">Estado civil:</td>
                <td colspan="4" class="dato_dinamico"></td>
                <td colspan="2" class="titulo_labels">Teléfono:</td>
                <td colspan="3" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="2" class="titulo_labels">Dirección:</td>
                <td colspan="4" class="dato_dinamico"></td>
                <td colspan="2" class="titulo_labels">Ciudad</td>
                <td colspan="4" class="dato_dinamico"></td>
                <td colspan="2" class="titulo_labels">E-mail:</td>
                <td colspan="3" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="17" class="dato_dinamico">En caso de calificar un beneficiario, anotar los datos del afiliado:</td>
            </tr>
            <tr>
                <td colspan="3" class="titulo_labels">Nombre y apellidos</td>
                <td colspan="5" class="dato_dinamico"></td>
                <td colspan="4" class="titulo_labels">Documento de identidad</td>
                <td colspan="5" class="dato_dinamico"></td>
            </tr>
            <tr>                
                <td colspan="3" class="titulo_labels">Teléfono</td>
                <td colspan="5" class="dato_dinamico"></td>
                <td colspan="4" class="titulo_labels">Ciudad</td>
                <td colspan="5" class="dato_dinamico"></td>
            </tr> 
            <tr>
                <td colspan="17" class="titulo_tablas">ETAPAS DEL CICLO VITAL:</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Población en edad económicamente activa:</td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="3" class="titulo_labels">Bebés y menores de 3 años:</td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="3" class="titulo_labels">Niños y adolescentes:</td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="3" class="titulo_labels">Adultos Mayores:</td>
                <td colspan="1" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="17" class="dato_dinamico">En caso de calificar un menor de edad, anotar los datos del acudiente o adulto responsable:</td>
            </tr>
            <tr>
                <td colspan="3" class="titulo_labels">Nombre y apellidos</td>
                <td colspan="5" class="dato_dinamico"></td>
                <td colspan="4" class="titulo_labels">Documento de identidad</td>
                <td colspan="5" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="3" class="titulo_labels">Teléfono</td>
                <td colspan="5" class="dato_dinamico"></td>
                <td colspan="4" class="titulo_labels">Ciudad</td>
                <td colspan="5" class="dato_dinamico"></td>
            </tr>  
            <tr>
                <td colspan="17" class="titulo_tablas">AFILIACIÓN AL  SISS:</td>
            </tr>
            <tr>
                <td colspan="3" class="titulo_labels">Régimen en salud</td>
                <td colspan="4" class="dato_dinamico">Contributivo:</td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="4" class="dato_dinamico">Subsidiado:</td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="3" class="dato_dinamico">No afiliado:</td>
                <td colspan="1" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="3" rowspan="2" class="titulo_labels">Administradoras:</td>
                <td colspan="4" class="centrar_titulo_labels">EPS:</td>
                <td colspan="3" class="centrar_titulo_labels">AFP:</td>
                <td colspan="4" class="centrar_titulo_labels">ARL:</td>
                <td colspan="3" class="centrar_titulo_labels">Otros:</td>
            </tr>
            <tr>
                <td colspan="4" class="dato_dinamico"></td>
                <td colspan="3" class="dato_dinamico"></td>
                <td colspan="4" class="dato_dinamico"></td>
                <td colspan="3" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="17" class="titulo_tablas">4. ANTECEDENTES LABORALES DEL CALIFICADO</td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Independiente:</td>
                <td colspan="5" class="dato_dinamico"></td>
                <td colspan="2" class="titulo_labels">Dependiente:</td>
                <td colspan="6" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Nombre del cargo:</td>
                <td colspan="5" class="dato_dinamico"></td>
                <td colspan="2" class="titulo_labels">Código CIUO:</td>
                <td colspan="6" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Funciones del cargo:</td>
                <td colspan="13" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="4" class="titulo_labels">Nombre de la empresa:</td>
                <td colspan="9" class="dato_dinamico"></td>
                <td colspan="1" class="titulo_labels">Nit:</td>
                <td colspan="3" class="dato_dinamico"></td>
            </tr>   
            <tr>
                <td colspan="17" class="titulo_tablas">5. RELACIÓN DE DOCUMENTOS/EXAMEN FÍSICO(Descripción)</td>
            </tr>
            <tr>
                <td colspan="2" class="centrar_titulo_labels">Fecha:</td>
                <td colspan="4" class="centrar_titulo_labels">Nombre del documento</td>
                <td colspan="11" class="centrar_titulo_labels">Descripción</td>
            </tr>
            <tr>
                <td colspan="2" class="dato_dinamico"></td>
                <td colspan="4" class="dato_dinamico"></td>
                <td colspan="11" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="17" class="titulo_tablas">6. FUNDAMENTOS PARA LA CALIFICACIÓN DE LA PERDIDA DE LA CAPACIDAD LABORAL Y OCUPACIONAL - TITULOS I Y II</td>
            </tr>
            <tr>
                <td colspan="5" class="titulo_labels">Descripción de la enfermedad Actual:</td>
                <td colspan="12" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="17"></td>
            </tr>
            <tr>
                <td colspan="17" class="titulo_tablas">TÍTULO I CLASIFICACIÓN / VALORACIÓN  DE LAS DEFICIENCIAS</td>
            </tr>
            <tr>
                <td colspan="1" class="titulo_labels">No.</td>
                <td colspan="2" class="titulo_labels">Código CIE 10</td>
                <td colspan="7" class="titulo_labels">Diagnóstico</td>
                <td colspan="1" class="titulo_labels">Origen</td>
                <td colspan="6" class="titulo_labels">Deficiencia(s) motivo de calificación / condiciones de salud</td>
            </tr>
            <tr>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="2" class="dato_dinamico"></td>
                <td colspan="7" class="dato_dinamico"></td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="6" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="17"></td>
            </tr>
            <tr>
                <td colspan="1" rowspan="2" class="titulo_labels">No.</td>
                <td colspan="2" rowspan="2" class="titulo_labels">Nombre de deficiencia</td>
                <td colspan="1" rowspan="2" class="titulo_labels">No. Tabla</td>
                <td colspan="1" rowspan="2" class="titulo_labels">Clase / FP</td>
                <td colspan="1" rowspan="2" class="titulo_labels">FU</td>
                <td colspan="1" rowspan="2" class="titulo_labels">CFM1</td>
                <td colspan="1" rowspan="2" class="titulo_labels">CFM2</td>
                <td colspan="1" rowspan="2" class="titulo_labels">CFM3</td>
                <td colspan="3" class="centrar_titulo_labels">Resultado</td>
                <td colspan="1" rowspan="2" class="titulo_labels">CAT</td>
                <td colspan="2" rowspan="2" class="titulo_labels">Dominancia</td>
                <td colspan="2" rowspan="2" class="titulo_labels">% Total Deficiencia (F. Balthazar, sin ponderar)</td>
            </tr>
            <tr>
                <td colspan="2" class="titulo_labels">Clase final y Literal</td>
                <td colspan="1" class="titulo_labels">% Defici.</td>
            </tr>
            <tr>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="2" class="dato_dinamico"></td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="2" class="dato_dinamico"></td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="2" class="dato_dinamico"></td>
                <td colspan="2" class="dato_dinamico"></td>
            </tr>           
            <tr>
                <td colspan="6" class="explicacionFB sinborder"><b>CFP:</b> Clase Factor principal</td>
                <td colspan="6" class="explicacionFB sinborder"><b>CFM:</b> Clase Factor Modulador</td>
                <td colspan="5" class="explicacionFB sinborder"><b>CFU:</b> Clase Factor único</td>
            </tr>
            <tr>
                <td colspan="17" class="explicacionFB sinborder"><b>Formula Ajuste Total de Deficiencias por tabla:</b> (CFM1-CFP) + (CFM2-CFP) + (CFM3-CFP)</td>
            </tr>
            <tr>
                <td colspan="8" rowspan="2" class="explicacionFB sinborder"><b>Fórmula de Balthazar:</b> Obtiene el valor final de las deficiencias sin ponderar</td>
                <td colspan="5" class="explicacionFB sinborder"><b>A +  (100 -  A) * B</b></td>
                <td colspan="4" class="explicacionFB sinborder">A: Deficiencia de mayor valor</td>
            </tr>
            <tr>
                <td colspan="5" class="explicacionFB sinborderlaterales"><b>100</b></td>
                <td colspan="4" class="explicacionFB sinborder">B: Deficiencia de menor valor</td>
            </tr>            
            <tr>
                <td colspan="16" class="explicacionFB"><b>CÁLCULO FINAL DE LA DEFICIENCIA PONDERADA (% Deficiencia sin ponderar X 0,5)</b>=</td>
                <td colspan="1" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="17"></td>
            </tr>
            <tr>
                <td colspan="17" class="titulo_tablas">TITULO II VALORACIÓN DEL ROL LABORAL, ROL OCUPACIONAL Y OTRAS ÁREAS OCUPACIONALES</td>
            </tr>
            <tr>
                <td colspan="17" class="dato_dinamico">Personas en edad económicamente activa (incluye menores trabajadores, jubilados, pensionados, adultos mayores que trabajan )</td>
            </tr>
            <tr>
                <td colspan="17" class="titulo_tablas">ROL LABORAL</td>
            </tr>
            <tr>
                <td colspan="1" rowspan="2" class="centrar_dato_labels">1</td>
                <td colspan="9" rowspan="2" class="centrar_dato_labels">Restricciones del rol laboral</td>
                <td colspan="1" class="label_rol_laboral">0,0</td>
                <td colspan="1" class="label_rol_laboral">5,0</td>
                <td colspan="1" class="label_rol_laboral">10,0</td>
                <td colspan="1" class="label_rol_laboral">15,0</td>
                <td colspan="1" class="label_rol_laboral">20,0</td>
                <td colspan="1" class="label_rol_laboral">25,0</td>
                <td colspan="1" rowspan="2" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="1" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="1" rowspan="2" class="centrar_dato_labels">2</td>
                <td colspan="9" rowspan="2" class="centrar_dato_labels">Restricciones autosuficiencia económica</td>
                <td colspan="1" class="label_rol_laboral">0,0</td>
                <td colspan="1" class="label_rol_laboral">1,0</td>
                <td colspan="1" class="label_rol_laboral">1,5</td>
                <td colspan="1" class="label_rol_laboral">2,0</td>
                <td colspan="1" class="label_rol_laboral">2,5</td>
                <td colspan="1" class="label_rol_laboral"></td>
                <td colspan="1" rowspan="2" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="1" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="1" rowspan="2" class="centrar_dato_labels">3</td>
                <td colspan="9" rowspan="2" class="centrar_dato_labels">Restricciones en función de la edad cronológica</td>
                <td colspan="1" class="label_rol_laboral">2,5</td>
                <td colspan="1" class="label_rol_laboral">0,5</td>
                <td colspan="1" class="label_rol_laboral">1,0</td>
                <td colspan="1" class="label_rol_laboral">1,5</td>
                <td colspan="1" class="label_rol_laboral">2,0</td>
                <td colspan="1" class="label_rol_laboral">2,5</td>
                <td colspan="1" rowspan="2" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="1" class="dato_dinamico"></td>
                <td colspan="1" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="16" class="right_titulo_labels"><b>Sumatoria rol laboral, autosuficiencia económica y edad (30%)</b></td>
                <td></td>
            </tr>
            <tr>
                {{-- <td colspan="1" class="dato_dinamico"><b>4</b></td> --}}
                <td colspan="17" class="titulo_tablas"><b>CALIFICACIÓN OTRAS ÁREAS OCUPACIONALES</b></td>
            </tr>
            <tr>
                <td colspan="17" class="sinborderinferior dato_dinamico">Valor según el grado de dificultad, ayuda y dependencia</td>
            </tr>
            <tr>
                <td colspan="4" rowspan="6" class="sinborder"></td>
                <td colspan="1" class="centrar_titulo_labels">CLASE</td>
                <td colspan="1" class="centrar_titulo_labels">VALOR</td>
                <td colspan="7" class="centrar_titulo_labels">CRITERIO CUALITATIVO</td>
                <td colspan="4" rowspan="6" class="sinborder"></td>
            </tr>
            <tr>
                <td class="centrar_dato_labels">A</td>
                <td class="centrar_dato_labels">0,0</td>
                <td colspan="7" class="centrar_dato_labels">No hay dificultad, No dependencia</td>
            </tr>
            <tr>
                <td class="centrar_dato_labels">B</td>
                <td class="centrar_dato_labels">0,1</td>
                <td colspan="7" class="centrar_dato_labels">Dificultad leve, No dependencia</td>
            </tr>
            <tr>
                <td class="centrar_dato_labels">C</td>
                <td class="centrar_dato_labels">0,2</td>
                <td colspan="7" class="centrar_dato_labels">Dificultad moderada, dependencia moderada</td>
            </tr>
            <tr>
                <td class="centrar_dato_labels">D</td>
                <td class="centrar_dato_labels">0,3</td>
                <td colspan="7" class="centrar_dato_labels">Dificultad severa, dependencia severa</td>
            </tr>
            <tr>
                <td class="centrar_dato_labels">E</td>
                <td class="centrar_dato_labels">0,4</td>
                <td colspan="7" class="centrar_dato_labels">Dificultad completa, dependencia completa</td>
            </tr>
            <tr>
                <td colspan="17" class="sinborder"></td>
            </tr>
            <tr>
                <td colspan="1" class="label_area_ocupacional">COD</td>
                <td colspan="5" class="label_area_ocupacional">ÁREA OCUPACIONAL</td>
                <td colspan="1" class="label_area_ocupacional">d110</td>
                <td colspan="1" class="label_area_ocupacional">d115</td>
                <td colspan="1" class="label_area_ocupacional">d140-145</td>
                <td colspan="1" class="label_area_ocupacional">d150</td>
                <td colspan="1" class="label_area_ocupacional">d163</td>
                <td colspan="1" class="label_area_ocupacional">d166</td>
                <td colspan="1" class="label_area_ocupacional">d170</td>
                <td colspan="1" class="label_area_ocupacional">d172</td>
                <td colspan="1" class="label_area_ocupacional">d175</td>
                <td colspan="1" class="label_area_ocupacional">d1751</td>
                <td colspan="1" rowspan="2" class="label_area_ocupacional">Total</td>
            </tr>
            <tr>
                <td colspan="1" rowspan="2" class="centrar_dato_labels">d1</td>
                <td colspan="1" rowspan="2" class="centrar_dato_labels">Tabla 6</td>
                <td colspan="4" rowspan="2" class="centrar_dato_labels">Aprendizaje y aplicación del conocimiento</td>
                <td colspan="1" class="label_area_ocupacional">1,1</td>
                <td colspan="1" class="label_area_ocupacional">1,2</td>
                <td colspan="1" class="label_area_ocupacional">1,3</td>
                <td colspan="1" class="label_area_ocupacional">1,4</td>
                <td colspan="1" class="label_area_ocupacional">1,5</td>
                <td colspan="1" class="label_area_ocupacional">1,6</td>
                <td colspan="1" class="label_area_ocupacional">1,7</td>
                <td colspan="1" class="label_area_ocupacional">1,8</td>
                <td colspan="1" class="label_area_ocupacional">1,9</td>
                <td colspan="1" class="label_area_ocupacional">1,10</td>
            </tr>
            <tr>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
            </tr>
            <tr>
                <td colspan="1" rowspan="3" class="centrar_dato_labels">d3</td>
                <td colspan="1" rowspan="3" class="centrar_dato_labels">Tabla 7</td>
                <td colspan="4" rowspan="3" class="centrar_dato_labels">Comunicación</td>
                <td colspan="1" class="label_area_ocupacional">d310</td>
                <td colspan="1" class="label_area_ocupacional">d315</td>
                <td colspan="1" class="label_area_ocupacional">d320</td>
                <td colspan="1" class="label_area_ocupacional">d325</td>
                <td colspan="1" class="label_area_ocupacional">d330</td>
                <td colspan="1" class="label_area_ocupacional">d335</td>
                <td colspan="1" class="label_area_ocupacional">d345</td>
                <td colspan="1" class="label_area_ocupacional">d350</td>
                <td colspan="1" class="label_area_ocupacional">d355</td>
                <td colspan="1" class="label_area_ocupacional">d360</td>
                <td colspan="1" rowspan="2" class="label_area_ocupacional">Total</td>
            </tr>
            <tr>
                <td colspan="1" class="label_area_ocupacional">2,1</td>
                <td colspan="1" class="label_area_ocupacional">2,2</td>
                <td colspan="1" class="label_area_ocupacional">2,3</td>
                <td colspan="1" class="label_area_ocupacional">2,4</td>
                <td colspan="1" class="label_area_ocupacional">2,5</td>
                <td colspan="1" class="label_area_ocupacional">2,6</td>
                <td colspan="1" class="label_area_ocupacional">2,7</td>
                <td colspan="1" class="label_area_ocupacional">2,8</td>
                <td colspan="1" class="label_area_ocupacional">2,9</td>
                <td colspan="1" class="label_area_ocupacional">2,10</td>
            </tr>
            <tr>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
            </tr>
            <tr>
                <td colspan="1" rowspan="3" class="centrar_dato_labels">d4</td>
                <td colspan="1" rowspan="3" class="centrar_dato_labels">Tabla 8</td>
                <td colspan="4" rowspan="3" class="centrar_dato_labels">Movilidad</td>
                <td colspan="1" class="label_area_ocupacional">d410</td>
                <td colspan="1" class="label_area_ocupacional">d415</td>
                <td colspan="1" class="label_area_ocupacional">d430</td>
                <td colspan="1" class="label_area_ocupacional">d440</td>
                <td colspan="1" class="label_area_ocupacional">d445</td>
                <td colspan="1" class="label_area_ocupacional">d455</td>
                <td colspan="1" class="label_area_ocupacional">d460</td>
                <td colspan="1" class="label_area_ocupacional">d465</td>
                <td colspan="1" class="label_area_ocupacional">d470</td>
                <td colspan="1" class="label_area_ocupacional">d475</td>
                <td colspan="1" rowspan="2" class="label_area_ocupacional">Total</td>
            </tr>
            <tr>
                <td colspan="1" class="label_area_ocupacional">3,1</td>
                <td colspan="1" class="label_area_ocupacional">3,2</td>
                <td colspan="1" class="label_area_ocupacional">3,3</td>
                <td colspan="1" class="label_area_ocupacional">3,4</td>
                <td colspan="1" class="label_area_ocupacional">3,5</td>
                <td colspan="1" class="label_area_ocupacional">3,6</td>
                <td colspan="1" class="label_area_ocupacional">3,7</td>
                <td colspan="1" class="label_area_ocupacional">3,8</td>
                <td colspan="1" class="label_area_ocupacional">3,9</td>
                <td colspan="1" class="label_area_ocupacional">3,10</td>
            </tr>
            <tr>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
            </tr>
            <tr>
                <td colspan="1" rowspan="3" class="centrar_dato_labels">d5</td>
                <td colspan="1" rowspan="3" class="centrar_dato_labels">Tabla 9</td>
                <td colspan="4" rowspan="3" class="centrar_dato_labels">Autocuidado - cuidado personal</td>
                <td colspan="1" class="label_area_ocupacional">d510</td>
                <td colspan="1" class="label_area_ocupacional">d520</td>
                <td colspan="1" class="label_area_ocupacional">d530</td>
                <td colspan="1" class="label_area_ocupacional">d540</td>
                <td colspan="1" class="label_area_ocupacional">d5401</td>
                <td colspan="1" class="label_area_ocupacional">d5402</td>
                <td colspan="1" class="label_area_ocupacional">d550</td>
                <td colspan="1" class="label_area_ocupacional">d560</td>
                <td colspan="1" class="label_area_ocupacional">d570</td>
                <td colspan="1" class="label_area_ocupacional">d5701</td>
                <td colspan="1" rowspan="2" class="label_area_ocupacional">Total</td>
            </tr>
            <tr>
                <td colspan="1" class="label_area_ocupacional">4,1</td>
                <td colspan="1" class="label_area_ocupacional">4,2</td>
                <td colspan="1" class="label_area_ocupacional">4,3</td>
                <td colspan="1" class="label_area_ocupacional">4,4</td>
                <td colspan="1" class="label_area_ocupacional">4,5</td>
                <td colspan="1" class="label_area_ocupacional">4,6</td>
                <td colspan="1" class="label_area_ocupacional">4,7</td>
                <td colspan="1" class="label_area_ocupacional">4,8</td>
                <td colspan="1" class="label_area_ocupacional">4,9</td>
                <td colspan="1" class="label_area_ocupacional">4,10</td>
            </tr>
            <tr>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
            </tr>
            <tr>
                <td colspan="1" rowspan="3" class="centrar_dato_labels">d6</td>
                <td colspan="1" rowspan="3" class="centrar_dato_labels">Tabla 10</td>
                <td colspan="4" rowspan="3" class="centrar_dato_labels">Vida doméstica</td>
                <td colspan="1" class="label_area_ocupacional">d610</td>
                <td colspan="1" class="label_area_ocupacional">d620</td>
                <td colspan="1" class="label_area_ocupacional">d6200</td>
                <td colspan="1" class="label_area_ocupacional">d630</td>
                <td colspan="1" class="label_area_ocupacional">d640</td>
                <td colspan="1" class="label_area_ocupacional">d6402</td>
                <td colspan="1" class="label_area_ocupacional">d650</td>
                <td colspan="1" class="label_area_ocupacional">d660</td>
                <td colspan="1" class="label_area_ocupacional">d6504</td>
                <td colspan="1" class="label_area_ocupacional">d6506</td>
                <td colspan="1" rowspan="2" class="label_area_ocupacional">Total</td>
            </tr>
            <tr>
                <td colspan="1" class="label_area_ocupacional">5,1</td>
                <td colspan="1" class="label_area_ocupacional">5,2</td>
                <td colspan="1" class="label_area_ocupacional">5,3</td>
                <td colspan="1" class="label_area_ocupacional">5,4</td>
                <td colspan="1" class="label_area_ocupacional">5,5</td>
                <td colspan="1" class="label_area_ocupacional">5,6</td>
                <td colspan="1" class="label_area_ocupacional">5,7</td>
                <td colspan="1" class="label_area_ocupacional">5,8</td>
                <td colspan="1" class="label_area_ocupacional">5,9</td>
                <td colspan="1" class="label_area_ocupacional">5,10</td>
            </tr>
            <tr>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
            </tr>   
            <tr>
                <td colspan="16" class="right_titulo_labels"><b>Sumatoria total otras áreas ocupacionales (20%)</b></td>
                <td></td>
            </tr>  
            <tr>
                <td colspan="17"></td>
            </tr>
            <tr>
                <td colspan="3" class="left_titulo_labels"><b>CÁLCULO FINAL PCO</b></td>
                <td colspan="13" class="right_titulo_labels"><b>Valor final de la segunda parte para las personas en edad económicamente activa</b></td>
                <td colspan="1" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="17"></td>
            </tr>
            <tr>
                <td colspan="17" class="titulo_tablas">7. CONCEPTO FINAL DEL DICTAMEN PERICIAL</td>
            </tr>
            <tr>
                <td colspan="17" class="centrar_dato_labels"><b>Pérdida de Capacidad Laboral</b> =  TÍTULO I -Valor Final Ponderada  +   TÍTULO II -Valor Final</td>
            </tr>
            <tr>
                <td colspan="16" class="label_valoracion_final">VALOR FINAL DE LA PCL / OCUPACIONAL %</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="17"></td>
            </tr>
            <tr>
                <td colspan="5" class="titulo_labels">FECHA DE ESTRUCTURACIÓN</td>
                <td colspan="4" class="dato_dinamico"></td>
                <td colspan="3" class="titulo_labels">TIPO DE EVENTO</td>
                <td colspan="5" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="9" class="titulo_labels">Sustentación de la Fecha de estructuración:</td>
                <td colspan="3" class="titulo_labels">FECHA ACCIDENTE / ENFERMEDAD</td>
                <td colspan="5" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="9" class="dato_dinamico"></td>
                <td colspan="3" class="titulo_labels">ORIGEN</td>
                <td colspan="5" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="17" class="titulo_labels">Detalle de la calificación</td>
            </tr>
            <tr>
                <td colspan="17" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="4" class="left_titulo_labels">Alto costo / Catastrófica:</td>
                <td colspan="5" class="dato_dinamico"></td>
                <td colspan="3" class="left_titulo_labels">Congénita o cercana a nacimiento:</td>
                <td colspan="5" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="4" class="left_titulo_labels">Revisión pensión:</td>
                <td colspan="5" class="dato_dinamico"></td>
                <td colspan="3" class="left_titulo_labels">Tipo de enfermedad / Deficiencia:</td>
                <td colspan="5" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="17" class="centrar_titulo_labels"><b>Clasificación condición de salud - Tipo de enfermedad (Marque con una X)</b></td>
            </tr>
            <tr>
                <td colspan="16" class="right_titulo_labels"><b>Requiere de terceras personas para realizar sus actividades de la vida diaria (áreas ocupacionales):</b></td>
                <td colspan="1" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="16" class="right_titulo_labels"><b>Se requiere de curador para la toma de decisiones:</b></td>
                <td colspan="1" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="16" class="right_titulo_labels"><b>Requiere de dispositivo de apoyo para realizar actividades de la vida diaria (áreas ocupacionales):</b></td>
                <td colspan="1" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="4" class="left_titulo_labels">Justificación de dependencia:</td>
                <td colspan="13" class="dato_dinamico"></td>
            </tr>
            <tr>
                <td colspan="17" class="titulo_tablas">8. GRUPO CALIFICADOR</td>
            </tr>
            <tr>
                <td colspan="6" class="dato_dinamico">Firma</td>
                <td colspan="5" class="dato_dinamico">Firma</td>
                <td colspan="6" class="dato_dinamico">Firma</td>
            </tr>
            <tr>
                <td colspan="6" class="dato_dinamico">Nombre de profesional 1</td>
                <td colspan="5" class="dato_dinamico">Nombre de profesional 3</td>
                <td colspan="6" class="dato_dinamico">Nombre de profesional 3</td>
            </tr>
            <tr>
                <td colspan="6" class="dato_dinamico">Cargo</td>
                <td colspan="5" class="dato_dinamico">Cargo</td>
                <td colspan="6" class="dato_dinamico">Cargo</td>
            </tr>
            <tr>
                <td colspan="6" class="dato_dinamico">Licencia / Identificación</td>
                <td colspan="5" class="dato_dinamico">Licencia / Identificación</td>
                <td colspan="6" class="dato_dinamico">Licencia / Identificación</td>
            </tr>
        </table>                                                                              
    </div>    
</body>
</html>

