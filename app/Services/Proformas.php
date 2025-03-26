<?php
namespace App\Services;

use App\Models\sigmel_informacion_comunicado_eventos;
use App\Models\sigmel_informacion_eventos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Coordinador\CalificacionPCLController;

class Proformas
{

    public static function make($tipo_proforma,...$param){
        $proformas = new self();

        $procesar_proforma = [
            "Documento_calificacion_tecnica" => function(...$param) use($proformas) {
                $proformas->calificacion_tecnica(...$param);
            },
        ];

        if(isset($procesar_proforma[$tipo_proforma])){
            $procesar_proforma[$tipo_proforma](...$param);
        }

        return $proformas;
    }

    public function calificacion_tecnica($request,$id_comunicado){
        $info_comunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
        ->select('*')->where('Id_Comunicado',$id_comunicado)->first();

        $copias = function ($tipo, $copias) {
            if (empty($copias)) return null;
            $result = false;
        
            foreach ($copias as $copia) {
                // Compara según el tipo y actualiza el resultado
                $result = match ($tipo) {
                    'edit_copia_afiliado' => ($copia == 'Afiliado'),
                    'edit_copia_empleador' => ($copia == 'Empleador'),
                    'edit_copia_eps' => ($copia == 'EPS'),
                    'edit_copia_afp' => ($copia == 'AFP'),  // Aquí parece que quieres hacer debug, ten cuidado
                    'edit_copia_arl' => ($copia == 'ARL'),
                    'edit_copia_jrci' => ($copia == 'Afiliado'),
                    'edit_copia_jnci' => ($copia == 'Afiliado'),
                    default => false,
                };
                
                // Si ya encontramos un valor verdadero, no es necesario seguir buscando
                if ($result) break;
            }
        
            return $result;
        };

        $data = [
            '_token' => $request->_token,
            'bandera_descarga' => 'BotonGuardarComunicado',
            'cliente_comunicado2_act' => $request->cliente_comunicado2,
            'nombre_afiliado_comunicado2_act' => $request->nombre_afiliado_comunicado2,
            'tipo_documento_comunicado2_act' => $request->tipo_documento_comunicado2,
            'identificacion_comunicado2_act' => $request->identificacion_comunicado2,
            'id_evento_comunicado2_act' => $request->Id_evento,
            'tipo_documento_descarga_califi_editar' => $request->tipo_descarga,
            'afiliado_comunicado_act' => $info_comunicado->Destinatario,
            'nombre_destinatario_act2' => $info_comunicado->Nombre_afiliado,
            'nic_cc_act2' => $info_comunicado->N_identificacion,
            'direccion_destinatario_act2' => $info_comunicado->Direccion_destinatario,
            'telefono_destinatario_act2' => $info_comunicado->Telefono_destinatario,
            'email_destinatario_act2' => $info_comunicado->Email_destinatario,
            'departamento_pdf' => $info_comunicado->Id_departamento,
            'ciudad_pdf' => $info_comunicado->Id_municipio,
            'asunto_act' => $info_comunicado->Asunto,
            'cuerpo_comunicado_act' => $info_comunicado->Cuerpo_comunicado,
            'anexos_act' => $info_comunicado->Anexos,
            'forma_envio_act' => $info_comunicado->Forma_envio,
            'elaboro2_act' => $info_comunicado->Elaboro,
            'reviso_act' => $info_comunicado->Reviso,
            'firmarcomunicado_editar' => $info_comunicado->Firmar_Comunicado,
            'ciudad_comunicado_act' => $request->ciudad,
            'Id_comunicado_act' => $id_comunicado,
            'Id_evento_act' => $request->Id_evento,
            'Id_asignacion_act' => $request->Id_asignacion,
            'Id_procesos_act' => $request->Id_procesos,
            'fecha_comunicado2_act' => $request->fecha_comunicado2,
            'agregar_copia_editar' => $request->agregar_copia,
            'radicado2_act' => $info_comunicado->N_radicado,
            'edit_copia_afiliado' => $copias('edit_copia_afiliado',$request->agregar_copia),
            'edit_copia_empleador' => $copias('edit_copia_empleador',$request->agregar_copia),
            'edit_copia_eps' => $copias('edit_copia_eps',$request->agregar_copia),
            'edit_copia_afp' =>$copias('edit_copia_afp',$request->agregar_copia),
            'edit_copia_arl' => $copias('edit_copia_arl',$request->agregar_copia),
            'edit_copia_jrci' => $copias('edit_copia_jrci',$request->agregar_copia),
            'edit_copia_jnci' => $copias('edit_copia_jnci',$request->agregar_copia),
            'radioafiliado_comunicado' => (bool) $request->radioafiliado_comunicado ?? null,
            'radioempresa_comunicado' => (bool) $request->radioempresa_comunicado ?? null,
            'radioOtro' => (bool) $request->radioOtro ?? null,
            'n_siniestro_proforma_editar' => $request->N_siniestro,
            'tipo_de_preforma_editar' => $request->tipo_descarga,
        ];

        $requestTMP = new Request();
        $requestTMP->setMethod('POST');
        $requestTMP->request->add($data);

        $calificacion_pcl = new CalificacionPCLController(app('\App\Services\GlobalService'));
        $calificacion_pcl->generarPdf($requestTMP);
    }

}

?>