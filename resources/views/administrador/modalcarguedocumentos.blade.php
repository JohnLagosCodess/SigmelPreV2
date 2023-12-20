
<?php if($aperturaModal == 'Edicion'): ?>
    <div class="row">
        <x-adminlte-modal id="modalListaDocumentos" title="Listado de Documentos" theme="info" icon="fas fa-plus" size='xl' scrollable="yes" disable-animations>
            <div class="col-12">
                <p class="h5">Los documentos marcados con &nbsp; &nbsp;<input type="checkbox" class="scales" disabled checked>&nbsp; &nbsp; son de cargue obligatorio para poder crear el evento.</p>
                <div class="table table-responsive">
                    <table id="listado_documentos" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Documento</th>
                                <th>Estado</th>
                                <th>Archivo</th>
                                <th>Descarga</th>
                                <th style="width: 1px !important;">Obligatorio</th>
                            </tr>
                        </thead>
                        <tbody>                                 
                            @foreach ($arraylistado_documentos as $documento)
                                <tr>
                                    <td>{{$documento->Nro_documento}}</td>
                                    <td style="width: 34% !important;">{{$documento->Nombre_documento}}</td>
                                    <td id="estadoDocumento_{{$documento->Id_Documento}}">
                                        <?php if($documento->estado_documento == "Cargado"):?>
                                            <strong class="text-success">Cargado</strong>
                                        <?php else:?>
                                            <strong class="text-danger">No Cargado</strong>
                                        <?php endif?>
                                    </td>
                                    <td>
                                        <?php if($documento->Nombre_documento === "Otros documentos"):?>
                                            <form id="formulario_documento_{{$documento->Id_Documento}}" class="form-inline align-items-center" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="col-12">
                                                    <div class="d-none">
                                                        <input type="text" name="Id_Documento" value="{{$documento->Id_Documento}}">
                                                        <input type="text" name="Nombre_documento" value="{{$documento->Nombre_documento}}">                                                
                                                        <input  type="text" name="EventoID" id="EventoID_{{$documento->Id_Documento}}">
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
                                            <form id="formulario_documento_{{$documento->Id_Documento}}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="d-none">
                                                    <input type="text" name="Id_Documento" value="{{$documento->Id_Documento}}">
                                                    <input type="text" name="Nombre_documento" value="{{$documento->Nombre_documento}}">                                                
                                                    <input  type="text" name="EventoID" id="EventoID_{{$documento->Id_Documento}}">                                                
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
                                            </form>
                                        <?php endif?>
                                    </td>
                                    <td>
                                        <?php if($documento->estado_documento == "Cargado"):?>
                                            <div class="d-none">
                                                <input type="text" id="nombre_documento_descarga_{{$documento->Id_Documento}}" value="{{$documento->nombre_Documento}}">                                                
                                                <input type="text" id="extension_documento_descarga_{{$documento->Id_Documento}}" value="{{$documento->formato_documento}}">                                                
                                            </div>
                                            <div class="text-center">
                                                <a href="javascript:void(0);" id="btn_generar_descarga_{{$documento->Id_Documento}}" data-id_documento_descargar="{{$documento->Id_Documento}}"><i class="fas fa-download text-info"></i></a>
                                            </div>
                                        <?php endif?>
                                    </td>
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
                <x-adminlte-button theme="danger" label="Cerrar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
        {{-- MODAL OTRO DOCUMENTO --}}
        <x-adminlte-modal id="modalOtroDocumento" title="Cargar Otro Documento" theme="info" icon="fas fa-plus" size='xl' v-centered="yes" disable-animations>
            <form id="formulario_documento_{{$documento->Id_Documento}}" class="form-inline align-items-center" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-12">
                    <div class="d-none">
                        <input type="text" name="Id_Documento" value="{{$documento->Id_Documento}}">
                        <input type="text" name="Nombre_documento" value="{{$documento->Nombre_documento}}">                                                
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
        <x-adminlte-modal id="modalListaDocumentos" title="Listado de Documentos" theme="info" icon="fas fa-plus" size='xl' scrollable="yes" disable-animations>
            <div class="col-12">
                <p class="h5">Los documentos marcados con &nbsp; &nbsp;<input type="checkbox" class="scales" disabled checked>&nbsp; &nbsp; son de cargue obligatorio para poder crear el evento.</p>
                <div class="table table-responsive">
                    <table id="listado_documentos" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
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
                                            <form id="formulario_documento_{{$documento->Id_Documento}}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="d-none">
                                                    <input type="text" name="Id_Documento" value="{{$documento->Id_Documento}}">
                                                    <input type="text" name="Nombre_documento" value="{{$documento->Nombre_documento}}">                                                
                                                    <input  type="text" name="EventoID" id="EventoID_{{$documento->Id_Documento}}">                                                
                                                </div>
                                                <div class="row">
                                                    <div class="input-group">
                                                        <input type="file" class="form-control select-doc" name="listadodocumento" 
                                                        id="listadodocumento_{{$documento->Id_Documento}}" aria-describedby="Carguedocumentos" aria-label="Upload"
                                                        <?php if($documento->Requerido === "Si"):?>
                                                            required
                                                        <?php endif?>
                                                        >
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
                <x-adminlte-button theme="danger" label="Cerrar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>

        {{-- MODAL OTRO DOCUMENTO --}}
        <x-adminlte-modal id="modalOtroDocumento" title="Cargar Otro Documento" theme="info" icon="fas fa-plus" size='xl' v-centered="yes" disable-animations>
            <form id="formulario_documento_{{$documento->Id_Documento}}" class="form-inline align-items-center" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-12">
                    <h5>Los campos marcados con <span style="color:red;">(*)</span> son obligatorios.</h5>
                    <div class="d-none">
                        <input type="text" name="Id_Documento" value="{{$documento->Id_Documento}}">
                        <input type="text" name="Nombre_documento" value="{{$documento->Nombre_documento}}">                                                
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
                            <tr>
                                <th>N°</th>
                                <th>Documento</th>
                                <th>Estado</th>
                                <th>Archivo</th>
                                <th>Descarga</th>
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
  


