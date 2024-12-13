<?php

namespace App\Imports;

use App\Models\sigmel_informacion_afiliado_eventos;
use App\Models\sigmel_informacion_asignacion_eventos;
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
            'id_destinatario' => 'required|string',
            'tipo_destinatario' => 'required|string',
            'n_guia' => 'nullable|numeric',
            'folios' => 'nullable|numeric',
            'f_envio' => 'nullable|date_format:Y-m-d',
            'f_notificacion' => 'nullable|date_format:Y-m-d',                        
        ]);

        // Log::info('Datos de la fila Despues de la validación: ', $row);
        // Manejar errores de validación según validator 
        if ($validaciones->fails()) {
            // Log::info('Encabezados en el archivo: ' . implode(', ', array_keys($row)));
            // Log::error('Error de validación en la fila: ', $validaciones->errors()->toArray());
            return null;
        }

        // Id destinarios como inserta en la tabla de comunicados       
        $idDestinatario = $row['id_destinatario'];
        // Se eliminan las convenciones o las mi primeras 3 letras y el guion y se mantiene solo el consecutivo
        $idDestinatarioConsecutivo = substr($idDestinatario, 4);
        // Se mantiene las convenciones o las mi primeras 3 letras y se elimna el guion y el consecutivo
        $idDestinatarioConvencion = substr($idDestinatario, 0, 3);
        
        // Log::info('id destinarios listo para actualizar: '. $idDestinatario);
        // Log::info('id destinarios listo solo consecutivo: '. $idDestinatarioConsecutivo);
        // Log::info('id destinarios listo solo convencion: '. $idDestinatarioConvencion);

        // Case validacion seteo de null a la fechas de envio y notificacion si vienen vacias

        switch ($row['f_envio']) {
            case '1970-01-01':
                $row['f_envio'] = null;
            break;            
            default:
                $row['f_envio'] = $row['f_envio'];
            break;
        }

        switch ($row['f_notificacion']) {
            case '1970-01-01':
                $row['f_notificacion'] = null;
            break;            
            default:
                $row['f_notificacion'] = $row['f_notificacion'];
            break;
        }

        // // Verificar las fechas
        // Log::info('Valores para insert: ', [
        //     'Numero guia' => $row['n_guia'],
        //     'Fecha envio' => $row['f_envio'],               
        //     'Fecha notificacion' => $row['f_notificacion'],               
        // ]);

        // Case para calcular el estado de la notificacion o estado de correspondencia
        // Case 1: Si guia y fecha de notificacion vienen vacias estado 362 (Pendiente)
        // Case 2: Si guia vienen vacia y fecha de notificacion trae registro estado 362 (Pendiente)
        // Case 3: Si guia trae registro y fecha de notificacion vienen vacia estado 362 (Pendiente)
        // Case 4: Si guia y fecha de notificacion traen registros estado 360 (Notificado)

        switch (true) {
            case (!$row['n_guia'] && $row['n_guia'] == '' && $row['f_notificacion']):
                $Id_Estado_corresp = 362;                                    
            break;
            case (!$row['n_guia'] && $row['n_guia'] == 0 && $row['f_notificacion']):
                $Id_Estado_corresp = 360;                                    
            break;
            case (!$row['n_guia'] && !$row['f_notificacion']):
                $Id_Estado_corresp = 362;
            break;
            case (!$row['n_guia'] && $row['f_notificacion']):
                $Id_Estado_corresp = 362;
            break;
            case ($row['n_guia'] && !$row['f_notificacion']):
                $Id_Estado_corresp = 362;
            break;
            case ($row['n_guia'] && $row['f_notificacion']):
                $Id_Estado_corresp = 360;                    
            break;
        }            
        
        // Verificar los estados
        // Log::info('Valores para insert: ', [
        //     'Estado de notificacion' => $Id_Estado_corresp                
        // ]);
        
        // Buscar  el registro en la tabla sigmel_informacion_correspondencia_eventos
        $actualizar_correspondencia = sigmel_informacion_correspondencia_eventos::on('sigmel_gestiones')
        ->where('Id_destinatario', $idDestinatarioConsecutivo)        
        ->first();
        
        // If para Realizar una operación de actualización o else para inserción en la base de datos
        // en la tabla sigmel_informacion_correspondencia_eventos
        if ($actualizar_correspondencia) {
            // Log::info('Registro encontrado: ' . json_encode($actualizar_correspondencia));
            // Actualizar el registro
            $actualizar_correspondencia->update([
                'Tipo_destinatario' => $row['tipo_destinatario'],                                
                'N_guia' => $row['n_guia'],                
                'Folios' => $row['folios'],
                'F_envio' => $row['f_envio'],                                
                'F_notificacion' => $row['f_notificacion'],
                'Id_Estado_corresp' => $Id_Estado_corresp,

            ]);
            // Log::info('Registro actualizado correctamente para Id_destinatario: ' . $idDestinatarioConsecutivo);
        } else {
            // Log::info('No se encontró correspondencia con Id_destinatario: ' . $idDestinatario . ' Se procede hacer insercion');

            // Consultar los ids (ID_evento, Id_Asignacion) de la tabla de comunicados eventos
            $info_comunicado_ids = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
            ->select('Id_Comunicado', 'ID_evento', 'Id_Asignacion', 'Id_proceso', 'N_radicado', 'Nombre_destinatario', 'Otro_destinatario', 'Destinatario') 
            ->where('Id_Destinatarios', 'like', '%'.$idDestinatario.'%')        
            ->first();
            // Verificar si se encontró un registro
            if ($info_comunicado_ids) {
                // Log::info('ids encontrados: ' . json_encode($info_comunicado_ids));            
                // Acceder a los datos del los ids
                $Id_Comunicado = $info_comunicado_ids->Id_Comunicado; 
                $ID_evento = $info_comunicado_ids->ID_evento;
                $Id_Asignacion = $info_comunicado_ids->Id_Asignacion;                        
                $Id_proceso = $info_comunicado_ids->Id_proceso;
                $N_radicado = $info_comunicado_ids->N_radicado;
                $Nombre_destinatarioId = $info_comunicado_ids->Nombre_destinatario;
                $Otro_destinatario = $info_comunicado_ids->Otro_destinatario;
                $DestinatarioPri = $info_comunicado_ids->Destinatario;
                // Consultar los ids (Id_servicio) de la tabla de asignacion eventos
                $info_asignacion_ids = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                ->select('Id_servicio', 'N_de_orden') 
                ->where('Id_Asignacion', $Id_Asignacion)        
                ->first();
                // Verificar si se encontró un registro
                if ($info_asignacion_ids) {
                    // Log::info('ids servicios y orden: ' . json_encode($info_asignacion_ids));            
                    // Acceder a los datos del los ids
                    $Id_servicio = $info_asignacion_ids->Id_servicio;
                    $N_de_orden = $info_asignacion_ids->N_de_orden; 
    
                } else {
                    // Log::warning('No se encontró ningún id con Id_Asignacion: ' . $Id_Asignacion);
                }
    
    
                // Consultar los Nombres e identificacion del afiliado) de la tabla de afiliado eventos
                $info_afiliado_nom_iden = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones')
                ->select('Nombre_afiliado', 'Nro_identificacion') 
                ->where('ID_evento', $ID_evento)        
                ->first();
                // Verificar si se encontró un registro
                if ($info_afiliado_nom_iden) {
                    // Log::info('Nombres e identificacion del afiliado: ' . json_encode($info_afiliado_nom_iden));            
                    // Acceder a los datos del los ids
                    $Nombre_afiliado = $info_afiliado_nom_iden->Nombre_afiliado;
                    $Nro_identificacion = $info_afiliado_nom_iden->Nro_identificacion; 
    
                } else {
                    // Log::warning('No se encontró ningún registro con ID_evento: ' . $ID_evento);
                }
    
                // Consultar los datos del afiliado y entidades de los destinatarios según el case
                // Case 1: Capturar datos del afiliado
                // Case 2: Capturar datos del empleador
                // Case 3: Capturar datos del eps
                // Case 4: Capturar datos del afp
                // Case 5: Capturar datos del arl
                // Case 6: Capturar datos del jrci
                // Case 7: Capturar datos del jnci
                // Case 8: Capturar datos del afp_conocimiento
                switch ($idDestinatarioConvencion) {
                    case 'AFI':
                        $info_destinatario_afiliado = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones') 
                        ->select('Nombre_afiliado', 'Direccion', 'sldm.Nombre_departamento', 'sldm.Nombre_municipio', 'Telefono_contacto', 'Email', 'Medio_notificacion')
                        ->leftJoin('sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'sigmel_informacion_afiliado_eventos.Id_municipio')
                        ->where('sigmel_informacion_afiliado_eventos.ID_evento', $ID_evento)
                        ->first();  
                        
                         // Verificar si se encontró un registro
                        if ($info_destinatario_afiliado) {
                            // Log::info('Afiliado encontrado: ' . json_encode($info_destinatario_afiliado));            
                            // Acceder a los datos del 
                            $Tipo_correspondencia = 'Afiliado';
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
                    case 'EMP':
                        $info_destinatario_laboral = sigmel_informacion_laboral_eventos::on('sigmel_gestiones') 
                        ->select('Empresa', 'Direccion', 'sldm.Nombre_departamento', 'sldm.Nombre_municipio', 'Telefono_empresa', 'Email', 'Medio_notificacion')
                        ->leftJoin('sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'sigmel_informacion_laboral_eventos.Id_municipio')
                        ->where('sigmel_informacion_laboral_eventos.ID_evento', $ID_evento)
                        ->first();  
                        
                         // Verificar si se encontró un registro
                        if ($info_destinatario_laboral) {
                            // Log::info('Afiliado encontrado: ' . json_encode($info_destinatario_afiliado));            
                            // Acceder a los datos del afiliado
                            $Tipo_correspondencia = 'Empleador';
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
                        // Convertimos el valor de variable del excel idDestinatarioConvencion igual a la de la DB DestinatarioPri
                        $idDestinatarioConvencion_minus = ucfirst(strtolower($idDestinatarioConvencion));                          
                        // Validar si tiene otro destinatario y si no, se trae info del afiliado                        
                        if ($Otro_destinatario == 1 && $DestinatarioPri == $idDestinatarioConvencion_minus) {
                            $info_destinatario_eps = sigmel_informacion_entidades::on('sigmel_gestiones') 
                            ->select('Nombre_entidad', 'Direccion', 'sldm.Nombre_departamento', 'sldm.Nombre_municipio', 
                            'Telefonos', 'Emails', 'slp.Nombre_parametro')                
                            ->leftJoin('sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'sigmel_informacion_entidades.Id_Ciudad')
                            ->leftJoin('sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sigmel_informacion_entidades.Id_Medio_Noti')
                            ->where('sigmel_informacion_entidades.Id_Entidad', $Nombre_destinatarioId)
                            ->first(); 

                             // Verificar si se encontró un registro
                            if ($info_destinatario_eps) {
                                // Log::info('Afiliado encontrado: ' . json_encode($info_destinatario_afiliado));            
                                // Acceder a los datos de la entidad
                                $Tipo_correspondencia = 'eps';
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
                        } else {
                            $info_destinatario_eps = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones') 
                            ->select('sie.Nombre_entidad', 'sie.Direccion', 'sldm.Nombre_departamento', 'sldm.Nombre_municipio', 
                            'sie.Telefonos', 'sie.Emails', 'slp.Nombre_parametro')
                            ->leftJoin('sigmel_informacion_entidades as sie', 'sie.Id_Entidad', '=', 'sigmel_informacion_afiliado_eventos.Id_eps')
                            ->leftJoin('sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'sie.Id_Ciudad')
                            ->leftJoin('sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sie.Id_Medio_Noti')
                            ->where('sigmel_informacion_afiliado_eventos.ID_evento', $ID_evento)
                            ->first();   
                            
                             // Verificar si se encontró un registro
                            if ($info_destinatario_eps) {
                                // Log::info('Afiliado encontrado: ' . json_encode($info_destinatario_afiliado));            
                                // Acceder a los datos del afiliado
                                $Tipo_correspondencia = 'eps';
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
                        }                        
                    break;
                    case 'AFP':
                        // Convertimos el valor de variable del excel idDestinatarioConvencion igual a la de la DB DestinatarioPri
                        $idDestinatarioConvencion_minus = ucfirst(strtolower($idDestinatarioConvencion));            
                        // Validar si tiene otro destinatario y si no, se trae info del afiliado                          
                        if ($Otro_destinatario == 1 && $DestinatarioPri == $idDestinatarioConvencion_minus) {
                            $info_destinatario_afp = sigmel_informacion_entidades::on('sigmel_gestiones') 
                            ->select('Nombre_entidad', 'Direccion', 'sldm.Nombre_departamento', 'sldm.Nombre_municipio', 
                            'Telefonos', 'Emails', 'slp.Nombre_parametro')                
                            ->leftJoin('sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'sigmel_informacion_entidades.Id_Ciudad')
                            ->leftJoin('sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sigmel_informacion_entidades.Id_Medio_Noti')
                            ->where('sigmel_informacion_entidades.Id_Entidad', $Nombre_destinatarioId)
                            ->first(); 

                             // Verificar si se encontró un registro
                            if ($info_destinatario_afp) {
                                // Log::info('Afiliado encontrado: ' . json_encode($info_destinatario_afiliado));            
                                // Acceder a los datos de la entidad
                                $Tipo_correspondencia = 'afp';
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
                        }  else {
                            
                            $info_destinatario_afp = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones') 
                            ->select('sie.Nombre_entidad', 'sie.Direccion', 'sldm.Nombre_departamento', 'sldm.Nombre_municipio', 
                            'sie.Telefonos', 'sie.Emails', 'slp.Nombre_parametro')
                            ->leftJoin('sigmel_informacion_entidades as sie', 'sie.Id_Entidad', '=', 'sigmel_informacion_afiliado_eventos.Id_afp')
                            ->leftJoin('sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'sie.Id_Ciudad')
                            ->leftJoin('sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sie.Id_Medio_Noti')
                            ->where('sigmel_informacion_afiliado_eventos.ID_evento', $ID_evento)
                            ->first();   
                            
                             // Verificar si se encontró un registro
                            if ($info_destinatario_afp) {
                                // Log::info('Afiliado encontrado: ' . json_encode($info_destinatario_afiliado));            
                                // Acceder a los datos del afiliado
                                $Tipo_correspondencia = 'afp';
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
                        }
                        
                    break;
                    case 'ARL':
                        // Convertimos el valor de variable del excel idDestinatarioConvencion igual a la de la DB DestinatarioPri
                        $idDestinatarioConvencion_minus = ucfirst(strtolower($idDestinatarioConvencion));                                                             
                        // Validar si tiene otro destinatario y si no, se trae info del afiliado                        
                        if ($Otro_destinatario == 1 && $DestinatarioPri == $idDestinatarioConvencion_minus) {
                            $info_destinatario_arl = sigmel_informacion_entidades::on('sigmel_gestiones') 
                            ->select('Nombre_entidad', 'Direccion', 'sldm.Nombre_departamento', 'sldm.Nombre_municipio', 
                            'Telefonos', 'Emails', 'slp.Nombre_parametro')                
                            ->leftJoin('sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'sigmel_informacion_entidades.Id_Ciudad')
                            ->leftJoin('sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sigmel_informacion_entidades.Id_Medio_Noti')
                            ->where('sigmel_informacion_entidades.Id_Entidad', $Nombre_destinatarioId)
                            ->first(); 

                             // Verificar si se encontró un registro
                            if ($info_destinatario_arl) {
                                // Log::info('Afiliado encontrado: ' . json_encode($info_destinatario_afiliado));            
                                // Acceder a los datos de la entidad
                                $Tipo_correspondencia = 'arl';
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
                        }  else {
                            
                            $info_destinatario_arl = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones') 
                            ->select('sie.Nombre_entidad', 'sie.Direccion', 'sldm.Nombre_departamento', 'sldm.Nombre_municipio', 
                            'sie.Telefonos', 'sie.Emails', 'slp.Nombre_parametro')
                            ->leftJoin('sigmel_informacion_entidades as sie', 'sie.Id_Entidad', '=', 'sigmel_informacion_afiliado_eventos.Id_arl')
                            ->leftJoin('sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'sie.Id_Ciudad')
                            ->leftJoin('sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sie.Id_Medio_Noti')
                            ->where('sigmel_informacion_afiliado_eventos.ID_evento', $ID_evento)
                            ->first();   
                            
                             // Verificar si se encontró un registro
                            if ($info_destinatario_arl) {
                                // Log::info('Afiliado encontrado: ' . json_encode($info_destinatario_afiliado));            
                                // Acceder a los datos del afiliado
                                $Tipo_correspondencia = 'arl';
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
                        }                        
                    break;
                    case 'JRC':
                        // Convertimos el valor de variable del excel idDestinatarioConvencion igual a la de la DB DestinatarioPri
                        $idDestinatarioConvencion_minus = ucfirst(strtolower($idDestinatarioConvencion));
                        // Conservamos las primeras tres letras del destinatario de la DB  DestinatarioPri
                        $DestinatarioPrici = substr($DestinatarioPri, 0, 3);
                        // Validar si tiene otro destinatario y si no, se trae info del afiliado                        
                        if ($Otro_destinatario == 1 && $DestinatarioPrici == $idDestinatarioConvencion) {
                            $info_destinatario_jrci = sigmel_informacion_entidades::on('sigmel_gestiones') 
                            ->select('Nombre_entidad', 'Direccion', 'sldm.Nombre_departamento', 'sldm.Nombre_municipio', 
                            'Telefonos', 'Emails', 'slp.Nombre_parametro')                
                            ->leftJoin('sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'sigmel_informacion_entidades.Id_Ciudad')
                            ->leftJoin('sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sigmel_informacion_entidades.Id_Medio_Noti')
                            ->where('sigmel_informacion_entidades.Id_Entidad', $Nombre_destinatarioId)
                            ->first(); 

                             // Verificar si se encontró un registro
                            if ($info_destinatario_jrci) {
                                // Log::info('Afiliado encontrado: ' . json_encode($info_destinatario_afiliado));            
                                // Acceder a los datos de la entidad
                                $Tipo_correspondencia = 'jrci';
                                $Nombre_destinatario = $info_destinatario_jrci->Nombre_entidad;
                                $Direccion_destinatario = $info_destinatario_jrci->Direccion;                      
                                $Departamento = $info_destinatario_jrci->Nombre_departamento;                        
                                $Ciudad = $info_destinatario_jrci->Nombre_municipio;                        
                                $Telefono_destinatario = $info_destinatario_jrci->Telefonos;                        
                                $Email_destinatario = $info_destinatario_jrci->Emails;
                                $Medio_notificacion = $info_destinatario_jrci->Nombre_parametro;
                                
                            } else {
                                // Log::warning('No se encontró ningún afiliado con ID_evento: ' . $idEvento);
                            }
                        } else {
                            $info_destinatario_jrci = sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones') 
                            ->select('sie.Nombre_entidad', 'sie.Direccion', 'sldm.Nombre_departamento', 'sldm.Nombre_municipio', 
                            'sie.Telefonos', 'sie.Emails', 'slp.Nombre_parametro')
                            ->leftJoin('sigmel_informacion_entidades as sie', 'sie.Id_Entidad', '=', 'sigmel_informacion_controversia_juntas_eventos.Jrci_califi_invalidez')
                            ->leftJoin('sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'sie.Id_Ciudad')
                            ->leftJoin('sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sie.Id_Medio_Noti')
                            ->where('sigmel_informacion_controversia_juntas_eventos.Id_Asignacion', $Id_Asignacion)
                            ->first();   
                            
                             // Verificar si se encontró un registro
                            if ($info_destinatario_jrci) {
                                // Log::info('Afiliado encontrado: ' . json_encode($info_destinatario_afiliado));            
                                // Acceder a los datos del afiliado
                                $Tipo_correspondencia = 'jrci';
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
                        }
                    break;
                    case 'JNC':
                        $info_destinatario_jnci = sigmel_informacion_entidades::on('sigmel_gestiones') 
                        ->select('Nombre_entidad', 'Direccion', 'sldm.Nombre_departamento', 'sldm.Nombre_municipio', 
                        'Telefonos', 'Emails', 'slp.Nombre_parametro')                
                        ->leftJoin('sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'sigmel_informacion_entidades.Id_Ciudad')
                        ->leftJoin('sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sigmel_informacion_entidades.Id_Medio_Noti')
                        ->where('sigmel_informacion_entidades.IdTipo_entidad', '5')
                        ->first();   
                        
                         // Verificar si se encontró un registro
                        if ($info_destinatario_jnci) {
                            // Log::info('Afiliado encontrado: ' . json_encode($info_destinatario_afiliado));            
                            // Acceder a los datos del afiliado
                            $Tipo_correspondencia = 'jnci';
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
                    case 'FPC':
                        $info_destinatario_afp_conocimiento = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones') 
                        ->select('sie.Nombre_entidad', 'sie.Direccion', 'sldm.Nombre_departamento', 'sldm.Nombre_municipio', 
                        'sie.Telefonos', 'sie.Emails', 'slp.Nombre_parametro')
                        ->leftJoin('sigmel_informacion_entidades as sie', 'sie.Id_Entidad', '=', 'sigmel_informacion_afiliado_eventos.Id_afp_entidad_conocimiento')
                        ->leftJoin('sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'sie.Id_Ciudad')
                        ->leftJoin('sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sie.Id_Medio_Noti')
                        ->where('sigmel_informacion_afiliado_eventos.ID_evento', $ID_evento)
                        ->first();   
                        
                         // Verificar si se encontró un registro
                        if ($info_destinatario_afp_conocimiento) {
                            // Log::info('Afiliado encontrado: ' . json_encode($info_destinatario_afiliado));            
                            // Acceder a los datos del afiliado
                            $Tipo_correspondencia = 'afp_conocimiento';
                            $Nombre_destinatario = $info_destinatario_afp_conocimiento->Nombre_entidad;
                            $Direccion_destinatario = $info_destinatario_afp_conocimiento->Direccion;                      
                            $Departamento = $info_destinatario_afp_conocimiento->Nombre_departamento;                        
                            $Ciudad = $info_destinatario_afp_conocimiento->Nombre_municipio;                        
                            $Telefono_destinatario = $info_destinatario_afp_conocimiento->Telefonos;                        
                            $Email_destinatario = $info_destinatario_afp_conocimiento->Emails;
                            $Medio_notificacion = $info_destinatario_afp_conocimiento->Nombre_parametro;
        
                            
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
                    default:
                        $Tipo_correspondencia = '';
                        $Nombre_destinatario = '';
                        $Direccion_destinatario = '';                      
                        $Departamento = '';                        
                        $Ciudad = '';                        
                        $Telefono_destinatario = '';                        
                        $Email_destinatario = '';
                        $Medio_notificacion = '';
                    break;
                }
        
                // Verificar los valores de las variables de las entidades justo antes del insert
                // Log::info('Valores para insert: ', [
                //     'Tipo_correspondencia' => $Tipo_correspondencia,
                //     'Nombre_destinatario' => $Nombre_destinatario,
                //     'Direccion_destinatario' => $Direccion_destinatario,
                //     'Departamento' => $Departamento,
                //     'Ciudad' => $Ciudad,
                //     'Telefono_destinatario' => $Telefono_destinatario,
                //     'Email_destinatario' => $Email_destinatario,
                //     'Medio_notificacion' => $Medio_notificacion
                // ]);   
                
                // Log::info('id destinarios listo solo consecutivo para insertar: '. $idDestinatarioConsecutivo);            
    
                // Verificar los valores de todas las variables justo antes del insert
                // Log::info('Valores para insert: ', [
                //     'ID_evento' => $ID_evento,
                //         'Id_Asignacion' => $Id_Asignacion,
                //         'Id_proceso' => $Id_proceso,
                //         'Id_servicio' => $Id_servicio,
                //         'Id_comunicado' => $Id_Comunicado,
                //         'Nombre_afiliado' => $Nombre_afiliado,
                //         'N_identificacion' => $Nro_identificacion,
                //         'N_radicado' => $N_radicado,
                //         'N_orden' => $N_de_orden,
                //         'Tipo_destinatario' => $row['tipo_destinatario'],
                //         'Nombre_destinatario' => $Nombre_destinatario,
                //         'Direccion_destinatario' => $Direccion_destinatario,
                //         'Departamento' => $Departamento,
                //         'Ciudad' => $Ciudad,
                //         'Telefono_destinatario' => $Telefono_destinatario,
                //         'Email_destinatario' => $Email_destinatario,
                //         'Medio_notificacion' => $Medio_notificacion,
                //         'N_guia' => $row['n_guia'],
                //         'Folios' => $row['folios'],
                //         'F_envio' => $row['f_envio'],
                //         'F_notificacion' => $row['f_notificacion'],
                //         'Tipo_correspondencia' => $Tipo_correspondencia,
                //         'Id_Estado_corresp' => $Id_Estado_corresp,  
                //         'Id_destinatario' => $idDestinatarioConsecutivo,                                    
                //         'Nombre_usuario' => $usuario,
                //         'F_registro' => $date,
                // ]); 
    
                try {
                    sigmel_informacion_correspondencia_eventos::on('sigmel_gestiones')->create([                    
                        'ID_evento' => $ID_evento,
                        'Id_Asignacion' => $Id_Asignacion,
                        'Id_proceso' => $Id_proceso,
                        'Id_servicio' => $Id_servicio,
                        'Id_comunicado' => $Id_Comunicado,
                        'Nombre_afiliado' => $Nombre_afiliado,
                        'N_identificacion' => $Nro_identificacion,
                        'N_radicado' => $N_radicado,
                        'N_orden' => $N_de_orden,
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
                        'Tipo_correspondencia' => $Tipo_correspondencia,
                        'Id_Estado_corresp' => $Id_Estado_corresp,  
                        'Id_destinatario' => $idDestinatarioConsecutivo,                                    
                        'Nombre_usuario' => $usuario,
                        'F_registro' => $date,
                    ]);
                    // Log::info('Registro insertado correctamente para Id_destinatario: ' . $idDestinatarioConsecutivo);                
                } catch (\Exception $e) {
                    // Log::error('Error al insertar el registro: ' . $e->getMessage());
                }
            } else {
                // Log::warning('No se encontró ningún id con Id_Destinatarios: ' . $idDestinatario);
            }

        }

        // Validacion de los id comunicados a actualizar
        // Consultar los ids (Id_Comunicado) de la tabla de comunicados eventos
        $info_comunicados_ids = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
        ->select('Id_Comunicado') 
        ->where('Id_Destinatarios', 'like', '%'.$idDestinatario.'%')        
        ->first();
        // Verificar si se encontró un registro
        if ($info_comunicados_ids) {
            // Log::info('ids encontrados: ' . json_encode($info_comunicados_ids));            
            // Acceder a los datos del los ids
            $Id_Comunicados = $info_comunicados_ids->Id_Comunicado;             
            // Consultar segun el Id_Comunicado en la tabla de correspondescia
            // Para captura de los Id_Estado_corresp
            $info_estados_corres_ids = sigmel_informacion_correspondencia_eventos::on('sigmel_gestiones')
            ->select('Id_comunicado', 'Id_Estado_corresp')
            ->where('Id_comunicado', $Id_Comunicados)
            ->get(); 
    
            if ($info_estados_corres_ids->isNotEmpty()) {
                // Obtener el Id_comunicado de los resultados 
                $id_comunicado = $info_estados_corres_ids->first()->Id_comunicado;
    
                // Verificar si todos los estados son 360
                if ($info_estados_corres_ids->every(function ($item) {
                    return $item->Id_Estado_corresp == 360;
                })) {
                    // Todos los estados son 360
                    // Log::info('Todos los estados del comunicado son 360. Id_comunicado: ' . $id_comunicado);
    
                    // Buscar  el registro en la tabla sigmel_informacion_comunicado_eventos
                    $actualizar_comunicados = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
                    ->where('Id_Comunicado', $id_comunicado)        
                    ->first();
                                
                    // Validar si hay registro para realizar la actualizacion
                    if ($actualizar_comunicados) {
                        // Log::info('Registro encontrado: ' . json_encode($actualizar_comunicados));
                        // Actualizar el registro
                        $actualizar_comunicados->update([
                            'Estado_Notificacion' => 357,                                
                        ]);
                        // Log::info('Registro actualizado correctamente para Id_Comunicado: ' . $id_comunicado);
                    } else {
                        // Log::info('No se encontró con Id_Comunicado: ' . $id_comunicado);
                    }
    
                } 
                // Verificar si hay una mezcla de 360 y 362
                elseif ($info_estados_corres_ids->contains('Id_Estado_corresp', 362) && $info_estados_corres_ids->contains('Id_Estado_corresp', 360)) {
                    // Hay una mezcla de 360 y 362
                    // Log::info('Hay una mezcla de estados 360 y 362. Id_comunicado: ' . $id_comunicado);
    
                    // Buscar  el registro en la tabla sigmel_informacion_comunicado_eventos
                    $actualizar_comunicados = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
                    ->where('Id_Comunicado', $id_comunicado)        
                    ->first();
                                
                    // Validar si hay registro para realizar la actualizacion
                    if ($actualizar_comunicados) {
                        // Log::info('Registro encontrado: ' . json_encode($actualizar_comunicados));
                        // Actualizar el registro
                        $actualizar_comunicados->update([
                            'Estado_Notificacion' => 358,                                
                        ]);
                        // Log::info('Registro actualizado correctamente para Id_Comunicado: ' . $id_comunicado);
                    } else {
                        // Log::info('No se encontró con Id_Comunicado: ' . $id_comunicado);
                    }
                }                   
                
            }

            // Luego consultar los estados y el tipo de correspondencia

            $info_estados_corres_tipos = sigmel_informacion_correspondencia_eventos::on('sigmel_gestiones')
            ->select('Id_comunicado', 'Id_Estado_corresp', 'Tipo_correspondencia')
            ->where([
                ['Id_comunicado', $Id_Comunicados],
                ['Id_Estado_corresp', 360],
            ])
            ->get(); 

            if ($info_estados_corres_tipos->isNotEmpty()) {
                // Obtener el Id_comunicado de los resultados 
                $id_comunicado = $info_estados_corres_tipos->first()->Id_comunicado;
                $array_tipo_corres = $info_estados_corres_tipos->map(function($item) {
                    return [
                        'Tipo_correspondencia' => $item->Tipo_correspondencia,
                        'Id_comunicado' => $item->Id_comunicado
                    ];
                })->toArray();
                // Log::info('tipos de correspondencias:'. json_encode($array_tipo_corres));

                // Buscar  el registro en la tabla sigmel_informacion_comunicado_eventos
                $info_comunicados_corres = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
                ->select('Id_Comunicado', 'Correspondencia')
                ->where('Id_Comunicado', $id_comunicado)    
                ->first();

                if ($info_comunicados_corres) {
                    $array_comunicado_corres = [
                        'Id_Comunicado' => $info_comunicados_corres->Id_Comunicado,
                        'Correspondencia' => $info_comunicados_corres->Correspondencia
                    ];
    
                    // Log::info('correspondecia comunicados: '. json_encode($array_comunicado_corres));                    
                }
                // Convertimos a array y eliminamos espacios
                $array_correspondencias_comuni = array_map('trim', explode(',', $array_comunicado_corres['Correspondencia'])); 
                // se crea array nuevo
                $combinar_array_comuni_corres = [];
                // se combinan los arrays
                foreach ($array_tipo_corres as $tipos) {
                    if ($tipos['Id_comunicado'] == $array_comunicado_corres['Id_Comunicado'] && 
                        !in_array($tipos['Tipo_correspondencia'], $array_correspondencias_comuni)) {
                        $combinar_array_comuni_corres[] = $tipos;
                    }
                }

                // Log::info('Correspondencias que no están en el primer array: ' . json_encode($combinar_array_comuni_corres));

                // Verificamos que el array no esté vacío y que los Id_Comunicado sean iguales
                if (!empty($combinar_array_comuni_corres) && $array_comunicado_corres['Id_Comunicado'] == $combinar_array_comuni_corres[0]['Id_comunicado']) {

                    // Verificar si 'Correspondencia' está vacía o solo contiene espacios
                    if (!empty(trim($array_comunicado_corres['Correspondencia']))) {
                        // Convertimos el string de Correspondencia a un array
                        $correspondencias_array = array_map('trim', explode(',', $array_comunicado_corres['Correspondencia']));
                    } else {
                        // Si está vacía, inicializamos el array vacío
                        $correspondencias_array = [];
                    }

                    // Iteramos sobre el segundo array y agregamos las correspondencias que no estén en el primero
                    foreach ($combinar_array_comuni_corres as $item) {
                        if (!in_array($item['Tipo_correspondencia'], $correspondencias_array)) {
                            $correspondencias_array[] = $item['Tipo_correspondencia'];
                        }
                    }

                    // Convertimos el array de correspondencias de nuevo a una cadena separada por comas
                    $nueva_correspondencia = implode(', ', $correspondencias_array);

                    // Creamos el nuevo array
                    $array_correspondecia_final = [
                        'Id_Comunicado' => $array_comunicado_corres['Id_Comunicado'],
                        'Correspondencia' => $nueva_correspondencia
                    ];

                    // Imprimir el nuevo array para depuración
                } else {
                    // Si $combinar_array_comuni_corres está vacío o no coincide el Id_Comunicado
                    $array_correspondecia_final = $array_comunicado_corres;
                }
                // Log::info('array combinado final: ' . json_encode($array_correspondecia_final));
           
                // Validar si hay registro para realizar la actualizacion
                if ($info_comunicados_corres) {
                    // Log::info('Registro encontrado: ' . json_encode($info_comunicados_corres));
                    // Actualizar el registro
                    $info_comunicados_corres->update([
                        'Correspondencia' => $array_correspondecia_final['Correspondencia'],
                    ]);
                    // Log::info('Registro actualizado correctamente para Id_Comunicado: ' . $id_comunicado);
                } else {
                    // Log::info('No se encontró con Id_Comunicado: ' . $id_comunicado);
                }
                
            }

        } else {
            // Log::warning('No se encontró ningún id con Id_Destinatarios: ' . $idDestinatario);
        }
        
        
    }
}
