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
use App\Models\sigmel_registro_documentos_eventos;

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
                    // Resultado Adicion DX Origen
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
                        $resultadoReviPcl =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
                        ->select('side.ID_Evento','side.Id_Asignacion','side.Porcentaje_pcl');
                        foreach ($posicionPclRevi as $item) {
                            $resultadoReviPcl->orWhere([
                                ['side.Id_Asignacion', $item['Id_Asignacion']],
                                ['side.Id_proceso', $item['Id_proceso']],
                                ['side.ID_evento', $item['ID_evento']]
                            ]);
                        }
                        $resulRevPcl = $resultadoReviPcl->get();  
                        if (count($resulRevPcl) > 0) {
                            $ArrayresulRevPcl = $resulRevPcl->toArray();                                                 
                            foreach ($posicionPclRevi as &$item) {
                                // Buscar el elemento correspondiente en los resultados de la consulta
                                $resultado = array_filter($ArrayresulRevPcl, function ($result) use ($item) {
                                    return $result->Id_Asignacion == $item['Id_Asignacion'];
                                });                    
                                // Si se encuentra una coincidencia, agregar la información al array original
                                if (!empty($resultado)) {
                                    $resultado = reset($resultado); // Obtener el primer elemento del array de resultados
                                    $item['ProcentajePClReviResultado'] = $resultado->Porcentaje_pcl;
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
                        $resultadoReviPcl =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
                        ->select('side.ID_Evento','side.Id_Asignacion','side.Porcentaje_pcl');
                        foreach ($posicionPclRevi as $item) {
                            $resultadoReviPcl->orWhere([
                                ['side.Id_Asignacion', $item['Id_Asignacion']],
                                ['side.Id_proceso', $item['Id_proceso']],
                                ['side.ID_evento', $item['ID_evento']]
                            ]);
                        }
                        $resulRevPcl = $resultadoReviPcl->get();  
                        if (count($resulRevPcl) > 0) {
                            $ArrayresulRevPcl = $resulRevPcl->toArray();                                                 
                            foreach ($posicionPclRevi as &$item) {
                                // Buscar el elemento correspondiente en los resultados de la consulta
                                $resultado = array_filter($ArrayresulRevPcl, function ($result) use ($item) {
                                    return $result->Id_Asignacion == $item['Id_Asignacion'];
                                });                    
                                // Si se encuentra una coincidencia, agregar la información al array original
                                if (!empty($resultado)) {
                                    $resultado = reset($resultado); // Obtener el primer elemento del array de resultados
                                    $item['ProcentajePClReviResultado'] = $resultado->Porcentaje_pcl;
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

        $id_cliente = $request->id_cliente;
        $id_proceso = $request->id_proceso;
        $id_servicio = $request->id_servicio;
        $id_accion = $request->id_accion;

        /* Extraemos el equippo de trabajo y el profesional asignado configurados en la paramétrica */
        $info_equipo_prof_asig = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
        ->select('sipc.Equipo_trabajo', 'sipc.Profesional_asignado')
        ->where([
            ['sipc.Id_cliente', '=', $id_cliente],
            ['sipc.Id_proceso', '=', $id_proceso],
            ['sipc.Servicio_asociado', '=', $id_servicio],
            ['sipc.Accion_ejecutar', '=', $id_accion]
        ])->get();

        /* Si el profesional asignado está configurado entonces el listado de profesionales
        se cargará con los usuarios que pertenecen al equipo de trabajo configurado en la paramétrica */
        if($info_equipo_prof_asig[0]->Profesional_asignado <> ""){
            $listado_profesionales = DB::table('users as u')
            ->leftJoin('sigmel_gestiones.sigmel_usuarios_grupos_trabajos as sugt', 'u.id', '=', 'sugt.id_usuarios_asignados')
            ->select('u.id', 'u.name')
            ->where([['sugt.id_equipo_trabajo', $info_equipo_prof_asig[0]->Equipo_trabajo]])
            ->get();
    
            $info_listado_profesionales = json_decode(json_encode($listado_profesionales, true));
            return response()->json([
                'info_listado_profesionales' => $info_listado_profesionales,
                'Profesional_asignado' => $info_equipo_prof_asig[0]->Profesional_asignado
            ]);
        }else{
            
            // Traemos los lideres acorde a la selección del proceso
            // DB::raw("SELECT id, name, email FROM users WHERE FIND_IN_SET($request->id_proceso_seleccionado, id_procesos_usuario)");
            $datos_lideres_x_proceso = DB::table('users')
            ->select("id", "name", "email")
            ->whereRaw("FIND_IN_SET($id_proceso, id_procesos_usuario) > 0")
            ->get();
    
            $informacion_de_vuelta = json_decode(json_encode($datos_lideres_x_proceso), true);
            // return response()->json($informacion_de_vuelta);

            return response()->json([
                'info_listado_profesionales' => $informacion_de_vuelta,
                'Profesional_asignado' => ''
            ]);

        }

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
        ->select('sipc.Estado','sipc.Enviar_a_bandeja_trabajo_destino as enviarA')
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

        //Asignamos #n de orden cuado se envie un caso a notificaciones
        if(!empty($estado_acorde_a_parametrica[0]->enviarA) && $estado_acorde_a_parametrica[0]->enviarA != 'No'){
            $N_orden_evento=$n_orden[0]->Numero_orden;
        }else{
            $N_orden_evento=null;
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
            'N_de_orden' =>  $N_orden_evento,
            'Notificacion' => isset($estado_acorde_a_parametrica[0]->enviarA) ? $estado_acorde_a_parametrica[0]->enviarA : 'No',  
            'Id_profesional' => $id_profesional,
            'Nombre_profesional' => $nombre_profesional,
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date
        ];

        $Id_Asignacion = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->insertGetId($datos_nuevo_servicio);

        sleep(1);

        // Insertar informacion en la tabla sigmel_informacion_historial_accion_eventos

        $datos_historial_accion_eventos = [
            'Id_Asignacion' => $Id_Asignacion,
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

        // Extraemos el id estado de la tabla de parametrizaciones dependiendo del
        // id del cliente, id proceso, id servicio, id accion. Este id irá como estado inicial
        // en la creación de un evento
        // MAURO PARAMETRICA
        $array_id_cliente = sigmel_informacion_eventos::on('sigmel_gestiones')
        ->select('Cliente')->where('ID_evento', $request->id_evento)->first();

        $id_cliente = $array_id_cliente["Cliente"];

        $estado_acorde_a_parametrica = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
        ->select('sipc.Estado','sipc.Enviar_a_bandeja_trabajo_destino as enviarA')
        ->where([
            ['sipc.Id_cliente', '=', $id_cliente],
            ['sipc.Id_proceso', '=', $request->selector_nuevo_proceso],
            ['sipc.Servicio_asociado', '=', $request->selector_nuevo_servicio],
            ['sipc.Accion_ejecutar','=', $request->nueva_accion_nuevo_proceso]
        ])->get();

        //Asignamos #n de orden cuado se envie un caso a notificaciones
        if(!empty($estado_acorde_a_parametrica[0]->enviarA) && $estado_acorde_a_parametrica[0]->enviarA != 'No'){
            $N_orden_evento=$n_orden[0]->Numero_orden;
        }else{
            $N_orden_evento=null;
        }

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
            'Notificacion' => isset($estado_acorde_a_parametrica[0]->enviarA) ? $estado_acorde_a_parametrica[0]->enviarA : 'No',
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date
        ];

        $Id_Asignacion = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->insertGetId($datos_nuevo_proceso);

        sleep(1);

        // Insertar informacion en la tabla sigmel_informacion_historial_accion_eventos

        $datos_historial_accion_eventos = [
            'Id_Asignacion' => $Id_Asignacion,
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

        //Procesamos la informacion del formulario asociado al nuevo servicio
        $this->procesarFormulariosJuntas($request->id_evento, $Id_Asignacion,$request->selector_nuevo_servicio,$request->tupla_proceso_escogido,$request->selector_nuevo_proceso,$request->id_servicio_actual_nuevo_proceso);
        
        $mensajes = array(
            "parametro" => 'creo_proceso',
            "retorno_id_evento" => $request->id_evento,
            "mensaje" => 'Proceso agregado satisfactoriamente. Por favor hacer clic en el botón Actualizar para visualizar los cambios.'
        );

        return json_decode(json_encode($mensajes, true));

    }

    // Función para validar la creación de un servicio ADX, CALIFICACIÓN TÉCNICA, RECALIFICACIÓN Y REVISIÓN PENSIÓN
    // Desde la modal de Nuevo Proceso
    public function ValidarNuevosServiciosNuevoProceso (Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        // Captura de datos del formulario
        $id_proceso_seleccionado = $request->Id_proceso;
        $id_servicio_seleccionado = $request->Id_servicio;
        $id_evento_seleccionado = $request->nro_evento;

        // Caso 1: Validación de creación de servicio de Determinación del Origen (DTO) ATEl
        // Caso 2: Validación de creación de servicio Adición Dx
        // Caso 3: Validación de creación de servicio Recalificación PCL
        // Caso 4: Validación de creación de servicio Revisión Pensión PCL
        // Caso 5: Validación de creación de servicio Calificación Técnica
        switch (true) {
            case ($id_servicio_seleccionado == 1):
                /*  Extraemos los id de asignación del o las Adiciones Dx  creadas para
                    el id evento seleccionado
                */
                $informacion_adx = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                ->select('Id_Asignacion')
                ->where([
                    ['ID_evento', $id_evento_seleccionado],
                    ['Id_proceso', $id_proceso_seleccionado]
                ])
                ->whereIn('Id_servicio', [2])
                ->get();

                // creamos el array con la información del o las Adiciones Dx
                $array_informacion_adx = json_decode(json_encode($informacion_adx, true));
                $total_datos_adx = count($array_informacion_adx);

                /*  Extraemos los id de asignación del o los DTO  creados para
                    el id evento seleccionado
                */
                $informacion_dto = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                ->select('Id_Asignacion')
                ->where([
                    ['ID_evento', $id_evento_seleccionado],
                    ['Id_proceso', $id_proceso_seleccionado]
                ])
                ->whereIn('Id_servicio', [1])
                ->get();

                // creamos el array con la información de DTO
                $array_informacion_dto = json_decode(json_encode($informacion_dto, true));
                $total_datos_dto = count($array_informacion_dto);

                // Si ya existe almenos una DTO o Adición Dx no se le permite crear el servicio
                if ($total_datos_dto > 0 || $total_datos_adx > 0) {
                    $mensajes = array(
                        "parametro" => 'fallo',
                        "mensaje" => 'No puede crear el servicio de Determinación del Origen (DTO) ATEl debido a que ya se cuenta con un servicio de Adición DX o Determinación del Origen (DTO) ATEl creado.'
                    );
                    return json_decode(json_encode($mensajes, true));
                } else {
                    $mensajes = array(
                        "parametro" => 'exito'
                    );
                    return json_decode(json_encode($mensajes, true));
                }
            break;
            case ($id_servicio_seleccionado == 2):

                /*  Extraemos los id de asignación del o las Adiciones Dx  creadas para
                    el id evento seleccionado
                */
                $informacion_adx = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                ->select('Id_Asignacion')
                ->where([
                    ['ID_evento', $id_evento_seleccionado],
                    ['Id_proceso', $id_proceso_seleccionado]
                ])
                ->whereIn('Id_servicio', [2])
                ->get();

                // creamos el array con la información del o las Adiciones Dx
                $array_informacion_adx = json_decode(json_encode($informacion_adx, true));
                $total_datos_adx = count($array_informacion_adx);

                /*  Extraemos los id de asignación del o los DTO  creados para
                    el id evento seleccionado
                */
                $informacion_dto = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                ->select('Id_Asignacion')
                ->where([
                    ['ID_evento', $id_evento_seleccionado],
                    ['Id_proceso', $id_proceso_seleccionado]
                ])
                ->whereIn('Id_servicio', [1])
                ->get();

                // creamos el array con la información de DTO
                $array_informacion_dto = json_decode(json_encode($informacion_dto, true));
                $total_datos_dto = count($array_informacion_dto);

                /* Escenario 1: Validar que si existe almenos un Adición Dx creada no deja crear el servicio */
                /* Escenario 2: Validar que si existe un DTO creado no deja crear el servicio */
                switch (true) {
                    case ($total_datos_adx > 0):
                        $mensajes = array(
                            "parametro" => 'fallo',
                            "mensaje" => 'No puede crear el servicio de Adición DX debido a que ya se cuenta con un servicio de Adición DX creado.'
                        );
                        return json_decode(json_encode($mensajes, true));
                    break;

                    case ($total_datos_dto > 0):
                        $mensajes = array(
                            "parametro" => 'fallo',
                            "mensaje" => 'No puede crear el servicio de Adición DX debido a que ya se cuenta con un servicio de Determinación del Origen (DTO) ATEL creado.'
                        );
                        return json_decode(json_encode($mensajes, true));
                    break;

                    default:
                        $mensajes = array(
                            "parametro" => 'exito'
                        );
                        return json_decode(json_encode($mensajes, true));
                    break;
                }

            break;                        
            case ($id_servicio_seleccionado == 7):
                //  PASO 1: Extracción del id de asignacion de la calficación técnica y recalificación
                //  que han sido creados para el id evento seleccioando 
                $informacion_recalificacion_pcl = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                ->select('Id_Asignacion')
                ->where([
                    ['ID_evento', $id_evento_seleccionado],
                    ['Id_proceso', $id_proceso_seleccionado]
                ])
                ->whereIn('Id_servicio', [6, 7, 8])
                ->get();     
                
                // se convierte el object a array
                $array_informacion_recalificacion_pcl = json_decode(json_encode($informacion_recalificacion_pcl, true));                
                // print_r($array_informacion_recalificacion_pcl);
                
                // Escenario 1: Validar si cuenta con una Calificación técnica o Recalificación creada.
                if (count($array_informacion_recalificacion_pcl) > 0) {

                    $mensajes = array(
                        "parametro" => 'fallo',
                        "mensaje" => 'No se puede crear el servicio de Recalificación debido a que ya se cuenta con un servicio de Recalificación, Revisión de Pensión o Calificación Técnica creada.'
                    );
                    return json_decode(json_encode($mensajes, true));
                }                                             
                
            break;
            case ($id_servicio_seleccionado == 8):                
                // PASO 1: Extracción del id de asignacion de la calficación técnica y revisión pensión
                //  que han sido creados para el id evento seleccioando 

                $informacion_revision_pension_pcl = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                ->select('Id_Asignacion')
                ->where([
                    ['ID_evento', $id_evento_seleccionado],
                    ['Id_proceso', $id_proceso_seleccionado]
                ])
                ->whereIn('Id_servicio', [6, 7, 8])
                ->get(); 

                // se convierte el object a array

                $array_informacion_revision_pension_pcl = json_decode(json_encode($informacion_revision_pension_pcl, true));
                // print_r($array_informacion_revision_pension_pcl);
                
                // Escenario 1: Validar si cuenta con una Calificación técnica o Revisión pensión creada.
                if (count($array_informacion_revision_pension_pcl) > 0) {                  

                    $mensajes = array(
                        "parametro" => 'fallo',
                        "mensaje" => 'No se puede crear el servicio de Revisión de Pensión debido a que ya se cuenta con un servicio de Recalificación, Revisión de Pensión o Calificación Técnica creada.'
                    );
                    return json_decode(json_encode($mensajes, true));
                }                                             
                
            break;
            case ($id_servicio_seleccionado == 6):                
                // PASO 1: Extracción del id de asignacion de la calficación técnica y revisión pensión
                //  que han sido creados para el id evento seleccioando 

                $informacion_calificacion_tecnica_pcl = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                ->select('Id_Asignacion')
                ->where([
                    ['ID_evento', $id_evento_seleccionado],
                    ['Id_proceso', $id_proceso_seleccionado]
                ])
                ->whereIn('Id_servicio', [6, 7, 8])
                ->get(); 

                // se convierte el object a array

                $array_informacion_calificacion_tecnica_pcl = json_decode(json_encode($informacion_calificacion_tecnica_pcl, true));
                // print_r($array_informacion_revision_pension_pcl);
                
                // Escenario 1: Validar si cuenta con una Calificación técnica o Recalificación creada.
                if (count($array_informacion_calificacion_tecnica_pcl) > 0) {                

                    $mensajes = array(
                        "parametro" => 'fallo',
                        "mensaje" => 'No se puede crear el servicio de Calificación técnica debido a que ya se cuenta con un servicio de Recalificación o Revisión Pensión creada.'
                    );
                    return json_decode(json_encode($mensajes, true));
                }                                             
                
            break;
            default:
                # code...
            break;
        }

    }

    /**
    *   Ficha: PSB023 - En funcion del servicio origen a partir del cual se creara el nuevo proceso extraemos la informacion relevante segun aplique
    *   para las reglas en @var $reglas
    *   @param evento Corresponden al numero de evento con el cual estaremos trabajando.
    *   @param nuevo_id_asignacion Corresponde al id de asignacion para el nuevo proceso.
    *   @param servicioNuevo Corresponde al nuevo servicio que se creo.
    *   @param Id_Asignacion_origen Corresponde al id de asignacion del evento origen a partid del cual se creo el proceso.
    *   @param proceso Corresponde al proceso asociado al nuevo servicio
    *   @return void
    */
    public function procesarFormulariosJuntas($evento, $nuevo_id_asignacion,$servicioNuevo,$Id_Asignacion_origen,$proceso,$servicioOrigen) {
        /**
         *  Reglas sobre las cuales se estaran insertado los datos en el nuevo servicio siempre y cuando se cumplan las condiciones
         *  @var servico_nuevo corresponde a Controversia origen o Controversia pcl
         *  @var servicio_origen Corresponde al servicio origen a partir del cual se esta creando el nuevo proceso.
         */
        $reglas = [
            [
                'servicio_origen' => ['Determinación del Origen (DTO) ATEL', 'Adición DX'],
                'servicio_nuevo' => 12, //Origen
                'accion' => 'traer_informacion',
            ],
            [
                'servicio_origen' => ['Calificación técnica', 'Recalificación', 'Revisión pensión','Controversia PCL'],
                'servicio_nuevo' => 13, //PCL
                'accion' => 'traer_informacion',
            ],
        ];
    
        $date = date("Y-m-d", time());
        $nombre_usuario = Auth::user()->name;
    
        //Info del servico a partir del cual se esta creando el nuevo proceso.
        $servicio = (array) DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_lista_procesos_servicios as lps')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_historial_accion_eventos as ihae', 'ihae.id_servicio', 'lps.id_servicio')
            ->select('Nombre_proceso', 'Nombre_servicio')
            ->where([['ihae.ID_evento', $evento], ['ihae.Id_Asignacion', $Id_Asignacion_origen]])
            ->first();

        if(empty($servicio)){
            return;
        }
        foreach ($reglas as $regla) {
            //Info. que se llenaran tanto para origen como para pcl
            if (in_array($servicio['Nombre_servicio'], $regla['servicio_origen']) && ($servicioNuevo == 12 || $servicioNuevo == 13)) {
                if ($regla['accion'] == 'traer_informacion') {
                    $InfoControvertido = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_eventos as IE')
                        ->leftJoin('sigmel_gestiones.sigmel_clientes AS SC', 'SC.Id_cliente', 'IE.cliente')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_clientes AS TC', 'TC.Id_TipoCliente', 'IE.Tipo_cliente')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros AS LP', 'LP.Nombre_parametro', 'TC.Nombre_tipo_cliente')
                        ->select(
                            'Nombre_cliente',
                            'Id_Parametro as TipoCliente',
                            'Tipo_evento'
                            )->where([
                            ['IE.ID_evento', $evento],
                            ['LP.Tipo_lista','Juntas Controversia']
                            ])->first();
    
                    $Controvertido = [
                        'ID_evento' => $evento,
                        'Id_asignacion' => $nuevo_id_asignacion,
                        'Id_proceso' => $proceso,
                        'Primer_calificador' => $InfoControvertido->TipoCliente,
                        'Nom_entidad' => $InfoControvertido->Nombre_cliente,
                        'Nombre_usuario' => $nombre_usuario,
                        'F_registro' => $date
                    ];
                }
    
                break;
            }
        }

        foreach ($reglas as $regla) {
            if (in_array($servicio['Nombre_servicio'], $regla['servicio_origen']) && $servicioNuevo == $regla['servicio_nuevo']) {
                //Caso PCL
                if ($regla['servicio_nuevo'] == 13) {
                    //Informacion para calificacion, recalificacion y revision pension
                    $informacionComite = optional(DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comite_interdisciplinario_eventos as cie')
                    ->leftJoin('sigmel_gestiones.sigmel_informacion_asignacion_eventos as sae', function ($join) {
                        $join->on('cie.ID_evento', '=', 'sae.ID_evento')
                        ->on('cie.Id_Asignacion', '=', 'sae.Id_Asignacion');
                    })->leftJoin('sigmel_gestiones.sigmel_informacion_decreto_eventos as de', function ($join) {
                        $join->on('cie.ID_evento', '=', 'de.ID_evento')
                        ->on('cie.Id_Asignacion', '=', 'de.Id_Asignacion');
                    })->leftJoin('sigmel_gestiones.sigmel_informacion_laboralmente_activo_eventos as lae', function ($join) {
                        $join->on('cie.ID_evento', '=', 'lae.ID_evento')
                        ->on('cie.Id_Asignacion', '=', 'lae.Id_Asignacion');
                    })->leftJoin('sigmel_gestiones.sigmel_informacion_libro2_libro3_eventos as lle', function ($join) {
                        $join->on('cie.ID_evento', '=', 'lle.ID_evento')
                        ->on('cie.Id_Asignacion', '=', 'lle.Id_Asignacion');
                    })->select(
                        'cie.F_visado_comite as FechaDictamen',
                        'sae.Consecutivo_dictamen as Consecutivo_dictamen',
                        'de.N_siniestro as N_siniestro',
                        'de.Porcentaje_pcl as _pcl',
                        'de.Decreto_calificacion as Decreto_calificacion',
                        'de.Total_Deficiencia50 as Deficiencia_50',
                        'de.F_estructuracion as F_estructuracion',
                        'de.Origen as Origen',
                        'lae.Total_laboral_otras_areas as Total_laboral_otras_areas',
                        'lle.Total_discapacidad as Total_discapacidad',
                        'lle.Total_minusvalia as Total_minusvalia'
                    )->where([
                        ['cie.ID_evento', '=', $evento],
                        ['cie.Id_Asignacion', '=', $Id_Asignacion_origen]
                    ])->first());

                    //Diagnostico de la calificacion
                    $diagnostico = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos')
                    ->select('CIE10', 'Nombre_CIE10', 'Lateralidad_CIE10', 'Deficiencia_motivo_califi_condiciones', 'Origen_CIE10', 'Principal')
                    ->where([
                        ['ID_evento', $evento],
                        ['Id_Asignacion', $Id_Asignacion_origen],
                        ['Estado', '=', 'Activo']
                    ])
                    ->get()
                        ->map(function ($item) use ($nombre_usuario, $date, $evento, $nuevo_id_asignacion, $proceso) {
                            $item = (array)$item;
                            $item['Nombre_usuario'] = $nombre_usuario;
                            $item['F_registro'] = $date;
                            $item['ID_evento'] = $evento;
                            $item['Id_Asignacion'] = $nuevo_id_asignacion;
                            $item['Id_proceso'] = $proceso;
                            $item['Item_servicio'] = 'Controvertido Juntas';
                            return $item;
                        });

                    //Informacion del controvertido para el caso PCL, el caul debe estar visado en el caso de calificacion
                    /* Validamos el decreto de calificacion para determinar cuando enviar el valor del rol ocupacional dependiendo
                        de los decretos 1507 de 2014 (id 1), 1507 de 2014 cero (id 2), 917 de 999 (id 3)
                    */
                    if(count($informacionComite) > 0){
                        if ($informacionComite->Decreto_calificacion == 1 || $informacionComite->Decreto_calificacion == 3) {
                            $Total_rol_ocupacional = optional($informacionComite)->Total_laboral_otras_areas;
                        }else{
                            $Total_rol_ocupacional = 0;
                        }
                    }else {
                        $Total_rol_ocupacional = null;
                    }

                    $Controvertido['N_dictamen_controvertido'] = optional($informacionComite)->Consecutivo_dictamen;
                    $Controvertido['F_dictamen_controvertido'] = optional($informacionComite)->FechaDictamen;
                    $Controvertido['N_siniestro'] =  optional($informacionComite)->N_siniestro;
                    $Controvertido['Origen_controversia'] = optional($informacionComite)->Origen;
                    $Controvertido['Manual_de_califi'] = optional($informacionComite)->Decreto_calificacion;
                    $Controvertido['Total_deficiencia'] = optional($informacionComite)->Deficiencia_50;
                    $Controvertido['Total_rol_ocupacional'] = $Total_rol_ocupacional;
                    $Controvertido['Total_discapacidad'] = optional($informacionComite)->Total_discapacidad;
                    $Controvertido['Total_minusvalia'] = optional($informacionComite)->Total_minusvalia;
                    $Controvertido['Porcentaje_pcl'] = optional($informacionComite)->_pcl;
                    $Controvertido['F_estructuracion_contro'] = optional($informacionComite)->F_estructuracion;
                    $Controvertido['Id_Asignacion_Servicio_Anterior'] = $Id_Asignacion_origen;

                    //Se copian los documentos siempre y cuando no se una controversia y cumpla las reglas.
                    if($servicio['Nombre_servicio'] != 'Controversia PCL'){
                        $this->copiarLisdatoGeneralDocumentos($evento,$servicioNuevo,$servicioOrigen);
                    }

                } elseif ($regla['servicio_nuevo'] == 12) {
                    //Caso Origen
                    //Informacion diagonostico
                    $diagnostico = optional(DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos')
                    ->select('CIE10', 'Nombre_CIE10', 'Lateralidad_CIE10', 'Deficiencia_motivo_califi_condiciones', 'Origen_CIE10', 'Lateralidad_CIE10', 'Principal')
                    ->where([
                        ['ID_evento', $evento],
                        ['Id_Asignacion', $Id_Asignacion_origen],
                        ['Estado', '=', 'Activo']
                    ])
                    ->get()
                        ->map(function ($item) use ($nombre_usuario, $date, $evento, $nuevo_id_asignacion, $proceso) {
                            $item = (array)$item;
                            $item['Nombre_usuario'] = $nombre_usuario;
                            $item['F_registro'] = $date;
                            $item['ID_evento'] = $evento;
                            $item['Id_Asignacion'] = $nuevo_id_asignacion;
                            $item['Id_proceso'] = $proceso;
                            $item['Item_servicio'] = 'Controvertido Juntas';
                            return $item;
                        }));

                    //Informacion DTO
                    $origen = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as IAE')
                    ->select('IAE.Consecutivo_dictamen as Consecutivo_dictamen', 'IAE.F_registro as F_registro', 'DTO.N_siniestro as N_siniestro', 'DTO.Origen as DTO_Origen')
                    ->leftJoin('sigmel_gestiones.sigmel_informacion_dto_atel_eventos as DTO', function ($join) {
                        $join->on('DTO.ID_evento', '=', 'IAE.ID_Evento')
                        ->on('DTO.Id_Asignacion', '=', 'IAE.Id_Asignacion');
                    })->where('IAE.Id_Asignacion', $Id_Asignacion_origen)->get();

                    $Controvertido['Origen_controversia'] = optional($origen[0])->DTO_Origen;
                    $Controvertido['N_dictamen_controvertido'] = optional($origen[0])->Consecutivo_dictamen;
                    $Controvertido['F_dictamen_controvertido'] = optional($origen[0])->F_registro;
                    $Controvertido['N_siniestro'] = optional($origen[0])->N_siniestro;
                    $Controvertido['Id_Asignacion_Servicio_Anterior'] = $Id_Asignacion_origen;

                    //Se copian los documentos siempre y cuando no se una controversia y cumpla las reglas.
                    $this->copiarLisdatoGeneralDocumentos($evento,$servicioNuevo,$servicioOrigen);

                    break;
                }

            }
        }

        if(isset($Controvertido)){
            DB::table('sigmel_gestiones.sigmel_informacion_controversia_juntas_eventos')->insert($Controvertido);
            sleep(1);
        }

        //Siempre y cuando el diagnostico no este vacio se insertera la informacion en la tabla
        if(isset($diagnostico) && !$diagnostico->isEmpty()){
            $diagnostico = json_decode(json_encode($diagnostico->toArray()),true);
            DB::table('sigmel_gestiones.sigmel_informacion_diagnosticos_eventos')->insert($diagnostico);
        }
    }

    /**
     * Copia todos los documentos cargados a partir del servicio origen del cual fue creado
     * @param string $evento Id del evento
     * @param int $servicio Id del nuevo sercio que se esta creando.
     * @param int $servicioOrigen Id del servico origen del cual se esta creando el nuvo proceso.
     */
    public function copiarLisdatoGeneralDocumentos(string $evento,int $servicio,int $servicioOrigen){
        $documentos = DB::select('CALL psrvistadocumentos(?,?)', array($evento,$servicioOrigen));
        
        $contador = 0;

        foreach($documentos as $documento){
            if($documento->estado_documento == 'Cargado'){
                $doc = sigmel_registro_documentos_eventos::on('sigmel_gestiones')->select('*')->where('Id_Registro_Documento',$documento->id_Registro_Documento)->get()->toArray();

                $infoDocumento[$contador] =  $doc[0];
            
                $infoDocumento[$contador]['Id_servicio'] = $servicio;

                unset($infoDocumento[$contador]['Id_Registro_Documento']);

                $nombrePdf = "{$infoDocumento[$contador]['Nombre_documento']}";
                $documentoOrigen = public_path("Documentos_Eventos/$evento/$nombrePdf.{$infoDocumento[$contador]['Formato_documento']}");
                $directorioDestino = public_path("Documentos_Eventos/$evento");
                
                $nombrePdf = substr($nombrePdf,0,strlen($nombrePdf)-13);
                $nuevoNombre = "{$nombrePdf}_IdServicio_{$servicio}";

                $documentoDestino = "$directorioDestino/{$nuevoNombre}.{$infoDocumento[$contador]['Formato_documento']}";
                
                // Copia el archivo si existe en el origen
                if (file_exists($documentoOrigen)) {
                    copy($documentoOrigen, $documentoDestino);
                }

                $infoDocumento[$contador]['Nombre_documento'] = $nuevoNombre;
                $contador++;
            }
        }

        if(isset($infoDocumento)){
            sigmel_registro_documentos_eventos::on('sigmel_gestiones')->insert($infoDocumento);
        }
        
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
