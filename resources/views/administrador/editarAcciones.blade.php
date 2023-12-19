<x-adminlte-modal class="habilitar_modal_edicion_accion" id="" theme="info" icon="fa fa-pen" size='xl' scrollable="yes" disable-animations>
    <div class="row">
        <div class="col-12">
            <h5>Los campos marcados con <span style="color:red;">(*)</span> son obligatorios.</h5>
            <form class="actualizar_accion" id="" method="POST">
                @csrf
                <input type="hidden" name="id_accion_editar" id="id_accion_editar">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label  class="col-form-label">Estado <span style="color:red;">(*)</span></label>
                            <select class="custom-select estado_edicion" name="estado_edicion" id="estado_edicion" style="width:100%;" required></select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label  class="col-form-label">Acción <span style="color:red;">(*)</span></label>
                            <input type="text" class="form-control" name="accion" id="accion" required>
                            {{-- <textarea class="form-control" name="accion" id="accion" rows="2" required></textarea> --}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label  class="col-form-label">Descripción de Acción <span style="color:red;">(*)</span></label>
                            <textarea class="form-control" name="descrip_accion" id="descrip_accion" rows="3" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="">
                            <label class="col-form-label">Status <span style="color: red;">(*)</span></label>
                            <select class="custom-select status" name="status" id="status" style="width:100%;" required>
                                <option></option>
                                {{-- <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option> --}}
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="">
                            <label class="col-form-label">Fecha de Creación <span style="color: red;">(*)</span></label>
                            <input type="date" class="form-control" name="fecha_creacion" id="fecha_creacion" readonly>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="alert mt-2 d-none" id="resultado_insercion_edicion_accion" role="alert"></div>
                <button type="submit" id="btn_actualizar_accion" class="btn btn-info mr-auto">Guardar Información</button>
                {{-- <button type="button" id="btn_actualizar_consulta" class="btn btn-info mr-auto d-none">Actualizar</button> --}}
                <button type="button" class="btn btn-danger" style="float:right;" data-dismiss="modal">Cerrar</button>

                <x-slot name="footerSlot">
                </x-slot>
            </form>
        </div>
    </div>
</x-adminlte-modal>