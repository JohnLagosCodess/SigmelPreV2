
<?php if($aperturaModal == 'Edicion'): ?>
    <div class="row">
        <x-adminlte-modal id="modalListaDocumentos" title="Listado de Documentos" theme="info" icon="fas fa-plus" size='xl' scrollable="yes" disable-animations>
            <div class="col-12">
                <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Para cargar un documento del mismo tipo
                    debe usar este formulario.
                </div>
                <form id="familia_documentos" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="listado_tipos_documentos">Listado Documentos Complementarios <span style="color: red;">(*)</span></label><br>
                                <select class="listado_tipos_documentos custom-select" name="listado_tipos_documentos" id="listado_tipos_documentos" style="width: 100%;" required>
                                    <option value="">Seleccione una Opción</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="" style="color: white;">label</label>
                                <div class="d-none">
                                    <input type="text" name="id_doc_familia" id="id_doc_familia">
                                    <input type="text" name="nombre_doc_familia" id="nombre_doc_familia">
                                    <input type="text" name="id_evento_familia" id="id_evento_familia">
                                    <input type="text" name="id_servicio_familia" id="id_servicio_familia">
                                    <input type="text" name="id_asignacion_familia" id="id_asignacion_familia">
                                </div>
                                <div class="input-group">
                                    <input type="file" class="form-control select-doc" name="doc_subir" id="doc_subir" aria-describedby="Carguedocumentos" aria-label="Upload" required>&nbsp;
                                    <button class="btn btn-info button-doc-select" type="submit" id="CargarDocumento">Cargar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="mostrar_fallo_doc_familia alert alert-danger mt-2 mr-auto d-none"role="alert"></div>
                <div class="mostrar_exito_doc_familia alert alert-success mt-2 mr-auto d-none" role="alert"></div>
            </div>
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
                            <tr>
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
                                    <?php if($documento->Nombre_documento === "Otros documentos"):?>
                                        <form id="formulario_documento_{{$documento->Id_Documento}}" class="form-inline align-items-center" data-id_reg_doc="{{$documento->id_Registro_Documento}}" data-id_doc="{{$documento->Id_Documento}}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="col-12">
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

                                                    <input  type="text" class="EventoID" name="EventoID" id="EventoID_{{$documento->Id_Documento}}">
                                                    <input type="text" class="Id_servicio" name="Id_servicio" id="Id_servicio_{{$documento->Id_Documento}}" value="{{$Id_servicio}}">
                                                    <input type="text" name="bandera_nombre_otro_doc" value="{{$documento->nombre_Documento}}">
                                                </div>
                                                <div class="row">
                                                    <p><?php if($documento->nombre_Documento <> ""){echo "{$documento->nombre_Documento}.{$documento->formato_documento}";}?></p>
                                                    <div class="input-group">
                                                        <input type="file" class="form-control select-doc" name="listadodocumento" 
                                                        id="listadodocumento_{{$documento->Id_Documento}}" aria-describedby="Carguedocumentos" aria-label="Upload"
                                                        <?php if($documento->Requerido === "Si"):?>
                                                            required
                                                        <?php endif?>
                                                        >&nbsp;
                                                        <button class="btn btn-info button-doc-select" type="submit" id="CargarDocumento_{{$documento->Id_Documento}}">
                                                            <?php if($documento->estado_documento == "Cargado"):?>
                                                                Actualizar
                                                            <?php else:?>
                                                                Cargar
                                                            <?php endif?>
                                                        </button>
                                                    </div>                                                                                                                                                              
                                                </div>
                                            </div>
                                        </form>
                                    <?php else:?>
                                        {{-- action="{{route('cargaDocumento')}}" --}}
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
                                                @if (isset($newIdAsignacion))
                                                    <input  type="text" name="Id_asignacion" id="Id_asignacion_{{$documento->Id_Documento}}" value="{{$newIdAsignacion}}">                                                                                                    
                                                @endif
                                                {{-- <input  type="text" name="Id_asignacion" id="Id_asignacion_{{$documento->Id_Documento}}" value="{{$Id_asignacion_juntas}}"> --}}
                                                <input type="text" name="string_nombre_doc" id="string_nombre_doc_{{$documento->Id_Documento}}" value="<?php if($documento->nombre_Documento <> ""){echo "{$documento->nombre_Documento}";}?>">
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
                                    <?php endif?>
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
            </div>
            <div class="col-12">
                <span>Mostrando {{count($cantidad_documentos_cargados)}} documentos cargados de un total de {{count($arraylistado_documentos)}} documentos.</span>
            </div>
            <x-slot name="footerSlot">
                <div class="col-12 d-flex">
                    <div class="col-4">
                        <button class="btn btn-info" id="descargar_documentos" onclick="descargarDocumentos()">Descargar todo</button>
                    </div>
                    <div class="col-4">
                        <div class="mostrar_fallo alert alert-danger mt-2 mr-auto d-none" role="alert"></div>
                        <div class="mostrar_exito alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                        <div class="bg-info form-control d-none" id="status_spinner" type="button" disabled>
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Comprimiendo documentos, por favor espere...
                        </div>
                    </div>
                    <div class="col-4 d-flex justify-content-end">
                        <button class="btn btn-info mr-2" id="recargar_ventana">Actualizar</button>
                        <x-adminlte-button theme="danger" label="Cerrar" data-dismiss="modal"/>
                    </div>
                </div>
            </x-slot>
        </x-adminlte-modal>
        {{-- MODAL OTRO DOCUMENTO --}}
        <x-adminlte-modal id="modalOtroDocumento" title="Cargar Otro Documento" theme="info" icon="fas fa-plus" size='xl' v-centered="yes" disable-animations>
            <form id="formulario_documento_{{$documento->Id_Documento}}" data-id_reg_doc="{{$documento->id_Registro_Documento}}" data-id_doc="{{$documento->Id_Documento}}" class="form-inline align-items-center" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-12">
                    <div class="d-none">
                        <input type="text" name="Id_Documento" value="{{$documento->Id_Documento}}">
                        <input type="text" name="Nombre_documento" id="Nombre_documento_{{$documento->Id_Documento}}" value="{{$documento->Nombre_documento}}">                                                
                        <input  type="text" name="EventoID" id="EventoID_{{$documento->Id_Documento}}">    
                        <input type="text" name="bandera_otro_documento" id="bandera_otro_documento" value="{{$documento->Id_Documento}}">                                            
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label">Nombre Documento</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nombre_otro_documento" id="nombre_otro_documento" style="width: 100% !important;">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label">Cargar Documento</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control mr-sm-2 select-doc" name="listadodocumento" 
                                id="listadodocumento_{{$documento->Id_Documento}}" aria-describedby="Carguedocumentos" aria-label="Upload"
                                <?php if($documento->Requerido === "Si"):?>
                                    required
                                <?php endif?>
                                style="width: 100% !important;"
                            >
                        </div>
                    </div>
                    <button class="btn btn-info" type="submit" id="CargarDocumento_{{$documento->Id_Documento}}">Cargar</button>
                </div>
            </form>

            <x-slot name="footerSlot">
                <x-adminlte-button theme="danger" label="Cerrar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </div>   
<?php endif ?>   

<?php if($aperturaModal == 'Nuevo'): ?>
    <div class="row">
        @php
            $modalTitle = 'Cargue del listado de documentos para el evento: ';
            if (session('id_evento_registrado')) {
                $modalTitle .= session('id_evento_registrado');
            }
        @endphp
        <x-adminlte-modal id="modalListaDocumentos" title="{{ $modalTitle }}" theme="info" icon="fas fa-plus" size='xl' scrollable="yes" disable-animations>
            <div class="col-12">
                <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Para que el documento se guarde debe haber escrito un número de ID evento y haber seleccionado un Servicio.
                </div>
            </div>
            <div class="col-12">
                <p class="h5">Los documentos marcados con &nbsp; &nbsp;<input type="checkbox" class="scales" disabled checked>&nbsp; &nbsp; son de cargue obligatorio para poder crear el evento.</p>
                <div class="table table-responsive">
                    <table id="listado_documentos" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr class="bg-info">
                                <th>N°</th>
                                <th>Documento</th>
                                <th>Estado</th>
                                <th>Archivo</th>
                                <th style="width: 1px !important;">Obligatorio</th>
                            </tr>
                        </thead>
                        <tbody>                                 
                            @foreach ($listado_documentos as $documento)
                                <tr>
                                    <td>{{$documento->Nro_documento}}</td>
                                    <td style="width: 34% !important;">{{$documento->Nombre_documento}}</td>
                                    <?php if($documento->Nombre_documento === "Otros documentos"):?>
                                        <td id="estadoDocumentoOtro_{{$documento->Id_Documento}}"><strong class="text-danger">No Cargado</strong></td>
                                        <td><x-adminlte-button label="Cargar Otro Documento" id="habilitar_modal_otro_doc" data-toggle="modal" data-target="#modalOtroDocumento" class="bg-info"/></td>
                                    <?php else:?>
                                        <td id="estadoDocumento_{{$documento->Id_Documento}}"><strong class="text-danger">No Cargado</strong></td>
                                        <td>
                                            {{-- action="{{route('cargaDocumento')}}" --}}
                                            <form id="formulario_documento_{{$documento->Id_Documento}}" data-id_reg_doc="{{$documento->id_Registro_Documento}}" data-id_doc="{{$documento->Id_Documento}}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="d-none">
                                                    <input type="text" name="Id_Documento" value="{{$documento->Id_Documento}}">
                                                    <input type="text" name="Nombre_documento" id="Nombre_documento_{{$documento->Id_Documento}}" value="{{$documento->Nombre_documento}}">                                                
                                                    <input  type="text" name="EventoID" id="EventoID_{{$documento->Id_Documento}}">
                                                    <input  type="text" name="Id_servicio" id="Id_servicio_{{$documento->Id_Documento}}">
                                                    <input  type="text" name="Id_asignacion" id="Id_asignacion_{{$documento->Id_Documento}}">
                                                </div>
                                                <div class="row">
                                                    <div class="input-group" style="justify-content: space-between;">
                                                        @if($documento->Id_Documento !== 4)
                                                            <input type="file" class="form-control select-doc" name="listadodocumento" 
                                                            id="listadodocumento_{{$documento->Id_Documento}}" data-id_doc="{{$documento->Id_Documento}}" aria-describedby="Carguedocumentos" aria-label="Upload"
                                                            <?php if($documento->Requerido === "Si"):?>
                                                                required
                                                            <?php endif?>
                                                            >
                                                        @else
                                                            <input type="file" class="form-control select-doc" style="display: none;" id="listadodocumento_{{$documento->Id_Documento}}" data-id_doc="{{$documento->Id_Documento}}" <?php if($documento->Requerido === "Si"):?>
                                                                required
                                                                <?php endif?>
                                                            >
                                                            <div style="display: flex;flex-direction: row;align-items: center;width: 82%; max-width:520px; border: 1px solid black; border-radius: 4px;">
                                                                <label for="listadodocumento_{{$documento->Id_Documento}}" id="thebutton_{{$documento->Id_Documento}}" class="btn btn-info button-doc-select" style="margin:0;font-weight: 400; border-radius: 1px 0px 0px 1px;">Seleccionar archivo</label>
                                                                <label for="listadodocumento_{{$documento->Id_Documento}}" id="fileName_{{$documento->Id_Documento}}" style="width: 55%; margin:0;padding-left: 5px;font-weight: 400; overflow: hidden;text-wrap: nowrap;text-overflow: ellipsis;">Sin archivos seleccionados</label>
                                                            </div>
                                                        @endif
                                                        <button class="btn btn-info button-doc-select" type="submit" id="CargarDocumento_{{$documento->Id_Documento}}">Cargar</button>
                                                    </div>                                                                                                                                                              
                                                </div>
                                            </form>
                                        </td>
                                    <?php endif?>
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
            </div>
            <x-slot name="footerSlot">
                <div class="mostrar_fallo alert alert-danger mt-2 mr-auto d-none"role="alert"></div>
                <div class="mostrar_exito alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                <x-adminlte-button theme="danger" label="Cerrar" id="cerrar_modal_docs_nuevo" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>

        {{-- MODAL OTRO DOCUMENTO --}}
        <x-adminlte-modal id="modalOtroDocumento" title="Cargar Otro Documento" theme="info" icon="fas fa-plus" size='xl' v-centered="yes" disable-animations>
            <form id="formulario_documento_{{$documento->Id_Documento}}" data-id_reg_doc="{{$documento->id_Registro_Documento}}" data-id_doc="{{$documento->Id_Documento}}" class="form-inline align-items-center" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-12">
                    <h5>Los campos marcados con <span style="color:red;">(*)</span> son obligatorios.</h5>
                    <div class="d-none">
                        <input type="text" name="Id_Documento" value="{{$documento->Id_Documento}}">
                        <input type="text" name="Nombre_documento" id="Nombre_documento_{{$documento->Id_Documento}}" value="{{$documento->Nombre_documento}}">                                                
                        <input  type="text" name="EventoID" id="EventoID_{{$documento->Id_Documento}}">    
                        <input type="text" name="bandera_otro_documento" id="bandera_otro_documento" value="{{$documento->Id_Documento}}">                                            
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label">Nombre Documento <span style="color:red;">(*)</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nombre_otro_documento" id="nombre_otro_documento" style="width: 100% !important;" required>
                        </div>
                    </div>
                    <div class="form-group row mt-1">
                        <label for="" class="col-sm-2 col-form-label">Descripción</label>
                        <div class="col-sm-10">
                            <textarea class="descripcion_documento form-control" name="descripcion_documento" id="descripcion_documento" rows="2" style="width: 100% !important;"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label">Cargar Documento</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control mr-sm-2 select-doc" name="listadodocumento" 
                                id="listadodocumento_{{$documento->Id_Documento}}" aria-describedby="Carguedocumentos" aria-label="Upload"
                                <?php if($documento->Requerido === "Si"):?>
                                    required
                                <?php endif?>
                                style="width: 100% !important;"
                            >
                        </div>
                    </div>
                    <button class="btn btn-info" type="submit" id="CargarDocumento_{{$documento->Id_Documento}}">Cargar</button>
                </div>
            </form>

            <x-slot name="footerSlot">
                <x-adminlte-button theme="danger" label="Cerrar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </div>
<?php endif ?>

<?php if($aperturaModal == 'Edicion_Busqueda'):?>
    <div class="row d-none" id="habilitar">
        <x-adminlte-modal id="modalListaDocumentos" title="Listado de Documentos" theme="info" icon="fas fa-plus" size='xl' scrollable="yes" disable-animations>
            <div class="col-12">
                <p class="h5">Los documentos marcados con &nbsp; &nbsp;<input type="checkbox" class="scales" disabled checked>&nbsp; &nbsp; son de cargue obligatorio para poder crear el evento.</p>
                <div class="table table-responsive">
                    <table id="listado_documentos" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr class="bg-info">
                                <th>N°</th>
                                <th>Documento</th>
                                <th>Estado</th>
                                <th>Archivo</th>
                                {{-- <th>Descarga</th> --}}
                                <th style="width: 1px !important;">Obligatorio</th>
                            </tr>
                        </thead>
                        <tbody id="agregar_documentos"></tbody>
                    </table>
                </div>
            </div>
            <x-slot name="footerSlot">
                <div class="mostrar_fallo alert alert-danger mt-2 mr-auto d-none"role="alert"></div>
                <div class="mostrar_exito alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                <x-adminlte-button theme="danger" label="Cerrar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </div>
<?php endif ?> 


