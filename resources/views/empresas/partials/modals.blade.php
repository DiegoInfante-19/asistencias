<div class="modal fade" id="createEmpresaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold">Registrar Empresa</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="createEmpresaForm" method="POST" action="{{ route('empresas.store') }}">
                    @csrf
                    <input type="hidden" name="origen" value="create_empresa">
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Nombre de la Empresa</label>
                        <input type="text" class="form-control @error('nombre_empresa') is-invalid @enderror" name="nombre_empresa" value="{{ old('nombre_empresa') }}" required>
                        <div class="dynamic-feedback text-danger small mt-1"></div>
                        @error('nombre_empresa')<div class="invalid-feedback" role="alert">{{ $message }}</div>@enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="createEmpresaForm" class="btn btn-primary fw-bold">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="UpdateEmpresaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold">Modificar Empresa</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="UpdateEmpresaForm" method="POST" action="">
                    @csrf @method('PUT')
                    <input type="hidden" name="origen" value="update_empresa">
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Nombre de la Empresa</label>
                        <input type="text" class="form-control" name="nombre_empresa" id="edit-nombre-empresa" required>
                        <div class="dynamic-feedback text-danger small mt-1"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="UpdateEmpresaForm" class="btn btn-warning fw-bold text-dark">Actualizar</button>
            </div>
        </div>
    </div>
</div>