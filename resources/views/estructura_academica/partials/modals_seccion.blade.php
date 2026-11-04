<!-- ========================================== -->
<!-- MODAL: NUEVA SECCIÓN                       -->
<!-- ========================================== -->
<div class="modal fade" id="modalSeccion" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-white border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="bi bi-layers text-success me-2"></i>Nueva Sección Académica
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-white p-4">
                <form action="{{ route('secciones.store') }}" method="POST" id="createSeccionForm">
                    @csrf
                    <!-- ID CORREGIDO: Coincide perfectamente con el script -->
                    <input type="hidden" name="id_periodo" id="id_periodo_hidden">
                    
                    <!-- SELECT PNF (MULTI-PNF HABILITADO) -->
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">PNFs Asignados a la Sección <span class="text-danger">*</span></label>
                        <!-- AQUI: Cambio a array (pnfs[]) y atributo multiple -->
                        <select class="form-select select2-multiple-pnf shadow-sm" 
                                name="pnfs[]" 
                                id="select_pnfs_seccion" 
                                multiple="multiple" 
                                data-placeholder="Seleccione uno o más PNFs..." 
                                required>
                            @foreach($pnfs as $pnf)
                                <option value="{{ $pnf->id_pnf }}" data-nombre="{{ $pnf->nombre_pnf }}">{{ $pnf->nombre_pnf }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted" style="font-size: 0.75rem;">Puede asociar 2 o más PNFs para permitir matrícula compartida.</small>
                    </div>

                    <!-- FILA GENERADORA DE NOMBRE -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-bold small text-muted">Número <span class="text-danger">*</span></label>
                                <input type="number" class="form-control bg-light border-secondary-subtle shadow-sm text-center fw-bold" id="numero_correlativo_seccion" min="1" placeholder="Ej: 1" required autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="form-label fw-bold small text-muted">Nombre Generado</label>
                                <input type="text" class="form-control bg-white border-secondary-subtle shadow-sm fw-bold text-primary" name="nombre_seccion" id="nombre_seccion_final" readonly placeholder="Seleccione PNF y Número" tabindex="-1">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Estatus Inicial <span class="text-danger">*</span></label>
                        <select class="form-select bg-light border-secondary-subtle shadow-sm" name="estatus_seccion" required>
                            <option value="Activa" selected>Activa</option>
                            <option value="Inactiva">Inactiva</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-white border-top py-3">
                <button type="button" class="btn btn-outline-secondary px-3" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="submit" form="createSeccionForm" class="btn btn-success fw-bold px-4 shadow-sm">
                    <i class="bi bi-save-fill me-1"></i> Guardar Sección
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: ACTUALIZAR SECCIÓN                  -->
<!-- ========================================== -->
<div class="modal fade" id="modalEditarSeccion" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-white border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="bi bi-pencil-square text-warning me-2"></i>Editar Sección
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-white p-4">
                <form id="formEditarSeccion" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- AQUI: Ahora permitimos editar los PNFs asociados -->
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">PNFs Asignados <span class="text-danger">*</span></label>
                        <select class="form-select select2-multiple-pnf shadow-sm" 
                                name="pnfs[]" 
                                id="edit_select_pnfs_seccion" 
                                multiple="multiple" 
                                data-placeholder="Seleccione uno o más PNFs..." 
                                required>
                            @foreach($pnfs as $pnf)
                                <option value="{{ $pnf->id_pnf }}" data-nombre="{{ $pnf->nombre_pnf }}">{{ $pnf->nombre_pnf }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted" style="font-size: 0.75rem;">Añade o quita PNFs según las necesidades de la sección.</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Nombre de la Sección <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-white border-secondary-subtle shadow-sm fw-bold" name="nombre_seccion" id="edit_nombre_seccion" required autocomplete="off">
                        <small class="text-muted" style="font-size: 0.75rem;">Puedes ajustar el nombre manualmente si es necesario.</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Estatus de la Sección <span class="text-danger">*</span></label>
                        <select class="form-select bg-light border-secondary-subtle shadow-sm" name="estatus_seccion" id="edit_estatus_seccion" required>
                            <option value="Activa">Activa</option>
                            <option value="Inactiva">Inactiva</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-white border-top py-3">
                <button type="button" class="btn btn-outline-secondary px-3" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="submit" form="formEditarSeccion" class="btn btn-warning fw-bold text-dark px-4 shadow-sm">
                    <i class="bi bi-arrow-repeat me-1"></i> Actualizar
                </button>
            </div>
        </div>
    </div>
</div>