<?php
namespace App\Services;

use App\Models\sigmel_consecutivos_destinatarios;
use App\Models\sigmel_informacion_afiliado_eventos;
use App\Models\sigmel_informacion_asignacion_eventos;
use App\Models\sigmel_informacion_comite_interdisciplinario_eventos;
use App\Models\sigmel_informacion_comunicado_eventos;
use App\Models\sigmel_informacion_documentos_solicitados_eventos;
use App\Models\sigmel_informacion_eventos;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GlobalService
{
    /**
        * Retorna toda la informacion laboral en base al id de evento
        * 
        * @param string $Id_evento Id del evento del cual se requiere obtener la información laboral.
        *
        * @return Collection | null Devuelve una colección con la información del comunicado
    */
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
    /**
        * Retorna toda la informacion sobre una entidad especifica.
        * 
        * @param string $Id_entidad Id de la entidad de la cual se necesita obtener la información.
        *
        * @return Collection | null Devuelve una colección con la información del comunicado
    */
    public function retornarInformaciónEntidad($Id_entidad){
        //Retornar información de una entidad
        return DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_entidades as sie')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Ciudad', '=', 'sldm1.Id_municipios')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Departamento', '=', 'sldm2.Id_departamento')
        ->select('sie.Nombre_entidad', 'sie.Direccion', 'sie.Telefonos', 'sldm1.Nombre_municipio as Nombre_ciudad', 'sldm2.Nombre_departamento','sie.Emails as Email')
        ->where([['Id_Entidad', $Id_entidad]])
        ->get();
    }
    /**
        * Retorna toda la informacion del comite interdisciplinario.
        * 
        * @param string $Id_evento Id del evento al cual pertenece el modulo / submodulo del cual se desea obtener el comite interdisciplinario del cual se quiere obtener la información
        *
        * @param string $Id_asignacion Id de asignación del modulo/submodulo del cual desea obtener el comite interdisciplinario
        *
        * @return Collection | null Devuelve una colección con la información del comunicado
    */
    public function retornarComiteInterdisciplinario($Id_evento, $Id_asignacion){
        // Comite interdisciplinario
        return sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento',$Id_evento],
            ['Id_Asignacion',$Id_asignacion]
        ])
        ->get();
    }
    /**
        * Retorna toda la informacion de un pronunciamiento origen (Aunque se supone deberia funcionar para PCL también si no hacer un override).
        * 
        * @param string $id_evento Id del evento del cual se requiere obtener la información del pronunciamiento.
        *
        * @param string $id_asignacion Id de asignación del pronuncimiento del que se requiere la información
        *
        * @return Collection | null Devuelve una colección con la información
    */
    public function retornarInformacionPronunciamiento($id_evento, $id_asignacion){
        $resultado =  DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_pronunciamiento_eventos as pr')
        ->select('pr.ID_evento','pr.Id_Asignacion', 'Id_proceso', 'pr.Id_primer_calificador','c.Tipo_Entidad','pr.Id_nombre_calificador','e.Nombre_entidad'
        ,'pr.Nit_calificador','pr.Dir_calificador','pr.Email_calificador','pr.Telefono_calificador','pr.Depar_calificador','pr.Ciudad_calificador'
        ,'pr.Id_tipo_pronunciamiento','p.Nombre_parametro as Tpronuncia','pr.Id_tipo_evento','ti.Nombre_evento','pr.Id_tipo_origen','or.Nombre_parametro as T_origen'
        ,'pr.Fecha_evento','pr.Dictamen_calificador','pr.Fecha_calificador','pr.N_siniestro','pr.Fecha_estruturacion','pr.Porcentaje_pcl','pr.Rango_pcl'
        ,'pr.Decision','pr.Fecha_pronuncia','pr.Asunto_cali','pr.Sustenta_cali','pr.Destinatario_principal','pr.Tipo_entidad','pr.Nombre_entidad as Nombre_entidad_correspon',
        'pr.Copia_afiliado','pr.copia_empleador','pr.Copia_eps','pr.Copia_afp','pr.Copia_arl','pr.Copia_Afp_Conocimiento','pr.Copia_junta_regional','pr.Copia_junta_nacional','pr.Junta_regional_cual',
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

        if ($resultado->isEmpty()) {
            return null;  // Si no hay resultados, retorna null
        }
    
        return $resultado;
    }
    /**
        * Retorna toda la informacion de un comunicado.
        * 
        * @param string $id_comunicado Id del comunicado del cual se quiere obtener la información
        *
        * @return Collection | null Devuelve una colección con la información del comunicado
    */
    public function retornarInformacionComunicado($id_comunicado){
        $resultado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
            ->where([['Id_Comunicado',$id_comunicado]])
            ->get();

        if ($resultado->isEmpty()) {
            return null;  // Si no hay resultados, retorna null
        }
    
        return $resultado;
    }
    /**
        * Retorna toda la informacion de un comunicado.
        * 
        * @param string $id_comunicado Id del comunicado del cual se quiere obtener la información
        *
        * @return Collection | null Devuelve una colección con la información del comunicado o null si no encuentra nada
    */
    public function retornarcuentaConAfpConocimiento($id_evento){
        $resultado = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_afiliado_eventos as siae')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_afp_entidad_conocimiento', '=', 'sie.Id_Entidad')
        ->select('siae.Entidad_conocimiento', 'siae.Id_afp_entidad_conocimiento', 'siae.Id_afp_entidad_conocimiento2','siae.Id_afp_entidad_conocimiento3','siae.Id_afp_entidad_conocimiento4',
        'siae.Id_afp_entidad_conocimiento5','siae.Id_afp_entidad_conocimiento6','siae.Id_afp_entidad_conocimiento7','siae.Id_afp_entidad_conocimiento8', 'siae.Otras_entidades_conocimiento')
        ->where([['siae.ID_evento', $id_evento]])
        ->get();
        if ($resultado->isEmpty()) {
            return null;  // Si no hay resultados, retorna null
        }
    
        return $resultado;
    }
    
    /**
        * Genera el consecutivo el cual va a ser usado como identificador de alguno de los posibles destinatarios del modal de correspondencia.
        * 
        * @return string | null Devuelve un string (id de destinatario) el cual es armado con 5 caracteres alfanumericos elegidos aleatoriamente + 
        * el microtimestamp del momento en el que fue recibida la petición
    */
    public function generacionConsecutivoIdDestinatario(){
        $letras = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $randomString = '';

        for ($i = 0; $i < 5; $i++) {
            $randomIndex = rand(0, strlen($letras) - 1); // Genera un índice aleatorio
            $randomString .= $letras[$randomIndex]; // Añade la letra correspondiente a la cadena
        }
        return $randomString.microtime(true);
    }
    /**
        * Retorna el numero de siniestro global del evento.
        * 
        * @param bool $is_jrci Bandera que indica que el comunicado requiere un id de destinatario para la JRCI.
        *
        * @param bool $is_jnci Bandera que indica que el comunicado requiere un id de destinatario para la JNCI.
        *
        * @return string | null Devuelve una cadena string con la los Id de destinatarios separados por , y si algo falla devuelve null
    */
    public function asignacionConsecutivoIdDestinatario($is_jrci = false, $is_jnci = false){
        $id_destinatarios = [];
        //Se establecen los prefijos que va a llevar cada uno de los posibles destinatarios, En la PBS092 solicitaron agregar 8 Entidades de conocimiento se realizo de forma estatica
        $prefijos = [
            'AFI_',
            'EMP_',
            'EPS_',
            'AFP_',
            'ARL_',
            'FPC_',
            'FPC2_',
            'FPC3_',
            'FPC4_',
            'FPC5_',
            'FPC6_',
            'FPC7_',
            'FPC8_'
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
    /**
        * Retorna el Id del servicio en base al id de asignación.
        * 
        * @param string Necesario ya que la consulta se hace en base al id de asignación.
        *
        * @return int | null Retorna el numero de id de servicio o devuelve null si el id de asignación no existe.
    */
    public function retornarNumeroDeServicio($id_asignacion){
        return sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                ->where([['Id_Asignacion', $id_asignacion]])
                ->value('Id_servicio');
    }

    public function retornarListadoDocumentos($id_evento,$id_proceso,$id_asignacion){
        $resultado = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento',$id_evento],
            ['Id_Asignacion', $id_asignacion],
            ['Estado','Activo'],
            ['Id_proceso', $id_proceso]
        ])
        ->get();

        if ($resultado->isEmpty()) {
            return null;  // Si no hay resultados, retorna null
        }
    
        return $resultado;
    }
    /**
        * Retorna el numero de siniestro global del evento.
        * 
        * @param string $id_evento Necesario para saber a cual evento hace referencia.
        *
        * @return Collection | null Devuelve una colección con la información y si no devuelve null
    */
    public function retornarNumeroSiniestro($id_evento){
        //Traer el N_siniestro del evento
        $resultado = sigmel_informacion_eventos::on('sigmel_gestiones')
        ->select('N_siniestro')
        ->where([['ID_evento',$id_evento]])
        ->get();

        if ($resultado->isEmpty()) {
            return null;  // Si no hay resultados, retorna null
        }
    
        return $resultado;
    }

    /**
        * Retorna la información necesaria para una copia de JNCI.
        * 
        * @return string Devuelve un string con la información concatenada.
    */
    public function retornarJnci(){
        $datos_jnci = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Ciudad', '=', 'sldm1.Id_municipios')
            ->select('sie.Nombre_entidad', 
                'sie.Direccion', 
                'sie.Telefonos',
                'sie.Emails',
                'sldm.Nombre_departamento',
                'sldm1.Nombre_municipio as Nombre_ciudad'
            )->where([
                ['sie.IdTipo_entidad', 5]
            ])->limit(1)->get();

        $nombre_jnci = $datos_jnci[0]->Nombre_entidad;
        $direccion_jnci = $datos_jnci[0]->Direccion;
        $email_jnci = $datos_jnci[0]->Emails;
        $telefonos_jnci = $datos_jnci[0]->Telefonos;
        $ciudad_jnci = $datos_jnci[0]->Nombre_ciudad;
        $departamento_jnci = $datos_jnci[0]->Nombre_departamento;

        return $nombre_jnci."; ".$direccion_jnci."; ".$email_jnci."; ".$telefonos_jnci."; ".$ciudad_jnci." - ".$departamento_jnci;
        
    }
    /**
        * Retorna la información necesaria para una copia de JRCI.
        * 
        * @param string $idJrci Realiza la busqueda de la JRCI en base a este ID, puede ser null, pero entonces el $nombreJrci debe tener algun valor, de lo contrario devolvera null.
        *
        * @param string $nombreJrci Realiza la busqueda de la JRCI en base al nombre de la JRCI, pero entonces el $idJrci debe tener algun valor, de lo contrario devolvera null.
        * 
        * @return string | null Devuelve un string con la información concatenada. Si id_jrci y nombreJrci son null devolvera null.
    */
    public function retornarJrci($idJrci = null, $nombreJrci = null){
        $datos_jrci = null;
        if($idJrci){
            $datos_jrci = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Ciudad', '=', 'sldm1.Id_municipios')
            ->select('sie.Nombre_entidad', 
                'sie.Nit_entidad', 
                'sie.Direccion', 
                'sie.Telefonos',
                'sie.Otros_Telefonos',
                'sie.Emails',
                'sldm.Id_departamento',
                'sldm.Nombre_departamento',
                'sldm1.Id_municipios',
                'sldm1.Nombre_municipio as Nombre_ciudad'
            )->where([
                ['sie.Id_Entidad', $idJrci]
            ])->get();
        }
        else if($nombreJrci){
            $datos_jrci = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Ciudad', '=', 'sldm1.Id_municipios')
            ->select('sie.Nombre_entidad', 
                'sie.Nit_entidad', 
                'sie.Direccion', 
                'sie.Telefonos',
                'sie.Otros_Telefonos',
                'sie.Emails',
                'sldm.Id_departamento',
                'sldm.Nombre_departamento',
                'sldm1.Id_municipios',
                'sldm1.Nombre_municipio as Nombre_ciudad'
            )->where([
                ['sie.Nombre_entidad', $nombreJrci]
            ])->get();
        }

        if($datos_jrci){
            $nombre_jrci = $datos_jrci[0]->Nombre_entidad;
            $direccion_jrci = $datos_jrci[0]->Direccion;

            if ($datos_jrci[0]->Otros_Telefonos != "") {
                $telefonos_jrci = $datos_jrci[0]->Telefonos.",".$datos_jrci[0]->Otros_Telefonos;
            } else {
                $telefonos_jrci = $datos_jrci[0]->Telefonos;
            }
            $email_jrci = $datos_jrci[0]->Emails;
            $ciudad_jrci = $datos_jrci[0]->Nombre_ciudad;
            $departamento_jrci = $datos_jrci[0]->Nombre_departamento;

            return $nombre_jrci."; ".$direccion_jrci."; ".$email_jrci."; ".$telefonos_jrci."; ".$ciudad_jrci." - ".$departamento_jrci;
        }
        return null;   
    }

    /**
        * Retorna la información necesaria para una copia de Empleador.
        * 
        * @param string $n_identificacion Necesario para saber en que empresa labora la persona.
        *
        * @param string $id_evento Necesario para determinar con exactitud el evento al cual hace referencia, ya que una persona puede tener varios eventos.
        * 
        * @return string Devuelve una cadena string con la información del empleador.
    */
    public function retornarEmpleador($n_identificacion, $id_evento){
        $datos_empleador = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sile.Id_departamento', '=', 'sldm.Id_departamento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sile.Id_municipio', '=', 'sldm2.Id_municipios')
        ->select('sile.Empresa', 'sile.Direccion', 'sile.Email','sile.Telefono_empresa', 'sldm.Nombre_departamento as Nombre_ciudad','sldm2.Nombre_municipio')
        ->where([['sile.Nro_identificacion', $n_identificacion],['sile.ID_evento', $id_evento]])
        ->get();

        if (preg_match("/&/", $datos_empleador[0]->Empresa)) {
            $nombre_empleador = htmlspecialchars(preg_replace('/&/', '&amp;', $datos_empleador[0]->Empresa));
        } else {
            $nombre_empleador = $datos_empleador[0]->Empresa;
        }
        $direccion_empleador = $datos_empleador[0]->Direccion;
        $email_empleador = $datos_empleador[0]->Email;
        $telefono_empleador = $datos_empleador[0]->Telefono_empresa;
        $ciudad_empleador = $datos_empleador[0]->Nombre_ciudad;
        $municipio_empleador = $datos_empleador[0]->Nombre_municipio;
        return $nombre_empleador."; ".$direccion_empleador."; ".$email_empleador."; ".$telefono_empleador."; ".$ciudad_empleador."; ".$municipio_empleador.".";
    }
    /**
        * Retorna la información necesaria para una copia de Afiliado.
        * 
        * @param string $n_identificacion Necesario para saber cual es la persona y consultar sus datos.
        *
        * @param string $id_evento Necesario para determinar con exactitud el evento al cual hace referencia, ya que una persona puede tener varios eventos.
        *
        * @return string Devuelve una cadena string con la información del afiliado
    */
    public function retornarAfiliado($n_identificacion, $id_evento){
        $informacion_afiliado = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'siae.Id_departamento', '=', 'sldm.Id_departamento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'siae.Id_municipio', '=', 'sldm2.Id_municipios')
        ->select('siae.Nombre_afiliado', 'siae.Direccion', 'siae.Telefono_contacto', 'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio', 'siae.Email')
        ->where([['siae.Nro_identificacion', $n_identificacion],['siae.ID_evento', $id_evento]])
        ->get();
        $nombreAfiliado = $informacion_afiliado[0]->Nombre_afiliado;
        $direccionAfiliado = $informacion_afiliado[0]->Direccion;
        $telefonoAfiliado = $informacion_afiliado[0]->Telefono_contacto;
        $ciudadAfiliado = $informacion_afiliado[0]->Nombre_ciudad;
        $municipioAfiliado = $informacion_afiliado[0]->Nombre_municipio;
        $emailAfiliado = $informacion_afiliado[0]->Email;            
        return $nombreAfiliado."; ".$direccionAfiliado."; ".$emailAfiliado."; ".$telefonoAfiliado."; ".$ciudadAfiliado."; ".$municipioAfiliado."."; 
    }
    /**
        * Retorna la información necesaria para una copia de la entidad que sea enviada.
        * 
        * @param string $n_identificacion Necesario para saber cual es la persona y consultar sus datos o entidades a las que esta afiliado.
        *
        * @param string $id_evento Necesario para determinar con exactitud el evento al cual hace referencia, ya que una persona puede tener varios eventos.
        *
        * @param string $entidad Entidad de la cual se necesita la copia puede ser (EPS, AFP o ARL).
        *
        * @return string | null Devuelve un string con la información concatenada de la entidad y un null si la entidad es distinta a (AFP,ARL,EPS)
    */
    public function retornarCopiaEntidad($n_identificacion, $id_evento, $entidad){
        $entidad_a_consultar = null;
        switch (strtolower($entidad)) {
            case 'eps':
                $entidad_a_consultar = 'siae.Id_eps';
                break;
            case 'afp':
                $entidad_a_consultar = 'siae.Id_afp';
                break;
            case 'arl':
                $entidad_a_consultar = 'siae.Id_arl';
                break;
            default:
                break;
        }
        if($entidad_a_consultar){
            $informacion_entidad = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', $entidad_a_consultar, '=', 'sie.Id_Entidad')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
            ->select('sie.Nombre_entidad', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos', 'sie.Emails as Email',
            'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
            ->where([['Nro_identificacion', $n_identificacion],['ID_evento', $id_evento]])
            ->get();
    
            $nombre_entidad = $informacion_entidad[0]->Nombre_entidad;
            $direccion_entidad = $informacion_entidad[0]->Direccion;
            $email_entidad = $informacion_entidad[0]->Email;
            $telefonos_entidad = $informacion_entidad[0]->Telefonos;
            $departamento_entidad = $informacion_entidad[0]->Nombre_departamento;
            $ciudad_entidad = $informacion_entidad[0]->Nombre_ciudad;
            return $nombre_entidad."; ".$direccion_entidad."; ".$email_entidad."; ".$telefonos_entidad."; ".$ciudad_entidad."; ".$departamento_entidad;
        }
        return null;
    }
    /**
        * Retorna la modalidad de calificación de PCL (solo PCL -> Calificación Tecnica, Recalificación, Revisión Pensión).
        * 
        * @param string $id_evento Necesario para determinar con exactitud el evento al cual hace referencia, ya que una persona puede tener varios eventos.
        *
        * @param string $id_asignacion Necesario para saber a que asignacion esta haciendo referencia.
        *
        * @return Collection | null Devuelve una colección con la información y si no devuelve null
    */
    public function retornarModalidadCalificacionPCL($id_evento, $id_asignacion){
        $resultado = DB::table('sigmel_gestiones.sigmel_informacion_decreto_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Modalidad_calificacion')
        ->where([['side.ID_Evento',$id_evento], ['side.Id_Asignacion', $id_asignacion]])
        ->select('side.Modalidad_calificacion', 'slp.Nombre_parametro as Nombre_modalidad_calificacion')
        ->get();

        if ($resultado->isEmpty()) {
            return null;  // Si no hay resultados, retorna null
        }
    
        return $resultado;
    }

    /**
        * Retorna la información del primer cliente registrado en la base de datos.
        * 
        * @return Collection Devuelve una colección con la información del cliente con ID #1 en la base de datos
    */
    public function infoCliente(){
        return DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_clientes as sc')
        ->select('sc.Nombre_cliente','sltc.Nombre_tipo_cliente')
        ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_clientes as sltc', 'sc.Tipo_cliente', '=', 'sltc.Id_TipoCliente')
        ->where([['sc.Id_cliente', '=', 1]])
        ->get();
    }

    /**
        * Query para consulta de Tipo de colaborador de los campos de usuario que se pasen para la tabla de información asignación eventos.
        * 
        * @param string $id_asignacion Necesario para buscar en la tabla dfe información asignación eventos.
        *
        * @param string $campo_a_consultar Este especifica el campo donde esta el nombre del usuario y en base a lo que haya en ese campo es con lo que se hara la busqueda en la tabla de usuarios.
        *
        * @return Collection | null Devuelve una colección con la información y si no devuelve null
    */
    public function InformacionCamposUsuarioAsignacionEventos($id_asignacion, $campo_a_consultar){
        return DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_asignacion_eventos as siae')
        ->leftJoin('sigmel_sys.users as u','siae.'.$campo_a_consultar,'=','u.name')
        ->select('siae.Nombre_profesional','u.id','u.name','u.tipo_colaborador')
        ->where([['Id_Asignacion', '=', $id_asignacion]])
        ->whereNotNull('u.id')
        ->get();
    }
    /**
        * Query para consulta de Tipo de colaborador de los campos de usuario que se pasen para la tabla de información asignación eventos.
        * 
        * @param string $id_asignacion Necesario para buscar en la tabla dfe información asignación eventos.
        *
        * @param string $campo_a_consultar Este especifica el campo donde esta el nombre del usuario y en base a lo que haya en ese campo es con lo que se hara la busqueda en la tabla de usuarios.
        *
        * @return Collection | null Devuelve una colección con la información y si no devuelve null
    */
    public function ComiteInterdisciplinarioModulosPrincipales($id_evento, $id_asignacion){
        return DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_comite_interdisciplinario_eventos as sicie')
        ->leftJoin('sigmel_sys.users as u','sicie.Profesional_comite','=','u.name')
        ->select('sicie.Profesional_comite','sicie.F_visado_comite','u.id','u.name','u.tipo_colaborador')
        ->where([
            ['ID_evento',$id_evento],
            ['Id_Asignacion',$id_asignacion]
         ])
        ->get();
    }

    /* Función para retornar la info de las entidades de conocimiento dependiendo del tipo de proforma */
    public function informacionEntidadesConocimientoEvento ($id_evento, $tipo_proforma){

        // Obtenemos la o las entidades de conocimiento del evento
        $entidades_conocimiento = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_afiliado_eventos as siae')
        ->select('siae.Id_afp_entidad_conocimiento', 'siae.Id_afp_entidad_conocimiento2','siae.Id_afp_entidad_conocimiento3','siae.Id_afp_entidad_conocimiento4','siae.Id_afp_entidad_conocimiento5','siae.Id_afp_entidad_conocimiento6','siae.Id_afp_entidad_conocimiento7','siae.Id_afp_entidad_conocimiento8','siae.Otras_entidades_conocimiento')
        ->where([['siae.ID_evento', $id_evento]])
        ->first();

        if ($entidades_conocimiento) {
            $entidades = [
                $entidades_conocimiento->Id_afp_entidad_conocimiento,
                $entidades_conocimiento->Id_afp_entidad_conocimiento2,
                $entidades_conocimiento->Id_afp_entidad_conocimiento3,
                $entidades_conocimiento->Id_afp_entidad_conocimiento4,
                $entidades_conocimiento->Id_afp_entidad_conocimiento5,
                $entidades_conocimiento->Id_afp_entidad_conocimiento6,
                $entidades_conocimiento->Id_afp_entidad_conocimiento7,
                $entidades_conocimiento->Id_afp_entidad_conocimiento8
            ];

            $entidades = array_filter($entidades, function ($value) {
                return $value !== null && $value !== '' && $value !== 0;
            });

            // Convertimos en string separado por comas
            $string_entidades = implode(',', $entidades);

            // Convertimos en array para el WHERE IN
            $array_string_entidades = explode(',', $string_entidades);
        } else {
            $string_entidades = '';
            $array_string_entidades = [];
        }
        
        // dd($array_string_entidades);
        $datos_entidades_conocimiento = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
        ->leftJoin('sigmel_gestiones.sigmel_lista_entidades as sle', 'sie.IdTipo_entidad', '=', 'sle.Id_Entidad')
        // ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
        // ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', function ($join) {
            $join->on('sie.Id_departamento', '=', 'sldm.Id_departamento')
                ->on('sie.Id_Ciudad', '=', 'sldm.Id_municipios');
        })
        ->select(
            'sie.Id_Entidad',
            'sle.Tipo_Entidad',
            'sie.Nombre_entidad',
            'sie.Direccion',
            'sie.Emails as Email',
            'sie.Telefonos',
            'sldm.Nombre_municipio as Ciudad',
            'sldm.Nombre_departamento as Departamento'
        )
        ->whereIn('sie.Id_Entidad', $array_string_entidades)
        ->orderByRaw("FIELD(sie.Id_Entidad, " . implode(',', $array_string_entidades) . ")")
        ->get();

        $array_datos_entidades_conocimiento = json_decode(json_encode($datos_entidades_conocimiento, true));

        // echo "<pre>";
        // print_r($array_datos_entidades_conocimiento);
        // echo "</pre>";

        $string_entidades = '';
        for ($i=0; $i < count($array_datos_entidades_conocimiento); $i++) {
            $tipo_entidad = $array_datos_entidades_conocimiento[$i]->Tipo_Entidad;
            $nombre_entidad = $array_datos_entidades_conocimiento[$i]->Nombre_entidad;
            $direccion_entidad = $array_datos_entidades_conocimiento[$i]->Direccion;
            $email_entidad = $array_datos_entidades_conocimiento[$i]->Email;
            $telefono_entidad = $array_datos_entidades_conocimiento[$i]->Telefonos;
            $ciudad_entidad = $array_datos_entidades_conocimiento[$i]->Ciudad;
            $departamento_entidad = $array_datos_entidades_conocimiento[$i]->Departamento;
            
            if ($tipo_proforma == 'pdf') {
                $string_entidades .= "<tr><td class='copias'><span class='negrita'>{$tipo_entidad}: </span>{$nombre_entidad} - {$direccion_entidad}; {$email_entidad}; {$telefono_entidad}; {$ciudad_entidad}; {$departamento_entidad}</td></tr>";
            }elseif ($tipo_proforma == 'word') {
                $string_entidades .= "<tr><td style='border: 1px solid #000; padding: 5px; text-align: justify; font-family: Verdana; font-size: 8pt; font-style: italic;'><span style='font-weight:bold;'>{$tipo_entidad}: </span>{$nombre_entidad} - {$direccion_entidad}; {$email_entidad}; {$telefono_entidad}; {$ciudad_entidad}; {$departamento_entidad}</td></tr>";
            }else if($tipo_proforma == 'otra_forma'){
                $string_entidades .= "<tr class='fuente_todo_texto'><td colspan='8'><span class='negrita'>{$tipo_entidad}: </span>{$nombre_entidad} - {$direccion_entidad}; {$email_entidad}; {$telefono_entidad}; {$ciudad_entidad}; {$departamento_entidad}</td></tr>";
            }
        }
        return $string_entidades;
    }

    //Retorna el string de copias de entidad de conocimiento
    public function retornarStringCopiasEntidadConocimiento($id_evento){
        $entidades_conocimiento = $this->retornarcuentaConAfpConocimiento($id_evento);
        if($entidades_conocimiento[0]->Entidad_conocimiento == 'Si'){
            $entidades_concatenadas[] = 'AFP_Conocimiento';   
            // El 8 es la cantidad de entidades conocimiento que puede tener, PBS092 se valido y se decidio dejar de forma estatica 
            for ($i=1; $i < 8; $i++) { 
                $AFP_Conocimiento = 'AFP_Conocimiento' . ($i + 1);
                $entidades_concatenadas[] = $AFP_Conocimiento;                
            }
            return $entidades_concatenadas = implode(', ', $entidades_concatenadas);
        }else{
            return null;
        }
    }

    /**
        * Valida que el oficio exista y si existe retona las copias del mismo
        * 
        * @param string $id_evento Necesario para identificar el evento al cual se le necesita hacer el ajuste.
        *
        * @param string $id_asignacion Necesario para identificar la asignación dada en el evento.
        *
        * @param string $id_proceso Necesario para conocer el proceso del servicio
        *
    */
    public function ValidarExistenciaOficioYCopiasOficio($id_evento,$id_asignacion,$id_proceso){
        $oficio = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento',$id_evento],
            ['Id_Asignacion',$id_asignacion],
            ['Id_proceso',$id_proceso],
        ])
        ->whereIn('Tipo_descarga',['Oficio','Comunicado'])->first();
        if(!$oficio){
            return null;
        }
        return $oficio->Agregar_copia ?? null;
    }
    /**
        * Query para agregar o quitar la copia de entidad conocimiento al dictamen PBS092.
        * 
        * @param string $id_evento Necesario para identificar el evento al cual se le necesita hacer el ajuste.
        *
        * @param string $id_asignacion Necesario para identificar la asignación dada en el evento.
        *
        * @param string $id_proceso Necesario para conocer el proceso del servicio
        *
    */
    public function AgregaroQuitarCopiaEntidadConocimientoDictamen($id_evento,$id_asignacion,$id_proceso,$copias_oficio){
        $copias_entidad_conocimiento = $this->retornarStringCopiasEntidadConocimiento($id_evento);
        //Copias actuales del dictamen
        $dictamen = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento',$id_evento],
            ['Id_Asignacion',$id_asignacion],
            ['Id_proceso',$id_proceso],
            ['Tipo_descarga','Dictamen'],
        ])->get();

        if($dictamen){
            //Copias del dictamen
            $copias_dictamen = $dictamen[0]->Agregar_copia;
            if($copias_dictamen){
                // Expresión regular para eliminar "AFP_Conocimiento" con o sin número
                $cadena_limpia = preg_replace('/,?\s*AFP_Conocimiento\d*/', '', $copias_dictamen);
                // Eliminar espacios en blanco innecesarios y posibles comas dobles
                $copias_dictamen = trim(preg_replace('/,\s*,/', ',', $cadena_limpia), ', ');
            }
            if(str_contains($copias_oficio, "AFP_Conocimiento")){
                if($copias_dictamen){
                    $copias_dictamen .= ', '.$copias_entidad_conocimiento;
                }else{
                    $copias_dictamen = $copias_entidad_conocimiento;
                }
            }
            $actualización_dictamen = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento',$id_evento],
                ['Id_Asignacion',$id_asignacion],
                ['Id_proceso',$id_proceso],
                ['Tipo_descarga','Dictamen'],
            ])->update(['Agregar_copia' => $copias_dictamen]);
            return $actualización_dictamen;
        }
    }
    public function getAFPConocimientosParaCorrespondencia($id_evento, $id_asignacion){
        $entidades_conocimiento = $this->retornarcuentaConAfpConocimiento($id_evento);
        if($entidades_conocimiento && $entidades_conocimiento[0]->Entidad_conocimiento == 'Si'){
            $lista_entidades_conocimiento = [];
            for($i = 1; $i <= 8; $i++){
                $campo = ($i == 1) ? 'Id_afp_entidad_conocimiento' : 'Id_afp_entidad_conocimiento' . $i;
                $tipo_correspondencia = ($i == 1) ? 'afp_conocimiento' : 'afp_conocimiento' . $i;
                if (!empty($entidades_conocimiento[0]->$campo) && $entidades_conocimiento[0]->$campo != 0) {
                    $entidades = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_entidades as sle', 'sie.IdTipo_entidad', '=', 'sle.Id_Entidad')
                        ->select('sie.Id_Entidad','sle.Tipo_Entidad','sie.Nombre_entidad')
                        ->where('sie.Id_Entidad', '=',$entidades_conocimiento[0]->$campo)
                        ->get();
                    if($entidades->isEmpty()){
                        $entidades = null;
                    }
                    array_push($lista_entidades_conocimiento,['Entidad'=>$entidades,'tipo_correspondencia'=>$tipo_correspondencia]);
                }
            }
            return $lista_entidades_conocimiento;
        }
        return null;
    } 

    // Función para actualizar la columna Agregar_copia de la tabla de comunicados acorde a las entidades de conocimiento (botón ojo descarga)
    public function actualizarCopiasEntidadesComunicado($id_evento, $id_comunicado, $id_asignacion, $id_proceso){

        // traemos el string de la cantidad de entidades de conocimientos presentes del evento
        $entidades_conocimiento_evento = $this->retornarStringCopiasEntidadConocimiento($id_evento);
        
        // transformamos la cadena en array
        $array_entidades_conocimiento_evento = explode(",", $entidades_conocimiento_evento);
        
        // consultamos el string de copias de la tabla sigmel_informacion_comunicado_eventos dependiendo del id de comunicado
        $copias_actuales_comunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
        ->select('Agregar_copia')
        ->where([['Id_Comunicado', $id_comunicado]])->get();

        $copias_actuales_comunicado_final = json_decode(json_encode($copias_actuales_comunicado,true));
        
        // transformamos la cadena en array
        $array_copias_actuales_comunicado = explode(",", $copias_actuales_comunicado_final[0]->Agregar_copia);
        
       if (!empty($copias_actuales_comunicado_final[0]->Agregar_copia) && !empty($entidades_conocimiento_evento)) {
        // Eliminamos las entidades de conocimiento que tiene actualmente el comunicado
        $nuevo_array_entidades_comunicado = array_filter($array_copias_actuales_comunicado, function ($valor) {
            return strpos(trim($valor), "AFP_Conocimiento") !== 0;
        });

        $nuevo_array_entidades_comunicado = array_values($nuevo_array_entidades_comunicado);

        // Insertamos al nuevo array las entidades existentes del evento. y lo convertimos en un string separado por comas
        $array_entidades_actualizadas = array_merge($nuevo_array_entidades_comunicado, $array_entidades_conocimiento_evento);
        $array_entidades_actualizadas = array_map('trim', $array_entidades_actualizadas);
        $string_entidades_actualizadas = implode(", ", $array_entidades_actualizadas);

       }elseif (!empty($copias_actuales_comunicado_final[0]->Agregar_copia) && empty($entidades_conocimiento_evento)) {

        // Eliminamos las entidades de conocimiento que tiene actualmente el comunicado
        $nuevo_array_entidades_comunicado = array_filter($array_copias_actuales_comunicado, function ($valor) {
            return strpos(trim($valor), "AFP_Conocimiento") !== 0;
        });

        $nuevo_array_entidades_comunicado = array_values($nuevo_array_entidades_comunicado);
        $array_entidades_actualizadas = $nuevo_array_entidades_comunicado;
        $array_entidades_actualizadas = array_map('trim', $array_entidades_actualizadas);
        $string_entidades_actualizadas = implode(", ", $array_entidades_actualizadas);

       }
    
        // Actualizamos la columna Agregar_copia de la tabla sigmel_informacion_comunicado_eventos dependiendo del comunicado
        $datos_actualizar = [
            'Agregar_copia' => $string_entidades_actualizadas
        ];

        sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
        ->where([['Id_Comunicado', $id_comunicado]])
        ->update($datos_actualizar);

        $this->AgregaroQuitarCopiaEntidadConocimientoDictamen($id_evento, $id_asignacion,$id_proceso,$copias_actuales_comunicado_final[0]->Agregar_copia);
        
    }
}