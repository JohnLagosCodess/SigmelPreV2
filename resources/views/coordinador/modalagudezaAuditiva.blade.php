<x-adminlte-modal id="modal_grilla_auditivo" class="modalscroll" title="Agudeza Auditiva" theme="info" icon="fas fa-plus-circle" size='xl' disable-animations>
    <div class="row">
        <div class="col-12">
            <h5>Los campos marcados con <span style="color:red;">(*)</span> son Obligatorios</h5>
            <form id="form_agregar_agudeza_auditiva" method="POST">
                @csrf
                <div class="card-info" style="border: 1.5px solid black; border-radius: 2px;">
                    <div class="card-header text-center">
                        <h5>Tabla 9.3 Deficiencia por Alteraciones del Sistema Auditivo</h5>
                        <input hidden="hidden" type="text" id="ID_evento" name="ID_evento" value="{{$array_datos_calificacionPclTecnica[0]->ID_evento}}">
                        <input hidden="hidden" type="text" id="Id_Asignacion" name="Id_Asignacion" value="{{$array_datos_calificacionPclTecnica[0]->Id_Asignacion}}">
                        <input hidden="hidden" type="text" id="Id_proceso" name="Id_proceso" value="{{$array_datos_calificacionPclTecnica[0]->Id_proceso}}">
                    </div>
                    <div class="card-body">                                
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="oido_izquierdo">Oído Izquierdo <span style="color: red;">(*)</span></label><br>
                                    <select class="oido_izquierdo custom-select" name="oido_izquierdo" id="oido_izquierdo" style="width: 100%;" required>
                                        <option value="">Seleccione una opción</option>
                                    </select>
                                </div> 
                            </div>                                    
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="oido_derecho">Oído Derecho <span style="color: red;">(*)</span></label><br>
                                    <select class="oido_derecho custom-select" name="oido_derecho" id="oido_derecho" style="width: 100%;" required>
                                        <option value="">Seleccione una opción</option>
                                    </select>
                                </div>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="table-responsive">
                                        <table id="calculo_Agudeza_auditiva" class="table table-striped table-bordered" width="100%">
                                            <thead>
                                                <tr class="bg-info">
                                                    <th>Deficiencia Monoaural Izquierda</th>
                                                    <th>Deficiencia Monoaural Derecha</th>
                                                    <th>Deficiencia Binaural</th>
                                                    <th>Adicion por Tinnitus</th>
                                                    <th>Deficiencia</th>
                                                </tr>
                                            </thead>
                                            <tbody id="Agudeza_auditiva">                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12">
                        <div class="alert d-none" id="resultado_insercion_auditiva" role="alert"></div>
                    </div>
                    <div class="col-12">
                        <input type="submit" id="Guardar_Auditivo" class="mr-auto btn btn-info" value="Guardar">
                        <button type="button" id="btn_cerrar_modal_agudeza" class="btn btn-danger" style="float:right !important;" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </form>                            
        </div>
    </div>   
    <x-slot name="footerSlot">
    </x-slot>     
</x-adminlte-modal>

