
<html>
    <head>
        <style>
            .centrar{text-align: center;}
            .justificado{
                text-align: justify;
                padding-left: 40px;
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
            .card-body{padding: 1rem;}
            @page { margin: 180px 50px; }
            #header { position: fixed; left: 0px; top: -190px; right: 0px; height: 0px; background-color: white; text-align: center; }
            #header2 { position: fixed; left: 0px; top: -150px; right: 0px; height: 0px; background-color: white; text-align: center; }
            #footer { position: fixed; left: 40px; bottom: -180px; right: 0px; height: 175px; background-color: white; text-align: center; }
            #footer4 { position: fixed; left: 40px; bottom: -180px; right: 0px; height: 135px; background-color: white;}
            #footer3 { position: fixed; left: -20px; right: 0px; width: 0px; height: 0px; color:black; background-color: white; transform: rotate(0deg); top:300px;}
            #footer2 { position: fixed; left: 638px; bottom: -180px; right: 0px; height: 50px; background-color: white; }
            #footer2 .page:after { content: counter(page, upper-decimal); }            
        </style>
    </head>
    <body>
        <div id="header">
            <h4>Comunicado - {{$N_radicado}}</h4>
        </div>
        <div id="header2">
            <img src="https://www.fasecolda.com/cms/wp-content/uploads/2020/11/SegurosALFA.png" alt="alfa">
        </div>
        <div id="footer">
            <p>{{$Nombre_afiliado}} - {{$T_documento}} {{$N_identificacion}} - {{$ID_evento}}  </p>
        </div>
        <div id="footer4">
            <table>
                <tr>
                    <td><b style="color:#00917B">Seguros Alfa S.A y Seguros de Vida Alfa S.A</b></td>
                </tr>
                <tr>
                    <td><b style="color:#00917B; font-size: 14px;">Lineas de atención al cliente:</b></td>
                    <td><b style="color:#00917B; font-size: 14px;"> www.<b style="color:#00917be2;">segurosalfa</b>.com.co</b></td>
                </tr>
                <tr>
                    <td style="font-size: 13px">Bogotá: 3077032, a nivel nacional: 018000122532</td>
                </tr>
                <p style="font-size: 13px">Lunes a viernes, de 08:00a.m a 08:00p.m en jornada continua y sábados de 08:00a.m a 12m.</p>
            </table>
        </div>
        <div id="footer2">
            <p class="page">Pagina  </p>    
        </div>
        <div id="footer3">
            <img src="https://www.cfa.com.co/wp-content/uploads/2020/10/vigilado-superintendencia.svg" alt="super">
            {{-- <img src="https://depositoatermino.com.co/wp-content/uploads/vigilante.PNG" alt="listo"> --}}
        </div>
        <div id="content">
            <div class="row">
                <table>
                    <tr>
                        <td class="justificado">Señor(a):</td>
                    </tr>
                    <tr>
                        <td class="ajustetd">{{$Nombre_destinatario}}</td>
                        <td><b>Bogotá D.C</b></td> 
                    </tr>
                    <tr>
                        <td class="ajustetd">{{$Nit_cc}}</td>
                        <td><b>{{date('Y-m-d')}}</b></td>
                    </tr>
                    <tr>
                        <td class="ajustetd">{{$Email_destinatario}} </td>
                        <td><b>{{$N_radicado}}</b></td>
                    </tr>
                    <tr>
                        <td class="justificado">{{$Direccion_destinatario}}</td>
                    </tr>
                    <tr>                            
                        <td class="justificado">{{$Telefono_destinatario}}</td>
                    </tr>
                    <tr>
                        <td class="justificado">{{$Nombre_ciudad.' - '.$Nombre_departamento}}</td>
                    </tr>
                </table>
            </div>
            <br>
            <div class="row">
                <table>
                    <tr>
                        <td class="justificado"><b>Asunto: </b>{{$Asunto}}</td>
                    </tr>
                    <tr>
                        <td class="justificado">{{$Nombre_afiliado}}</td>
                    </tr>
                    <tr>
                        <td class="justificado">{{$T_documento}}: {{$N_identificacion}}</td>
                    </tr>
                    <tr>
                        <td class="justificado">Siniestro: {{$ID_evento}}</td>
                    </tr>
                </table>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <p class="justificado">{{$Cuerpo_comunicado}}</p>
                    </div>
                </div>
            </div>                 
            <div class="row">
                <table>
                    <tr>
                        <td><u class="justificado">{{$Nombre_usuario}}</u></td>
                    </tr>
                    <tr>
                        <td class="justificado">Firma Módulo clientes</td>
                    </tr>
                    <tr>
                        <td class="justificado">{{$Nombre_usuario}}</td>
                    </tr>
                    <tr>
                        <td class="justificado">Cargo: {{$Cargo}}</td>
                    </tr>
                </table>
            </div>
            <br>       
            <div class="row">
                <table>
                    <tr>
                        <td class="justificado">Anexo: {{$Anexos}} folios</td>
                    </tr>
                    <tr>
                        <td class="justificado">Copia: {{$Agregar_copia}}</td>
                    </tr>
                </table>
            </div>
            <br>
            <div class="row">
                <table>
                    <tr>
                        <td class="justificado">Elaboró: {{$Elaboro}}</td>
                    </tr>
                    <tr>
                        <td class="justificado">Revisó: {{$Reviso_lider}}</td>
                    </tr>
                    <tr>
                        <td class="justificado">Forma de envió: {{$Forma_envios}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>