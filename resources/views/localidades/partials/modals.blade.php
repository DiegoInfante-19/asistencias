<div class="modal fade" id="createEstateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="createEstateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header border-bottom">
                <h1 class="modal-title fs-5 fw-bold" id="createEstateModalLabel">
                    Registrar Estados
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <form id="createEstateForm" method="POST" action="{{ route('estados.store') }}">
                    @csrf
                    <input type="hidden" name="origen" value="create_estado">
                    <div class="col-md-12">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nombre del Estado</label>
                        <input type="text" class="form-control @error('nombre_estado') is-invalid @enderror" name="nombre_estado" value="{{ old('nombre_estado') }}" autocomplete="off" required>
                        @error('nombre_estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </form>
            </div>

            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="createEstateForm" class="btn btn-primary fw-bold shadow-sm">
                    <i class="bi bi-save me-1"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createCiudadModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="createCiudadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header border-bottom">
                <h1 class="modal-title fs-5 fw-bold" id="createCiudadModalLabel">
                    Registrar Estados
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <form id="createCiudadForm" method="POST" action="{{ route('ciudades.store') }}">
                <input type="hidden" name="origen" value="create_ciudad">    
                @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Estado</label>
                            <select class="form-select @error('id_estado') is-invalid @enderror" name="id_estado" required>
                                <option value="">Seleccione...</option>
                                @foreach($estados as $estado)
                                <option value="{{ $estado->id_estado }}" {{ old('id_estado') == $estado->id_estado ? 'selected' : '' }}>{{ $estado->nombre_estado }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Nombre de la Ciudad</label>
                            <input type="text" class="form-control @error('nombre_ciudad') is-invalid @enderror" name="nombre_ciudad" value="{{ old('nombre_ciudad') }}" autocomplete="off" required>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="createCiudadForm" class="btn btn-primary fw-bold shadow-sm">
                    <i class="bi bi-save me-1"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="UpdateEstateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="UpdateEstateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header border-bottom">
                <h1 class="modal-title fs-5 fw-bold" id="UpdateEstateModalLabel">
                    Registrar Estados
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                
                <form id="UpdateEstateForm" method="POST" action="">
                    @csrf @method('PUT')
                    <input type="hidden" name="id_estado" id="edit-id-estado">
                    <label class="form-label fw-bold small text-muted text-uppercase">Nombre del Estado</label>
                    <input type="text" class="form-control" name="nombre_estado" id="edit-nombre-estado" autocomplete="off" required>
                </form>
            </div>

            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="UpdateEstateForm" class="btn btn-warning fw-bold text-dark shadow-sm">
                    <i class="bi bi-pencil me-1"></i> Actualizar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="UpdateCiudadModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="UpdateCiudadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header border-bottom">
                <h1 class="modal-title fs-5 fw-bold" id="UpdateCiudadModalLabel">
                    Registrar Estados
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <form id="UpdateCiudadForm" method="POST" action="">
                    @csrf @method('PUT')
                    <input type="hidden" name="id_ciudad" id="edit-id-ciudad">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Estado</label>
                            <select class="form-select" name="id_estado" id="edit-id-estado-ciudad" required>
                                @foreach($estados as $estado)
                                    <option value="{{ $estado->id_estado }}">{{ $estado->nombre_estado }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Nombre de la Ciudad</label>
                            <input type="text" class="form-control" name="nombre_ciudad" id="edit-nombre-ciudad" autocomplete="off" required>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="UpdateCiudadForm" class="btn btn-warning fw-bold text-dark shadow-sm">
                    <i class="bi bi-pencil me-1"></i> Actualizar
                </button>
            </div>
        </div>
    </div>
</div>