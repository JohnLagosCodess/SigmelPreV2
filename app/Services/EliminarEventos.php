<?php 
namespace App\Services;

use App\Contracts\BaseServicio;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class EliminarEventos extends BaseServicio{

    /**
     * Comandos disponibles para invocarse en  la consola
     * @var Array
     */
    protected $comandos = [
        'required' => [
            "--n_evento" => "# del evento",
           // "--n_eventossss" => "# del evento"
        ],
        'opcional' => [
            "--excepcion" => "Omitir la tabla indica para que no elimine el registro"
        ]
    ];

    /**
     * Formato de salida en el que se mostrara el resultado
     * @var string
     */
    protected $format = "table";

    /**
     * Tablas en las cuales se eliminaran los registros
     * @var Array
     */
    protected $tablas = [
        'sigmel_gestiones' => array(
            "sigmel_informacion_eventos",
            "sigmel_informacion_asignacion_eventos",
            "sigmel_informacion_decreto_eventos",
            "sigmel_informacion_examenes_interconsultas_eventos",
            "sigmel_informacion_diagnosticos_eventos",
            "sigmel_informacion_agudeza_auditiva_eventos",
            "sigmel_informacion_deficiencias_alteraciones_eventos",
            "sigmel_informacion_laboralmente_activo_eventos",
            "sigmel_informacion_rol_ocupacional_eventos",
            "sigmel_informacion_libro2_libro3_eventos",
            "sigmel_registro_documentos_eventos",
            "sigmel_informacion_comite_interdisciplinario_eventos",
            "sigmel_informacion_comunicado_eventos",
            "sigmel_informacion_correspondencia_eventos",
            "sigmel_informacion_dto_atel_eventos",
            "sigmel_historial_acciones_eventos",
            "sigmel_informacion_historial_accion_eventos",
            "sigmel_informacion_adiciones_dx_eventos",
            "sigmel_informacion_pronunciamiento_eventos",
            "sigmel_informacion_controversia_juntas_eventos",
            "sigmel_registro_descarga_documentos",
            "sigmel_informacion_acciones_automaticas_eventos",
            "sigmel_informacion_alertas_automaticas_eventos",
            "sigmel_informacion_seguimientos_eventos",
            "sigmel_informacion_afiliado_eventos",
            "sigmel_informacion_laboral_eventos",
            "sigmel_informacion_pericial_eventos",
        ),
        'sigmel_auditorias' => array(
            "sigmel_auditorias_informacion_comunicado_eventos",
            "sigmel_auditorias_asignacion_cambio_proceso",
            "sigmel_auditorias_informacion_accion_eventos",
            "sigmel_auditorias_informacion_adiciones_dx_eventos",
            "sigmel_auditorias_informacion_afiliado_eventos",
            "sigmel_auditorias_informacion_asignacion_eventos",
            "sigmel_auditorias_informacion_controversia_juntas_eventos",
            "sigmel_auditorias_informacion_documentos_solicitados_eventos",
            "sigmel_auditorias_informacion_dto_atel_eventos",
            "sigmel_auditorias_informacion_eventos",
            "sigmel_auditorias_informacion_laboral_eventos",
            "sigmel_auditorias_informacion_pericial_eventos",
            "sigmel_auditorias_pronunciamiento_eventos",
            "sigmel_auditorias_registro_documentos_eventos"
        )
    ];
    
    /**
     * Funcion principal que se invoca al momento de ser llamado desde la funcion
     * @param Array $param Parametros ejecutados por el usuario dentro de la consola.
     */
    public function ejecutar($param){
        
        $n_evento = $param['required']['n_evento'];
        $exepcion = $param['opcional']['excepcion'];

        //Eliminaos la tabla indica por el usuario para no tenerla en cuenta durante la ejecucion
        if($exepcion === ""){
            throw new \Exception("No se ha suministrado la tabla ah omitir.");
        }else{
            $sigmel_gestiones = $this->tablas['sigmel_gestiones'];
            $sigmel_auditorias = $this->tablas['sigmel_auditorias'];
            unset($sigmel_gestiones[array_search($exepcion,$sigmel_gestiones)]);
            unset($sigmel_auditorias[array_search($exepcion,$sigmel_auditorias)]);
        }

        

        return $this->eliminarEvento($n_evento);
    }

    /**
     * Ejecuta la eliminacion del evento en sigmel_gestiones y sigmel_auditorias
     * @param string $evento # del evento a eliminar dentro de las tablas
     */
    private function eliminarEvento(string $evento){
        $resultado = [];
        foreach($this->tablas as $db => $item){
            if($db === "sigmel_gestiones"){
                foreach($item as $tabla){
                    $ID_evento_columna = Schema::connection("sigmel_gestiones")->hasColumn($tabla,'ID_evento');
                    if($ID_evento_columna){
                        DB::table(getDatabaseName('sigmel_gestiones') . $tabla)->where('ID_evento',$evento)->delete();
                        $resultado[$tabla] = [
                            "status" => true,
                            'info' => "Se elimino el registro"
                        ];
                    }else{
                        $resultado[$tabla] = [
                            "status" => false,
                            'info' => "El registro no se elimino la llave ID_evento no esta en la tabla"
                        ];
                    }
                }

                $this->eliminarAgudezaVisual($evento);
            }elseif($db === 'sigmel_auditorias'){
                foreach($item as $tabla){
                    $llave = Schema::connection("sigmel_auditorias")->hasColumn($tabla,'Aud_ID_evento') ? 'Aud_ID_evento' :
                                (Schema::connection("sigmel_auditorias")->hasColumn($tabla,'ID_evento') ? 'ID_evento' : false);

                    if($llave){
                        DB::table(getDatabaseName('sigmel_auditorias') . $tabla)->where($llave,$evento)->delete();
                        $resultado[$tabla] = [
                            "status" => true,
                            'info' => "Se elimino el registro"
                        ];
                    }else{
                        $resultado[$tabla] = [
                            "status" => false,
                            'info' => "El registro no se elimino la llave $llave no esta en la tabla"
                        ];
                    }
                }
            }
        }

        return $resultado;
    }
    
    public function eliminarAgudezaVisual(string $n_evento){
        $tablas_agudeza = [
            'agudeza_visual' => [
                'sigmel_info_campimetria_ojo_der_eventos',
                'sigmel_info_campimetria_ojo_izq_eventos'
            ],
            'agudeza_visual_re' => [
                'sigmel_info_campimetria_ojo_derre_eventos',
                'sigmel_info_campimetria_ojo_izqre_eventos'
            ]
        ];

        foreach($tablas_agudeza as $item => $tabla_agudeza){
            if($item == 'agudeza_visual'){
                $id_agudeza = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_agudeza_visual_eventos')->select('Id_agudeza')->where('ID_evento',$n_evento)->first();
                if($id_agudeza != null){
                    foreach($tabla_agudeza as $capimetria){
                        DB::table(getDatabaseName('sigmel_auditorias') . $capimetria)->where('Id_agudeza',$id_agudeza)->delete();
                    }   
                }
            }elseif($item == 'agudeza_visual_re'){
                $id_agudeza = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_agudeza_visualre_eventos')->select('Id_agudeza_re')->where('ID_evento_re',$n_evento)->first();
                if($id_agudeza != null){
                    foreach($tabla_agudeza as $capimetria){
                        DB::table(getDatabaseName('sigmel_auditorias') . $capimetria)->where('Id_agudeza_re',$id_agudeza)->delete();
                    }
                }
            }
        }

    }
}
?>