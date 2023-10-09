<x-adminlte-modal id="modal_editar_agudeza_visual" class="modalscroll" title="Edición Agudeza Visual" theme="info" icon="fas fa-plus-circle" size='xl' disable-animations>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Tenga en cuenta que hacer clic en la opción
                <strong>Ceguera Toal</strong> el sistema seteará el formulario.
            </div>
        </div>
    </div>
    <form id="form_editar_agudeza_visual" method="POST">
        @csrf
        <div class="d-none">   
            @if (!empty($hay_agudeza_visual[0]->Estado) && $hay_agudeza_visual[0]->Estado == 'Activo' && empty($hay_agudeza_visualre[0]->Estado))
                <input type="text" id="ID_evento" name="ID_evento" value="{{$info_agudeza->ID_evento_re}}">
                <input type="text" id="Id_Asignacion" name="Id_Asignacion" value="{{$info_agudeza->Id_Asignacion_re}}">
                <input type="text" id="Id_proceso" name="Id_proceso" value="{{$info_agudeza->Id_proceso_re}}">
                <input type="text" id="nombre_usuario" name="nombre_usuario" value="{{$user->name}}">
                <input type="text" id="dia_actual" name="dia_actual" value="{{date('Y-m-d')}}">
                <input type="text" id="dato_id_agudeza" value="{{$info_agudeza->Id_agudeza_re}}">
                <input type="text" id="dato_agudeza_ojo_izq" value="{{$info_agudeza->Agudeza_Ojo_Izq_re}}">
                <input type="text" id="dato_agudeza_ojo_der" value="{{$info_agudeza->Agudeza_Ojo_Der_re}}">
                <input type="text" id="dato_ceguera_total" value="{{$info_agudeza->Ceguera_Total_re}}">                                
            @elseif (!empty($hay_agudeza_visualre[0]->Estado) && $hay_agudeza_visualre[0]->Estado == 'Activo')
                <input type="text" id="ID_evento" name="ID_evento" value="{{$info_agudezare->ID_evento_re}}">
                <input type="text" id="Id_Asignacion" name="Id_Asignacion" value="{{$info_agudezare->Id_Asignacion_re}}">
                <input type="text" id="Id_proceso" name="Id_proceso" value="{{$info_agudezare->Id_proceso_re}}">
                <input type="text" id="nombre_usuario" name="nombre_usuario" value="{{$user->name}}">
                <input type="text" id="dia_actual" name="dia_actual" value="{{date('Y-m-d')}}">
                <input type="text" id="dato_id_agudeza" value="{{$info_agudezare->Id_agudeza_re}}">
                <input type="text" id="dato_agudeza_ojo_izq" value="{{$info_agudezare->Agudeza_Ojo_Izq_re}}">
                <input type="text" id="dato_agudeza_ojo_der" value="{{$info_agudezare->Agudeza_Ojo_Der_re}}">
                <input type="text" id="dato_ceguera_total" value="{{$info_agudezare->Ceguera_Total_re}}">
            @endif         
        </div>
        <div class="row text-center">
            <div class="col-12">
                <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" id="ceguera_total">
                    <label for="ceguera_total" class="custom-control-label">Ceguera Total</label>                    
                </div>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-4">
                <div class="form-group">
                    <label for="" class="col-form-label">Agudeza Ojo Izquierdo</label>
                    <select class="custom-select agudeza_ojo_izq" id="agudeza_ojo_izq" style="width: 100% !important;">
                         <option></option>
                    </select>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="" class="col-form-label">Agudeza Ojo Derecho</label>
                    <select class="custom-select agudeza_ojo_der" name="agudeza_ojo_der" id="agudeza_ojo_der" style="width: 100% !important;">
                        <option></option>
                    </select>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="" class="col-form-label">Agudeza Ambos Ojos</label>
                    {{-- <input type="number" class="form-control" name="agudeza_ambos_ojos" id="agudeza_ambos_ojos" value="{{$info_agudeza->Agudeza_Ambos_Ojos}}"> --}}
                    <input type="number" class="form-control" name="agudeza_ambos_ojos" id="agudeza_ambos_ojos">
                    <strong class="mensaje_fuera_rango text-danger text-sm d-none" role="alert">El valor debe encontrarse entre un rango de 0 a 110.</strong>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12">
                <div class="card-info" style="border: 1.5px solid black;">
                    <div class="card-header text-center">
                        <h5>Campo Visual</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @csrf
                            {{-- OJO IZQUIERDO --}}
                            <div class="col-6">
                                <div class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="todo_ojo_izquierdo" name="todo_ojo_izquierdo">
                                        <label for="todo_ojo_izquierdo" class="custom-control-label">Ojo Izquierdo</label>
                                    </div>
                                </div>
                                <div class="table-responsive mt-2">
                                    <table style="width: 100%; border-collapse:collapse;" id="tabla_campimetria_ojo_izquierdo"></table>
                                    <table id="tabla_suma_columnas_ojo_izq" class="mt-2" style="width: 100%; border-collapse:collapse;">
                                        <tr>
                                            <td class="ajustar_ancho"><span id="resultado_suma_ojo_izq_col_1">0</span></td>
                                            <td class="ajustar_ancho"><span id="resultado_suma_ojo_izq_col_2">0</span></td>
                                            <td class="ajustar_ancho"><span id="resultado_suma_ojo_izq_col_3">0</span></td>
                                            <td class="ajustar_ancho"><span id="resultado_suma_ojo_izq_col_4">0</span></td>
                                            <td class="ajustar_ancho"><span id="resultado_suma_ojo_izq_col_5">0</span></td>
                                            <td class="ajustar_ancho"><span id="resultado_suma_ojo_izq_col_6">0</span></td>
                                            <td class="ajustar_ancho"><span id="resultado_suma_ojo_izq_col_7">0</span></td>
                                            <td class="ajustar_ancho"><span id="resultado_suma_ojo_izq_col_8">0</span></td>
                                            <td class="ajustar_ancho"><span id="resultado_suma_ojo_izq_col_9">0</span></td>
                                            <td class="ajustar_ancho"><span id="resultado_suma_ojo_izq_col_10">0</span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            {{-- OJO DERECHO --}}
                            <div class="col-6">
                                <div class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="todo_ojo_derecho" name="todo_ojo_derecho">
                                        <label for="todo_ojo_derecho" class="custom-control-label">Ojo Derecho</label>
                                    </div>
                                </div>
                                <div class="table-responsive mt-2">
                                    <table style="width: 100%; border-collapse:collapse;" id="tabla_campimetria_ojo_derecho"></table>
                                    <table id="tabla_suma_columnas_ojo_der" class="mt-2" style="width: 100%; border-collapse:collapse;">
                                        <tr>
                                            <td class="ajustar_ancho"><span id="resultado_suma_ojo_der_col_1">0</span></td>
                                            <td class="ajustar_ancho"><span id="resultado_suma_ojo_der_col_2">0</span></td>
                                            <td class="ajustar_ancho"><span id="resultado_suma_ojo_der_col_3">0</span></td>
                                            <td class="ajustar_ancho"><span id="resultado_suma_ojo_der_col_4">0</span></td>
                                            <td class="ajustar_ancho"><span id="resultado_suma_ojo_der_col_5">0</span></td>
                                            <td class="ajustar_ancho"><span id="resultado_suma_ojo_der_col_6">0</span></td>
                                            <td class="ajustar_ancho"><span id="resultado_suma_ojo_der_col_7">0</span></td>
                                            <td class="ajustar_ancho"><span id="resultado_suma_ojo_der_col_8">0</span></td>
                                            <td class="ajustar_ancho"><span id="resultado_suma_ojo_der_col_9">0</span></td>
                                            <td class="ajustar_ancho"><span id="resultado_suma_ojo_der_col_10">0</span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        {{-- TABLA DE RESULTADOS --}}
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="tabla_calculos_deficiencia_visual_modal" style="width: 100%; border-collapse:collapse;">
                                        <thead>
                                            <tr>
                                                <th>Agudeza Ojo Izquierdo</th>
                                                <th>Agudeza Ojo Derecho</th>
                                                <th>Agudeza Ambos Ojos</th>
                                                <th>Puntaje de Agudeza Visual Funcional (PAVF)</th>
                                                <th>Deficiencia por Agudeza Visual (DAV)</th>
                                                <th>Campo Visual Ojo Izquierdo</th>
                                                <th>Campo Visual Ojo Derecho</th>
                                                <th>Campo Visual Ambos Ojos</th>
                                                <th>Puntaje Campo Visual Funcional (CVF)</th>
                                                <th>Deficiencia por Campo Visual (DCV)</th>
                                                <th>Deficiencia Global del Sistema Visual (DSV)</th>
                                                <th>Deficiencia</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="text-center">
                                                @if (!empty($hay_agudeza_visual[0]->Estado) && $hay_agudeza_visual[0]->Estado == 'Activo' && empty($hay_agudeza_visualre[0]->Estado))
                                                    <td><input type="text" id="resultado_agudeza_ojo_izquierdo" name="resultado_agudeza_ojo_izquierdo" class="text-center" readonly value="{{$info_agudeza->Agudeza_Ojo_Izq_re}}"></td>
                                                    <td><input type="text" id="resultado_agudeza_ojo_derecho" name="resultado_agudeza_ojo_derecho" class="text-center" readonly value="{{$info_agudeza->Agudeza_Ojo_Der_re}}"></td>
                                                    <td><input type="text" id="resultado_agudeza_ambos_ojos" name="resultado_agudeza_ambos_ojos" class="text-center" readonly value="{{$info_agudeza->Agudeza_Ambos_Ojos_re}}"></td>
                                                    <td><input type="text" id="resultado_pavf" name="resultado_pavf" class="text-center" readonly value="{{$info_agudeza->PAVF_re}}"></td>
                                                    <td><input type="text" id="resultado_dav" name="resultado_dav" class="text-center" readonly value="{{$info_agudeza->DAV_re}}"></td>
                                                    <td><input type="text" id="resultado_campo_visual_ojo_izq" name="resultado_campo_visual_ojo_izq" class="text-center" readonly value="{{$info_agudeza->Campo_Visual_Ojo_Izq_re}}"></td>
                                                    <td><input type="text" id="resultado_campo_visual_ojo_der" name="resultado_campo_visual_ojo_der" class="text-center" readonly value="{{$info_agudeza->Campo_Visual_Ojo_Der_re}}"></td>
                                                    <td><input type="text" id="resultado_campo_visual_ambos_ojos" name="resultado_campo_visual_ambos_ojos" class="text-center" readonly value="{{$info_agudeza->Campo_Visual_Ambos_Ojos_re}}"></td>
                                                    <td><input type="text" id="resultado_cvf" name="resultado_cvf" class="text-center" readonly value="{{$info_agudeza->CVF_re}}"></td>
                                                    <td><input type="text" id="resultado_dcv" name="resultado_dcv" class="text-center" readonly value="{{$info_agudeza->DCV_re}}"></td>
                                                    <td><input type="text" id="resultado_dsv" name="resultado_dsv" class="text-center" readonly value="{{$info_agudeza->DSV_re}}"></td>
                                                    <td><input type="text" id="resultado_deficiencia" name="resultado_deficiencia" class="text-center" readonly value="{{$info_agudeza->Deficiencia_re}}"></td>                                                    
                                                @elseif(!empty($hay_agudeza_visualre[0]->Estado) && $hay_agudeza_visualre[0]->Estado == 'Activo')
                                                    <td><input type="text" id="resultado_agudeza_ojo_izquierdo" name="resultado_agudeza_ojo_izquierdo" class="text-center" readonly value="{{$info_agudezare->Agudeza_Ojo_Izq_re}}"></td>
                                                    <td><input type="text" id="resultado_agudeza_ojo_derecho" name="resultado_agudeza_ojo_derecho" class="text-center" readonly value="{{$info_agudezare->Agudeza_Ojo_Der_re}}"></td>
                                                    <td><input type="text" id="resultado_agudeza_ambos_ojos" name="resultado_agudeza_ambos_ojos" class="text-center" readonly value="{{$info_agudezare->Agudeza_Ambos_Ojos_re}}"></td>
                                                    <td><input type="text" id="resultado_pavf" name="resultado_pavf" class="text-center" readonly value="{{$info_agudezare->PAVF_re}}"></td>
                                                    <td><input type="text" id="resultado_dav" name="resultado_dav" class="text-center" readonly value="{{$info_agudezare->DAV_re}}"></td>
                                                    <td><input type="text" id="resultado_campo_visual_ojo_izq" name="resultado_campo_visual_ojo_izq" class="text-center" readonly value="{{$info_agudezare->Campo_Visual_Ojo_Izq_re}}"></td>
                                                    <td><input type="text" id="resultado_campo_visual_ojo_der" name="resultado_campo_visual_ojo_der" class="text-center" readonly value="{{$info_agudezare->Campo_Visual_Ojo_Der_re}}"></td>
                                                    <td><input type="text" id="resultado_campo_visual_ambos_ojos" name="resultado_campo_visual_ambos_ojos" class="text-center" readonly value="{{$info_agudezare->Campo_Visual_Ambos_Ojos_re}}"></td>
                                                    <td><input type="text" id="resultado_cvf" name="resultado_cvf" class="text-center" readonly value="{{$info_agudezare->CVF_re}}"></td>
                                                    <td><input type="text" id="resultado_dcv" name="resultado_dcv" class="text-center" readonly value="{{$info_agudezare->DCV_re}}"></td>
                                                    <td><input type="text" id="resultado_dsv" name="resultado_dsv" class="text-center" readonly value="{{$info_agudezare->DSV_re}}"></td>
                                                    <td><input type="text" id="resultado_deficiencia" name="resultado_deficiencia" class="text-center" readonly value="{{$info_agudezare->Deficiencia_re}}"></td>                                                    
                                                @endif
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-12">
                <div class="alert d-none" id="resultado_insercion" role="alert"></div>
            </div>
            <div>
                <input type="submit" id="btn_guardar_agudeza" class="mr-auto btn btn-info" value="Guardar">
                <button type="button" id="btn_cerrar_modal_agudeza" class="btn btn-danger" style="float:right !important;" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
        <x-slot name="footerSlot" class="d-none">
        </x-slot>
    </form>
</x-adminlte-modal>

@section('js_edicion_agudeza')
<script type="text/javascript" src="/js/edicion_campimetria_re.js"></script>
@stop