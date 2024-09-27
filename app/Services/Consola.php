<?php 
namespace App\Services;
use App\Contracts\BaseServicio;

class Consola extends ServiceBus{

    /**
     * @var string Formato que tendran las respuestas del servicio string|table
     */
    public $format;

    /**
     * @var Array Comandos disponibles para el servicio invocado 
     */
    public $agregarcomandos;

    /**
     * Registra el servicio para poder ser invocado
     * @param string Nombre del servicio
     * @param Class Referencia a la clase del servicio
     */
    public function registrarServicioConsola(string $NombreServicio, $interfaz){
        $this->servicios[$NombreServicio] = $interfaz;

        //Instancia el servicio
        $this->servicio = app($interfaz);

        //Agrega los comandos disponibles para el servicio invocado
        if ($this->servicio instanceof BaseServicio) {
           $this->agregarcomandos[$NombreServicio] = $this->servicio->getParams();
        }
    }

    /**
     * Obtiene los parametros disponibles para el servicio indicado
     * @param string Nombre del servicio
     */
    public function agregarParametros(string $nombreServicio){
        return $this->agregarcomandos[$nombreServicio] ?? [];
    }

    /**
     * Formato de salida que tendra las respuestas dadas desde el servicio
     */
    public function format(){
        $this->format = $this->servicio->getFormat();
    }

    /**
     * Invoca al servicio digitado por el usuario
     * @param string Nombre del servicio
     * @param Array Parametros del servicio
     */
    public function despacharConsola(string $NombreServicio,$param = []){

        if(!array_key_exists($NombreServicio,$this->servicios)){
            throw new \Exception("El serviciio $NombreServicio no se encuentra registrado");
        }

        //Instancia el servicio
        $this->servicio = app($this->servicios[$NombreServicio]);

        if(!$this->servicio instanceof BaseServicio){
            throw new \Exception("El servicio $NombreServicio no ha implementado la interfaz de servicios");
        }

        //Ejecuta el servicio
        return $this->servicio->ejecutar($param);
    }
}
?>