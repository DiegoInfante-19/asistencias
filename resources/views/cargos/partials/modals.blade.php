<div class="modal fade" id="createCargoModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold">Registrar Cargo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="createCargoForm" method="POST" action="{{ route('cargos.store') }}">
                    @csrf
                    <input type="hidden" name="origen" value="create_cargo">
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Descripción del Cargo</label>
                        <input type="text" class="form-control" name="descripcion_cargo" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="createCargoForm" class="btn btn-primary fw-bold">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="UpdateCargoModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold">Modificar Cargo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="UpdateCargoForm" method="POST" action="">
                    @csrf @method('PUT')
                    <input type="hidden" name="origen" value="update_cargo">
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Descripción del Cargo</label>
                        <input type="text" class="form-control" name="descripcion_cargo" id="edit-descripcion-cargo" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="UpdateCargoForm" class="btn btn-warning fw-bold text-dark">Actualizar</button>
            </div>
        </div>
    </div>
</div>