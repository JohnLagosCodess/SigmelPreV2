<x-adminlte-modal id="alertas_gestion" class="modalscroll" title="Confirmar gestión" theme="info" icon="fas fa-info-circle"
    disable-animations>
    <div class="card-header" id="contenido_header_gestion">
        <h5 id="titulo"></h5>
        <div class="row d-flex justify-content-center">
            <span id="subtitulo"></span>
        </div>
        <div class="row d-flex justify-content-center">
            <span id="subtitulo2"></span>
        </div>
    </div>
    <div class="card-body" style="font-size: 1.25rem !important;">
        <div class="row d-flex justify-content-evenly" id="cuerpo_gestion">
        </div>
        <div class="row" id="content_footer"></div>
        <div class="row alert alert-danger h6 d-none" id="alerta_gestion"></div>
    </div>
    <div name="footerSlot" id="footer_alerta">
        <div class="col-12 d-flex justify-content-end">
            <div class="col-9" id="info_footer">
                <h5>¿Esta de acuerdo?</h5>
            </div>
            <div class="col-3 d-flex justify-content-between">
                <button class="btn btn-info" id="ejecutar_gestion">Si</button>
                <button class="btn btn-danger" id="no_ejecutar_gestion" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</x-adminlte-modal>
