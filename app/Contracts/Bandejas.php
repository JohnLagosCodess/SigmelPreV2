<?php 
namespace App\Contracts;



abstract class Bandejas implements Paginacion{
    public function sinFiltroBandeja(string $proceso){
    }

    public function filtrosBandeja($consultar_f_desde,$consultar_f_hasta,$consultar_g_dias,$proceso){

    }

    public function ejecutar_accion(){

    }

    public function getAlertasNaranjas(){

    }
}

?>