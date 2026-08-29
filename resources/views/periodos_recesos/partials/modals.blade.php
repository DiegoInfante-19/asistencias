<!-- MODAL: CREAR PERIODO -->
<div class="modal fade" id="createPeriodoModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow">
            <div class="modal-header bg-white border-bottom" style="font-weight: 500;">
                <h1 class="modal-title fs-5 text-dark">
                    Registrar Periodo o Evento
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Cuerpo blanco con inputs de fondo gris claro -->
            <div class="modal-body bg-white p-4">
                <form id="createPeriodoForm" method="POST" action="{{ route('periodos_recesos.store') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-8 form-group">
                            <label class="form-label fw-bold small text-muted">Nombre del Evento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-light border-secondary-subtle shadow-sm" name="nombre_periodo_receso" required autocomplete="off">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label fw-bold small text-muted">Tipo de Evento <span class="text-danger">*</span></label>
                            <select class="form-select bg-light border-secondary-subtle shadow-sm" name="nivel_periodo_receso" required>
                                <option value="" selected disabled>Seleccione...</option>
                                <option value="Académico">Académico</option>
                                <option value="Administrativo">Administrativo</option>
                                <option value="Institucional">Institucional</option>
                                <option value="Feriado Nacional">Feriado Nacional</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 form-group">
                            <label class="form-label fw-bold small text-muted">Fecha de Inicio <span class="text-danger">*</span></label>
                            <input type="date" class="form-control bg-light border-secondary-subtle shadow-sm" name="fecha_inicio_periodo_receso" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label fw-bold small text-muted">Fecha de Fin <span class="text-danger">*</span></label>
                            <input type="date" class="form-control bg-light border-secondary-subtle shadow-sm" name="fecha_fin_periodo_receso" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Descripción (Opcional)</label>
                        <textarea class="form-control bg-light border-secondary-subtle shadow-sm" name="descripcion_periodo_receso" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Tipo de Proyección Anual <span class="text-danger">*</span></label>
                        <select class="form-select bg-light border-secondary-subtle shadow-sm" name="tipo_receso" id="tipo_receso" required>
                            <option value="" disabled selected>Seleccione...</option>
                            <option value="1">Fijo (Se repite en la misma fecha ej: Navidad)</option>
                            <option value="2">Móvil (Depende del calendario lunar ej: Semana Santa)</option>
                            <option value="3">Único (No se proyecta al próximo año ej: Elecciones)</option>
                        </select>
                    </div>

                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" role="switch" name="suspension_actividades" id="createSuspension" value="1">
                        <label class="form-check-label fw-bold small text-dark" for="createSuspension">
                            ¿Este evento suspende las actividades académicas/laborales?
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="submit" form="createPeriodoForm" class="btn btn-primary fw-bold">
                    <i class="bi bi-save-fill me-1"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: MODIFICAR PERIODO -->
<div class="modal fade" id="UpdatePeriodoModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow">
            <div class="modal-header bg-white border-bottom" style="font-weight: 500;">
                <h1 class="modal-title fs-5 text-dark">
                    Modificar Periodo o Evento
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-white p-4">
                <form id="UpdatePeriodoForm" method="POST" action="">
                    @csrf @method('PUT')
                    <div class="row mb-3">
                        <div class="col-md-8 form-group">
                            <label class="form-label fw-bold small text-muted">Nombre del Evento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-light border-secondary-subtle shadow-sm" name="nombre_periodo_receso" id="edit-nombre-periodo" required autocomplete="off">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label fw-bold small text-muted">Tipo de Evento <span class="text-danger">*</span></label>
                            <select class="form-select bg-light border-secondary-subtle shadow-sm" name="nivel_periodo_receso" id="edit-nivel-periodo" required>
                                <option value="" disabled>Seleccione...</option>
                                <option value="Académico">Académico</option>
                                <option value="Administrativo">Administrativo</option>
                                <option value="Institucional">Institucional</option>
                                <option value="Feriado Nacional">Feriado Nacional</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 form-group">
                            <label class="form-label fw-bold small text-muted">Fecha de Inicio <span class="text-danger">*</span></label>
                            <input type="date" class="form-control bg-light border-secondary-subtle shadow-sm" name="fecha_inicio_periodo_receso" id="edit-inicio-periodo" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label fw-bold small text-muted">Fecha de Fin <span class="text-danger">*</span></label>
                            <input type="date" class="form-control bg-light border-secondary-subtle shadow-sm" name="fecha_fin_periodo_receso" id="edit-fin-periodo" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Descripción (Opcional)</label>
                        <textarea class="form-control bg-light border-secondary-subtle shadow-sm" name="descripcion_periodo_receso" id="edit-descripcion-periodo" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Tipo de Proyección Anual <span class="text-danger">*</span></label>
                        <select class="form-select bg-light border-secondary-subtle shadow-sm" name="tipo_receso" id="edit-tipo-receso" required>
                            <option value="" disabled selected>Seleccione...</option>
                            <option value="1">Fijo (Se repite en la misma fecha ej: Navidad)</option>
                            <option value="2">Móvil (Depende del calendario lunar ej: Semana Santa)</option>
                            <option value="3">Único (No se proyecta al próximo año ej: Elecciones)</option>
                        </select>
                    </div>

                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" role="switch" name="suspension_actividades" id="edit-suspension-periodo" value="1">
                        <label class="form-check-label fw-bold small text-dark" for="edit-suspension-periodo">
                            ¿Este evento suspende las actividades académicas/laborales?
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="submit" form="UpdatePeriodoForm" class="btn btn-warning fw-bold text-dark">
                    <i class="bi bi-arrow-repeat me-1"></i> Actualizar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: VER DETALLES (SHOW) -->
<div class="modal fade" id="showPeriodoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow">
            <div class="modal-header bg-white border-bottom" style="font-weight: 500;">
                <h1 class="modal-title fs-5 text-dark">
                    Detalles del Evento
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-white p-4">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <p class="mb-1 fw-bold small text-muted">Nombre del Evento</p>
                        <h5 id="show-nombre-periodo" class="text-dark mb-0 fw-bold"></h5>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1 fw-bold small text-muted">Tipo de Evento</p>
                        <span id="show-nivel-periodo" class="badge bg-secondary px-3 py-2 fs-6"></span>
                    </div>
                </div>

                <div class="row mb-4 bg-light p-3 rounded border">
                    <div class="col-md-6 border-end">
                        <p class="mb-1 fw-bold small text-muted"><i class="bi bi-calendar-check me-1"></i> Fecha de Inicio</p>
                        <p id="show-inicio-periodo" class="text-dark fw-semibold mb-0 fs-5"></p>
                    </div>
                    <div class="col-md-6 ps-md-4">
                        <p class="mb-1 fw-bold small text-muted"><i class="bi bi-calendar-x me-1"></i> Fecha de Fin</p>
                        <p id="show-fin-periodo" class="text-dark fw-semibold mb-0 fs-5"></p>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="mb-1 fw-bold small text-muted">Descripción</p>
                    <div id="show-descripcion-periodo" class="p-3 border rounded bg-light text-dark" style="min-height: 60px;"></div>
                </div>

                <div class="d-flex align-items-center p-3 rounded bg-light border">
                    <p class="mb-0 fw-bold me-3 text-muted small">¿Suspende actividades académicas/laborales?</p>
                    <div id="show-suspension-periodo"></div>
                </div>
            </div>
            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>