<?php 
namespace App\Services;

use App\Contracts\Bandejas;
use App\Models\sigmel_informacion_alertas_automaticas_eventos;
use App\Models\cndatos_bandeja_eventos;
use Illuminate\Support\Facades\DB;

class BaseBandeja extends Bandejas{

    private $roles_notificacion = [5,10,3];
    private $roles_pcl = [5,10,3];
    private $roles_juntas = [5,10,3];
    private $roles_origen = [5,10,3];

    private  $proceso;

    private $datosBandeja;

    private $Enviar_Notificaciones;

    private $id_rol;

    private $id_usuario;

    public function getRoles($proceso){
        $rolesMap = [
            'notificaciones' => $this->roles_notificacion,
            'pcl' => $this->roles_pcl,
            'juntas' => $this->roles_juntas,
            'origen' => $this->roles_origen,
        ];

        return $rolesMap[$proceso] ?? null; 
    }

    public function configurar(int $id_rol,int $id_usuario,string $proceso){
        $this->proceso = $proceso;
        $this->Enviar_Notificaciones = $proceso == 'notificaciones' ? 'Si' : 'No';
        $this->id_rol = $id_rol;
        $this->id_usuario = $id_usuario;
    }

    public function sinFiltroBandeja(string $proceso){
        $roles_disponibles = $this->getRoles($this->proceso);

        if(in_array($this->id_rol,$roles_disponibles)){
            $this->datosBandeja = cndatos_bandeja_eventos::on('sigmel_gestiones')
            ->where('Id_profesional', '=', $this->id_usuario)->where(function($query){
                $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', $this->Enviar_Notificaciones);
            });

        }else{
            $this->datosBandeja = cndatos_bandeja_eventos::on('sigmel_gestiones')
            ->where('Enviar_bd_Notificacion', '=', $this->Enviar_Notificaciones)
            ->get();
        }

        return $this;
    }

    public function determinarFiltro(){

    }

    public function filtrosBandeja($consultar_f_desde,$consultar_f_hasta,$consultar_g_dias,$proceso){
        $roles_disponibles = $this->getRoles($this->proceso);

        if(in_array($this->id_rol,$roles_disponibles)){
            $this->datosBandeja = cndatos_bandeja_eventos::on('sigmel_gestiones')
            ->where([
                ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias],
                ['Id_profesional', '=', $this->id_usuario]
            ])->where(function($query){
                $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', $this->Enviar_Notificaciones);
            })
            ->whereBetween(DB::raw('DATE(F_accion)'), [$consultar_f_desde ,$consultar_f_hasta])
            ->get();
        }else{
            $this->datosBandeja = cndatos_bandeja_eventos::on('sigmel_gestiones')
            ->where('Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias)->where(function($query){
                $query->where('Enviar_bd_Notificacion', '=', $this->Enviar_Notificaciones);
            })
            ->whereBetween(DB::raw('DATE(F_accion)'), [$consultar_f_desde ,$consultar_f_hasta])
            ->get();
        }

        return $this;

    }

    public function getDatosBandeja(){
        $mensajes = array(
            "parametro" => 'sin_datos',
            "mensajes" => 'No se encontraron registros acorde a la búsqueda realizada.',
            "registros" => 0
        );
        
        return $this->datosBandeja ?? $mensajes;
    }


    public function getAlertasNaranjas()
    {
        $alertas = sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')
        ->where([['Estado_alerta_automatica', '=', 'Ejecucion']])
        ->get();
        return response()->json(['data' => $alertas]);
    }
}
?>