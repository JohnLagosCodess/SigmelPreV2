    {{--Modal  Crear expediente--}}
    <div class="row">
        <div class="contenedor_sol_crear_expediente" style="float: left;">
            <x-adminlte-modal id="modalCrearExpediente" title="Crear expediente" theme="info" icon="fas fa-folder-open" size='xl' disable-animations>
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-danger d-none" id='alert_expediente' role="alert">
                            <i class="fas fa-info-circle"></i> <strong>Importante:</strong> No puede generar un chequeo ya que el evento {{$array_datos_calificacionJuntas[0]->ID_evento}} no tiene comunicados, debe cargar uno para  poder continuar.
                        </div>
                        <div class="alert alert-warning" id='info_expediente' role="alert">
                            <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Para poder generar la lista de chequeo y expediente debe cumplir las siguientes condiciones; <br>
                            <strong>1:</strong> Selecionar minimo un documento de la tabla de chequeo <br>
                            <strong>2:</strong> Después del guardado de la lista de chequeo debe ingresar las posiciones de la tabla de chequeo<br>
                            <strong>3:</strong> Ingresar minimo un documento en la tabla de expediente con su posicion
                        </div>
                        <form id="form_crear_ExpedientesJuntas" method="POST">
                            @csrf
                            <div class="card-body">                                
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="fecha_seguimiento">Nombre de afiliado</label>
                                            <input class="form-control" type="text" name="nombre_afiliado" id="nombre_afiliado" value="{{$array_datos_calificacionJuntas[0]->Nombre_afiliado}}" readonly>
                                        </div> 
                                    </div>   
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="fecha_seguimiento">N° de Identificacion</label>
                                            <input class="form-control" type="text" name="n_identificacion" id="n_identificacion" value="{{$array_datos_calificacionJuntas[0]->Nro_identificacion}}" readonly>
                                        </div> 
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="fecha_seguimiento">Tipo de evento</label>
                                            <input class="form-control" type="text" name="tipo_evento" id="tipo_evento" value="{{$array_datos_calificacionJuntas[0]->Nombre_evento}}" readonly>
                                        </div> 
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="Posicion_chequeo">Última Posición</label>
                                            <input class="form-control" type="text" name="Posicion_chequeo" id="Posicion_chequeo" value="{{$posicionExpediente}}" readonly>
                                        </div> 
                                    </div>
                                    @if (count($archivo_noencontrado) > 0 && count($archivo_encontrado) == 0)
                                        <input class="form-control" type="hidden" name="Alerta_chequeo" id="Alerta_chequeo" value="{{count($archivo_noencontrado)}}" readonly>                                        
                                    @elseif(count($archivo_noencontrado) == 0 && count($archivo_encontrado) > 0)
                                        <input class="form-control" type="hidden" name="Alerta_chequeo" id="Alerta_chequeo" value="{{count($archivo_encontrado)}}" readonly>                                        
                                    @elseif (count($archivo_encontrado) > 0 && count($archivo_noencontrado) > 0)
                                        <input class="form-control" type="hidden" name="Alerta_chequeo" id="Alerta_chequeo" value="{{count($archivo_encontrado).'-'.count($archivo_noencontrado)}}" readonly>                                        
                                    @endif
                                    {{--<div class="col-4">
                                        <div class="form-group">
                                            <label for="fecha_seguimiento">Seleccionar comunicado</label>
                                            <select class="custom-select" id="historial_comunicados">
                                                <option></option>
                                            </select>
                                        </div> 
                                    </div>  --}}                             
                                </div>
                                {{-- <div class="row mt-1">
                                    <div class="col-4">
                                        <div class="form-group d-none" id="ver_chequeo">
                                            <a for="lista_chequeo" style="cursor: pointer;" >Visualizar lista de chequeo</a>
                                            <i class="far fa-eye text-info"></i>
                                        </div> 
                                    </div>  
                                </div> --}}
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table table-responsive" style="overflow-y:scroll; height:400px; position:relative">
                                            <table class="table table-striped table-bordered table-hover" width="100%">
                                                <thead class="bg-info">
                                                    <tr><th class="bg-info text-center titulo_lista_chequeo" colspan="4">Lista de chequeo</th></tr>
                                                    <tr>
                                                        <th>N°</th>
                                                        <th>Documento</th>
                                                        <th>Estado</th>
                                                        <th>Incluir <input class="scales" type="checkbox" id="Marcar_todos_lista_chequeo" style="margin-left: 10px;"></th>
                                                        <th class="posicion_foleo d-none">Posición</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="lista_documentos_check">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="card-body" style="text-align: right;">
                            <div class="col-12">
                                <div class="alerta_chequeo alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                            </div>
                            <div class="col-12" role="alert">
                                <div class="error_chequeo alert alert-danger mt-2 mr-auto d-none" role="alert"></div>
                            </div>                            
                            @if (!empty($validar_lista_chequeo[0]->Nombre_documento) && $validar_lista_chequeo[0]->Nombre_documento == 'Lista_chequeo_IdEvento_'.$validar_lista_chequeo[0]->ID_evento.'_IdServicio_'.$validar_lista_chequeo[0]->Id_servicio.'_IdAsignacion_'.$validar_lista_chequeo[0]->Id_Asignacion)
                                <button type="submit"  class="btn btn-info d-none actualizar_chequeo" disabled data-accion='Actualizar'>Actualizar lista de chequeo</button>                                
                            @else
                                <button type="submit"  class="btn btn-info guardar_chequeo" disabled data-accion='Guardar'>Generar lista de chequeo</button>                                
                            @endif
                        </div> 
                    </div>                                        
                </div>
                <div class="row d-none" id="row_cuadro_expediente">
                    <div class="col-12">
                        <div class="card-info">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Expediente</h5>
                            </div>
                            <br>
                            <div class="col-4">                                
                                <label for="Posicion_expediente">Última Posición</label>
                                <input class="form-control" type="text" name="Posicion_expediente" id="Posicion_expediente" value="{{$posicionExpediente}}" readonly>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">  
                                            <div class="col-12">
                                                <div class="alert mt-2 mr-auto d-none" id="resultado_inseractua_expediente" role="alert"></div>
                                            </div>                                      
                                            <div class="table-responsive">
                                                <table id="listado_lista_expedientes" class="table table-striped table-bordered" width="100%">
                                                    <thead>
                                                        <tr class="bg-info">
                                                            <th>Documento</th>
                                                            <th>Posición</th>
                                                            <th>Folear</th>
                                                            <th>Acción</th>
                                                            <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_fila_expediente"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($info_cuadros_expedientes as $cuadro_expediente)
                                                            <tr class="fila_expediente_{{$cuadro_expediente->Id_expedientes}}" id="datos_expedientes">
                                                                <td>{{$cuadro_expediente->Nombre_documento}}.pdf</td>
                                                                <td>
                                                                    <input type="text" class="form-control posicion-expediente_{{$cuadro_expediente->Id_Documento}}" id="posicion_expediente_{{$cuadro_expediente->Id_expedientes}}" data-iddocumento = "{{$cuadro_expediente->Id_Documento}}" value="{{$cuadro_expediente->Posicion}}">
                                                                </td>
                                                                <td>
                                                                    <select class="custom-select folear_expediente"  name="folear_expediente" id="folear_expediente_{{$cuadro_expediente->Id_expedientes}}">
                                                                        <option value="{{$cuadro_expediente->Folear}}">{{$cuadro_expediente->Folear}}</option>
                                                                        <option value="<?php if ($cuadro_expediente->Folear == 'Si') { echo 'No'; } else { echo 'Si';} ?>"><?php if ($cuadro_expediente->Folear == 'Si') { echo 'No'; } else { echo 'Si';} ?></option>
                                                                    </select>
                                                                </td>
                                                                <td>                                                                    
                                                                    <div style="display: flex; flex-direction: row; justify-content: space-around; align-items: center">
                                                                        <a href="javascript:void(0);" id="Descarga_listachequeo_{{$cuadro_expediente->Id_expedientes}}" data-id_doc_expediente_chequeo="{{$cuadro_expediente->Id_expedientes}}" data-nombre_documento_chequeo="{{$cuadro_expediente->Nombre_documento}}.pdf" data-id_evento = "{{$cuadro_expediente->ID_evento}}"><i style="cursor:pointer; display: flex; justify-content: center; align-items:center;" class="far fa-eye text-info"></i></a>
                                                                        <a href="javascript:void(0);"  class="editar_posicion_foleo_expediente_{{$cuadro_expediente->Id_expedientes}}" id="editar_posicion_foleo_expediente_{{$cuadro_expediente->Id_expedientes}}" data-id_expediente = "{{$cuadro_expediente->Id_expedientes}}"  style="display: flex; justify-content: center;"><i class="fa fa-sm fa-check text-success"></i></a>
                                                                    </div>
                                                                </td>   
                                                                <td>
                                                                    @if ($cuadro_expediente->Nombre_documento <> 'Lista_chequeo_IdEvento_'.$cuadro_expediente->ID_evento.'_IdServicio_'.$cuadro_expediente->Id_servicio.'_IdAsignacion_'.$cuadro_expediente->Id_Asignacion)
                                                                        <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_comunicado_expediente_{{$cuadro_expediente->Id_expedientes}}" data-id_fila_quitar="{{$cuadro_expediente->Id_expedientes}}" data-clase_fila="fila_expediente_{{$cuadro_expediente->Id_expedientes}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div><br>
                                            <div class="row">
                                                <div class="col-12"> 
                                                    <div class="row">
                                                        <div class="col-8 alert alert-warning mr-auto" role="alert" style="padding:7px; left:7px;">
                                                            <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Para visualizar los cambios dar click en el botón <strong>Actualizar Tabla Expediente</strong> y después de guardar un documento o editar una posición debe esperar a que realicé dicha acción para ejecutar otra.
                                                        </div>
                                                        <div class="col-3" style="text-align:right;">
                                                            <x-adminlte-button class="mr-auto" id="refrescar_expedientes" theme="info" label="Actualizar Tabla Expediente"/>
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div>
                                            @if (!empty($info_cuadros_expedientes) && count($info_cuadros_expedientes) > 1)
                                                @if (!empty($IdExpediente_estado))
                                                    <x-adminlte-button class="mr-auto" id="generar_datos_expediente" theme="info" label="Actualizar Zip Expediente"/>
                                                @else
                                                    <x-adminlte-button class="mr-auto" id="generar_datos_expediente" theme="info" label="Generar Zip Expediente"/>
                                                @endif
                                            @else
                                                <x-adminlte-button class="mr-auto" id="generar_datos_expediente" theme="info" label="Generar Zip Expediente" disabled/>
                                            @endif
                                            @if (!empty($info_cuadros_expedientes) && count($info_cuadros_expedientes) > 1)
                                                @if (!empty($IdExpediente_estado))
                                                    <x-adminlte-button class="mr-auto" id="generarNuevo_datos_expediente" theme="info" label="Generar Zip Nuevo Expediente"/>
                                                @else
                                                    <x-adminlte-button class="mr-auto" id="generarNuevo_datos_expediente" theme="info" label="Generar Zip Nuevo Expediente" disabled/>
                                                @endif
                                            @else
                                                <x-adminlte-button class="mr-auto" id="generarNuevo_datos_expediente" theme="info" label="Generar Zip Nuevo Expediente" disabled/>
                                            @endif
                                            <br>
                                            <br>
                                            <form action="/UnificarExpedientesPdfs" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="Evento_expediente" value="{{$array_datos_calificacionJuntas[0]->ID_evento}}">
                                                <input type="hidden" name="IdAsignacion_expediente" value="{{$array_datos_calificacionJuntas[0]->Id_Asignacion}}">
                                                <input type="hidden" name="IdProceso_expediente" value="{{$array_datos_calificacionJuntas[0]->Id_proceso}}">
                                                <input type="hidden" name="IdServicio_expediente" value="{{$array_datos_calificacionJuntas[0]->Id_Servicio}}">
                                                <input type="hidden" name="Nombre_afiliado_expediente" value="{{$array_datos_calificacionJuntas[0]->Nombre_afiliado}}">
                                                <input type="hidden" name="Nro_identificacion_expediente" value="{{$array_datos_calificacionJuntas[0]->Nro_identificacion}}">
                                                <input type="hidden" name="radicado2" id="radicado2" value="{{$consecutivo}}">
                                                <input type="hidden" name="IdExpediente_estado" id="IdExpediente_estado" value="{{$IdExpediente_estado}}">
                                                <label for="pdfs">Seleccionar PDFs:</label>
                                                <input type="file" class="form-control select-doc"  name="pdfs[]" id="pdfs" multiple accept=".pdf" required>
                                                <br>
                                                @if (!empty($info_cuadros_expedientes) && count($info_cuadros_expedientes) > 1)                                                   
                                                    <button type="submit" id="CrearActualizarExpediente" class="btn btn-info">Crear / Actualizar Expediente</button>                                                                                                            
                                                @else                                                    
                                                    <button type="submit" id="CrearActualizarExpediente" class="btn btn-info" disabled>Crear / Actualizar Expediente</button>
                                                @endif
                                            </form>
                                            @if ($errors->has('pdfs_error'))
                                                <span class="text-danger">{{ $errors->first('pdfs_error') }}</span>
                                            @endif
                                        </div>
                                        <x-adminlte-button class="mr-auto" id="refrescar_comunicados" theme="info" label="Actualizar Tabla Comunicados"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <x-slot name="footerSlot">
                    <x-adminlte-button theme="danger" id="refresca_expediente" label="Cerrar" data-dismiss="modal"/>
                </x-slot>
            </x-adminlte-modal>
            
        </div>
    </div>