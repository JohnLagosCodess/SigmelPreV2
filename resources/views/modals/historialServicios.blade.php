<input type="hidden" id="action_modulo_calificacion_Juntas" value="{{ route('calificacionJuntas') }}">
<input type="hidden" id="action_modulo_calificacion_Origen" value="{{ route('calificacionOrigen') }}">
<input type="hidden" id="action_modulo_calificacion_pcl" value="{{ route('calificacionPCL') }}">

<x-adminlte-modal id="historial_servicios" class="modalscroll" title="Historial de Servicios -" theme="info"
    icon="fas fa-project-diagram" size='xl' disable-animations>
      <div class="card-body">
            <div class="row">
                <div class="col-12" id="container_historial_s">
                    <div class="table table-responsive">
                        <table id="listado_historial_s" class="table table-striped table-bordered" width="100%">
                            <thead>
                                <tr>
                                    <th>Fecha de registro</th>
                                    <th>ID evento</th>
                                    <th>Servicio</th>
                                    <th>Estado</th>
                                    <th>Ultima Acción</th>
                                    <th>Fecha última Acción</th>
                                    <th>Detalle</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
      </div>
    <x-slot name="footerSlot">
        <div class="col-12 d-flex justify-content-end">
            <div class="col-3 m-2">
                <button type="button" id="btn_cm_historial_s" class="btn btn-danger" style="float:right !important;" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </x-slot>
</x-adminlte-modal>
