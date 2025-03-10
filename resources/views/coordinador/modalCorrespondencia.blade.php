    {{-- Modal  Crear expediente --}}
    <div class="row">
        <div class="contenedor_sol_correspondencia" style="float: left;">
            <x-adminlte-modal id="modalCorrespondencia" title="Correspondencia" theme="info" icon="fas fa-plus mr-2"
                size='xl' disable-animations>
                <div class="row">
                    <div class="col-12">
                        <form id="form_correspondencia" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="nombre_afiliado">Nombre de afiliado</label>
                                            <input class="form-control" type="text" name="nombre_afiliado"
                                                id="nombre_afiliado" readonly>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="n_identificacion">N° de Identificacion</label>
                                            <input class="form-control" type="text" name="n_identificacion"
                                                id="n_identificacion"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="id_evento">ID evento</label>
                                            <br>
                                            <input hidden="hidden" type="text" class="form-control" name="id_evento"
                                                id="id_evento" disabled>
                                            {{-- DATOS PARA VER EDICIÓN DE EVENTO --}}
                                            <a onclick="document.getElementById('botonVerEdicionEvento').click();"
                                                id="enlace_ed_evento" style="cursor:pointer; font-weight: bold;"
                                                class="btn text-info" type="button"></a>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="id_destinatario">ID Destinatario</label>
                                            <input class="form-control" type="text" name="id_destinatario" id="id_destinatario" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-4">
                                        <div class="form-group d-none" id="ver_chequeo">
                                            <a for="lista_chequeo" style="cursor: pointer;">Visualizar lista de
                                                chequeo</a>
                                            <i class="far fa-eye text-info"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-info">
                                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                                <h5>Información de Correspondencia</h5>
                                            </div>

                                            <div class="card-body">
                                                <input class="form-control" type="hidden" name="tipo_correspondencia" id="tipo_correspondencia">
                                                <input class="form-control" type="hidden" name="id_asignacion" id="id_asignacion">
                                                <input class="form-control" type="hidden" name="id_proceso" id="id_proceso">
                                                <input class="form-control" type="hidden" name="id_comunicado" id="id_comunicado">
                                                <input class="form-control" type="hidden" name="id_correspondencia" id="id_correspondencia">
                                                <div class="row">
                                                    
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="radicado">N° Radicado</label>
                                                            <input class="form-control" type="text" name="radicado"
                                                                id="radicado" disabled>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="radicado">N° de orden</label>
                                                            <input class="form-control" type="text" name="n_orden"
                                                                id="n_orden" disabled>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-4">
                                                        <label for="t_destinatario">Tipo de Destinatario<span style="color:red;">(*)</span></label>
                                                        <div class="form-group d-flex">
                                                            <div class="col-6 custom-control custom-checkbox">
                                                                <input class="custom-control-input" type="checkbox" id="check_principal" name="check_principal" value="Principal" required>
                                                                <label for="check_principal" class="custom-control-label">Principal</label>                 
                                                            </div>
                                                            <div class="col-6 custom-control custom-checkbox">
                                                                <input class="custom-control-input" type="checkbox" id="check_copia" name="check_copia" value="Copia" required>
                                                                <label for="check_copia" class="custom-control-label">Copia</label>                 
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="nombre_destinatario">Nombre de Destinatario</label>
                                                            <input class="form-control" type="text" name="nombre_destinatario"
                                                                id="nombre_destinatario"disabled>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="direccion">Direccion</label>
                                                            <input class="form-control" type="text" name="direccion"
                                                                id="direccion" disabled>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="departamento">Departamento</label>
                                                            <input class="form-control" type="text" name="departamento"
                                                                id="departamento" disabled>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="ciudad">Ciudad</label>
                                                            <input class="form-control" type="text" name="ciudad"
                                                                id="ciudad" disabled>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="telefono">Telefono/Celular</label>
                                                            <input class="form-control" type="text" name="telefono"
                                                                id="telefono" disabled>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="email">E-mail</label>
                                                            <input class="form-control" type="email" name="email"
                                                                id="email" disabled>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="m_notificacion">Medio de Notificacion</label>
                                                            <input class="form-control" type="text" name="m_notificacion"
                                                                id="m_notificacion" disabled>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="n_guia">N° de guia</label>
                                                            <input class="form-control" type="text" name="n_guia"
                                                                id="n_guia">
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="folios">Folios</label>
                                                            <input class="form-control" type="number" name="folios"
                                                                id="folios">
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="f_envio">Fecha de envio</label>
                                                            <input class="form-control" type="date"  name="f_envio" id="f_envio" max="{{ date('Y-m-d') }}" min="1900-01-01">
                                                            <span class="d-none" id="alerta_fecha_envio" style="color: red; font-style: italic;"></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="f_notificacion">Fecha de notificacion</label>
                                                            <input class="form-control" type="date" name="f_notificacion" id="f_notificacion" max="{{ date('Y-m-d') }}" min="1900-01-01">
                                                            <span class="d-none" id="f_notificacion_alerta" style="color: red; font-style: italic;"></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="state_notificacion">Estado de notificacion<span style="color:red;">(*)</span></label>
                                                            <select class="forma_envio custom-select state_notificacion" name="state_notificacion" id="state_notificacion" style="width: 100%;" required>                                                    
                                                                <option value="">Seleccione una opción</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        {{-- Guías Ventana Correspondencia --}}
                                        <div class="accordion" id="accordionGuia">
                                            <a class="text-dark text-md" data-toggle="collapse" data-target="#collapseGuia" role="button" aria-expanded="false" aria-controls="collapseGuia"><i class="far fa-file text-info"></i> <b>Guía <span id="tipo_guia"></span></b></a>
                                            <br><br>
                                            <div id="collapseGuia" class="collapse" data-parent="#accordionGuia">
                                                <div class="row">
                                                    {{-- Documentos Complementarios --}}
                                                    <div class="col-12">
                                                        <div class="alert alert-warning" role="alert">
                                                            <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Para cargar un documento del mismo tipo debe usar este formulario.
                                                        </div>
                                                        {{-- <form id="familia_documentos" method="POST" enctype="multipart/form-data"> --}}
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <div class="form-group">
                                                                        <label for="listado_tipos_documentos_guias">Listado Documentos Complementarios <span style="color: red;">(*)</span></label><br>
                                                                        <select class="listado_tipos_documentos_guias custom-select" name="listado_tipos_documentos_guias" id="listado_tipos_documentos_guias" style="width: 100%;">
                                                                            <option value="">Seleccione una Opción</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <div class="form-group">
                                                                        <label for="" style="color: white;">label</label>
                                                                        <div class="d-none">
                                                                            <input type="text" name="id_doc_familia_guias" id="id_doc_familia_guias">
                                                                            <input type="text" name="nombre_doc_familia_guias" id="nombre_doc_familia_guias">
                                                                            <input type="text" name="id_evento_familia_guias" id="id_evento_familia_guias">
                                                                            <input type="text" name="id_servicio_familia_guias" id="id_servicio_familia_guias">
                                                                            <input type="text" name="id_asignacion_familia_guias" id="id_asignacion_familia_guias">
                                                                        </div>
                                                                        <div class="input-group">
                                                                            <input type="file" class="form-control select-doc" name="doc_subir_guias" id="doc_subir_guias" aria-describedby="Carguedocumentos" aria-label="Upload">&nbsp;
                                                                            <button class="btn btn-info button-doc-select" type="button" id="CargarDocumento_guias">Cargar</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        {{-- </form> --}}
                                                        <div class="mostrar_fallo_doc_familia alert alert-danger mt-2 mr-auto d-none" role="alert"></div>
                                                        <div class="mostrar_exito_doc_familia alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                                                    </div>
                                                    {{-- Listado de Documentos --}}
                                                    <div class="col-12">
                                                        <div class="table table-responsive">
                                                            <table id="listado_documentos_ed" class="table table-striped table-bordered" style="width:100%">
                                                                <thead>
                                                                    <tr class="bg-info">
                                                                        <th>N°</th>
                                                                        <th>Documento</th>
                                                                        <th>Estado</th>
                                                                        <th>Archivo</th>
                                                                        <th>Fecha Cargue</th>
                                                                        <th>Descarga</th>
                                                                        <th>Detalle</th>
                                                                        <th style="width: 1px !important;">Obligatorio</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>                                 
                                                                    @foreach ($arraylistado_documentos as $documento)
                                                                        <tr id="fila_doc_{{$documento->Nro_documento}}">
                                                                            {{-- Nro --}}
                                                                            <td>{{$documento->Nro_documento}}</td>
                                                                            {{-- Documento --}}
                                                                            <td style="width: 20% !important;">{{$documento->Nombre_documento}}</td>
                                                                            {{-- Estado --}}
                                                                            <td id="estadoDocumento_{{$documento->Id_Documento}}">
                                                                                <?php if($documento->estado_documento == "Cargado"):?>
                                                                                    <strong class="text-success">Cargado</strong>
                                                                                <?php else:?>
                                                                                    <strong class="text-danger">No Cargado</strong>
                                                                                <?php endif?>
                                                                            </td>
                                                                            {{-- Archivo --}}
                                                                            <td>
                                                                                <form id="formulario_documento_{{$documento->Id_Documento}}" data-id_reg_doc="{{$documento->id_Registro_Documento}}" data-id_doc="{{$documento->Id_Documento}}" data-tipo_documento="{{$documento->Tipo}}" method="POST" enctype="multipart/form-data">
                                                                                    @csrf
                                                                                    <div class="d-none">
                                                                                        <input type="text" name="Id_Documento" value="{{$documento->Id_Documento}}">
                                                                                        <?php if($documento->Tipo == "Complementario"):?>
                                                                                            <?php 
                                                                                                $patron = '/^(.*?)_IdEvento/';
                                                                                                preg_match($patron, $documento->nombre_Documento, $matches);
                                                                                                $name = $matches[1];
                                                                                            ?>
                                                                                            <input type="text" name="Nombre_documento" id="Nombre_documento_{{$documento->Id_Documento}}" value="{{$name}}">  
                                                                                        <?php else:?>
                                                                                            <input type="text" name="Nombre_documento" id="Nombre_documento_{{$documento->Id_Documento}}" value="{{$documento->Nombre_documento}}">  
                                                                                        <?php endif?>
                                                                                        <input  type="text" name="EventoID" id="EventoID_{{$documento->Id_Documento}}">
                                                                                        <input type="text" name="Id_servicio" id="Id_servicio_{{$documento->Id_Documento}}" value="{{$Id_servicio}}">
                                                                                        <input type="text" name="string_nombre_doc" id="string_nombre_doc_{{$documento->Id_Documento}}" value="<?php if($documento->nombre_Documento <> ""){echo "{$documento->nombre_Documento}";}?>">
                                                                                        @if (isset($Id_Asignacion))
                                                                                            <input  type="text" name="Id_asignacion" id="Id_asignacion_{{$documento->Id_Documento}}" value="{{$Id_Asignacion}}">                                                                                                    
                                                                                        @endif
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <p><?php if($documento->nombre_Documento <> ""){echo "{$documento->nombre_Documento}.{$documento->formato_documento}";}?></p>
                                                                                        <div class="input-group" style="justify-content: space-between;">
                                                                                            @if($documento->Id_Documento === 4 && $documento->Tipo === null)
                                                                                                <input type="file" class="form-control select-doc" style="display: none;" data-id_reg_doc="{{$documento->id_Registro_Documento}}" data-tipo_documento="{{$documento->Tipo}}" id="listadodocumento_{{$documento->Id_Documento}}" data-id_doc="{{$documento->Id_Documento}}" aria-describedby="Carguedocumentos" aria-label="Upload" <?php if($documento->Requerido === "Si"):?>
                                                                                                required
                                                                                                <?php endif?>>
                                                                                                <div style="display: flex; flex-direction: row; justify-content: space-between; width: 100%;">
                                                                                                    <div style="display: flex;flex-direction: row;align-items: center;width: 82%; max-width:520px; border: 1px solid black; border-radius: 4px;">
                                                                                                        <label for="listadodocumento_{{$documento->Id_Documento}}" id="thebutton_{{$documento->Id_Documento}}" class="btn btn-info button-doc-select" style="margin:0;font-weight: 400; border-radius: 1px 0px 0px 1px;">Seleccionar archivo</label>
                                                                                                        <label for="listadodocumento_{{$documento->Id_Documento}}" id="fileName_{{$documento->Id_Documento}}" style="width: 60%; margin:0;padding-left: 5px;font-weight: 400; overflow: hidden;text-wrap: nowrap;text-overflow: ellipsis;">Sin archivos seleccionados</label>
                                                                                                    </div>
                                                                                                    <button class="btn btn-info button-doc-select" type="submit" id="CargarDocumento_{{$documento->Id_Documento}}">
                                                                                                        <?php if($documento->estado_documento == "Cargado"):?>
                                                                                                            Actualizar
                                                                                                        <?php else:?>
                                                                                                            Cargar
                                                                                                        <?php endif?>
                                                                                                    </button>
                                                                                                </div>
                                                                                            @else
                                                                                                <input type="file" class="form-control select-doc" name="listadodocumento" 
                                                                                                id="listadodocumento_{{$documento->Id_Documento}}" data-id_doc="{{$documento->Id_Documento}}" data-tipo_documento="{{$documento->Tipo}}" aria-describedby="Carguedocumentos" aria-label="Upload"
                                                                                                <?php if($documento->Requerido === "Si"):?>
                                                                                                    required
                                                                                                <?php endif?>
                                                                                                >
                                                                                                &nbsp;
                                                                                                <button class="btn btn-info button-doc-select" type="submit" id="CargarDocumento_{{$documento->Id_Documento}}">
                                                                                                    <?php if($documento->estado_documento == "Cargado"):?>
                                                                                                        Actualizar
                                                                                                    <?php else:?>
                                                                                                        Cargar
                                                                                                    <?php endif?>
                                                                                                </button>
                                                                                                
                                                                                            @endif
                                                                                        </div>                                                                                                                                                              
                                                                                    </div>
                                                                                </form>
                                                                            </td>
                                                                            {{-- Fecha Cargue --}}
                                                                            <td>
                                                                                <?php if(!empty($documento->fecha_cargue_documento)):?>
                                                                                    <input type="date" class="form-control" id="fecha_cargue_documento_{{$documento->id_Registro_Documento}}_{{$documento->Id_Documento}}" value="{{$documento->fecha_cargue_documento}}" readonly>
                                                                                <?php else: ?>
                                                                                    <input type="date" class="form-control" id="fecha_cargue_documento_{{$documento->id_Registro_Documento}}_{{$documento->Id_Documento}}" readonly>
                                                                                <?php endif?>
                                                                            </td>
                                                                            {{-- Descarga --}}
                                                                            <td>
                                                                                <?php if($documento->estado_documento == "Cargado"):?>
                                                                                    <div class="d-none">
                                                                                        <input type="text" id="nombre_documento_descarga_{{$documento->id_Registro_Documento}}_{{$documento->Id_Documento}}" value="{{$documento->nombre_Documento}}">                                                
                                                                                        <input type="text" id="extension_documento_descarga_{{$documento->id_Registro_Documento}}_{{$documento->Id_Documento}}" value="{{$documento->formato_documento}}">                                                
                                                                                    </div>
                                                                                    <div class="text-center">
                                                                                        <a href="javascript:void(0);" id="btn_generar_descarga_{{$documento->Id_Documento}}" data-id_doc_reg_descargar="{{$documento->id_Registro_Documento}}" data-id_documento_descargar="{{$documento->Id_Documento}}"><i class="fas fa-download text-info"></i></a>
                                                                                    </div>
                                                                                <?php endif?>
                                                                            </td>
                                                                            {{-- Detalle --}}
                                                                            <td>
                                                                                <?php if($documento->Tipo == "Complementario"): ?>
                                                                                    <form id="form_eliminar_doc_complementario_{{$documento->Id_Documento}}" method="POST">
                                                                                        @csrf
                                                                                        <div class="d-none">
                                                                                            <input type="text" name="tupla_doc_complementario" id="tupla_doc_complementario" value="{{$documento->id_Registro_Documento}}">
                                                                                            <input type="text" name="nombre_doc_complementario" value="<?php echo "{$documento->nombre_Documento}.{$documento->formato_documento}";?>"> 
                                                                                        </div>
                                                                                        <div class="form-group text-center">
                                                                                            <button class="btn" type="submit" id="EliminarDocComplementario">
                                                                                                <i class="fas fa-minus-circle text-info" style="font-size:20px;"></i>
                                                                                            </button>
                                                                                        </div>
                                                                                    </form>
                                                                                <?php endif ?>
                                                                            </td>
                                                                            {{-- Obligatorio --}}
                                                                            <td class="text-center" style="width: 10% !important;">
                                                                                <input type="checkbox" class="scales" name="checkdocumentos" id="check_documento_{{$documento->Id_Documento}}" 
                                                                                    <?php if($documento->Requerido === "Si"): ?>
                                                                                        checked
                                                                                        disabled
                                                                                    <?php else:?>
                                                                                        disabled
                                                                                    <?php endif ?>
                                                                                >
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach                                                                                              
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="mostrar_fallo alert alert-danger mt-2 mr-auto d-none" role="alert"></div>
                                                        <div class="mostrar_exito alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="alerta_correspondencia alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                            <div class="alerta_error alert alert-danger mt-2 mr-auto d-none" role="alert"></div>
                            <div class="alerta_advertencia alert alert-danger mt-2 mr-auto d-none" role="alert"></div>
                            <div style="display: flex; justify-content: flex-end; gap:10px;">                                
                                {{-- <button type="submit" class="btn d-none btn-info actualizar_correspondencia" id="btn_actualizar_correspondencia" data-accion='Actualizar'>Actualizar</button>
                                <input type="submit" id="ActualizarPronuncia" name="ActualizarPronuncia" class="btn btn-info" value="Actualizar">
                                <button type="submit" class="btn btn-info guardar_correspondencia" id="btn-guardar-correspondencia" data-accion='Guardar'>Guardar</button> --}}
                                <input type="submit" id="btn_guardar_actualizar_correspondencia" name="btn_guardar_actualizar_correspondencia" class="btn btn-info" value="Guardar">
                                <x-adminlte-button theme="danger" label="Cerrar" id="cerar_modalCorrespondencia" data-dismiss="modal" />
                            </div>
                        </form>

                    </div>
                </div>

                <x-slot name="footerSlot">
                </x-slot>
            </x-adminlte-modal>

        </div>
    </div>
    <section id="loading">
        <div id="loading-content"></div>
    </section>

    {{-- Validación en los campos de fecha, en el cual la fecha de envio no debe ser mayor a la fecha de notificación, y ninguna de esas dos fechas pueden ser mayores a la fecha actual --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let today = new Date().toISOString().split("T")[0];
            // Obtener referencias a los campos de fecha
            const fechaEnvio = document.getElementById('f_envio');
            const fechaNotificacion = document.getElementById('f_notificacion');
            const alerta_fecha_envio = document.getElementById('alerta_fecha_envio');
    
            // Evento para cuando se cambie la fecha de envío
            fechaEnvio.addEventListener('change', function () {
                // Obtener los valores de las fechas
                const envioValue = fechaEnvio.value;
                const notificacionValue = fechaNotificacion.value ? fechaNotificacion.value : null;
                // Validar que la fecha de envío no sea mayor a la fecha de notificación
                if (notificacionValue && envioValue > notificacionValue) {
                    $("#alerta_fecha_envio").text('La fecha ingresada no debe ser superior a la fecha de Notificación').removeClass('d-none')
                    fechaEnvio.value = ''; // Limpiar el campo
                }
                else if(envioValue < '1900-01-01'){
                    $(`#alerta_fecha_envio`).text("La fecha ingresada no es válida. Por favor valide la fecha ingresada").removeClass("d-none");
                    $('#btn_guardar_actualizar_correspondencia').addClass('d-none');
                    return;
                }
                //Validamos que la fecha no sea mayor a la fecha actual
                else if(envioValue > today){
                    $(`#alerta_fecha_envio`).text("La fecha ingresada no puede ser mayor a la actual").removeClass("d-none");
                    $('#btn_guardar_actualizar_correspondencia').addClass('d-none');
                    return;
                }
                else{
                    $("#alerta_fecha_envio").text('').addClass('d-none')
                    $('#btn_guardar_actualizar_correspondencia').removeClass('d-none');
                }
            });
            //Fecha de notificación
            fechaNotificacion.addEventListener('change', function () {
                //Notificación de las fechas
                const notificacionValue = fechaNotificacion.value ? fechaNotificacion.value : null;
                
                if(fechaEnvio.value && fechaNotificacion.value && fechaEnvio.value > fechaNotificacion.value){
                        $("#alerta_fecha_envio").text('La fecha ingresada no debe ser superior a la fecha de Notificación').removeClass('d-none');
                        fechaEnvio.value = ''; // Limpiar el campo
                }else{
                    $("#alerta_fecha_envio").addClass('d-none')
                }
                //Validaciones generales para el input de fecha de notificación
                //Validamos que la fecha no sea menor a 1900-01-01
                if(notificacionValue < '1900-01-01'){
                    $(`#${this.id}_alerta`).text("La fecha ingresada no es válida. Por favor valide la fecha ingresada").removeClass("d-none");
                    $('#btn_guardar_actualizar_correspondencia').addClass('d-none');
                    return;
                }
                //Validamos que la fecha no sea mayor a la fecha actual
                if(notificacionValue > today){
                    $(`#${this.id}_alerta`).text("La fecha ingresada no puede ser mayor a la actual").removeClass("d-none");
                    $('#btn_guardar_actualizar_correspondencia').addClass('d-none');
                    return;
                }
                //Validamos que la fecha no sea menor a 1900-01-01
                if(fechaEnvio.value && fechaEnvio.value < '1900-01-01'){
                    $(`#alerta_fecha_envio`).text("La fecha ingresada no es válida. Por favor valide la fecha ingresada").removeClass("d-none");
                    $('#btn_guardar_actualizar_correspondencia').addClass('d-none');
                    return;
                }
                //Validamos que la fecha no sea mayor a la fecha actual
                if(fechaEnvio.value && fechaEnvio.value > today){
                    $(`#alerta_fecha_envio`).text("La fecha ingresada no puede ser mayor a la actual").removeClass("d-none");
                    $('#btn_guardar_actualizar_correspondencia').addClass('d-none');
                    return;
                }
                $("#alerta_fecha_envio").text('').addClass('d-none')
                $(`#${this.id}_alerta`).text('').addClass("d-none");
                return $('#btn_guardar_actualizar_correspondencia').removeClass('d-none');
            });
        });
    </script>
