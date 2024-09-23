<?php 
namespace App\Services;

use App\Contracts\Acciones;

class AccionesAutomaticas{

    /**
     * @var Array Acciones disponibles para ejecutar
     */
    protected $acciones = [];

    /**
     * @var Object Instancia de la accion que se esta ejecutando
     */
    protected $accion_ejecutada;

    /**
     * @var Array datos de la accion a invocar
     */
    protected $data = [];

    /**
     * @var Mixed Respuesta de la accion
     */
    public $response = [];

    /**
     * Registra las acciones indicadas para poder ser invocadas
     */
    public function registrarAccion(Array $acciones){
        foreach($acciones as $accion => $class){
            $this->acciones[$accion] = $class;
        }        
        return $this;
    }

    /**
     * Establece los datos que la accion estara utilizando
     */
    public function with($data){
        $this->data = $data;
        return $this;
    }

    /**
     * Invoca la accion y/o acciones indicadas con los datos cargados $data
     * @param string $accion Nombre de la accion a invocar, si no se envia nada se ejecutaran las acciones que esten
     * registradas en $acciones
     */
    public function llamarAcciones(string $accion = null){

        if($accion != ""){
            if(!array_key_exists($accion,$this->acciones)){
                throw new \Exception("La accion $accion no se encuentra registrada");
            }
            $this->accion_ejecutada = app($this->acciones[$accion]);

            //Ejecuta la accion cono los parametros dados
            $status = $this->accion_ejecutada->init(...$this->data);
            array_push($this->response,$status);
        }else{
            foreach ($this->acciones as $accion => $class) {
                $this->accion_ejecutada = app($class);
        
                if(!$this->accion_ejecutada instanceof Acciones){
                    throw new \Exception("El servicio $accion no ha implementado la interfaz de acciones");
                }

                //Ejecuta la accion cono los parametros dados
                $status = $this->accion_ejecutada->init(...$this->data);

                array_push($this->response,$status);
            }
        }

    }
}

?>