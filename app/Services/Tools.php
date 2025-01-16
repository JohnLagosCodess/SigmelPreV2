<?php
namespace App\Services;
use App\Services\Consola;
use App\Contracts\BaseServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\sigmel_lista_tipo_eventos;
use App\Models\sigmel_lista_entidades;
use App\Models\sigmel_informacion_entidades;
use App\Models\sigmel_lista_actividad_economicas;
use App\Models\sigmel_lista_parametros;
use App\Models\sigmel_lista_departamentos_municipios;
use App\Models\sigmel_lista_solicitantes;
use App\Models\sigmel_lista_clase_riesgos;
use App\Models\sigmel_lista_ciuo_codigos;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
class Tools
{

    private static $request;

    public $registro_original;

    public $nuevo_registro;

    public static function make(Request $request, $values = [],callable $callback = null){
        self::$request = $request;

        if(!empty($values)){
            self::procesar($values);
        }
        
        if(!is_null($callback)){
            call_user_func($callback);
        }

        return new self();
    }

    private static function procesar($input, $campo = "")
    {

        foreach ($input as $key => $item) {
            if (is_array($item)) {
                foreach ($item as $campo => $acciones) {
                    self::invocar_acciones($acciones, $campo);
                }
            } else {
                self::invocar_acciones($item, $key);
            }
        }

        die("fin");
    }
    
    /**
     * Invoca las acciones disponibles para cada campo que se este operando
     * @param Array Listado de acciones
     * @param String Campo que se esta evaluando
     */
    public function invocar_acciones(array $target = [],...$params)
    {
        $response = [];
        foreach ($target as $item => $accion) {
            $accion = explode(":", $accion);
            if (isset($accion[0]) && method_exists(__CLASS__, $accion[0])) {
                // Llamamos al método correspondiente pasando el segundo parámetro y el campo original
               $response[$accion[0]] = $this->{$accion[0]}($accion[1] ?? null, ...$params);
            }
        }

        return $response;
    }
    
    /**
     * Homologa los cambios realizados sobre el campo especificado
     * @param string $param Parametro suministrado para la accion
     * @param string $campo Campo sobre el cual se esta operando
     */
    private function bitacora(string $param,string $campo){
        $campo_modificado = str_replace("_"," ",$param);
        $resultado = [
            "accion" => "Actualizar información",
            "campo" => $campo_modificado,
            "detalle" => "{$campo_modificado}, de {$this->registro_original[$campo]} a {$this->nuevo_registro[$campo]}"
        ];
       
        return $resultado;
    }

    /**
     * Homologa el campo indicado por su valor dentro dentro de la BD
     * @param string $param Parametro suministrado para la accion
     * @param string $campo Campo sobre el cual se esta operando
     */
    private function info(string $param,string $campo){
        $info = [
            "afp" => function($selecion){
                $query = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Nombre_parametro')
                ->where('Id_Parametro',$selecion)->first();

                return $query->Nombre_parametro ?? null;
            },
            "t_evento" => function($selecion){
                $query = sigmel_lista_tipo_eventos::on('sigmel_gestiones')
                ->select('Nombre_evento')
                ->where('Id_Evento',$selecion)->first();

                return $query->Nombre_evento ?? null;
            },
            "t_afiliado" => function($selecion){
                $query = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Nombre_parametro')
                ->where('Id_Parametro',$selecion)->first();
                return $query->Nombre_parametro ?? null;
            },
            "t_documento" => function($selecion){
                $query = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Nombre_parametro')
                ->where('Id_Parametro',$selecion)->first();
                return $query->Nombre_parametro ?? null;
            },
            "t_genero" => function($selecion){
                $query = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Nombre_parametro')
                ->where('Id_Parametro',$selecion)->first();
                return $query->Nombre_parametro ?? null;
            },
            "estado_civil" => function($selecion){
                $query = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Nombre_parametro')
                ->where('Id_Parametro',$selecion)->first();
                return $query->Nombre_parametro ?? null;
            },
            "nivel_escolar" => function($selecion){
                $query = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Nombre_parametro')
                ->where('Id_Parametro',$selecion)->first();
                return $query->Nombre_parametro ?? null;
            },
            "t_domininacia" => function($selecion){
                $query = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Nombre_parametro')
                ->where('Id_Parametro',$selecion)->first();
                return $query->Nombre_parametro ?? null;
            },
            "departamento" => function($selecion){
                $query = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
                ->select('Nombre_departamento')
                ->where('Id_departamento',$selecion)->first();
                return $query->Nombre_departamento ?? null;
            },
            "ciudad" => function($selecion){
                $query = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
                ->select('Nombre_municipio')
                ->where('Id_municipios',$selecion)->first();
                return $query->Nombre_municipio ?? null;
            },
            "arl" => function($selecion){
                $query = sigmel_informacion_entidades::on('sigmel_gestiones')
                ->select('Nombre_entidad')
                ->where('Id_Entidad',$selecion)->first();
                return $query->Nombre_entidad ?? null;
            },
            "eps" => function($selecion){
                $query = sigmel_informacion_entidades::on('sigmel_gestiones')
                ->select('Nombre_entidad')
                ->where('Id_Entidad',$selecion)->first();
                return $query->Nombre_entidad ?? null;
            },
            "afp" => function($selecion){
                $query = sigmel_informacion_entidades::on('sigmel_gestiones')
                ->select('Nombre_entidad')
                ->where('Id_Entidad',$selecion)->first();
                return $query->Nombre_entidad ?? null;
            },
            "m_notificacion" => function($selecion){
                $query = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Nombre_parametro')
                ->where('Id_Parametro',$selecion)->first();
                return $query->Nombre_parametro ?? null;
            },
            "actividad_economica" => function($selecion){
                $query = sigmel_lista_actividad_economicas::on('sigmel_gestiones')
                ->select('Nombre_actividad')
                ->where('Id_ActEco',$selecion)->first();
                return $query->Nombre_actividad ?? null;
            },
            "t_riesgo" => function($selecion){
                $query = sigmel_lista_clase_riesgos::on('sigmel_gestiones')
                ->select('Nombre_riesgo')
                ->where('Id_Riesgo',$selecion)->first();
                return $query->Nombre_riesgo ?? null;
            },
            "ciuo" => function($selecion){
                $query = sigmel_lista_ciuo_codigos::on('sigmel_gestiones')
                ->select('Nombre_ciuo')
                ->where('Id_Codigo',$selecion)->first();
                return $query->Nombre_ciuo ?? null;
            },
            "f_informacion" => function($selecion){
                $query = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Nombre_parametro')
                ->where('Id_Parametro',$selecion)->first();
                return $query->Nombre_parametro ?? null;
            },
            "n_solicitante" => function($selecion){
                $query = sigmel_informacion_entidades::on('sigmel_gestiones')
                ->select('Nombre_entidad')
                ->where('Id_Entidad',$selecion)->first();
                return $query->Nombre_entidad ?? $selecion;
            },
            "solicitante" => function($selecion){
                $query = sigmel_lista_solicitantes::on('sigmel_gestiones')
                ->select('Solicitante')
                ->where('Id_Nombre_solicitante',$selecion)->first();
                return $query->Solicitante ?? null;
            },
            "r_salud" => function($selecion){
                $query = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Nombre_parametro')
                ->where('Id_Parametro',$selecion)->first();
                return $query->Nombre_parametro ?? null;
            },
            "t_vinculacion" => function($selecion){
                $query = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Nombre_parametro')
                ->where('Id_Parametro',$selecion)->first();
                return $query->Nombre_parametro ?? null;
            },

        ];

        //dd($param,$this->nuevo_registro,$this->registro_original,$campo);
        if(isset($info[$param])){
            $this->registro_original[$campo] = $info[$param]($this->registro_original[$campo]);
            $this->nuevo_registro[$campo]= $info[$param]($this->nuevo_registro[$campo]);   
        }

        Log::channel('tools')->info("homologando informacion - bitacora",[
            "validacion" => isset($info[$param]),
            "campo" => $campo ,
            "nuevo" => $this->nuevo_registro[$campo], 
            "original" => $this->registro_original[$campo],
        ]);

        return "datos homologados";
    }

    /**
     * Carga el documento para el proceso de documento historial
     * @param string $id_evento Evento procesado
     * @param int $proceso Proceso asociado
     * @param int $idInsertado Id la accion realiazada
     * @return string
     */
    public  function CargueDocumentos(string $id_evento,int $proceso,int $idInsertado){
        //Nombre para los documentos cargados basados en el proceso que se esta ejecutando
        $get_tipo_doc = function($proceso){
            return match ($proceso) {
                1 => "Documento Historial Origen",
                2 => "Documento Historial PCL",
                3 => "Documento Historial Juntas",
                default => "Documento Historial"
            };
        };

        $tipo_archivo = $get_tipo_doc($proceso);

        if(self::$request->hasFile('cargue_documentos')){
            $archivo = self::$request->file('cargue_documentos');
            $path = public_path('Documentos_Eventos/'.$id_evento);
            $mode = 0777;
            $nombre_documento = str_replace(' ', '_', $tipo_archivo);

            if (!File::exists($path)) {
                File::makeDirectory($path, $mode, true, true);
                chmod($path, $mode);
            }

            $nombre_final_documento = "{$nombre_documento}{$idInsertado}_IdEvento_{$id_evento}.".$archivo->extension();
            Storage::putFileAs($id_evento, $archivo, $nombre_final_documento);

            Log::channel('tools')->info("Cargando historial documento",[
                "id_evento" => $id_evento,
                "idInsertado" => $idInsertado,
                "tipo_archivo" => $tipo_archivo,
                "Nombre_documento" => $nombre_final_documento
            ]);
        }else{
            $nombre_final_documento='N/A';            
        } 

        return $nombre_final_documento;
    }

}

?>