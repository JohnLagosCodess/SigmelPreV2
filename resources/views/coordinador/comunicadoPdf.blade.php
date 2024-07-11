
<html>
    <head>
        <style>
            .centrar{text-align: center;}
            .justificado{
                text-align: justify;
                padding-left: 40px;
                white-space: pre-wrap;
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
            .card-body{padding: 1rem;}
            @page { margin: 180px 50px; }
            #header { position: fixed; left: 0px; top: -190px; right: 0px; height: 0px; background-color: white; text-align: center; }
            #header2 { position: fixed; left: 0px; top: -150px; right: 0px; height: 0px; background-color: white; text-align: center; }
            #footer { position: fixed; left: 40px; bottom: -180px; right: 0px; height: 175px; background-color: white; text-align: center; }
            #footer4 { position: fixed; left: 40px; bottom: -180px; right: 0px; height: 135px; background-color: white;}
            #footer3 { position: fixed; left: -20px; right: 0px; width: 0px; height: 0px; color:black; background-color: white; transform: rotate(0deg); top:300px;}
            #footer2 { position: fixed; left: 638px; bottom: -180px; right: 0px; height: 50px; background-color: white; }
            #footer2 .page:after { content: counter(page, upper-decimal); } 
            #footer4 { position: fixed;
                /* esta ligado con el tercer valor del margin */
                bottom: -4cm;
                left: 0cm;
                width: 100%;
                height: 15%;
                /* background-color: green; */
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
                align-items: center; 
            }
            #footer4 .page{
                text-align: center;
            }
            #footer4 .page:after { content: counter(page, upper-decimal); }    
            .footer_image{
                max-width: 100%;
                max-height: 80%;
                margin-bottom: -5px;
            }
            .footer_content {
                position: relative;
                text-align: center;
            }        
            .fuente_todo_texto{
                font-family: Arial;
                font-size: 15px;
            }
            .content2{
                margin-top: -0.5cm;
                margin-left: 0.5cm;
                margin-right: 0.5cm;
            }         
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
            <?php if($footer == null): ?>
                <p class="page" style="color: black;">Página </p>
            <?php else: ?>
                <?php 
                    $ruta_footer = "/footer_clientes/{$id_cliente}/{$footer}";
                    $footer_path = public_path($ruta_footer);
                    $footer_data = file_get_contents($footer_path);
                    $footer_base64 = base64_encode($footer_data);
                ?>
                <div class="footer_content">
                    <img src="data:image/png;base64,{{ $footer_base64 }}" class="footer_image">
                    <p class="page" style="color: black;">Página </p>
                </div>
            <?php endif ?>
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
                        <td class="justificado"><b>Siniestro: </b>{{$N_siniestro}}</td>
                    </tr>
                </table>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <p class="justificados">{{$Cuerpo_comunicado}}</p>
                    </div>
                </div>
            </div>                 
            <div class="row">
                <table>
                    <tr>                        
                        <td class="firma">                                                       
                            <?=$Firma_cliente?>
                        </td>
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
                        @if ($Anexos == '')
                            <td class="justificado"><b>Anexo: </b>0 folios</td>                         
                        @else
                            <td class="justificado"><b>Anexo: </b>{{$Anexos}} folios</td>                                                     
                        @endif
                    </tr>
                    @if (count($Agregar_copia) == 0)
                        <tr>
                            <td class="justificado"><b>Copia: </b>No se registran copias</td>                                                                                
                        </tr>
                    @else
                        <tr>
                            <td class="justificado"><b>Copia:</b></td>                            
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
                                    <td class="copias">
                                        <b>Afiliado: </b><?=$Agregar_copia['Afiliado'];?>
                                    </td>
                                </tr>
                            <?php       
                            }
                        ?>
                        <?php 
                            if (isset($Agregar_copia[$Empleador])) { ?>
                                <tr>
                                    <td class="copias">
                                        <b>Empleador: </b><?=$Agregar_copia['Empleador'];?>
                                    </td>
                                </tr>
                            <?php       
                            }
                        ?>
                        <?php 
                            if (isset($Agregar_copia[$EPS])) { ?>
                                <tr>
                                    <td class="copias">
                                        <b>EPS: </b><?=$Agregar_copia['EPS'];?>
                                    </td>
                                </tr>
                            <?php       
                            }
                        ?>
                        <?php 
                            if (isset($Agregar_copia[$AFP])) { ?>
                                <tr>
                                    <td class="copias">
                                        <b>AFP: </b><?=$Agregar_copia['AFP'];?>
                                    </td>
                                </tr>
                            <?php       
                            }
                        ?>
                        <?php 
                            if (isset($Agregar_copia[$ARL])) { ?>
                                <tr>
                                    <td class="copias">
                                        <b>ARL: </b><?=$Agregar_copia['ARL'];?>
                                    </td>
                                </tr>
                            <?php       
                            }
                        ?>
                        <?php 
                            if (isset($Agregar_copia[$JRCI])) { ?>
                                <tr>
                                    <td class="copias">
                                        <b>JRCI: </b><?=$Agregar_copia['JRCI'];?>
                                    </td>
                                </tr>
                            <?php       
                            }
                        ?>
                        <?php 
                        if (isset($Agregar_copia[$JNCI])) { ?>
                            <tr>
                                <td class="copias">
                                    <b>JNCI: </b><?=$Agregar_copia['JNCI'];?>
                                </td>
                            </tr>
                        <?php       
                        }
                        ?>
                    @endif
                </table>
            </div>
            <br>
            <div class="row">
                <table>
                    <tr>
                        <td class="justificado"><b>Elaboró: </b>{{$Elaboro}}</td>
                    </tr>
                    <tr>
                        <td class="justificado"><b>Revisó: </b>{{$Reviso}}</td>
                    </tr>
                    <tr>
                        <td class="justificado">Forma de envió: {{$Forma_envio}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>