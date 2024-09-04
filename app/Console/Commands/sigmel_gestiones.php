<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ServiceBus;
use App\Services\EliminarEventos;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

class sigmel_gestiones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sigmel_gestiones {nombre_servicio} {--*}';

    protected $comandos = [];

    protected $format = "string";

    protected $servicebus;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatizar gestiones comunes y/o repetitivas dentro del proceeso de sigmel';

    protected $registarServicios = [
        "EliminarEvento" => \App\Services\EliminarEventos::class
    ];

    public function __construct(ServiceBus $ServiceBus)
    {
        parent::__construct();

        $this->servicebus = $ServiceBus;

        foreach ($this->registarServicios as $nombre => $servicio) {
            $this->servicebus->registrarServicio($nombre, $servicio);

            $opciones = $this->servicebus->agregarParametros($nombre);
            foreach ($opciones as $atributo => $comando) {
                foreach ($comando as $nombre => $descripcion) {
                    $this->addOption($nombre, null, InputOption::VALUE_OPTIONAL, $descripcion);
                }
            }
        }
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $nombre_servicio = $this->argument('nombre_servicio');

        // Obtener los parámetros requeridos desde $this->comandos
        $parametros = $this->servicebus->agregarParametros($nombre_servicio);

        //Formato que tendra la respuesta del servicio al ser ejecutado
        $this->format = $this->servicebus->format;

        $opciones = [];

        // Verificar si todos los parámetros requeridos han sido proporcionados
        foreach ($parametros as $parametro => $param) {
            if ($parametro == "required") {

                $opciones['required'] = $this->getParam($param);
                $values = array_values($opciones['required']);
                if (in_array(null, $values, true) || in_array("", $values, true)) {
                    $this->error("No se han proporcionado todos los parametros requeridos");
                    return 1;
                }
            } else {
                $opciones["opcional"] = $this->getParam($param);
            }
        }

        try {
            $resultado = $this->servicebus->despachar($nombre_servicio, $opciones);

            if($this->format == "string"){
                $this->info((string) $resultado);
            }else{
                $this->dibujarTabla($resultado);
            }

        } catch (\Exception $e) {
            $this->error("Ha ocurrido un error durante la ejecucion del servicio $nombre_servicio :" . $e->getMessage());
        }
    }

    public function getParam(array $parametros)
    {

        $opciones = [];
        foreach ($parametros as $param => $descripcion) {
            $nombreParametro = ltrim($param, '-');
            $valor = $this->option($nombreParametro);

            $opciones[$nombreParametro] = $valor;
        }

        return $opciones;
    }

    protected function dibujarTabla(array $resultado)
    {
        // Crear una instancia de Table
        $table = new Table($this->output);
        $table->setHeaders(['Tabla', 'Status', 'Info']);

        // Formatear las filas de la tabla
        $rows = [];
        foreach ($resultado as $key => $value) {
            $status = $value['status'] ? 'true' : 'false';
            $info = $value['info'];
            $rows[] = [$key, $status, $info];
        }

        $table->setRows($rows);
        $table->render();
    }
}
