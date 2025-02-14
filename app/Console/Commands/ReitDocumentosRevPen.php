<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Coordinador\BandejaNotifiController;
use App\Http\Controllers\Coordinador\CalificacionPCLController;
use App\Models\sigmel_auditorias_informacion_accion_eventos;
use App\Models\sigmel_clientes;
use App\Models\sigmel_historial_acciones_eventos;
use App\Models\sigmel_informacion_accion_eventos;
use App\Models\sigmel_informacion_acciones_automaticas_eventos;
use App\Models\sigmel_informacion_afiliado_eventos;
use App\Models\sigmel_informacion_alertas_ans_eventos;
use App\Models\sigmel_informacion_alertas_automaticas_eventos;
use App\Models\sigmel_informacion_ans_clientes;
use App\Models\sigmel_informacion_asignacion_eventos;
use App\Models\sigmel_informacion_comunicado_eventos;
use App\Models\sigmel_informacion_eventos;
use App\Models\sigmel_informacion_firmas_clientes;
use App\Models\sigmel_informacion_historial_accion_eventos;
use App\Models\sigmel_informacion_parametrizaciones_clientes;
use App\Models\sigmel_lista_departamentos_municipios;
use App\Models\sigmel_lista_parametros;
use App\Models\sigmel_numero_orden_eventos;
use App\Traits\GenerarRadicados;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Services\GlobalService;
use Illuminate\Support\Facades\Log;

class ReitDocumentosRevPen extends Command
{
    use GenerarRadicados;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reit-documentos-rev-pen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatización reiteración de documentos revisión pensión PBS070';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $time = time();
        $date = date("Y-m-d", $time);
        $date_time = date("Y-m-d 00:00:00");
        // se Captura la info de las acciones automaticas que se acabaron de ejecutar en el dia actual a  las 00:05:00 del job de Mysql
        $datos_info_acciones_automaticas = sigmel_informacion_acciones_automaticas_eventos::on('sigmel_gestiones')
        ->where([['F_movimiento_automatico', $date], ['Estado_accion_automatica', 'Ejecutada']])
        ->get();

        // Construyo el array de las acciones automaticas ejecutadas en el dia actual
        $array_datos_info_acciones_automaticas = $datos_info_acciones_automaticas->toArray();
        if(count($array_datos_info_acciones_automaticas) > 0){
            Log::info('REIT_DOCUMENTOS_REV_PEN: INICIA VALIDACIÓN DE REITERACIÓN DE DOCUMENTOS REVISIÓN PENSIÓN.');
            foreach ($array_datos_info_acciones_automaticas as $key => $item) {
                try{
                    //Se valida que la acción ejecutada automaticamente sea la 172 - REVISAR DOCUMENTOS DE REVISIÓN PENSIÓN, ya que es para la que piden la automatización PBS070
                    if($item['Accion_automatica'] === 172){
                        Log::info('REIT_DOCUMENTOS_REV_PEN: ID_ASIGNACION QUE CUMPLE CON LA ACCIÓN DE REVISAR DOCUMENTOS DE REVISIÓN PENSIÓN.',['Id_Asignacion',$item['Id_Asignacion']]);
                        //Se busca en los comunicados uno que cumpla con los requisitos solicitados en el PBS070 item 2
                        $comunicado_a_editar = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_comunicado_eventos as sice')
                            ->select('sice.*')
                            ->leftJoin('sigmel_gestiones.sigmel_informacion_correspondencia_eventos as sicee', 'sice.Id_Comunicado', '=', 'sicee.Id_comunicado')
                            ->where([
                                ['sice.ID_evento',$item['ID_evento']],
                                ['sice.Id_Asignacion',$item['Id_Asignacion']],
                                ['sice.Id_proceso',$item['Id_proceso']],
                                ['sice.Modulo_creacion','calificacionPCL'],
                                ['sice.Tipo_descarga','Documento_Revision_pension'],
                                ['sice.Estado_Notificacion',357],
                                ['sicee.Tipo_correspondencia','Afiliado'],
                            ])
                            ->wherenotNull('sicee.N_guia')
                            ->orderByDesc('F_registro')
                            ->orderByDesc('N_radicado')
                            ->first();
                        if($comunicado_a_editar){
                            Log::info('REIT_DOCUMENTOS_REV_PEN: SE ENCONTRO UN COMUNICADO QUE CUMPLE CON LAS CONDICIONES NECESARIAS.',['Id_Comunicado',$comunicado_a_editar->Id_Comunicado]);
                            //Si el comunicado existe se procede a editar en la parte documentos solicitados y agregar los siguientes PBS070 - Item 2
                            $antiguo_cuerpo_comunicado = $comunicado_a_editar->Cuerpo_comunicado;
                            $reemplazo ='<ol>
                                            <li>Dictamen de calificación de PCL que dio derecho a pensión por invalidez</li>
                                            <li>Exámenes complementarios</li>            
                                        </ol>';
                            $nuevo_cuerpo_comunicado = str_replace('<p>{{$documentos_solicitados}}</p>',$reemplazo,$antiguo_cuerpo_comunicado);
                            //Se guarda la actualización del cuerpo del comunicado
                            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
                            ->where([
                                ['ID_evento',$item['ID_evento']],
                                ['Id_Asignacion',$item['Id_Asignacion']],
                                ['Id_proceso',$item['Id_proceso']],
                                ['Modulo_creacion','calificacionPCL'],
                                ['Tipo_descarga','Documento_Revision_pension'],
                            ])
                            ->update([
                                'Cuerpo_comunicado' => $nuevo_cuerpo_comunicado,
                            ]);

                            //Ahora procedemos a crear otro comunicado PBS 070 - item 2
                            //Traemos los datos necesarios para la creación del comunicado 
                            //Información del afiliado
                            $informacion_afiliado = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_afiliado_eventos as siae')
                            ->select('siae.*','slp.Nombre_parametro as Tipo_documento_afiliado', 'slp2.Nombre_parametro as Tipo_documento_afiliado_benefi')
                            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'siae.Tipo_documento')
                            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'siae.Tipo_documento_benefi')
                            ->where('siae.ID_evento',$item['ID_evento'])
                            ->first();
                            //Obtener información del cliente 
                            $infoCliente = sigmel_clientes::on('sigmel_gestiones')
                                ->where('Id_cliente','=',$item['Id_cliente'])
                                ->value('Nombre_cliente');
                            //Traer el N_siniestro del evento
                            $N_siniestro = sigmel_informacion_eventos::on('sigmel_gestiones')
                                ->where('ID_evento','=',$item['ID_evento'])
                                ->value('N_siniestro');
                            //Consulta el numero de radicado en el cual va actualmente para la generación del comunicado
                            $N_radicado = $this->getRadicado('pcl',$item['ID_evento']);
                            //Capturamos el historial de acciones para tener las fechas en las que se ejecuto la primera acción y la fecha actual para saber el tiempo de movimiento en dias ya que la nota del comunicado lo pide PBS070
                            $Historial_acciones = sigmel_informacion_historial_accion_eventos::on('sigmel_gestiones')
                                ->where([['Movimiento_automatico',1],['ID_evento',$item['ID_evento']],['Id_Asignacion',$item['Id_Asignacion']],['Id_accion',185]])
                                ->first();
                            if($Historial_acciones){
                                $f_accion = new DateTime(date('Y-m-d', strtotime($Historial_acciones->F_accion)));
                                $f_primer_accion = new DateTime(date('Y-m-d', strtotime($Historial_acciones->F_primer_accion)));
                                // Calcular la diferencia entre las dos fechas
                                $diferencia = $f_accion->diff($f_primer_accion);
                                // Obtener la diferencia de las dos fechas en días
                                $tiempo_movimiento_dias = $diferencia->days;
                            }
                            //Creamos el objeto necesario para la creación de la proforma,el cual es el siguiente:
                            if($informacion_afiliado){
                                $fecha_primer_comunicado = fechaFormatoDinamico( $item['F_accion'],'d \d\e F');
                                $cuerpo_comunicado = 
                                    "<p>Reciba un cordial saludo por parte de Seguros de Vida Alfa S.A.</p>
                                    <p>En cumplimiento de la normatividad vigente, Seguros de Vida Alfa S.A., se encuentra realizando la actualización de información de las condiciones de 
                                    salud de los pensionados por invalidez, sustentada en el artículo 44 de la Ley 100 de 1993, que establece:</p>
                                    <p class='cuerpo_doc_revPen'>“(...)<strong>ARTÍCULO 44. REVISIÓN DE LAS PENSIONES DE INVALIDEZ.</strong> El estado de invalidez podrá <br>revisarse: 
                                    <br>a. Por solicitud de la entidad de previsión o seguridad social correspondiente cada tres (3) años, con el fin de ratificar, modificar o dejar sin 
                                    efectos el dictamen que sirvió de base para la liquidación de la pensión que disfruta su beneficiario y proceder a la extinción, disminución o aumento
                                    de la misma, si a ello hubiera lugar.<br>Este nuevo dictamen se sujeta a las reglas de los artículos anteriores. <br>El pensionado tendrá un plazo de 
                                    tres (3) meses contados a partir de la fecha de dicha solicitud, para someterse a la respectiva revisión del estado de invalidez. Salvo casos de fuerza
                                    mayor, si el pensionado no se presenta o impide dicha revisión dentro de dicho plazo, se suspenderá el pago de la pensión. Transcurridos doce (12) meses
                                    contados desde la misma fecha sin que el pensionado se presente o permita el examen, la respectiva pensión prescribirá. Para readquirir el derecho en 
                                    forma posterior, el afiliado que alegue permanecer inválido deberá someterse a un nuevo dictamen. Los gastos de este nuevo dictamen serán pagados por 
                                    el afiliado <br>(...)”</p><p>Dando alcance a la notificación de la primera carta de solicitud de documentos el pasado 
                                    $fecha_primer_comunicado y en cumplimiento con el compromiso adquirido, le recordamos que le queda un (1) mes a partir del recibido de 
                                    la presente comunicación para aportar la documentación solicitada, esto debido a que revisados nuestros sistemas de información, no se evidencian los 
                                    documentos solicitados, por lo tanto se reitera la solicitud ya que es necesario que allegue esta documentación al siguiente correo electrónico: 
                                    servicioalcliente@segurosalfa.com.co y evitar la suspensión de su mesada pensional. Los documentos solicitados son:</p>
                                    <ol>
                                        <li>Dictamen de calificación de PCL que dio derecho a pensión por invalidez</li>
                                        <li>Exámenes complementarios</li>            
                                    </ol>
                                    <p>Los documentos anteriormente mencionados deben 
                                    ser solicitados en su EPS con su médico tratante; por otra parte, aclaramos que los mismos se deben radicar en papelería física.</p><p>Cualquier inquietud 
                                    o consulta al respecto, le invitamos a comunicarse a nuestras líneas de atención al cliente en Bogotá (601) 3 07 70 32 o a la línea nacional gratuita 
                                    01 8000 122 532, de lunes a viernes, de 8:00 a. m. a 8:00 p.m. - sábados de 8:00 a.m. a 12 m., o escríbanos a «servicioalcliente@segurosalfa.com.co» 
                                    o a la dirección <b>Carrera 10 # 18-36, piso 4, Edificio José María Córdoba, Bogotá D.C.</b></p>";
                                    if($Historial_acciones && $tiempo_movimiento_dias){
                                        $nota = "Reiteración de documentos automática a los $tiempo_movimiento_dias días de la notificación de Solicitud de documentos.";
                                    }else{
                                        $nota = 'Reiteración de documentos automática a los 60 días de la notificación de Solicitud de documentos."';
                                    }
                                $datos_comunicado = [
                                    "ciudad" => "Bogotá D.C.",
                                    "Id_evento" => $item['ID_evento'],
                                    "Id_asignacion" => $item['Id_Asignacion'],
                                    "Id_procesos" => $item['Id_proceso'],
                                    "radicado2" => $N_radicado, 
                                    "cliente_comunicado2" => $infoCliente, 
                                    "nombre_afiliado_comunicado2" => $informacion_afiliado->Nombre_afiliado,
                                    "tipo_documento_comunicado2" => $informacion_afiliado->Tipo_documento_afiliado,
                                    "identificacion_comunicado2" => $informacion_afiliado->Nro_identificacion,
                                    "direccion_destinatario" => $informacion_afiliado->Direccion,
                                    "telefono_destinatario" => $informacion_afiliado->Telefono_contacto,
                                    "email_destinatario" => $informacion_afiliado->Email,
                                    "departamento_destinatario" => $informacion_afiliado->Id_departamento,
                                    "ciudad_destinatario" => $informacion_afiliado->Id_municipio,
                                    "cuerpo_comunicado" => $cuerpo_comunicado,
                                    "N_siniestro" => $N_siniestro,
                                    "Nota" => $nota
                                ];
                                // Crear una instancia simulada de Request.
                                $globalService = new GlobalService();
                                $ids_destinatarios = $globalService->asignacionConsecutivoIdDestinatario();
                                try {
                                    // Se intenta guardar el comunicado
                                    $this->guardarComunicado($datos_comunicado, $ids_destinatarios);
                                } catch (\Exception $e) {
                                    Log::error('REIT_DOCUMENTOS_REV_PEN: Error al guardar el comunicado.', [
                                        'Id_Asignacion' => $item['Id_Asignacion'],
                                        'Error' => $e->getMessage(),
                                    ]);
                                    // Continuar con la acción a pesar del error al guardar el comunicado
                                } finally {
                                    try {
                                        $this->ejecutarAccion($item['Id_cliente'], $item['Id_proceso'], $item['ID_evento'], $item['Id_Asignacion']);
                                        Log::info('REIT_DOCUMENTOS_REV_PEN: Acción ejecutada con éxito.', [
                                            'Id_Asignacion' => $item['Id_Asignacion']
                                        ]);
                                    } catch (\Exception $e) {
                                        Log::error('REIT_DOCUMENTOS_REV_PEN: Error al ejecutar la acción.', [
                                            'Id_Asignacion' => $item['Id_Asignacion'],
                                            'Error' => $e->getMessage(),
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
                catch (\Exception $e) {
                    Log::error('REIT_DOCUMENTOS_REV_PEN: HA OCURRIDO UN ERROR.', [
                        'Id_Asignacion' => $item['Id_Asignacion'],
                        'Error' => $e->getMessage(),
                    ]);
                    continue; // Continúa con el siguiente elemento del foreach
                }
            }
        }
    }
    function guardarComunicado($data, $ids_destinatarios){
        Log::info('REIT_DOCUMENTOS_REV_PEN: INSERTANDO COMUNICADO EN LA BASE DE DATOS.',['IDS_DESTINATARIOS',$ids_destinatarios]);

        $time = time();
        $date = date("Y-m-d", $time);
        //Se asignan los IDs de destinatario por cada posible destinatario
        $firmacliente = implode(["firmar comunicado"]);
        $total_agregarcopias = implode(", ", ["EPS"]);                

        $datos_info_registrarComunicadoPcl=[

            'ID_evento' => $data['Id_evento'],
            'Id_Asignacion' => $data['Id_asignacion'],
            'Id_proceso' => $data['Id_procesos'],
            'Ciudad' => $data['ciudad'],
            'F_comunicado' => $date,
            'N_radicado' => $data['radicado2'],
            'Cliente' => $data['cliente_comunicado2'],
            'Nombre_afiliado' => $data['nombre_afiliado_comunicado2'],
            'T_documento' => $data['tipo_documento_comunicado2'],
            'N_identificacion' => $data['identificacion_comunicado2'],
            'Destinatario' => 'Afiliado',
            'Nombre_destinatario' => $data['nombre_afiliado_comunicado2'], //ES EL MISMO AFILIADO
            'Nit_cc' => $data['identificacion_comunicado2'], // ES LA MISMA INFORMACIÓN DEL AFILIADO
            'Direccion_destinatario' => $data['direccion_destinatario'],
            'Telefono_destinatario' => $data['telefono_destinatario'],
            'Email_destinatario' => $data['email_destinatario'],
            'Id_departamento' => $data['departamento_destinatario'],
            'Id_municipio' => $data['ciudad_destinatario'],
            'Asunto' => "REITERACIÓN DE SOLICITUD DE DOCUMENTOS",
            'Cuerpo_comunicado' => $data['cuerpo_comunicado'],
            'Anexos' => "0",
            'Forma_envio' => "47",
            'Elaboro' => "SIGMEL (ACCIÓN AUTOMATICA)",
            'Reviso' => "1",
            'Agregar_copia' => $total_agregarcopias,
            'Firmar_Comunicado' => $firmacliente,
            'Tipo_descarga' => "Documento_Revision_pension",
            'Modulo_creacion' => 'calificacionPCL',
            'Reemplazado'=> 0,
            'N_siniestro' => $data['N_siniestro'],
            'Id_Destinatarios' => $ids_destinatarios,
            'Nombre_usuario' => 'SIGMEL (ACCIÓN AUTOMATICA)',
            'Nota' => $data['Nota'],
            'F_registro' => $date,
        ];
        
        $Id_comunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insertGetId($datos_info_registrarComunicadoPcl);
        $datos_info_historial_acciones = [
            'ID_evento' => $data['Id_evento'],
            'F_accion' => $date,
            'Nombre_usuario' => 'SIGMEL (ACCIÓN AUTOMATICA)',
            'Accion_realizada' => "Se genera comunicado a partir de automatización PBS070.",
            'Descripcion' => 'Se genera comunicado automático de Reiteración de documentos',
        ];

        sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);
        Log::info('REIT_DOCUMENTOS_REV_PEN: COMUNICADO INSERTADO EN LA BASE DE DATOS',['Id_comunicado',$Id_comunicado]);



        //DESCARGA DE COMUNICADO 
        Log::info('REIT_DOCUMENTOS_REV_PEN: GENERANDO COMUNICADO EN EL SERVIDOR.');
        $Forma_envio = 47;        
        $Nombre_destinatario = $data['nombre_afiliado_comunicado2'];
        $Direccion_destinatario = $data['direccion_destinatario'];
        $Telefono_destinatario = $data['telefono_destinatario'];
        $email_destinatario = $data['email_destinatario'];


        $departamentos_info_comunicado = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
        ->select('Nombre_departamento')
        ->where('Id_departamento',$data['departamento_destinatario'])
        ->get();
        $ciudad_info_comunicado = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
        ->select('Nombre_municipio')
        ->where('Id_municipios',$data['ciudad_destinatario'])
        ->get();
        $nombre_departamento = $departamentos_info_comunicado[0]->Nombre_departamento;
        $nombre_ciudad = $ciudad_info_comunicado[0]->Nombre_municipio;
        

        $ID_evento = $data['Id_evento'];
        $Id_Asignacion = $data['Id_asignacion'];
        $Cliente = $data['cliente_comunicado2'];
        $Nombre_afiliado = $data['nombre_afiliado_comunicado2'];
        $T_documento = $data['tipo_documento_comunicado2'];
        $N_identificacion = $data['identificacion_comunicado2'];
        $Cuerpo_comunicado = $data['cuerpo_comunicado'];
        $Anexos = "0";


        $validarFirma = isset($request->firmarcomunicado_editar) ? 'firmar comunicado' : 'No lleva firma';            

        
        if ($validarFirma == 'Firmar Documento' || $validarFirma == 'firmar comunicado') {            
            $idcliente = sigmel_clientes::on('sigmel_gestiones')->select('Id_cliente', 'Nombre_cliente')
            ->where('Nombre_cliente', $Cliente)->get();
    
            $firmaclientecompleta = sigmel_informacion_firmas_clientes::on('sigmel_gestiones')->select('Firma')
            ->where([['Id_cliente', $idcliente[0]->Id_cliente], ['Estado', '=', 'Activo']])->limit(1)->get();

            if(count($firmaclientecompleta) > 0){
                $Firma_cliente = $firmaclientecompleta[0]->Firma;
            }else{
                $Firma_cliente = 'No firma';
            }
            
        }else{
            $Firma_cliente = 'No firma';
        }

        /* Agregamos el indicativo */
        $indicativo = time();
        $dato_fecha_evento = sigmel_informacion_eventos::on('sigmel_gestiones')
            ->select('F_evento')
            ->where([['ID_evento', $ID_evento]])
            ->get();

        /* Creación de las variables faltantes que no están en el formulario */
        $array_datos_fecha_evento = json_decode(json_encode($dato_fecha_evento), true);
        $fecha_evento = $array_datos_fecha_evento[0]["F_evento"];


        $datos_eps = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_eps', '=', 'sie.Id_Entidad')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
        ->select('sie.Nombre_entidad as Nombre_eps', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos', 'sie.Emails as Email',
        'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
        ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
        ->get();

        $nombre_eps = $datos_eps[0]->Nombre_eps;
        $direccion_eps = $datos_eps[0]->Direccion;
        $email_eps = $datos_eps[0]->Email;
        if ($datos_eps[0]->Otros_Telefonos != "") {
            $telefonos_eps = $datos_eps[0]->Telefonos.",".$datos_eps[0]->Otros_Telefonos;
        } else {
            $telefonos_eps = $datos_eps[0]->Telefonos;
        }
        $ciudad_eps = $datos_eps[0]->Nombre_ciudad;
        $minucipio_eps = $datos_eps[0]->Nombre_municipio;

        $Agregar_copias['EPS'] = $nombre_eps."; ".$direccion_eps."; ".$email_eps."; ".$telefonos_eps."; ".$ciudad_eps."; ".$minucipio_eps;

        /* Extraer el id del cliente */
        $dato_id_cliente = sigmel_informacion_eventos::on('sigmel_gestiones')
        ->select('Cliente')
        ->where([['ID_evento', $ID_evento]])
        ->get();

        if (count($dato_id_cliente)>0) {
            $id_cliente = $dato_id_cliente[0]->Cliente;
        }
        
        /* datos del logo que va en el header */
        $dato_logo_header = sigmel_clientes::on('sigmel_gestiones')
        ->select('Logo_cliente')
        ->where([['Id_cliente', $id_cliente]])
        ->limit(1)->get();

        if (count($dato_logo_header) > 0) {
            $logo_header = $dato_logo_header[0]->Logo_cliente;
        } else {
            $logo_header = "Sin logo";
        }  

        //Footer_Image
        $footer_imagen = sigmel_clientes::on('sigmel_gestiones')
        ->select('Footer_cliente')
        ->where([['Id_cliente', $id_cliente]])
        ->limit(1)->get();

        if (count($footer_imagen) > 0 && $footer_imagen[0]->Footer_cliente != null) {
            $footer = $footer_imagen[0]->Footer_cliente;
        } else {
            $footer = null;
        } 

        $data = [
            'logo_header' => $logo_header,
            'id_cliente' => $id_cliente,
            'email_destinatario' => $email_destinatario,
            'ciudad' => $data['ciudad'],
            'fecha' => fechaFormateada($date),
            'Nombre_afiliado' => $Nombre_afiliado,
            'T_documento' => $T_documento,
            'N_identificacion'  => $N_identificacion,  
            'nombre' => $Nombre_destinatario,
            'direccion' => $Direccion_destinatario,
            'telefono' => $Telefono_destinatario,
            'municipio' => $nombre_ciudad,
            'departamento' => $nombre_departamento,
            'nro_radicado' => $data['radicado2'],
            'tipo_identificacion' => $T_documento,
            'num_identificacion' =>  $N_identificacion,
            'nro_siniestro' => $ID_evento,
            'asunto' => strtoupper("REITERACIÓN DE SOLICITUD DE DOCUMENTOS"),
            'cuerpo' => $Cuerpo_comunicado, 
            'fecha_evento' => $fecha_evento,
            'Firma_cliente' => $Firma_cliente,
            'nombre_usuario' => 'SIGMEL',
            'Anexos' => $Anexos,
            'Agregar_copia' => $Agregar_copias,
            'footer' => $footer,
            'Documentos_solicitados' => '',
            'N_siniestro' => $data['N_siniestro'],
        ];
        // Creación y guardado del pdf
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('/Proformas/Proformas_Prev/PCL/solicitud_documentos_revpen', $data);

        // $nombre_pdf = "PCL_OFICIO_REV_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";
        $nombre_pdf = "PCL_OFICIO_REV_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}_{$indicativo}.pdf";

        $output = $pdf->output();
        file_put_contents(public_path("Documentos_Eventos/{$ID_evento}/{$nombre_pdf}"), $output);
        $actualizar_nombre_documento = [
            'Nombre_documento' => $nombre_pdf
        ];

        sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $Id_comunicado)
        ->update($actualizar_nombre_documento);
        Log::info('REIT_DOCUMENTOS_REV_PEN: COMUNICADO GENERADO EN EL SERVIDOR CORRECTAMENTE.');
    }

    function ejecutarAccion($id_cliente, $id_proceso, $id_evento, $id_asignacion){
        Log::info('REIT_DOCUMENTOS_REV_PEN: EJECUTANDO ACCIÓN 136 NECESARIA PARA FINALIZAR CON LA TAREA');
        $time = time();
        $date = date("Y-m-d", $time);
        $datetime = date("Y-m-d h:i:s", $time);
        $date_time = date("Y-m-d H:i:s");

        $parametrica = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
        ->select('sipc.*','u.name as Profesional')
        ->leftJoin('sigmel_sys.users as u', 'sipc.Profesional_asignado', '=', 'u.id')
        ->where([['Servicio_asociado',8],['Accion_ejecutar',136],['Status_parametrico','Activo']])
        ->first();
        $array_datos_calificacionPcl = DB::select('CALL psrcalificacionpcl(?)', array($id_asignacion));
        //LOGICA DE GUARDAR CALIFICACION PCL TRAIDA DIRECTAMENTE DESDE EL CONTROLADOR DE CALIFICACION PCL
        if($parametrica && count($array_datos_calificacionPcl) > 0){
            $Id_servicio = 8;
            if ($array_datos_calificacionPcl[0]->Nueva_F_radicacion <> "") {
                $Nueva_fecha_radicacion = $array_datos_calificacionPcl[0]->Nueva_F_radicacion;
            } else {
                $Nueva_fecha_radicacion = null;
            }
            $fecha_radicacion_actual = $array_datos_calificacionPcl[0]->F_radicacion;
            $fecha_alerta = $date_time;
            [
                "newId_evento" => "000000000000058",
                "newId_asignacion" => "231",
                "Id_proceso" => "2",
                "Id_servicio" => "8",
                "fecha_devolucion" => "Sin Fecha Devolución",
                "fecha_radicacion_actual" => "2024-12-17",
                "fuente_informacion" => null,
                "nueva_fecha_radicacion" => null,
                "accion" => "136",
                "fecha_alerta" => "2024-12-26T08:40",
                "enviar" => "4",
                "estado_facturacion" => "A ESPERA DE DOCUMENTOS",
                "profesional" => "3",
                "causal_devolucion_comite" => null,
                "fecha_cierre" => null,
                "fecha_asignacion_calificacion" => "2024-12-17 08:27:28",
                "fecha_calificacion" => "Sin Calificación",
                "fecha_vencimiento_actual" => null,
                "descripcion_accion" => null,
                "tiempo_gestion" => "4",
                "banderaguardar" => "Actualizar",
            ];
            $Accion_realizar = 136;
            $datos_estado_acciones_automaticas = [
                'Estado_accion_automatica' => 'Ejecutada'
            ];

            sigmel_informacion_acciones_automaticas_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $id_asignacion)
            ->update($datos_estado_acciones_automaticas);

            $datos_estado_alertas_automaticas = [
                'Estado_alerta_automatica' => 'Finalizada'
            ];

            sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $id_asignacion)
            ->update($datos_estado_alertas_automaticas);
            
            $Id_Estado_evento = $parametrica->Estado;

            //Trae El numero de orden actual
            $n_orden = sigmel_numero_orden_eventos::on('sigmel_gestiones')
            ->select('Numero_orden')
            ->get();

            $n_ordenNotificacion = DB::table(getDatabaseName('sigmel_gestiones') . "sigmel_informacion_asignacion_eventos")
            ->select('N_de_orden')->where('Id_Asignacion', $id_asignacion)->get()->first();

            //Asignamos #n de orden cuado se envie un caso a notificaciones
            if(!empty($parametrica->Enviar_a_bandeja_trabajo_destino) && $parametrica->Enviar_a_bandeja_trabajo_destino != 'No'){
                BandejaNotifiController::finalizarNotificacion($id_evento,$id_asignacion,false);
                $N_orden_evento= $n_ordenNotificacion->N_de_orden ?? $n_orden[0]->Numero_orden;
            }else{
                BandejaNotifiController::finalizarNotificacion($id_evento,$id_asignacion,true);
                $N_orden_evento= $n_ordenNotificacion->N_de_orden ?? null;
            }

            $Detener_tiempo_gestion = "No";
            $F_detencion_tiempo_gestion = null;

            //Captura de datos para el id y nombre del profesional
            $id_profesional = $parametrica->Profesional_asignado;
            $asignacion_profesional = $parametrica->Profesional;

            // actualizacion de datos a la tabla de sigmel_informacion_accion_eventos
            
            $datos_info_actualizarCalifcacionPcl= [
                'ID_evento' => $id_evento,
                'Id_Asignacion' => $id_asignacion,
                'Id_proceso' => $id_proceso,
                'F_accion' => $date_time,
                'Accion' => 136,
                'F_Alerta' => $fecha_alerta,
                'Estado_Facturacion' => $parametrica->Estado_facturacion,
                'Descripcion_accion' => 'Se genera comunicado automático de Reiteración de documentos',
                'Nombre_usuario' => 'SIGMEL',
                'F_registro' => $date,
            ];

            $aud_datos_info_actualizarCalifcacionPcl= [
                'Aud_ID_evento' => $id_evento,
                'Aud_Id_Asignacion' => $id_asignacion,
                'Aud_Id_proceso' => $id_proceso,
                'Aud_F_accion' => $date_time,
                'Aud_Accion' => 136,
                'Aud_F_Alerta' => $fecha_alerta,
                'Aud_Estado_Facturacion' => $parametrica->Estado_facturacion,
                'Aud_Descripcion_accion' => 'Se genera comunicado automático de Reiteración de documentos',
                'Aud_Nombre_usuario' => 'SIGMEL',
                'Aud_F_registro' => $date,
            ];
            

            sigmel_informacion_accion_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $id_asignacion)->update($datos_info_actualizarCalifcacionPcl);

            // Realizamos la inserción a la tabla de auditoria sigmel_auditorias_informacion_accion_eventos
            sigmel_auditorias_informacion_accion_eventos::on('sigmel_auditorias')->insert($aud_datos_info_actualizarCalifcacionPcl);

            //Capturar el id accion para validar la accion que se acabo de guardar
            $info_accion_evento = sigmel_informacion_accion_eventos::on('sigmel_gestiones')
            ->select('Accion', 'F_accion')
            ->where([
                ['Id_Asignacion', $id_asignacion],
            ])
            ->get();
            // accion a realizar
            $AccionEvento = $info_accion_evento[0]->Accion;            
            // captura de movimiento automatico, tiempo de movimiento (dias) y accion automatica segun la accion a realizar 
            // segun al servicio asosciado
            $info_accion_automatica = sigmel_informacion_parametrizaciones_clientes::on('sigmel_gestiones')
            ->select('Movimiento_automatico','Tiempo_movimiento','Accion_automatica')
            ->where([
                ['Accion_ejecutar', $AccionEvento],
                ['Id_cliente', $id_cliente],
                ['Id_proceso', $id_proceso],
                ['Servicio_asociado', $Id_servicio],
                ['Status_parametrico', 'Activo']
            ])->get(); 
            $Movimiento_automatico = $info_accion_automatica[0]->Movimiento_automatico;
            $Tiempo_movimiento = $info_accion_automatica[0]->Tiempo_movimiento;
            $Accion_automatica = $info_accion_automatica[0]->Accion_automatica;  
            // case 1: si hay movimiento automatico, tiempo movimiento y accion automatica 
            // Case 2: Si hay movimiento automatico y tiempo movimiento pero no accion automatica
            // Case 3: Si hay movimiento automatico, accion automatica y no hay tiempo movimiento
            // Case 4: Si hay movimiento automatico y no hay tiempo movimiento y accion automatica
            switch (true) {
                case (!empty($Movimiento_automatico) and $Movimiento_automatico == 'Si' and !empty($Tiempo_movimiento) and !empty($Accion_automatica)):
                        $info_datos_accion_automatica = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_parametrizaciones_clientes as sipc')
                        ->leftJoin('sigmel_sys.users as u', 'u.id', '=', 'sipc.Profesional_asignado')
                        ->select('sipc.Accion_ejecutar', 'sipc.Estado', 'sipc.Profesional_asignado', 'u.name')
                        ->where([
                            ['sipc.Accion_ejecutar', $Accion_automatica],
                            ['sipc.Id_cliente', $id_cliente],
                            ['sipc.Id_proceso', $id_proceso],
                            ['sipc.Servicio_asociado', $Id_servicio],
                            ['sipc.Status_parametrico', 'Activo']
                        ])->get();
                            $Accion_ejecutar_automatica = $info_datos_accion_automatica[0]->Accion_ejecutar;
                            $Profesional_asignado_automatico = $info_datos_accion_automatica[0]->Profesional_asignado;
                            $NombreProfesional_asignado_automatico = $info_datos_accion_automatica[0]->name;
                            $Id_Estado_evento_automatico = $info_datos_accion_automatica[0]->Estado;

                            // Se suman los dias a la fecha actual para saber la fecha del movimiento automatico
                            $dateTime = new DateTime($date_time);
                            $dias = $Tiempo_movimiento; // Número de días que quieres sumar
                            $dateTime->modify("+$dias days");
                            $F_movimiento_automatico = $dateTime->format('Y-m-d');   

                            // Validar si existe el Id_Asignacion en la tabla sigmel_informacion_acciones_automaticas_eventos para insert o update
                            $info_datos_acciones_automaticas_eventos = sigmel_informacion_acciones_automaticas_eventos::on('sigmel_gestiones')
                            ->where([['Id_Asignacion', $id_asignacion]])->get();

                            if (count($info_datos_acciones_automaticas_eventos) > 0) {
                                
                                $array_info_datos_accion_automatica = [
                                    'Id_Asignacion' => $id_asignacion,
                                    'ID_evento' => $id_evento,
                                    'Id_proceso' => $id_proceso,
                                    'Id_servicio' => $Id_servicio,
                                    'Id_cliente' =>$id_cliente,
                                    'Accion_automatica' => $Accion_ejecutar_automatica,
                                    'Id_Estado_evento_automatico' => $Id_Estado_evento_automatico,                                    
                                    'F_accion' => $date_time,
                                    'Id_profesional_automatico' => $Profesional_asignado_automatico,
                                    'Nombre_profesional_automatico' => $NombreProfesional_asignado_automatico,
                                    'F_movimiento_automatico' => $F_movimiento_automatico,
                                    'Estado_accion_automatica' => 'Pendiente',
                                    'Nombre_usuario' => 'SIGMEL',
                                    'F_registro' => $date,
    
                                ];
    
                                sigmel_informacion_acciones_automaticas_eventos::on('sigmel_gestiones')
                                ->where([['Id_Asignacion', $id_asignacion]])
                                ->update($array_info_datos_accion_automatica);
                                
                                $mensaje_2 = 'la acción parametrizada tiene una Acción automatica y se ejecutará en '.$Tiempo_movimiento.' día(s)';

                            } else {
                                
                                $array_info_datos_accion_automatica = [
                                    'Id_Asignacion' => $id_asignacion,
                                    'ID_evento' => $id_evento,
                                    'Id_proceso' => $id_proceso,
                                    'Id_servicio' => $Id_servicio,
                                    'Id_cliente' =>$id_cliente,
                                    'Accion_automatica' => $Accion_ejecutar_automatica,
                                    'Id_Estado_evento_automatico' => $Id_Estado_evento_automatico,
                                    'F_accion' => $date_time,
                                    'Id_profesional_automatico' => $Profesional_asignado_automatico,
                                    'Nombre_profesional_automatico' => $NombreProfesional_asignado_automatico,
                                    'F_movimiento_automatico' => $F_movimiento_automatico,
                                    'Estado_accion_automatica' => 'Pendiente',
                                    'Nombre_usuario' => 'SIGMEL',
                                    'F_registro' => $date,
    
                                ];
    
                                sigmel_informacion_acciones_automaticas_eventos::on('sigmel_gestiones')->insert($array_info_datos_accion_automatica);
                                
                                $mensaje_2 = 'la acción parametrizada tiene una Acción automatica y se ejecutará en '.$Tiempo_movimiento.' día(s)';
                                                            
                            }                            
                        
                    break;
                case (!empty($Movimiento_automatico) and $Movimiento_automatico == 'Si' and !empty($Tiempo_movimiento) and empty($Accion_automatica)):
                        $mensaje_2 = 'la acción parametrizada tiene movimiento automatico, Tiempo de movimiento (Días) pero no cuenta con una Acción automatica';
                    break;
                case (!empty($Movimiento_automatico) and $Movimiento_automatico == 'Si' and empty($Tiempo_movimiento) and !empty($Accion_automatica)):
                        $mensaje_2 = 'la acción parametrizada tiene movimiento automatico, Acción automatica pero no cuenta con Tiempo de movimiento (Días)';
                    break;
                case (!empty($Movimiento_automatico) and $Movimiento_automatico == 'Si' and empty($Tiempo_movimiento) and empty($Accion_automatica)):
                        $mensaje_2 = 'la acción parametrizada tiene movimiento automatico, pero no cuenta con un Tiempo de movimiento (Días) y Acción automatica';
                    break;                    
                default:   
                        $mensaje_2 = 'la acción parametrizada NO tiene Movimiento Automático';
                    break;
            } 
            /*  Consultamos si el servicio y acción tiene un ANS configurado y en caso de ser así
                extraemos el valor de ans, el porcentaje de alerta naranja, el porcentaje de alerta roja, id de ans, su parte entera y su parte decimal.
                El ans debe estar activo (parametro 365)
            */

            // Consultamos si con el servicio y acción tiene un ANS configurado
            $array_tiene_ans = sigmel_informacion_ans_clientes::on('sigmel_gestiones')
            ->select('Valor', 'Porcentaje_Alerta_Naranja', 'Porcentaje_Alerta_Roja', 'Id_ans')
            ->where([
                ['Servicio', $Id_servicio],
                ['Accion', $Accion_realizar],
                ['Estado', '=', 366]
            ])
            ->get();

            /* En caso de que tenga un ANS se realiza el correspondiente análisis */
            if (count($array_tiene_ans) > 0) {

                $id_ans = $array_tiene_ans[0]->Id_ans;
                $valor_ans = $array_tiene_ans[0]->Valor;
                $parte_entera_ans = floor($valor_ans);
                $parte_decimal_ans = $valor_ans - $parte_entera_ans;

                // Caso 1: La acción a ejecutar tiene un ANS parametrizado y además se selecciona una Nueva Fecha de Radicación
                // Caso 2: La acción a ejecutar tiene un ANS parametrizado pero no se selecciona una Nueva Fecha de Radicación.

                switch (true) {
                    case (!empty($id_ans) && $Nueva_fecha_radicacion <> ""):

                        /*  Enviamos la nueva fecha de radicacion, la parte entera y parte decimal del ans a un 
                            procedimiento almacenado para realizar el calculo de la fecha de vencimiento
                        */
                        $array_fecha_vencimiento = DB::select('CALL psrFechaVencimientoEventos(?,?,?)', array($Nueva_fecha_radicacion, $parte_entera_ans, $parte_decimal_ans));

                        $fecha_vencimiento = $array_fecha_vencimiento[0]->fecha_vencimiento;

                        /*  Enviamos la nueva fecha de radicacion, el porcentaje de alerta naranja, 
                            el porcentaje de alerta roja, el valor del ans a un procedimiento almacenado
                            para calcular el tiempo de alertas naranja y roja y obtener así las fechas de
                            alertas naranja y roja
                        */

                        $porcentaje_alerta_naranja_ans = $array_tiene_ans[0]->Porcentaje_Alerta_Naranja;
                        $porcentaje_alerta_roja_ans = $array_tiene_ans[0]->Porcentaje_Alerta_Roja;

                        $array_datos_alertas_ans = DB::select('CALL psrFechasAlertaAnsEventos(?,?,?,?)', array($Nueva_fecha_radicacion, $valor_ans, $porcentaje_alerta_naranja_ans, $porcentaje_alerta_roja_ans));

                        $fecha_alerta_naranja_ans = $array_datos_alertas_ans[0]->fecha_alerta_naranja_ans;
                        $fecha_alerta_roja_ans = $array_datos_alertas_ans[0]->fecha_alerta_roja_ans;

                        // Actualizamos las fechas de alerta en la tabla sigmel_informacion_alertas_ans_eventos
                        $datos_actualizar_alertas_ans = [
                            'Id_ans' => $id_ans,
                            'Fecha_alerta_naranja' => $fecha_alerta_naranja_ans,
                            'Fecha_alerta_roja' => $fecha_alerta_roja_ans,
                            'Nombre_usuario' => 'SIGMEL',
                            'F_registro' => $date
                        ];

                        sigmel_informacion_alertas_ans_eventos::on('sigmel_gestiones')
                        ->where([
                            ['ID_evento', $id_evento],
                            ['Id_Asignacion', $id_asignacion]
                        ])->update($datos_actualizar_alertas_ans);

                    break; 
                    case (!empty($id_ans) && empty($Nueva_fecha_radicacion)):

                        /*  Enviamos la fecha de radicacion actual del formulario, la parte entera y parte decimal del ans a un 
                            procedimiento almacenado para realizar el calculo de la fecha de vencimiento
                        */
                        $array_fecha_vencimiento = DB::select('CALL psrFechaVencimientoEventos(?,?,?)', array($fecha_radicacion_actual, $parte_entera_ans, $parte_decimal_ans));

                        $fecha_vencimiento = $array_fecha_vencimiento[0]->fecha_vencimiento;

                        /*  Enviamos la fecha de radicacion actual, el porcentaje de alerta naranja, 
                            el porcentaje de alerta roja, el valor del ans a un procedimiento almacenado
                            para calcular el tiempo de alertas naranja y roja y obtener así las fechas de
                            alertas naranja y roja
                        */

                        $porcentaje_alerta_naranja_ans = $array_tiene_ans[0]->Porcentaje_Alerta_Naranja;
                        $porcentaje_alerta_roja_ans = $array_tiene_ans[0]->Porcentaje_Alerta_Roja;

                        $array_datos_alertas_ans = DB::select('CALL psrFechasAlertaAnsEventos(?,?,?,?)', array($fecha_radicacion_actual, $valor_ans, $porcentaje_alerta_naranja_ans, $porcentaje_alerta_roja_ans));

                        $fecha_alerta_naranja_ans = $array_datos_alertas_ans[0]->fecha_alerta_naranja_ans;
                        $fecha_alerta_roja_ans = $array_datos_alertas_ans[0]->fecha_alerta_roja_ans;

                        // Actualizamos las fechas de alerta en la tabla sigmel_informacion_alertas_ans_eventos
                        $datos_actualizar_alertas_ans = [
                            'Id_ans' => $id_ans,
                            'Fecha_alerta_naranja' => $fecha_alerta_naranja_ans,
                            'Fecha_alerta_roja' => $fecha_alerta_roja_ans,
                            'Nombre_usuario' => 'SIGMEL',
                            'F_registro' => $date
                        ];

                        sigmel_informacion_alertas_ans_eventos::on('sigmel_gestiones')
                        ->where([
                            ['ID_evento', $id_evento],
                            ['Id_Asignacion', $id_asignacion]
                        ])->update($datos_actualizar_alertas_ans);

                    break;
                    default:
                        # code...
                    break;
                }

            }else{
                // Caso 3: La acción a ejecutar no tiene un ANS parametrizado la fecha de vencimiento será la misma.
                $fecha_vencimiento = $array_datos_calificacionPcl[0]->F_vencimiento;
            }

            // Actualizar la tabla sigmel_informacion_asignacion_eventos
            $datos_info_actualizarAsignacionEvento= [      
                'Id_accion' => 136,
                'Id_Estado_evento' => $Id_Estado_evento, 
                'F_alerta' => $fecha_alerta, 
                'F_accion' => $date_time,
                'Id_profesional' => $id_profesional,
                'Nombre_profesional' => $asignacion_profesional,
                'Nueva_F_radicacion' => $Nueva_fecha_radicacion,
                'Tiempo_gestion' => $array_datos_calificacionPcl[0]->Tiempo_de_gestion,
                'Fecha_vencimiento' => $fecha_vencimiento,
                'N_de_orden' =>  $N_orden_evento,
                'Notificacion' => isset($parametrica->Enviar_a_bandeja_trabajo_destino) ? $parametrica->Enviar_a_bandeja_trabajo_destino : 'No',  
                'Nombre_usuario' => 'SIGMEL',
                'Detener_tiempo_gestion' => $Detener_tiempo_gestion,
                'F_detencion_tiempo_gestion' => $F_detencion_tiempo_gestion,
                // 'F_registro' => $date,
            ];

            sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $id_asignacion)->update($datos_info_actualizarAsignacionEvento);

            $F_accionEvento = $info_accion_evento[0]->F_accion;
            $info_datos_alertar_accion_ejecutar = sigmel_informacion_parametrizaciones_clientes::on('sigmel_gestiones')
            ->select('Tiempo_alerta', 'Porcentaje_alerta_naranja', 'Porcentaje_alerta_roja')
            ->where([
                ['Accion_ejecutar', $AccionEvento],
                ['Id_cliente', $id_cliente],
                ['Id_proceso', $id_proceso],
                ['Servicio_asociado', $Id_servicio],
                ['Status_parametrico', 'Activo']
            ])
            ->get();
            $Tiempo_alerta = $info_datos_alertar_accion_ejecutar[0]->Tiempo_alerta;
            $Porcentaje_alerta_naranja = $info_datos_alertar_accion_ejecutar[0]->Porcentaje_alerta_naranja;
            $Porcentaje_alerta_roja = $info_datos_alertar_accion_ejecutar[0]->Porcentaje_alerta_roja; 
            switch (true) {
                case (!empty($Tiempo_alerta) and empty($Porcentaje_alerta_naranja) and empty($Porcentaje_alerta_roja)):
                        $Nueva_F_Alerta = new DateTime($F_accionEvento);
                        $horas = $Tiempo_alerta;
                        $minutosAdicionales = ($horas - floor($horas)) * 60;
                        $horas = floor($horas);
                        $Nueva_F_Alerta->modify("+$horas hours");
                        $Nueva_F_Alerta->modify("+$minutosAdicionales minutes");
                        $Nueva_F_AlertaEvento = $Nueva_F_Alerta->format('Y-m-d H:i:s');
                        
                        $infoNueva_F_AlertaEvento_accion = [
                            'F_Alerta' => $Nueva_F_AlertaEvento
                        ];

                        $infoNueva_F_AlertaEvento_asignacion = [
                            'F_alerta' => $Nueva_F_AlertaEvento
                        ];
                        
                        sigmel_informacion_accion_eventos::on('sigmel_gestiones')
                        ->where([['Id_Asignacion', $id_asignacion]])
                        ->update($infoNueva_F_AlertaEvento_accion);

                        sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                        ->where([['Id_Asignacion', $id_asignacion]])
                        ->update($infoNueva_F_AlertaEvento_asignacion);                       
                    break;
                case (!empty($Tiempo_alerta) and !empty($Porcentaje_alerta_naranja)  and empty($Porcentaje_alerta_roja)):
                        $Nueva_F_Alerta = new DateTime($F_accionEvento);
                        $horas = $Tiempo_alerta;
                        $minutosAdicionales = ($horas - floor($horas)) * 60;
                        $horas = floor($horas);
                        $Nueva_F_Alerta->modify("+$horas hours");
                        $Nueva_F_Alerta->modify("+$minutosAdicionales minutes");
                        $Nueva_F_AlertaEvento = $Nueva_F_Alerta->format('Y-m-d H:i:s');
                        
                        $infoNueva_F_AlertaEvento_accion = [
                            'F_Alerta' => $Nueva_F_AlertaEvento
                        ];

                        $infoNueva_F_AlertaEvento_asignacion = [
                            'F_alerta' => $Nueva_F_AlertaEvento
                        ];
                        
                        sigmel_informacion_accion_eventos::on('sigmel_gestiones')
                        ->where([['Id_Asignacion', $id_asignacion]])
                        ->update($infoNueva_F_AlertaEvento_accion);

                        sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                        ->where([['Id_Asignacion', $id_asignacion]])
                        ->update($infoNueva_F_AlertaEvento_asignacion);

                        $Alerta_Naranja = ($Tiempo_alerta * $Porcentaje_alerta_naranja) / 100;

                        $Nueva_F_Alerta_Naranja = new DateTime($F_accionEvento);
                        $horas = $Alerta_Naranja;
                        $minutosAdicionales_naranja = ($horas - floor($horas)) * 60;
                        $horas = floor($horas);
                        $Nueva_F_Alerta_Naranja->modify("+$horas hours");
                        $minutosAdicionales_naranja_entero = round($minutosAdicionales_naranja);
                        $Nueva_F_Alerta_Naranja->modify("+$minutosAdicionales_naranja_entero minutes");
                        $Nueva_F_Alerta_NaranjaEvento = $Nueva_F_Alerta_Naranja->format('Y-m-d H:i:s');

                        // Validar si existe el Id_Asignacion en la tabla sigmel_informacion_alertas_automaticas_eventos para insert o update
                        $info_datos_alertar_automaticas_eventos = sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')
                        ->where([['Id_Asignacion', $id_asignacion]])->get();

                        if (count($info_datos_alertar_automaticas_eventos) > 0) {
                            $array_info_datos_alertas_automatica = [
                                'Id_Asignacion' => $id_asignacion,
                                'ID_evento' => $id_evento,
                                'Id_proceso' => $id_proceso,
                                'Id_servicio' => $Id_servicio,
                                'Id_cliente' =>$id_cliente,
                                'Accion_ejecutar' => $AccionEvento,
                                'F_accion' => $date_time,
                                'Tiempo_alerta' => $Tiempo_alerta,
                                'Porcentaje_alerta_naranja' => $Porcentaje_alerta_naranja,
                                'F_accion_alerta_naranja' => $Nueva_F_Alerta_NaranjaEvento,   
                                'Porcentaje_alerta_roja' => null,
                                'F_accion_alerta_roja' => null,                           
                                'Estado_alerta_automatica' => 'Ejecucion',
                                'Nombre_usuario' => 'SIGMEL',
                                'F_registro' => $date,
                            ];
    
                            sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')
                            ->where('Id_Asignacion', $id_asignacion)
                            ->update($array_info_datos_alertas_automatica);                            
                        } else {
                            $array_info_datos_alertas_automatica = [
                                'Id_Asignacion' => $id_asignacion,
                                'ID_evento' => $id_evento,
                                'Id_proceso' => $id_proceso,
                                'Id_servicio' => $Id_servicio,
                                'Id_cliente' =>$id_cliente,
                                'Accion_ejecutar' => $AccionEvento,
                                'F_accion' => $date_time,
                                'Tiempo_alerta' => $Tiempo_alerta,
                                'Porcentaje_alerta_naranja' => $Porcentaje_alerta_naranja,
                                'F_accion_alerta_naranja' => $Nueva_F_Alerta_NaranjaEvento, 
                                'Porcentaje_alerta_roja' => null,
                                'F_accion_alerta_roja' => null,                           
                                'Estado_alerta_automatica' => 'Ejecucion',
                                'Nombre_usuario' => 'SIGMEL',
                                'F_registro' => $date,
                            ];
    
                            sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')                            
                            ->insert($array_info_datos_alertas_automatica); 
                        }                        
                    break;
                case (!empty($Tiempo_alerta) and empty($Porcentaje_alerta_naranja) and !empty($Porcentaje_alerta_roja)):
                    $Nueva_F_Alerta = new DateTime($F_accionEvento);
                    $horas = $Tiempo_alerta;
                    $minutosAdicionales = ($horas - floor($horas)) * 60;
                    $horas = floor($horas);
                    $Nueva_F_Alerta->modify("+$horas hours");
                    $Nueva_F_Alerta->modify("+$minutosAdicionales minutes");
                    $Nueva_F_AlertaEvento = $Nueva_F_Alerta->format('Y-m-d H:i:s');
                    
                    $infoNueva_F_AlertaEvento_accion = [
                        'F_Alerta' => $Nueva_F_AlertaEvento
                    ];

                    $infoNueva_F_AlertaEvento_asignacion = [
                        'F_alerta' => $Nueva_F_AlertaEvento
                    ];
                    
                    sigmel_informacion_accion_eventos::on('sigmel_gestiones')
                    ->where([['Id_Asignacion', $id_asignacion]])
                    ->update($infoNueva_F_AlertaEvento_accion);

                    sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                    ->where([['Id_Asignacion', $id_asignacion]])
                    ->update($infoNueva_F_AlertaEvento_asignacion);                    

                    $Alerta_Roja = ($Tiempo_alerta * $Porcentaje_alerta_roja) / 100;

                    $Nueva_F_Alerta_Roja = new DateTime($F_accionEvento);
                    $horas_roja = $Alerta_Roja;
                    $minutosAdicionales_roja = ($horas_roja - floor($horas_roja)) * 60;
                    $horas_roja = floor($horas_roja);
                    $Nueva_F_Alerta_Roja->modify("+$horas_roja hours");
                    $minutosAdicionales_roja_entero = round($minutosAdicionales_roja);
                    $Nueva_F_Alerta_Roja->modify("+$minutosAdicionales_roja_entero minutes");
                    $Nueva_F_Alerta_RojaEvento = $Nueva_F_Alerta_Roja->format('Y-m-d H:i:s');

                    // Validar si existe el Id_Asignacion en la tabla sigmel_informacion_alertas_automaticas_eventos para insert o update
                    $info_datos_alertar_automaticas_eventos = sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')
                    ->where([['Id_Asignacion', $id_asignacion]])->get();                    
                    if (count($info_datos_alertar_automaticas_eventos) > 0) {
                        $array_info_datos_alertas_automatica = [
                            'Id_Asignacion' => $id_asignacion,
                            'ID_evento' => $id_evento,
                            'Id_proceso' => $id_proceso,
                            'Id_servicio' => $Id_servicio,
                            'Id_cliente' =>$id_cliente,
                            'Accion_ejecutar' => $AccionEvento,
                            'F_accion' => $date_time,
                            'Tiempo_alerta' => $Tiempo_alerta,
                            'Porcentaje_alerta_naranja' => null,
                            'F_accion_alerta_naranja' => null,
                            'Porcentaje_alerta_roja' => $Porcentaje_alerta_roja,
                            'F_accion_alerta_roja' => $Nueva_F_Alerta_RojaEvento,
                            'Estado_alerta_automatica' => 'Ejecucion',
                            'Nombre_usuario' => 'SIGMEL',
                            'F_registro' => $date,
                        ];
    
                        sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')
                        ->where('Id_Asignacion', $id_asignacion)
                        ->update($array_info_datos_alertas_automatica);
                        
                    } else {
                        $array_info_datos_alertas_automatica = [
                            'Id_Asignacion' => $id_asignacion,
                            'ID_evento' => $id_evento,
                            'Id_proceso' => $id_proceso,
                            'Id_servicio' => $Id_servicio,
                            'Id_cliente' =>$id_cliente,
                            'Accion_ejecutar' => $AccionEvento,
                            'F_accion' => $date_time,
                            'Tiempo_alerta' => $Tiempo_alerta,
                            'Porcentaje_alerta_naranja' => null,
                            'F_accion_alerta_naranja' => null,
                            'Porcentaje_alerta_roja' => $Porcentaje_alerta_roja,
                            'F_accion_alerta_roja' => $Nueva_F_Alerta_RojaEvento,
                            'Estado_alerta_automatica' => 'Ejecucion',
                            'Nombre_usuario' => 'SIGMEL',
                            'F_registro' => $date,
                        ];
    
                        sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')                        
                        ->insert($array_info_datos_alertas_automatica);
                    }
                    break;

                case (!empty($Tiempo_alerta) and !empty($Porcentaje_alerta_naranja) and !empty($Porcentaje_alerta_roja)):
                    $Nueva_F_Alerta = new DateTime($F_accionEvento);
                    $horas = $Tiempo_alerta;
                    $minutosAdicionales = ($horas - floor($horas)) * 60;
                    $horas = floor($horas);
                    $Nueva_F_Alerta->modify("+$horas hours");
                    $Nueva_F_Alerta->modify("+$minutosAdicionales minutes");
                    $Nueva_F_AlertaEvento = $Nueva_F_Alerta->format('Y-m-d H:i:s');
                    
                    $infoNueva_F_AlertaEvento_accion = [
                        'F_Alerta' => $Nueva_F_AlertaEvento
                    ];

                    $infoNueva_F_AlertaEvento_asignacion = [
                        'F_alerta' => $Nueva_F_AlertaEvento
                    ];
                    
                    sigmel_informacion_accion_eventos::on('sigmel_gestiones')
                    ->where([['Id_Asignacion', $id_asignacion]])
                    ->update($infoNueva_F_AlertaEvento_accion);

                    sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                    ->where([['Id_Asignacion', $id_asignacion]])
                    ->update($infoNueva_F_AlertaEvento_asignacion);

                    $Alerta_Naranja = ($Tiempo_alerta * $Porcentaje_alerta_naranja) / 100;
                    
                    $Nueva_F_Alerta_Naranja = new DateTime($F_accionEvento);
                    $horas_naranja = $Alerta_Naranja;
                    $minutosAdicionales_naranja = ($horas_naranja - floor($horas_naranja)) * 60;                    
                    $horas_naranja = floor($horas_naranja);                   
                    $Nueva_F_Alerta_Naranja->modify("+$horas_naranja hours");
                    $minutosAdicionales_naranja_entero = round($minutosAdicionales_naranja);
                    $Nueva_F_Alerta_Naranja->modify("+$minutosAdicionales_naranja_entero minutes");
                    $Nueva_F_Alerta_NaranjaEvento = $Nueva_F_Alerta_Naranja->format('Y-m-d H:i:s');
                    
                    $Alerta_Roja = ($Tiempo_alerta * $Porcentaje_alerta_roja) / 100;
                    
                    $Nueva_F_Alerta_Roja = new DateTime($F_accionEvento);
                    $horas_roja = $Alerta_Roja;
                    $minutosAdicionales_roja = ($horas_roja - floor($horas_roja)) * 60;                    
                    $horas_roja = floor($horas_roja);                    
                    $Nueva_F_Alerta_Roja->modify("+$horas_roja hours");
                    $minutosAdicionales_roja_entero = round($minutosAdicionales_roja);
                    $Nueva_F_Alerta_Roja->modify("+$minutosAdicionales_roja_entero minutes");
                    $Nueva_F_Alerta_RojaEvento = $Nueva_F_Alerta_Roja->format('Y-m-d H:i:s');
                    
                    // Validar si existe el Id_Asignacion en la tabla sigmel_informacion_alertas_automaticas_eventos para insert o update
                    $info_datos_alertar_automaticas_eventos = sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')
                    ->where([['Id_Asignacion', $id_asignacion]])->get();

                    if (count($info_datos_alertar_automaticas_eventos) > 0) {
                        $array_info_datos_alertas_automatica = [
                            'Id_Asignacion' => $id_asignacion,
                            'ID_evento' => $id_evento,
                            'Id_proceso' => $id_proceso,
                            'Id_servicio' => $Id_servicio,
                            'Id_cliente' =>$id_cliente,
                            'Accion_ejecutar' => $AccionEvento,
                            'F_accion' => $date_time,
                            'Tiempo_alerta' => $Tiempo_alerta,
                            'Porcentaje_alerta_naranja' => $Porcentaje_alerta_naranja,
                            'F_accion_alerta_naranja' => $Nueva_F_Alerta_NaranjaEvento,
                            'Porcentaje_alerta_roja' => $Porcentaje_alerta_roja,
                            'F_accion_alerta_roja' => $Nueva_F_Alerta_RojaEvento,
                            'Estado_alerta_automatica' => 'Ejecucion',
                            'Nombre_usuario' => 'SIGMEL',
                            'F_registro' => $date,
                        ];
    
                        sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')
                        ->where('Id_Asignacion', $id_asignacion)
                        ->update($array_info_datos_alertas_automatica);
                        
                    } else {
                        $array_info_datos_alertas_automatica = [
                            'Id_Asignacion' => $id_asignacion,
                            'ID_evento' => $id_evento,
                            'Id_proceso' => $id_proceso,
                            'Id_servicio' => $Id_servicio,
                            'Id_cliente' =>$id_cliente,
                            'Accion_ejecutar' => $AccionEvento,
                            'F_accion' => $date_time,
                            'Tiempo_alerta' => $Tiempo_alerta,
                            'Porcentaje_alerta_naranja' => $Porcentaje_alerta_naranja,
                            'F_accion_alerta_naranja' => $Nueva_F_Alerta_NaranjaEvento,
                            'Porcentaje_alerta_roja' => $Porcentaje_alerta_roja,
                            'F_accion_alerta_roja' => $Nueva_F_Alerta_RojaEvento,
                            'Estado_alerta_automatica' => 'Ejecucion',
                            'Nombre_usuario' => 'SIGMEL',
                            'F_registro' => $date,
                        ];
    
                        sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')                        
                        ->insert($array_info_datos_alertas_automatica);
                    }
                    
                    break;
                default:
                    
                    break;
            }
            $datos_info_historial_acciones = [
                'ID_evento' => $id_evento,
                'F_accion' => $date,
                'Nombre_usuario' => 'SIGMEL',
                'Accion_realizada' => "Actualizado Modulo Calificacion Pcl.",
                'Descripcion' => 'Se genera comunicado automático de Reiteración de documentos',
            ];

            sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);

            // Insertar informacion en la tabla sigmel_informacion_historial_accion_eventos
            $datos_historial_accion_eventos = [
                'Id_Asignacion' => $id_asignacion,
                'ID_evento' => $id_evento,
                'Id_proceso' => $id_proceso,
                'Id_servicio' => $Id_servicio,
                'Id_accion' => $Accion_realizar,
                'Documento' => 'N/A',
                'Descripcion' => 'Se genera comunicado automático de Reiteración de documentos',
                'F_accion' => $date_time,
                'Nombre_usuario' => 'SIGMEL',
            ];

            sigmel_informacion_historial_accion_eventos::on('sigmel_gestiones')->insert($datos_historial_accion_eventos);
            Log::info('REIT_DOCUMENTOS_REV_PEN: ACCION 136 EJECUTADA CORRECTAMENTE');
        }
    }
}