<div class="modal fade" id="createPeriodoModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold">Registrar Periodo o Evento</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="createPeriodoForm" method="POST" action="{{ route('periodos_recesos.store') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-8 form-group">
                            <label class="form-label fw-bold small text-muted">Nombre del Evento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nombre_periodo_receso" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label fw-bold small text-muted">Tipo de Evento <span class="text-danger">*</span></label>
                            <select class="form-select" name="nivel_periodo_receso" required>
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
                            <input type="date" class="form-control" name="fecha_inicio_periodo_receso" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label fw-bold small text-muted">Fecha de Fin <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="fecha_fin_periodo_receso" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Descripción (Opcional)</label>
                        <textarea class="form-control" name="descripcion_periodo_receso" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tipo de Proyección Anual <span class="text-danger">*</span></label>
                        <select class="form-select border-secondary" name="tipo_receso" id="tipo_receso" required>
                            <option value="" disabled selected>Seleccione...</option>
                            <option value="1">Fijo (Se repite en la misma fecha ej: Navidad)</option>
                            <option value="2">Móvil (Depende del calendario lunar ej: Semana Santa)</option>
                            <option value="3">Único (No se proyecta al próximo año ej: Elecciones)</option>
                        </select>
                    </div>

                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" role="switch" name="suspension_actividades" id="createSuspension" value="1">
                        <label class="form-check-label fw-bold" for="createSuspension">
                            ¿Este evento suspende las actividades académicas/laborales?
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="createPeriodoForm" class="btn btn-primary fw-bold">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="UpdatePeriodoModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold">Modificar Periodo o Evento</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="UpdatePeriodoForm" method="POST" action="">
                    @csrf @method('PUT')
                    <div class="row mb-3">
                        <div class="col-md-8 form-group">
                            <label class="form-label fw-bold small text-muted">Nombre del Evento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nombre_periodo_receso" id="edit-nombre-periodo" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label fw-bold small text-muted">Tipo de Evento <span class="text-danger">*</span></label>
                            <select class="form-select" name="nivel_periodo_receso" id="edit-nivel-periodo" required>
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
                            <input type="date" class="form-control" name="fecha_inicio_periodo_receso" id="edit-inicio-periodo" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label fw-bold small text-muted">Fecha de Fin <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="fecha_fin_periodo_receso" id="edit-fin-periodo" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Descripción (Opcional)</label>
                        <textarea class="form-control" name="descripcion_periodo_receso" id="edit-descripcion-periodo" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tipo de Proyección Anual <span class="text-danger">*</span></label>
                        <select class="form-select border-secondary" name="tipo_receso" id="tipo_receso" required>
                            <option value="" disabled selected>Seleccione...</option>
                            <option value="1">Fijo (Se repite en la misma fecha ej: Navidad)</option>
                            <option value="2">Móvil (Depende del calendario lunar ej: Semana Santa)</option>
                            <option value="3">Único (No se proyecta al próximo año ej: Elecciones)</option>
                        </select>
                    </div>

                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" role="switch" name="suspension_actividades" id="edit-suspension-periodo" value="1">
                        <label class="form-check-label fw-bold" for="edit-suspension-periodo">
                            ¿Este evento suspende las actividades académicas/laborales?
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="UpdatePeriodoForm" class="btn btn-warning fw-bold text-dark">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="showPeriodoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h1 class="modal-title fs-5 fw-bold text-primary">
                    <i class="bi bi-info-circle me-2"></i> Detalles del Evento
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <p class="mb-1 fw-bold small text-muted">Nombre del Evento</p>
                        <h5 id="show-nombre-periodo" class="text-dark mb-0"></h5>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1 fw-bold small text-muted">Tipo de Evento</p>
                        <span id="show-nivel-periodo" class="badge bg-secondary px-3 py-2 fs-6"></span>
                    </div>
                </div>

                <div class="row mb-4 bg-light p-3 rounded">
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
                    <div id="show-descripcion-periodo" class="p-3 border rounded bg-white text-dark" style="min-height: 60px;"></div>
                </div>

                <div class="d-flex align-items-center p-3 rounded" style="background-color: #f8f9fa;">
                    <p class="mb-0 fw-bold me-3 text-muted">¿Suspende actividades académicas/laborales?</p>
                    <div id="show-suspension-periodo"></div>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>