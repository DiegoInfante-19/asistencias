<div class="modal fade" id="createCohorteModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold">Registrar Cohorte</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="createCohorteForm" method="POST" action="{{ route('cohortes.store') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Número de Cohorte</label>
                        <input type="text" class="form-control text-uppercase" name="numero_cohorte" required autocomplete="off" oninput="this.value = this.value.toUpperCase()">
                    </div>
                    <div class="row">
                        <div class="col-6 form-group mb-3">
                            <label class="form-label fw-bold small text-muted">Fecha Inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio_cohorte" required>
                        </div>
                        <div class="col-6 form-group mb-3">
                            <label class="form-label fw-bold small text-muted">Fecha Fin</label>
                            <input type="date" class="form-control" name="fecha_fin_cohorte" required>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Descripción</label>
                        <textarea class="form-control" name="descripcion_cohorte" rows="2"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Estatus</label>
                        <select class="form-select" name="estatus_cohorte" required>
                            <option value="En curso">En curso</option>
                            <option value="Finalizada">Finalizada</option>
                            <option value="Proxima">Próxima</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="createCohorteForm" class="btn btn-primary fw-bold">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="UpdateCohorteModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold">Modificar Cohorte</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="UpdateCohorteForm" method="POST" action="">
                    @csrf @method('PUT')
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Número de Cohorte</label>
                        <input type="text" class="form-control text-uppercase" name="numero_cohorte" id="edit-numero-cohorte" required>
                    </div>
                    <div class="row">
                        <div class="col-6 form-group mb-3">
                            <label class="form-label fw-bold small text-muted">Fecha Inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio_cohorte" id="edit-fecha-inicio" required>
                        </div>
                        <div class="col-6 form-group mb-3">
                            <label class="form-label fw-bold small text-muted">Fecha Fin</label>
                            <input type="date" class="form-control" name="fecha_fin_cohorte" id="edit-fecha-fin" required>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Descripción</label>
                        <textarea class="form-control" name="descripcion_cohorte" id="edit-descripcion" rows="2"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Estatus</label>
                        <select class="form-select" name="estatus_cohorte" id="edit-estatus" required>
                            <option value="En curso">En curso</option>
                            <option value="Finalizada">Finalizada</option>
                            <option value="Proxima">Próxima</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="UpdateCohorteForm" class="btn btn-warning fw-bold text-dark">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<!-- <div class="modal fade" id="createCohorteModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold">Registrar Cohorte</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="createCohorteForm" method="POST" action="{{ route('cohortes.store') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Número de Cohorte</label>
                        <input type="text" class="form-control" name="numero_cohorte" required autocomplete="off">
                    </div>
                    <div class="row">
                        <div class="col-6 form-group mb-3">
                            <label class="form-label fw-bold small text-muted">Fecha Inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio_cohorte" required>
                        </div>
                        <div class="col-6 form-group mb-3">
                            <label class="form-label fw-bold small text-muted">Fecha Fin</label>
                            <input type="date" class="form-control" name="fecha_fin_cohorte" required>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Descripción</label>
                        <textarea class="form-control" name="descripcion_cohorte" rows="2"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Estatus</label>
                        <select class="form-select" name="estatus_cohorte" required>
                            <option value="En curso">En curso</option>
                            <option value="Finalizada">Finalizada</option>
                            <option value="Proxima">Próxima</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="createCohorteForm" class="btn btn-primary fw-bold">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="UpdateCohorteModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold">Modificar Cohorte</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="UpdateCohorteForm" method="POST" action="">
                    @csrf @method('PUT')
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Número de Cohorte</label>
                        <input type="text" class="form-control" name="numero_cohorte" id="edit-numero-cohorte" required>
                    </div>
                    <div class="row">
                        <div class="col-6 form-group mb-3">
                            <label class="form-label fw-bold small text-muted">Fecha Inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio_cohorte" id="edit-fecha-inicio" required>
                        </div>
                        <div class="col-6 form-group mb-3">
                            <label class="form-label fw-bold small text-muted">Fecha Fin</label>
                            <input type="date" class="form-control" name="fecha_fin_cohorte" id="edit-fecha-fin" required>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Descripción</label>
                        <textarea class="form-control" name="descripcion_cohorte" id="edit-descripcion" rows="2"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Estatus</label>
                        <select class="form-select" name="estatus_cohorte" id="edit-estatus" required>
                            <option value="En curso">En curso</option>
                            <option value="Finalizada">Finalizada</option>
                            <option value="Proxima">Próxima</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="UpdateCohorteForm" class="btn btn-warning fw-bold text-dark">Actualizar</button>
            </div>
        </div>
    </div>
</div> -->