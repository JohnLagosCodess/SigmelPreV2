<?php 
namespace App\Contracts;

use App\Services\FormulaAlertas;

abstract class Acciones extends FormulaAlertas{
    /**
     * Metodo principal para que las acciones puedan ser invocadas
     */
    abstract public function init($fechaAccion,$AccionEvento,$idCliente,$Id_proceso,$Id_servicio,$Id_evento,$id_asignacion);
}
?>