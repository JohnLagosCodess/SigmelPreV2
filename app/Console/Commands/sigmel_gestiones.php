<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ServiceBus;
use App\Services\EliminarEventos;

class sigmel_gestiones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sigmel_gestiones {nombre_servicio}';

    protected $servicebus;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatizar gestiones comunes y/o repetitivas dentro del proceeso de sigmel';

    public function __construct(ServiceBus $ServiceBus)
    {
        parent::__construct();

        $this->servicebus = $ServiceBus;

        $registarServicios = [
            "EliminarEvento" => \App\Services\EliminarEventos::class
        ];

        foreach ($registarServicios as $nombre => $servicio) {
            $this->servicebus->registrarServicio($nombre, $servicio);
        }
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $nombre_servicio = $this->argument('nombre_servicio');

        try{
            $resultado = $this->servicebus->despachar($nombre_servicio);
            $this->info("Resultado: " . $resultado);
        } catch(\Exception $e){
            $this->error("Ha ocurrido un error durante la ejecucion del servicio $nombre_servicio :" . $e->getMessage());
        }
    }
}
