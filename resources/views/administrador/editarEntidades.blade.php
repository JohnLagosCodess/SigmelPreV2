<x-adminlte-modal class="habilitar_modal_edicion_entidad" id="" theme="info" icon="fa fa-pen" size='xl' scrollable="yes" disable-animations>
    <div class="row">
        <div class="col-12">
            <h5>Los campos marcados con <span style="color:red;">(*)</span> son obligatorios.</h5>
            <form class="actualizar_entidad" method="POST">
                @csrf
                <div class="row">
                    <input type="hidden" id="captura_id_entidad" name="captura_id_entidad">
                    <div class="col-3">
                        <div class="form-group">
                            <label  class="col-form-label">Tipo de Entidad <span style="color:red;">(*)</span></label>
                            <select class="editar_tipo_entidad custom-select" name="edi_tipo_entidad" id="edi_tipo_entidad" style="width:100%;" requierd></select>
                        </div>
                    </div> 
                    <div class="columna_otro_entidad_edit col-3" style="display:none" >
                        <div class="form-group">
                            <label  class="col-form-label">Otra Entidad <span style="color:red;">(*)</span></label>
                            <input type="text" class="mayus_entidad form-control" name="otra_entidad_edit" id="otra_entidad_edit">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label  class="col-form-label">Nombre de Entidad <span style="color:red;">(*)</span></label>
                            <input type="text" class="mayus_entidad form-control" name="nombre_entidad" id="nombre_entidad" required>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label  class="col-form-label">NIT<span style="color:red;">(*)</span></label>
                            <input type="texto" class="form-control" name="nit_entidad" id="nit_entidad" required>
                        </div>
                    </div>  
                    <div class="col-3">
                        <div class="form-group">
                            <label  class="col-form-label">Teléfóno Principal<span style="color:red;">(*)</span></label>
                            <input type="number" class="soloNumeros form-control" name="entidad_telefono" id="entidad_telefono" required>
                        </div>
                    </div>  
                    <div class="col-3">
                        <div class="form-group">
                            <label  class="col-form-label">Otros Teléfóno(s)</label>
                            <input type="text" class="form-control" name="entidad_telefono_otro" id="entidad_telefono_otro">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label  class="col-form-label">E-mail Principal<span style="color:red;">(*)</span></label>
                            <input type="email" class="form-control" name="entidad_email" id="entidad_email" required>
                        </div>
                    </div> 
                    <div class="col-3">
                        <div class="form-group">
                            <label  class="col-form-label">Otros E-mail(s)</label>
                            <input type="text" class="form-control" name="entidad_email_otro" id="entidad_email_otro">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label  class="col-form-label">Dirección<span style="color:red;">(*)</span></label>
                            <input type="text" class="mayus_entidad form-control" name="entidad_direccion" id="entidad_direccion" required>
                        </div>
                    </div> 
                    <div class="col-3">
                        <div class="form-group">
                            <label  class="col-form-label">Departamento<span style="color:red;">(*)</span></label>
                            <select class="editar_entidad_departamento proceso custom-select" name="edi_entidad_departamento" id="edi_entidad_departamento" style="width:100%;" requierd></select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label  class="col-form-label">Ciudad<span style="color:red;">(*)</span></label>
                            <select class="editar_entidad_ciudad proceso custom-select" name="edi_entidad_ciudad" id="edi_entidad_ciudad" style="width:100%;" disabled></select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label  class="col-form-label">Medio de Notificación<span style="color:red;">(*)</span></label>
                            <select class="editar_entidad_medio_noti proceso custom-select" name="edi_entidad_medio_noti" id="edi_entidad_medio_noti" style="width:100%;" requierd></select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label  class="col-form-label">Sucursal<span style="color:red;">(*)</span></label>
                            <input type="text" class="mayus_entidad form-control" name="entidad_sucursal" id="entidad_sucursal" required>
                        </div>
                    </div> 
                    <div class="col-3">
                        <div class="form-group">
                            <label  class="col-form-label">Dirigido a<span style="color:red;">(*)</span></label>
                            <input type="text" class="mayus_entidad form-control" name="entidad_dirigido" id="entidad_dirigido" required>
                        </div>
                    </div> 
                    <div class="col-3">
                        <div class="form-group">
                            <label  class="col-form-label">Status<span style="color:red;">(*)</span></label>
                            <select class="editar_estado_entidad proceso custom-select" name="edit_estado_entidad" id="edit_estado_entidad" style="width:100%;" requierd>
                            </select>
                        </div>
                    </div> 
                </div>
                <hr>
                <div id="mostrar_mensaje_actualizacion" class="alert mt-2 mr-auto d-none" role="alert"></div>
                <button type="submit" id="btn_actualizar_entidad" class="btn btn-info mr-auto">Guardar Información</button>
                <button type="button" id="btn_actualizar_consulta" class="btn btn-info mr-auto d-none">Actualizar</button>
                <button type="button" class="btn btn-danger" style="float:right;" data-dismiss="modal">Cerrar</button>
                
                <div class="text-center" id="mostrar_barra_editar_entidad"  style="display:none;">                                
                    <button class="btn btn-info" type="button" disabled>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Actualizando Entidad por favor espere...
                    </button>
                </div>
                <x-slot name="footerSlot">
                </x-slot>
            </form>
        </div>
    </div>
</x-adminlte-modal>

