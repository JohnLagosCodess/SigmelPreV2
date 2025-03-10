<div class="row">
    <x-adminlte-modal id="modalReemplazarArchivos" class="replace" title="Reemplazar documento" theme="info" icon="fas fa-sync-alt" size='l' scrollable="no" disable-animations>
        <form id="reemplazar_documento" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="alert alert-danger cargueundocumentoprimeromodal d-none" role="alert">
                <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Por favor, adjunta un documento antes de cargar. 
            </div>
            <div class="alert alert-danger extensionInvalidaModal d-none" role="alert">
                <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Este archivo solo puede ser reemplazado por un archivo con extensión  <strong id="extensionInvalidaMensaje"></strong>
            </div>
            <div class="alerta_externa_comunicado_modal alert alert-success mt-2 mr-auto d-none" role="alert"></div>
            <div style="display: flex; justify-content:center;">
                <input style="width:100%%" type="file" class="form-control select-doc" name="cargue_comunicados_modal" id="cargue_comunicados_modal" aria-describedby="Carguecomunicados" aria-label="Upload" accept=".pdf, .doc, .docx"/>
                <button type="submit" class="btn btn-sm btn-info" id="cargarComunicadoModal">Cargar</button>
            </div>
        </form>
    </x-adminlte-modal>
</div>   
<style>
    .replace .modal-dialog {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }
    .replace .modal-footer{
        display: none;
    }

    .replace .modal-content {
        margin: 5%;
    }
</style>
