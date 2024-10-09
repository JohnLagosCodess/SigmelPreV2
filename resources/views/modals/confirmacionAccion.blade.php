<x-adminlte-modal id="confirmar_accion" class="modalscroll" title="Confirmar acción" theme="info"
    icon="fas fa-info-circle"  disable-animations>
    <div class="card-header">
        <h5>Está a punto de ejecutar el siguiente movimiento:</h5>
      </div>
      <div class="card-body" style="font-size: 1.25rem !important;">
        <div class="row">
            <label >Acción:</label>
            <p class="pl-2 text-end" id="c_accion_ejecutar">
            </p>
        </div>
        <div class="row">
            <label  >Fecha de acción:</label>
            <p class="pl-2 text-end" id="c_f_accion">
            </p>
        </div>
        <div class="row" id="c_estado_facturacion">
            <label >Estado de facturación:</label>
            <p class="pl-2 text-end" id="c_e_facturacion">
            </p>
        </div>
        <div class="row">
            <label >Profesional asignado:</label>
            <p class="pl-2 text-end" id="c_profesional">
            </p>
        </div>
        <div class="row">
            <label >Servicio:</label>
            <p class="pl-2 text-end" id="c_servicio">
            </p>
        </div>
        <div class="row" id="n_confirmarAccion">
            <label >Nota:</label>
            <p class="pl-2 text-end" id="c_nota">
                Si la acción que desea ejecutar cuenta con un Estado de facturación relacionado se generará un registro en el informe correspondiente.
            </p>
        </div>
        <div class="row alert alert-danger h5 d-none" id="alerta_accion"></div>
        <div class="row alert alert-info h5 d-none" id="alerta_accion_ejecutando">
            <span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>
             Actualizando informacion, por favor espere...
        </div>
      </div>
    <x-slot name="footerSlot">
        <div class="col-12 d-flex justify-content-end">
            <div class="col-9">
                <h5>¿Esta seguro de realizar el movimiento?</h5>
            </div>
            <div class="col-3 m-2">
                <button class="btn btn-info" id="c_ejecutar_accion">Si</button>
                <button class="btn btn-danger" id="c_no_accion" data-dismiss="modal">No</button>
            </div>
        </div>
    </x-slot>
</x-adminlte-modal>
