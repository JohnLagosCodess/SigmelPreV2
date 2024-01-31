<x-adminlte-modal class="habilitar_modal_edicion_cliente" id="" theme="info" icon="fa fa-pen" size='xl' scrollable="yes" disable-animations>
    <div class="row">
        <div class="col-12">
            <h5>Los campos marcados con <span style="color:red;">(*)</span> son obligatorios.</h5>
            <form class="actualizar_cliente" id="" method="POST">
                @csrf
                <input type="hidden" name="id_cliente_editar" id="id_cliente_editar">
                <div class="card-body">
                    <div class="col-12">
                        {{-- INFO BÁSICA DEL CLIENTE Y SUCURSALES --}}
                        <div class="card-info">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Información Principal</h5>
                            </div>
                            <div class="card-body">
                                <div class="col-12">
                                    <div class="mensaje_correo_mal_escrito alert alert-danger d-none" role="alert">
                                        <i class="fas fa-info-circle"></i> <strong>El correo que está escribiendo es incorrecto.</strong>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="form-group">
                                                <label for="tipo_cliente" class="col-form-label">Tipo de Cliente <span style="color:red;">(*)</span></label>
                                                <select class="tipo_cliente custom-select" name="tipo_cliente" id="tipo_cliente" style="width:100%;" required></select>
                                            </div>
                                        </div>
                                        <div class="col-sm columna_otro_tipo_cliente">
                                            <div class="form-group">
                                                <label for="otro_tipo_cliente" class="col-form-label">Otro Tipo Cliente <span style="color:red;">(*)</span></label>
                                                <input class="otro_tipo_cliente form-control" name="otro_tipo_cliente" id="otro_tipo_cliente">
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-group">
                                                <label for="nombre_cliente" class="col-form-label">Nombre del Cliente <span style="color:red;">(*)</span></label>
                                                <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente" required>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-group">
                                                <label for="nit_cliente" class="col-form-label">NIT <span style="color:red;">(*)</span></label>
                                                <input type="text" class="form-control" name="nit_cliente" id="nit_cliente" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="telefono_principal" class="col-form-label">Teléfono Principal <span style="color:red;">(*)</span></label>
                                                <input type="tel" class="form-control soloNumeros" name="telefono_principal" id="telefono_principal" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="otros_telefonos" class="col-form-label">Otro(s) Teléfóno(s)</label>
                                                <input type="text" class="form-control" name="otros_telefonos" id="otros_telefonos">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="email_principal" class="col-form-label">E-mail Principal <span style="color:red;">(*)</span></label>
                                                <input type="email" class="form-control" name="email_principal" id="email_principal" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="otros_emails" class="col-form-label">Otro(s) E-mail(s)</label>
                                                <input type="text" class="form-control" name="otros_emails" id="otros_emails">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="linea_atencion_principal" class="col-form-label">Línea de Atención principal <span style="color:red;">(*)</span></label>
                                                <input type="number" class="form-control soloNumeros" name="linea_atencion_principal" id="linea_atencion_principal" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="otras_lineas_atencion" class="col-form-label">Otra(s) Línea(s) de Atención</label>
                                                <input type="text" class="form-control" name="otras_lineas_atencion" id="otras_lineas_atencion">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="direccion" class="col-form-label">Dirección <span style="color:red;">(*)</span></label>
                                                <input type="text" class="form-control" name="direccion" id="direccion">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="departamento" class="col-form-label">Departamento <span style="color:red;">(*)</span></label>
                                                <select class="custom-select departamento" name="departamento" id="departamento" style="width:100%;" requierd></select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="ciudad" class="col-form-label">Ciudad<span style="color:red;">(*)</span></label>
                                                <select class="custom-select ciudad" name="ciudad" id="ciudad" style="width:100%;" disabled></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="" class="col-form-label">N° de Contrato <span style="color:red;">(*)</span></label>
                                                <input type="text" class="form-control" name="nro_contrato" id="nro_contrato" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="" class="col-form-label">Fecha Inicio Contrato <span style="color:red;">(*)</span></label>
                                                <input type="date" class="form-control" name="f_inicio_contrato" id="f_inicio_contrato" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="" class="col-form-label">Fecha Finalización Contato</label>
                                                <input type="date" class="form-control" name="f_finalizacion_contrato" id="f_finalizacion_contrato">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="alert alert-warning" role="alert">
                                                <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de agregar una fila nueva es necesario
                                                que diligencie en su totalidad los campos. Recuerde escribir los correos de manera correcta.
                                            </div>
                                            <div class="alert d-none" id="resultado" role="alert"></div>
                                            <div class="table-responsive">
                                                <table id="sucursales" class="table table-striped table-bordered" width="100%">
                                                    <thead>
                                                        <tr class="bg-info">
                                                            <th>Nombre Sucursal</th>
                                                            <th>Nombre Gerente de Sucursal</th>
                                                            <th>Teléfono Principal Sucursal</th>
                                                            <th>Otro(s) Teléfono(s) Sucursal</th>
                                                            <th>E-mail Principal Sucursal</th>
                                                            <th>Otro(s) E-mail(s) Sucursal</th>
                                                            <th>Línea de Atención Principal Sucursal</th>
                                                            <th>Otro(s) Línea(s) de Atención Sucursal</th>
                                                            <th>Dirección Sucursal</th>
                                                            <th>Departamento Sucursal</th>
                                                            <th>Ciudad Sucursal</th>
                                                            <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_sucursal_fila"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="borrar_tabla_sucursales"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- FIRMAS --}}
                        <div class="card-info">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Firmas Autorizadas</h5>
                            </div>
                            <div class="card-body">
                                {{-- FIRMAS CLIENTE --}}
                                <div class="card-info">
                                    <div class="card-header text-center" style="border: 1.5px solid black;">
                                        <h5>Firmas Cliente</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-warning" role="alert">
                                            <i class="fas fa-info-circle"></i> <strong>Importante: Para ver las firmas agregadas debe guardar la edición del cliente.</strong>
                                        </div>
                                        <a href="javascript:void(0);" id="btn_abrir_form_firma_cliente" class="text-dark text-md">
                                            <i class="fas fa-plus-circle text-info"></i> <strong>Nueva Firma</strong>
                                        </a>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="alert d-none" id="resultado_firma_cliente" role="alert"></div>
                                                <div class="table-responsive">
                                                    <table id="listado_firmas_cliente" class="table table-bordered" width="100%">
                                                        <thead>
                                                            <tr class="bg-info">
                                                                <th>Nombre del Firmante</th>
                                                                <th>Cargo del Firmante</th>
                                                                <th style="width: 20% !important;">Firma</th>
                                                                <th class="centrar">-</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="borrar_tabla_firmas_cliente"></tbody>
                                                    </table>
                                                    <div class="d-none" id="contenedor_html_firma_cliente"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-none" id="agregar_firma_cliente">
                                            <div class="alert d-none" id="resultado_nueva_firma_cliente" role="alert"></div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <input type="hidden" id="Id_firma_cliente_cliente">
                                                        <label for="" class="col-form-label">Nombre del Firmante <span style="color:red;">(*)</span></label>
                                                        <input type="text" class="form-control" name="nombre_del_firmante_cliente" id="nombre_del_firmante_cliente">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="" class="col-form-label">Cargo del Firmante <span style="color:red;">(*)</span></label>
                                                        <input type="text" class="form-control" name="cargo_del_firmante_cliente" id="cargo_del_firmante_cliente">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="" class="col-form-label">Firma <span style="color:red;">(*)</span></label>
                                                        <textarea id="firma_del_cliente" name="firma_del_cliente"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="cerrar_formulario_edicion_firma_cliente">
                                                <div class="col-2">
                                                    <div class="form-group">
                                                        <input type="button" id="guardar_nueva_firma_cliente" class="form-control btn btn-info" value="Guardar">
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="form-group">
                                                        <input type="button" id="no_editar_firma_cliente" class="form-control btn btn-info" value="Cerrar">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- FIRMAS PORVEEDOR --}}
                                <div class="card-info">
                                    <div class="card-header text-center" style="border: 1.5px solid black;">
                                        <h5>Firmas Proveedor</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-warning" role="alert">
                                            <i class="fas fa-info-circle"></i> <strong>Importante: Para ver las firmas agregadas debe guardar la edición del cliente.</strong>
                                        </div>
                                        <a href="javascript:void(0);" id="btn_abrir_form_firma_proveedor" class="text-dark text-md">
                                            <i class="fas fa-plus-circle text-info"></i> <strong>Nueva Firma</strong>
                                        </a>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="alert d-none" id="resultado_firma_proveedor" role="alert"></div>
                                                <div class="table-responsive">
                                                    <table id="listado_firmas_proveedor" class="table table-bordered" width="100%">
                                                        <thead>
                                                            <tr class="bg-info">
                                                                <th>Nombre del Firmante</th>
                                                                <th>Cargo del Firmante</th>
                                                                <th style="width: 20% !important;">Firma</th>
                                                                <th class="centrar">-</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="borrar_tabla_firmas_proveedor"></tbody>
                                                    </table>
                                                    <div class="d-none" id="contenedor_html_firma_proveedor"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-none" id="agregar_firma_proveedor">
                                            <div class="alert d-none" id="resultado_nueva_firma_proveedor" role="alert"></div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <input type="hidden" id="Id_firma_cliente_proveedor">
                                                        <label for="" class="col-form-label">Nombre del Firmante <span style="color:red;">(*)</span></label>
                                                        <input type="text" class="form-control" name="nombre_del_firmante_proveedor" id="nombre_del_firmante_proveedor">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="" class="col-form-label">Cargo del Firmante <span style="color:red;">(*)</span></label>
                                                        <input type="text" class="form-control" name="cargo_del_firmante_proveedor" id="cargo_del_firmante_proveedor">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="" class="col-form-label">Firma <span style="color:red;">(*)</span></label>
                                                        <textarea id="firma_del_proveedor" name="firma_del_proveedor"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="cerrar_formulario_edicion_firma_proveedor">
                                                <div class="col-2">
                                                    <div class="form-group">
                                                        <input type="button" id="guardar_nueva_firma_proveedor" class="form-control btn btn-info" value="Guardar">
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="form-group">
                                                        <input type="button" id="no_editar_firma_proveedor" class="form-control btn btn-info" value="Cerrar">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- SERVICIOS CONTRATADOS Y ANS --}}
                        <div class="card-info">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Servicios Contratados</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="nro_consecutivo_dictamen">N° Consecutivo Dictamen <span style="color:red;">(*)</span></label>
                                            <input type="number" class="form-control" name="nro_consecutivo_dictamen" id="nro_consecutivo_dictamen" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" width="100%">
                                        <thead>
                                            <tr class="bg-info centrar">
                                                <th>Proceso</th>
                                                <th colspan="2">Servicio</th>
                                                <th style="width: 20% !important;">Valor Tarifa</th>
                                                {{-- <th style="width: 20% !important;">N° Consecutivo Dictamen</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td rowspan="3" class="justify-content-center align-items-center" style="background:white;">Origen ATEL</td>
                                                <td>Determinación de Origen (DTO)</td>
                                                <td class="centrar"><input type="checkbox" name="checkbox_servicio_dto" id="checkbox_servicio_dto" class="scales" value="1"></td>
                                                <td><input type="text" class="form-control d-none soloContabilidad" name="valor_tarifa_servicio_dto" id="valor_tarifa_servicio_dto"></td>
                                                {{-- <td><input type="number" class="form-control d-none" name="nro_consecutivo_servicio_dto" id="nro_consecutivo_servicio_dto" ></td> --}}
                                            </tr>
                                            <tr>
                                                <td>Adición DX</td>
                                                <td class="centrar"><input type="checkbox" name="checkbox_servicio_adicion_dx" id="checkbox_servicio_adicion_dx" class="scales" value="2"></td>
                                                <td><input type="text" class="form-control d-none soloContabilidad" name="valor_tarifa_servicio_adicion_dx" id="valor_tarifa_servicio_adicion_dx"></td>
                                                {{-- <td><input type="number" class="form-control d-none" name="nro_consecutivo_servicio_adicion_dx" id="nro_consecutivo_servicio_adicion_dx" ></td> --}}
                                            </tr>
                                            <tr>
                                                <td>Pronunciamientos</td>
                                                <td class="centrar"><input type="checkbox" name="checkbox_servicio_pronunciamiento" id="checkbox_servicio_pronunciamiento" class="scales" value="3"></td>
                                                <td><input type="text" class="form-control d-none soloContabilidad" name="valor_tarifa_servicio_pronunciamiento" id="valor_tarifa_servicio_pronunciamiento"></td>
                                                {{-- <td><input type="number" class="form-control d-none" name="nro_consecutivo_servicio_pronunciamiento" id="nro_consecutivo_servicio_pronunciamiento" ></td> --}}
                                            </tr>
                                            <tr>
                                                <td rowspan="4">Calificación PCL</td>
                                                <td>Calificación Técnica</td>
                                                <td class="centrar"><input type="checkbox" name="checkbox_servicio_calificacion_tecnica" id="checkbox_servicio_calificacion_tecnica" class="scales" value="6"></td>
                                                <td><input type="text" class="form-control d-none soloContabilidad" name="valor_tarifa_servicio_calificacion_tecnica" id="valor_tarifa_servicio_calificacion_tecnica"></td>
                                                {{-- <td><input type="number" class="form-control d-none" name="nro_consecutivo_servicio_calificacion_tecnica" id="nro_consecutivo_servicio_calificacion_tecnica" ></td> --}}
                                            </tr>
                                            <tr>
                                                <td>Recalificación</td>
                                                <td class="centrar"><input type="checkbox" name="checkbox_servicio_recalificacion" id="checkbox_servicio_recalificacion" class="scales" value="7"></td>
                                                <td><input type="text" class="form-control d-none soloContabilidad" name="valor_tarifa_servicio_recalificacion" id="valor_tarifa_servicio_recalificacion"></td>
                                                {{-- <td><input type="number" class="form-control d-none" name="nro_consecutivo_servicio_recalificacion" id="nro_consecutivo_servicio_recalificacion" ></td> --}}
                                            </tr>
                                            <tr>
                                                <td>Revisión Pensión</td>
                                                <td class="centrar"><input type="checkbox" name="checkbox_servicio_revision_pension" id="checkbox_servicio_revision_pension" class="scales" value="8"></td>
                                                <td><input type="text" class="form-control d-none soloContabilidad" name="valor_tarifa_servicio_revision_pension" id="valor_tarifa_servicio_revision_pension"></td>
                                                {{-- <td><input type="number" class="form-control d-none" name="nro_consecutivo_servicio_revision_pension" id="nro_consecutivo_servicio_revision_pension" ></td> --}}
                                            </tr>
                                            <tr>
                                                <td>Pronunciamientos</td>
                                                <td class="centrar"><input type="checkbox" name="checkbox_servicio_pronunciamiento_pcl" id="checkbox_servicio_pronunciamiento_pcl" class="scales" value="9"></td>
                                                <td><input type="text" class="form-control d-none soloContabilidad" name="valor_tarifa_servicio_pronunciamiento_pcl" id="valor_tarifa_servicio_pronunciamiento_pcl"></td>
                                                {{-- <td><input type="number" class="form-control d-none" name="nro_consecutivo_servicio_pronunciamiento_pcl" id="nro_consecutivo_servicio_pronunciamiento_pcl" ></td> --}}
                                            </tr>
                                            <tr>
                                                <td rowspan="2" style="background: white;">Juntas</td>
                                                <td>Controversia Origen</td>
                                                <td class="centrar"><input type="checkbox" name="checkbox_servicio_controversia_origen" id="checkbox_servicio_controversia_origen" class="scales" value="12"></td>
                                                <td><input type="text" class="form-control d-none soloContabilidad" name="valor_tarifa_servicio_controversia_origen" id="valor_tarifa_servicio_controversia_origen"></td>
                                                {{-- <td><input type="number" class="form-control d-none" name="nro_consecutivo_servicio_controversia_origen" id="nro_consecutivo_servicio_controversia_origen" ></td> --}}
                                            </tr>
                                            <tr>
                                                <td>Controversia Pcl</td>
                                                <td class="centrar"><input type="checkbox" name="checkbox_servicio_controversia_pcl" id="checkbox_servicio_controversia_pcl" class="scales" value="13"></td>
                                                <td><input type="text" class="form-control d-none soloContabilidad" name="valor_tarifa_servicio_controversia_pcl" id="valor_tarifa_servicio_controversia_pcl"></td>
                                                {{-- <td><input type="number" class="form-control d-none" name="nro_consecutivo_servicio_controversia_pcl" id="nro_consecutivo_servicio_controversia_pcl"></td> --}}
                                            </tr>
                                            {{-- <tr>
                                                <td rowspan="4" style="background-color: rgba(0,0,0,.05);">Otros</td>
                                                <td>PQRD</td>
                                                <td class="centrar"><input type="checkbox" name="checkbox_servicio_pqrd" id="checkbox_servicio_pqrd" class="scales"></td>
                                                <td><input type="text" class="form-control d-none soloContabilidad" name="valor_tarifa_servicio_pqrd" id="valor_tarifa_servicio_pqrd"></td>
                                                <td><input type="number" class="form-control d-none" name="nro_consecutivo_servicio_pqrd" id="nro_consecutivo_servicio_pqrd" ></td>
                                            </tr>
                                            <tr>
                                                <td>Tutelas</td>
                                                <td class="centrar"><input type="checkbox" name="checkbox_servicio_tutelas" id="checkbox_servicio_tutelas" class="scales"></td>
                                                <td><input type="text" class="form-control d-none soloContabilidad" name="valor_tarifa_servicio_tutelas" id="valor_tarifa_servicio_tu|telas"></td>
                                                <td><input type="number" class="form-control d-none" name="nro_consecutivo_servicio_tutelas" id="nro_consecutivo_servicio_tutelas" ></td>
                                            </tr>
                                            <tr>
                                                <td>Gestión Integral del Siniestro (GIS)</td>
                                                <td class="centrar"><input type="checkbox" name="checkbox_servicio_gis" id="checkbox_servicio_gis" class="scales"></td>
                                                <td><input type="text" class="form-control d-none soloContabilidad" name="valor_tarifa_servicio_gis" id="valor_tarifa_servicio_gis"></td>
                                                <td><input type="number" class="form-control d-none" name="nro_consecutivo_servicio_gis" id="nro_consecutivo_servicio_gis" ></td>
                                            </tr>
                                            <tr>
                                                <td>Auditorías</td>
                                                <td class="centrar"><input type="checkbox" name="checkbox_servicio_auditorias" id="checkbox_servicio_auditorias" class="scales"></td>
                                                <td><input type="text" class="form-control d-none soloContabilidad" name="valor_tarifa_servicio_auditorias" id="valor_tarifa_servicio_auditorias"></td>
                                                <td><input type="number" class="form-control d-none" name="nro_consecutivo_servicio_auditorias" id="nro_consecutivo_servicio_auditorias" ></td>
                                            </tr> --}}
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-warning" role="alert">
                                            <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de agregar una fila nueva es necesario
                                            que diligencie en su totalidad los campos.
                                        </div>
                                        <div class="alert d-none" id="resultado_ans" role="alert"></div>
                                        <div class="table-responsive">
                                            <table id="ans" class="table table-striped table-bordered" width="100%">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>Nombre ANS</th>
                                                        <th>Descripción</th>
                                                        <th>Valor</th>
                                                        <th>Unidad</th>
                                                        <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_ans_fila"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="borrar_tabla_ans"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- COMUNICADOS --}}
                        <div class="card-info">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Comunicados</h5>
                            </div>
                            <div class="card-body">
                                <div class="mensaje_extension_logo alert alert-danger d-none" role="alert">
                                    <i class="fas fa-info-circle"></i> <strong>Extensión Incorrecta: Recuerde que la extensión debe ser png o jpg.</strong>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="logo_cliente" class="col-form-label">Logo</label>
                                            <input type="file" class="logo_cliente form-control" name="logo_cliente" id="logo_cliente" accept=".png, .jpg">
                                            <input type="hidden" id="img_codificada">
                                            <input type="hidden" id="nombre_ext_imagen">
                                            <input type="hidden" id="httpohttps" value="<?php if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
                                                echo "https://";
                                            } else {
                                                echo "http://";
                                            }?>">
                                            <input type="hidden" id="host" value="<?php echo $_SERVER['HTTP_HOST'] ;?>">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="col-form-label">Previsualización Logo</label>
                                        <input type="hidden" id="nombre_logo_bd">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <div id="imagePreview" class="d-flex justify-content-center align-items-center" style="border: 1.5px solid black; width: 50%; height: 120px; overflow: hidden;">
                                                <img id="previewImage" src="#" alt="Previusalización Imagen" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="footer_cliente" class="col-form-label">Pie de página</label>
                                            <div class="alert alert-warning" role="alert">
                                                <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Escriba la información en los campos disponibles 
                                                ya que el sistema mantendrá esta estructura (tabla) para la creación de los documentos.
                                            </div>
                                            <table style="width: 100%;font-family: sf-pro-display-black, sans-serif;font-size: 12px; border: 2px solid black; border-collapse: collapse;">
                                                <tbody>
                                                    <tr>
                                                        <td colspan="2"  style="border: 2px solid black; border-collapse: collapse; padding: 6px;">
                                                            <input type="text" class="form-control" id="footer_dato_1" placeholder="Escriba texto aquí">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border: 2px solid black; border-collapse: collapse; padding: 6px;">
                                                            <input type="text" class="form-control" id="footer_dato_2" placeholder="Escriba texto aquí">
                                                        </td>
                                                        <td style="border: 2px solid black; border-collapse: collapse; padding: 6px;">
                                                            <input type="text" class="form-control" id="footer_dato_3" placeholder="Escriba texto aquí">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="border: 2px solid black; border-collapse: collapse; padding: 6px;">
                                                            <input type="text" class="form-control" id="footer_dato_4" placeholder="Escriba texto aquí">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="border: 2px solid black; border-collapse: collapse; padding: 6px;">
                                                            <input type="text" class="form-control" id="footer_dato_5" placeholder="Escriba texto aquí">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-info">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="status_cliente" class="col-form-label">Status <span style="color:red;">(*)</span></label>
                                            <select class="custom-select status_cliente" name="status_cliente" id="status_cliente" style="width:100%;" required>
                                                {{-- <option></option>
                                                <option value="Activo">Activo</option>
                                                <option value="Inactivo">Inactivo</option> --}}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="codigo_cliente" class="col-form-label">Código Cliente <span style="color:red;">(*)</span></label>
                                            <input type="text" class="form-control" name="codigo_cliente" id="codigo_cliente" required>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="fecha_creacion_cliente" class="col-form-label">Fecha Creación</label>
                                            <input type="date" class="form-control" name="fecha_creacion" id="fecha_creacion" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="">
                    <div class="row" id="contenedor_btn_actualizar_cliente">
                        <div class="col-6">
                            <div class="form-group">
                                <input type="submit" class="btn btn-info" id="GuardarCliente" name="GuardarCliente" value="Actualizar">    
                            </div>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-danger" style="float:right;" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                    <div class="row d-none" id="mostrar_barra_actualizar_cliente">
                        <div class="col-12">
                            <div class="text-center">                                
                                <button class="btn btn-info" type="button" disabled>
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Actualizando Cliente por favor espere...
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row d-none" id="mostrar_mensaje_actualizo_cliente">
                        <div  class="col-12">
                            <div class="form-group">
                                <div class="mensaje_actualizo_cliente alert" role="alert"></div>
                            </div>
                        </div>
                    </div>
                </div>


                <x-slot name="footerSlot">
                </x-slot>
            </form>
        </div>
    </div>
</x-adminlte-modal>