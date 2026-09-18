<!-- ========================================== -->
<!-- MODAL: NUEVA COHORTE Y PERÍODO UNIFICADO   -->
<!-- ========================================== -->
<div class="modal fade" id="modalCohorte" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header bg-white border-bottom">
                <h1 class="modal-title fs-5 fw-bold text-dark">
                    <i class="bi bi-folder-plus text-primary me-2"></i>Nueva Cohorte y Período
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-white p-4">
                <form action="{{ route('cohortes.store') }}" method="POST" id="createCohorteForm">
                    @csrf
                    <!-- Campo oculto que enviará el formato romano "V COHORTE" -->
                    <input type="hidden" name="numero_cohorte" id="numero_cohorte_final">

                    <!-- SECCIÓN 1: NÚMERO DE COHORTE -->
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Número de la Cohorte (Ej: 3) <span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="number" id="input_numero_arabe" class="form-control bg-light border-secondary-subtle shadow-sm text-center fw-bold fs-5" style="width: 110px;" min="1" max="100" required placeholder="Ej: 3" autocomplete="off">
                            <div class="flex-grow-1 p-2 bg-light border rounded text-center">
                                <span class="small text-muted d-block" style="font-size: 0.75rem;">Se guardará como:</span>
                                <span id="preview_romano" class="fw-bold text-primary fs-6">--- COHORTE</span>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 2: PERÍODO ACADÉMICO ASOCIADO -->
                    <div class="border rounded p-3 bg-light mb-3">
                        <label class="form-label fw-bold small text-primary mb-2">
                            <i class="bi bi-calendar-range me-1"></i> Período Académico Asociado
                        </label>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label small text-muted fw-semibold">Fecha Inicio <span class="text-danger">*</span></label>
                                <input type="date" class="form-control bg-white border-secondary-subtle shadow-sm" name="fecha_inicio" id="input_fecha_inicio" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted fw-semibold">Fecha Fin <span class="text-danger">*</span></label>
                                <input type="date" class="form-control bg-white border-secondary-subtle shadow-sm" name="fecha_fin" id="input_fecha_fin" required>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 3: DESCRIPCIÓN Y ESTATUS -->
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Descripción</label>
                        <input type="text" class="form-control bg-light border-secondary-subtle shadow-sm" name="descripcion_cohorte" placeholder="Opcional: Descripción breve de la cohorte" autocomplete="off">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Estatus <span class="text-danger">*</span></label>
                        <select class="form-select bg-light border-secondary-subtle shadow-sm" name="estatus_cohorte" required>
                            <option value="Activo" selected>Activo</option>
                            <option value="Finalizada">Finalizada</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="submit" form="createCohorteForm" class="btn btn-primary fw-bold">
                    <i class="bi bi-save-fill me-1"></i> Guardar Cohorte y Período
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: ACTUALIZAR COHORTE Y PERÍODO        -->
<!-- ========================================== -->
<div class="modal fade" id="modalEditarCohorte" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            
            <!-- HEADER BLANCO Y SERENO -->
            <div class="modal-header bg-white border-bottom">
                <h1 class="modal-title fs-5 fw-bold text-dark">
                    <i class="bi bi-pencil-square text-warning me-2"></i>Editar Cohorte y Período
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body bg-white p-4">
                <form id="formEditarCohorte" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- SECCIÓN 1: NÚMERO DE COHORTE -->
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Número de Cohorte <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-light border-secondary-subtle shadow-sm" name="numero_cohorte" id="edit_numero_cohorte" required autocomplete="off" placeholder="Ej: III COHORTE">
                    </div>

                    <!-- SECCIÓN 2: PERÍODO ACADÉMICO ENLAZADO (FECHAS EDITABLES) -->
                    <div class="border rounded p-3 bg-light mb-3">
                        <label class="form-label fw-bold small text-primary mb-2">
                            <i class="bi bi-calendar-range me-1"></i> Período Académico Enlazado
                        </label>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label small text-muted fw-semibold">Fecha Inicio <span class="text-danger">*</span></label>
                                <input type="date" class="form-control bg-white border-secondary-subtle shadow-sm" name="fecha_inicio" id="edit_fecha_inicio" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted fw-semibold">Fecha Fin <span class="text-danger">*</span></label>
                                <input type="date" class="form-control bg-white border-secondary-subtle shadow-sm" name="fecha_fin" id="edit_fecha_fin" required>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 3: DESCRIPCIÓN Y ESTATUS -->
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Descripción</label>
                        <input type="text" class="form-control bg-light border-secondary-subtle shadow-sm" name="descripcion_cohorte" id="edit_descripcion_cohorte" autocomplete="off" placeholder="Opcional: Descripción breve">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Estatus de la Cohorte <span class="text-danger">*</span></label>
                        <select class="form-select bg-light border-secondary-subtle shadow-sm" name="estatus_cohorte" id="edit_estatus_cohorte" required>
                            <option value="Activo">Activo</option>
                            <option value="Finalizada">Finalizada</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- FOOTER LIMPIO -->
            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="submit" form="formEditarCohorte" class="btn btn-warning fw-bold text-dark">
                    <i class="bi bi-arrow-repeat me-1"></i> Actualizar Cohorte y Período
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: NUEVA SECCIÓN                       -->
<!-- ========================================== -->
<div class="modal fade" id="modalSeccion" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header bg-white border-bottom">
                <h1 class="modal-title fs-5 fw-bold text-dark">
                    <i class="bi bi-layers text-success me-2"></i>Nueva Sección Académica
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-white p-4">
                <form action="{{ route('secciones.store') }}" method="POST" id="createSeccionForm">
                    @csrf
                    <input type="hidden" name="id_periodo" id="id_periodo_hidden" value="">
                    
                    <!-- SELECT PNF -->
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">PNF (Programa Nacional de Formación) <span class="text-danger">*</span></label>
                        <select class="form-select bg-light border-secondary-subtle shadow-sm" name="id_pnf" id="select_pnf_seccion" required>
                            <option value="" selected disabled data-nombre="">Seleccione un PNF...</option>
                            @foreach($pnfs as $pnf)
                                <option value="{{ $pnf->id_pnf }}" data-nombre="{{ $pnf->nombre_pnf }}">{{ $pnf->nombre_pnf }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- FILA GENERADORA DE NOMBRE -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-bold small text-muted">Número <span class="text-danger">*</span></label>
                                <input type="number" class="form-control bg-light border-secondary-subtle shadow-sm" id="numero_correlativo_seccion" min="1" placeholder="Ej: 1" required>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="form-label fw-bold small text-muted">Nombre Generado</label>
                                <input type="text" class="form-control bg-white border-secondary-subtle shadow-sm fw-bold text-primary" name="nombre_seccion" id="nombre_seccion_final" readonly placeholder="Seleccione PNF y Número" tabindex="-1">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Estatus Inicial <span class="text-danger">*</span></label>
                        <select class="form-select bg-light border-secondary-subtle shadow-sm" name="estatus_seccion" required>
                            <option value="Activa" selected>Activa</option>
                            <option value="Inactiva">Inactiva</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="submit" form="createSeccionForm" class="btn btn-success fw-bold">
                    <i class="bi bi-save-fill me-1"></i> Guardar Sección
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: ACTUALIZAR SECCIÓN (PNF PROTEGIDO)  -->
<!-- ========================================== -->
<div class="modal fade" id="modalEditarSeccion" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            
            <div class="modal-header bg-white border-bottom">
                <h1 class="modal-title fs-5 fw-bold text-dark">
                    <i class="bi bi-pencil-square text-warning me-2"></i>Editar Sección
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body bg-white p-4">
                <form id="formEditarSeccion" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- PNF FIJO / BLOQUEADO PARA PROTEGER LA INTEGRIDAD DE LOS ESTUDIANTES -->
                    <input type="hidden" name="id_pnf" id="edit_id_pnf">
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">PNF Asignado</label>
                        <input type="text" class="form-control bg-light border-secondary-subtle shadow-sm text-dark fw-semibold" id="edit_pnf_nombre_display" readonly tabindex="-1">
                        <small class="text-muted" style="font-size: 0.75rem;">El PNF no se puede modificar para proteger la integridad de los estudiantes inscritos.</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Nombre de la Sección <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-white border-secondary-subtle shadow-sm fw-bold" name="nombre_seccion" id="edit_nombre_seccion" required autocomplete="off">
                        <small class="text-muted" style="font-size: 0.75rem;">Puedes ajustar el nombre manualmente si es necesario.</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Estatus de la Sección <span class="text-danger">*</span></label>
                        <select class="form-select bg-light border-secondary-subtle shadow-sm" name="estatus_seccion" id="edit_estatus_seccion" required>
                            <option value="Activa">Activa</option>
                            <option value="Inactiva">Inactiva</option>
                        </select>
                    </div>
                </form>
            </div>

            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="submit" form="formEditarSeccion" class="btn btn-warning fw-bold text-dark">
                    <i class="bi bi-arrow-repeat me-1"></i> Actualizar Sección
                </button>
            </div>
        </div>
    </div>
</div>