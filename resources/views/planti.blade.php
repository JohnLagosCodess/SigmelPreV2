<html>
    <head>
        <style>
            /** Define the margins of your page **/
            @page {
            margin: 100px 25px;
            }

            header {
            position: fixed;
            top: -60px;
            left: 0px;
            right: 0px;
            /* height: 50px; */

            /** Extra personal styles **/
            /* background-color: #03a9f4;
            color: white;
            text-align: center;
            line-height: 35px; */
            }

            footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 50px;

            /** Extra personal styles **/
            background-color: #03a9f4;
            color: white;
            text-align: center;
            line-height: 35px;
            }
            .general{
            color:black;
            font-family: "sans-serif" !important;
            font-size: 18px;
            text-align: center;
            border: none;
            background: #A7C3A4;
            }
            .datos_empleado{
                color:black;
                font-family: "sans-serif" !important;
                font-size: 18px;
                border: none;
            }
            .intralaboral{
                color: black;
                font-family: "sans-serif" !important;
                font-size: 16px;
                background:#A7C3A4;
                text-align: center;
            }
            .dom1{
                color: black;
                font-family: "sans-serif" !important;
                font-size: 14px;
                background:#A7C3A4;
                padding-left: 5px;
            }
            .dom2{
                color: black;
                font-family: "sans-serif" !important;
                font-size: 14px;
                background:#A7C3A4;
                padding-left: 5px;
            }
            .dom3{
                color: black;
                font-family: "sans-serif" !important;
                font-size: 14px;
                background:#A7C3A4;
                padding-left: 5px;
            }
            .dom4{
                color: black;
                font-family: "sans-serif" !important;
                font-size: 14px;
                background:#A7C3A4;
                padding-left: 5px;
            }
            .dom_extra{
                color: black;
                font-family: "sans-serif" !important;
                font-size: 14px;
                background:#A7C3A4;
                padding-left: 5px;
            }
            .observaciones{
                color: black;
                font-family: "sans-serif" !important;
                font-size: 14px;
                background:#A7C3A4;
                text-align: center;
            }
            .info_nivel_riesgo li {
                font-family: "sans-serif" !important;
                font-size: 12px;
                text-align: justify;
                color: black;
                list-style-type: disc;
                margin-top: 8px;
                padding-right: 10px;
                padding-bottom: 9px;
            }
            .estres{
                color: black;
                font-family: "sans-serif" !important;
                font-size: 14px;
                background:#A7C3A4;
                text-align: center; 
            }
            .elaboracion{
                color: black;
                font-family: "sans-serif" !important;
                font-size: 14px;
                background:#A7C3A4;
                padding-left: 2px;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
            <img src="data:image/png;base64,{{ base64_encode($codigoQR) }}" alt="Código QR">
        </header>

        <footer>
            Copyright © <?php echo date("Y");?>
        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            <!-- EMPLEADO -->
            <table style='width:100%; border-collapse:collapse; border:none'>
            <tr>
            <td >
                <p class="general"><span>DATOS GENERALES DEL TRABAJADOR</span></p>
            </td>
            </tr>
            </table>
            <table style='border-collapse:collapse; border:none; margin-top: -20px;'>
                <tr class='datos_empleado'>
                    <td width=246>
                        <p style="background: #A7C3A4; padding-left: 5px;"><span>Nombre del trabajador:</span></p>
                    </td>
                    <td width=28>
                        <p><span>&nbsp;</span></p>
                    </td>
                    <td width=249>
                        <p style="border: 1px solid black; padding-left: 5px;">{{ $nombre }}</p>
                    </td>
                </tr>
            </table>
            <table style='border-collapse:collapse; border:none; margin-top: -20px;'>
                <tr class='datos_empleado'>
                    <td width=246>
                        <p style="background: #A7C3A4; padding-left: 5px;"><span>Número de identificación (ID):</span></p>
                    </td>
                    <td width=28>
                        <p><span>&nbsp;</span></p>
                    </td>
                    <td width=249>
                        <p style="border: 1px solid black; padding-left: 5px;">{{ $cedula }}</p>
                    </td>
                </tr>
            </table>
            <table style='border-collapse:collapse; border:none; margin-top: -20px;'>
                <tr class='datos_empleado'>
                    <td width=246>
                        <p style="background: #A7C3A4; padding-left: 5px;"><span>Cargo:</span></p>
                    </td>
                    <td width=28>
                        <p><span>&nbsp;</span></p>
                    </td>
                    <td width=249>
                        <p style="border: 1px solid black; padding-left: 5px;">{{ $cargo }}</p>
                    </td>
                </tr>
            </table>
            <table style='border-collapse:collapse; border:none; margin-top: -20px;'>
                <tr class='datos_empleado'>
                    <td width=246>
                        <p style="background: #A7C3A4; padding-left: 5px;"><span>Departamento o sección:</span></p>
                    </td>
                    <td width=28>
                        <p><span>&nbsp;</span></p>
                    </td>
                    <td width=249>
                        <p style="border: 1px solid black; padding-left: 5px;">{{ $depar }}</p>
                    </td>
                </tr>
            </table>
            <table style='border-collapse:collapse; border:none; margin-top: -20px;'>
                <tr class='datos_empleado'>
                    <td width=246>
                        <p style="background: #A7C3A4; padding-left: 5px;"><span>Edad:</span></p>
                    </td>
                    <td width=28>
                        <p><span>&nbsp;</span></p>
                    </td>
                    <td width=249>
                        <p style="border: 1px solid black; padding-left: 5px;">{{ $edad }}</p>
                    </td>
                </tr>
            </table>
            <table style='border-collapse:collapse; border:none; margin-top: -20px;'>
                <tr class='datos_empleado'>
                    <td width=246>
                        <p style="background: #A7C3A4; padding-left: 5px;"><span>Sexo:</span></p>
                    </td>
                    <td width=28>
                        <p><span>&nbsp;</span></p>
                    </td>
                    <td width=249>
                        <p style="border: 1px solid black; padding-left: 5px;">{{ $sexo }}</p>
                    </td>
                </tr>
            </table>
            <table style='border-collapse:collapse; border:none; margin-top: -20px;'>
                <tr class='datos_empleado'>
                    <td width=246>
                        <p style="background: #A7C3A4; padding-left: 5px;"><span>Fecha de aplicación cuestionario:</span></p>
                    </td>
                    <td width=28>
                        <p><span>&nbsp;</span></p>
                    </td>
                    <td width=249>
                        <p style="border: 1px solid black; padding-left: 5px;">{{ $aplicacion_cuestionario }}</p>
                    </td>
                </tr>
            </table>
            <table style='border-collapse:collapse; border:none; margin-top: -20px;'>
                <tr class='datos_empleado'>
                    <td width=246>
                        <p style="background: #A7C3A4; padding-left: 5px; height:42px;"><span>Nombre de la empresa:</span></p>
                    </td>
                    <td width=28>
                        <p><span>&nbsp;</span></p>
                    </td>
                    <td width=249>
                        <p style="border: 1px solid black; padding-left: 5px; height:42px;">{{ $nombre_empresa }}</p>
                    </td>
                </tr>
            </table>

            <!-- EVALUADOR -->
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -20px;'>
            <tr>
            <td >
                <p class="general"><span>DATOS DEL EVALUADOR</span></p>
            </td>
            </tr>
            </table>
            <table style='border-collapse:collapse; border:none; margin-top: -20px;'>
                <tr class='datos_empleado'>
                    <td width=246>
                        <p style="background: #A7C3A4; padding-left: 5px;"><span>Nombre del evaluador:</span></p>
                    </td>
                    <td width=28>
                        <p><span>&nbsp;</span></p>
                    </td>
                    <td width=249>
                        <p style="border: 1px solid black; padding-left: 5px;">{{ $nombre_evaluador }}</p>
                    </td>
                </tr>
            </table>
            <table style='border-collapse:collapse; border:none; margin-top: -20px;'>
                <tr class='datos_empleado'>
                    <td width=246>
                        <p style="background: #A7C3A4; padding-left: 5px;"><span>Número de identificación (c.c):</span></p>
                    </td>
                    <td width=28>
                        <p><span>&nbsp;</span></p>
                    </td>
                    <td width=249>
                        <p style="border: 1px solid black; padding-left: 5px;">{{ $cc_evaluador }}</p>
                    </td>
                </tr>
            </table>
            <table style='border-collapse:collapse; border:none; margin-top: -20px;'>
                <tr class='datos_empleado'>
                    <td width=246>
                        <p style="background: #A7C3A4; padding-left: 5px;"><span>Profesión:</span></p>
                    </td>
                    <td width=28>
                        <p><span>&nbsp;</span></p>
                    </td>
                    <td width=249>
                        <p style="border: 1px solid black; padding-left: 5px;">{{ $profesion_evaluador }}</p>
                    </td>
                </tr>
            </table>
            <table style='border-collapse:collapse; border:none; margin-top: -20px;'>
                <tr class='datos_empleado'>
                    <td width=246>
                        <p style="background: #A7C3A4; padding-left: 5px;"><span>Postgrado:</span></p>
                    </td>
                    <td width=28>
                        <p><span>&nbsp;</span></p>
                    </td>
                    <td width=249>
                        <p style="border: 1px solid black; padding-left: 5px;">{{ $posgrado_evaluador }}</p>
                    </td>
                </tr>
            </table>
            <table style='border-collapse:collapse; border:none; margin-top: -20px;'>
                <tr class='datos_empleado'>
                    <td width=246>
                        <p style="background: #A7C3A4; padding-left: 5px;"><span>N° Tarjeta Profesional:</span></p>
                    </td>
                    <td width=28>
                        <p><span>&nbsp;</span></p>
                    </td>
                    <td width=249>
                        <p style="border: 1px solid black; padding-left: 5px;">{{ $tarjeta_profesional }}</p>
                    </td>
                </tr>
            </table>
            <table style='border-collapse:collapse; border:none; margin-top: -20px;'>
                <tr class='datos_empleado'>
                    <td width=246>
                        <p style="background: #A7C3A4; padding-left: 5px;"><span>N° Licencia en salud ocupacional:</span></p>
                    </td>
                    <td width=28>
                        <p><span>&nbsp;</span></p>
                    </td>
                    <td width=249>
                        <p style="border: 1px solid black; padding-left: 5px;">{{ $lice_ocupa_evaluador }}</p>
                    </td>
                </tr>
            </table>
            <table style='border-collapse:collapse; border:none; margin-top: -20px;'>
                <tr class='datos_empleado'>
                    <td width=246>
                        <p style="background: #A7C3A4; padding-left: 5px;"><span>Fecha de expedición de la licencia en salud ocupacional:</span></p>
                    </td>
                    <td width=28>
                        <p><span>&nbsp;</span></p>
                    </td>
                    <td width=249>
                        <p style="border: 1px solid black; padding-left: 5px; height:42px;">{{ $fe_lice_ocupa_evaluador }}</p>
                    </td>
                </tr>
            </table>

            <!-- INTRALABORAL – FORMA A -->
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none;'>
            <tr>
            <td >
                <p class="general"><span>INFORME DE RESULTADOS DEL CUESTIONARIO DE FACTORES DE RIESGO PSICOSOCIAL INTRALABORAL – FORMA A</span></p>
            </td>
            </tr>
            </table>

            <table style='width:100%; border-collapse:collapse; border:none;'>
                <tr>
                    <td width="17%" class="intralaboral"><p><b>Dominios</b></p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="27%" class="intralaboral"><p><b>Dimensiones</b></p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%" class="intralaboral"><b>Puntaje</b><span style="font-size: 12px !important;">(transformado)</span></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="24%" class="intralaboral"><p><b>Nivel de riesgo</b></p></td>
                </tr>
            </table>
            <!-- DOMINIO 1 INTRA -->
            <table style='width:100%; border-collapse:collapse; border:none; margin-top:20px;'>
                <tr>
                    <td width="17%" rowspan="4" class="dom1" style="text-align: center; border-bottom: 30px solid white;"><p>Liderazgo y relaciones sociales en el trabajo</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="27%"><p class="dom1" style="margin-top: -1px;">Características del liderazgo</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -2px;">{{ $dim_1_int }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="24.2%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px; font-family: sans-serif !important; color:black; margin-top: -2px;">{{ $riesgo_dim_1_int }}</p></td>
                </tr>
                <tr>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="27%"><p class="dom1" style="margin-top: -10px;">Relaciones sociales en el trabajo</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $dim_2_int }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="24%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $riesgo_dim_2_int }}</p></td>
                </tr>
                <tr>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="27%"><p class="dom1" style="margin-top: -10px;">Retroalimentación del desempeño</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $dim_3_int }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="23%"><p style="border: 1px solid black; padding-left: 5px;font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $riesgo_dim_3_int }}</p></td>
                </tr>
                <tr>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="27%"><p class="dom1" style="margin-top: -10px;">Relación con los colaboradores (subordinados)</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px; height:33px;">{{ $dim_4_int }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="24%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px; height:33px;">{{ $riesgo_dim_4_int }}</p></td>
                </tr>
                <tr>
                    <td colspan="3"><p class="dom1" style="margin-top: -20px;">LIDERAZGO Y RELACIONES SOCIALES EN EL TRABAJO</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -6px;">{{ $dom_1_int }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -6px;">{{ $riesgo_dom_1_int }}</p></td>
                </tr>
            </table>

            <!-- DOMINIO 2 INTRA -->
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -5px;'>
                <tr>
                    <td width="17%" rowspan="5" class="dom2" style="text-align: center; border-bottom: 30px solid white;"><p>Control sobre el trabajo</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="27%"><p class="dom2" style="margin-top: -1px;">Claridad de rol</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -2px;">{{ $dim_5_int }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="24.2%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px; font-family: sans-serif !important; color:black; margin-top: -2px;">{{ $riesgo_dim_5_int }}</p></td>
                </tr>
                <tr>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="27%"><p class="dom2" style="margin-top: -10px;">Capacitación</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $dim_6_int }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="24%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $riesgo_dim_6_int }}</p></td>
                </tr>
                <tr>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="27%"><p class="dom2" style="margin-top: -10px;">Participación y manejo del cambio</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $dim_7_int }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="23%"><p style="border: 1px solid black; padding-left: 5px;font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $riesgo_dim_7_int }}</p></td>
                </tr>
                <tr>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="27%"><p class="dom2" style="margin-top: -10px;">Oportunidades para el uso y desarrollo de habilidades y conocimientos</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px; height:50px;">{{ $dim_8_int }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="24%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px; height:50px;">{{ $riesgo_dim_8_int }}</p></td>
                </tr>
                <tr>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="27%"><p class="dom2" style="margin-top: -10px;">Control y autonomía sobre el trabajo</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px; height:33px;">{{ $dim_9_int }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="24%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px; height:33px;">{{ $riesgo_dim_9_int }}</p></td>
                </tr>
                <tr>
                    <td colspan="3"><p class="dom2" style="margin-top: -20px; text-align: center;">CONTROL SOBRE EL TRABAJO</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -6px;">{{ $dom_2_int }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -6px;">{{ $riesgo_dom_2_int }}</p></td>
                </tr>
            </table>

            <!-- DOMINIO 3 INTRA -->
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -5px;'>
                <tr>
                    <td width="17%" rowspan="8" class="dom3" style="text-align: center; border-bottom: 30px solid white;"><p>Demandas del trabajo</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="27%"><p class="dom3" style="margin-top: -1px;">Demandas ambientales y de esfuerzo físico</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -2px; height:33px;">{{ $dim_10_int }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="24.2%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px; font-family: sans-serif !important; color:black; margin-top: -2px; height:33px;">{{ $riesgo_dim_10_int }}</p></td>
                </tr>
                <tr>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="27%"><p class="dom3" style="margin-top: -10px;">Demandas emocionales</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $dim_11_int }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="24%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $riesgo_dim_11_int }}</p></td>
                </tr>
                <tr>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="27%"><p class="dom3" style="margin-top: -10px;">Demandas cuantitativas</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $dim_12_int }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="23%"><p style="border: 1px solid black; padding-left: 5px;font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $riesgo_dim_12_int }}</p></td>
                </tr>
                <tr>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="27%"><p class="dom3" style="margin-top: -10px;">Influencia del trabajo sobre el entorno extra laboral</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px; height:33px;">{{ $dim_13_int }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="24%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px; height:33px;">{{ $riesgo_dim_13_int }}</p></td>
                </tr>
                <tr>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="27%"><p class="dom3" style="margin-top: -10px;">Exigencias de responsabilidad del cargo</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px; height:33px;">{{ $dim_14_int }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="24%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px; height:33px;">{{ $riesgo_dim_14_int }}</p></td>
                </tr>
                <tr>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="27%"><p class="dom3" style="margin-top: -10px;">Demandas de carga mental</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $dim_15_int }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="23%"><p style="border: 1px solid black; padding-left: 5px;font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $riesgo_dim_15_int }}</p></td>
                </tr>
                <tr>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="27%"><p class="dom3" style="margin-top: -10px;">Consistencia del rol</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $dim_16_int }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="23%"><p style="border: 1px solid black; padding-left: 5px;font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $riesgo_dim_16_int }}</p></td>
                </tr>
                <tr>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="27%"><p class="dom3" style="margin-top: -10px;">Demandas de la jornada de trabajo</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px; height:33px;">{{ $dim_17_int }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="23%"><p style="border: 1px solid black; padding-left: 5px;font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px; height:33px;">{{ $riesgo_dim_17_int }}</p></td>
                </tr>
                <tr>
                    <td colspan="3"><p class="dom3" style="margin-top: -20px; text-align: center;">DEMANDAS DEL TRABAJO</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -6px;">{{ $dom_3_int }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -6px;">{{ $riesgo_dom_3_int }}</p></td>
                </tr>
            </table>

            <!-- DOMINIO 4 INTRA -->
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -5px;'>
                <tr>
                    <td width="17%" rowspan="2" class="dom4" style="text-align: center;"><p>Recompensas</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="27%"><p class="dom4" style="margin-top: -1px;">Recompensas derivadas de la pertenencia a la organización y del trabajo que se realiza</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -2px; height:50px;">{{ $dim_18_int }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>_
                    <td width="24.2%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px; font-family: sans-serif !important; color:black; margin-top: -2px; height:50px;">{{ $riesgo_dim_18_int }}</p></td>
                </tr>
                <tr>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="27%" class="dom4" style="margin-top: -10px;"><p>Reconocimiento y compensación</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%" style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black;"><p>{{ $dim_19_int }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="24%" style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black;"><p>{{ $riesgo_dim_19_int }}</p></td>
                </tr>
                <tr>
                    <td colspan="3"><p class="dom4" style="text-align:center;">RECOMPENSAS</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black;">{{ $dom_4_int }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black;">{{ $riesgo_dom_4_int }}</p></td>
                </tr>
                <tr>
                    <td colspan="3"><p class="dom4" style="text-align:center;">TOTAL GENERAL FACTORES DE RIESGO PSICOSOCIAL INTRALABORAL</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; height: 32.5px;">{{ $total_int }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; height: 32.5px;">{{ $riesgo_total_int }}</p></td>
                </tr>
            </table>

            <!-- Observaciones y Recomendaciones intra -->
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones">OBSERVACIONES Y COMENTARIOS DEL EVALUADOR</p></td>
                </tr>
                <tr>
                    <td><p style="height: 100px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px; margin-top: -15px;">{{ $observ_coment_evaluador_intra }}</p></td>
                </tr>
                <tr>
                    <td><p class="observaciones" style="margin-top: -7px;">RECOMENDACIONES PARTICULARES</p></td>
                </tr>
                <tr>
                    <td><p style="height: 100px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px; margin-top: -15px;">{{ $recomend_parti_evaluador_intra }}</p></td>
                </tr>
            </table>

            <!-- EXTRALABORAL-->
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
            <tr>
            <td >
                <p class="general"><span>INFORME DE RESULTADOS DEL CUESTIONARIO DE FACTORES DE RIESGO PSICOSOCIAL EXTRALABORAL</span></p>
            </td>
            </tr>
            </table>

            <table style='width:100%; border-collapse:collapse; border:none;'>
                <tr>
                    <td width="27%" class="intralaboral"><p><b>Dimensiones</b></p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%" class="intralaboral"><b>Puntaje</b><span style="font-size: 12px !important;">(transformado)</span></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="24%" class="intralaboral"><p><b>Nivel de riesgo</b></p></td>
                </tr>
            </table>

            <!-- DIMENSIONES EXTRA -->
            <table style='width:100%; border-collapse:collapse; border:none; margin-top:20px;'>
                <tr>
                    <td width="27%"><p class="dom_extra" style="margin-top: -1px;">Tiempo fuera del trabajo</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -2px;">{{ $dim_1_ext }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="24.2%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px; font-family: sans-serif !important; color:black; margin-top: -2px;">{{ $riesgo_dim_1_ext }}</p></td>
                </tr>
                <tr>
                    <td width="27%"><p class="dom_extra" style="margin-top: -10px;">Relaciones familiares</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $dim_2_ext }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="24%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $riesgo_dim_2_ext }}</p></td>
                </tr>
                <tr>
                    <td width="27%"><p class="dom_extra" style="margin-top: -10px;">Comunicación y relaciones interpersonales</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $dim_3_ext }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="23%"><p style="border: 1px solid black; padding-left: 5px;font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $riesgo_dim_3_ext }}</p></td>
                </tr>
                <tr>
                    <td width="27%"><p class="dom_extra" style="margin-top: -10px;">Situación económica del grupo familiar</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $dim_4_ext }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="24%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $riesgo_dim_4_ext }}</p></td>
                </tr>
                <tr>
                    <td width="27%"><p class="dom_extra" style="margin-top: -10px;">Características de la vivienda y de su entorno</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $dim_5_ext }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="24%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $riesgo_dim_5_ext }}</p></td>
                </tr>
                <tr>
                    <td width="27%"><p class="dom_extra" style="margin-top: -10px;">Influencia del entorno extra laboral sobre el trabajo</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px; height:33px;">{{ $dim_6_ext }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="24%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px; height:33px;">{{ $riesgo_dim_6_ext }}</p></td>
                </tr>
                <tr>
                    <td width="27%"><p class="dom_extra" style="margin-top: -10px;">Desplazamiento vivienda – trabajo – vivienda</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $dim_7_ext }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="24%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -10px;">{{ $riesgo_dim_7_ext }}</p></td>
                </tr>
                <tr>
                    <td width="27%"><p class="dom_extra" style="text-align:center; margin-top: -1px;">TOTAL GENERAL FACTORES DE RIESGO PSICOSOCIAL EXTRA LABORAL</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="10%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; height: 32.5px; margin-top: -1px;">{{ $total_ext }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="24%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; height: 32.5px; margin-top: -1px;">{{ $riesgo_total_ext }}</p></td>
                </tr>
            </table>

            <!-- Observaciones y Recomendaciones extra -->
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones">OBSERVACIONES Y COMENTARIOS DEL EVALUADOR</p></td>
                </tr>
                <tr>
                    <td><p style="height: 100px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px; margin-top: -15px;">{{ $observ_coment_evaluador_extra }}</p></td>
                </tr>
                <tr>
                    <td><p class="observaciones" style="margin-top: -7px;">RECOMENDACIONES PARTICULARES</p></td>
                </tr>
                <tr>
                    <td><p style="height: 100px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px; margin-top: -15px;">{{ $recomend_parti_evaluador_extra }}</p></td>
                </tr>
            </table>

            <!-- INTERPRETACIÓN INTRA Y EXTRA LABORAL -->
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones" style="margin-bottom: -0.1px;">INTERPRETACIÓN GENÉRICA DE LOS NIVELES DE RIESGO (INTRALABORAL Y/O EXTRALABORAL)</p></td>
                </tr>
                <tr>
                    <td style="height: 250px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px;">
                        <ul class="info_nivel_riesgo">
                            <li>
                                <b>Sin riesgo o riesgo despreciable:</b> Ausencia de riesgo o riesgo tan bajo que no amerita desarrollar actividades de intervención. 
                                Las dimensiones y/o dominios que se encuentren bajo esta categoría serán objeto de acciones o programas de promoción.
                            </li>
                            <li>
                                <b>Riesgo bajo:</b> No se espera que los factores psicosociales que obtengan puntuaciones de este nivel estén
                                relacionados con síntomas o respuestas de estrés significativas. Las dimensiones y/o dominios que se encuentren
                                bajo esta categoría serán objeto de acciones o programas de intervención, a fin de mantenerlos en los niveles de
                                riesgo más bajos posibles.
                            </li>
                            <li>
                                <b>Riesgo medio:</b> Nivel de riesgo en el que se esperaría una respuesta de estrés moderada. Las dimensiones y/o
                                dominios que se encuentren bajo esta categoría ameritan observación y acciones sistemáticas de intervención
                                para prevenir efectos perjudiciales en la salud.
                            </li>
                            <li>
                                <b>Riesgo alto:</b> Nivel de riesgo que tiene una importante posibilidad de asociación con respuestas de estrés alto y por
                                tanto, las dimensiones y/o dominios que se encuentren bajo esta categoría requieren intervención en el marco de un
                                sistema de vigilancia epidemiológica.
                            </li>
                            <li>
                                <b>Riesgo muy alto:</b> Nivel de riesgo con amplia posibilidad de asociarse a respuestas muy altas de estrés. Por
                                consiguiente las dimensiones y/o dominios que se encuentren bajo esta categoría requieren intervención inmediata
                                en el marco de un sistema de vigilancia epidemiológica.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>

            <!-- ESTRÉS -->
            <table style='width:100%; border-collapse:collapse; border:none;'>
            <tr>
            <td >
                <p class="general"><span>INFORME DE RESULTADOS DEL CUESTIONARIO PARA LA EVALUACIÓN DEL ESTRÉS</span></p>
            </td>
            </tr>
            </table>

            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -5px;'>
                <tr>
                    <td width="22%" rowspan="2" class="estres" style="border-bottom: 30px solid white;">TOTAL GENERAL SÍNTOMAS DE ESTRÉS</td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="12%" class="estres" style="border-bottom: 10px solid white"><p><b>Puntaje </b><span style="font-size: 12px !important;">(transformado)</span></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="17%" class="estres" style="border-bottom: 10px solid white"><p><b>Nivel de riesgo</b></td>
                </tr>
                <tr>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="12%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; margin-top: -5px;">{{ $total_estres }}</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td width="17%"><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black;  margin-top: -5px;">{{ $riesgo_total_estres }}</p></td>
                </tr>

            </table>

            <!-- Observaciones y Recomendaciones estres -->
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -27px;'>
                <tr>
                    <td><p class="observaciones">OBSERVACIONES Y COMENTARIOS DEL EVALUADOR</p></td>
                </tr>
                <tr>
                    <td><p style="height: 100px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px; margin-top: -15px;">{{ $observ_coment_evaluador_estres }}</p></td>
                </tr>
                <tr>
                    <td><p class="observaciones" style="margin-top: -7px;">RECOMENDACIONES PARTICULARES</p></td>
                </tr>
                <tr>
                    <td><p style="height: 100px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px; margin-top: -15px;">{{ $recomend_parti_evaluador_estres }}</p></td>
                </tr>
            </table>

            <!-- INTERPRETACIÓN ESTRÉS -->
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones" style="margin-bottom: -0.1px;">INTERPRETACIÓN GENÉRICA DE LOS NIVELES DE RIESGO (ESTRÉS)</p></td>
                </tr>
                <tr>
                    <td style="height: 250px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px;">
                        <ul class="info_nivel_riesgo">
                            <li>
                                <b>Muy bajo:</b> Ausencia de síntomas de estrés u ocurrencia muy rara que no amerita desarrollar actividades de 
                                intervención específicas, salvo acciones o programas de promoción en salud.
                            </li>
                            <li>
                                <b>Bajo:</b> Es indicativo de baja frecuencia de síntomas de estrés y por tanto escasa afectación del estado general 
                                de salud. Es pertinente desarrollar acciones o programas de intervención, a fin de mantener la baja frecuencia de síntomas.
                            </li>
                            <li>
                                <b>Medio:</b> La presentación de síntomas es indicativa de una respuesta de estrés moderada. Los síntomas más 
                                frecuentes y críticos ameritan observación y acciones sistemáticas de intervención para prevenir efectos perjudiciales en la salud. 
                                Además, se sugiere identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés alto. 
                                Los síntomas más críticos y frecuentes requieren intervención en el marco de un sistema de vigilancia epidemiológica. 
                                Además, es muy importante identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Muy alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés
                                severa y perjudicial para la salud. Los síntomas más críticos y frecuentes requieren intervención inmediata en el marco de un sistema de vigilancia epidemiológica. 
                                Así mismo, es imperativo identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>

            <!-- FECHA DE ELABORACIÓN Y FIRMA DEL EVALUADOR -->
            <table style='border-collapse:collapse; border:none; margin-top: 15px;'>
                <tr>
                    <td width="25%"><p class="elaboracion">Fecha de elaboración del informe:</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; width: 230%; ">{{ $fecha_elab_info }}</p></td>
                </tr>
                <tr>
                    <td width="25%"><p class="elaboracion" style="height: 100px; margin-top: -5px;"><br><br>Firma del evaluador:</p></td>
                    <td width="2%"><span>&nbsp;</span></td>
                    <td><p style="border: 1px solid black; padding-left: 5px; font-size: 13px;  font-family: sans-serif !important; color:black; width: 230%; height: 100px; margin-top: -5px;"></p></td>
                </tr>
            </table>
            <!-- INTERPRETACIÓN ESTRÉS -->
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones" style="margin-bottom: -0.1px;">INTERPRETACIÓN GENÉRICA DE LOS NIVELES DE RIESGO (ESTRÉS)</p></td>
                </tr>
                <tr>
                    <td style="height: 250px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px;">
                        <ul class="info_nivel_riesgo">
                            <li>
                                <b>Muy bajo:</b> Ausencia de síntomas de estrés u ocurrencia muy rara que no amerita desarrollar actividades de 
                                intervención específicas, salvo acciones o programas de promoción en salud.
                            </li>
                            <li>
                                <b>Bajo:</b> Es indicativo de baja frecuencia de síntomas de estrés y por tanto escasa afectación del estado general 
                                de salud. Es pertinente desarrollar acciones o programas de intervención, a fin de mantener la baja frecuencia de síntomas.
                            </li>
                            <li>
                                <b>Medio:</b> La presentación de síntomas es indicativa de una respuesta de estrés moderada. Los síntomas más 
                                frecuentes y críticos ameritan observación y acciones sistemáticas de intervención para prevenir efectos perjudiciales en la salud. 
                                Además, se sugiere identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés alto. 
                                Los síntomas más críticos y frecuentes requieren intervención en el marco de un sistema de vigilancia epidemiológica. 
                                Además, es muy importante identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Muy alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés
                                severa y perjudicial para la salud. Los síntomas más críticos y frecuentes requieren intervención inmediata en el marco de un sistema de vigilancia epidemiológica. 
                                Así mismo, es imperativo identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <!-- INTERPRETACIÓN ESTRÉS -->
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones" style="margin-bottom: -0.1px;">INTERPRETACIÓN GENÉRICA DE LOS NIVELES DE RIESGO (ESTRÉS)</p></td>
                </tr>
                <tr>
                    <td style="height: 250px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px;">
                        <ul class="info_nivel_riesgo">
                            <li>
                                <b>Muy bajo:</b> Ausencia de síntomas de estrés u ocurrencia muy rara que no amerita desarrollar actividades de 
                                intervención específicas, salvo acciones o programas de promoción en salud.
                            </li>
                            <li>
                                <b>Bajo:</b> Es indicativo de baja frecuencia de síntomas de estrés y por tanto escasa afectación del estado general 
                                de salud. Es pertinente desarrollar acciones o programas de intervención, a fin de mantener la baja frecuencia de síntomas.
                            </li>
                            <li>
                                <b>Medio:</b> La presentación de síntomas es indicativa de una respuesta de estrés moderada. Los síntomas más 
                                frecuentes y críticos ameritan observación y acciones sistemáticas de intervención para prevenir efectos perjudiciales en la salud. 
                                Además, se sugiere identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés alto. 
                                Los síntomas más críticos y frecuentes requieren intervención en el marco de un sistema de vigilancia epidemiológica. 
                                Además, es muy importante identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Muy alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés
                                severa y perjudicial para la salud. Los síntomas más críticos y frecuentes requieren intervención inmediata en el marco de un sistema de vigilancia epidemiológica. 
                                Así mismo, es imperativo identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones" style="margin-bottom: -0.1px;">INTERPRETACIÓN GENÉRICA DE LOS NIVELES DE RIESGO (ESTRÉS)</p></td>
                </tr>
                <tr>
                    <td style="height: 250px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px;">
                        <ul class="info_nivel_riesgo">
                            <li>
                                <b>Muy bajo:</b> Ausencia de síntomas de estrés u ocurrencia muy rara que no amerita desarrollar actividades de 
                                intervención específicas, salvo acciones o programas de promoción en salud.
                            </li>
                            <li>
                                <b>Bajo:</b> Es indicativo de baja frecuencia de síntomas de estrés y por tanto escasa afectación del estado general 
                                de salud. Es pertinente desarrollar acciones o programas de intervención, a fin de mantener la baja frecuencia de síntomas.
                            </li>
                            <li>
                                <b>Medio:</b> La presentación de síntomas es indicativa de una respuesta de estrés moderada. Los síntomas más 
                                frecuentes y críticos ameritan observación y acciones sistemáticas de intervención para prevenir efectos perjudiciales en la salud. 
                                Además, se sugiere identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés alto. 
                                Los síntomas más críticos y frecuentes requieren intervención en el marco de un sistema de vigilancia epidemiológica. 
                                Además, es muy importante identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Muy alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés
                                severa y perjudicial para la salud. Los síntomas más críticos y frecuentes requieren intervención inmediata en el marco de un sistema de vigilancia epidemiológica. 
                                Así mismo, es imperativo identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones" style="margin-bottom: -0.1px;">INTERPRETACIÓN GENÉRICA DE LOS NIVELES DE RIESGO (ESTRÉS)</p></td>
                </tr>
                <tr>
                    <td style="height: 250px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px;">
                        <ul class="info_nivel_riesgo">
                            <li>
                                <b>Muy bajo:</b> Ausencia de síntomas de estrés u ocurrencia muy rara que no amerita desarrollar actividades de 
                                intervención específicas, salvo acciones o programas de promoción en salud.
                            </li>
                            <li>
                                <b>Bajo:</b> Es indicativo de baja frecuencia de síntomas de estrés y por tanto escasa afectación del estado general 
                                de salud. Es pertinente desarrollar acciones o programas de intervención, a fin de mantener la baja frecuencia de síntomas.
                            </li>
                            <li>
                                <b>Medio:</b> La presentación de síntomas es indicativa de una respuesta de estrés moderada. Los síntomas más 
                                frecuentes y críticos ameritan observación y acciones sistemáticas de intervención para prevenir efectos perjudiciales en la salud. 
                                Además, se sugiere identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés alto. 
                                Los síntomas más críticos y frecuentes requieren intervención en el marco de un sistema de vigilancia epidemiológica. 
                                Además, es muy importante identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Muy alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés
                                severa y perjudicial para la salud. Los síntomas más críticos y frecuentes requieren intervención inmediata en el marco de un sistema de vigilancia epidemiológica. 
                                Así mismo, es imperativo identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones" style="margin-bottom: -0.1px;">INTERPRETACIÓN GENÉRICA DE LOS NIVELES DE RIESGO (ESTRÉS)</p></td>
                </tr>
                <tr>
                    <td style="height: 250px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px;">
                        <ul class="info_nivel_riesgo">
                            <li>
                                <b>Muy bajo:</b> Ausencia de síntomas de estrés u ocurrencia muy rara que no amerita desarrollar actividades de 
                                intervención específicas, salvo acciones o programas de promoción en salud.
                            </li>
                            <li>
                                <b>Bajo:</b> Es indicativo de baja frecuencia de síntomas de estrés y por tanto escasa afectación del estado general 
                                de salud. Es pertinente desarrollar acciones o programas de intervención, a fin de mantener la baja frecuencia de síntomas.
                            </li>
                            <li>
                                <b>Medio:</b> La presentación de síntomas es indicativa de una respuesta de estrés moderada. Los síntomas más 
                                frecuentes y críticos ameritan observación y acciones sistemáticas de intervención para prevenir efectos perjudiciales en la salud. 
                                Además, se sugiere identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés alto. 
                                Los síntomas más críticos y frecuentes requieren intervención en el marco de un sistema de vigilancia epidemiológica. 
                                Además, es muy importante identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Muy alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés
                                severa y perjudicial para la salud. Los síntomas más críticos y frecuentes requieren intervención inmediata en el marco de un sistema de vigilancia epidemiológica. 
                                Así mismo, es imperativo identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones" style="margin-bottom: -0.1px;">INTERPRETACIÓN GENÉRICA DE LOS NIVELES DE RIESGO (ESTRÉS)</p></td>
                </tr>
                <tr>
                    <td style="height: 250px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px;">
                        <ul class="info_nivel_riesgo">
                            <li>
                                <b>Muy bajo:</b> Ausencia de síntomas de estrés u ocurrencia muy rara que no amerita desarrollar actividades de 
                                intervención específicas, salvo acciones o programas de promoción en salud.
                            </li>
                            <li>
                                <b>Bajo:</b> Es indicativo de baja frecuencia de síntomas de estrés y por tanto escasa afectación del estado general 
                                de salud. Es pertinente desarrollar acciones o programas de intervención, a fin de mantener la baja frecuencia de síntomas.
                            </li>
                            <li>
                                <b>Medio:</b> La presentación de síntomas es indicativa de una respuesta de estrés moderada. Los síntomas más 
                                frecuentes y críticos ameritan observación y acciones sistemáticas de intervención para prevenir efectos perjudiciales en la salud. 
                                Además, se sugiere identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés alto. 
                                Los síntomas más críticos y frecuentes requieren intervención en el marco de un sistema de vigilancia epidemiológica. 
                                Además, es muy importante identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Muy alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés
                                severa y perjudicial para la salud. Los síntomas más críticos y frecuentes requieren intervención inmediata en el marco de un sistema de vigilancia epidemiológica. 
                                Así mismo, es imperativo identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones" style="margin-bottom: -0.1px;">INTERPRETACIÓN GENÉRICA DE LOS NIVELES DE RIESGO (ESTRÉS)</p></td>
                </tr>
                <tr>
                    <td style="height: 250px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px;">
                        <ul class="info_nivel_riesgo">
                            <li>
                                <b>Muy bajo:</b> Ausencia de síntomas de estrés u ocurrencia muy rara que no amerita desarrollar actividades de 
                                intervención específicas, salvo acciones o programas de promoción en salud.
                            </li>
                            <li>
                                <b>Bajo:</b> Es indicativo de baja frecuencia de síntomas de estrés y por tanto escasa afectación del estado general 
                                de salud. Es pertinente desarrollar acciones o programas de intervención, a fin de mantener la baja frecuencia de síntomas.
                            </li>
                            <li>
                                <b>Medio:</b> La presentación de síntomas es indicativa de una respuesta de estrés moderada. Los síntomas más 
                                frecuentes y críticos ameritan observación y acciones sistemáticas de intervención para prevenir efectos perjudiciales en la salud. 
                                Además, se sugiere identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés alto. 
                                Los síntomas más críticos y frecuentes requieren intervención en el marco de un sistema de vigilancia epidemiológica. 
                                Además, es muy importante identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Muy alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés
                                severa y perjudicial para la salud. Los síntomas más críticos y frecuentes requieren intervención inmediata en el marco de un sistema de vigilancia epidemiológica. 
                                Así mismo, es imperativo identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones" style="margin-bottom: -0.1px;">INTERPRETACIÓN GENÉRICA DE LOS NIVELES DE RIESGO (ESTRÉS)</p></td>
                </tr>
                <tr>
                    <td style="height: 250px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px;">
                        <ul class="info_nivel_riesgo">
                            <li>
                                <b>Muy bajo:</b> Ausencia de síntomas de estrés u ocurrencia muy rara que no amerita desarrollar actividades de 
                                intervención específicas, salvo acciones o programas de promoción en salud.
                            </li>
                            <li>
                                <b>Bajo:</b> Es indicativo de baja frecuencia de síntomas de estrés y por tanto escasa afectación del estado general 
                                de salud. Es pertinente desarrollar acciones o programas de intervención, a fin de mantener la baja frecuencia de síntomas.
                            </li>
                            <li>
                                <b>Medio:</b> La presentación de síntomas es indicativa de una respuesta de estrés moderada. Los síntomas más 
                                frecuentes y críticos ameritan observación y acciones sistemáticas de intervención para prevenir efectos perjudiciales en la salud. 
                                Además, se sugiere identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés alto. 
                                Los síntomas más críticos y frecuentes requieren intervención en el marco de un sistema de vigilancia epidemiológica. 
                                Además, es muy importante identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Muy alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés
                                severa y perjudicial para la salud. Los síntomas más críticos y frecuentes requieren intervención inmediata en el marco de un sistema de vigilancia epidemiológica. 
                                Así mismo, es imperativo identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones" style="margin-bottom: -0.1px;">INTERPRETACIÓN GENÉRICA DE LOS NIVELES DE RIESGO (ESTRÉS)</p></td>
                </tr>
                <tr>
                    <td style="height: 250px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px;">
                        <ul class="info_nivel_riesgo">
                            <li>
                                <b>Muy bajo:</b> Ausencia de síntomas de estrés u ocurrencia muy rara que no amerita desarrollar actividades de 
                                intervención específicas, salvo acciones o programas de promoción en salud.
                            </li>
                            <li>
                                <b>Bajo:</b> Es indicativo de baja frecuencia de síntomas de estrés y por tanto escasa afectación del estado general 
                                de salud. Es pertinente desarrollar acciones o programas de intervención, a fin de mantener la baja frecuencia de síntomas.
                            </li>
                            <li>
                                <b>Medio:</b> La presentación de síntomas es indicativa de una respuesta de estrés moderada. Los síntomas más 
                                frecuentes y críticos ameritan observación y acciones sistemáticas de intervención para prevenir efectos perjudiciales en la salud. 
                                Además, se sugiere identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés alto. 
                                Los síntomas más críticos y frecuentes requieren intervención en el marco de un sistema de vigilancia epidemiológica. 
                                Además, es muy importante identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Muy alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés
                                severa y perjudicial para la salud. Los síntomas más críticos y frecuentes requieren intervención inmediata en el marco de un sistema de vigilancia epidemiológica. 
                                Así mismo, es imperativo identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones" style="margin-bottom: -0.1px;">INTERPRETACIÓN GENÉRICA DE LOS NIVELES DE RIESGO (ESTRÉS)</p></td>
                </tr>
                <tr>
                    <td style="height: 250px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px;">
                        <ul class="info_nivel_riesgo">
                            <li>
                                <b>Muy bajo:</b> Ausencia de síntomas de estrés u ocurrencia muy rara que no amerita desarrollar actividades de 
                                intervención específicas, salvo acciones o programas de promoción en salud.
                            </li>
                            <li>
                                <b>Bajo:</b> Es indicativo de baja frecuencia de síntomas de estrés y por tanto escasa afectación del estado general 
                                de salud. Es pertinente desarrollar acciones o programas de intervención, a fin de mantener la baja frecuencia de síntomas.
                            </li>
                            <li>
                                <b>Medio:</b> La presentación de síntomas es indicativa de una respuesta de estrés moderada. Los síntomas más 
                                frecuentes y críticos ameritan observación y acciones sistemáticas de intervención para prevenir efectos perjudiciales en la salud. 
                                Además, se sugiere identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés alto. 
                                Los síntomas más críticos y frecuentes requieren intervención en el marco de un sistema de vigilancia epidemiológica. 
                                Además, es muy importante identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Muy alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés
                                severa y perjudicial para la salud. Los síntomas más críticos y frecuentes requieren intervención inmediata en el marco de un sistema de vigilancia epidemiológica. 
                                Así mismo, es imperativo identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones" style="margin-bottom: -0.1px;">INTERPRETACIÓN GENÉRICA DE LOS NIVELES DE RIESGO (ESTRÉS)</p></td>
                </tr>
                <tr>
                    <td style="height: 250px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px;">
                        <ul class="info_nivel_riesgo">
                            <li>
                                <b>Muy bajo:</b> Ausencia de síntomas de estrés u ocurrencia muy rara que no amerita desarrollar actividades de 
                                intervención específicas, salvo acciones o programas de promoción en salud.
                            </li>
                            <li>
                                <b>Bajo:</b> Es indicativo de baja frecuencia de síntomas de estrés y por tanto escasa afectación del estado general 
                                de salud. Es pertinente desarrollar acciones o programas de intervención, a fin de mantener la baja frecuencia de síntomas.
                            </li>
                            <li>
                                <b>Medio:</b> La presentación de síntomas es indicativa de una respuesta de estrés moderada. Los síntomas más 
                                frecuentes y críticos ameritan observación y acciones sistemáticas de intervención para prevenir efectos perjudiciales en la salud. 
                                Además, se sugiere identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés alto. 
                                Los síntomas más críticos y frecuentes requieren intervención en el marco de un sistema de vigilancia epidemiológica. 
                                Además, es muy importante identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Muy alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés
                                severa y perjudicial para la salud. Los síntomas más críticos y frecuentes requieren intervención inmediata en el marco de un sistema de vigilancia epidemiológica. 
                                Así mismo, es imperativo identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones" style="margin-bottom: -0.1px;">INTERPRETACIÓN GENÉRICA DE LOS NIVELES DE RIESGO (ESTRÉS)</p></td>
                </tr>
                <tr>
                    <td style="height: 250px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px;">
                        <ul class="info_nivel_riesgo">
                            <li>
                                <b>Muy bajo:</b> Ausencia de síntomas de estrés u ocurrencia muy rara que no amerita desarrollar actividades de 
                                intervención específicas, salvo acciones o programas de promoción en salud.
                            </li>
                            <li>
                                <b>Bajo:</b> Es indicativo de baja frecuencia de síntomas de estrés y por tanto escasa afectación del estado general 
                                de salud. Es pertinente desarrollar acciones o programas de intervención, a fin de mantener la baja frecuencia de síntomas.
                            </li>
                            <li>
                                <b>Medio:</b> La presentación de síntomas es indicativa de una respuesta de estrés moderada. Los síntomas más 
                                frecuentes y críticos ameritan observación y acciones sistemáticas de intervención para prevenir efectos perjudiciales en la salud. 
                                Además, se sugiere identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés alto. 
                                Los síntomas más críticos y frecuentes requieren intervención en el marco de un sistema de vigilancia epidemiológica. 
                                Además, es muy importante identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Muy alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés
                                severa y perjudicial para la salud. Los síntomas más críticos y frecuentes requieren intervención inmediata en el marco de un sistema de vigilancia epidemiológica. 
                                Así mismo, es imperativo identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones" style="margin-bottom: -0.1px;">INTERPRETACIÓN GENÉRICA DE LOS NIVELES DE RIESGO (ESTRÉS)</p></td>
                </tr>
                <tr>
                    <td style="height: 250px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px;">
                        <ul class="info_nivel_riesgo">
                            <li>
                                <b>Muy bajo:</b> Ausencia de síntomas de estrés u ocurrencia muy rara que no amerita desarrollar actividades de 
                                intervención específicas, salvo acciones o programas de promoción en salud.
                            </li>
                            <li>
                                <b>Bajo:</b> Es indicativo de baja frecuencia de síntomas de estrés y por tanto escasa afectación del estado general 
                                de salud. Es pertinente desarrollar acciones o programas de intervención, a fin de mantener la baja frecuencia de síntomas.
                            </li>
                            <li>
                                <b>Medio:</b> La presentación de síntomas es indicativa de una respuesta de estrés moderada. Los síntomas más 
                                frecuentes y críticos ameritan observación y acciones sistemáticas de intervención para prevenir efectos perjudiciales en la salud. 
                                Además, se sugiere identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés alto. 
                                Los síntomas más críticos y frecuentes requieren intervención en el marco de un sistema de vigilancia epidemiológica. 
                                Además, es muy importante identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Muy alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés
                                severa y perjudicial para la salud. Los síntomas más críticos y frecuentes requieren intervención inmediata en el marco de un sistema de vigilancia epidemiológica. 
                                Así mismo, es imperativo identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones" style="margin-bottom: -0.1px;">INTERPRETACIÓN GENÉRICA DE LOS NIVELES DE RIESGO (ESTRÉS)</p></td>
                </tr>
                <tr>
                    <td style="height: 250px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px;">
                        <ul class="info_nivel_riesgo">
                            <li>
                                <b>Muy bajo:</b> Ausencia de síntomas de estrés u ocurrencia muy rara que no amerita desarrollar actividades de 
                                intervención específicas, salvo acciones o programas de promoción en salud.
                            </li>
                            <li>
                                <b>Bajo:</b> Es indicativo de baja frecuencia de síntomas de estrés y por tanto escasa afectación del estado general 
                                de salud. Es pertinente desarrollar acciones o programas de intervención, a fin de mantener la baja frecuencia de síntomas.
                            </li>
                            <li>
                                <b>Medio:</b> La presentación de síntomas es indicativa de una respuesta de estrés moderada. Los síntomas más 
                                frecuentes y críticos ameritan observación y acciones sistemáticas de intervención para prevenir efectos perjudiciales en la salud. 
                                Además, se sugiere identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés alto. 
                                Los síntomas más críticos y frecuentes requieren intervención en el marco de un sistema de vigilancia epidemiológica. 
                                Además, es muy importante identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Muy alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés
                                severa y perjudicial para la salud. Los síntomas más críticos y frecuentes requieren intervención inmediata en el marco de un sistema de vigilancia epidemiológica. 
                                Así mismo, es imperativo identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones" style="margin-bottom: -0.1px;">INTERPRETACIÓN GENÉRICA DE LOS NIVELES DE RIESGO (ESTRÉS)</p></td>
                </tr>
                <tr>
                    <td style="height: 250px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px;">
                        <ul class="info_nivel_riesgo">
                            <li>
                                <b>Muy bajo:</b> Ausencia de síntomas de estrés u ocurrencia muy rara que no amerita desarrollar actividades de 
                                intervención específicas, salvo acciones o programas de promoción en salud.
                            </li>
                            <li>
                                <b>Bajo:</b> Es indicativo de baja frecuencia de síntomas de estrés y por tanto escasa afectación del estado general 
                                de salud. Es pertinente desarrollar acciones o programas de intervención, a fin de mantener la baja frecuencia de síntomas.
                            </li>
                            <li>
                                <b>Medio:</b> La presentación de síntomas es indicativa de una respuesta de estrés moderada. Los síntomas más 
                                frecuentes y críticos ameritan observación y acciones sistemáticas de intervención para prevenir efectos perjudiciales en la salud. 
                                Además, se sugiere identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés alto. 
                                Los síntomas más críticos y frecuentes requieren intervención en el marco de un sistema de vigilancia epidemiológica. 
                                Además, es muy importante identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Muy alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés
                                severa y perjudicial para la salud. Los síntomas más críticos y frecuentes requieren intervención inmediata en el marco de un sistema de vigilancia epidemiológica. 
                                Así mismo, es imperativo identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones" style="margin-bottom: -0.1px;">INTERPRETACIÓN GENÉRICA DE LOS NIVELES DE RIESGO (ESTRÉS)</p></td>
                </tr>
                <tr>
                    <td style="height: 250px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px;">
                        <ul class="info_nivel_riesgo">
                            <li>
                                <b>Muy bajo:</b> Ausencia de síntomas de estrés u ocurrencia muy rara que no amerita desarrollar actividades de 
                                intervención específicas, salvo acciones o programas de promoción en salud.
                            </li>
                            <li>
                                <b>Bajo:</b> Es indicativo de baja frecuencia de síntomas de estrés y por tanto escasa afectación del estado general 
                                de salud. Es pertinente desarrollar acciones o programas de intervención, a fin de mantener la baja frecuencia de síntomas.
                            </li>
                            <li>
                                <b>Medio:</b> La presentación de síntomas es indicativa de una respuesta de estrés moderada. Los síntomas más 
                                frecuentes y críticos ameritan observación y acciones sistemáticas de intervención para prevenir efectos perjudiciales en la salud. 
                                Además, se sugiere identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés alto. 
                                Los síntomas más críticos y frecuentes requieren intervención en el marco de un sistema de vigilancia epidemiológica. 
                                Además, es muy importante identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Muy alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés
                                severa y perjudicial para la salud. Los síntomas más críticos y frecuentes requieren intervención inmediata en el marco de un sistema de vigilancia epidemiológica. 
                                Así mismo, es imperativo identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones" style="margin-bottom: -0.1px;">INTERPRETACIÓN GENÉRICA DE LOS NIVELES DE RIESGO (ESTRÉS)</p></td>
                </tr>
                <tr>
                    <td style="height: 250px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px;">
                        <ul class="info_nivel_riesgo">
                            <li>
                                <b>Muy bajo:</b> Ausencia de síntomas de estrés u ocurrencia muy rara que no amerita desarrollar actividades de 
                                intervención específicas, salvo acciones o programas de promoción en salud.
                            </li>
                            <li>
                                <b>Bajo:</b> Es indicativo de baja frecuencia de síntomas de estrés y por tanto escasa afectación del estado general 
                                de salud. Es pertinente desarrollar acciones o programas de intervención, a fin de mantener la baja frecuencia de síntomas.
                            </li>
                            <li>
                                <b>Medio:</b> La presentación de síntomas es indicativa de una respuesta de estrés moderada. Los síntomas más 
                                frecuentes y críticos ameritan observación y acciones sistemáticas de intervención para prevenir efectos perjudiciales en la salud. 
                                Además, se sugiere identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés alto. 
                                Los síntomas más críticos y frecuentes requieren intervención en el marco de un sistema de vigilancia epidemiológica. 
                                Además, es muy importante identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Muy alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés
                                severa y perjudicial para la salud. Los síntomas más críticos y frecuentes requieren intervención inmediata en el marco de un sistema de vigilancia epidemiológica. 
                                Así mismo, es imperativo identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones" style="margin-bottom: -0.1px;">INTERPRETACIÓN GENÉRICA DE LOS NIVELES DE RIESGO (ESTRÉS)</p></td>
                </tr>
                <tr>
                    <td style="height: 250px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px;">
                        <ul class="info_nivel_riesgo">
                            <li>
                                <b>Muy bajo:</b> Ausencia de síntomas de estrés u ocurrencia muy rara que no amerita desarrollar actividades de 
                                intervención específicas, salvo acciones o programas de promoción en salud.
                            </li>
                            <li>
                                <b>Bajo:</b> Es indicativo de baja frecuencia de síntomas de estrés y por tanto escasa afectación del estado general 
                                de salud. Es pertinente desarrollar acciones o programas de intervención, a fin de mantener la baja frecuencia de síntomas.
                            </li>
                            <li>
                                <b>Medio:</b> La presentación de síntomas es indicativa de una respuesta de estrés moderada. Los síntomas más 
                                frecuentes y críticos ameritan observación y acciones sistemáticas de intervención para prevenir efectos perjudiciales en la salud. 
                                Además, se sugiere identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés alto. 
                                Los síntomas más críticos y frecuentes requieren intervención en el marco de un sistema de vigilancia epidemiológica. 
                                Además, es muy importante identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Muy alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés
                                severa y perjudicial para la salud. Los síntomas más críticos y frecuentes requieren intervención inmediata en el marco de un sistema de vigilancia epidemiológica. 
                                Así mismo, es imperativo identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones" style="margin-bottom: -0.1px;">INTERPRETACIÓN GENÉRICA DE LOS NIVELES DE RIESGO (ESTRÉS)</p></td>
                </tr>
                <tr>
                    <td style="height: 250px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px;">
                        <ul class="info_nivel_riesgo">
                            <li>
                                <b>Muy bajo:</b> Ausencia de síntomas de estrés u ocurrencia muy rara que no amerita desarrollar actividades de 
                                intervención específicas, salvo acciones o programas de promoción en salud.
                            </li>
                            <li>
                                <b>Bajo:</b> Es indicativo de baja frecuencia de síntomas de estrés y por tanto escasa afectación del estado general 
                                de salud. Es pertinente desarrollar acciones o programas de intervención, a fin de mantener la baja frecuencia de síntomas.
                            </li>
                            <li>
                                <b>Medio:</b> La presentación de síntomas es indicativa de una respuesta de estrés moderada. Los síntomas más 
                                frecuentes y críticos ameritan observación y acciones sistemáticas de intervención para prevenir efectos perjudiciales en la salud. 
                                Además, se sugiere identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés alto. 
                                Los síntomas más críticos y frecuentes requieren intervención en el marco de un sistema de vigilancia epidemiológica. 
                                Además, es muy importante identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Muy alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés
                                severa y perjudicial para la salud. Los síntomas más críticos y frecuentes requieren intervención inmediata en el marco de un sistema de vigilancia epidemiológica. 
                                Así mismo, es imperativo identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones" style="margin-bottom: -0.1px;">INTERPRETACIÓN GENÉRICA DE LOS NIVELES DE RIESGO (ESTRÉS)</p></td>
                </tr>
                <tr>
                    <td style="height: 250px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px;">
                        <ul class="info_nivel_riesgo">
                            <li>
                                <b>Muy bajo:</b> Ausencia de síntomas de estrés u ocurrencia muy rara que no amerita desarrollar actividades de 
                                intervención específicas, salvo acciones o programas de promoción en salud.
                            </li>
                            <li>
                                <b>Bajo:</b> Es indicativo de baja frecuencia de síntomas de estrés y por tanto escasa afectación del estado general 
                                de salud. Es pertinente desarrollar acciones o programas de intervención, a fin de mantener la baja frecuencia de síntomas.
                            </li>
                            <li>
                                <b>Medio:</b> La presentación de síntomas es indicativa de una respuesta de estrés moderada. Los síntomas más 
                                frecuentes y críticos ameritan observación y acciones sistemáticas de intervención para prevenir efectos perjudiciales en la salud. 
                                Además, se sugiere identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés alto. 
                                Los síntomas más críticos y frecuentes requieren intervención en el marco de un sistema de vigilancia epidemiológica. 
                                Además, es muy importante identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Muy alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés
                                severa y perjudicial para la salud. Los síntomas más críticos y frecuentes requieren intervención inmediata en el marco de un sistema de vigilancia epidemiológica. 
                                Así mismo, es imperativo identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones" style="margin-bottom: -0.1px;">INTERPRETACIÓN GENÉRICA DE LOS NIVELES DE RIESGO (ESTRÉS)</p></td>
                </tr>
                <tr>
                    <td style="height: 250px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px;">
                        <ul class="info_nivel_riesgo">
                            <li>
                                <b>Muy bajo:</b> Ausencia de síntomas de estrés u ocurrencia muy rara que no amerita desarrollar actividades de 
                                intervención específicas, salvo acciones o programas de promoción en salud.
                            </li>
                            <li>
                                <b>Bajo:</b> Es indicativo de baja frecuencia de síntomas de estrés y por tanto escasa afectación del estado general 
                                de salud. Es pertinente desarrollar acciones o programas de intervención, a fin de mantener la baja frecuencia de síntomas.
                            </li>
                            <li>
                                <b>Medio:</b> La presentación de síntomas es indicativa de una respuesta de estrés moderada. Los síntomas más 
                                frecuentes y críticos ameritan observación y acciones sistemáticas de intervención para prevenir efectos perjudiciales en la salud. 
                                Además, se sugiere identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés alto. 
                                Los síntomas más críticos y frecuentes requieren intervención en el marco de un sistema de vigilancia epidemiológica. 
                                Además, es muy importante identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Muy alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés
                                severa y perjudicial para la salud. Los síntomas más críticos y frecuentes requieren intervención inmediata en el marco de un sistema de vigilancia epidemiológica. 
                                Así mismo, es imperativo identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones" style="margin-bottom: -0.1px;">INTERPRETACIÓN GENÉRICA DE LOS NIVELES DE RIESGO (ESTRÉS)</p></td>
                </tr>
                <tr>
                    <td style="height: 250px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px;">
                        <ul class="info_nivel_riesgo">
                            <li>
                                <b>Muy bajo:</b> Ausencia de síntomas de estrés u ocurrencia muy rara que no amerita desarrollar actividades de 
                                intervención específicas, salvo acciones o programas de promoción en salud.
                            </li>
                            <li>
                                <b>Bajo:</b> Es indicativo de baja frecuencia de síntomas de estrés y por tanto escasa afectación del estado general 
                                de salud. Es pertinente desarrollar acciones o programas de intervención, a fin de mantener la baja frecuencia de síntomas.
                            </li>
                            <li>
                                <b>Medio:</b> La presentación de síntomas es indicativa de una respuesta de estrés moderada. Los síntomas más 
                                frecuentes y críticos ameritan observación y acciones sistemáticas de intervención para prevenir efectos perjudiciales en la salud. 
                                Además, se sugiere identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés alto. 
                                Los síntomas más críticos y frecuentes requieren intervención en el marco de un sistema de vigilancia epidemiológica. 
                                Además, es muy importante identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Muy alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés
                                severa y perjudicial para la salud. Los síntomas más críticos y frecuentes requieren intervención inmediata en el marco de un sistema de vigilancia epidemiológica. 
                                Así mismo, es imperativo identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <div style="page-break-before:always;"> </div>
            <table style='width:100%; border-collapse:collapse; border:none; margin-top: -15px;'>
                <tr>
                    <td><p class="observaciones" style="margin-bottom: -0.1px;">INTERPRETACIÓN GENÉRICA DE LOS NIVELES DE RIESGO (ESTRÉS)</p></td>
                </tr>
                <tr>
                    <td style="height: 250px !important; border: 1px solid black; font-family: sans-serif !important; font-size: 13px;">
                        <ul class="info_nivel_riesgo">
                            <li>
                                <b>Muy bajo:</b> Ausencia de síntomas de estrés u ocurrencia muy rara que no amerita desarrollar actividades de 
                                intervención específicas, salvo acciones o programas de promoción en salud.
                            </li>
                            <li>
                                <b>Bajo:</b> Es indicativo de baja frecuencia de síntomas de estrés y por tanto escasa afectación del estado general 
                                de salud. Es pertinente desarrollar acciones o programas de intervención, a fin de mantener la baja frecuencia de síntomas.
                            </li>
                            <li>
                                <b>Medio:</b> La presentación de síntomas es indicativa de una respuesta de estrés moderada. Los síntomas más 
                                frecuentes y críticos ameritan observación y acciones sistemáticas de intervención para prevenir efectos perjudiciales en la salud. 
                                Además, se sugiere identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés alto. 
                                Los síntomas más críticos y frecuentes requieren intervención en el marco de un sistema de vigilancia epidemiológica. 
                                Además, es muy importante identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                            <li>
                                <b>Muy alto:</b> La cantidad de síntomas y su frecuencia de presentación es indicativa de una respuesta de estrés
                                severa y perjudicial para la salud. Los síntomas más críticos y frecuentes requieren intervención inmediata en el marco de un sistema de vigilancia epidemiológica. 
                                Así mismo, es imperativo identificar los factores de riesgo psicosocial intra y extralaboral que pudieran tener alguna relación con los efectos identificados.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
        </main>
    </body>
</html>