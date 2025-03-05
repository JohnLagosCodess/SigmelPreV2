<?php
namespace App\Services;
use App\Services\Consola;
use App\Contracts\BaseServicio;

class ServiceBus
{
    /**
     * @var Array Servicios disponibles a ser invocados
     */
    protected $servicios = [];

    /**
     * @var Object Instancia del servicio que se esta ejecutando
     */
    protected $servicio;

    /**
     * Registra el servicio para poder ser invocado.
     *
     * Permite registrar una o más clases de servicio, 
     * donde cada clase se asocia a una referencia que puede ser utilizada
     * posteriormente para invocarlas.
     * @param array $class Contiene la clase del servicio en el formato { 'nombre' => 'referencia' }.
     * @return this
     */
    public function registrarServicio(Array $class){
        foreach($class as $referencia => $obj){
            $this->servicios[$referencia] = $obj;
        }

        return $this;
    }

    /**
     * Invoca al servicio digitado por el usuario
     * @param string Nombre del servicio
     * @param string Metodo a ser invocado para el servicio
     * @param Array Parametros del servicio
     */
    public function llamar($servicio){
        if (!isset($this->servicios[$servicio])) {
            throw new \Exception("El servico $servicio no se encuentra registrado");
        }

        return app($this->servicios[$servicio]);

    }

}

?>