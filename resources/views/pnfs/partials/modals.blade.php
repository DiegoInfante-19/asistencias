<div class="modal fade" id="createPnfModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold">Registrar PNF</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="createPnfForm" method="POST" action="{{ route('pnfs.store') }}">
                    @csrf
                    <input type="hidden" name="origen" value="create_pnf">
                    
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Nombre del PNF <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nombre_pnf" required autocomplete="off">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Descripción</label>
                        <textarea class="form-control" name="descripcion_pnf" rows="3" placeholder="Opcional"></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Vigencia <span class="text-danger">*</span></label>
                        <select class="form-select" name="vigencia_pnf" required>
                            <option value="1" selected>Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="createPnfForm" class="btn btn-primary fw-bold">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="UpdatePnfModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold">Modificar PNF</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="UpdatePnfForm" method="POST" action="">
                    @csrf @method('PUT')
                    <input type="hidden" name="origen" value="update_pnf">
                    
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Nombre del PNF <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nombre_pnf" id="edit-nombre-pnf" required autocomplete="off">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Descripción</label>
                        <textarea class="form-control" name="descripcion_pnf" id="edit-descripcion-pnf" rows="3"></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Vigencia <span class="text-danger">*</span></label>
                        <select class="form-select" name="vigencia_pnf" id="edit-vigencia-pnf" required>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="UpdatePnfForm" class="btn btn-warning fw-bold text-dark">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="showPnfModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold">Detalles del Programa</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="fw-bold small text-muted">Nombre del PNF</label>
                    <p id="show-nombre-pnf" class="form-control-plaintext mb-0 border-bottom pb-2"></p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold small text-muted">Descripción</label>
                    <p id="show-descripcion-pnf" class="form-control-plaintext mb-0 text-break border-bottom pb-2"></p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold small text-muted">Estado Actual</label>
                    <div class="pt-1">
                        <span id="show-vigencia-pnf"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>