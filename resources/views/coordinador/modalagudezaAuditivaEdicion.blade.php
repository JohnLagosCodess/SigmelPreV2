<x-adminlte-modal id="modal_grilla_auditivo" class="modalscroll" title="Agudeza Auditiva" theme="info" icon="fas fa-plus-circle" size='xl' disable-animations>
    <div class="row">
        <div class="col-12">
            <h5>Los campos marcados con <span style="color:red;">(*)</span> son Obligatorios</h5>
            <form id="form_agregar_agudeza_auditiva_editar" method="POST">
                @csrf
                <div class="card-info" style="border: 1.5px solid black; border-radius: 2px;">
                    <div class="card-header text-center">
                        <h5>Tabla 9.3 Deficiencia por Alteraciones del Sistema Auditivo</h5>
                        <input hidden="hidden" type="text" id="ID_evento_editar" name="ID_evento_editar" value="{{$array_datos_calificacionPclTecnica[0]->ID_evento}}">
                        <input hidden="hidden" type="text" id="Id_Asignacion_editar" name="Id_Asignacion_editar" value="{{$array_datos_calificacionPclTecnica[0]->Id_Asignacion}}">
                        <input hidden="hidden" type="text" id="Id_proceso_editar" name="Id_proceso_editar" value="{{$array_datos_calificacionPclTecnica[0]->Id_proceso}}">
                    </div>
                    <div class="card-body">                                
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="oido_izquierdo_editar">Oído Izquierdo <span style="color: red;">(*)</span></label><br>
                                    <select class="oido_izquierdo_editar custom-select" name="oido_izquierdo_editar" id="oido_izquierdo_editar" style="width: 100%;" required>
                                        <option value="{{$array_agudeza_Auditiva[0]->Oido_Izquierdo}}">{{$array_agudeza_Auditiva[0]->Oido_Izquierdo}}</option>
                                        <option value="265">265</option>
                                    </select>
                                </div> 
                            </div>                                    
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="oido_derecho_editar">Oído Derecho <span style="color: red;">(*)</span></label><br>
                                    <select class="oido_derecho_editar custom-select" name="oido_derecho_editar" id="oido_derecho_editar" style="width: 100%;" required>
                                        <option value="{{$array_agudeza_Auditiva[0]->Oido_Derecho}}">{{$array_agudeza_Auditiva[0]->Oido_Derecho}}</option>
                                        <option value="360">360</option>
                                    </select>
                                </div>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="table-responsive">
                                        <table id="calculo_Agudeza_auditiva_editar" class="table table-striped table-bordered" width="100%">
                                            <thead>
                                                <tr class="bg-info">
                                                    <th>Deficiencia Monoaural Izquierda</th>
                                                    <th>Deficiencia Monoaural Derecha</th>
                                                    <th>Deficiencia Binaural</th>
                                                    <th>Adicion por Tinnitus</th>
                                                    <th>Deficiencia</th>
                                                </tr>
                                            </thead>
                                            <tbody id="Agudeza_auditiva_editar">                                                
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
                        <div class="alert d-none" id="resultado_insercion_auditiva_editar" role="alert"></div>
                    </div>
                    <div class="col-12">
                        <input type="submit" id="Guardar_Auditivo_editar" class="mr-auto btn btn-info" value="Guardar">
                        <button type="button" id="btn_cerrar_modal_agudeza_editar" class="btn btn-danger" style="float:right !important;" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </form>                            
        </div>
    </div>   
    <x-slot name="footerSlot">
    </x-slot>     
</x-adminlte-modal>

