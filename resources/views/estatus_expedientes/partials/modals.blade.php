<!-- MODAL: REGISTRAR ESTATUS -->
<div class="modal fade" id="createEstatusModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header bg-white border-bottom">
                <h1 class="modal-title fs-5 text-dark" style="font-weight: 500;">
                    Registrar Estatus
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <!-- Body en BLANCO absoluto para resaltar los inputs grises -->
            <div class="modal-body bg-white p-4">
                <form id="createEstatusForm" method="POST" action="{{ route('estatus_expedientes.store') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Nombre del Estatus <span class="text-danger">*</span></label>
                        <!-- Input con fondo gris claro y borde sutil -->
                        <input type="text" class="form-control bg-light border-secondary-subtle shadow-sm" name="nombre_estatus_expediente" placeholder="Ej. En Revisión, Aprobado..." autocomplete="off" required>
                    </div>
                </form>
            </div>
            
            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="submit" form="createEstatusForm" class="btn btn-primary fw-bold">
                    <i class="bi bi-save-fill me-1"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: MODIFICAR ESTATUS -->
<div class="modal fade" id="UpdateEstatusModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header bg-white border-bottom">
                <h1 class="modal-title fs-5 text-dark" style="font-weight: 500;">
                    Modificar Estatus
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <!-- Body en BLANCO absoluto -->
            <div class="modal-body bg-white p-4">
                <form id="UpdateEstatusForm" method="POST" action="">
                    @csrf 
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Nombre del Estatus <span class="text-danger">*</span></label>
                        <!-- Input con fondo gris claro y borde sutil -->
                        <input type="text" class="form-control bg-light border-secondary-subtle shadow-sm" name="nombre_estatus_expediente" id="edit-nombre-estatus" autocomplete="off" required>
                    </div>
                </form>
            </div>
            
            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="submit" form="UpdateEstatusForm" class="btn btn-warning fw-bold text-dark">
                    <i class="bi bi-arrow-repeat me-1"></i> Actualizar
                </button>
            </div>
        </div>
    </div>
</div>