<?php 
namespace App\Contracts;

use App\Contracts\InterfazServicio;

abstract class BaseServicio implements InterfazServicio{
    protected $comandos = [];

    /**
     * Corresponde al formato que tendra al respuesta del servicio al ser ejecutado (string o tabla)
     * @var string 
     */
    protected $format = "string";

    abstract public function ejecutar($param);

    public function getParams(){
        return $this->comandos;
    }

    public function getFormat(){
        return $this->format;
    }

}
?>