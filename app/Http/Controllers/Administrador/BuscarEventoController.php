<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\cndatos_eventos;
use App\Models\sigmel_clientes;
use App\Models\sigmel_informacion_asignacion_eventos;
use App\Models\sigmel_informacion_controversia_juntas_eventos;
use App\Models\sigmel_informacion_decreto_eventos;
use App\Models\sigmel_informacion_diagnosticos_eventos;
use App\Models\sigmel_numero_orden_eventos;
use App\Models\sigmel_informacion_eventos;
use App\Models\sigmel_informacion_historial_accion_eventos;
use App\Models\sigmel_informacion_pronunciamiento_eventos;

class BuscarEventoController extends Controller
{
    /* TODO LO REFERENTE AL FORMULARIO DE BUSCAR UN EVENTO*/
    // Busqueda Evaluado y evento
    public function mostrarVistaBuscarEvento(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();

        // $session = app('session');
        // $session->put('num_ident', "");
        // $session->put('num_id_evento', "");

        return view('administrador.busquedaEvento', compact('user'));
    }


    // Resultado de busqueda
    public function consultaInformacionEvento(Request $request){
    
        $consultar_nro_identificacion = $request->consultar_nro_identificacion;
        $consultar_id_evento = $request->consultar_id_evento;
        $proceso_origen = 1;
        $servicio_OrigenDto = 1;
        $servicio_OrigenAdx = 2;
        $servicio_OrigenPron = 3;
        $proceso_pcl = 2;
        $servicio_PclCali = 6;
        $servicio_PclReca = 7;  
        $servicio_PclRevi = 8; 
        $servicio_PclPron = 9;  
        $proceso_Juntas = 3;              
        $servicio_JuntasConOri = 12;
        $servicio_JuntasConPcl = 13;
        $servicio_Notificacion = 4;        
        /* 
            CASO N° 1: Cuando se consulta solamente por el número de identificación.
            CASO N° 2: Cuando se consulta solamente por el id de evento.
            CASO N° 3: Cuando se consulta por número de identificación y id de evento.
        */
        switch(true)
        {
            case (!empty($consultar_nro_identificacion) and empty($consultar_id_evento)):
                $informacion_eventos = cndatos_eventos::on('sigmel_gestiones')
                    ->where('Nro_identificacion', $consultar_nro_identificacion)
                    ->orderBy('ID_evento', 'desc')
                    ->get();
                $array_informacion_eventos = json_decode(json_encode($informacion_eventos, true));                
                if(count($array_informacion_eventos)>0){
                    foreach ($array_informacion_eventos as $model) {
                        $resultArrayEventos[] = [
                            'ID_evento' => $model->ID_evento,
                            'Id_proceso' => $model->Id_proceso,
                            'Nombre_proceso' => $model->Nombre_proceso,
                            'Id_Servicio' => $model->Id_Servicio,
                            'Nombre_servicio' => $model->Nombre_servicio,
                            'Id_Asignacion' => $model->Id_Asignacion,
                        ];
                    }                                             
                    // Resultado DTO Origen
                    $posicionOrigenDto = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_origen && $element['Id_Servicio'] == $servicio_OrigenDto) {
                            $posicionOrigenDto[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }             
                    if (count($posicionOrigenDto) > 0) {                    
                        $resultadoDtoOrigen = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_dto_atel_eventos as sidae')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sidae.Origen')
                        ->select('sidae.ID_evento', 'sidae.Id_Asignacion', 'sidae.Origen', 'slp.Nombre_parametro');
                        // Iterar sobre el array y agregar las condiciones
                        foreach ($posicionOrigenDto as $item) {
                            $resultadoDtoOrigen->orWhere([
                                ['sidae.Id_Asignacion', $item['Id_Asignacion']],
                                ['sidae.Id_proceso', $item['Id_proceso']],
                                ['sidae.ID_evento', $item['ID_evento']]
                            ]);
                        }                    
                        // Ejecutar la consulta final
                        $resulDtoOrigen = $resultadoDtoOrigen->get(); 
                        if (count($resulDtoOrigen) > 0) {                        
                            $ArrayresulDtoOrigen = $resulDtoOrigen->toArray();                                                            
                            foreach ($posicionOrigenDto as &$item) {
                                // Buscar el elemento correspondiente en los resultados de la consulta
                                $resultado = array_filter($ArrayresulDtoOrigen, function ($result) use ($item) {
                                    return $result->Id_Asignacion == $item['Id_Asignacion'];
                                });                    
                                // Si se encuentra una coincidencia, agregar la información al array original
                                if (!empty($resultado)) {
                                    $resultado = reset($resultado); // Obtener el primer elemento del array de resultados
                                    $item['OrigenDtoResultado'] = $resultado->Nombre_parametro;
                                } 
                            }                                           
                            // Filtrar los elementos que contienen [OrigenDtoResultado]
                            $posicionOrigenDtoFiltrado = array_filter($posicionOrigenDto, function ($item) {
                                return isset($item['OrigenDtoResultado']);
                            }); 
                            // Reorganizar los índices del array filtrado
                            $posicionOrigenDtoFiltrado = array_values($posicionOrigenDtoFiltrado);                                             
                            //Combinar el array object con el array                          
                            foreach ($array_informacion_eventos as $key2 => $item2) {
                                foreach ($posicionOrigenDtoFiltrado as $item1) {
                                    // Verificar si hay coincidencia en Id_Asignacion
                                    if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                        // Agregar el elemento a la posición correspondiente
                                        $array_informacion_eventos[$key2]->Resultado = $item1['OrigenDtoResultado'];
                                        break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                    }
                                }
                            }    
                        }                   
                    }  
                    // // Resultado Adicion DX Origen
                    $posicionOrigenAdx = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_origen && $element['Id_Servicio'] == $servicio_OrigenAdx) {
                            $posicionOrigenAdx[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }                 
                    if (count($posicionOrigenAdx) > 0) {
                        $resultadoAdxOrigen = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
                        ->select('side.ID_evento','side.Id_Asignacion','side.CIE10', 'slcd.CIE10 as CodigoCIE', 'side.Nombre_CIE10');
                        // Iterar sobre el array y agregar las condiciones
                        foreach ($posicionOrigenAdx as $item) {
                            $resultadoAdxOrigen->orWhere([
                                ['side.Id_Asignacion', $item['Id_Asignacion']],
                                ['side.Id_proceso', $item['Id_proceso']],
                                ['side.ID_evento', $item['ID_evento']]
                            ]);
                        }
                        // Ejecutar la consulta final
                        $resulAdxOrigen = $resultadoAdxOrigen->whereNotNull('F_adicion_CIE10')->get();                     
                        if (count($resulAdxOrigen) > 0) {
                            $ArrayresulAdxOrigen = $resulAdxOrigen->toArray();                                            
                            foreach ($ArrayresulAdxOrigen as $item) {
                                $idEvento = $item->ID_evento;
                                $idAsignacion = $item->Id_Asignacion;
                                $codigoCIE = $item->CodigoCIE;                       
                                // Buscar la clave correspondiente en $posicionOrigenAdx
                                $clave = null;
                                foreach ($posicionOrigenAdx as $indice => $elemento) {
                                    if ($elemento['ID_evento'] == $idEvento && $elemento['Id_Asignacion'] == $idAsignacion) {
                                        $clave = $indice;
                                        break;
                                    }
                                }
                                // Si se encuentra la clave, agregar el CodigoCIE al array existente
                                if ($clave !== null) {
                                    if (!isset($posicionOrigenAdx[$clave]['OrigenCieResultado'])) {
                                        $posicionOrigenAdx[$clave]['OrigenCieResultado'] = $codigoCIE;
                                    } else {
                                        $posicionOrigenAdx[$clave]['OrigenCieResultado'] .= ', ' . $codigoCIE;
                                    }
                                }
                                
                            }                                                                
                            // Filtrar los elementos que contienen [OrigenDtoResultado]
                            $posicionOrigenAdxFiltrado = array_filter($posicionOrigenAdx, function ($item) {
                                return isset($item['OrigenCieResultado']);
                            }); 
                            // Reorganizar los índices del array filtrado
                            $posicionOrigenAdxFiltrado = array_values($posicionOrigenAdxFiltrado);                           
                            //Combinar el array object con el array posicionOrigenAdx
                            foreach ($array_informacion_eventos as $key2 => $item2) {
                                foreach ($posicionOrigenAdxFiltrado as $item1) {
                                    // Verificar si hay coincidencia en Id_Asignacion
                                    if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                        // Agregar el elemento a la posición correspondiente
                                        $array_informacion_eventos[$key2]->Resultado = $item1['OrigenCieResultado'];
                                        break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                    }
                                }
                            }
                        }
                        
                    }  
                    // Resultado Pronunciamiento Origen
                    $posicionOrigenPron = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_origen && $element['Id_Servicio'] == $servicio_OrigenPron) {
                            $posicionOrigenPron[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }                
                    if (count($posicionOrigenPron) > 0) {
                        $resultadoPronOrigen = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_pronunciamiento_eventos as sidae')                    
                        ->select('sidae.ID_evento','sidae.Id_Asignacion', 'sidae.Id_proceso', 'sidae.Decision');
                        // Iterar sobre el array y agregar las condiciones
                        foreach ($posicionOrigenPron as $item) {
                            $resultadoPronOrigen->orWhere([
                                ['sidae.Id_Asignacion', $item['Id_Asignacion']],
                                ['sidae.Id_proceso', $item['Id_proceso']],
                                ['sidae.ID_evento', $item['ID_evento']]
                            ]);
                        }
                        // Ejecutar la consulta final
                        $resulPronOrigen = $resultadoPronOrigen->get();                     
                        if (count($resulPronOrigen) > 0) {
                            $ArrayresulPronOrigen = $resulPronOrigen->toArray();                         
                            foreach ($posicionOrigenPron as &$item) {
                                // Buscar el elemento correspondiente en los resultados de la consulta
                                $resultado = array_filter($ArrayresulPronOrigen, function ($result) use ($item) {
                                    return $result->Id_Asignacion == $item['Id_Asignacion'];
                                });                    
                                // Si se encuentra una coincidencia, agregar la información al array original
                                if (!empty($resultado)) {
                                    $resultado = reset($resultado); // Obtener el primer elemento del array de resultados
                                    $item['DecisionProResultado'] = $resultado->Decision;
                                } 
                            }                          
                            // Filtrar los elementos que contienen [OrigenDtoResultado]
                            $posicionOrigenPronFiltrado = array_filter($posicionOrigenPron, function ($item) {
                                return isset($item['DecisionProResultado']);
                            }); 
                            // Reorganizar los índices del array filtrado
                            $posicionOrigenPronFiltrado = array_values($posicionOrigenPronFiltrado);                                             
                            //Combinar el array object con el array                          
                            foreach ($array_informacion_eventos as $key2 => $item2) {
                                foreach ($posicionOrigenPronFiltrado as $item1) {
                                    // Verificar si hay coincidencia en Id_Asignacion
                                    if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                        // Agregar el elemento a la posición correspondiente
                                        $array_informacion_eventos[$key2]->Resultado = $item1['DecisionProResultado'];
                                        break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                    }
                                }
                            }                                                    
                               
                        }
                        
                    }
                    // // Resultado Calificacion Tecnica Pcl
                    $posicionPclCali = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_pcl && $element['Id_Servicio'] == $servicio_PclCali) {
                            $posicionPclCali[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }                
                    if (count($posicionPclCali) > 0) {                    
    
                        $resultadoCaliPcl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')                    
                        ->select('ID_Evento','Id_Asignacion','Porcentaje_pcl');
                        foreach ($posicionPclCali as $item) {
                            $resultadoCaliPcl->orWhere([
                                ['side.Id_Asignacion', $item['Id_Asignacion']],
                                ['side.Id_proceso', $item['Id_proceso']],
                                ['side.ID_evento', $item['ID_evento']]
                            ]);
                        }
                        $resulCaliPcl = $resultadoCaliPcl->get();                     
                        if (count($resulCaliPcl) > 0) {
                            $ArrayresulCaliPcl = $resulCaliPcl->toArray();                                                 
                            foreach ($posicionPclCali as &$item) {
                                // Buscar el elemento correspondiente en los resultados de la consulta
                                $resultado = array_filter($ArrayresulCaliPcl, function ($result) use ($item) {
                                    return $result->Id_Asignacion == $item['Id_Asignacion'];
                                });                    
                                // Si se encuentra una coincidencia, agregar la información al array original
                                if (!empty($resultado)) {
                                    $resultado = reset($resultado); // Obtener el primer elemento del array de resultados
                                    $item['Porcentaje_pclProResultado'] = $resultado->Porcentaje_pcl;
                                } 
                            }                          
                            // Filtrar los elementos que contienen [OrigenDtoResultado]
                            $posicionPclCaliFiltrado = array_filter($posicionPclCali, function ($item) {
                                return isset($item['Porcentaje_pclProResultado']);
                            }); 
                            // Reorganizar los índices del array filtrado
                            $posicionPclCaliFiltrado = array_values($posicionPclCaliFiltrado);
                            //Combinar el array object con el array                          
                            foreach ($array_informacion_eventos as $key2 => $item2) {
                                foreach ($posicionPclCaliFiltrado as $item1) {
                                    // Verificar si hay coincidencia en Id_Asignacion
                                    if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                        // Agregar el elemento a la posición correspondiente
                                        $array_informacion_eventos[$key2]->Resultado = $item1['Porcentaje_pclProResultado'];
                                        break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                    }
                                }
                            }                           
                        }
                    }
                    // Resultado Recalificacion Pcl
                    $posicionPclReca = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_pcl && $element['Id_Servicio'] == $servicio_PclReca) {
                            $posicionPclReca[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }
                    if (count($posicionPclReca) > 0) {
                        
                        $resultadoRecaPcl =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
                        ->select('side.ID_Evento','side.Id_Asignacion','side.Porcentaje_pcl');
                        foreach ($posicionPclReca as $item) {
                            $resultadoRecaPcl->orWhere([
                                ['side.Id_Asignacion', $item['Id_Asignacion']],
                                ['side.Id_proceso', $item['Id_proceso']],
                                ['side.ID_evento', $item['ID_evento']]
                            ]);
                        }
                        $resulRecaPcl = $resultadoRecaPcl->get();                                        
                        if (count($resulRecaPcl) > 0) {
                            $ArrayresulRecaPcl = $resulRecaPcl->toArray();                                                 
                            foreach ($posicionPclReca as &$item) {
                                // Buscar el elemento correspondiente en los resultados de la consulta
                                $resultado = array_filter($ArrayresulRecaPcl, function ($result) use ($item) {
                                    return $result->Id_Asignacion == $item['Id_Asignacion'];
                                });                    
                                // Si se encuentra una coincidencia, agregar la información al array original
                                if (!empty($resultado)) {
                                    $resultado = reset($resultado); // Obtener el primer elemento del array de resultados
                                    $item['ProcentajePClRecaResultado'] = $resultado->Porcentaje_pcl;
                                } 
                            }                          
                            // Filtrar los elementos que contienen [OrigenDtoResultado]
                            $posicionPclRecaFiltrado = array_filter($posicionPclReca, function ($item) {
                                return isset($item['ProcentajePClRecaResultado']);
                            }); 
                            // Reorganizar los índices del array filtrado
                            $posicionPclRecaFiltrado = array_values($posicionPclRecaFiltrado);
                            //Combinar el array object con el array 
                            foreach ($array_informacion_eventos as $key2 => $item2) {
                                foreach ($posicionPclRecaFiltrado as $item1) {
                                    // Verificar si hay coincidencia en Id_Asignacion
                                    if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                        // Agregar el elemento a la posición correspondiente
                                        $array_informacion_eventos[$key2]->Resultado = $item1['ProcentajePClRecaResultado'];
                                        break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                    }
                                }
                            }
                        }                    
                        
                    }
                    // Resultado Revision Pension Pcl
                    $posicionPclRevi = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_pcl && $element['Id_Servicio'] == $servicio_PclRevi) {
                            $posicionPclRevi[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }
                    if (count($posicionPclRevi) > 0) {
                        $resultadoReviPcl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
                        ->select('side.ID_Evento','side.Id_Asignacion','side.Porcentaje_pcl');
                        foreach ($posicionPclRevi as $item) {
                            $resultadoReviPcl->orWhere([
                                ['Id_Asignacion',$item['Id_Asignacion']], 
                                ['Id_proceso',$item['Id_proceso']], 
                                ['ID_Evento',$item['ID_evento']]]);
                        }
                        $resulReviPcl = $resultadoReviPcl->get();
    
                        if(count($resulReviPcl) > 0){
                            $ArrayresulReviPcl = $resulReviPcl->toArray();                                                
                            foreach ($posicionPclRevi as &$item) {
                                // Buscar el elemento correspondiente en los resultados de la consulta
                                $resultado = array_filter($ArrayresulReviPcl, function ($result) use ($item) {
                                    return $result->Id_Asignacion == $item['Id_Asignacion'];
                                });                    
                                // Si se encuentra una coincidencia, agregar la información al array original
                                if (!empty($resultado)) {
                                    $resultado = reset($resultado); // Obtener el primer elemento del array de resultados
                                    $item['ProcentajePClReviResultado'] = $resultado->Porcentaje_pcl;
                                } 
                            }                              
                            // Filtrar los elementos que contienen [OrigenDtoResultado]
                            $posicionPclReviFiltrado = array_filter($posicionPclRevi, function ($item) {
                                return isset($item['ProcentajePClReviResultado']);
                            }); 
                            // Reorganizar los índices del array filtrado
                            $posicionPclReviFiltrado = array_values($posicionPclReviFiltrado);
                            //Combinar el array object con el array 
                            foreach ($array_informacion_eventos as $key2 => $item2) {
                                foreach ($posicionPclReviFiltrado as $item1) {
                                    // Verificar si hay coincidencia en Id_Asignacion
                                    if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                        // Agregar el elemento a la posición correspondiente
                                        $array_informacion_eventos[$key2]->Resultado = $item1['ProcentajePClReviResultado'];
                                        break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                    }
                                }
                            }
                        }
                    }
                    // Resultado Pronunciamiento Pcl
                    $posicionPclPron = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_pcl && $element['Id_Servicio'] == $servicio_PclPron) {
                            $posicionPclPron[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }
                    if (count($posicionPclPron) > 0) {
                        
                        $resultadoPronPcl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_pronunciamiento_eventos as sipe')                    
                        ->select('sipe.ID_evento','sipe.Id_Asignacion','sipe.Decision');
                        foreach ($posicionPclPron as $item) {
                            $resultadoPronPcl->orWhere([
                                ['sipe.Id_Asignacion',$item['Id_Asignacion']], 
                                ['sipe.Id_proceso',$item['Id_proceso']], 
                                ['sipe.ID_evento',$item['ID_evento']]
                            ]);
                        }
                        $resulPronPcl = $resultadoPronPcl->get();
    
                        if (count($resulPronPcl) > 0) {
                            $ArrayresulPronPcl = $resulPronPcl->toArray();                                                
                            foreach ($posicionPclPron as &$item) {
                                // Buscar el elemento correspondiente en los resultados de la consulta
                                $resultado = array_filter($ArrayresulPronPcl, function ($result) use ($item) {
                                    return $result->Id_Asignacion == $item['Id_Asignacion'];
                                });                    
                                // Si se encuentra una coincidencia, agregar la información al array original
                                if (!empty($resultado)) {
                                    $resultado = reset($resultado); // Obtener el primer elemento del array de resultados
                                    $item['DecisionProResultado'] = $resultado->Decision;
                                } 
                            }                              
                            // Filtrar los elementos que contienen [OrigenDtoResultado]
                            $posicionPclPronFiltrado = array_filter($posicionPclPron, function ($item) {
                                return isset($item['DecisionProResultado']);
                            }); 
                            // Reorganizar los índices del array filtrado
                            $posicionPclPronFiltrado = array_values($posicionPclPronFiltrado);
                            //Combinar el array object con el array 
                            foreach ($array_informacion_eventos as $key2 => $item2) {
                                foreach ($posicionPclPronFiltrado as $item1) {
                                    // Verificar si hay coincidencia en Id_Asignacion
                                    if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                        // Agregar el elemento a la posición correspondiente
                                        $array_informacion_eventos[$key2]->Resultado = $item1['DecisionProResultado'];
                                        break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                    }
                                }
                            }
                        }  
                    }
                    //Resultado Controversia Origen Juntas
                    $posicionJuntasConOri = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_Juntas && $element['Id_Servicio'] == $servicio_JuntasConOri) {
                            $posicionJuntasConOri[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }                  
                    if (count($posicionJuntasConOri) > 0) {                    
                        $resultadoJuntasConOri = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_controversia_juntas_eventos as sicje')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slpa', 'slpa.Id_Parametro', '=', 'sicje.Origen_jnci_emitido')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slpara', 'slpara.Id_Parametro', '=', 'sicje.Origen_reposicion_jrci')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sicje.Origen_jrci_emitido')
                        ->select('ID_evento','Id_Asignacion',
                            DB::raw("CASE WHEN n_dictamen_jrci_emitido IS NULL THEN 'Vacio' ELSE n_dictamen_jrci_emitido END AS n_dictamen_jrci_emitido"),
                            'sicje.Origen_jrci_emitido',
                            'slp.Nombre_parametro as OrigenDxJRCIemitido',
                            'sicje.Decision_dictamen_jrci',
                            DB::raw("CASE WHEN n_dictamen_reposicion_jrci IS NULL THEN 'Vacio' ELSE n_dictamen_reposicion_jrci END AS n_dictamen_reposicion_jrci"),
                            'sicje.Origen_reposicion_jrci',
                            'slpara.Nombre_parametro as OrigenDxJRCIreposicion',
                            'sicje.Decision_dictamen_repo_jrci',
                            DB::raw("CASE WHEN n_dictamen_jnci_emitido IS NULL THEN 'Vacio' ELSE n_dictamen_jnci_emitido END AS n_dictamen_jnci_emitido"),
                            'sicje.Origen_jnci_emitido',
                            'slpa.Nombre_parametro as OrigenDxJNCIemitido');
                        foreach ($posicionJuntasConOri as $item) {
                            $resultadoJuntasConOri->orWhere([
                                ['Id_Asignacion',$item['Id_Asignacion']], 
                                ['Id_proceso',$item['Id_proceso']], 
                                ['ID_evento',$item['ID_evento']]
                            ]);
                        }
                        $resulJuntasConOri = $resultadoJuntasConOri->get();
                        if (count($resulJuntasConOri) > 0) {
                            $ArrayresulJuntasConOri = $resulJuntasConOri->toArray();                                                                        
                            $cantidadArrayresulJuntasConOri = count($ArrayresulJuntasConOri) - 1;                        
                            for ($i=0; $i <= $cantidadArrayresulJuntasConOri ; $i++) { 
                                //echo $i.'<br>';
                                //variables JRCI Emitido
                                $Jrci_1_Resultado = $ArrayresulJuntasConOri[$i]->n_dictamen_jrci_emitido; 
                                $OrigenDxJRCIemitido = $ArrayresulJuntasConOri[$i]->OrigenDxJRCIemitido;                 
                                $Decision_dictamen_jrci = $ArrayresulJuntasConOri[$i]->Decision_dictamen_jrci;
                                // Variables JRCI Reposicion
                                $Jrci_2_Resultado = $ArrayresulJuntasConOri[$i]->n_dictamen_reposicion_jrci;
                                $OrigenDxJRCIreposicion = $ArrayresulJuntasConOri[$i]->OrigenDxJRCIreposicion;
                                $Decision_dictamen_repo_jrci = $ArrayresulJuntasConOri[$i]->Decision_dictamen_repo_jrci;
                                // Variables JNCI Emitido                   
                                $Jnci_Resultado = $ArrayresulJuntasConOri[$i]->n_dictamen_jnci_emitido;
                                $OrigenDxJNCIemitido = $ArrayresulJuntasConOri[$i]->OrigenDxJNCIemitido;
                                $IdAsignacionResultado = $ArrayresulJuntasConOri[$i]->Id_Asignacion;
                                $ID_eventoResultado = $ArrayresulJuntasConOri[$i]->ID_evento;
            
                                if ($Jnci_Resultado != 'Vacio') {
                                    foreach ($posicionJuntasConOri as &$elemento) {
                                        // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                        if ($elemento['Id_Asignacion'] == $IdAsignacionResultado) {
                                            // Agregar $OrigenResultado al array
                                            $elemento['ContJuntasOriResultado'] = 'JNCI_'.$OrigenDxJNCIemitido;
                                        }
                                    }
                                }elseif ($Jrci_2_Resultado != 'Vacio') {
                                    foreach ($posicionJuntasConOri as &$elemento) {
                                        // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                        if ($elemento['Id_Asignacion'] == $IdAsignacionResultado) {
                                            // Agregar $OrigenResultado al array
                                            $elemento['ContJuntasOriResultado'] = 'JRCI_'.$OrigenDxJRCIreposicion.'_'.$Decision_dictamen_repo_jrci;
                                        }
                                    }                                                                                           
                                }elseif ($Jrci_1_Resultado != 'Vacio') {
                                    foreach ($posicionJuntasConOri as &$elemento) {
                                        // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                        if ($elemento['Id_Asignacion'] == $IdAsignacionResultado) {
                                            // Agregar $OrigenResultado al array
                                            $elemento['ContJuntasOriResultado'] = 'JRCI_'.$OrigenDxJRCIemitido.'_'.$Decision_dictamen_jrci;
                                        }
                                    }                                                                                   
                                }  
                            }                        
                            // Filtrar los elementos que contienen [OrigenDtoResultado]
                            $posicionJuntasConOriFiltrado = array_filter($posicionJuntasConOri, function ($item) {
                                return isset($item['ContJuntasOriResultado']);
                            }); 
                            // Reorganizar los índices del array filtrado
                            $posicionJuntasConOriFiltrado = array_values($posicionJuntasConOriFiltrado);                        
                            //Combinar el array object con el array posicionJuntasConOri
                            foreach ($array_informacion_eventos as $key2 => $item2) {
                                foreach ($posicionJuntasConOriFiltrado as $item1) {
                                    // Verificar si hay coincidencia en Id_Asignacion
                                    if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                        // Agregar el elemento a la posición correspondiente
                                        $array_informacion_eventos[$key2]->Resultado = $item1['ContJuntasOriResultado'];
                                        break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                    }
                                }
                            }                            
                        }
                    }
                    // Resultado Controversia Pcl Juntas
                    $posicionJuntasConPcl = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_Juntas && $element['Id_Servicio'] == $servicio_JuntasConPcl) {
                            $posicionJuntasConPcl[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }                
                    if (count($posicionJuntasConPcl) > 0) {                    
                        $resultadoJuntasConPcl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_controversia_juntas_eventos as sicje')                    
                        ->select('ID_evento','Id_Asignacion',
                            DB::raw("CASE WHEN n_dictamen_jrci_emitido IS NULL THEN 'Vacio' ELSE n_dictamen_jrci_emitido END AS n_dictamen_jrci_emitido"),
                            'sicje.porcentaje_pcl_jrci_emitido',
                            'sicje.Decision_dictamen_jrci',
                            DB::raw("CASE WHEN n_dictamen_reposicion_jrci IS NULL THEN 'Vacio' ELSE n_dictamen_reposicion_jrci END AS n_dictamen_reposicion_jrci"),
                            'sicje.porcentaje_pcl_reposicion_jrci',
                            'sicje.Decision_dictamen_repo_jrci',
                            DB::raw("CASE WHEN n_dictamen_jnci_emitido IS NULL THEN 'Vacio' ELSE n_dictamen_jnci_emitido END AS n_dictamen_jnci_emitido"),
                            'sicje.porcentaje_pcl_jnci_emitido');
                        foreach ($posicionJuntasConPcl as $item) {
                            $resultadoJuntasConPcl->orWhere([
                                ['Id_Asignacion',$item['Id_Asignacion']], 
                                ['Id_proceso',$item['Id_proceso']], 
                                ['ID_evento',$item['ID_evento']]
                            ]);
                        }
                        $resulJuntasConPcl = $resultadoJuntasConPcl->get();
                        
                        if (count($resulJuntasConPcl) > 0) {
                            $ArrayresulJuntasConPcl = $resulJuntasConPcl->toArray();                                                                         
                            $cantidadArrayresulJuntasConPcl = count($ArrayresulJuntasConPcl) - 1;
                            for ($i=0; $i <= $cantidadArrayresulJuntasConPcl ; $i++) {                             
                                // variables JRCI Emitido
                                $Jrci_1_Resultado = $ArrayresulJuntasConPcl[$i]->n_dictamen_jrci_emitido; 
                                $porcentaje_pcl_jrci_emitido = $ArrayresulJuntasConPcl[$i]->porcentaje_pcl_jrci_emitido;                 
                                $Decision_dictamen_jrci = $ArrayresulJuntasConPcl[$i]->Decision_dictamen_jrci;
                                // Variables JRCI Reposicion
                                $Jrci_2_Resultado = $ArrayresulJuntasConPcl[$i]->n_dictamen_reposicion_jrci;
                                $porcentaje_pcl_reposicion_jrci = $ArrayresulJuntasConPcl[$i]->porcentaje_pcl_reposicion_jrci;
                                $Decision_dictamen_repo_jrci = $ArrayresulJuntasConPcl[$i]->Decision_dictamen_repo_jrci;
                                // Variables JNCI Emitido                   
                                $Jnci_Resultado = $ArrayresulJuntasConPcl[$i]->n_dictamen_jnci_emitido;
                                $porcentaje_pcl_jnci_emitido = $ArrayresulJuntasConPcl[$i]->porcentaje_pcl_jnci_emitido;
                                $IdAsignacionResultado = $ArrayresulJuntasConPcl[$i]->Id_Asignacion;
                                $ID_eventoResultado = $ArrayresulJuntasConPcl[$i]->ID_evento;
            
                                if ($Jnci_Resultado != 'Vacio') {
                                    foreach ($posicionJuntasConPcl as &$elemento) {
                                        // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                        if ($elemento['Id_Asignacion'] == $IdAsignacionResultado) {
                                            // Agregar $OrigenResultado al array
                                            $elemento['ContJuntasPclResultado'] = 'JNCI_'.$porcentaje_pcl_jnci_emitido;
                                        }
                                    }                                
                                }elseif ($Jrci_2_Resultado != 'Vacio') {
                                    foreach ($posicionJuntasConPcl as &$elemento) {
                                        // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                        if ($elemento['Id_Asignacion'] == $IdAsignacionResultado) {
                                            // Agregar $OrigenResultado al array
                                            $elemento['ContJuntasPclResultado'] = 'JRCI_'.$porcentaje_pcl_reposicion_jrci.'_'.$Decision_dictamen_repo_jrci;
                                        }
                                    }                                
                                }elseif ($Jrci_1_Resultado != 'Vacio') {
                                    foreach ($posicionJuntasConPcl as &$elemento) {
                                        // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                        if ($elemento['Id_Asignacion'] == $IdAsignacionResultado) {
                                            // Agregar $OrigenResultado al array
                                            $elemento['ContJuntasPclResultado'] = 'JRCI_'.$porcentaje_pcl_jrci_emitido.'_'.$Decision_dictamen_jrci;
                                        }
                                    }
                                }
                            }   
                            // Filtrar los elementos que contienen [OrigenDtoResultado]
                            $posicionJuntasConPclFiltrado = array_filter($posicionJuntasConPcl, function ($item) {
                                return isset($item['ContJuntasPclResultado']);
                            }); 
                            // Reorganizar los índices del array filtrado
                            $posicionJuntasConPclFiltrado = array_values($posicionJuntasConPclFiltrado);    
                            //Combinar el array object con el array posicionJuntasConPcl
                            foreach ($array_informacion_eventos as $key2 => $item2) {
                                foreach ($posicionJuntasConPclFiltrado as $item1) {
                                    // Verificar si hay coincidencia en Id_Asignacion
                                    if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                        // Agregar el elemento a la posición correspondiente
                                        $array_informacion_eventos[$key2]->Resultado = $item1['ContJuntasPclResultado'];
                                        break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                    }
                                }
                            }                     
                        }
                    }               
                    return response()->json($array_informacion_eventos);
                }else{
                    $mensajes = array(
                        "parametro" => 'sin_datos',
                        "mensaje" => 'No se encontraron datos acorde a la búsqueda realizada.'
                    );
                    return json_decode(json_encode($mensajes, true));
                }
            break;
            case (!empty($consultar_id_evento) and empty($consultar_nro_identificacion)):
                $informacion_eventos = cndatos_eventos::on('sigmel_gestiones')
                    ->where('ID_evento', $consultar_id_evento)
                    ->orderBy('ID_evento', 'desc')
                    ->get();
                $array_informacion_eventos = json_decode(json_encode($informacion_eventos, true));                   
                if(count($array_informacion_eventos)>0){
                    foreach ($array_informacion_eventos as $model) {
                        $resultArrayEventos[] = [
                            'ID_evento' => $model->ID_evento,
                            'Id_proceso' => $model->Id_proceso,
                            'Nombre_proceso' => $model->Nombre_proceso,
                            'Id_Servicio' => $model->Id_Servicio,
                            'Nombre_servicio' => $model->Nombre_servicio,
                            'Id_Asignacion' => $model->Id_Asignacion,
                        ];
                    }                                   
                    // Resultado DTO Origen
                    
                    $posicionOrigenDto = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_origen && $element['Id_Servicio'] == $servicio_OrigenDto && $element['ID_evento'] == $consultar_id_evento) {
                            $posicionOrigenDto[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }   
                                                    
                    if (count($posicionOrigenDto) > 0) {
                        $ID_eventoDto = $posicionOrigenDto[0]['ID_evento'];
                        $Id_procesoDto = $posicionOrigenDto[0]['Id_proceso'];
                        $Id_ServicioDto = $posicionOrigenDto[0]['Id_Servicio'];
                        $Id_AsignacionDto = $posicionOrigenDto[0]['Id_Asignacion'];
                        
                        $resultadoDtoOrigen = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_dto_atel_eventos as sidae')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sidae.Origen')
                        ->select('sidae.ID_evento','sidae.Id_Asignacion','sidae.Origen', 'slp.Nombre_parametro')
                        ->where([['sidae.Id_Asignacion',$Id_AsignacionDto], ['sidae.Id_proceso',$Id_procesoDto], ['sidae.ID_evento',$ID_eventoDto]])
                        ->get(); 
        
                        if (count($resultadoDtoOrigen)>0) {
                            $OrigenDtoResultado = $resultadoDtoOrigen[0]->Nombre_parametro;
                            $IdAsignacionResultado = $resultadoDtoOrigen[0]->Id_Asignacion;
                            $ID_eventoResultado = $resultadoDtoOrigen[0]->ID_evento;
            
                            foreach ($posicionOrigenDto as &$elemento) {
                                // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                if ($elemento['Id_Asignacion'] == $IdAsignacionResultado && $elemento['ID_evento'] == $ID_eventoResultado) {
                                    // Agregar $OrigenResultado al array
                                    $elemento['OrigenDtoResultado'] = $OrigenDtoResultado;
                                }
                            }
                           
                            // Filtrar los elementos que contienen [OrigenDtoResultado]
                            $posicionOrigenDtoFiltrado = array_filter($posicionOrigenDto, function ($item) {
                                return isset($item['OrigenDtoResultado']);
                            });
                            
                            // Reorganizar los índices del array filtrado
                            $posicionOrigenDtoFiltrado = array_values($posicionOrigenDtoFiltrado);                                                                                   
                            
                            //Combinar el array object con el array 
                            foreach ($array_informacion_eventos as $key2 => $item2) {
                                foreach ($posicionOrigenDtoFiltrado as $item1) {
                                    // Verificar si hay coincidencia en Id_Asignacion
                                    if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                        // Agregar el elemento a la posición correspondiente
                                        $array_informacion_eventos[$key2]->Resultado = $item1['OrigenDtoResultado'];
                                        break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                    }
                                }
                            }                            
                        } 
                    }                                           
                    // // Resultado Adicion DX Origen                    
                    $posicionOrigenAdx = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_origen && $element['Id_Servicio'] == $servicio_OrigenAdx && $element['ID_evento'] == $consultar_id_evento) {
                            $posicionOrigenAdx[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }    
                               
                    if (count($posicionOrigenAdx) > 0) {
                        $ID_eventoAdx = $posicionOrigenAdx[0]['ID_evento'];
                        $Id_procesoAdx = $posicionOrigenAdx[0]['Id_proceso'];
                        $Id_ServicioAdx = $posicionOrigenAdx[0]['Id_Servicio'];
                        $Id_AsignacionAdx = $posicionOrigenAdx[0]['Id_Asignacion'];
    
                        $resultadoAdxOrigen = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
                        ->select('side.ID_evento','side.Id_Asignacion','side.CIE10', 'slcd.CIE10 as CodigoCIE', 'side.Nombre_CIE10')
                        ->where([['side.Id_Asignacion',$Id_AsignacionAdx], ['side.Id_proceso',$Id_procesoAdx], ['side.ID_evento',$ID_eventoAdx]])
                        ->whereNotNull('F_adicion_CIE10')
                        ->get(); 
                        
                        if (count($resultadoAdxOrigen) > 0) {
                            foreach ($resultadoAdxOrigen as $item) {
                                $idEvento = $item->ID_evento;
                                $idAsignacion = $item->Id_Asignacion;
                                $codigoCIE = $item->CodigoCIE;                       
                                // Buscar la clave correspondiente en $posicionOrigenAdx
                                $clave = null;
                                foreach ($posicionOrigenAdx as $indice => $elemento) {
                                    if ($elemento['ID_evento'] == $idEvento && $elemento['Id_Asignacion'] == $idAsignacion) {
                                        $clave = $indice;
                                        break;
                                    }
                                }
                                // Si se encuentra la clave, agregar el CodigoCIE al array existente
                                if ($clave !== null) {
                                    if (!isset($posicionOrigenAdx[$clave]['OrigenCieResultado'])) {
                                        $posicionOrigenAdx[$clave]['OrigenCieResultado'] = $codigoCIE;
                                    } else {
                                        $posicionOrigenAdx[$clave]['OrigenCieResultado'] .= ', ' . $codigoCIE;
                                    }
                                }
                                
                            }   
                            
                            // Filtrar los elementos que contienen [OrigenCieResultado]
                            $posicionOrigenAdxFiltrado = array_filter($posicionOrigenAdx, function ($item) {
                                return isset($item['OrigenCieResultado']);
                            });
                            
                            // Reorganizar los índices del array filtrado
                            $posicionOrigenAdxFiltrado = array_values($posicionOrigenAdxFiltrado);                                                                                   
                            
                            //Combinar el array object con el array posicionOrigenAdx
                            foreach ($array_informacion_eventos as $key2 => $item2) {
                                foreach ($posicionOrigenAdxFiltrado as $item1) {
                                    // Verificar si hay coincidencia en Id_Asignacion
                                    if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                        // Agregar el elemento a la posición correspondiente
                                        $array_informacion_eventos[$key2]->Resultado = $item1['OrigenCieResultado'];
                                        break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                    }
                                }
                            }                            
                        }
                        
                    }  
                       
                    // Resultado Pronunciamiento Origen
                    $posicionOrigenPron = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_origen && $element['Id_Servicio'] == $servicio_OrigenPron && $element['ID_evento'] == $consultar_id_evento) {
                            $posicionOrigenPron[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }
                    if (count($posicionOrigenPron) > 0) {
                        $ID_eventoPron = $posicionOrigenPron[0]['ID_evento'];
                        $Id_procesoPron = $posicionOrigenPron[0]['Id_proceso'];
                        $Id_ServicioPron = $posicionOrigenPron[0]['Id_Servicio'];
                        $Id_AsignacionPron = $posicionOrigenPron[0]['Id_Asignacion'];
    
                        $resultadoPronOrigen = sigmel_informacion_pronunciamiento_eventos::on('sigmel_gestiones')
                        ->select('ID_evento','Id_Asignacion','Decision')
                        ->where([['Id_Asignacion',$Id_AsignacionPron], ['Id_proceso',$Id_procesoPron], ['ID_evento',$ID_eventoPron]])
                        ->get(); 
                        if (count($resultadoPronOrigen) > 0) {
                            $DecisionProResultado = $resultadoPronOrigen[0]->Decision;
                            $IdAsignacionResultado = $resultadoPronOrigen[0]->Id_Asignacion;
                            $ID_eventoResultado = $resultadoPronOrigen[0]->ID_evento;
            
                            foreach ($posicionOrigenPron as &$elemento) {
                                // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                if ($elemento['Id_Asignacion'] == $IdAsignacionResultado && $elemento['ID_evento'] == $ID_eventoResultado) {
                                    // Agregar $OrigenResultado al array
                                    $elemento['DecisionProResultado'] = $DecisionProResultado;
                                }
                            }
                            // Filtrar los elementos que contienen [DecisionProResultado]
                            $posicionOrigenPronFiltrado = array_filter($posicionOrigenPron, function ($item) {
                                return isset($item['DecisionProResultado']);
                            }); 
                            // Reorganizar los índices del array filtrado
                            $posicionOrigenPronFiltrado = array_values($posicionOrigenPronFiltrado);                                                                            
                            //Combinar el array object con el array 
                            foreach ($array_informacion_eventos as $key2 => $item2) {
                                foreach ($posicionOrigenPronFiltrado as $item1) {
                                    // Verificar si hay coincidencia en Id_Asignacion
                                    if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                        // Agregar el elemento a la posición correspondiente
                                        $array_informacion_eventos[$key2]->Resultado = $item1['DecisionProResultado'];
                                        break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                    }
                                }
                            }                            
                        }
                        
                    }
                    // Resultado Calificacion Tecnica Pcl
                    $posicionPclCali = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_pcl && $element['Id_Servicio'] == $servicio_PclCali && $element['ID_evento'] == $consultar_id_evento) {
                            $posicionPclCali[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }
                    if (count($posicionPclCali) > 0) {
                        $ID_eventoCali = $posicionPclCali[0]['ID_evento'];
                        $Id_procesoCali = $posicionPclCali[0]['Id_proceso'];
                        $Id_ServicioCali = $posicionPclCali[0]['Id_Servicio'];
                        $Id_AsignacionCali = $posicionPclCali[0]['Id_Asignacion'];
    
                        $resultadoCaliPcl = sigmel_informacion_decreto_eventos::on('sigmel_gestiones')
                        ->select('ID_Evento','Id_Asignacion','Porcentaje_pcl')
                        ->where([['Id_Asignacion',$Id_AsignacionCali], ['Id_proceso',$Id_procesoCali], ['ID_Evento',$ID_eventoCali]])
                        ->get(); 
                        if (count($resultadoCaliPcl) > 0) {
                            $ProcentajePClCaliResultado = $resultadoCaliPcl[0]->Porcentaje_pcl;
                            $IdAsignacionResultado = $resultadoCaliPcl[0]->Id_Asignacion;
                            $ID_eventoResultado = $resultadoCaliPcl[0]->ID_Evento;
            
                            foreach ($posicionPclCali as &$elemento) {
                                // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                if ($elemento['Id_Asignacion'] == $IdAsignacionResultado && $elemento['ID_evento'] == $ID_eventoResultado) {
                                    // Agregar $OrigenResultado al array
                                    $elemento['ProcentajePClCaliResultado'] = $ProcentajePClCaliResultado;
                                }
                            }
                            // Filtrar los elementos que contienen [ProcentajePClCaliResultado]
                            $posicionPclCaliFiltrado = array_filter($posicionPclCali, function ($item) {
                                return isset($item['ProcentajePClCaliResultado']);
                            }); 
                            // Reorganizar los índices del array filtrado
                            $posicionPclCaliFiltrado = array_values($posicionPclCaliFiltrado);
                            //Combinar el array object con el array 
                            foreach ($array_informacion_eventos as $key2 => $item2) {
                                foreach ($posicionPclCaliFiltrado as $item1) {
                                    // Verificar si hay coincidencia en Id_Asignacion
                                    if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                        // Agregar el elemento a la posición correspondiente
                                        $array_informacion_eventos[$key2]->Resultado = $item1['ProcentajePClCaliResultado'];
                                        break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                    }
                                }
                            }                            
                        }                        
                    }
                    // Resultado Recalificacion Pcl
                    $posicionPclReca = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_pcl && $element['Id_Servicio'] == $servicio_PclReca && $element['ID_evento'] == $consultar_id_evento) {
                            $posicionPclReca[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }
                    if (count($posicionPclReca) > 0) {
                        
                        $resultadoRecaPcl =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
                        ->select('side.ID_Evento','side.Id_Asignacion','side.Porcentaje_pcl');
                        foreach ($posicionPclReca as $item) {
                            $resultadoRecaPcl->orWhere([
                                ['side.Id_Asignacion', $item['Id_Asignacion']],
                                ['side.Id_proceso', $item['Id_proceso']],
                                ['side.ID_evento', $item['ID_evento']]
                            ]);
                        }
                        $resulRecaPcl = $resultadoRecaPcl->get();                                        
                        if (count($resulRecaPcl) > 0) {
                            $ArrayresulRecaPcl = $resulRecaPcl->toArray();                                                 
                            foreach ($posicionPclReca as &$item) {
                                // Buscar el elemento correspondiente en los resultados de la consulta
                                $resultado = array_filter($ArrayresulRecaPcl, function ($result) use ($item) {
                                    return $result->Id_Asignacion == $item['Id_Asignacion'];
                                });                    
                                // Si se encuentra una coincidencia, agregar la información al array original
                                if (!empty($resultado)) {
                                    $resultado = reset($resultado); // Obtener el primer elemento del array de resultados
                                    $item['ProcentajePClRecaResultado'] = $resultado->Porcentaje_pcl;
                                } 
                            }                          
                            // Filtrar los elementos que contienen [ProcentajePClRecaResultado]
                            $posicionPclRecaFiltrado = array_filter($posicionPclReca, function ($item) {
                                return isset($item['ProcentajePClRecaResultado']);
                            }); 
                            // Reorganizar los índices del array filtrado
                            $posicionPclRecaFiltrado = array_values($posicionPclRecaFiltrado);
                            //Combinar el array object con el array 
                            foreach ($array_informacion_eventos as $key2 => $item2) {
                                foreach ($posicionPclRecaFiltrado as $item1) {
                                    // Verificar si hay coincidencia en Id_Asignacion
                                    if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                        // Agregar el elemento a la posición correspondiente
                                        $array_informacion_eventos[$key2]->Resultado = $item1['ProcentajePClRecaResultado'];
                                        break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                    }
                                }
                            }
                        }                    
                        
                    }                    
                    // Resultado Revision Pension Pcl
                    $posicionPclRevi = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_pcl && $element['Id_Servicio'] == $servicio_PclRevi && $element['ID_evento'] == $consultar_id_evento) {
                            $posicionPclRevi[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }
                    if (count($posicionPclRevi) > 0) {
                        $ID_eventoRevi = $posicionPclRevi[0]['ID_evento'];
                        $Id_procesoRevi = $posicionPclRevi[0]['Id_proceso'];
                        $Id_ServicioRevi = $posicionPclRevi[0]['Id_Servicio'];
                        $Id_AsignacionRevi = $posicionPclRevi[0]['Id_Asignacion'];
    
                        $resultadoReviPcl = sigmel_informacion_decreto_eventos::on('sigmel_gestiones')
                        ->select('ID_Evento','Id_Asignacion','Porcentaje_pcl')
                        ->where([['Id_Asignacion',$Id_AsignacionRevi], ['Id_proceso',$Id_procesoRevi], ['ID_Evento',$ID_eventoRevi]])
                        ->get(); 
                        if (count($resultadoReviPcl) > 0) {
                            $ProcentajePClReviResultado = $resultadoReviPcl[0]->Porcentaje_pcl;
                            $IdAsignacionResultado = $resultadoReviPcl[0]->Id_Asignacion;
                            $ID_eventoResultado = $resultadoReviPcl[0]->ID_Evento;
            
                            foreach ($posicionPclRevi as &$elemento) {
                                // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                if ($elemento['Id_Asignacion'] == $IdAsignacionResultado && $elemento['ID_evento'] == $ID_eventoResultado) {
                                    // Agregar $OrigenResultado al array
                                    $elemento['ProcentajePClReviResultado'] = $ProcentajePClReviResultado;
                                }
                            }
                            // Filtrar los elementos que contienen [ProcentajePClReviResultado]
                            $posicionPclReviFiltrado = array_filter($posicionPclRevi, function ($item) {
                                return isset($item['ProcentajePClReviResultado']);
                            }); 
                            // Reorganizar los índices del array filtrado
                            $posicionPclReviFiltrado = array_values($posicionPclReviFiltrado);
                            //Combinar el array object con el array 
                            foreach ($array_informacion_eventos as $key2 => $item2) {
                                foreach ($posicionPclReviFiltrado as $item1) {
                                    // Verificar si hay coincidencia en Id_Asignacion
                                    if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                        // Agregar el elemento a la posición correspondiente
                                        $array_informacion_eventos[$key2]->Resultado = $item1['ProcentajePClReviResultado'];
                                        break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                    }
                                }
                            }                            
                        }                        
                    }
                    // Resultado Pronunciamiento Pcl
                    $posicionPclPron = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_pcl && $element['Id_Servicio'] == $servicio_PclPron && $element['ID_evento'] == $consultar_id_evento) {
                            $posicionPclPron[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }
                    if (count($posicionPclPron) > 0) {
                        $ID_eventoPron = $posicionPclPron[0]['ID_evento'];
                        $Id_procesoPron = $posicionPclPron[0]['Id_proceso'];
                        $Id_ServicioPron = $posicionPclPron[0]['Id_Servicio'];
                        $Id_AsignacionPron = $posicionPclPron[0]['Id_Asignacion'];
    
                        $resultadoPronPcl = sigmel_informacion_pronunciamiento_eventos::on('sigmel_gestiones')
                        ->select('ID_evento','Id_Asignacion','Decision')
                        ->where([['Id_Asignacion',$Id_AsignacionPron], ['Id_proceso',$Id_procesoPron], ['ID_evento',$ID_eventoPron]])
                        ->get(); 
                        if (count($resultadoPronPcl) > 0) {
                            $DecisionProResultado = $resultadoPronPcl[0]->Decision;
                            $IdAsignacionResultado = $resultadoPronPcl[0]->Id_Asignacion;
                            $ID_eventoResultado = $resultadoPronPcl[0]->ID_evento;
            
                            foreach ($posicionPclPron as &$elemento) {
                                // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                if ($elemento['Id_Asignacion'] == $IdAsignacionResultado && $elemento['ID_evento'] == $ID_eventoResultado) {
                                    // Agregar $OrigenResultado al array
                                    $elemento['DecisionProResultado'] = $DecisionProResultado;
                                }
                            }
                            // Filtrar los elementos que contienen [DecisionProResultado]
                            $posicionPclPronFiltrado = array_filter($posicionPclPron, function ($item) {
                                return isset($item['DecisionProResultado']);
                            }); 
                            // Reorganizar los índices del array filtrado
                            $posicionPclPronFiltrado = array_values($posicionPclPronFiltrado);
                            //Combinar el array object con el array 
                            foreach ($array_informacion_eventos as $key2 => $item2) {
                                foreach ($posicionPclPronFiltrado as $item1) {
                                    // Verificar si hay coincidencia en Id_Asignacion
                                    if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                        // Agregar el elemento a la posición correspondiente
                                        $array_informacion_eventos[$key2]->Resultado = $item1['DecisionProResultado'];
                                        break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                    }
                                }
                            }                            
                        }                        
                    }
                    // Resultado Controversia Origen Juntas
                    $posicionJuntasConOri = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_Juntas && $element['Id_Servicio'] == $servicio_JuntasConOri && $element['ID_evento'] == $consultar_id_evento) {
                            $posicionJuntasConOri[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }
                    if (count($posicionJuntasConOri) > 0) {
                        $ID_eventoConOri = $posicionJuntasConOri[0]['ID_evento'];
                        $Id_procesoConOri = $posicionJuntasConOri[0]['Id_proceso'];
                        $Id_ServicioConOri = $posicionJuntasConOri[0]['Id_Servicio'];
                        $Id_AsignacionConOri = $posicionJuntasConOri[0]['Id_Asignacion'];
                        $resultadoJuntasConOri = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_controversia_juntas_eventos as sicje')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slpa', 'slpa.Id_Parametro', '=', 'sicje.Origen_jnci_emitido')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slpara', 'slpara.Id_Parametro', '=', 'sicje.Origen_reposicion_jrci')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sicje.Origen_jrci_emitido')
                        ->select('ID_evento','Id_Asignacion',
                            DB::raw("CASE WHEN n_dictamen_jrci_emitido IS NULL THEN 'Vacio' ELSE n_dictamen_jrci_emitido END AS n_dictamen_jrci_emitido"),
                            'sicje.Origen_jrci_emitido',
                            'slp.Nombre_parametro as OrigenDxJRCIemitido',
                            'sicje.Decision_dictamen_jrci',
                            DB::raw("CASE WHEN n_dictamen_reposicion_jrci IS NULL THEN 'Vacio' ELSE n_dictamen_reposicion_jrci END AS n_dictamen_reposicion_jrci"),
                            'sicje.Origen_reposicion_jrci',
                            'slpara.Nombre_parametro as OrigenDxJRCIreposicion',
                            'sicje.Decision_dictamen_repo_jrci',
                            DB::raw("CASE WHEN n_dictamen_jnci_emitido IS NULL THEN 'Vacio' ELSE n_dictamen_jnci_emitido END AS n_dictamen_jnci_emitido"),
                            'sicje.Origen_jnci_emitido',
                            'slpa.Nombre_parametro as OrigenDxJNCIemitido')
                        ->where([['Id_Asignacion',$Id_AsignacionConOri], ['Id_proceso',$Id_procesoConOri], ['ID_evento',$ID_eventoConOri]])                    
                        ->get(); 
                        if (count($resultadoJuntasConOri) > 0) {
                            // variables JRCI Emitido
                            $Jrci_1_Resultado = $resultadoJuntasConOri[0]->n_dictamen_jrci_emitido; 
                            $OrigenDxJRCIemitido = $resultadoJuntasConOri[0]->OrigenDxJRCIemitido;                 
                            $Decision_dictamen_jrci = $resultadoJuntasConOri[0]->Decision_dictamen_jrci;
                            // Variables JRCI Reposicion
                            $Jrci_2_Resultado = $resultadoJuntasConOri[0]->n_dictamen_reposicion_jrci;
                            $OrigenDxJRCIreposicion = $resultadoJuntasConOri[0]->OrigenDxJRCIreposicion;
                            $Decision_dictamen_repo_jrci = $resultadoJuntasConOri[0]->Decision_dictamen_repo_jrci;
                            // Variables JNCI Emitido                   
                            $Jnci_Resultado = $resultadoJuntasConOri[0]->n_dictamen_jnci_emitido;
                            $OrigenDxJNCIemitido = $resultadoJuntasConOri[0]->OrigenDxJNCIemitido;
                            $IdAsignacionResultado = $resultadoJuntasConOri[0]->Id_Asignacion;
                            $ID_eventoResultado = $resultadoJuntasConOri[0]->ID_evento;
        
                            if ($Jnci_Resultado != 'Vacio') {
                                foreach ($posicionJuntasConOri as &$elemento) {
                                    // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                    if ($elemento['Id_Asignacion'] == $IdAsignacionResultado && $elemento['ID_evento'] == $ID_eventoResultado) {
                                        // Agregar $OrigenResultado al array
                                        $elemento['ContJuntasOriResultado'] = 'JNCI_'.$OrigenDxJNCIemitido;
                                    }
                                }
                                // Filtrar los elementos que contienen [ContJuntasOriResultado]
                                $posicionJuntasConOriFiltrado = array_filter($posicionJuntasConOri, function ($item) {
                                    return isset($item['ContJuntasOriResultado']);
                                }); 
                                // Reorganizar los índices del array filtrado
                                $posicionJuntasConOriFiltrado = array_values($posicionJuntasConOriFiltrado);                                                        
                                //Combinar el array object con el array posicionJuntasConOri
                                foreach ($array_informacion_eventos as $key2 => $item2) {
                                    foreach ($posicionJuntasConOriFiltrado as $item1) {
                                        // Verificar si hay coincidencia en Id_Asignacion
                                        if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                            // Agregar el elemento a la posición correspondiente
                                            $array_informacion_eventos[$key2]->Resultado = $item1['ContJuntasOriResultado'];
                                            break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                        }
                                    }
                                }
                            }elseif ($Jrci_2_Resultado != 'Vacio') {
                                foreach ($posicionJuntasConOri as &$elemento) {
                                    // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                    if ($elemento['Id_Asignacion'] == $IdAsignacionResultado && $elemento['ID_evento'] == $ID_eventoResultado) {
                                        // Agregar $OrigenResultado al array
                                        $elemento['ContJuntasOriResultado'] = 'JRCI_'.$OrigenDxJRCIreposicion.'_'.$Decision_dictamen_repo_jrci;
                                    }
                                }
                                // Filtrar los elementos que contienen [ContJuntasOriResultado]
                                $posicionJuntasConOriFiltrado = array_filter($posicionJuntasConOri, function ($item) {
                                    return isset($item['ContJuntasOriResultado']);
                                }); 
                                // Reorganizar los índices del array filtrado
                                $posicionJuntasConOriFiltrado = array_values($posicionJuntasConOriFiltrado);                                                        
                                //Combinar el array object con el array posicionJuntasConOri                                
                                foreach ($array_informacion_eventos as $key2 => $item2) {
                                    foreach ($posicionJuntasConOriFiltrado as $item1) {
                                        // Verificar si hay coincidencia en Id_Asignacion
                                        if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                            // Agregar el elemento a la posición correspondiente
                                            $array_informacion_eventos[$key2]->Resultado = $item1['ContJuntasOriResultado'];
                                            break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                        }
                                    }
                                }
                            }elseif ($Jrci_1_Resultado != 'Vacio') {
                                foreach ($posicionJuntasConOri as &$elemento) {
                                    // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                    if ($elemento['Id_Asignacion'] == $IdAsignacionResultado && $elemento['ID_evento'] == $ID_eventoResultado) {
                                        // Agregar $OrigenResultado al array
                                        $elemento['ContJuntasOriResultado'] = 'JRCI_'.$OrigenDxJRCIemitido.'_'.$Decision_dictamen_jrci;
                                    }
                                }
                                // Filtrar los elementos que contienen [ContJuntasOriResultado]
                                $posicionJuntasConOriFiltrado = array_filter($posicionJuntasConOri, function ($item) {
                                    return isset($item['ContJuntasOriResultado']);
                                }); 
                                // Reorganizar los índices del array filtrado
                                $posicionJuntasConOriFiltrado = array_values($posicionJuntasConOriFiltrado);                                                        
                                //Combinar el array object con el array posicionJuntasConOri                                 
                                foreach ($array_informacion_eventos as $key2 => $item2) {
                                    foreach ($posicionJuntasConOriFiltrado as $item1) {
                                        // Verificar si hay coincidencia en Id_Asignacion
                                        if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                            // Agregar el elemento a la posición correspondiente
                                            $array_informacion_eventos[$key2]->Resultado = $item1['ContJuntasOriResultado'];
                                            break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                        }
                                    }
                                }
                            }
                            
                        }
                    }
                    // Resultado Controversia Pcl Juntas
                    $posicionJuntasConPcl = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_Juntas && $element['Id_Servicio'] == $servicio_JuntasConPcl && $element['ID_evento'] == $consultar_id_evento) {
                            $posicionJuntasConPcl[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }
                    if (count($posicionJuntasConPcl) > 0) {
                        $ID_eventoConPcl = $posicionJuntasConPcl[0]['ID_evento'];
                        $Id_procesoConPcl = $posicionJuntasConPcl[0]['Id_proceso'];
                        $Id_ServicioConPcl = $posicionJuntasConPcl[0]['Id_Servicio'];
                        $Id_AsignacionConPcl = $posicionJuntasConPcl[0]['Id_Asignacion'];
                        $resultadoJuntasConPcl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_controversia_juntas_eventos as sicje')                    
                        ->select('ID_evento','Id_Asignacion',
                            DB::raw("CASE WHEN n_dictamen_jrci_emitido IS NULL THEN 'Vacio' ELSE n_dictamen_jrci_emitido END AS n_dictamen_jrci_emitido"),
                            'sicje.porcentaje_pcl_jrci_emitido',
                            'sicje.Decision_dictamen_jrci',
                            DB::raw("CASE WHEN n_dictamen_reposicion_jrci IS NULL THEN 'Vacio' ELSE n_dictamen_reposicion_jrci END AS n_dictamen_reposicion_jrci"),
                            'sicje.porcentaje_pcl_reposicion_jrci',
                            'sicje.Decision_dictamen_repo_jrci',
                            DB::raw("CASE WHEN n_dictamen_jnci_emitido IS NULL THEN 'Vacio' ELSE n_dictamen_jnci_emitido END AS n_dictamen_jnci_emitido"),
                            'sicje.porcentaje_pcl_jnci_emitido')
                        ->where([['Id_Asignacion',$Id_AsignacionConPcl], ['Id_proceso',$Id_procesoConPcl], ['ID_evento',$ID_eventoConPcl]])                    
                        ->get(); 
                        if (count($resultadoJuntasConPcl) > 0) {
                            // variables JRCI Emitido
                            $Jrci_1_Resultado = $resultadoJuntasConPcl[0]->n_dictamen_jrci_emitido; 
                            $porcentaje_pcl_jrci_emitido = $resultadoJuntasConPcl[0]->porcentaje_pcl_jrci_emitido;                 
                            $Decision_dictamen_jrci = $resultadoJuntasConPcl[0]->Decision_dictamen_jrci;
                            // Variables JRCI Reposicion
                            $Jrci_2_Resultado = $resultadoJuntasConPcl[0]->n_dictamen_reposicion_jrci;
                            $porcentaje_pcl_reposicion_jrci = $resultadoJuntasConPcl[0]->porcentaje_pcl_reposicion_jrci;
                            $Decision_dictamen_repo_jrci = $resultadoJuntasConPcl[0]->Decision_dictamen_repo_jrci;
                            // Variables JNCI Emitido                   
                            $Jnci_Resultado = $resultadoJuntasConPcl[0]->n_dictamen_jnci_emitido;
                            $porcentaje_pcl_jnci_emitido = $resultadoJuntasConPcl[0]->porcentaje_pcl_jnci_emitido;
                            $IdAsignacionResultado = $resultadoJuntasConPcl[0]->Id_Asignacion;
                            $ID_eventoResultado = $resultadoJuntasConPcl[0]->ID_evento;
        
                            if ($Jnci_Resultado != 'Vacio') {
                                foreach ($posicionJuntasConPcl as &$elemento) {
                                    // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                    if ($elemento['Id_Asignacion'] == $IdAsignacionResultado && $elemento['ID_evento'] == $ID_eventoResultado) {
                                        // Agregar $OrigenResultado al array
                                        $elemento['ContJuntasPclResultado'] = 'JNCI_'.$porcentaje_pcl_jnci_emitido;
                                    }
                                }
                                // Filtrar los elementos que contienen [ContJuntasPclResultado]
                                $posicionJuntasConPclFiltrado = array_filter($posicionJuntasConPcl, function ($item) {
                                    return isset($item['ContJuntasPclResultado']);
                                }); 
                                // Reorganizar los índices del array filtrado
                                $posicionJuntasConPclFiltrado = array_values($posicionJuntasConPclFiltrado);    
                                //Combinar el array object con el array posicionJuntasConPcl
                                foreach ($array_informacion_eventos as $key2 => $item2) {
                                    foreach ($posicionJuntasConPclFiltrado as $item1) {
                                        // Verificar si hay coincidencia en Id_Asignacion
                                        if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                            // Agregar el elemento a la posición correspondiente
                                            $array_informacion_eventos[$key2]->Resultado = $item1['ContJuntasPclResultado'];
                                            break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                        }
                                    }
                                }
                            }elseif ($Jrci_2_Resultado != 'Vacio') {
                                foreach ($posicionJuntasConPcl as &$elemento) {
                                    // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                    if ($elemento['Id_Asignacion'] == $IdAsignacionResultado && $elemento['ID_evento'] == $ID_eventoResultado) {
                                        // Agregar $OrigenResultado al array
                                        $elemento['ContJuntasPclResultado'] = 'JRCI_'.$porcentaje_pcl_reposicion_jrci.'_'.$Decision_dictamen_repo_jrci;
                                    }
                                }
                                // Filtrar los elementos que contienen [ContJuntasPclResultado]
                                $posicionJuntasConPclFiltrado = array_filter($posicionJuntasConPcl, function ($item) {
                                    return isset($item['ContJuntasPclResultado']);
                                }); 
                                // Reorganizar los índices del array filtrado
                                $posicionJuntasConPclFiltrado = array_values($posicionJuntasConPclFiltrado);    
                                //Combinar el array object con el array posicionJuntasConPcl
                                foreach ($array_informacion_eventos as $key2 => $item2) {
                                    foreach ($posicionJuntasConPclFiltrado as $item1) {
                                        // Verificar si hay coincidencia en Id_Asignacion
                                        if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                            // Agregar el elemento a la posición correspondiente
                                            $array_informacion_eventos[$key2]->Resultado = $item1['ContJuntasPclResultado'];
                                            break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                        }
                                    }
                                }
                            }elseif ($Jrci_1_Resultado != 'Vacio') {
                                foreach ($posicionJuntasConPcl as &$elemento) {
                                    // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                    if ($elemento['Id_Asignacion'] == $IdAsignacionResultado && $elemento['ID_evento'] == $ID_eventoResultado) {
                                        // Agregar $OrigenResultado al array
                                        $elemento['ContJuntasPclResultado'] = 'JRCI_'.$porcentaje_pcl_jrci_emitido.'_'.$Decision_dictamen_jrci;
                                    }
                                }
                                // Filtrar los elementos que contienen [ContJuntasPclResultado]
                                $posicionJuntasConPclFiltrado = array_filter($posicionJuntasConPcl, function ($item) {
                                    return isset($item['ContJuntasPclResultado']);
                                }); 
                                // Reorganizar los índices del array filtrado
                                $posicionJuntasConPclFiltrado = array_values($posicionJuntasConPclFiltrado);    
                                //Combinar el array object con el array posicionJuntasConPcl
                                foreach ($array_informacion_eventos as $key2 => $item2) {
                                    foreach ($posicionJuntasConPclFiltrado as $item1) {
                                        // Verificar si hay coincidencia en Id_Asignacion
                                        if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                            // Agregar el elemento a la posición correspondiente
                                            $array_informacion_eventos[$key2]->Resultado = $item1['ContJuntasPclResultado'];
                                            break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                        }
                                    }
                                }
                            }
                            
                        }
                    }
                    return response()->json($array_informacion_eventos);
                }else{
                    $mensajes = array(
                        "parametro" => 'sin_datos',
                        "mensaje" => 'No se encontraron datos acorde a la búsqueda realizada.'
                    );
                    return json_decode(json_encode($mensajes, true));
                }
            break;
            case (!empty($consultar_id_evento) and !empty($consultar_nro_identificacion)):
                $informacion_eventos = cndatos_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nro_identificacion', '=', $consultar_nro_identificacion],
                        ['ID_evento', '=', $consultar_id_evento]
                    ])
                    ->orderBy('ID_evento', 'desc')
                    ->get();
                $array_informacion_eventos = json_decode(json_encode($informacion_eventos, true));                
                if(count($array_informacion_eventos)>0){
                    foreach ($array_informacion_eventos as $model) {
                        $resultArrayEventos[] = [
                            'ID_evento' => $model->ID_evento,
                            'Id_proceso' => $model->Id_proceso,
                            'Nombre_proceso' => $model->Nombre_proceso,
                            'Id_Servicio' => $model->Id_Servicio,
                            'Nombre_servicio' => $model->Nombre_servicio,
                            'Id_Asignacion' => $model->Id_Asignacion,
                        ];
                    }                                   
                    // Resultado DTO Origen
                    $posicionOrigenDto = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_origen && $element['Id_Servicio'] == $servicio_OrigenDto && $element['ID_evento'] == $consultar_id_evento) {
                            $posicionOrigenDto[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }                                    
                    if (count($posicionOrigenDto) > 0) {
                        $ID_eventoDto = $posicionOrigenDto[0]['ID_evento'];
                        $Id_procesoDto = $posicionOrigenDto[0]['Id_proceso'];
                        $Id_ServicioDto = $posicionOrigenDto[0]['Id_Servicio'];
                        $Id_AsignacionDto = $posicionOrigenDto[0]['Id_Asignacion'];
                        
                        $resultadoDtoOrigen = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_dto_atel_eventos as sidae')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sidae.Origen')
                        ->select('sidae.ID_evento','sidae.Id_Asignacion','sidae.Origen', 'slp.Nombre_parametro')
                        ->where([['sidae.Id_Asignacion',$Id_AsignacionDto], ['sidae.Id_proceso',$Id_procesoDto], ['sidae.ID_evento',$ID_eventoDto]])
                        ->get(); 
        
                        if (count($resultadoDtoOrigen)>0) {
                            $OrigenDtoResultado = $resultadoDtoOrigen[0]->Nombre_parametro;
                            $IdAsignacionResultado = $resultadoDtoOrigen[0]->Id_Asignacion;
                            $ID_eventoResultado = $resultadoDtoOrigen[0]->ID_evento;
            
                            foreach ($posicionOrigenDto as &$elemento) {
                                // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                if ($elemento['Id_Asignacion'] == $IdAsignacionResultado && $elemento['ID_evento'] == $ID_eventoResultado) {
                                    // Agregar $OrigenResultado al array
                                    $elemento['OrigenDtoResultado'] = $OrigenDtoResultado;
                                }
                            }

                            // Filtrar los elementos que contienen [OrigenDtoResultado]
                            $posicionOrigenDtoFiltrado = array_filter($posicionOrigenDto, function ($item) {
                                return isset($item['OrigenDtoResultado']);
                            });
                            
                            // Reorganizar los índices del array filtrado
                            $posicionOrigenDtoFiltrado = array_values($posicionOrigenDtoFiltrado);

                            //Combinar el array object con el array 
                            foreach ($array_informacion_eventos as $key2 => $item2) {
                                foreach ($posicionOrigenDtoFiltrado as $item1) {
                                    // Verificar si hay coincidencia en Id_Asignacion
                                    if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                        // Agregar el elemento a la posición correspondiente
                                        $array_informacion_eventos[$key2]->Resultado = $item1['OrigenDtoResultado'];
                                        break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                    }
                                }
                            }                            
                        } 
                    }                       
                    // Resultado Adicion DX Origen
                    $posicionOrigenAdx = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_origen && $element['Id_Servicio'] == $servicio_OrigenAdx && $element['ID_evento'] == $consultar_id_evento) {
                            $posicionOrigenAdx[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }                 
                    if (count($posicionOrigenAdx) > 0) {
                        $ID_eventoAdx = $posicionOrigenAdx[0]['ID_evento'];
                        $Id_procesoAdx = $posicionOrigenAdx[0]['Id_proceso'];
                        $Id_ServicioAdx = $posicionOrigenAdx[0]['Id_Servicio'];
                        $Id_AsignacionAdx = $posicionOrigenAdx[0]['Id_Asignacion'];
    
                        $resultadoAdxOrigen = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
                        ->select('side.ID_evento','side.Id_Asignacion','side.CIE10', 'slcd.CIE10 as CodigoCIE', 'side.Nombre_CIE10')
                        ->where([['side.Id_Asignacion',$Id_AsignacionAdx], ['side.Id_proceso',$Id_procesoAdx], ['side.ID_evento',$ID_eventoAdx]])
                        ->whereNotNull('F_adicion_CIE10')
                        ->get(); 
                        if (count($resultadoAdxOrigen) > 0) {
                            foreach ($resultadoAdxOrigen as $item) {
                                $idEvento = $item->ID_evento;
                                $idAsignacion = $item->Id_Asignacion;
                                $codigoCIE = $item->CodigoCIE;                       
                                // Buscar la clave correspondiente en $posicionOrigenAdx
                                $clave = null;
                                foreach ($posicionOrigenAdx as $indice => $elemento) {
                                    if ($elemento['ID_evento'] == $idEvento && $elemento['Id_Asignacion'] == $idAsignacion) {
                                        $clave = $indice;
                                        break;
                                    }
                                }
                                // Si se encuentra la clave, agregar el CodigoCIE al array existente
                                if ($clave !== null) {
                                    if (!isset($posicionOrigenAdx[$clave]['OrigenCieResultado'])) {
                                        $posicionOrigenAdx[$clave]['OrigenCieResultado'] = $codigoCIE;
                                    } else {
                                        $posicionOrigenAdx[$clave]['OrigenCieResultado'] .= ', ' . $codigoCIE;
                                    }
                                }
                                
                            }
                            // Filtrar los elementos que contienen [OrigenCieResultado]
                            $posicionOrigenAdxFiltrado = array_filter($posicionOrigenAdx, function ($item) {
                                return isset($item['OrigenCieResultado']);
                            });
                            // Reorganizar los índices del array filtrado
                            $posicionOrigenAdxFiltrado = array_values($posicionOrigenAdxFiltrado);                                                                           
                            //Combinar el array object con el array posicionOrigenAdx
                            foreach ($array_informacion_eventos as $key2 => $item2) {
                                foreach ($posicionOrigenAdxFiltrado as $item1) {
                                    // Verificar si hay coincidencia en Id_Asignacion
                                    if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                        // Agregar el elemento a la posición correspondiente
                                        $array_informacion_eventos[$key2]->Resultado = $item1['OrigenCieResultado'];
                                        break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                    }
                                }
                            }                            
                        }
                        
                    }  
                    // Resultado Pronunciamiento Origen
                    $posicionOrigenPron = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_origen && $element['Id_Servicio'] == $servicio_OrigenPron && $element['ID_evento'] == $consultar_id_evento) {
                            $posicionOrigenPron[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }
                    if (count($posicionOrigenPron) > 0) {
                        $ID_eventoPron = $posicionOrigenPron[0]['ID_evento'];
                        $Id_procesoPron = $posicionOrigenPron[0]['Id_proceso'];
                        $Id_ServicioPron = $posicionOrigenPron[0]['Id_Servicio'];
                        $Id_AsignacionPron = $posicionOrigenPron[0]['Id_Asignacion'];
    
                        $resultadoPronOrigen = sigmel_informacion_pronunciamiento_eventos::on('sigmel_gestiones')
                        ->select('ID_evento','Id_Asignacion','Decision')
                        ->where([['Id_Asignacion',$Id_AsignacionPron], ['Id_proceso',$Id_procesoPron], ['ID_evento',$ID_eventoPron]])
                        ->get(); 
                        if (count($resultadoPronOrigen) > 0) {
                            $DecisionProResultado = $resultadoPronOrigen[0]->Decision;
                            $IdAsignacionResultado = $resultadoPronOrigen[0]->Id_Asignacion;
                            $ID_eventoResultado = $resultadoPronOrigen[0]->ID_evento;
            
                            foreach ($posicionOrigenPron as &$elemento) {
                                // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                if ($elemento['Id_Asignacion'] == $IdAsignacionResultado && $elemento['ID_evento'] == $ID_eventoResultado) {
                                    // Agregar $OrigenResultado al array
                                    $elemento['DecisionProResultado'] = $DecisionProResultado;
                                }
                            }
                            // Filtrar los elementos que contienen [DecisionProResultado]
                            $posicionOrigenPronFiltrado = array_filter($posicionOrigenPron, function ($item) {
                                return isset($item['DecisionProResultado']);
                            }); 
                            // Reorganizar los índices del array filtrado
                            $posicionOrigenPronFiltrado = array_values($posicionOrigenPronFiltrado);                                                                            
                                                        
                            //Combinar el array object con el array 
                            foreach ($array_informacion_eventos as $key2 => $item2) {
                                foreach ($posicionOrigenPronFiltrado as $item1) {
                                    // Verificar si hay coincidencia en Id_Asignacion
                                    if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                        // Agregar el elemento a la posición correspondiente
                                        $array_informacion_eventos[$key2]->Resultado = $item1['DecisionProResultado'];
                                        break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                    }
                                }
                            }                            
                        }
                        
                    }
                    // Resultado Calificacion Tecnica Pcl
                    $posicionPclCali = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_pcl && $element['Id_Servicio'] == $servicio_PclCali && $element['ID_evento'] == $consultar_id_evento) {
                            $posicionPclCali[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }
                    if (count($posicionPclCali) > 0) {
                        $ID_eventoCali = $posicionPclCali[0]['ID_evento'];
                        $Id_procesoCali = $posicionPclCali[0]['Id_proceso'];
                        $Id_ServicioCali = $posicionPclCali[0]['Id_Servicio'];
                        $Id_AsignacionCali = $posicionPclCali[0]['Id_Asignacion'];
    
                        $resultadoCaliPcl = sigmel_informacion_decreto_eventos::on('sigmel_gestiones')
                        ->select('ID_Evento','Id_Asignacion','Porcentaje_pcl')
                        ->where([['Id_Asignacion',$Id_AsignacionCali], ['Id_proceso',$Id_procesoCali], ['ID_Evento',$ID_eventoCali]])
                        ->get(); 
                        if (count($resultadoCaliPcl) > 0) {
                            $ProcentajePClCaliResultado = $resultadoCaliPcl[0]->Porcentaje_pcl;
                            $IdAsignacionResultado = $resultadoCaliPcl[0]->Id_Asignacion;
                            $ID_eventoResultado = $resultadoCaliPcl[0]->ID_Evento;
            
                            foreach ($posicionPclCali as &$elemento) {
                                // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                if ($elemento['Id_Asignacion'] == $IdAsignacionResultado && $elemento['ID_evento'] == $ID_eventoResultado) {
                                    // Agregar $OrigenResultado al array
                                    $elemento['ProcentajePClCaliResultado'] = $ProcentajePClCaliResultado;
                                }
                            }
                            // Filtrar los elementos que contienen [ProcentajePClCaliResultado]
                            $posicionPclCaliFiltrado = array_filter($posicionPclCali, function ($item) {
                                return isset($item['ProcentajePClCaliResultado']);
                            }); 
                            // Reorganizar los índices del array filtrado
                            $posicionPclCaliFiltrado = array_values($posicionPclCaliFiltrado);
                            //Combinar el array object con el array                             
                            foreach ($array_informacion_eventos as $key2 => $item2) {
                                foreach ($posicionPclCaliFiltrado as $item1) {
                                    // Verificar si hay coincidencia en Id_Asignacion
                                    if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                        // Agregar el elemento a la posición correspondiente
                                        $array_informacion_eventos[$key2]->Resultado = $item1['ProcentajePClCaliResultado'];
                                        break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                    }
                                }
                            }                            
                        }                        
                    }
                    // Resultado Recalificacion Pcl
                    $posicionPclReca = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_pcl && $element['Id_Servicio'] == $servicio_PclReca && $element['ID_evento'] == $consultar_id_evento) {
                            $posicionPclReca[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }
                    if (count($posicionPclReca) > 0) {
                        
                        $resultadoRecaPcl =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
                        ->select('side.ID_Evento','side.Id_Asignacion','side.Porcentaje_pcl');
                        foreach ($posicionPclReca as $item) {
                            $resultadoRecaPcl->orWhere([
                                ['side.Id_Asignacion', $item['Id_Asignacion']],
                                ['side.Id_proceso', $item['Id_proceso']],
                                ['side.ID_evento', $item['ID_evento']]
                            ]);
                        }
                        $resulRecaPcl = $resultadoRecaPcl->get();                                        
                        if (count($resulRecaPcl) > 0) {
                            $ArrayresulRecaPcl = $resulRecaPcl->toArray();                                                 
                            foreach ($posicionPclReca as &$item) {
                                // Buscar el elemento correspondiente en los resultados de la consulta
                                $resultado = array_filter($ArrayresulRecaPcl, function ($result) use ($item) {
                                    return $result->Id_Asignacion == $item['Id_Asignacion'];
                                });                    
                                // Si se encuentra una coincidencia, agregar la información al array original
                                if (!empty($resultado)) {
                                    $resultado = reset($resultado); // Obtener el primer elemento del array de resultados
                                    $item['ProcentajePClRecaResultado'] = $resultado->Porcentaje_pcl;
                                } 
                            }                          
                            // Filtrar los elementos que contienen [ProcentajePClRecaResultado]
                            $posicionPclRecaFiltrado = array_filter($posicionPclReca, function ($item) {
                                return isset($item['ProcentajePClRecaResultado']);
                            }); 
                            // Reorganizar los índices del array filtrado
                            $posicionPclRecaFiltrado = array_values($posicionPclRecaFiltrado);
                            //Combinar el array object con el array 
                            foreach ($array_informacion_eventos as $key2 => $item2) {
                                foreach ($posicionPclRecaFiltrado as $item1) {
                                    // Verificar si hay coincidencia en Id_Asignacion
                                    if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                        // Agregar el elemento a la posición correspondiente
                                        $array_informacion_eventos[$key2]->Resultado = $item1['ProcentajePClRecaResultado'];
                                        break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                    }
                                }
                            }
                        }                    
                        
                    } 
                    // Resultado Revision Pension Pcl
                    $posicionPclRevi = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_pcl && $element['Id_Servicio'] == $servicio_PclRevi && $element['ID_evento'] == $consultar_id_evento) {
                            $posicionPclRevi[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }
                    if (count($posicionPclRevi) > 0) {
                        $ID_eventoRevi = $posicionPclRevi[0]['ID_evento'];
                        $Id_procesoRevi = $posicionPclRevi[0]['Id_proceso'];
                        $Id_ServicioRevi = $posicionPclRevi[0]['Id_Servicio'];
                        $Id_AsignacionRevi = $posicionPclRevi[0]['Id_Asignacion'];
    
                        $resultadoReviPcl = sigmel_informacion_decreto_eventos::on('sigmel_gestiones')
                        ->select('ID_Evento','Id_Asignacion','Porcentaje_pcl')
                        ->where([['Id_Asignacion',$Id_AsignacionRevi], ['Id_proceso',$Id_procesoRevi], ['ID_Evento',$ID_eventoRevi]])
                        ->get(); 
                        if (count($resultadoReviPcl) > 0) {
                            $ProcentajePClReviResultado = $resultadoReviPcl[0]->Porcentaje_pcl;
                            $IdAsignacionResultado = $resultadoReviPcl[0]->Id_Asignacion;
                            $ID_eventoResultado = $resultadoReviPcl[0]->ID_Evento;
            
                            foreach ($posicionPclRevi as &$elemento) {
                                // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                if ($elemento['Id_Asignacion'] == $IdAsignacionResultado && $elemento['ID_evento'] == $ID_eventoResultado) {
                                    // Agregar $OrigenResultado al array
                                    $elemento['ProcentajePClReviResultado'] = $ProcentajePClReviResultado;
                                }
                            }
                            // Filtrar los elementos que contienen [ProcentajePClReviResultado]
                            $posicionPclReviFiltrado = array_filter($posicionPclRevi, function ($item) {
                                return isset($item['ProcentajePClReviResultado']);
                            }); 
                            // Reorganizar los índices del array filtrado
                            $posicionPclReviFiltrado = array_values($posicionPclReviFiltrado);
                            //Combinar el array object con el array                             
                            foreach ($array_informacion_eventos as $key2 => $item2) {
                                foreach ($posicionPclReviFiltrado as $item1) {
                                    // Verificar si hay coincidencia en Id_Asignacion
                                    if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                        // Agregar el elemento a la posición correspondiente
                                        $array_informacion_eventos[$key2]->Resultado = $item1['ProcentajePClReviResultado'];
                                        break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                    }
                                }
                            }                            
                        }                        
                    }
                    // Resultado Pronunciamiento Pcl
                    $posicionPclPron = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_pcl && $element['Id_Servicio'] == $servicio_PclPron && $element['ID_evento'] == $consultar_id_evento) {
                            $posicionPclPron[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }
                    if (count($posicionPclPron) > 0) {
                        $ID_eventoPron = $posicionPclPron[0]['ID_evento'];
                        $Id_procesoPron = $posicionPclPron[0]['Id_proceso'];
                        $Id_ServicioPron = $posicionPclPron[0]['Id_Servicio'];
                        $Id_AsignacionPron = $posicionPclPron[0]['Id_Asignacion'];
    
                        $resultadoPronPcl = sigmel_informacion_pronunciamiento_eventos::on('sigmel_gestiones')
                        ->select('ID_evento','Id_Asignacion','Decision')
                        ->where([['Id_Asignacion',$Id_AsignacionPron], ['Id_proceso',$Id_procesoPron], ['ID_evento',$ID_eventoPron]])
                        ->get(); 
                        if (count($resultadoPronPcl) > 0) {
                            $DecisionProResultado = $resultadoPronPcl[0]->Decision;
                            $IdAsignacionResultado = $resultadoPronPcl[0]->Id_Asignacion;
                            $ID_eventoResultado = $resultadoPronPcl[0]->ID_evento;
            
                            foreach ($posicionPclPron as &$elemento) {
                                // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                if ($elemento['Id_Asignacion'] == $IdAsignacionResultado && $elemento['ID_evento'] == $ID_eventoResultado) {
                                    // Agregar $OrigenResultado al array
                                    $elemento['DecisionProResultado'] = $DecisionProResultado;
                                }
                            }
                            // Filtrar los elementos que contienen [DecisionProResultado]
                            $posicionPclPronFiltrado = array_filter($posicionPclPron, function ($item) {
                                return isset($item['DecisionProResultado']);
                            }); 
                            // Reorganizar los índices del array filtrado
                            $posicionPclPronFiltrado = array_values($posicionPclPronFiltrado);                            
                            //Combinar el array object con el array 
                            foreach ($array_informacion_eventos as $key2 => $item2) {
                                foreach ($posicionPclPronFiltrado as $item1) {
                                    // Verificar si hay coincidencia en Id_Asignacion
                                    if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                        // Agregar el elemento a la posición correspondiente
                                        $array_informacion_eventos[$key2]->Resultado = $item1['DecisionProResultado'];
                                        break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                    }
                                }
                            }                            
                        }                        
                    }
                    // Resultado Controversia Origen Juntas
                    $posicionJuntasConOri = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_Juntas && $element['Id_Servicio'] == $servicio_JuntasConOri && $element['ID_evento'] == $consultar_id_evento) {
                            $posicionJuntasConOri[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }
                    if (count($posicionJuntasConOri) > 0) {
                        $ID_eventoConOri = $posicionJuntasConOri[0]['ID_evento'];
                        $Id_procesoConOri = $posicionJuntasConOri[0]['Id_proceso'];
                        $Id_ServicioConOri = $posicionJuntasConOri[0]['Id_Servicio'];
                        $Id_AsignacionConOri = $posicionJuntasConOri[0]['Id_Asignacion'];
                        $resultadoJuntasConOri = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_controversia_juntas_eventos as sicje')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slpa', 'slpa.Id_Parametro', '=', 'sicje.Origen_jnci_emitido')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slpara', 'slpara.Id_Parametro', '=', 'sicje.Origen_reposicion_jrci')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sicje.Origen_jrci_emitido')
                        ->select('ID_evento','Id_Asignacion',
                            DB::raw("CASE WHEN n_dictamen_jrci_emitido IS NULL THEN 'Vacio' ELSE n_dictamen_jrci_emitido END AS n_dictamen_jrci_emitido"),
                            'sicje.Origen_jrci_emitido',
                            'slp.Nombre_parametro as OrigenDxJRCIemitido',
                            'sicje.Decision_dictamen_jrci',
                            DB::raw("CASE WHEN n_dictamen_reposicion_jrci IS NULL THEN 'Vacio' ELSE n_dictamen_reposicion_jrci END AS n_dictamen_reposicion_jrci"),
                            'sicje.Origen_reposicion_jrci',
                            'slpara.Nombre_parametro as OrigenDxJRCIreposicion',
                            'sicje.Decision_dictamen_repo_jrci',
                            DB::raw("CASE WHEN n_dictamen_jnci_emitido IS NULL THEN 'Vacio' ELSE n_dictamen_jnci_emitido END AS n_dictamen_jnci_emitido"),
                            'sicje.Origen_jnci_emitido',
                            'slpa.Nombre_parametro as OrigenDxJNCIemitido')
                        ->where([['Id_Asignacion',$Id_AsignacionConOri], ['Id_proceso',$Id_procesoConOri], ['ID_evento',$ID_eventoConOri]])                    
                        ->get(); 
                        if (count($resultadoJuntasConOri) > 0) {
                            // variables JRCI Emitido
                            $Jrci_1_Resultado = $resultadoJuntasConOri[0]->n_dictamen_jrci_emitido; 
                            $OrigenDxJRCIemitido = $resultadoJuntasConOri[0]->OrigenDxJRCIemitido;                 
                            $Decision_dictamen_jrci = $resultadoJuntasConOri[0]->Decision_dictamen_jrci;
                            // Variables JRCI Reposicion
                            $Jrci_2_Resultado = $resultadoJuntasConOri[0]->n_dictamen_reposicion_jrci;
                            $OrigenDxJRCIreposicion = $resultadoJuntasConOri[0]->OrigenDxJRCIreposicion;
                            $Decision_dictamen_repo_jrci = $resultadoJuntasConOri[0]->Decision_dictamen_repo_jrci;
                            // Variables JNCI Emitido                   
                            $Jnci_Resultado = $resultadoJuntasConOri[0]->n_dictamen_jnci_emitido;
                            $OrigenDxJNCIemitido = $resultadoJuntasConOri[0]->OrigenDxJNCIemitido;
                            $IdAsignacionResultado = $resultadoJuntasConOri[0]->Id_Asignacion;
                            $ID_eventoResultado = $resultadoJuntasConOri[0]->ID_evento;
        
                            if ($Jnci_Resultado != 'Vacio') {
                                foreach ($posicionJuntasConOri as &$elemento) {
                                    // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                    if ($elemento['Id_Asignacion'] == $IdAsignacionResultado && $elemento['ID_evento'] == $ID_eventoResultado) {
                                        // Agregar $OrigenResultado al array
                                        $elemento['ContJuntasOriResultado'] = 'JNCI_'.$OrigenDxJNCIemitido;
                                    }
                                }
                                // Filtrar los elementos que contienen [ContJuntasOriResultado]
                                $posicionJuntasConOriFiltrado = array_filter($posicionJuntasConOri, function ($item) {
                                    return isset($item['ContJuntasOriResultado']);
                                }); 
                                // Reorganizar los índices del array filtrado
                                $posicionJuntasConOriFiltrado = array_values($posicionJuntasConOriFiltrado);                                                        
                                //Combinar el array object con el array posicionJuntasConOri
                                foreach ($array_informacion_eventos as $key2 => $item2) {
                                    foreach ($posicionJuntasConOriFiltrado as $item1) {
                                        // Verificar si hay coincidencia en Id_Asignacion
                                        if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                            // Agregar el elemento a la posición correspondiente
                                            $array_informacion_eventos[$key2]->Resultado = $item1['ContJuntasOriResultado'];
                                            break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                        }
                                    }
                                }
                            }elseif ($Jrci_2_Resultado != 'Vacio') {
                                foreach ($posicionJuntasConOri as &$elemento) {
                                    // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                    if ($elemento['Id_Asignacion'] == $IdAsignacionResultado && $elemento['ID_evento'] == $ID_eventoResultado) {
                                        // Agregar $OrigenResultado al array
                                        $elemento['ContJuntasOriResultado'] = 'JRCI_'.$OrigenDxJRCIreposicion.'_'.$Decision_dictamen_repo_jrci;
                                    }
                                }
                                // Filtrar los elementos que contienen [ContJuntasOriResultado]
                                $posicionJuntasConOriFiltrado = array_filter($posicionJuntasConOri, function ($item) {
                                    return isset($item['ContJuntasOriResultado']);
                                }); 
                                // Reorganizar los índices del array filtrado
                                $posicionJuntasConOriFiltrado = array_values($posicionJuntasConOriFiltrado);                                                        
                                //Combinar el array object con el array posicionJuntasConOri
                                foreach ($array_informacion_eventos as $key2 => $item2) {
                                    foreach ($posicionJuntasConOriFiltrado as $item1) {
                                        // Verificar si hay coincidencia en Id_Asignacion
                                        if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                            // Agregar el elemento a la posición correspondiente
                                            $array_informacion_eventos[$key2]->Resultado = $item1['ContJuntasOriResultado'];
                                            break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                        }
                                    }
                                }
                            }elseif ($Jrci_1_Resultado != 'Vacio') {
                                foreach ($posicionJuntasConOri as &$elemento) {
                                    // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                    if ($elemento['Id_Asignacion'] == $IdAsignacionResultado && $elemento['ID_evento'] == $ID_eventoResultado) {
                                        // Agregar $OrigenResultado al array
                                        $elemento['ContJuntasOriResultado'] = 'JRCI_'.$OrigenDxJRCIemitido.'_'.$Decision_dictamen_jrci;
                                    }
                                }
                                // Filtrar los elementos que contienen [ContJuntasOriResultado]
                                $posicionJuntasConOriFiltrado = array_filter($posicionJuntasConOri, function ($item) {
                                    return isset($item['ContJuntasOriResultado']);
                                }); 
                                // Reorganizar los índices del array filtrado
                                $posicionJuntasConOriFiltrado = array_values($posicionJuntasConOriFiltrado);                                                        
                                //Combinar el array object con el array posicionJuntasConOri
                                foreach ($array_informacion_eventos as $key2 => $item2) {
                                    foreach ($posicionJuntasConOriFiltrado as $item1) {
                                        // Verificar si hay coincidencia en Id_Asignacion
                                        if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                            // Agregar el elemento a la posición correspondiente
                                            $array_informacion_eventos[$key2]->Resultado = $item1['ContJuntasOriResultado'];
                                            break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                        }
                                    }
                                }
                            }
                            
                        }
                    }
                    // Resultado Controversia Pcl Juntas
                    $posicionJuntasConPcl = [];
                    foreach ($resultArrayEventos as $element) {
                        if ($element['Id_proceso'] == $proceso_Juntas && $element['Id_Servicio'] == $servicio_JuntasConPcl && $element['ID_evento'] == $consultar_id_evento) {
                            $posicionJuntasConPcl[] = [
                                'ID_evento' => $element['ID_evento'],
                                'Id_proceso' => $element['Id_proceso'],
                                'Id_Servicio' => $element['Id_Servicio'],
                                'Id_Asignacion' => $element['Id_Asignacion'],
                            ];
                        }
                    }
                    if (count($posicionJuntasConPcl) > 0) {
                        $ID_eventoConPcl = $posicionJuntasConPcl[0]['ID_evento'];
                        $Id_procesoConPcl = $posicionJuntasConPcl[0]['Id_proceso'];
                        $Id_ServicioConPcl = $posicionJuntasConPcl[0]['Id_Servicio'];
                        $Id_AsignacionConPcl = $posicionJuntasConPcl[0]['Id_Asignacion'];
                        $resultadoJuntasConPcl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_controversia_juntas_eventos as sicje')                    
                        ->select('ID_evento','Id_Asignacion',
                            DB::raw("CASE WHEN n_dictamen_jrci_emitido IS NULL THEN 'Vacio' ELSE n_dictamen_jrci_emitido END AS n_dictamen_jrci_emitido"),
                            'sicje.porcentaje_pcl_jrci_emitido',
                            'sicje.Decision_dictamen_jrci',
                            DB::raw("CASE WHEN n_dictamen_reposicion_jrci IS NULL THEN 'Vacio' ELSE n_dictamen_reposicion_jrci END AS n_dictamen_reposicion_jrci"),
                            'sicje.porcentaje_pcl_reposicion_jrci',
                            'sicje.Decision_dictamen_repo_jrci',
                            DB::raw("CASE WHEN n_dictamen_jnci_emitido IS NULL THEN 'Vacio' ELSE n_dictamen_jnci_emitido END AS n_dictamen_jnci_emitido"),
                            'sicje.porcentaje_pcl_jnci_emitido')
                        ->where([['Id_Asignacion',$Id_AsignacionConPcl], ['Id_proceso',$Id_procesoConPcl], ['ID_evento',$ID_eventoConPcl]])                    
                        ->get(); 
                        if (count($resultadoJuntasConPcl) > 0) {
                            // variables JRCI Emitido
                            $Jrci_1_Resultado = $resultadoJuntasConPcl[0]->n_dictamen_jrci_emitido; 
                            $porcentaje_pcl_jrci_emitido = $resultadoJuntasConPcl[0]->porcentaje_pcl_jrci_emitido;                 
                            $Decision_dictamen_jrci = $resultadoJuntasConPcl[0]->Decision_dictamen_jrci;
                            // Variables JRCI Reposicion
                            $Jrci_2_Resultado = $resultadoJuntasConPcl[0]->n_dictamen_reposicion_jrci;
                            $porcentaje_pcl_reposicion_jrci = $resultadoJuntasConPcl[0]->porcentaje_pcl_reposicion_jrci;
                            $Decision_dictamen_repo_jrci = $resultadoJuntasConPcl[0]->Decision_dictamen_repo_jrci;
                            // Variables JNCI Emitido                   
                            $Jnci_Resultado = $resultadoJuntasConPcl[0]->n_dictamen_jnci_emitido;
                            $porcentaje_pcl_jnci_emitido = $resultadoJuntasConPcl[0]->porcentaje_pcl_jnci_emitido;
                            $IdAsignacionResultado = $resultadoJuntasConPcl[0]->Id_Asignacion;
                            $ID_eventoResultado = $resultadoJuntasConPcl[0]->ID_evento;
        
                            if ($Jnci_Resultado != 'Vacio') {
                                foreach ($posicionJuntasConPcl as &$elemento) {
                                    // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                    if ($elemento['Id_Asignacion'] == $IdAsignacionResultado && $elemento['ID_evento'] == $ID_eventoResultado) {
                                        // Agregar $OrigenResultado al array
                                        $elemento['ContJuntasPclResultado'] = 'JNCI_'.$porcentaje_pcl_jnci_emitido;
                                    }
                                }
                                // Filtrar los elementos que contienen [ContJuntasPclResultado]
                                $posicionJuntasConPclFiltrado = array_filter($posicionJuntasConPcl, function ($item) {
                                    return isset($item['ContJuntasPclResultado']);
                                }); 
                                // Reorganizar los índices del array filtrado
                                $posicionJuntasConPclFiltrado = array_values($posicionJuntasConPclFiltrado);    
                                //Combinar el array object con el array posicionJuntasConPcl
                                foreach ($array_informacion_eventos as $key2 => $item2) {
                                    foreach ($posicionJuntasConPclFiltrado as $item1) {
                                        // Verificar si hay coincidencia en Id_Asignacion
                                        if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                            // Agregar el elemento a la posición correspondiente
                                            $array_informacion_eventos[$key2]->Resultado = $item1['ContJuntasPclResultado'];
                                            break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                        }
                                    }
                                }
                            }elseif ($Jrci_2_Resultado != 'Vacio') {
                                foreach ($posicionJuntasConPcl as &$elemento) {
                                    // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                    if ($elemento['Id_Asignacion'] == $IdAsignacionResultado && $elemento['ID_evento'] == $ID_eventoResultado) {
                                        // Agregar $OrigenResultado al array
                                        $elemento['ContJuntasPclResultado'] = 'JRCI_'.$porcentaje_pcl_reposicion_jrci.'_'.$Decision_dictamen_repo_jrci;
                                    }
                                }
                                // Filtrar los elementos que contienen [ContJuntasPclResultado]
                                $posicionJuntasConPclFiltrado = array_filter($posicionJuntasConPcl, function ($item) {
                                    return isset($item['ContJuntasPclResultado']);
                                }); 
                                // Reorganizar los índices del array filtrado
                                $posicionJuntasConPclFiltrado = array_values($posicionJuntasConPclFiltrado);    
                                //Combinar el array object con el array posicionJuntasConPcl
                                foreach ($array_informacion_eventos as $key2 => $item2) {
                                    foreach ($posicionJuntasConPclFiltrado as $item1) {
                                        // Verificar si hay coincidencia en Id_Asignacion
                                        if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                            // Agregar el elemento a la posición correspondiente
                                            $array_informacion_eventos[$key2]->Resultado = $item1['ContJuntasPclResultado'];
                                            break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                        }
                                    }
                                }
                            }elseif ($Jrci_1_Resultado != 'Vacio') {
                                foreach ($posicionJuntasConPcl as &$elemento) {
                                    // Verificar si Id_Asignacion es igual a $IdAsignacionResultado
                                    if ($elemento['Id_Asignacion'] == $IdAsignacionResultado && $elemento['ID_evento'] == $ID_eventoResultado) {
                                        // Agregar $OrigenResultado al array
                                        $elemento['ContJuntasPclResultado'] = 'JRCI_'.$porcentaje_pcl_jrci_emitido.'_'.$Decision_dictamen_jrci;
                                    }
                                }
                                // Filtrar los elementos que contienen [ContJuntasPclResultado]
                                $posicionJuntasConPclFiltrado = array_filter($posicionJuntasConPcl, function ($item) {
                                    return isset($item['ContJuntasPclResultado']);
                                }); 
                                // Reorganizar los índices del array filtrado
                                $posicionJuntasConPclFiltrado = array_values($posicionJuntasConPclFiltrado);    
                                //Combinar el array object con el array posicionJuntasConPcl
                                foreach ($array_informacion_eventos as $key2 => $item2) {
                                    foreach ($posicionJuntasConPclFiltrado as $item1) {
                                        // Verificar si hay coincidencia en Id_Asignacion
                                        if ($item1['Id_Asignacion'] == $item2->Id_Asignacion) {
                                            // Agregar el elemento a la posición correspondiente
                                            $array_informacion_eventos[$key2]->Resultado = $item1['ContJuntasPclResultado'];
                                            break; // Romper el bucle interno una vez que se encuentra la coincidencia
                                        }
                                    }
                                }
                            }
                            
                        }
                    }
                    return response()->json($array_informacion_eventos);
                }else{
                    $mensajes = array(
                        "parametro" => 'sin_datos',
                        "mensaje" => 'No se encontraron datos acorde a la búsqueda realizada.'
                    );
                    return json_decode(json_encode($mensajes, true));
                }
            break;
            default:
            break;
        }
        
    }

    // Traer listado de Profesionales acorde al proceso
    public function ProfesionalesXProceso(Request $request){
        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }

        // Traemos los lideres acorde a la selección del proceso
        // DB::raw("SELECT id, name, email FROM users WHERE FIND_IN_SET($request->id_proceso_seleccionado, id_procesos_usuario)");
        $datos_lideres_x_proceso = DB::table('users')
        ->select("id", "name", "email")
        ->whereRaw("FIND_IN_SET($request->id_proceso, id_procesos_usuario) > 0")
        ->get();

        $informacion_de_vuelta = json_decode(json_encode($datos_lideres_x_proceso), true);

        return response()->json($informacion_de_vuelta);
    }

    // Crear un nuevo servicio para el Evento seleccionado
    public function crearNuevoServicio(Request $request){
        
        if(!Auth::check()){
            return redirect('/');
        }

        $time = time();
        $date = date("Y-m-d", $time);
        $date_con_hora = date("Y-m-d h:i:s", $time);
        $nombre_usuario = Auth::user()->name;
        $date_time = date("Y-m-d H:i:s");
        
        // Actualizamos a No el servicio escogido (tupla) para deshabilitar la opcion
        // de crear nuevo servicio
        $actualizar_estado_bandera_nuevo_servicio = [
            'Visible_Nuevo_Servicio' => 'No'
        ];
        sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->where('Id_Asignacion', $request->tupla_servicio_escogido)
        ->update($actualizar_estado_bandera_nuevo_servicio);

        $consecutivo_Dictamen = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_eventos as sie', 'siae.ID_evento', '=', 'sie.ID_evento')
        ->leftJoin('sigmel_gestiones.sigmel_clientes as sc', 'sie.Cliente', '=', 'sc.Id_cliente')
        ->where('sie.Cliente',$request->id_clientes)
        ->max('Consecutivo_dictamen');

        if ($consecutivo_Dictamen > 0) {
            // Validar que el servicio solo sean Origen (Dto y Adx) y PCL (Calificacion tecnica, Recalificacion y Revision pension)
            $procesoActual = $request->id_proceso_actual;
            $servicioNuevo = $request->nuevo_servicio;
            if ($procesoActual == 1 && $servicioNuevo <> 3 || $procesoActual == 2 && $servicioNuevo <> 9) {                
                $numero_consecutivo_Dictamen = $consecutivo_Dictamen + 1;

                $actualizar_id_cliente = [
                    'Nro_consecutivo_dictamen' =>$numero_consecutivo_Dictamen + 1,            
                ];
                
                sigmel_clientes::on('sigmel_gestiones')->where('Id_cliente',$request->id_clientes)
                ->update($actualizar_id_cliente);
        
            } else {
                $numero_consecutivo_Dictamen = null;
            }            
        }
        
        if ($request->nuevo_profesional <> "") {
            $id_profesional = $request->nuevo_profesional;
            $nombre_profesional = $request->nombre_profesional;
            $F_asignacion_calificacion = $date_con_hora;
        }else{
            $id_profesional = null;
            $nombre_profesional = null;
            $F_asignacion_calificacion = null;

        }

        //Trae El numero de orden actual
        $n_orden = sigmel_numero_orden_eventos::on('sigmel_gestiones')
        ->select('Numero_orden')
        ->get();
        //Validamos si un caso de Notificaciones
        if($request->id_proceso_actual=='4'){
            $N_orden_evento=$n_orden[0]->Numero_orden;
        }else{
            $N_orden_evento='';
        }

        // Extraemos el id estado de la tabla de parametrizaciones dependiendo del
        // id del cliente, id proceso, id servicio, id accion. Este id irá como estado inicial
        // en la creación de un evento
        // MAURO PARAMETRICA
        $array_id_cliente = sigmel_informacion_eventos::on('sigmel_gestiones')
        ->select('Cliente')->where('ID_evento', $request->id_evento)->first();

        $id_cliente = $array_id_cliente["Cliente"];

        $estado_acorde_a_parametrica = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
        ->select('sipc.Estado')
        ->where([
            ['sipc.Id_cliente', '=', $id_cliente],
            ['sipc.Id_proceso', '=', $request->id_proceso_actual],
            ['sipc.Servicio_asociado', '=', $request->nuevo_servicio],
            ['sipc.Accion_ejecutar','=', $request->nueva_accion]
        ])->get();

        if(count($estado_acorde_a_parametrica)>0){
            $Id_Estado_evento = $estado_acorde_a_parametrica[0]->Estado;
        }else{
            $Id_Estado_evento = 223;
        }

        // Recopilación de datos para insertar el nuevo servicio
        $datos_nuevo_servicio = [
            'ID_evento' => $request->id_evento,
            'Id_proceso' => $request->id_proceso_actual,
            'Visible_Nuevo_Proceso' => 'Si',
            'Id_servicio' => $request->nuevo_servicio,
            'Visible_Nuevo_Servicio' => 'Si',
            'Id_accion' => $request->nueva_accion,
            'Descripcion' => $request->nueva_descripcion,
            'F_alerta' => $request->nueva_fecha_alerta,
            'Id_Estado_evento' => $Id_Estado_evento,
            'F_accion' => $request->nueva_fecha_accion,
            'F_radicacion' => $request->nueva_fecha_radicacion,
            'N_de_orden' => $N_orden_evento,
            'Id_proceso_anterior' => $request->id_proceso_actual,
            'Id_servicio_anterior' => $request->id_servicio_actual,
            'F_asignacion_calificacion' => $F_asignacion_calificacion,
            'Consecutivo_dictamen' => $numero_consecutivo_Dictamen,
            'Id_profesional' => $id_profesional,
            'Nombre_profesional' => $nombre_profesional,
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date
        ];

        sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->insert($datos_nuevo_servicio);

        sleep(1);

        // Insertar informacion en la tabla sigmel_informacion_historial_accion_eventos

        $datos_historial_accion_eventos = [
            'ID_evento' => $request->id_evento,
            'Id_proceso' => $request->id_proceso_actual,
            'Id_servicio' => $request->nuevo_servicio,
            'Id_accion' => $request->nueva_accion,
            'Documento' => 'N/A',
            'Descripcion' => $request->nueva_descripcion,
            'F_accion' => $date_time,
            'Nombre_usuario' => $nombre_usuario,
        ];

        sigmel_informacion_historial_accion_eventos::on('sigmel_gestiones')->insert($datos_historial_accion_eventos);
        sleep(2);
        
        $mensajes = array(
            "parametro" => 'creo_servicio',
            "retorno_id_evento" => $request->id_evento,
            "mensaje" => 'Servicio agregado satisfactoriamente. Por favor hacer clic en el botón Actualizar para visualizar los cambios.'
        );
        
        return json_decode(json_encode($mensajes, true));

    }

    // Crear un nuevo proceso para el Evento seleccionado
    public function crearNuevoProceso(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        
        $time = time();
        $date = date("Y-m-d", $time);
        $date_con_hora = date("Y-m-d h:i:s", $time);
        $nombre_usuario = Auth::user()->name;
        $date_time = date("Y-m-d H:i:s");        

        // Actualizamos a No el proceso escogido (tupla) para deshabilitar la opcion
        // de crear nuevo proceso.

        $actualizar_estado_bandera_nuevo_proceso = [
            'Visible_Nuevo_Proceso' => 'No'
        ];

        sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->where('Id_Asignacion', $request->tupla_proceso_escogido)
        ->update($actualizar_estado_bandera_nuevo_proceso);

        $consecutivo_Dictamen = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_eventos as sie', 'siae.ID_evento', '=', 'sie.ID_evento')
        ->leftJoin('sigmel_gestiones.sigmel_clientes as sc', 'sie.Cliente', '=', 'sc.Id_cliente')
        ->where('sie.Cliente',$request->id_clientes)
        ->max('Consecutivo_dictamen');

        if ($consecutivo_Dictamen > 0) {
            // Validar que el servicio solo sean Origen (Dto y Adx) y PCL (Calificacion tecnica, Recalificacion y Revision pension)
            $procesoNuevo = $request->selector_nuevo_proceso;
            $servicioNuevo = $request->selector_nuevo_servicio;
            if ($procesoNuevo == 1 && $servicioNuevo <> 3 || $procesoNuevo == 2 && $servicioNuevo <> 9) {                
                $numero_consecutivo_Dictamen = $consecutivo_Dictamen + 1;
                $actualizar_id_cliente = [
                    'Nro_consecutivo_dictamen' =>$numero_consecutivo_Dictamen + 1,            
                ];
        
                sigmel_clientes::on('sigmel_gestiones')->where('Id_cliente',$request->id_clientes)
                ->update($actualizar_id_cliente);

            } else {
                $numero_consecutivo_Dictamen = null;
            }            
        }

        if ($request->nuevo_profesional_nuevo_proceso <> "") {
            $id_profesional = $request->nuevo_profesional_nuevo_proceso;
            $nombre_profesional = $request->nombre_profesional_nuevo_proceso;
            $F_asignacion_calificacion = $date_con_hora;
        }else{
            $id_profesional = null;
            $nombre_profesional = null;
            $F_asignacion_calificacion = null;

        }

        //Trae El numero de orden actual
        $n_orden = sigmel_numero_orden_eventos::on('sigmel_gestiones')
        ->select('Numero_orden')
        ->get();
        //Validamos si un caso de Notificaciones
        if($request->selector_nuevo_proceso=='4'){
            $N_orden_evento=$n_orden[0]->Numero_orden;
        }else{
            $N_orden_evento='';
        }


        // Extraemos el id estado de la tabla de parametrizaciones dependiendo del
        // id del cliente, id proceso, id servicio, id accion. Este id irá como estado inicial
        // en la creación de un evento
        // MAURO PARAMETRICA
        $array_id_cliente = sigmel_informacion_eventos::on('sigmel_gestiones')
        ->select('Cliente')->where('ID_evento', $request->id_evento)->first();

        $id_cliente = $array_id_cliente["Cliente"];

        $estado_acorde_a_parametrica = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
        ->select('sipc.Estado')
        ->where([
            ['sipc.Id_cliente', '=', $id_cliente],
            ['sipc.Id_proceso', '=', $request->selector_nuevo_proceso],
            ['sipc.Servicio_asociado', '=', $request->selector_nuevo_servicio],
            ['sipc.Accion_ejecutar','=', $request->nueva_accion_nuevo_proceso]
        ])->get();

        if(count($estado_acorde_a_parametrica)>0){
            $Id_Estado_evento = $estado_acorde_a_parametrica[0]->Estado;
        }else{
            $Id_Estado_evento = 223;
        }

        $datos_nuevo_proceso = [
            'ID_evento' => $request->id_evento,
            'Id_proceso' => $request->selector_nuevo_proceso,
            'Visible_Nuevo_Proceso' => 'Si',
            'Id_servicio' => $request->selector_nuevo_servicio,
            'Visible_Nuevo_Servicio' => 'Si',
            'Id_accion' => $request->nueva_accion_nuevo_proceso,
            'Descripcion' => $request->nueva_descripcion_nuevo_proceso,
            'F_alerta' => $request->nueva_fecha_alerta_nuevo_proceso,
            'Id_Estado_evento' => $Id_Estado_evento,
            'F_accion' => $request->nueva_fecha_accion_nuevo_proceso,
            'F_radicacion' => $request->fecha_radicacion_nuevo_proceso,
            'Id_proceso_anterior' => $request->id_proceso_actual_nuevo_proceso,
            'Id_servicio_anterior' => $request->id_servicio_actual_nuevo_proceso,
            'F_asignacion_calificacion' => $F_asignacion_calificacion,
            'Consecutivo_dictamen' => $numero_consecutivo_Dictamen,
            'N_de_orden' => $N_orden_evento,
            'Id_profesional' => $id_profesional,
            'Nombre_profesional' => $nombre_profesional,
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date
        ];

        sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->insert($datos_nuevo_proceso);

        sleep(1);

        // Insertar informacion en la tabla sigmel_informacion_historial_accion_eventos

        $datos_historial_accion_eventos = [
            'ID_evento' => $request->id_evento,
            'Id_proceso' => $request->selector_nuevo_proceso,
            'Id_servicio' => $request->selector_nuevo_servicio,
            'Id_accion' => $request->nueva_accion_nuevo_proceso,
            'Documento' => 'N/A',
            'Descripcion' => $request->nueva_descripcion_nuevo_proceso,
            'F_accion' => $date_time,
            'Nombre_usuario' => $nombre_usuario,
        ];

        sigmel_informacion_historial_accion_eventos::on('sigmel_gestiones')->insert($datos_historial_accion_eventos);
        sleep(2);
        
        $mensajes = array(
            "parametro" => 'creo_proceso',
            "retorno_id_evento" => $request->id_evento,
            "mensaje" => 'Proceso agregado satisfactoriamente. Por favor hacer clic en el botón Actualizar para visualizar los cambios.'
        );

        return json_decode(json_encode($mensajes, true));

    }

    // Mantener o Borrar datos de búsqueda del formulario de buscador de eventos
    public function mantenerDatosBusquedaEvento(Request $request){
        // Obtén la instancia del objeto de sesión
        $session = app('session');

        $parametro = $request->parametro;
        if ($parametro == "mantener_datos_busqueda") {

            // Establece la variable de sesión
            $session->put('num_ident', $request->consulta_nro_identificacion);
            $session->put('num_id_evento', $request->consulta_id_evento);
        }

        if ($parametro == "borrar_datos_busqueda") {

            // Establece la variable de sesión
            $session->put('num_ident', "");
            $session->put('num_id_evento', "");
        }

        $mensajes = array(
            "parametro" => 'creo_variables'
        );
        return json_decode(json_encode($mensajes, true));
    }
}
