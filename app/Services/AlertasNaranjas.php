<?php 
namespace App\Services;
use Illuminate\Support\Facades\DB;
use DateTime;
use App\Models\sigmel_informacion_parametrizaciones_clientes;
use App\Services\FormulaAlertas;
use Illuminate\Support\Facades\Auth;
use App\Contracts\Acciones;

class AlertasNaranjas extends Acciones{
    protected $Tiempo_alerta,$F_accionEvento,$newIdAsignacion,
    $Porcentaje_alerta_naranja,$Porcentaje_alerta_roja,
    $newIdEvento,$Id_proceso,$Id_servicio,$id_cliente,$AccionEvento,
    $date_time,$nombre_usuario,$date;

    protected $estadoOk = "Se establecio una alerta para el evento %s ya que este tiene configurada una alerta";

    protected $estadoFail = "El evento %s no cuenta con una alerta parametrizada";

    protected $status;

    /**
     * Inicializa las variables
     */
    public function init($fechaAccion, $accionEvento, $idCliente, $idProceso, $idServicio, $idEvento, $idAsignacion)
    {
        $this->setBasicInfo($fechaAccion, $accionEvento, $idCliente, $idProceso, $idServicio, $idEvento, $idAsignacion);
        $this->setTimeInfo();
        $this->setUserInfo();
        $status = $this->setAlertInfo();

        if($status == 'fail'){
            return [
                "estado" => $status,
                "mensaje" => sprintf($this->estadoFail, $this->newIdEvento)
            ];
        }

        return [
            "estado" => 'ok',
            "mensaje" => $this->determinarFormula()
        ];

    }

    /**
     * Informacion basica del evento
     */
    protected function setBasicInfo($fechaAccion, $accionEvento, $idCliente, $idProceso, $idServicio, $idEvento, $idAsignacion)
    {
        $this->F_accionEvento = $fechaAccion;
        $this->AccionEvento = $accionEvento;
        $this->id_cliente = $idCliente;
        $this->Id_proceso = $idProceso;
        $this->Id_servicio = $idServicio;
        $this->newIdEvento = $idEvento;
        $this->newIdAsignacion = $idAsignacion;
    }

    /**
     * Informacion respecto al momento en el que fue ejecutado
     */
    protected function setTimeInfo()
    {
        $this->date_time = date("Y-m-d H:i:s");
        $this->date = date("Y-m-d");
    }

    /**
     * Informacion del usuario que esta ejecutando el proceso
     */
    protected function setUserInfo()
    {
        $this->nombre_usuario = Auth::user()->name;
    }

    /**
     * Informacion respecto las alertas
     */
    protected function setAlertInfo()
    {
        $infoAlerta = sigmel_informacion_parametrizaciones_clientes::on('sigmel_gestiones')
            ->where([
                ['Accion_ejecutar', $this->AccionEvento],
                ['Id_cliente', $this->id_cliente],
                ['Id_proceso', $this->Id_proceso],
                ['Servicio_asociado', $this->Id_servicio],
                ['Status_parametrico', 'Activo']
            ])
            ->first(['Tiempo_alerta', 'Porcentaje_alerta_naranja', 'Porcentaje_alerta_roja']);

        if($infoAlerta == null){
            $this->status = 'fail';
            return;
        }

        $this->Tiempo_alerta = $infoAlerta->Tiempo_alerta;
        $this->Porcentaje_alerta_naranja = $infoAlerta->Porcentaje_alerta_naranja;
        $this->Porcentaje_alerta_roja = $infoAlerta->Porcentaje_alerta_roja;

    }

    //Invoca la formula a calcular de acuerdo a la condicion evaluada
    public function determinarFormula(){
        $mensaje = sprintf($this->estadoFail, $this->newIdEvento);

        $condidiciones = [
            'FA' => $this->Tiempo_alerta &&  empty($this->Porcentaje_alerta_naranja) && empty($this->Porcentaje_alerta_roja), 
            'FA_AN' => $this->Tiempo_alerta && !empty($this->Porcentaje_alerta_naranja) && empty($this->Porcentaje_alerta_roja),
            'FA_AR' => $this->Tiempo_alerta &&  empty($this->Porcentaje_alerta_naranja) && !empty($this->Porcentaje_alerta_roja),
            'FA_AR_AN' => $this->Tiempo_alerta && !empty($this->Porcentaje_alerta_naranja) && !empty($this->Porcentaje_alerta_roja),
        ];

        foreach($condidiciones as $codigo => $condicion){
            if($condicion){
                $this->{$codigo}();
                $mensaje = sprintf($this->estadoOk, $this->newIdEvento);
                break;
            }
        }

        return $mensaje;
    }

}

?>