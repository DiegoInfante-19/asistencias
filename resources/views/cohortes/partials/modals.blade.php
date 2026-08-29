<!-- MODAL: REGISTRAR COHORTE -->
<div class="modal fade" id="createCohorteModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header bg-white border-bottom">
                <h1 class="modal-title fs-5 text-dark" style="font-weight: 500;">
                    Registrar Cohorte Nueva
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Body en blanco -->
            <div class="modal-body bg-white p-4">
                <form id="createCohorteForm" method="POST" action="{{ route('cohortes.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Número de Cohorte <span class="text-danger">*</span></label>
                        <!-- Input con bg-light para contraste -->
                        <input type="text" class="form-control bg-light border-secondary-subtle text-uppercase" name="numero_cohorte" required autocomplete="off" oninput="this.value = this.value.toUpperCase()">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Descripción (Opcional)</label>
                        <textarea class="form-control bg-light border-secondary-subtle" name="descripcion_cohorte" rows="2"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Estatus <span class="text-danger">*</span></label>
                        <select class="form-select bg-light border-secondary-subtle" name="estatus_cohorte" required>
                            <option value="Activo" selected>Activo (En curso)</option>
                            <option value="Próxima">Próxima</option>
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
                    <i class="bi bi-save-fill me-1"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: MODIFICAR COHORTE -->
<div class="modal fade" id="UpdateCohorteModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header bg-white border-bottom">
                <h1 class="modal-title fs-5 text-dark" style="font-weight: 500;">
                    Editar datos de la Cohorte
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Body en blanco -->
            <div class="modal-body bg-white p-4">
                <form id="UpdateCohorteForm" method="POST" action="">
                    @csrf 
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Número de Cohorte <span class="text-danger">*</span></label>
                        <!-- Input con bg-light para contraste -->
                        <input type="text" class="form-control bg-light border-secondary-subtle text-uppercase" name="numero_cohorte" id="edit-numero-cohorte" required oninput="this.value = this.value.toUpperCase()">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Descripción (Opcional)</label>
                        <textarea class="form-control bg-light border-secondary-subtle" name="descripcion_cohorte" id="edit-descripcion" rows="2"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Estatus <span class="text-danger">*</span></label>
                        <select class="form-select bg-light border-secondary-subtle" name="estatus_cohorte" id="edit-estatus" required>
                            <option value="Activo">Activo (En curso)</option>
                            <option value="Próxima">Próxima</option>
                            <option value="Finalizada">Finalizada</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="submit" form="UpdateCohorteForm" class="btn btn-warning fw-bold text-dark">
                    <i class="bi bi-arrow-repeat me-1"></i> Actualizar
                </button>
            </div>
        </div>
    </div>
</div>