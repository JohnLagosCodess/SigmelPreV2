<?php 
namespace App\Services;
use Illuminate\Support\Facades\DB;
use DateTime;

use App\Models\sigmel_informacion_accion_eventos;
use App\Models\sigmel_informacion_alertas_automaticas_eventos;
use App\Models\sigmel_informacion_asignacion_eventos;



abstract class FormulaAlertas{

    const FA = "FC+TA";
    const AN = "(TA*PN)/100";
    const AR = "(TA*PR)/100";

    protected $Tiempo_alerta,$F_accionEvento,$newIdAsignacion,$Porcentaje_alerta_naranja,
    $newIdEvento,$Id_proceso,$Id_servicio,$id_cliente,$AccionEvento,$date_time,$nombre_usuario,$date,$Porcentaje_alerta_roja;

    /**
     * Validar si hay tiempo de alerta para crear la nueva fecha de alerta segun la fecha de accion
     // formula FA= FC+TA (FA = fecha alerta, FC = fecha accion y TA = tiempo de alerta)
     */
    public function FA(){
        $Nueva_F_Alerta = new DateTime($this->F_accionEvento);
        $horas = $this->Tiempo_alerta;
        $minutosAdicionales = ($horas - floor($horas)) * 60;
        $horas = floor($horas);
        $Nueva_F_Alerta->modify("+$horas hours");
        $Nueva_F_Alerta->modify("+$minutosAdicionales minutes");
        $Nueva_F_AlertaEvento = $Nueva_F_Alerta->format('Y-m-d H:i:s');
                
        sigmel_informacion_accion_eventos::on('sigmel_gestiones')
        ->where([['Id_Asignacion', $this->newIdAsignacion]])
        ->update(['F_alerta' => $Nueva_F_AlertaEvento]);

        sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
        ->where([['Id_Asignacion', $this->newIdAsignacion]])
        ->update(['F_alerta' => $Nueva_F_AlertaEvento]);
    }

    /**
     * Validar si hay tiempo de alerta y porcentaje de alerta naraja para crear la alerta naranja
    // formula FA= FC+TA (FA = fecha alerta, FC = fecha accion y TA = tiempo de alerta)
    // formula AN = (TA*PN)/100 (AN= Alerta naranja, TA = tiempo de alerta y PN = porcentaje de alerta naranja)
     */
    public function FA_AN(){
        $Nueva_F_Alerta = new DateTime($this->F_accionEvento);
        $horas = $this->Tiempo_alerta;
        $minutosAdicionales = ($horas - floor($horas)) * 60;
        $horas = floor($horas);
        $Nueva_F_Alerta->modify("+$horas hours");
        $Nueva_F_Alerta->modify("+$minutosAdicionales minutes");
        $Nueva_F_AlertaEvento = $Nueva_F_Alerta->format('Y-m-d H:i:s');
        
        $infoNueva_F_AlertaEvento_accion = [
            'F_Alerta' => $Nueva_F_AlertaEvento
        ];

        $infoNueva_F_AlertaEvento_asignacion = [
            'F_alerta' => $Nueva_F_AlertaEvento
        ];
        
        sigmel_informacion_accion_eventos::on('sigmel_gestiones')
        ->where([['Id_Asignacion', $this->newIdAsignacion]])
        ->update($infoNueva_F_AlertaEvento_accion);

        sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
        ->where([['Id_Asignacion', $this->newIdAsignacion]])
        ->update($infoNueva_F_AlertaEvento_asignacion);

        $Alerta_Naranja = ($this->Tiempo_alerta * $this->Porcentaje_alerta_naranja) / 100;

        $Nueva_F_Alerta_Naranja = new DateTime($this->F_accionEvento);
        $horas = $Alerta_Naranja;
        $minutosAdicionales_naranja = ($horas - floor($horas)) * 60;
        $horas = floor($horas);
        $Nueva_F_Alerta_Naranja->modify("+$horas hours");
        $minutosAdicionales_naranja_entero = round($minutosAdicionales_naranja);
        $Nueva_F_Alerta_Naranja->modify("+$minutosAdicionales_naranja_entero minutes");
        $Nueva_F_Alerta_NaranjaEvento = $Nueva_F_Alerta_Naranja->format('Y-m-d H:i:s');

        $array_info_datos_alertas_automatica = [
            'Id_Asignacion' => $this->newIdAsignacion,
            'ID_evento' => $this->newIdEvento,
            'Id_proceso' => $this->Id_proceso,
            'Id_servicio' => $this->Id_servicio,
            'Id_cliente' => $this->id_cliente,
            'Accion_ejecutar' => $this->AccionEvento,
            'F_accion' => $this->date_time,
            'Tiempo_alerta' => $this->Tiempo_alerta,
            'Porcentaje_alerta_naranja' => $this->Porcentaje_alerta_naranja,
            'F_accion_alerta_naranja' => $Nueva_F_Alerta_NaranjaEvento,                            
            'Estado_alerta_automatica' => 'Ejecucion',
            'Nombre_usuario' => $this->nombre_usuario,
            'F_registro' => $this->date,
        ];

        sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')->insert($array_info_datos_alertas_automatica);
    }

    /**
     * Validar si hay tiempo de alerta y porcentaje de alerta roja para crear la alerta roja
     // formula FA= FC+TA (FA = fecha alerta, FC = fecha accion y TA = tiempo de alerta)
     // formula AR = (TA*PR)/100 (AR= Alerta roja, TA = tiempo de alerta y PR = porcentaje de alerta roja)
     */
    public function FA_AR(){
        $Nueva_F_Alerta = new DateTime($this->F_accionEvento);
        $horas = $this->Tiempo_alerta;
        $minutosAdicionales = ($horas - floor($horas)) * 60;
        $horas = floor($horas);
        $Nueva_F_Alerta->modify("+$horas hours");
        $Nueva_F_Alerta->modify("+$minutosAdicionales minutes");
        $Nueva_F_AlertaEvento = $Nueva_F_Alerta->format('Y-m-d H:i:s');
        
        $infoNueva_F_AlertaEvento_accion = [
            'F_Alerta' => $Nueva_F_AlertaEvento
        ];

        $infoNueva_F_AlertaEvento_asignacion = [
            'F_alerta' => $Nueva_F_AlertaEvento
        ];
        
        sigmel_informacion_accion_eventos::on('sigmel_gestiones')
        ->where([['Id_Asignacion', $this->newIdAsignacion]])
        ->update($infoNueva_F_AlertaEvento_accion);

        sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
        ->where([['Id_Asignacion', $this->newIdAsignacion]])
        ->update($infoNueva_F_AlertaEvento_asignacion);

        $Alerta_Roja = ($this->Tiempo_alerta * $this->Porcentaje_alerta_roja) / 100;

        $Nueva_F_Alerta_Roja = new DateTime($this->F_accionEvento);
        $horas_roja = $Alerta_Roja;
        $minutosAdicionales_roja = ($horas_roja - floor($horas_roja)) * 60;
        $horas_roja = floor($horas_roja);
        $Nueva_F_Alerta_Roja->modify("+$horas_roja hours");
        $minutosAdicionales_roja_entero = round($minutosAdicionales_roja);
        $Nueva_F_Alerta_Roja->modify("+$minutosAdicionales_roja_entero minutes");
        $Nueva_F_Alerta_RojaEvento = $Nueva_F_Alerta_Roja->format('Y-m-d H:i:s');

        $array_info_datos_alertas_automatica = [
            'Id_Asignacion' => $this->newIdAsignacion,
            'ID_evento' => $this->newIdEvento,
            'Id_proceso' => $this->Id_proceso,
            'Id_servicio' => $this->Id_servicio,
            'Id_cliente' =>$this->id_cliente,
            'Accion_ejecutar' => $this->AccionEvento,
            'F_accion' => $this->date_time,
            'Tiempo_alerta' => $this->Tiempo_alerta,                            
            'Porcentaje_alerta_roja' => $this->Porcentaje_alerta_roja,
            'F_accion_alerta_roja' => $Nueva_F_Alerta_RojaEvento,
            'Estado_alerta_automatica' => 'Ejecucion',
            'Nombre_usuario' => $this->nombre_usuario,
            'F_registro' => $this->date,
        ];

        sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')->insert($array_info_datos_alertas_automatica);   
    }

    /**
     * Validar si hay tiempo de alerta, porcentaje de alerta naraja y porcentaje de alerta roja para crear todas las alertas
    // formula FA= FC+TA (FA = fecha alerta, FC = fecha accion y TA = tiempo de alerta)
    // formula AN = (TA*PN)/100 (AN= Alerta naranja, TA = tiempo de alerta y PN = porcentaje de alerta naranja)
    // formula AR = (TA*PR)/100 (AR= Alerta roja, TA = tiempo de alerta y PR = porcentaje de alerta roja)
     */
    public function FA_AR_AN(){
        $Nueva_F_Alerta = new DateTime($this->F_accionEvento);
        $horas = $this->Tiempo_alerta;
        $minutosAdicionales = ($horas - floor($horas)) * 60;
        $horas = floor($horas);
        $Nueva_F_Alerta->modify("+$horas hours");
        $Nueva_F_Alerta->modify("+$minutosAdicionales minutes");
        $Nueva_F_AlertaEvento = $Nueva_F_Alerta->format('Y-m-d H:i:s');
        
        $infoNueva_F_AlertaEvento_accion = [
            'F_Alerta' => $Nueva_F_AlertaEvento
        ];

        $infoNueva_F_AlertaEvento_asignacion = [
            'F_alerta' => $Nueva_F_AlertaEvento
        ];
        
        sigmel_informacion_accion_eventos::on('sigmel_gestiones')
        ->where([['Id_Asignacion', $this->newIdAsignacion]])
        ->update($infoNueva_F_AlertaEvento_accion);

        sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
        ->where([['Id_Asignacion', $this->newIdAsignacion]])
        ->update($infoNueva_F_AlertaEvento_asignacion);

        $Alerta_Naranja = ($this->Tiempo_alerta * $this->Porcentaje_alerta_naranja) / 100;

        $Nueva_F_Alerta_Naranja = new DateTime($this->F_accionEvento);
        $horas_naranja = $Alerta_Naranja;
        $minutosAdicionales_naranja = ($horas_naranja - floor($horas_naranja)) * 60;
        $horas_naranja = floor($horas_naranja);
        $Nueva_F_Alerta_Naranja->modify("+$horas_naranja hours");
        $minutosAdicionales_naranja_entero = round($minutosAdicionales_naranja);
        $Nueva_F_Alerta_Naranja->modify("+$minutosAdicionales_naranja_entero minutes");
        $Nueva_F_Alerta_NaranjaEvento = $Nueva_F_Alerta_Naranja->format('Y-m-d H:i:s');

        $Alerta_Roja = ($this->Tiempo_alerta * $this->Porcentaje_alerta_roja) / 100;

        $Nueva_F_Alerta_Roja = new DateTime($this->F_accionEvento);
        $horas_roja = $Alerta_Roja;
        $minutosAdicionales_roja = ($horas_roja - floor($horas_roja)) * 60;
        $horas_roja = floor($horas_roja);
        $Nueva_F_Alerta_Roja->modify("+$horas_roja hours");
        $minutosAdicionales_roja_entero = round($minutosAdicionales_roja);
        $Nueva_F_Alerta_Roja->modify("+$minutosAdicionales_roja_entero minutes");
        $Nueva_F_Alerta_RojaEvento = $Nueva_F_Alerta_Roja->format('Y-m-d H:i:s');

        $array_info_datos_alertas_automatica = [
            'Id_Asignacion' => $this->newIdAsignacion,
            'ID_evento' => $this->newIdEvento,
            'Id_proceso' => $this->Id_proceso,
            'Id_servicio' => $this->Id_servicio,
            'Id_cliente' =>$this->id_cliente,
            'Accion_ejecutar' => $this->AccionEvento,
            'F_accion' => $this->date_time,
            'Tiempo_alerta' => $this->Tiempo_alerta,
            'Porcentaje_alerta_naranja' => $this->Porcentaje_alerta_naranja,
            'F_accion_alerta_naranja' => $Nueva_F_Alerta_NaranjaEvento,
            'Porcentaje_alerta_roja' => $this->Porcentaje_alerta_roja,
            'F_accion_alerta_roja' => $Nueva_F_Alerta_RojaEvento,
            'Estado_alerta_automatica' => 'Ejecucion',
            'Nombre_usuario' => $this->nombre_usuario,
            'F_registro' => $this->date,
        ];

        sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')->insert($array_info_datos_alertas_automatica);
    }
}