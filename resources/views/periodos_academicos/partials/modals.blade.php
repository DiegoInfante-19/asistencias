<!-- MODAL: CREAR PERÍODO -->
<div class="modal fade" id="createPeriodoModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="createPeriodoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="createPeriodoLabel">Aperturar Nuevo Período Académico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="createPeriodoForm" method="POST" action="{{ route('periodos-academicos.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Cohorte Base <span class="text-danger">*</span></label>
                            <select class="form-select" name="id_cohortes" required>
                                <option value="" selected disabled>Seleccione...</option>
                                @foreach($cohortes as $cohorte)
                                    <option value="{{ $cohorte->id_cohortes }}">Cohorte {{ $cohorte->numero_cohorte }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Estatus <span class="text-danger">*</span></label>
                            <select class="form-select" name="estatus_periodo" required>
                                <option value="Activo" selected>Activo</option>
                                <option value="Inactivo">Inactivo</option>
                                <option value="Cerrado">Cerrado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Fecha de Inicio <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="fecha_inicio" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Fecha de Cierre <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="fecha_fin" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="createPeriodoForm" class="btn btn-primary fw-bold shadow-sm">
                    <i class="bi bi-save me-1"></i> Guardar Período
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: EDITAR PERÍODO -->
<div class="modal fade" id="editPeriodoModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="editPeriodoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="editPeriodoLabel">Modificar Período Académico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editPeriodoForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Cohorte Base</label>
                            <select class="form-select" name="id_cohortes" id="edit_cohorte" required>
                                @foreach($cohortes as $cohorte)
                                    <option value="{{ $cohorte->id_cohortes }}">Cohorte {{ $cohorte->numero_cohorte }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Estatus</label>
                            <select class="form-select" name="estatus_periodo" id="edit_estatus" required>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                                <option value="Cerrado">Cerrado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Fecha de Inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio" id="edit_inicio" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Fecha de Cierre</label>
                            <input type="date" class="form-control" name="fecha_fin" id="edit_fin" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="editPeriodoForm" class="btn btn-warning fw-bold text-dark shadow-sm">
                    <i class="bi bi-save me-1"></i> Actualizar Período
                </button>
            </div>
        </div>
    </div>
</div>