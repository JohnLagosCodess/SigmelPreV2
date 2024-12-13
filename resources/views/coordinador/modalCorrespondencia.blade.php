    {{-- Modal  Crear expediente --}}
    <div class="row">
        <div class="contenedor_sol_correspondencia" style="float: left;">
            <x-adminlte-modal id="modalCorrespondencia" title="Correspondencia" theme="info" icon="fas fa-plus mr-2"
                size='xl' disable-animations>
                <div class="row">
                    <div class="col-12">
                        <form id="form_correspondencia" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="nombre_afiliado">Nombre de afiliado</label>
                                            <input class="form-control" type="text" name="nombre_afiliado"
                                                id="nombre_afiliado" readonly>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="n_identificacion">N° de Identificacion</label>
                                            <input class="form-control" type="text" name="n_identificacion"
                                                id="n_identificacion"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="id_evento">ID evento</label>
                                            <br>
                                            <input hidden="hidden" type="text" class="form-control" name="id_evento"
                                                id="id_evento" disabled>
                                            {{-- DATOS PARA VER EDICIÓN DE EVENTO --}}
                                            <a onclick="document.getElementById('botonVerEdicionEvento').click();"
                                                id="enlace_ed_evento" style="cursor:pointer; font-weight: bold;"
                                                class="btn text-info" type="button"></a>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="id_destinatario">ID Destinatario</label>
                                            <input class="form-control" type="text" name="id_destinatario" id="id_destinatario" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-4">
                                        <div class="form-group d-none" id="ver_chequeo">
                                            <a for="lista_chequeo" style="cursor: pointer;">Visualizar lista de
                                                chequeo</a>
                                            <i class="far fa-eye text-info"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-info">
                                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                                <h5>Información de Correspondencia</h5>
                                            </div>

                                            <div class="card-body">
                                                <input class="form-control" type="hidden" name="tipo_correspondencia" id="tipo_correspondencia">
                                                <input class="form-control" type="hidden" name="id_asignacion" id="id_asignacion">
                                                <input class="form-control" type="hidden" name="id_proceso" id="id_proceso">
                                                <input class="form-control" type="hidden" name="id_comunicado" id="id_comunicado">
                                                <input class="form-control" type="hidden" name="id_correspondencia" id="id_correspondencia">
                                                <div class="row">
                                                    
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="radicado">N° Radicado</label>
                                                            <input class="form-control" type="text" name="radicado"
                                                                id="radicado" disabled>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="radicado">N° de orden</label>
                                                            <input class="form-control" type="text" name="n_orden"
                                                                id="n_orden" disabled>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-4">
                                                        <label for="t_destinatario">Tipo de Destinatario<span style="color:red;">(*)</span></label>
                                                        <div class="form-group d-flex">
                                                            <div class="col-6 custom-control custom-checkbox">
                                                                <input class="custom-control-input" type="checkbox" id="check_principal" name="check_principal" value="Principal" required>
                                                                <label for="check_principal" class="custom-control-label">Principal</label>                 
                                                            </div>
                                                            <div class="col-6 custom-control custom-checkbox">
                                                                <input class="custom-control-input" type="checkbox" id="check_copia" name="check_copia" value="Copia" required>
                                                                <label for="check_copia" class="custom-control-label">Copia</label>                 
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="nombre_destinatario">Nombre de Destinatario</label>
                                                            <input class="form-control" type="text" name="nombre_destinatario"
                                                                id="nombre_destinatario"disabled>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="direccion">Direccion</label>
                                                            <input class="form-control" type="text" name="direccion"
                                                                id="direccion" disabled>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="departamento">Departamento</label>
                                                            <input class="form-control" type="text" name="departamento"
                                                                id="departamento" disabled>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="ciudad">Ciudad</label>
                                                            <input class="form-control" type="text" name="ciudad"
                                                                id="ciudad" disabled>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="telefono">Telefono/Celular</label>
                                                            <input class="form-control" type="text" name="telefono"
                                                                id="telefono" disabled>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="email">E-mail</label>
                                                            <input class="form-control" type="email" name="email"
                                                                id="email" disabled>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="m_notificacion">Medio de Notificacion</label>
                                                            <input class="form-control" type="text" name="m_notificacion"
                                                                id="m_notificacion" disabled>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="n_guia">N° de guia</label>
                                                            <input class="form-control" type="text" name="n_guia"
                                                                id="n_guia">
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="folios">Folios</label>
                                                            <input class="form-control" type="number" name="folios"
                                                                id="folios">
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="f_envio">Fecha de envio</label>
                                                            <input class="form-control" type="date"  name="f_envio" id="f_envio" max="{{ date('Y-m-d') }}" min="1900-01-01">
                                                            <span class="d-none" id="alerta_fecha_envio" style="color: red; font-style: italic;"></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="f_notificacion">Fecha de notificacion</label>
                                                            <input class="form-control" type="date" name="f_notificacion" id="f_notificacion" max="{{ date('Y-m-d') }}" min="1900-01-01">
                                                            <span class="d-none" id="f_notificacion_alerta" style="color: red; font-style: italic;"></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="state_notificacion">Estado de notificacion<span style="color:red;">(*)</span></label>
                                                            <select class="forma_envio custom-select state_notificacion" name="state_notificacion" id="state_notificacion" style="width: 100%;" required>                                                    
                                                                <option value="">Seleccione una opción</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="alerta_correspondencia alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                            <div class="alerta_error alert alert-danger mt-2 mr-auto d-none" role="alert"></div>
                            <div class="alerta_advertencia alert alert-danger mt-2 mr-auto d-none" role="alert"></div>
                            <div style="display: flex; justify-content: flex-end; gap:10px;">                                
                                {{-- <button type="submit" class="btn d-none btn-info actualizar_correspondencia" id="btn_actualizar_correspondencia" data-accion='Actualizar'>Actualizar</button>
                                <input type="submit" id="ActualizarPronuncia" name="ActualizarPronuncia" class="btn btn-info" value="Actualizar">
                                <button type="submit" class="btn btn-info guardar_correspondencia" id="btn-guardar-correspondencia" data-accion='Guardar'>Guardar</button> --}}
                                <input type="submit" id="btn_guardar_actualizar_correspondencia" name="btn_guardar_actualizar_correspondencia" class="btn btn-info" value="Guardar">
                                <x-adminlte-button theme="danger" label="Cerrar" id="cerar_modalCorrespondencia" data-dismiss="modal" />
                            </div>
                        </form>

                    </div>
                </div>

                <x-slot name="footerSlot">
                </x-slot>
            </x-adminlte-modal>

        </div>
    </div>
    <section id="loading">
        <div id="loading-content"></div>
    </section>

    {{-- Validación en los campos de fecha, en el cual la fecha de envio no debe ser mayor a la fecha de notificación, y ninguna de esas dos fechas pueden ser mayores a la fecha actual --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let today = new Date().toISOString().split("T")[0];
            // Obtener referencias a los campos de fecha
            const fechaEnvio = document.getElementById('f_envio');
            const fechaNotificacion = document.getElementById('f_notificacion');
            const alerta_fecha_envio = document.getElementById('alerta_fecha_envio');
    
            // Evento para cuando se cambie la fecha de envío
            fechaEnvio.addEventListener('change', function () {
                // Obtener los valores de las fechas
                const envioValue = fechaEnvio.value;
                const notificacionValue = fechaNotificacion.value ? fechaNotificacion.value : null;
                // Validar que la fecha de envío no sea mayor a la fecha de notificación
                if (notificacionValue && envioValue > notificacionValue) {
                    $("#alerta_fecha_envio").text('La fecha ingresada no debe ser superior a la fecha de Notificación').removeClass('d-none')
                    fechaEnvio.value = ''; // Limpiar el campo
                }
                else if(envioValue < '1900-01-01'){
                    $(`#alerta_fecha_envio`).text("La fecha ingresada no es válida. Por favor valide la fecha ingresada").removeClass("d-none");
                    $('#btn_guardar_actualizar_correspondencia').addClass('d-none');
                    return;
                }
                //Validamos que la fecha no sea mayor a la fecha actual
                else if(envioValue > today){
                    $(`#alerta_fecha_envio`).text("La fecha ingresada no puede ser mayor a la actual").removeClass("d-none");
                    $('#btn_guardar_actualizar_correspondencia').addClass('d-none');
                    return;
                }
                else{
                    $("#alerta_fecha_envio").text('').addClass('d-none')
                    $('#btn_guardar_actualizar_correspondencia').removeClass('d-none');
                }
            });
            //Fecha de notificación
            fechaNotificacion.addEventListener('change', function () {
                //Notificación de las fechas
                const notificacionValue = fechaNotificacion.value ? fechaNotificacion.value : null;
                
                if(fechaEnvio.value && fechaNotificacion.value && fechaEnvio.value > fechaNotificacion.value){
                        $("#alerta_fecha_envio").text('La fecha ingresada no debe ser superior a la fecha de Notificación').removeClass('d-none');
                        fechaEnvio.value = ''; // Limpiar el campo
                }else{
                    $("#alerta_fecha_envio").addClass('d-none')
                }
                //Validaciones generales para el input de fecha de notificación
                //Validamos que la fecha no sea menor a 1900-01-01
                if(notificacionValue < '1900-01-01'){
                    $(`#${this.id}_alerta`).text("La fecha ingresada no es válida. Por favor valide la fecha ingresada").removeClass("d-none");
                    $('#btn_guardar_actualizar_correspondencia').addClass('d-none');
                    return;
                }
                //Validamos que la fecha no sea mayor a la fecha actual
                if(notificacionValue > today){
                    $(`#${this.id}_alerta`).text("La fecha ingresada no puede ser mayor a la actual").removeClass("d-none");
                    $('#btn_guardar_actualizar_correspondencia').addClass('d-none');
                    return;
                }
                //Validamos que la fecha no sea menor a 1900-01-01
                if(fechaEnvio.value && fechaEnvio.value < '1900-01-01'){
                    $(`#alerta_fecha_envio`).text("La fecha ingresada no es válida. Por favor valide la fecha ingresada").removeClass("d-none");
                    $('#btn_guardar_actualizar_correspondencia').addClass('d-none');
                    return;
                }
                //Validamos que la fecha no sea mayor a la fecha actual
                if(fechaEnvio.value && fechaEnvio.value > today){
                    $(`#alerta_fecha_envio`).text("La fecha ingresada no puede ser mayor a la actual").removeClass("d-none");
                    $('#btn_guardar_actualizar_correspondencia').addClass('d-none');
                    return;
                }
                $("#alerta_fecha_envio").text('').addClass('d-none')
                $(`#${this.id}_alerta`).text('').addClass("d-none");
                return $('#btn_guardar_actualizar_correspondencia').removeClass('d-none');
            });
        });
    </script>
