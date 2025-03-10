<?php
namespace App\Services;

use App\Contracts\Acciones;
use Illuminate\Support\Facades\DB;
use DateTime;

use App\Models\sigmel_informacion_eventos;
use App\Models\sigmel_informacion_parametrizaciones_clientes;
use App\Models\sigmel_informacion_acciones_automaticas_eventos;
use Illuminate\Support\Facades\Auth;

class MovimientosAutomaticas extends Acciones{

    /**
     * @var Mixed Datos correspondientes a la parametrica del proceso que se este ejecutando
     */
    protected $Movimiento_automatico,$Tiempo_movimiento,$Accion_automatica;

    /**
     * @var Mixed Datos correspondientes al evento que se esta procesando
     */
    protected $AccionEvento,$Id_proceso,$Id_servicio,$Id_evento,$id_cliente,$id_asignacion;

    /**
     * @var Date Fecha actual en la que se esta ejecutando el proceso
     */
    protected $timestap;
    
    /**
     * @var Array Estados correspondientes al proceso de movimientosAutomaticos
     */
    protected  $mensajes = [
        'default' => 'la acción parametrizada NO tiene Movimiento Automático',
        'mv_0' => "", // Mensaje cuando se ejecuta el movimiento
        'mv_1' => 'la acción parametrizada tiene movimiento automático, Tiempo de moviemiento (Días) pero no cuenta con una Acción automática',
        'mv_2' => 'la acción parametrizada tiene movimiento automático, Acción automatica pero no cuenta con Tiempo de moviemiento (Días)',
        'mv_3' => 'la acción parametrizada tiene movimiento automático, pero no cuenta con un Tiempo de moviemiento (Días) y Acción automática',
        "mv_4" => "La acción parametrizada %s tiene un movimiento automatico, pero dicha acción no se encuentra asociado al servicio actual %s"
    ];

    public function init($fechaAccion,$AccionEvento,$idCliente,$Id_proceso,$Id_servicio,$Id_evento,$id_asignacion){
//dd($fechaAccion,$AccionEvento,$idCliente,$Id_proceso,$Id_servicio,$Id_evento,$id_asignacion);
        $this->AccionEvento = $AccionEvento;
        $this->Id_proceso = $Id_proceso;
        $this->Id_servicio = $Id_servicio;
        $this->Id_evento = $Id_evento;
        $this->id_asignacion = $id_asignacion;
        $this->timestap = date("Y-m-d H:i:s");
    
        $idCliente = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_eventos')->select('Cliente')->where('ID_evento',$Id_evento)->first();
        
        $this->id_cliente = $idCliente->Cliente;
        $info_accion_automatica = sigmel_informacion_parametrizaciones_clientes::on('sigmel_gestiones')
        ->select('Movimiento_automatico','Tiempo_movimiento','Accion_automatica')
        ->where([
            ['Accion_ejecutar', $AccionEvento],
            ['Id_cliente', $this->id_cliente],
            ['Id_proceso', $Id_proceso],
            ['Servicio_asociado', $Id_servicio],
            ['Status_parametrico', 'Activo']
        ])->first();
        
        if($info_accion_automatica == null){
            return [
                "estado" => 'fail',
                "mensaje" => $this->getMensaje('default')
            ];
        }
        
        $this->Movimiento_automatico = $info_accion_automatica->Movimiento_automatico;
        $this->Tiempo_movimiento = $info_accion_automatica->Tiempo_movimiento;
        $this->Accion_automatica = $info_accion_automatica->Accion_automatica;
        
        return [
            "estado" => 'ok',
            "mensaje" => $this->ejecutar()
        ];
    }


    /**
     * Ejecuta el movimiento automatico siempre y cuando cumpla las validaciones
     */
    public function ejecutar() {
        if ($this->esMovimientoValido()) {
            return $this->ejecutar_movimiento();
        }

        //Si el movimiento no es ejecutado, devuelve el mensaje correspondiente al estado del movimiento.
        return $this->determinarEstadoMovimiento();
    }

    /**
     * Determina el mensaje correspondiente en funcion de las validaciones para ejecutar el movimiento
     */
    private function determinarEstadoMovimiento() {
        $condiciones = [
            'mv_1' => $this->Movimiento_automatico == 'Si' && !empty($this->Tiempo_movimiento) && empty($this->Accion_automatica),
            'mv_2' => $this->Movimiento_automatico == 'Si' && empty($this->Tiempo_movimiento) && !empty($this->Accion_automatica),
            'mv_3' => $this->Movimiento_automatico == 'Si' && empty($this->Tiempo_movimiento) && empty($this->Accion_automatica)
        ];

        foreach ($condiciones as $codigo => $condicion) {
            if ($condicion) {
                return $this->getMensaje($codigo);
            }
        }

        return $this->getMensaje('default');
    }

    /**
     * Condiciones para poder ejecutar el movimiento
     */
    private function esMovimientoValido() {
        return $this->Movimiento_automatico == 'Si' && !empty($this->Tiempo_movimiento) && !empty($this->Accion_automatica);
    }

    /**
     * Funcion principal que lleva a cabo la ejecucion del movimiento
     */
    protected function ejecutar_movimiento(){
        $info_datos_accion_automatica = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_parametrizaciones_clientes as sipc')
        ->leftJoin('sigmel_sys.users as u', 'u.id', '=', 'sipc.Profesional_asignado')
        ->select('sipc.Accion_ejecutar', 'sipc.Estado', 'sipc.Profesional_asignado', 'u.name')
        ->where([
            ['sipc.Accion_ejecutar', $this->Accion_automatica],
            ['sipc.Id_cliente', $this->id_cliente],
            ['sipc.Id_proceso', $this->Id_proceso],
            ['sipc.Servicio_asociado', $this->Id_servicio],
            ['sipc.Status_parametrico', 'Activo']
        ])->first();

        if($info_datos_accion_automatica == null){
            return [
                "estado" => "fail",
                "mensaje" => sprintf($this->getMensaje('mv_4'),$this->Accion_automatica,$this->Id_servicio)
            ];
        }
        $Accion_ejecutar_automatica = $info_datos_accion_automatica->Accion_ejecutar;
        $Profesional_asignado_automatico = $info_datos_accion_automatica->Profesional_asignado;
        $NombreProfesional_asignado_automatico = $info_datos_accion_automatica->name;
        $Id_Estado_evento_automatico = $info_datos_accion_automatica->Estado;

        $F_movimiento_automatico = $this->getFechaMovimiento($this->Tiempo_movimiento);                          
                            
        $array_info_datos_accion_automatica = [
            'Id_Asignacion' => $this->id_asignacion,
            'ID_evento' => $this->Id_evento,
            'Id_proceso' => $this->Id_proceso,
            'Id_servicio' => $this->Id_servicio,
            'Id_cliente' =>$this->id_cliente,
            'Accion_automatica' => $Accion_ejecutar_automatica,
            'Id_Estado_evento_automatico' => $Id_Estado_evento_automatico,
            'F_accion' => $this->timestap,
            'Id_profesional_automatico' => $Profesional_asignado_automatico,
            'Nombre_profesional_automatico' => $NombreProfesional_asignado_automatico,
            'F_movimiento_automatico' => $F_movimiento_automatico,
            'Estado_accion_automatica' => 'Pendiente',
            'Nombre_usuario' => Auth::user()->name,
            'F_registro' => date("Y-m-d", time()),

        ];

        sigmel_informacion_acciones_automaticas_eventos::on('sigmel_gestiones')->insert($array_info_datos_accion_automatica);

        $mensaje = "la acción parametrizada tiene una Acción automática y se ejecutará en {$this->Tiempo_movimiento} día(s)";

        return [
            "estado" => "ok",
            "mensaje" => $this->getMensaje('mv_0', $mensaje)
        ];
    }

    /**
     * Devuelve el mensaje correspondiente para el estado del movimiento
     */
    protected function getMensaje($index,$agregar = ""){
        if($agregar != ""){
            $this->mensajes[$index] = $agregar;
        }
        return $this->mensajes[$index] ?? $this->mensajes['default'];
    }

    protected function getFechaMovimiento($Tiempo_movimiento){
        // Se suman los dias a la fecha actual para saber la fecha del movimiento automatico
        $dateTime = new DateTime((string) $this->timestap);
        $dias = $Tiempo_movimiento; // Número de días que quieres sumar
        $dateTime->modify("+$dias days");
        
        return $dateTime->format('Y-m-d');     
    }
}

?>