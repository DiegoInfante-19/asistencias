<!-- ========================================== -->
<!-- MODAL: NUEVA COHORTE Y PERÍODO UNIFICADO    -->
<!-- ========================================== -->
<div class="modal fade" id="modalCohorte" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-white border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="bi bi-folder-plus text-primary me-2"></i>Nueva Cohorte y Período
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-white p-4">
                <form action="{{ route('cohortes.store') }}" method="POST" id="createCohorteForm">
                    @csrf
                    <input type="hidden" name="numero_cohorte" id="numero_cohorte_final">

                    <!-- SECCIÓN 1: NÚMERO DE COHORTE -->
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Número de la Cohorte (Ej: 3) <span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="number" id="input_numero_arabe" class="form-control bg-light border-secondary-subtle shadow-sm text-center fw-bold fs-5" style="width: 110px;" min="1" max="100" required placeholder="Ej: 3" autocomplete="off">
                            <div class="flex-grow-1 p-2 bg-light border border-secondary-subtle rounded text-center">
                                <span class="small text-muted d-block" style="font-size: 0.75rem;">Se guardará como:</span>
                                <span id="preview_romano" class="fw-bold text-primary fs-6">--- COHORTE</span>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 2: PERÍODO ACADÉMICO ASOCIADO -->
                    <div class="border border-secondary-subtle rounded p-3 bg-light mb-3">
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
            <div class="modal-footer bg-white border-top py-3">
                <button type="button" class="btn btn-outline-secondary px-3" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="submit" form="createCohorteForm" class="btn btn-primary fw-bold px-4 shadow-sm">
                    <i class="bi bi-save-fill me-1"></i> Guardar Cohorte
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
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-white border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="bi bi-pencil-square text-warning me-2"></i>Editar Cohorte y Período
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-white p-4">
                <form id="formEditarCohorte" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Número de Cohorte <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-light border-secondary-subtle shadow-sm" name="numero_cohorte" id="edit_numero_cohorte" required autocomplete="off" placeholder="Ej: III COHORTE">
                    </div>

                    <div class="border border-secondary-subtle rounded p-3 bg-light mb-3">
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
            <div class="modal-footer bg-white border-top py-3">
                <button type="button" class="btn btn-outline-secondary px-3" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="submit" form="formEditarCohorte" class="btn btn-warning fw-bold text-dark px-4 shadow-sm">
                    <i class="bi bi-arrow-repeat me-1"></i> Actualizar
                </button>
            </div>
        </div>
    </div>
</div>