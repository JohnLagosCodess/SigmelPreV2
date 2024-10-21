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
                            <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Para poder generar la lista de chequeo debe cumplir las siguientes condiciones; <br><strong>1:</strong> Selecionar minimo un documento de la tabla de chequo
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
                            @if (!empty($validar_lista_chequeo[0]->Nombre_documento) && $validar_lista_chequeo[0]->Nombre_documento == 'Lista_chequeo')
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
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">                                        
                                            <div class="alert d-none" id="resultado_insercion_cie10" role="alert"></div>
                                            <div class="table-responsive">
                                                <table id="listado_lista_chequeos" class="table table-striped table-bordered" width="100%">
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
                                                        @foreach ($info_cuadro_expedientes as $cuadro_expediente)
                                                            <tr class="fila_expediente_{{$cuadro_expediente->Id_expedientes}}" id="datos_expedientes">
                                                                <td>{{$cuadro_expediente->Nombre_documento}}</td>
                                                                <td>
                                                                    <input type="number" class="form-control" id="posicion_expediente_{{$cuadro_expediente->Id_expedientes}}" value="{{$cuadro_expediente->Posicion}}">
                                                                </td>
                                                                <td>
                                                                    <select class="custom-select folear_expediente"  name="folear_expediente" id="folear_expediente_{{$cuadro_expediente->Id_expedientes}}">
                                                                        <option value="{{$cuadro_expediente->Folear}}">{{$cuadro_expediente->Folear}}</option>
                                                                        <option value="<?php if ($cuadro_expediente->Folear == 'Si') { echo 'No'; } else { echo 'Si';} ?>"><?php if ($cuadro_expediente->Folear == 'Si') { echo 'No'; } else { echo 'Si';} ?></option>
                                                                    </select>
                                                                </td>
                                                                <td>Acciones</td>
                                                                <td>
                                                                    @if ($cuadro_expediente->Nombre_documento <> 'Lista_chequeo')                                                                        
                                                                        <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_diagnosticos_moticalifi{{$cuadro_expediente->Id_expedientes}}" data-id_fila_quitar="{{$cuadro_expediente->Id_expedientes}}" data-clase_fila="fila_diagnosticos_{{$cuadro_expediente->Id_expedientes}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>                                                                                                                                            
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div><br>
                                            <x-adminlte-button class="mr-auto" id="guardar_datos_expediente" theme="info" label="Actualizar Expediente"/>
                                            <x-adminlte-button class="mr-auto" id="guardar_datos_expediente" theme="info" label="Generar Nuevo Expediente"/>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <x-slot name="footerSlot">
                    <x-adminlte-button theme="danger" label="Cerrar" data-dismiss="modal"/>
                </x-slot>
            </x-adminlte-modal>
            
        </div>
    </div>