<?php
namespace App\Services;

use App\Models\sigmel_consecutivos_destinatarios;
use App\Models\sigmel_informacion_asignacion_eventos;
use App\Models\sigmel_informacion_comite_interdisciplinario_eventos;
use App\Models\sigmel_informacion_comunicado_eventos;
use Illuminate\Support\Facades\DB;

class GlobalService
{
    public function retornarInformaciónLaboral($Id_evento){
        // Traer Información laboral
        return DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
        ->leftJoin('sigmel_gestiones.sigmel_lista_arls as sla', 'sla.Id_arl', '=', 'sile.Id_arl')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_departamento', '=', 'sile.Id_departamento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldms', 'sldms.Id_municipios', '=', 'sile.Id_municipio')
        ->leftJoin('sigmel_gestiones.sigmel_lista_actividad_economicas as slae', 'slae.Id_ActEco', '=', 'sile.Id_actividad_economica')
        ->leftJoin('sigmel_gestiones.sigmel_lista_clase_riesgos as slcr', 'slcr.Id_Riesgo', '=', 'sile.Id_clase_riesgo')
        ->leftJoin('sigmel_gestiones.sigmel_lista_ciuo_codigos as slcc', 'slcc.Id_Codigo', '=', 'sile.Id_codigo_ciuo')
        ->select('sile.ID_evento', 'sile.Tipo_empleado','sile.Id_arl', 'sla.Nombre_arl', 'sile.Empresa', 'sile.Nit_o_cc', 'sile.Telefono_empresa',
        'sile.Email', 'sile.Direccion', 'sile.Id_departamento', 'sldm.Nombre_departamento', 'sile.Id_municipio', 
        'sldms.Nombre_municipio', 'sile.Id_actividad_economica', 'slae.Nombre_actividad', 'sile.Id_clase_riesgo', 
        'slcr.Nombre_riesgo', 'sile.Persona_contacto', 'sile.Telefono_persona_contacto', 'sile.Id_codigo_ciuo', 'slcc.Nombre_ciuo', 
        'sile.F_ingreso', 'sile.Cargo', 'sile.Funciones_cargo', 'sile.Antiguedad_empresa', 'sile.Antiguedad_cargo_empresa', 
        'sile.F_retiro', 'sile.Descripcion')
        ->where([['sile.ID_evento','=', $Id_evento]])
        ->orderBy('sile.F_registro', 'desc')
        ->limit(1)
        ->get();
    }

    public function retornarInformaciónEntidad($Id_entidad){
        //Retornar información de una entidad
        return DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_entidades as sie')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Ciudad', '=', 'sldm1.Id_municipios')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Departamento', '=', 'sldm2.Id_departamento')
        ->select('sie.Nombre_entidad', 'sie.Direccion', 'sie.Telefonos', 'sldm1.Nombre_municipio as Nombre_ciudad', 'sldm2.Nombre_departamento','sie.Emails as Email')
        ->where([['Id_Entidad', $Id_entidad]])
        ->get();
    }

    public function retornarComiteInterdisciplinario($Id_evento, $Id_asignacion){
        // Comite interdisciplinario
        return  sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento',$Id_evento],
            ['Id_Asignacion',$Id_asignacion]
        ])
        ->get();
    }
    public function retornarInformacionPronunciamiento($id_evento, $id_asignacion){
        return DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_pronunciamiento_eventos as pr')
        ->select('pr.ID_evento','pr.Id_Asignacion', 'Id_proceso', 'pr.Id_primer_calificador','c.Tipo_Entidad','pr.Id_nombre_calificador','e.Nombre_entidad'
        ,'pr.Nit_calificador','pr.Dir_calificador','pr.Email_calificador','pr.Telefono_calificador','pr.Depar_calificador','pr.Ciudad_calificador'
        ,'pr.Id_tipo_pronunciamiento','p.Nombre_parametro as Tpronuncia','pr.Id_tipo_evento','ti.Nombre_evento','pr.Id_tipo_origen','or.Nombre_parametro as T_origen'
        ,'pr.Fecha_evento','pr.Dictamen_calificador','pr.Fecha_calificador','pr.N_siniestro','pr.Fecha_estruturacion','pr.Porcentaje_pcl','pr.Rango_pcl'
        ,'pr.Decision','pr.Fecha_pronuncia','pr.Asunto_cali','pr.Sustenta_cali','pr.Destinatario_principal','pr.Tipo_entidad','pr.Nombre_entidad as Nombre_entidad_correspon',
        'pr.Copia_afiliado','pr.copia_empleador','pr.Copia_eps','pr.Copia_afp','pr.Copia_arl','pr.Copia_junta_regional','pr.Copia_junta_nacional','pr.Junta_regional_cual',
        'sie.Nombre_entidad as Ciudad_Junta','pr.N_anexos','pr.Elaboro_pronuncia','pr.Reviso_Pronuncia','pr.Ciudad_correspon','pr.N_radicado','pr.Firmar','pr.Fecha_correspondencia'
        ,'pr.Archivo_pronuncia')
        ->leftJoin('sigmel_gestiones.sigmel_lista_entidades as c', 'c.Id_Entidad', '=', 'pr.Id_primer_calificador')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as e', 'e.Id_Entidad', '=', 'pr.Id_nombre_calificador')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as p', 'p.Id_Parametro', '=', 'pr.Id_tipo_pronunciamiento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_eventos as ti', 'ti.Id_Evento', '=', 'pr.Id_tipo_evento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as or', 'or.Id_Parametro', '=', 'pr.Id_tipo_origen')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'sie.Id_Entidad', '=', 'pr.Junta_regional_cual')
        ->where([
            ['pr.ID_evento', '=', $id_evento],
            ['pr.Id_Asignacion', '=', $id_asignacion]
        ])
        ->get();
    }
    public function retornarInformacionComunicado($id_comunicado){
        return sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
            ->where([['Id_Comunicado',$id_comunicado]])
            ->get();
    }

    public function retornarcuentaConAfpConocimiento($id_evento){
        return DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_afiliado_eventos as siae')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_afp_entidad_conocimiento', '=', 'sie.Id_Entidad')
        ->select('siae.Entidad_conocimiento')
        ->where([['siae.ID_evento', $id_evento]])
        ->get();
    }
    
    public function generacionConsecutivoIdDestinatario(){
        $letras = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $randomString = '';

        for ($i = 0; $i < 5; $i++) {
            $randomIndex = rand(0, strlen($letras) - 1); // Genera un índice aleatorio
            $randomString .= $letras[$randomIndex]; // Añade la letra correspondiente a la cadena
        }
        return $randomString.microtime(true);
    }

    public function asignacionConsecutivoIdDestinatario($is_jrci = false, $is_jnci = false){
        $id_destinatarios = [];
        //Se establecen los prefijos que va a llevar cada uno de los posibles destinatarios
        $prefijos = [
            'AFI_',
            'EMP_',
            'EPS_',
            'AFP_',
            'ARL_',
            'FPC_'
        ];
        //Si JRCI es visible en los posibles destinatarios se agrega a la lista (solo debe ser visible en modulo de Juntas)
        if($is_jrci){
            array_push($prefijos,'JRC_');
        }
        //Si JNCI es visible en los posibles destinatarios se agrega a la lista (solo debe ser visible en modulo de Juntas)
        if($is_jnci){
            array_push($prefijos,'JNC_');
        }

        /* Se iterara en base a cada posible destinatario y se consultara el id actual, se generara el nuevo id, se guardara y asi sucesivamente,
        se hace de esta forma para garantizar que si varias personas estan generando un comunicado al mismo tiempo no llegue a presentarse el incon-
        veniente de que un id_destinatario se repita. */

        //Se inicia una transacción en la cual lo que nos interesa es que si ocurre un error en el proceso de inserción, automaticamente haga un rollback
        DB::beginTransaction();
        try{
            for($i = 0; $i < count($prefijos); $i++){
                $actualizar_id_destinatario = [
                    'Consecutivo_Destinatario' => $this->generacionConsecutivoIdDestinatario()
                ];
                //Se actualiza el id_destinatario en la base de datos.
                sigmel_consecutivos_destinatarios::on('sigmel_gestiones')->where([['Id',1],['Estado','activo']])->update($actualizar_id_destinatario);
                //Creamos una tupla con los ids_de_destinatario 
                array_push($id_destinatarios, $prefijos[$i].$actualizar_id_destinatario['Consecutivo_Destinatario']);
            }
        DB::commit();
        }catch (\Exception $e) {
            // Si ocurre un error, deshacer la transacción
            DB::rollBack();
            throw $e;
        }
        if(!empty($id_destinatarios)){
            return implode(',',$id_destinatarios);
        }
        return null;
    }

    public function retornarNumeroDeServicio($id_asignacion){
        return sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                ->where([['Id_Asignacion', $id_asignacion]])
                ->value('Id_servicio');
    }

}