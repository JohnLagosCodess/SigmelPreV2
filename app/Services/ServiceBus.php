<?php
namespace App\Services;

use App\Contracts\InterfazServicio;

class ServiceBus
{
    protected $servicios = [];

    public function despachar(string $NombreServicio){

        if(!array_key_exists($NombreServicio,$this->servicios)){
            throw new \Exception("El serviciio $NombreServicio no se encuentra registrado");
        }

        $servicio = app($this->servicios[$NombreServicio]);

        if(!$servicio instanceof InterfazServicio){
            throw new \Exception("El servicio $NombreServicio no ha implementado la interfaz de servicios");
        }

        return $servicio->ejecutar();
    }

    public function registrarServicio(string $NombreServicio, $interfaz){
        $this->servicios[$NombreServicio] = $interfaz;
    }
}

?>