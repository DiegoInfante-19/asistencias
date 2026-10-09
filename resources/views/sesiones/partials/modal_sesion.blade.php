<!-- Modal Único de Sesión (Crear / Editar) -->
<div class="modal fade" id="modalSesion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalSesionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            
            <div class="modal-header bg-white border-bottom">
                <h1 class="modal-title fs-5 text-dark" id="modalSesionLabel" style="font-weight: 500;">
                    <i class="bi bi-calendar-plus text-primary me-2"></i><span id="modalSesionTitle">Programar Sesión de Clase</span>
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="formSesion" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="id_seccion" value="{{ $seccion->id_seccion ?? '' }}">

                <div class="modal-body bg-white p-4">
                    
                    <!-- Alerta de restricción si ya tiene asistencia -->
                    <div id="alertaAsistenciaRegistrada" class="alert alert-warning d-none mb-4 border-0 shadow-sm">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Atención:</strong> Esta sesión ya cuenta con registros de asistencia. Por seguridad institucional, **la fecha de la clase no puede ser modificada**.
                    </div>

                    <div class="row g-3">
                        <!-- 1. Información de la Sección (Fija) -->
                        <div class="col-12 mb-2">
                            <label class="form-label fw-bold small text-muted text-uppercase">1. Sección Académica</label>
                            <input type="text" class="form-control bg-light border-secondary-subtle text-muted fw-bold shadow-sm" value="{{ $seccion->nombre_seccion ?? '' }} — {{ $seccion->pnf->nombre_pnf ?? '' }}" disabled>
                        </div>

                        <!-- 2. Selección del Profesor con Select2 -->
                        <div class="col-12 mb-2">
                            <label class="form-label fw-bold small text-muted text-uppercase">2. Profesor Responsable <span class="text-danger">*</span></label>
                            <select class="form-select select2-buscador bg-light border-secondary-subtle shadow-sm" name="id_profesor" id="modal_id_profesor" required>
                                <option value="" selected disabled>Seleccione el docente...</option>
                                @if(isset($seccion) && $seccion->profesores)
                                    @foreach($seccion->profesores as $profesor)
                                        <option value="{{ $profesor->id_profesor }}">
                                            {{ trim(($profesor->user->name_users ?? '') . ' ' . ($profesor->user->last_name_users ?? '')) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="form-text text-muted small">Docentes asignados a esta sección.</div>
                            <div class="invalid-feedback fw-bold" id="error_id_profesor"></div>
                        </div>

                        <!-- 3. Fecha de la Clase (Flatpickr Local) -->
                        <div class="col-12 mb-2">
                            <label class="form-label fw-bold small text-muted text-uppercase">3. Fecha de la Clase (Solo Miércoles) <span class="text-danger">*</span></label>
                            <input type="text" id="modal_fecha_sesion" class="form-control bg-light border-secondary-subtle shadow-sm" 
                                   name="fecha_sesion" 
                                   placeholder="Seleccione un miércoles..."
                                   required autocomplete="off">
                            <div class="form-text text-muted small" id="ayuda_fecha">Los días no hábiles o distintos al miércoles están bloqueados por calendario.</div>
                            <div class="invalid-feedback d-block fw-bold" id="error_fecha_sesion"></div>
                        </div>

                        <!-- 4. Observaciones -->
                        <div class="col-12 mb-2">
                            <label class="form-label fw-bold small text-muted text-uppercase">4. Observaciones de Programación <span class="text-muted small fw-normal">(Opcional)</span></label>
                            <textarea class="form-control bg-light border-secondary-subtle shadow-sm" 
                                      name="observacion_sesion" id="modal_observacion_sesion" rows="3" 
                                      placeholder="Ej: Clase de recuperación / Suplencia"></textarea>
                            <div class="invalid-feedback fw-bold" id="error_observacion_sesion"></div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-white border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary fw-bold shadow-sm" id="btnGuardarSesion">
                        <i class="bi bi-calendar-check-fill me-1"></i> <span id="btnText">Programar Clase</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>