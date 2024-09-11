<?php
namespace App\Services;

use App\Contracts\BaseServicio;

class ServiceBus
{
    protected $servicios = [];
    
    public $format;

    public $agregarcomandos;

    private $servicio;

    public function registrarServicio(string $NombreServicio, $interfaz){
        $this->servicios[$NombreServicio] = $interfaz;

        $this->servicio = app($interfaz);

        if ($this->servicio instanceof BaseServicio) {
           $this->agregarcomandos[$NombreServicio] = $this->servicio->getParams();
        }

    }

    public function despachar(string $NombreServicio,$param = []){

        if(!array_key_exists($NombreServicio,$this->servicios)){
            throw new \Exception("El serviciio $NombreServicio no se encuentra registrado");
        }

        $this->servicio = app($this->servicios[$NombreServicio]);

        if(!$this->servicio instanceof BaseServicio){
            throw new \Exception("El servicio $NombreServicio no ha implementado la interfaz de servicios");
        }

        return $this->servicio->ejecutar($param);
    }

    public function agregarParametros(string $nombreServicio){
        return $this->agregarcomandos[$nombreServicio] ?? [];
    }

    public function format(){
        $this->format = $this->servicio->getFormat();
    }
}

?>