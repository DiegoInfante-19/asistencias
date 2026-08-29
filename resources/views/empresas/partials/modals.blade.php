<!-- MODAL: REGISTRAR EMPRESA -->
<div class="modal fade" id="createEmpresaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header bg-white border-bottom">
                <h1 class="modal-title fs-5 text-dark" style="font-weight: 500;">
                    Registrar Empresa
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <!-- Body en BLANCO absoluto para resaltar el input gris -->
            <div class="modal-body bg-white p-4">
                <form id="createEmpresaForm" method="POST" action="{{ route('empresas.store') }}">
                    @csrf
                    <input type="hidden" name="origen" value="create_empresa">
                    
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Nombre de la Empresa <span class="text-danger">*</span></label>
                        <!-- Input con fondo gris claro y borde sutil -->
                        <input type="text" class="form-control bg-light border-secondary-subtle text-uppercase shadow-sm @error('nombre_empresa') is-invalid @enderror" name="nombre_empresa" value="{{ old('nombre_empresa') }}" autocomplete="off" required oninput="this.value = this.value.toUpperCase()">

                        <div class="dynamic-feedback text-danger small mt-1"></div>
                        @error('nombre_empresa')<div class="invalid-feedback" role="alert">{{ $message }}</div>@enderror
                    </div>
                </form>
            </div>
            
            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="submit" form="createEmpresaForm" class="btn btn-primary fw-bold">
                    <i class="bi bi-save-fill me-1"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: MODIFICAR EMPRESA -->
<div class="modal fade" id="UpdateEmpresaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header bg-white border-bottom">
                <h1 class="modal-title fs-5 text-dark" style="font-weight: 500;">
                    Modificar Empresa
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <!-- Body en BLANCO absoluto -->
            <div class="modal-body bg-white p-4">
                <form id="UpdateEmpresaForm" method="POST" action="">
                    @csrf 
                    @method('PUT')
                    <input type="hidden" name="origen" value="update_empresa">
                    
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Nombre de la Empresa <span class="text-danger">*</span></label>
                        <!-- Input con fondo gris claro y borde sutil -->
                        <input type="text" class="form-control bg-light border-secondary-subtle text-uppercase shadow-sm" name="nombre_empresa" id="edit-nombre-empresa" autocomplete="off" required oninput="this.value = this.value.toUpperCase()">

                        <div class="dynamic-feedback text-danger small mt-1"></div>
                    </div>
                </form>
            </div>
            
            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="submit" form="UpdateEmpresaForm" class="btn btn-warning fw-bold text-dark">
                    <i class="bi bi-arrow-repeat me-1"></i> Actualizar
                </button>
            </div>
        </div>
    </div>
</div>