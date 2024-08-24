<?php 
namespace App\Traits;

use Illuminate\Support\Facades\DB;

    trait GenerarRadicados{

        private $proceso;

        private $generarRadicado = true;

        private $radicadoTMP;

        private $id_evento;

        private $config = [
            'prefix' => 'TEMP_',
            'formato_fecha' => 'Ymd',
            'longitud' => 6,
        ];

        private $radicado = [];

        /**
        * Configuracion de los parámetros para la generación del radicado.
        *
        * @param array $config
        * @param callable|null $callback Sirve para aplicar alguna configuracion personalizada para el radicado, se debe enviar la instancia del trait
        */
        public function config($config = [], callable $callback = null){
            $this->config = array_merge($this->config,$config);

            $this->radicado['fecha'] = date($this->config['formato_fecha']);
            $this->radicado['prefix'] = $this->config['prefix']; 

            //Invoca a la funcion dada
            if ($callback) {
                $callback($this);
            }

            return $this;
        }


        /**
        * Obtiene un radicado basado en el proceso y el evento.
        *
        * @param string $proceso Proceso al que pertenece el radicado
        * @param int $id_evento
        * @param bool $conf_default Aplicar conf por defacto, o conf indicada
        * @return string
        * @throws InvalidArgumentException
        */
        public function getRadicado($proceso,$Id_evento,$conf_default = true){
            $this->proceso = $proceso;
            $this->id_evento = $Id_evento;

            //Si generarRadicado no es true devolvera el radicado radicadoTMP, principalmente para los casos en los que el radicado que se esta generando ya existe
            if(!$this->generarRadicado){
                return $this->radicadoTMP;
            }

            switch($proceso){
                case 'juntas':
                        $conf_default ? $this->config(['prefix' => 'SAL-JUN']) : '' ;

                        return $this->generarRadicadoJuntas($Id_evento);
                    break;
                case 'origen':
                        $conf_default ? $this->config(['prefix' => 'SAL-ORI']) : '';
                        return $this->generarRadicadoOrigen($Id_evento);
                    break;
                case 'pcl':
                        $conf_default ? $this->config(['prefix' => 'SAL-PCL']) : '';
                        return $this->generarRadicadoPCL($Id_evento);
                    break;
                default:
                    throw new \InvalidArgumentException("No se reconce el proceso para la generacion del radicado: $proceso");

            }
        }

        /**
        * Genera un radicado para el proceso PCL.
        *
        * @param int $id_evento
        * @return string
        */
        public function generarRadicadoPCL($Id_evento){
            $consecutivo = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comunicado_eventos')
            ->select('N_radicado')
            ->where([
                ['ID_evento',$Id_evento],
                ['F_comunicado', date('Ymd')],
                ['Id_proceso','2']
            ])->max('N_radicado');

            $consecutivo = $consecutivo ? substr($consecutivo, - $this->config['longitud']) : 0;
            $nuevoConsecutivo = sprintf("%0{$this->config['longitud']}d", $consecutivo + 1);

            $radicado = sprintf("%s%s%s", $this->radicado['prefix'], $this->radicado['fecha'], $nuevoConsecutivo);

            return $radicado;
        }

        /**
        * Genera un radicado para el proceso Origen.
        *
        * @param int $id_evento
        * @return string
        */
        public function generarRadicadoOrigen($Id_evento){
            $consecutivo = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comunicado_eventos')
            ->select('N_radicado')
            ->where([
                ['ID_evento',$Id_evento],
                ['F_comunicado', date('Ymd')],
                ['Id_proceso','1']
            ])->max('N_radicado');

            $consecutivo = $consecutivo ? substr($consecutivo, - $this->config['longitud']) : 0;
            $nuevoConsecutivo = sprintf("%0{$this->config['longitud']}d", $consecutivo + 1);

            $radicado = sprintf("%s%s%s", $this->radicado['prefix'], $this->radicado['fecha'], $nuevoConsecutivo);

            return $radicado;
        }

        /**
        * Genera un radicado para el proceso Juntas.
        *
        * @param int $id_evento
        * @return string
        */
        public function generarRadicadoJuntas($Id_evento){
            $consecutivo = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comunicado_eventos')
            ->select('N_radicado')
            ->where([
                ['ID_evento',$Id_evento],
                ['F_comunicado', date('Ymd')],
                ['Id_proceso','3']
            ])->max('N_radicado');

            $consecutivo = $consecutivo ? substr($consecutivo, - $this->config['longitud']) : 0;
            $nuevoConsecutivo = sprintf("%0{$this->config['longitud']}d", $consecutivo + 1);

            $radicado = sprintf("%s%s%s", $this->radicado['prefix'], $this->radicado['fecha'], $nuevoConsecutivo);

            return $radicado;
        }

        /**
        * Verifica si el radicado está disponible para el evento especificado.
        *
        * @param string $radicado
        * @return $this
        */
        public function disponible(string $radicado,$id_evento){
            $this->radicadoTMP = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comunicado_eventos')
            ->select('N_radicado')
            ->where([
                ['ID_evento',$id_evento],
                ['N_radicado', $radicado]
            ])->get()->first();

            if($this->radicadoTMP){
                $this->generarRadicado = true;
            }else{
                $this->radicadoTMP = $radicado;
                $this->generarRadicado = false;
            }
            return $this;
        }

    }
?>