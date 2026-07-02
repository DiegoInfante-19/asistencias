<div class="modal fade" id="createTituloModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold">Registrar Título</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="createTituloForm" method="POST" action="{{ route('titulos.store') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Nombre del Título</label>
                        <input type="text" class="form-control" name="nombre_titulo_base" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Nivel Académico</label>
                        <input type="text" class="form-control" name="nivel_academico" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="createTituloForm" class="btn btn-primary fw-bold">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="UpdateTituloModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold">Modificar Título</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="UpdateTituloForm" method="POST" action="">
                    @csrf @method('PUT')
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Nombre del Título</label>
                        <input type="text" class="form-control" name="nombre_titulo_base" id="edit-nombre-titulo" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Nivel Académico</label>
                        <input type="text" class="form-control" name="nivel_academico" id="edit-nivel-titulo" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="UpdateTituloForm" class="btn btn-warning fw-bold text-dark">Actualizar</button>
            </div>
        </div>
    </div>
</div>