<!-- MODAL: REGISTRAR TÍTULO -->
<div class="modal fade" id="createTituloModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header bg-white border-bottom">
                <h1 class="modal-title fs-5 text-dark" style="font-weight: 500;">
                    Registrar Título Base
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <!-- Body en BLANCO absoluto para resaltar los inputs grises -->
            <div class="modal-body bg-white p-4">
                <form id="createTituloForm" method="POST" action="{{ route('titulos.store') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Nombre del Título <span class="text-danger">*</span></label>
                        <!-- Input con fondo gris claro y borde sutil -->
                        <input type="text" class="form-control bg-light border-secondary-subtle text-uppercase shadow-sm" name="nombre_titulo_base" required autocomplete="off" oninput="this.value = this.value.toUpperCase()">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Nivel Académico <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-light border-secondary-subtle text-uppercase shadow-sm" name="nivel_academico" required autocomplete="off" oninput="this.value = this.value.toUpperCase()" placeholder="Ej: Pregrado, Postgrado, Técnico...">
                    </div>
                </form>
            </div>
            
            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="submit" form="createTituloForm" class="btn btn-primary fw-bold">
                    <i class="bi bi-save-fill me-1"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: MODIFICAR TÍTULO -->
<div class="modal fade" id="UpdateTituloModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header bg-white border-bottom">
                <h1 class="modal-title fs-5 text-dark" style="font-weight: 500;">
                    Modificar Título Base
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <!-- Body en BLANCO absoluto -->
            <div class="modal-body bg-white p-4">
                <form id="UpdateTituloForm" method="POST" action="">
                    @csrf 
                    @method('PUT')
                    
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Nombre del Título <span class="text-danger">*</span></label>
                        <!-- Input con fondo gris claro y borde sutil -->
                        <input type="text" class="form-control bg-light border-secondary-subtle text-uppercase shadow-sm" name="nombre_titulo_base" id="edit-nombre-titulo" required autocomplete="off" oninput="this.value = this.value.toUpperCase()">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Nivel Académico <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-light border-secondary-subtle text-uppercase shadow-sm" name="nivel_academico" id="edit-nivel-titulo" required autocomplete="off" oninput="this.value = this.value.toUpperCase()">
                    </div>
                </form>
            </div>
            
            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="submit" form="UpdateTituloForm" class="btn btn-warning fw-bold text-dark">
                    <i class="bi bi-arrow-repeat me-1"></i> Actualizar
                </button>
            </div>
        </div>
    </div>
</div>