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
                                    <div class="row mt-1">
                                        <div class="col-4">
                                            <div class="form-group d-none" id="ver_chequeo">
                                                <a for="lista_chequeo" style="cursor: pointer;" >Visualizar lista de chequeo</a>
                                                <i class="far fa-eye text-info"></i>
                                            </div> 
                                        </div>  
                                    </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table table-responsive" style="overflow-y:scroll; height:400px; position:relative">
                                                    <table class="table table-striped table-bordered table-hover" width="100%">
                                                        <thead class="bg-info">
                                                            <tr><th class="bg-info text-center" colspan="4">Lista de chequeo</th></tr>
                                                            <tr>
                                                                <th>N°</th>
                                                                <th>Documento</th>
                                                                <th>Estado</th>
                                                                <th>Incluir</th>
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
                        
                    </div>
                </div>

                <x-slot name="footerSlot">
                        <div class="col-12">
                            <div class="alerta_chequeo alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                        </div>
                        <div class="col-12" role="alert">
                            <div class="error_chequeo alert alert-danger mt-2 mr-auto d-none" role="alert"></div>
                        </div>
                        <button type="submit"  class="btn btn-info d-none actualizar_chequeo" disabled data-accion='Actualizar'>Actualizar lista de chequeo</button>
                        <button type="submit"  class="btn btn-info guardar_chequeo" disabled data-accion='Guardar'>Generar lista de chequeo</button>
                    <x-adminlte-button theme="danger" label="Cerrar" data-dismiss="modal"/>
                </x-slot>
            </x-adminlte-modal>
            
        </div>
    </div>