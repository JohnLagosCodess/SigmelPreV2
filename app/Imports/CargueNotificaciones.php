<?php

namespace App\Imports;

use App\Models\sigmel_informacion_afiliado_eventos;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\sigmel_informacion_comunicado_eventos;
use App\Models\sigmel_informacion_controversia_juntas_eventos;
use App\Models\sigmel_informacion_correspondencia_eventos;
use App\Models\sigmel_informacion_entidades;
use App\Models\sigmel_informacion_laboral_eventos;
use App\Models\sigmel_lista_departamentos_municipios;

class CargueNotificaciones implements ToModel, WithHeadingRow
{
    
    public function model(array $row)
    {
        $usuario = Auth::user()->name;
        $time = time();
        $date = date("Y-m-d", $time);
        // Probar la conexión a la base de datos
        try {
            DB::connection()->getPdo();
            // Log::info('Conexión a la base de datos establecida correctamente.');
        } catch (\Exception$e) {
            // Log::error('No se puede conectar a la base de datos: ' . $e->getMessage());
            return null;
        }
    
        // Resto del código...
        try {
            $data = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones');
            // Log::info('Modelo cargado correctamente.');
        } catch (\Exception$e) {
            // Log::error('Error al usar el modelo: ' . $e->getMessage());
            return null;
        }

        // Log::info('Datos de la fila antes de la validación: ', $row);

        
        // Convertir desde la serie de fechas de Excel a un timestamp, y luego a Y-m-d
        try {
            $row['f_envio'] = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['f_envio']))->format('Y-m-d');
            $row['f_notificacion'] = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['f_notificacion']))->format('Y-m-d');
            // Log::info('Fechas convertidas: f_envio=' . $row['f_envio'] . ', f_notificacion=' . $row['f_notificacion']);
        } catch (\Exception $e) {
            // Log::error('Error al procesar las fechas en la fila: ' . json_encode($row) . ' - Mensaje: ' . $e->getMessage());
            return null;
        }

        // Validar los datos de la fila
        $validaciones = Validator::make($row, [
            'id_correspondencia' => 'nullable|numeric',
            'id_comunicado' => 'required|numeric',
            'id_asignacion' => 'required|numeric',
            'id_proceso' => 'required|numeric',
            'id_servicio' => 'required|numeric',
            'id_evento' => 'required|string',
            'n_radicado' => 'required|string',
            'n_orden' => 'required|string',
            'nombre_documento' => 'required|string',
            'carpeta_impresion' => 'required|string',
            'destinatario' => 'required|string',
            'tipo_destinatario' => 'required|string',
            'n_guia' => 'required|numeric',
            'folios' => 'required|numeric',
            'f_envio' => 'required|date_format:Y-m-d',
            'f_notificacion' => 'required|date_format:Y-m-d',
            'estado_correspondencia' => 'required|string',
            'destinario_copias' => 'nullable|string',
            'jrci' => 'nullable|string',
            'correspondencia' => 'required|string',
                        
        ]);

        // Log::info('Datos de la fila Despues de la validación: ', $row);
        // Manejar errores de validación según validator 
        if ($validaciones->fails()) {
            // Log::info('Encabezados en el archivo: ' . implode(', ', array_keys($row)));
            // Log::error('Error de validación en la fila: ', $validaciones->errors()->toArray());
            return null;
        }

        // Cambiar el valor de estado_correspondencia basado en las condiciones de la tabla  sigmel_lista_parametros
        switch ($row['estado_correspondencia']) {
            case 'Notificado':
                $row['estado_correspondencia'] = 359;
            break;
            case 'Devuelto':
                $row['estado_correspondencia'] = 360;
            break;
            case 'Pendiente':
                $row['estado_correspondencia'] = 361;
            break;
        }

        // Log::info('Datos de la fila después de modificar estado_correspondencia: ', $row);

        // Realizar una operación de actualización o inserción en la base de datos
        
        //    Convertir los ID a entero
        $idComunicado = intval($row['id_comunicado']);
        $idCorrespondencia = $row['id_correspondencia'] ? intval($row['id_correspondencia']) : null;
        $idAsignacion = intval($row['id_asignacion']);     
        $idProceso = intval($row['id_proceso']);       
        $idServicio = intval($row['id_servicio']);    
        $idEvento = $row['id_evento'];       
        $tipoDestinatario = $row['destinatario']; 
        $carpetaImpresion = $row['carpeta_impresion'];               

        // Buscar  el registro en la tabla sigmel_informacion_comunicado_eventos
        $actualizar_comunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
        ->where('Id_Comunicado', $idComunicado)
        ->first();

        if ($actualizar_comunicado) {
            // Log::info('Registro encontrado: ' . json_encode($actualizar_comunicado));
            // Actualizar el registro
            $actualizar_comunicado->update([
                'Agregar_copia' => $row['destinario_copias'],                
                'JRCI_copia' => $row['jrci'],
                'Correspondencia' => $row['correspondencia'],                                
            ]);
            // Log::info('Registro actualizado correctamente para Id_Comunicado: ' . $idComunicado);
        } else {
            // Log::warning('No se encontró ningún registro con Id_Comunicado: ' . $idComunicado);
        }

        // Consultar los datos del afiliado segun el id Evento
        $info_afiliado = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $idEvento)
        ->first();
        // Verificar si se encontró un registro
        if ($info_afiliado) {
            // Log::info('Afiliado encontrado: ' . json_encode($info_afiliado));            
            // Acceder a los datos del afiliado
            $Nombre_afiliado = $info_afiliado->Nombre_afiliado;
            $N_identificacion = $info_afiliado->Nro_identificacion;                        
        } else {
            // Log::warning('No se encontró ningún afiliado con ID_evento: ' . $idEvento);
        }
        // Log::info('Destinarios : '. $tipoDestinatario);            
        
        // Consultar los datos del afiliado y entidades de los destinatarios

        switch ($tipoDestinatario) {
            case 'Afiliado':
                $info_destinatario_afiliado = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones') 
                ->select('Nombre_afiliado', 'Direccion', 'sldm.Nombre_departamento', 'sldm.Nombre_municipio', 'Telefono_contacto', 'Email', 'Medio_notificacion')
                ->leftJoin('sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'sigmel_informacion_afiliado_eventos.Id_municipio')
                ->where('sigmel_informacion_afiliado_eventos.ID_evento', $idEvento)
                ->first();  
                
                 // Verificar si se encontró un registro
                if ($info_destinatario_afiliado) {
                    // Log::info('Afiliado encontrado: ' . json_encode($info_destinatario_afiliado));            
                    // Acceder a los datos del afiliado
                    $Nombre_destinatario = $info_destinatario_afiliado->Nombre_afiliado;
                    $Direccion_destinatario = $info_destinatario_afiliado->Direccion;                      
                    $Departamento = $info_destinatario_afiliado->Nombre_departamento;                        
                    $Ciudad = $info_destinatario_afiliado->Nombre_municipio;                        
                    $Telefono_destinatario = $info_destinatario_afiliado->Telefono_contacto;                        
                    $Email_destinatario = $info_destinatario_afiliado->Email;
                    $Medio_notificacion = $info_destinatario_afiliado->Medio_notificacion;

                    
                } else {
                    // Log::warning('No se encontró ningún afiliado con ID_evento: ' . $idEvento);
                }
            break;
            case 'Empleador':
            case 'Empresa':
                $info_destinatario_laboral = sigmel_informacion_laboral_eventos::on('sigmel_gestiones') 
                ->select('Empresa', 'Direccion', 'sldm.Nombre_departamento', 'sldm.Nombre_municipio', 'Telefono_empresa', 'Email', 'Medio_notificacion')
                ->leftJoin('sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'sigmel_informacion_laboral_eventos.Id_municipio')
                ->where('sigmel_informacion_laboral_eventos.ID_evento', $idEvento)
                ->first();  
                
                 // Verificar si se encontró un registro
                if ($info_destinatario_laboral) {
                    // Log::info('Afiliado encontrado: ' . json_encode($info_destinatario_afiliado));            
                    // Acceder a los datos del afiliado
                    $Nombre_destinatario = $info_destinatario_laboral->Empresa;
                    $Direccion_destinatario = $info_destinatario_laboral->Direccion;                      
                    $Departamento = $info_destinatario_laboral->Nombre_departamento;                        
                    $Ciudad = $info_destinatario_laboral->Nombre_municipio;                        
                    $Telefono_destinatario = $info_destinatario_laboral->Telefono_empresa;                        
                    $Email_destinatario = $info_destinatario_laboral->Email;
                    $Medio_notificacion = $info_destinatario_laboral->Medio_notificacion;

                    
                } else {
                    // Log::warning('No se encontró ningún afiliado con ID_evento: ' . $idEvento);
                }
            break;
            case 'EPS':
                $info_destinatario_eps = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones') 
                ->select('sie.Nombre_entidad', 'sie.Direccion', 'sldm.Nombre_departamento', 'sldm.Nombre_municipio', 
                'sie.Telefonos', 'sie.Emails', 'slp.Nombre_parametro')
                ->leftJoin('sigmel_informacion_entidades as sie', 'sie.Id_Entidad', '=', 'sigmel_informacion_afiliado_eventos.Id_eps')
                ->leftJoin('sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'sie.Id_Ciudad')
                ->leftJoin('sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sie.Id_Medio_Noti')
                ->where('sigmel_informacion_afiliado_eventos.ID_evento', $idEvento)
                ->first();   
                
                 // Verificar si se encontró un registro
                if ($info_destinatario_eps) {
                    // Log::info('Afiliado encontrado: ' . json_encode($info_destinatario_afiliado));            
                    // Acceder a los datos del afiliado
                    $Nombre_destinatario = $info_destinatario_eps->Nombre_entidad;
                    $Direccion_destinatario = $info_destinatario_eps->Direccion;                      
                    $Departamento = $info_destinatario_eps->Nombre_departamento;                        
                    $Ciudad = $info_destinatario_eps->Nombre_municipio;                        
                    $Telefono_destinatario = $info_destinatario_eps->Telefonos;                        
                    $Email_destinatario = $info_destinatario_eps->Emails;
                    $Medio_notificacion = $info_destinatario_eps->Nombre_parametro;

                    
                } else {
                    // Log::warning('No se encontró ningún afiliado con ID_evento: ' . $idEvento);
                }
            break;
            case 'AFP':
                $info_destinatario_afp = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones') 
                ->select('sie.Nombre_entidad', 'sie.Direccion', 'sldm.Nombre_departamento', 'sldm.Nombre_municipio', 
                'sie.Telefonos', 'sie.Emails', 'slp.Nombre_parametro')
                ->leftJoin('sigmel_informacion_entidades as sie', 'sie.Id_Entidad', '=', 'sigmel_informacion_afiliado_eventos.Id_afp')
                ->leftJoin('sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'sie.Id_Ciudad')
                ->leftJoin('sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sie.Id_Medio_Noti')
                ->where('sigmel_informacion_afiliado_eventos.ID_evento', $idEvento)
                ->first();   
                
                 // Verificar si se encontró un registro
                if ($info_destinatario_afp) {
                    // Log::info('Afiliado encontrado: ' . json_encode($info_destinatario_afiliado));            
                    // Acceder a los datos del afiliado
                    $Nombre_destinatario = $info_destinatario_afp->Nombre_entidad;
                    $Direccion_destinatario = $info_destinatario_afp->Direccion;                      
                    $Departamento = $info_destinatario_afp->Nombre_departamento;                        
                    $Ciudad = $info_destinatario_afp->Nombre_municipio;                        
                    $Telefono_destinatario = $info_destinatario_afp->Telefonos;                        
                    $Email_destinatario = $info_destinatario_afp->Emails;
                    $Medio_notificacion = $info_destinatario_afp->Nombre_parametro;

                    
                } else {
                    // Log::warning('No se encontró ningún afiliado con ID_evento: ' . $idEvento);
                }
            break;
            case 'ARL':
                $info_destinatario_arl = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones') 
                ->select('sie.Nombre_entidad', 'sie.Direccion', 'sldm.Nombre_departamento', 'sldm.Nombre_municipio', 
                'sie.Telefonos', 'sie.Emails', 'slp.Nombre_parametro')
                ->leftJoin('sigmel_informacion_entidades as sie', 'sie.Id_Entidad', '=', 'sigmel_informacion_afiliado_eventos.Id_arl')
                ->leftJoin('sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'sie.Id_Ciudad')
                ->leftJoin('sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sie.Id_Medio_Noti')
                ->where('sigmel_informacion_afiliado_eventos.ID_evento', $idEvento)
                ->first();   
                
                 // Verificar si se encontró un registro
                if ($info_destinatario_arl) {
                    // Log::info('Afiliado encontrado: ' . json_encode($info_destinatario_afiliado));            
                    // Acceder a los datos del afiliado
                    $Nombre_destinatario = $info_destinatario_arl->Nombre_entidad;
                    $Direccion_destinatario = $info_destinatario_arl->Direccion;                      
                    $Departamento = $info_destinatario_arl->Nombre_departamento;                        
                    $Ciudad = $info_destinatario_arl->Nombre_municipio;                        
                    $Telefono_destinatario = $info_destinatario_arl->Telefonos;                        
                    $Email_destinatario = $info_destinatario_arl->Emails;
                    $Medio_notificacion = $info_destinatario_arl->Nombre_parametro;

                    
                } else {
                    // Log::warning('No se encontró ningún afiliado con ID_evento: ' . $idEvento);
                }
            break;
            case 'JRCI':
                $info_destinatario_jrci = sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones') 
                ->select('sie.Nombre_entidad', 'sie.Direccion', 'sldm.Nombre_departamento', 'sldm.Nombre_municipio', 
                'sie.Telefonos', 'sie.Emails', 'slp.Nombre_parametro')
                ->leftJoin('sigmel_informacion_entidades as sie', 'sie.Id_Entidad', '=', 'sigmel_informacion_controversia_juntas_eventos.Jrci_califi_invalidez')
                ->leftJoin('sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'sie.Id_Ciudad')
                ->leftJoin('sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sie.Id_Medio_Noti')
                ->where('sigmel_informacion_controversia_juntas_eventos.Id_Asignacion', $idAsignacion)
                ->first();   
                
                 // Verificar si se encontró un registro
                if ($info_destinatario_jrci) {
                    // Log::info('Afiliado encontrado: ' . json_encode($info_destinatario_afiliado));            
                    // Acceder a los datos del afiliado
                    $Nombre_destinatario = $info_destinatario_jrci->Nombre_entidad;
                    $Direccion_destinatario = $info_destinatario_jrci->Direccion;                      
                    $Departamento = $info_destinatario_jrci->Nombre_departamento;                        
                    $Ciudad = $info_destinatario_jrci->Nombre_municipio;                        
                    $Telefono_destinatario = $info_destinatario_jrci->Telefonos;                        
                    $Email_destinatario = $info_destinatario_jrci->Emails;
                    $Medio_notificacion = $info_destinatario_jrci->Nombre_parametro;

                    
                } else {
                    $Nombre_destinatario = '';
                    $Direccion_destinatario = '';                      
                    $Departamento = '';                        
                    $Ciudad = '';                        
                    $Telefono_destinatario = '';                        
                    $Email_destinatario = '';
                    $Medio_notificacion = '';
                    // Log::warning('No se encontró ningún afiliado con ID_evento: ' . $idEvento);
                }
            break;
            case 'JNCI':
                $info_destinatario_jnci = sigmel_informacion_entidades::on('sigmel_gestiones') 
                ->select('Nombre_entidad', 'Direccion', 'sldm.Nombre_departamento', 'sldm.Nombre_municipio', 
                'Telefonos', 'Emails', 'slp.Nombre_parametro')                
                ->leftJoin('sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'sigmel_informacion_entidades.Id_Ciudad')
                ->leftJoin('sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sigmel_informacion_entidades.Id_Medio_Noti')
                ->where('sigmel_informacion_entidades.Id_Entidad', '111')
                ->first();   
                
                 // Verificar si se encontró un registro
                if ($info_destinatario_jnci) {
                    // Log::info('Afiliado encontrado: ' . json_encode($info_destinatario_afiliado));            
                    // Acceder a los datos del afiliado
                    $Nombre_destinatario = $info_destinatario_jnci->Nombre_entidad;
                    $Direccion_destinatario = $info_destinatario_jnci->Direccion;                      
                    $Departamento = $info_destinatario_jnci->Nombre_departamento;                        
                    $Ciudad = $info_destinatario_jnci->Nombre_municipio;                        
                    $Telefono_destinatario = $info_destinatario_jnci->Telefonos;                        
                    $Email_destinatario = $info_destinatario_jnci->Emails;
                    $Medio_notificacion = $info_destinatario_jnci->Nombre_parametro;

                    
                } else {
                    // Log::warning('No se encontró ningún afiliado con ID_evento: ' . $idEvento);
                }
                break;
            default:
                $Nombre_destinatario = '';
                $Direccion_destinatario = '';                      
                $Departamento = '';                        
                $Ciudad = '';                        
                $Telefono_destinatario = '';                        
                $Email_destinatario = '';
                $Medio_notificacion = '';
            break;
        }

        // Verificar los valores de las variables justo antes del insert
        // Log::info('Valores para insert: ', [
        //     'Nombre_destinatario' => $Nombre_destinatario,
        //     'Direccion_destinatario' => $Direccion_destinatario,
        //     'Departamento' => $Departamento,
        //     'Ciudad' => $Ciudad,
        //     'Telefono_destinatario' => $Telefono_destinatario,
        //     'Email_destinatario' => $Email_destinatario,
        //     'Medio_notificacion' => $Medio_notificacion
        // ]);

        // Si idCorrespondencia no está presente, realizar un insert
        if (!$idCorrespondencia) {
            try {
                sigmel_informacion_correspondencia_eventos::on('sigmel_gestiones')->create([                    
                    'ID_evento' => $row['id_evento'],
                    'Id_Asignacion' => $idAsignacion,
                    'Id_proceso' => $idProceso,
                    'Id_servicio' => $idServicio,
                    'Id_comunicado' => $idComunicado,
                    'Nombre_afiliado' => $Nombre_afiliado,
                    'N_identificacion' => $N_identificacion,
                    'N_radicado' => $row['n_radicado'],
                    'N_orden' => $row['n_orden'],
                    'Tipo_destinatario' => $row['tipo_destinatario'],
                    'Nombre_destinatario' => $Nombre_destinatario,
                    'Direccion_destinatario' => $Direccion_destinatario,
                    'Departamento' => $Departamento,
                    'Ciudad' => $Ciudad,
                    'Telefono_destinatario' => $Telefono_destinatario,
                    'Email_destinatario' => $Email_destinatario,
                    'Medio_notificacion' => $Medio_notificacion,
                    'N_guia' => $row['n_guia'],
                    'Folios' => $row['folios'],
                    'F_envio' => $row['f_envio'],
                    'F_notificacion' => $row['f_notificacion'],
                    'Id_Estado_corresp' => $row['estado_correspondencia'],                
                    'Tipo_correspondencia' => $row['destinatario'],
                    'Nombre_usuario' => $usuario,
                    'F_registro' => $date,

                ]);
                // Log::info('Registro insertado correctamente.');
            } catch (\Exception $e) {
                // Log::error('Error al insertar el registro: ' . $e->getMessage());
            }
        } else {
            // Si idCorrespondencia está presente, realizar un update
            // Si la junta regiona no quedo insertada hace el if para la actualizarla 
            // Y el Else es para actualizar los otros documentos
            if ($carpetaImpresion == 'CARGADO_MANUALMENTE' && $tipoDestinatario == 'JRCI') {

                // Buscar  el registro en la tabla sigmel_informacion_correspondencia_eventos
                $actualizar_correspondencia = sigmel_informacion_correspondencia_eventos::on('sigmel_gestiones')
                ->where('Id_Correspondencia', $idCorrespondencia)
                ->first();
    
                if ($actualizar_correspondencia) {
                    // Log::info('Registro encontrado: ' . json_encode($actualizar_correspondencia));
                    // Actualizar el registro
                    $actualizar_correspondencia->update([
                        'ID_evento' => $row['id_evento'],
                        'Id_Asignacion' => $idAsignacion,
                        'Id_proceso' => $idProceso,
                        'Id_servicio' => $idServicio,
                        'Id_comunicado' => $idComunicado,
                        'Nombre_afiliado' => $Nombre_afiliado,
                        'N_identificacion' => $N_identificacion,
                        'N_radicado' => $row['n_radicado'],
                        'N_orden' => $row['n_orden'],
                        'Tipo_destinatario' => $row['tipo_destinatario'],
                        'Nombre_destinatario' => $Nombre_destinatario,
                        'Direccion_destinatario' => $Direccion_destinatario,
                        'Departamento' => $Departamento,
                        'Ciudad' => $Ciudad,
                        'Telefono_destinatario' => $Telefono_destinatario,
                        'Email_destinatario' => $Email_destinatario,
                        'Medio_notificacion' => $Medio_notificacion,
                        'N_guia' => $row['n_guia'],
                        'Folios' => $row['folios'],
                        'F_envio' => $row['f_envio'],
                        'F_notificacion' => $row['f_notificacion'],
                        'Id_Estado_corresp' => $row['estado_correspondencia'],                
                        'Tipo_correspondencia' => $row['destinatario'],
                        'Nombre_usuario' => $usuario,
                        'F_registro' => $date,
                    ]);
                    // Log::info('Registro actualizado correctamente para Id_Correspondencia: ' . $idCorrespondencia);
    
                }else {
                    // Log::warning('No se encontró ningún registro con Id_Correspondencia: ' . $idCorrespondencia);
                }
                
            } else {                
                // Buscar  el registro en la tabla sigmel_informacion_correspondencia_eventos
                $actualizar_correspondencia = sigmel_informacion_correspondencia_eventos::on('sigmel_gestiones')
                ->where('Id_Correspondencia', $idCorrespondencia)
                ->first();
    
                if ($actualizar_correspondencia) {
                    // Log::info('Registro encontrado: ' . json_encode($actualizar_correspondencia));
                    // Actualizar el registro
                    $actualizar_correspondencia->update([
                        'Tipo_correspondencia' => $row['destinatario'],
                        'Tipo_destinatario' => $row['tipo_destinatario'],
                        'N_guia' => $row['n_guia'],
                        'Folios' => $row['folios'],
                        'F_envio' => $row['f_envio'],
                        'F_notificacion' => $row['f_notificacion'],  
                        'Id_Estado_corresp' => $row['estado_correspondencia'],
                        'Nombre_usuario' => $usuario,
                        'F_registro' => $date,
                    ]);
                    // Log::info('Registro actualizado correctamente para Id_Correspondencia: ' . $idCorrespondencia);
    
                }else {
                    // Log::warning('No se encontró ningún registro con Id_Correspondencia: ' . $idCorrespondencia);
                }
            }
            

        }

    }
}
