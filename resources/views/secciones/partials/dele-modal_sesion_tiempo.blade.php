<!-- MODAL: REGISTRO ADMINISTRATIVO DE SESIÓN -->
<div class="modal fade" id="modalMaquinaTiempoSesion" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-white border-bottom">
                <h1 class="modal-title fs-5 fw-bold text-dark">
                    <i class="bi bi-calendar-plus text-primary me-2"></i> Registro Administrativo de Sesión
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body bg-white p-4">
                <form action="{{ route('sesiones.store') }}" method="POST" id="formMaquinaTiempo">
                    @csrf
                    <input type="hidden" name="id_seccion" value="{{ $seccion->id_seccion }}">

                    <!-- AVISO INFORMATIVO -->
                    <div class="alert alert-info border-0 bg-light text-dark small mb-3 shadow-sm">
                        <i class="bi bi-info-circle-fill text-primary me-1"></i>
                        Permite registrar o transcribir asistencias de días miércoles pasados mediante control administrativo.
                    </div>

                    <!-- 1. SELECCIÓN DE FECHA (MIÉRCOLES) -->
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Fecha de la Clase (Solo Miércoles) <span class="text-danger">*</span></label>
                        <input type="date" class="form-control bg-light border-secondary-subtle shadow-sm" name="fecha_sesion" id="admin_fecha_sesion" required>
                        <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">El sistema validará automáticamente que corresponda a un día miércoles hábil.</small>
                    </div>

                    <!-- 2. SELECCIÓN DE PROFESOR (TITULAR VS SUPLENTE) -->
                    <div class="form-group mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold small text-muted mb-0">Profesor a Cargo <span class="text-danger">*</span></label>
                            <div class="form-check form-switch small">
                                <input class="form-check-input" type="checkbox" role="switch" id="toggleProfesorSuplente">
                                <label class="form-check-label text-muted fw-semibold" for="toggleProfesorSuplente" style="font-size: 0.75rem;">¿Profesor Suplente?</label>
                            </div>
                        </div>

                        <!-- Selector por defecto: Profesores oficiales de la sección -->
                        <select class="form-select bg-light border-secondary-subtle shadow-sm" name="id_profesor" id="selectProfesorTitular" required>
                            <option value="" disabled selected>Seleccione el profesor...</option>
                            @foreach($seccion->profesores as $profesor)
                                <option value="{{ $profesor->id_profesor }}">
                                    {{ $profesor->user->name_users }} {{ $profesor->user->last_name_users }} (Titular)
                                </option>
                            @endforeach
                        </select>

                        <!-- Selector alternativo (Oculto por defecto): Todos los profesores del sistema -->
                        <select class="form-select bg-light border-secondary-subtle shadow-sm d-none" id="selectProfesorSuplente" disabled>
                            <option value="" disabled selected>Seleccione el profesor suplente...</option>
                            @foreach(\App\Models\Profesor::with('user')->get() as $prof)
                                <option value="{{ $prof->id_profesor }}">
                                    {{ $prof->user->name_users }} {{ $prof->user->last_name_users }} [Suplente / Externo]
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 3. OBSERVACIÓN OPCIONAL -->
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Observación Administrativa</label>
                        <textarea class="form-control bg-light border-secondary-subtle shadow-sm" name="observacion_sesion" rows="2" placeholder="Ej: Transcripción de asistencia entregada en físico por retraso..."></textarea>
                    </div>
                </form>
            </div>

            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="submit" form="formMaquinaTiempo" class="btn btn-primary btn-sm fw-bold shadow-sm">
                    <i class="bi bi-save-fill me-1"></i> Continuar al Pase de Lista
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleSuplente = document.getElementById('toggleProfesorSuplente');
        const selectTitular = document.getElementById('selectProfesorTitular');
        const selectSuplente = document.getElementById('selectProfesorSuplente');

        if (toggleSuplente) {
            toggleSuplente.addEventListener('change', function() {
                if (this.checked) {
                    selectTitular.classList.add('d-none');
                    selectTitular.removeAttribute('name');
                    selectTitular.removeAttribute('required');
                    
                    selectSuplente.classList.remove('d-none');
                    selectSuplente.setAttribute('name', 'id_profesor');
                    selectSuplente.setAttribute('required', 'required');
                    selectSuplente.removeAttribute('disabled');
                } else {
                    selectSuplente.classList.add('d-none');
                    selectSuplente.removeAttribute('name');
                    selectSuplente.removeAttribute('required');
                    selectSuplente.setAttribute('disabled', 'disabled');

                    selectTitular.classList.remove('d-none');
                    selectTitular.setAttribute('name', 'id_profesor');
                    selectTitular.setAttribute('required', 'required');
                }
            });
        }
    });
</script>
@endpush