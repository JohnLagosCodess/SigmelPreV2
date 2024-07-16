<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\sigmel_informacion_accion_eventos;
use App\Models\sigmel_informacion_asignacion_eventos;
use App\Models\sigmel_historial_acciones_eventos;
use App\Models\sigmel_lista_tipo_eventos;
use App\Models\sigmel_lista_tipo_evento_documentos;
use App\Models\sigmel_lista_grupo_documentales;
use App\Models\sigmel_informacion_documentos_solicitados_eventos;
use App\Models\sigmel_informacion_eventos;
use App\Models\sigmel_informacion_comunicado_eventos;
use App\Models\cndatos_info_comunicado_eventos;
use App\Models\sigmel_informacion_seguimientos_eventos;
use App\Models\sigmel_informacion_comite_interdisciplinario_eventos;
use App\Models\sigmel_informacion_parametrizaciones_clientes;
use App\Models\sigmel_informacion_acciones;
use App\Models\sigmel_informacion_acciones_automaticas_eventos;
use App\Models\sigmel_informacion_alertas_automaticas_eventos;
use App\Models\sigmel_informacion_historial_accion_eventos;
use App\Models\sigmel_lista_documentos;
use DateTime;
use Illuminate\Support\Sleep;

class AccionesAutomaticas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:acciones-automaticas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validar acciones automaicas ejecutadas para actualizar nuevas accciones automaticas';

    /**
     * Execute the console command.
     */
    public function handle()
    {   
        $time = time();
        $date = date("Y-m-d", $time);
        $date_time = date("Y-m-d 00:00:00");

        // se Captura la info de las acciones automaticas que se acabaron de ejecutar en el dia actual a  las 00:05:00 del job de Mysql
        $datos_info_acciones_automaticas = sigmel_informacion_acciones_automaticas_eventos::on('sigmel_gestiones')
        ->where([['F_movimiento_automatico', $date], ['Estado_accion_automatica', 'Ejecutada']])
        ->get();

        // Construyo el array de las acciones automaticas ejecutadas en el dia actual
        $array_datos_info_acciones_automaticas = $datos_info_acciones_automaticas->toArray();
        
        // sleep(3);
        // $this->info(print_r($array_datos_info_acciones_automaticas));        
        // se crea array para llenado
        $array_info_acciones_auto = array();

        // Iterar sobre el array de entrada (array principal) array_datos_info_acciones_automaticas y construir el array de salida array_info_acciones_auto
        foreach ($array_datos_info_acciones_automaticas as $item) {
            $nuevoItem = array(
                "Id_Asignacion" => $item["Id_Asignacion"],
                "ID_evento" => $item["ID_evento"],
                "Id_proceso" => $item["Id_proceso"],
                "Id_servicio" => $item["Id_servicio"],
                "Id_cliente" => $item["Id_cliente"],
                "Accion_automatica" => $item["Accion_automatica"],
                "Fecha_accion" => $item["F_accion"],
                "Nombre_usuario" => $item["Nombre_usuario"],
                "F_registro" => $item["F_registro"]
            );
            $array_info_acciones_auto[] = $nuevoItem;
        }
        // sleep(3);
        // print_r($array_info_acciones_auto);
        // Se captura la info deseada en la tabla sigmel_informacion_parametrizaciones_clientes con la iteracion
        // del array de salida construido array_info_acciones_auto 
        $resultadoParametrizaciones_clientes = sigmel_informacion_parametrizaciones_clientes::on('sigmel_gestiones')        
        ->select('Accion_ejecutar', 'Id_cliente', 'Id_proceso', 'Servicio_asociado', 'Movimiento_automatico', 'Tiempo_movimiento', 'Accion_automatica', 'Tiempo_alerta', 'Porcentaje_alerta_naranja', 'Porcentaje_alerta_roja');
        // Iterar sobre el array y agregar las condiciones
        foreach ($array_info_acciones_auto as $item) {
            $resultadoParametrizaciones_clientes->orWhere([
                ['Accion_ejecutar', $item['Accion_automatica']],
                ['Id_cliente', $item['Id_cliente']],
                ['Id_proceso', $item['Id_proceso']],
                ['Servicio_asociado', $item['Id_servicio']]                
            ]);
        }                    
        // Ejecutar la consulta final
        $resulParametrizaciones_clientes = $resultadoParametrizaciones_clientes->where([['Status_parametrico', 'Activo']])->get(); 
        // Convertir el object en array
        $validar_acciones_auto = $resulParametrizaciones_clientes->toArray();
        // sleep(3);
        // print_r($validar_acciones_auto);

        // Crear un nuevo array para almacenar el resultado ordenado 
        //en base a como se consulta  en resultadoParametrizaciones_clientes con este array array_info_acciones_auto

        $Organizar_acciones_ejecutadas_automaticas = [];

        foreach ($array_info_acciones_auto as $Item1) {
            foreach ($validar_acciones_auto as $Item2) {
                if ($Item1['Accion_automatica'] == $Item2['Accion_ejecutar']) {
                    $Organizar_acciones_ejecutadas_automaticas[] = $Item2;
                }
            }
        }

        // print_r($Organizar_acciones_ejecutadas_automaticas);

        // Combinar los array con cada una de sus posiciones pasando
            // [Id_Asignacion]
            // [ID_evento]
            // [Nombre_usuario]
            // [F_registro]

        $array_info_acciones_automati = [];

        foreach ($array_info_acciones_auto as $index => $item) {
            if (isset($Organizar_acciones_ejecutadas_automaticas[$index])) {
                $array_info_acciones_automati[$index] = array_merge($Organizar_acciones_ejecutadas_automaticas[$index], [
                    'Id_Asignacion' => $item['Id_Asignacion'],
                    'ID_evento' => $item['ID_evento'],
                    "Fecha_accion" => $item["Fecha_accion"],
                    'Nombre_usuario' => $item['Nombre_usuario'],
                    'F_registro' => $item['F_registro']
                ]);
            }
        }
        // sleep(3);
        // print_r($array_info_acciones_automati);       

        // Funciones para realizar consulta a la tabla sigmel_informacion_parametrizaciones_clientes 
        // si existe un valor en el intem Accion_automatica del array array_info_acciones_automati se realiza la consulta
        // si no no realiza nada o cero

        function consultaParametrizaciones_cliente($valorAccionAutomatica, $valorId_cliente, $valorId_proceso, $valorServicio_asociado) {
            
            $consultaParametrica_clientes = sigmel_informacion_parametrizaciones_clientes::on('sigmel_gestiones')
            ->select('Profesional_asignado')
            ->where([
                ['Accion_ejecutar', $valorAccionAutomatica],
                ['Id_cliente', $valorId_cliente],
                ['Id_proceso', $valorId_proceso],
                ['Servicio_asociado', $valorServicio_asociado]
            ])
            ->get();
            // Validar si la consulta viene falsa o no
            if ($consultaParametrica_clientes->isNotEmpty()) {
                return $consultaParametrica_clientes[0]->Profesional_asignado;
            } else {
                return 0; // en caso donde la consulta no devuelve resultados
            }
        }

        function consultaParametrizaciones_clientes($valorAccionAutomatica, $valorId_cliente, $valorId_proceso, $valorServicio_asociado) {
            
            $consultaParametrica_clientes = sigmel_informacion_parametrizaciones_clientes::on('sigmel_gestiones')
            ->select('Estado')
            ->where([
                ['Accion_ejecutar', $valorAccionAutomatica],
                ['Id_cliente', $valorId_cliente],
                ['Id_proceso', $valorId_proceso],
                ['Servicio_asociado', $valorServicio_asociado]
            ])
            ->get();
            // Validar si la consulta viene falsa o no
            if ($consultaParametrica_clientes->isNotEmpty()) {
                return $consultaParametrica_clientes[0]->Estado;
            } else {
                return 0; // en caso donde la consulta no devuelve resultados
            }
        }

        // Recorremos nuevamente el array_info_acciones_automati para consulta el profesional con la funcion consultaParametrizaciones_cliente

        foreach ($array_info_acciones_automati as &$item) {
            if (!empty($item['Accion_automatica']) and !empty($item['Tiempo_movimiento'])) {
                // Realizar la consulta con el valor de Accion_automatica
                $Id_profesional_automatico = consultaParametrizaciones_cliente($item['Accion_automatica'], $item['Id_cliente'], $item['Id_proceso'], $item['Servicio_asociado']);
                // Agregar $Id_profesional_automatico al array actual usando una referencia
                $item['Id_profesional_automatico'] = $Id_profesional_automatico;
            } else {
                $item['Id_profesional_automatico'] = 0;
                // No hacer nada si Accion_automatica está vacío
                // echo "Accion_automatica está vacío para Id_Asignacion " . $item['Id_Asignacion'] . "\n";
            }
        }

        unset($item); // Romper la referencia para finalizar el bucle cuando ya este listo

        // Recorremos nuevamente el array_info_acciones_automati para consulta el profesional con la funcion consultaParametrizaciones_cliente

        foreach ($array_info_acciones_automati as &$item) {
            if (!empty($item['Accion_automatica']) and !empty($item['Tiempo_movimiento'])) {
                // Realizar la consulta con el valor de Accion_automatica
                $Id_Estado_evento_automatico = consultaParametrizaciones_clientes($item['Accion_automatica'], $item['Id_cliente'], $item['Id_proceso'], $item['Servicio_asociado']);
                // Agregar $Id_Estado_evento_automatico al array actual usando una referencia
                $item['Id_Estado_evento_automatico'] = $Id_Estado_evento_automatico;
            } else {
                $item['Id_Estado_evento_automatico'] = 0;
                // No hacer nada si Accion_automatica está vacío
                // echo "Accion_automatica está vacío para Id_Asignacion " . $item['Id_Asignacion'] . "\n";
            }
        }

        unset($item); // Romper la referencia para finalizar el bucle cuando ya este listo
        // sleep(3);

        // ARRAY DEFINITIVO array_info_acciones_automati

        // print_r($array_info_acciones_automati);

        // AQUI INICIA CONSTRUCCION DE ARRAY PARA LA NUEVA ACCION AUTOMATICA

        $array_accion_automatica = array();

        foreach ($array_info_acciones_automati as $item) {
            if (!empty($item['Accion_automatica']) and !empty($item['Tiempo_movimiento'])) {
                $array_accion_automatica[] = $item;
            }
        }
        // sleep(3);

        // print_r($array_accion_automatica);

        // Funcion para realizar consulta a la tabla users 
        // si existe un valor en el intem Id_profesional_automatico del array array_accion_automatica se realiza la consulta
        // si no no realiza nada o cero

        function consulta_users($valorId_profesional_automatico) {
            
            $consultaUsers = DB::table('users')->select('name')
            ->where('id',$valorId_profesional_automatico)->get(); 
            // Validar si la consulta viene falsa o no
            if ($consultaUsers->isNotEmpty()) {
                return $consultaUsers[0]->name;
            } else {
                return null; // en caso donde la consulta no devuelve resultados
            }
        }

        // Recorremos el array_accion_automatica para consulta el nombre del profesional con la funcion consulta_users

        foreach ($array_accion_automatica as &$item) {
            if ($item['Id_profesional_automatico'] != 0){
                // Realizar la consulta con el valor de Id profesional automatico
                $Nombre_profesional_automatico = consulta_users($item['Id_profesional_automatico']);
                // Agregar $Id_profesional_automatico al array actual usando una referencia
                $item['Nombre_profesional_automatico'] = $Nombre_profesional_automatico;
            } else {
                $item['Nombre_profesional_automatico'] = null;
                // No hacer nada si Id_profesional_automatico es igual a 0
                // echo "Id_profesional_automatico es 0 para Id_Asignacion " . $item['Id_Asignacion'] . "\n";
            }
        }

        unset($item); // Romper la referencia para finalizar el bucle cuando ya este listo
        // sleep(3);

        // print_r($array_accion_automatica);

        // Funcion para calcular la Fecha de movimiento automatico

        function movimiento_automatico($valor_tiempo_movimiento){            
            $date_time = date("Y-m-d 00:00:00");
            // Se suman los dias a la fecha actual para saber la fecha del movimiento automatico
            $dateTime = new DateTime($date_time);
            $dias = $valor_tiempo_movimiento; // Número de días que quieres sumar
            $dateTime->modify("+$dias days");
            $F_movimiento_automatico = $dateTime->format('Y-m-d');   
            
            return $F_movimiento_automatico;
        }

        // Recorremos nuevamente el array_accion_automatica para calcular e insertar el nuevo item de la fecha de movimiento automatico

        foreach ($array_accion_automatica as &$item) {
            // Realizar la consulta con el valor de tiempo de movimiento
            $F_movimiento_automatico = movimiento_automatico($item['Tiempo_movimiento']);
            // Agregar $F_movimiento_automatico al array actual usando una referencia
            $item['F_movimiento_automatico'] = $F_movimiento_automatico;            
            $item['Estado_accion_automatica'] = 'Pendiente';   
            $item['F_accion'] = $date_time;
        }

        unset($item); // Romper la referencia para finalizar el bucle cuando ya este listo
        // sleep(3);

        // print_r($array_accion_automatica);

        // Se eliminan los items sobrantes que se usaron para las validaciones,
        // Para conservar los items que se van hacer update

        $items_a_eliminar = [
            'Accion_ejecutar',
            'Movimiento_automatico',
            'Tiempo_movimiento',
            'Tiempo_alerta',
            'Porcentaje_alerta_naranja',
            'Porcentaje_alerta_roja',
            'Fecha_accion'          
        ];

        // Recorremos  el array array_accion_automatica nuevamente y eliminamos los items especificados
        // Recorrer el array y eliminar los items especificados

        foreach ($array_accion_automatica as $key => $item) {
            foreach ($items_a_eliminar as $item_a_eliminar) {
                if (array_key_exists($item_a_eliminar, $item)) {
                    unset($array_accion_automatica[$key][$item_a_eliminar]);
                }
            }
        }

        // Romper la referencia del último elemento al finalizar el bucle
        unset($item);
        // sleep(3);

        // Imprimir el array modificado
        // print_r($array_accion_automatica);

        // Array con las items organizados segun la tabla sigmel_informacion_acciones_automaticas_eventos

        $organizar_array_accion_auto = [
            'Id_Asignacion',
            'ID_evento',
            'Id_proceso',
            'Servicio_asociado',
            'Id_cliente',
            'Accion_automatica',
            'Id_Estado_evento_automatico',
            'F_accion',
            'Id_profesional_automatico',
            'Nombre_profesional_automatico',
            'F_movimiento_automatico',
            'Estado_accion_automatica',
            'Nombre_usuario',
            'F_registro'
        ];

        $reorganizado_array_info_acciones_auto = [];
        // Se organiza el array para la actualizacion
        foreach ($array_accion_automatica as $item) {
            $nuevo_item = [];
            foreach ($organizar_array_accion_auto as $clave) {
                if (isset($item[$clave])) {
                    $nuevo_item[$clave] = $item[$clave];
                }
            }
            $reorganizado_array_info_acciones_auto[] = $nuevo_item;
        }
        // sleep(3);
        
        // print_r($reorganizado_array_info_acciones_auto);

        // FINALIZA LOGICA PARA LA ACTUALIZACION DE LAS ACCIONES AUTOMATICAS

        // AQUI INICIA CONSTRUCCION DE ARRAY PARA LA ACCION EJECUTADA QUE TIENE ALERTAS

        // print_r($array_info_acciones_automati);

        // Se crea un nuevo array el cual va a filtrar en el array array_info_acciones_automati las posiciones que solo tienen tiempo de alerta
        // y se reindexan sus posiciones

        $array_tiempo_alerta = array_values(array_filter($array_info_acciones_automati, function($item) {
            return !empty($item['Tiempo_alerta']) && empty($item['Porcentaje_alerta_naranja']) && empty($item['Porcentaje_alerta_roja']);
        }));

        $array_tiempo_alertaNaranja = array_values(array_filter($array_info_acciones_automati, function($item) {
            return !empty($item['Tiempo_alerta']) && !empty($item['Porcentaje_alerta_naranja']) && empty($item['Porcentaje_alerta_roja']);
        }));

        $array_tiempo_alertaRoja = array_values(array_filter($array_info_acciones_automati, function($item) {
            return !empty($item['Tiempo_alerta']) && empty($item['Porcentaje_alerta_naranja']) && !empty($item['Porcentaje_alerta_roja']);
        }));

        $array_tiempo_alertaNaranjaRoja = array_values(array_filter($array_info_acciones_automati, function($item) {
            return !empty($item['Tiempo_alerta']) && !empty($item['Porcentaje_alerta_naranja']) && !empty($item['Porcentaje_alerta_roja']);
        }));

        // Procedemos a eliminar las fecha accion anterior para poner la actual

        // print_r($array_tiempo_alerta);

        $items_a_eliminar_alerta = [            
            'Fecha_accion'         
        ];

        // Recorremos  el array array_tiempo_alerta nuevamente y eliminamos los items especificados
        // Recorrer el array y eliminar los items especificados

        foreach ($array_tiempo_alerta as $key => $item) {
            foreach ($items_a_eliminar_alerta as $item_a_eliminar) {
                if (array_key_exists($item_a_eliminar, $item)) {
                    unset($array_tiempo_alerta[$key][$item_a_eliminar]);
                }
            }
        }

        // Romper la referencia del último elemento al finalizar el bucle
        unset($item);

        // print_r($array_tiempo_alerta);

        // Recorremos el array_tiempo_alerta para calcular e insertar el nuevo item de la fecha de alerta automatico

        foreach ($array_tiempo_alerta as &$item) {            
            // Agregar $F_accion al array actual usando una referencia            
            $item['F_accion'] = $date_time;            
        }

        unset($item); // Romper la referencia para finalizar el bucle cuando ya este listo

        // print_r($array_tiempo_alerta);

        // print_r($array_tiempo_alertaNaranja);

        $items_a_eliminar_alerta_N = [            
            'Fecha_accion'         
        ];

        // Recorremos  el array array_tiempo_alerta nuevamente y eliminamos los items especificados
        // Recorrer el array y eliminar los items especificados

        foreach ($array_tiempo_alertaNaranja as $key => $item) {
            foreach ($items_a_eliminar_alerta_N as $item_a_eliminar) {
                if (array_key_exists($item_a_eliminar, $item)) {
                    unset($array_tiempo_alertaNaranja[$key][$item_a_eliminar]);
                }
            }
        }

        // Romper la referencia del último elemento al finalizar el bucle
        unset($item);

        // print_r($array_tiempo_alertaNaranja);

        // Recorremos el array_tiempo_alertaNaranja para calcular e insertar el nuevo item de la fecha de alerta automatico

        foreach ($array_tiempo_alertaNaranja as &$item) {            
            // Agregar $F_accion al array actual usando una referencia            
            $item['F_accion'] = $date_time;            
        }

        unset($item); // Romper la referencia para finalizar el bucle cuando ya este listo

        // print_r($array_tiempo_alertaNaranja);

        // print_r($array_tiempo_alertaRoja);

        $items_a_eliminar_alerta_R = [            
            'Fecha_accion'         
        ];

        // Recorremos  el array array_tiempo_alertaRoja nuevamente y eliminamos los items especificados
        // Recorrer el array y eliminar los items especificados

        foreach ($array_tiempo_alertaRoja as $key => $item) {
            foreach ($items_a_eliminar_alerta_R as $item_a_eliminar) {
                if (array_key_exists($item_a_eliminar, $item)) {
                    unset($array_tiempo_alertaRoja[$key][$item_a_eliminar]);
                }
            }
        }

        // Romper la referencia del último elemento al finalizar el bucle
        unset($item);

        // print_r($array_tiempo_alertaRoja);

        // Recorremos el array_tiempo_alerta para calcular e insertar el nuevo item de la fecha de alerta automatico

        foreach ($array_tiempo_alertaRoja as &$item) {            
            // Agregar $F_accion al array actual usando una referencia            
            $item['F_accion'] = $date_time;            
        }

        unset($item); // Romper la referencia para finalizar el bucle cuando ya este listo

        // print_r($array_tiempo_alertaRoja);

        // print_r($array_tiempo_alertaNaranjaRoja);

        $items_a_eliminar_alerta_NR = [            
            'Fecha_accion'         
        ];

        // Recorremos  el array array_tiempo_alertaNaranjaRoja nuevamente y eliminamos los items especificados
        // Recorrer el array y eliminar los items especificados

        foreach ($array_tiempo_alertaNaranjaRoja as $key => $item) {
            foreach ($items_a_eliminar_alerta_NR as $item_a_eliminar) {
                if (array_key_exists($item_a_eliminar, $item)) {
                    unset($array_tiempo_alertaNaranjaRoja[$key][$item_a_eliminar]);
                }
            }
        }

        // Romper la referencia del último elemento al finalizar el bucle
        unset($item);

        // print_r($array_tiempo_alertaNaranjaRoja);

        // Recorremos el array_tiempo_alerta para calcular e insertar el nuevo item de la fecha de alerta automatico

        foreach ($array_tiempo_alertaNaranjaRoja as &$item) {            
            // Agregar $F_accion al array actual usando una referencia            
            $item['F_accion'] = $date_time;            
        }

        unset($item); // Romper la referencia para finalizar el bucle cuando ya este listo

        // print_r($array_tiempo_alertaNaranjaRoja);

        // Funcion para calcular la nueva Fecha de alerta
             // formula FA= FC+TA (FA = fecha alerta, FC = fecha accion (fecha actual) y TA = tiempo de alerta)
        function nueva_fecha_alerta($valor_fecha_accion, $valor_tiempo_alerta){           
            $Nueva_F_Alerta = new DateTime($valor_fecha_accion);
            $horas = $valor_tiempo_alerta;
            $minutosAdicionales = ($horas - floor($horas)) * 60;
            $horas = floor($horas);
            $Nueva_F_Alerta->modify("+$horas hours");
            $Nueva_F_Alerta->modify("+$minutosAdicionales minutes");
            $Nueva_F_AlertaEvento = $Nueva_F_Alerta->format('Y-m-d H:i:s');   
            
            return $Nueva_F_AlertaEvento;
        }

        // Recorremos el array_tiempo_alerta para calcular e insertar el nuevo item de la fecha de alerta automatico

        foreach ($array_tiempo_alerta as &$item) {
            // Realizar la consulta con el valor de tiempo de alerta
            $F_alerta_automatico = nueva_fecha_alerta($item['F_accion'] , $item['Tiempo_alerta']);
            // Agregar $F_alerta_automatico al array actual usando una referencia            
            $item['F_alerta_automatico'] = $F_alerta_automatico;            
        }

        unset($item); // Romper la referencia para finalizar el bucle cuando ya este listo

        // print_r($array_tiempo_alerta);

        // Si solo hay tiempo alerta, se actualiza la fecha de alerta 

        foreach ($array_tiempo_alerta as $item) {            

            sigmel_informacion_accion_eventos::on('sigmel_gestiones')
            ->where([['Id_Asignacion', $item['Id_Asignacion']]])
            ->update([
                'F_Alerta' => $item['F_alerta_automatico'],
                'F_accion' => $item['F_accion']
            ]);

            sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->where([['Id_Asignacion', $item['Id_Asignacion']]])            
            ->update([
                'F_alerta' => $item['F_alerta_automatico'],
                'F_accion' => $item['F_accion']
            ]);
        }

        // Funcion para calcular  Alerta Naranja
            // formula AN = (TA*PN)/100 (AN= Alerta naranja, TA = tiempo de alerta y PN = porcentaje de alerta naranja)
        function alerta_naranja($valor_fecha_accion, $valor_tiempo_alerta, $valor_porcentaje_alerta_naranja){           
            $Alerta_Naranja = ($valor_tiempo_alerta * $valor_porcentaje_alerta_naranja) / 100;

            $Nueva_F_Alerta_Naranja = new DateTime($valor_fecha_accion);
            $horas = $Alerta_Naranja;
            $minutosAdicionales_naranja = ($horas - floor($horas)) * 60;
            $horas = floor($horas);
            $Nueva_F_Alerta_Naranja->modify("+$horas hours");
            $minutosAdicionales_naranja_entero = round($minutosAdicionales_naranja);
            $Nueva_F_Alerta_Naranja->modify("+$minutosAdicionales_naranja_entero minutes");
            $Nueva_F_Alerta_NaranjaEvento = $Nueva_F_Alerta_Naranja->format('Y-m-d H:i:s');
            
            return $Nueva_F_Alerta_NaranjaEvento;
        }


        // Recorremos el array_tiempo_alertaNaranja 
        //para calcular e insertar el nuevo item de la fecha de alerta automatico y alerta naranja         

        foreach ($array_tiempo_alertaNaranja as &$item) {
            // Realizar la consulta con el valor de tiempo de alerta
            $F_alerta_automatico = nueva_fecha_alerta($item['F_accion'] , $item['Tiempo_alerta']);
            // Agregar $F_alerta_automatico al array actual usando una referencia            
            $item['F_alerta_automatico'] = $F_alerta_automatico;    
            // Realizar la consulta con el valor de tiempo de alerta y porcentaje de alerta naranja
            $F_accion_alerta_naranja = alerta_naranja($item['F_accion'], $item['Tiempo_alerta'] , $item['Porcentaje_alerta_naranja']);        
            // Agregar $F_alerta_automatico al array actual usando una referencia            
            $item['F_accion_alerta_naranja'] = $F_accion_alerta_naranja;    
            $item['F_accion_alerta_roja'] = '';
            $item['Estado_alerta_automatica'] = 'Ejecucion';
        }

        unset($item); // Romper la referencia para finalizar el bucle cuando ya este listo

        // print_r($array_tiempo_alertaNaranja);

        // Se eliminan los items sobrantes que se usaron para las validaciones,
        // Para conservar los items que se van hacer update

        $items_a_eliminar_alertaNyR = [
            'Accion_automatica',
            'Movimiento_automatico',
            'Tiempo_movimiento',
            'Id_profesional_automatico',
            'Id_Estado_evento_automatico'      
        ];

        // Recorremos  el array array_tiempo_alertaNaranja nuevamente y eliminamos los items especificados
        // Recorrer el array y eliminar los items especificados

        foreach ($array_tiempo_alertaNaranja as $key => $item) {
            foreach ($items_a_eliminar_alertaNyR as $item_a_eliminar) {
                if (array_key_exists($item_a_eliminar, $item)) {
                    unset($array_tiempo_alertaNaranja[$key][$item_a_eliminar]);
                }
            }
        }

        // Romper la referencia del último elemento al finalizar el bucle
        unset($item);

        // print_r($array_tiempo_alertaNaranja);

        // Array con las items organizados segun la tabla sigmel_informacion_alertas_automaticas_eventos
        // Se agrega F_alerta_automatico el cual no esta dentro de la sigmel_informacion_alertas_automaticas_eventos

        $organizar_array_alerta_auto = [
            'Id_Asignacion',
            'ID_evento',
            'Id_proceso',
            'Servicio_asociado',
            'Id_cliente',
            'Accion_ejecutar',
            'F_accion',
            'Tiempo_alerta',
            'Porcentaje_alerta_naranja',
            'F_accion_alerta_naranja',
            'Porcentaje_alerta_roja',
            'F_accion_alerta_roja',
            'Estado_alerta_automatica',
            'Nombre_usuario',
            'F_registro',
            'F_alerta_automatico'
        ];

        $reorganizado_array_info_alertas_autoN = [];
        // Se organiza el array para la actualización o inserción
        foreach ($array_tiempo_alertaNaranja as $item) {
            $nuevo_item = [];
            foreach ($organizar_array_alerta_auto as $clave) {
                // Aseguramos que todas las claves estén presentes
                $nuevo_item[$clave] = isset($item[$clave]) ? $item[$clave] : null;
            }
            $reorganizado_array_info_alertas_autoN[] = $nuevo_item;
        }
        
        // print_r($reorganizado_array_info_alertas_autoN);

        // Si solo hay tiempo alerta, y porcentaje de alerta naraja, se actualiza la fecha de alerta 
        // y fecha de accion alerta naranja

        foreach ($reorganizado_array_info_alertas_autoN as $item) {            

            sigmel_informacion_accion_eventos::on('sigmel_gestiones')
            ->where([['Id_Asignacion', $item['Id_Asignacion']]])
            ->update([
                'F_Alerta' => $item['F_alerta_automatico'],
                'F_accion' => $item['F_accion']
            ]);

            sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->where([['Id_Asignacion', $item['Id_Asignacion']]])            
            ->update([
                'F_alerta' => $item['F_alerta_automatico'],
                'F_accion' => $item['F_accion']
            ]);

            // Validar si existe el Id_Asignacion en la tabla sigmel_informacion_alertas_automaticas_eventos para insert o update
            $info_datos_alertar_automaticas_eventos = sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')
            ->where([['Id_Asignacion', $item['Id_Asignacion']]])->get();

            if (count($info_datos_alertar_automaticas_eventos) > 0) {
                
                sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')
                ->where('Id_Asignacion', $item['Id_Asignacion'])
                ->update([
                    'ID_evento' => $item['ID_evento'],
                    'Id_proceso' => $item['Id_proceso'],
                    'Id_servicio' => $item['Servicio_asociado'],
                    'Id_cliente' => $item['Id_cliente'],
                    'Accion_ejecutar' => $item['Accion_ejecutar'],
                    'F_accion' => $item['F_accion'],
                    'Tiempo_alerta' => $item['Tiempo_alerta'],
                    'Porcentaje_alerta_naranja' => $item['Porcentaje_alerta_naranja'],
                    'F_accion_alerta_naranja' => $item['F_accion_alerta_naranja'],
                    'Porcentaje_alerta_roja' => $item['Porcentaje_alerta_roja'],
                    'F_accion_alerta_roja' => null,
                    'Estado_alerta_automatica' => $item['Estado_alerta_automatica'],
                    'Nombre_usuario' => $item['Nombre_usuario'],
                    'F_registro' => $item['F_registro']
                ]);                         
                
            } else {
                sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')                
                ->insert([
                    'Id_Asignacion' => $item['Id_Asignacion'],
                    'ID_evento' => $item['ID_evento'],
                    'Id_proceso' => $item['Id_proceso'],
                    'Id_servicio' => $item['Servicio_asociado'],
                    'Id_cliente' => $item['Id_cliente'],
                    'Accion_ejecutar' => $item['Accion_ejecutar'],
                    'F_accion' => $item['F_accion'],
                    'Tiempo_alerta' => $item['Tiempo_alerta'],
                    'Porcentaje_alerta_naranja' => $item['Porcentaje_alerta_naranja'],
                    'F_accion_alerta_naranja' => $item['F_accion_alerta_naranja'],
                    'Porcentaje_alerta_roja' => $item['Porcentaje_alerta_roja'],
                    'F_accion_alerta_roja' => null,
                    'Estado_alerta_automatica' => $item['Estado_alerta_automatica'],
                    'Nombre_usuario' => $item['Nombre_usuario'],
                    'F_registro' => $item['F_registro']
                ]); 
            }
        }

        // Funcion para calcular Alerta Roja
            // formula AR = (TA*PR)/100 (AR= Alerta roja, TA = tiempo de alerta y PR = porcentaje de alerta roja)
        function alerta_roja($valor_fecha_accion, $valor_tiempo_alerta, $valor_porcentaje_alerta_roja){           
            $Alerta_Roja = ($valor_tiempo_alerta * $valor_porcentaje_alerta_roja) / 100;

            $Nueva_F_Alerta_Roja = new DateTime($valor_fecha_accion);
            $horas_roja = $Alerta_Roja;
            $minutosAdicionales_roja = ($horas_roja - floor($horas_roja)) * 60;
            $horas_roja = floor($horas_roja);
            $Nueva_F_Alerta_Roja->modify("+$horas_roja hours");
            $minutosAdicionales_roja_entero = round($minutosAdicionales_roja);
            $Nueva_F_Alerta_Roja->modify("+$minutosAdicionales_roja_entero minutes");
            $Nueva_F_Alerta_RojaEvento = $Nueva_F_Alerta_Roja->format('Y-m-d H:i:s');
            
            return $Nueva_F_Alerta_RojaEvento;
        }

        // Recorremos el array_tiempo_alertaRoja 
        //para calcular e insertar el nuevo item de la fecha de alerta automatico y roja

        foreach ($array_tiempo_alertaRoja as &$item) {
            // Realizar la consulta con el valor de tiempo de alerta
            $F_alerta_automatico = nueva_fecha_alerta($item['F_accion'] , $item['Tiempo_alerta']);
            // Agregar $F_alerta_automatico al array actual usando una referencia            
            $item['F_alerta_automatico'] = $F_alerta_automatico;            
            // Agregar $F_accion_alerta_naranja al array actual usando una referencia            
            $item['F_accion_alerta_naranja'] = '';   
            // Realizar la consulta con el valor de fecha de accion, tiempo de alerta y porcentaje de alerta roja
            $F_accion_alerta_roja = alerta_roja($item['F_accion'], $item['Tiempo_alerta'] , $item['Porcentaje_alerta_roja']);        
            // Agregar $F_accion_alerta_roja al array actual usando una referencia            
            $item['F_accion_alerta_roja'] = $F_accion_alerta_roja;
            $item['Estado_alerta_automatica'] = 'Ejecucion';
        }

        unset($item); // Romper la referencia para finalizar el bucle cuando ya este listo

        // print_r($array_tiempo_alertaRoja);

        // Recorremos  el array array_tiempo_alertaRoja nuevamente y eliminamos los items especificados
        // Recorrer el array y eliminar los items especificados

        foreach ($array_tiempo_alertaRoja as $key => $item) {
            foreach ($items_a_eliminar_alertaNyR as $item_a_eliminar) {
                if (array_key_exists($item_a_eliminar, $item)) {
                    unset($array_tiempo_alertaRoja[$key][$item_a_eliminar]);
                }
            }
        }

        // Romper la referencia del último elemento al finalizar el bucle
        unset($item);

        // print_r($array_tiempo_alertaRoja);


        $reorganizado_array_info_alertas_autoR = [];
        // Se organiza el array para la actualización o inserción
        foreach ($array_tiempo_alertaRoja as $item) {
            $nuevo_item = [];
            foreach ($organizar_array_alerta_auto as $clave) {
                // Aseguramos que todas las claves estén presentes
                $nuevo_item[$clave] = isset($item[$clave]) ? $item[$clave] : null;
            }
            $reorganizado_array_info_alertas_autoR[] = $nuevo_item;
        }   
        
        // print_r($reorganizado_array_info_alertas_autoR);

        // Si solo hay tiempo alerta, y porcentaje de alerta roja
        // se actualiza la fecha de alerta, fecha accion alerta roja

        foreach ($reorganizado_array_info_alertas_autoR as $item) {            

            sigmel_informacion_accion_eventos::on('sigmel_gestiones')
            ->where([['Id_Asignacion', $item['Id_Asignacion']]])
            ->update([
                'F_Alerta' => $item['F_alerta_automatico'],
                'F_accion' => $item['F_accion']
            ]);

            sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->where([['Id_Asignacion', $item['Id_Asignacion']]])            
            ->update([
                'F_alerta' => $item['F_alerta_automatico'],
                'F_accion' => $item['F_accion']
            ]);

            // Validar si existe el Id_Asignacion en la tabla sigmel_informacion_alertas_automaticas_eventos para insert o update
            $info_datos_alertar_automaticas_eventos = sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')
            ->where([['Id_Asignacion', $item['Id_Asignacion']]])->get();

            if (count($info_datos_alertar_automaticas_eventos) > 0) {
                
                sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')
                ->where('Id_Asignacion', $item['Id_Asignacion'])
                ->update([
                    'ID_evento' => $item['ID_evento'],
                    'Id_proceso' => $item['Id_proceso'],
                    'Id_servicio' => $item['Servicio_asociado'],
                    'Id_cliente' => $item['Id_cliente'],
                    'Accion_ejecutar' => $item['Accion_ejecutar'],
                    'F_accion' => $item['F_accion'],
                    'Tiempo_alerta' => $item['Tiempo_alerta'],
                    'Porcentaje_alerta_naranja' => $item['Porcentaje_alerta_naranja'],
                    'F_accion_alerta_naranja' => null,
                    'Porcentaje_alerta_roja' => $item['Porcentaje_alerta_roja'],
                    'F_accion_alerta_roja' => $item['F_accion_alerta_roja'],
                    'Estado_alerta_automatica' => $item['Estado_alerta_automatica'],
                    'Nombre_usuario' => $item['Nombre_usuario'],
                    'F_registro' => $item['F_registro']
                ]);                         
                
            } else {
                sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')                
                ->insert([
                    'Id_Asignacion' => $item['Id_Asignacion'],
                    'ID_evento' => $item['ID_evento'],
                    'Id_proceso' => $item['Id_proceso'],
                    'Id_servicio' => $item['Servicio_asociado'],
                    'Id_cliente' => $item['Id_cliente'],
                    'Accion_ejecutar' => $item['Accion_ejecutar'],
                    'F_accion' => $item['F_accion'],
                    'Tiempo_alerta' => $item['Tiempo_alerta'],
                    'Porcentaje_alerta_naranja' => $item['Porcentaje_alerta_naranja'],
                    'F_accion_alerta_naranja' => null,
                    'Porcentaje_alerta_roja' => $item['Porcentaje_alerta_roja'],
                    'F_accion_alerta_roja' => $item['F_accion_alerta_roja'],
                    'Estado_alerta_automatica' => $item['Estado_alerta_automatica'],
                    'Nombre_usuario' => $item['Nombre_usuario'],
                    'F_registro' => $item['F_registro']
                ]); 
            }
        }

        // Recorremos el array_tiempo_alertaNaranjaRoja 
        //para calcular e insertar el nuevo item de la fecha de alerta automatico, alerta naranja y roja        
        
        foreach ($array_tiempo_alertaNaranjaRoja as &$item) {
            // Realizar la consulta con el valor de tiempo de alerta
            $F_alerta_automatico = nueva_fecha_alerta($item['F_accion'] , $item['Tiempo_alerta']);
            // Agregar $F_alerta_automatico al array actual usando una referencia            
            $item['F_alerta_automatico'] = $F_alerta_automatico;    
            // Realizar la consulta con el valor de fecha de accion, tiempo de alerta y porcentaje de alerta naranja
            $F_accion_alerta_naranja = alerta_naranja($item['F_accion'], $item['Tiempo_alerta'] , $item['Porcentaje_alerta_naranja']);        
            // Agregar $F_accion_alerta_naranja al array actual usando una referencia            
            $item['F_accion_alerta_naranja'] = $F_accion_alerta_naranja;   
            // Realizar la consulta con el valor de fecha de accion, tiempo de alerta y porcentaje de alerta roja
            $F_accion_alerta_roja = alerta_roja($item['F_accion'], $item['Tiempo_alerta'] , $item['Porcentaje_alerta_roja']);        
            // Agregar $F_accion_alerta_roja al array actual usando una referencia            
            $item['F_accion_alerta_roja'] = $F_accion_alerta_roja;
            $item['Estado_alerta_automatica'] = 'Ejecucion';
        }

        unset($item); // Romper la referencia para finalizar el bucle cuando ya este listo

        // Recorremos  el array array_tiempo_alertaNaranjaRoja nuevamente y eliminamos los items especificados
        // Recorrer el array y eliminar los items especificados

        foreach ($array_tiempo_alertaNaranjaRoja as $key => $item) {
            foreach ($items_a_eliminar_alertaNyR as $item_a_eliminar) {
                if (array_key_exists($item_a_eliminar, $item)) {
                    unset($array_tiempo_alertaNaranjaRoja[$key][$item_a_eliminar]);
                }
            }
        }

        // Romper la referencia del último elemento al finalizar el bucle
        unset($item);

        // print_r($array_tiempo_alertaNaranjaRoja);

        $reorganizado_array_info_alertas_autoNR = [];
        // Se organiza el array para la actualización o inserción
        foreach ($array_tiempo_alertaNaranjaRoja as $item) {
            $nuevo_item = [];
            foreach ($organizar_array_alerta_auto as $clave) {
                // Aseguramos que todas las claves estén presentes
                $nuevo_item[$clave] = isset($item[$clave]) ? $item[$clave] : null;
            }
            $reorganizado_array_info_alertas_autoNR[] = $nuevo_item;
        }
        
        // print_r($reorganizado_array_info_alertas_autoNR);

        // Si solo hay tiempo alerta,  porcentaje de alerta naraja, y porcentaje de alerta roja
        // se actualiza la fecha de alerta, fecha de accion alerta naranja y fecha accion alerta roja

        foreach ($reorganizado_array_info_alertas_autoNR as $item) {            

            sigmel_informacion_accion_eventos::on('sigmel_gestiones')
            ->where([['Id_Asignacion', $item['Id_Asignacion']]])
            ->update([
                'F_Alerta' => $item['F_alerta_automatico'],
                'F_accion' => $item['F_accion']
            ]);

            sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->where([['Id_Asignacion', $item['Id_Asignacion']]])            
            ->update([
                'F_alerta' => $item['F_alerta_automatico'],
                'F_accion' => $item['F_accion']
            ]);

            // Validar si existe el Id_Asignacion en la tabla sigmel_informacion_alertas_automaticas_eventos para insert o update
            $info_datos_alertar_automaticas_eventos = sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')
            ->where([['Id_Asignacion', $item['Id_Asignacion']]])->get();

            if (count($info_datos_alertar_automaticas_eventos) > 0) {
                
                sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')
                ->where('Id_Asignacion', $item['Id_Asignacion'])
                ->update([
                    'ID_evento' => $item['ID_evento'],
                    'Id_proceso' => $item['Id_proceso'],
                    'Id_servicio' => $item['Servicio_asociado'],
                    'Id_cliente' => $item['Id_cliente'],
                    'Accion_ejecutar' => $item['Accion_ejecutar'],
                    'F_accion' => $item['F_accion'],
                    'Tiempo_alerta' => $item['Tiempo_alerta'],
                    'Porcentaje_alerta_naranja' => $item['Porcentaje_alerta_naranja'],
                    'F_accion_alerta_naranja' => $item['F_accion_alerta_naranja'],
                    'Porcentaje_alerta_roja' => $item['Porcentaje_alerta_roja'],
                    'F_accion_alerta_roja' => $item['F_accion_alerta_roja'],
                    'Estado_alerta_automatica' => $item['Estado_alerta_automatica'],
                    'Nombre_usuario' => $item['Nombre_usuario'],
                    'F_registro' => $item['F_registro']
                ]);                         
                
            } else {
                sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')                
                ->insert([
                    'Id_Asignacion' => $item['Id_Asignacion'],
                    'ID_evento' => $item['ID_evento'],
                    'Id_proceso' => $item['Id_proceso'],
                    'Id_servicio' => $item['Servicio_asociado'],
                    'Id_cliente' => $item['Id_cliente'],
                    'Accion_ejecutar' => $item['Accion_ejecutar'],
                    'F_accion' => $item['F_accion'],
                    'Tiempo_alerta' => $item['Tiempo_alerta'],
                    'Porcentaje_alerta_naranja' => $item['Porcentaje_alerta_naranja'],
                    'F_accion_alerta_naranja' => $item['F_accion_alerta_naranja'],
                    'Porcentaje_alerta_roja' => $item['Porcentaje_alerta_roja'],
                    'F_accion_alerta_roja' => $item['F_accion_alerta_roja'],
                    'Estado_alerta_automatica' => $item['Estado_alerta_automatica'],
                    'Nombre_usuario' => $item['Nombre_usuario'],
                    'F_registro' => $item['F_registro']
                ]); 
            }
        }

        // ACTUALIZACION ACCIONES AUTOMATICAS, SE DEJA AL FINAL POR LOGICA DE LA FUNCIONALIDAD POR CAMBIO DE LOS ESTADOS
        // Se recorre array organizado y se va actualizando segun el Id Asignacion 
        // en la tabla sigmel_informacion_acciones_automaticas_eventos

        foreach ($reorganizado_array_info_acciones_auto as $item) {
            sigmel_informacion_acciones_automaticas_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $item['Id_Asignacion'])
            ->update([
                'ID_evento' => $item['ID_evento'],
                'Id_proceso' => $item['Id_proceso'],
                'Id_servicio' => $item['Servicio_asociado'],
                'Id_cliente' => $item['Id_cliente'],
                'Accion_automatica' => $item['Accion_automatica'],
                'Id_Estado_evento_automatico' => $item['Id_Estado_evento_automatico'],
                'F_accion' => $item['F_accion'],
                'Id_profesional_automatico' => $item['Id_profesional_automatico'],
                'Nombre_profesional_automatico' => $item['Nombre_profesional_automatico'],
                'F_movimiento_automatico' => $item['F_movimiento_automatico'],
                'Estado_accion_automatica' => $item['Estado_accion_automatica'],
                'Nombre_usuario' => $item['Nombre_usuario'],
                'F_registro' => $item['F_registro'],
            ]);
        }
        // sleep(3);
        
    }
}
