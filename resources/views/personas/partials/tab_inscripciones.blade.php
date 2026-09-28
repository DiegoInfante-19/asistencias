<div class="row g-4 mt-2">
    <!-- COLUMNA IZQUIERDA: FORMULARIO DE INSCRIPCIÓN -->
    <div class="col-md-4">
        <div class="card shadow-sm h-100" id="card_inscripcion_container">
            <div class="card-header bg-white py-3 fw-bold text-dark">
                Inscribir en Sección Académica
            </div>
            <div class="card-body bg-white py-4">

                @if(!$persona->titulacionPersona)
                    <!-- BLOQUEO 1: PNF Faltante -->
                    <div class="text-center py-5">
                        <i class="bi bi-shield-lock-fill text-danger mb-3" style="font-size: 3.5rem;"></i>
                        <h5 class="fw-bold">Acción Bloqueada</h5>
                        <p class="text-muted small px-2">Para poder inscribir a este estudiante, <strong>primero debe asignarle un Programa Nacional de Formación (PNF)</strong> en la pestaña "Exp. Académico".</p>
                    </div>

                @elseif($persona->inscripcionActiva)
                    <!-- BLOQUEO 2: Estudiante ya matriculado activamente -->
                    <div class="text-center py-4">
                        <i class="bi bi-person-check-fill text-success mb-3" style="font-size: 3.5rem;"></i>
                        <h5 class="fw-bold text-dark">Matrícula Activa</h5>
                        <p class="text-muted small px-2">Este estudiante ya se encuentra cursando estudios en una sección. Un estudiante no puede estar inscrito en dos secciones simultáneamente.</p>
                        
                        <div class="alert alert-success border-0 shadow-sm text-start mt-3">
                            <span class="d-block small text-muted fw-bold">Sección Actual:</span>
                            <span class="d-block fw-bold fs-6">{{ $persona->inscripcionActiva->seccion->nombre_seccion }}</span>
                        </div>
                    </div>

                @else
                    <!-- FORMULARIO DE INSCRIPCIÓN -->
                    <div class="alert alert-primary py-2 shadow-sm border-0 small mb-3">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        Filtrando secciones exclusivas para:<br>
                        <strong>{{ $persona->titulacionPersona->pnf->nombre_pnf ?? 'PNF Asignado' }}</strong>
                    </div>

                    @php
                        // Filtramos directamente en PHP de manera segura usando el ID del PNF del estudiante
                        $pnfIdEstudiante = $persona->titulacionPersona->id_pnf;
                        $seccionesFiltradas = collect($seccionesData ?? [])->filter(function($sec) use ($pnfIdEstudiante) {
                            return $sec['id_pnf'] == $pnfIdEstudiante;
                        });
                    @endphp

                    <form action="{{ route('personas.inscripciones.store', $persona->id_personas) }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_personas" value="{{ $persona->id_personas }}">

                        <!-- Select: Sección -->
                        <div class="mb-3">
                            <label for="select_seccion" class="form-label fw-bold small text-muted">Sección Académica <span class="text-danger">*</span></label>
                            
                            <select id="select_seccion" class="form-select bg-light border-secondary-subtle shadow-sm" required {{ $seccionesFiltradas->isEmpty() ? 'disabled' : '' }}>
                                <option value="" selected disabled>
                                    {{ $seccionesFiltradas->isNotEmpty() ? 'Seleccione una sección...' : 'No hay secciones activas para este PNF' }}
                                </option>
                                
                                @foreach($seccionesFiltradas as $sec)
                                    <option value="{{ $sec['id_seccion'] }}">
                                        {{ $sec['nombre_seccion'] }}
                                    </option>
                                @endforeach
                            </select>
                            
                            <small class="text-muted d-block mt-1">Solo se muestran secciones activas del PNF del estudiante.</small>
                        </div>

                        <input type="hidden" name="id_seccion" id="id_seccion_hidden" value="">
                        @error('id_seccion')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror

                        <!-- Fecha de Inscripción (Estilo gris y sombreado añadido) -->
                        <div class="mb-3 mt-3">
                            <label for="fecha_inscripcion" class="form-label fw-bold small text-muted">Fecha de Inscripción <span class="text-danger">*</span></label>
                            <input type="date" class="form-control bg-light border-secondary-subtle shadow-sm @error('fecha_inscripcion') is-invalid @enderror" 
                                   id="fecha_inscripcion" name="fecha_inscripcion" value="{{ old('fecha_inscripcion', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                            @error('fecha_inscripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Estatus Inicial (Estilo gris y sombreado añadido) -->
                        <div class="mb-4">
                            <label for="estatus_inscripcion" class="form-label fw-bold small text-muted">Estatus Inicial <span class="text-danger">*</span></label>
                            <select class="form-select bg-light border-secondary-subtle shadow-sm @error('estatus_inscripcion') is-invalid @enderror" id="estatus_inscripcion" name="estatus_inscripcion" required>
                                <option value="Activo" selected>Activo</option>
                                <option value="Retirado">Retirado</option>
                            </select>
                            @error('estatus_inscripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <button type="submit" id="btn_inscribir" class="btn btn-primary" disabled>
                                <i class="bi bi-save-fill me-1"></i> Registrar Inscripción
                            </button>
                        </div>
                    </form>
                @endif
            </div>
            <div class="card-footer bg-light py-2 text-muted small">
                Módulo de matriculación en secciones.
            </div>
        </div>
    </div>

    <!-- COLUMNA DERECHA: HISTORIAL DE INSCRIPCIONES -->
    <div class="col-md-8">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3 fw-bold text-dark">
                Historial Académico del Estudiante
            </div>
            <div class="card-body bg-white p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Período / Cohorte</th>
                                <th>PNF / Sección</th>
                                <th>Inscripción</th>
                                <th class="text-center">Estatus</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($persona->inscripcionesSecciones as $inscripcion)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $inscripcion->seccion->periodoAcademico->cohorte->numero_cohorte ?? 'N/A' }}</div>
                                        <div class="small text-muted">Inicio: {{ \Carbon\Carbon::parse($inscripcion->seccion->periodoAcademico->fecha_inicio ?? now())->format('d/m/y') }}</div>
                                    </td>
                                    <td>
                                        <div class="text-dark fw-semibold">{{ $inscripcion->seccion->pnf->nombre_pnf ?? 'N/A' }}</div>
                                        <div class="small text-muted">Sec: {{ $inscripcion->seccion->nombre_seccion ?? 'N/A' }}</div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($inscripcion->fecha_inscripcion)->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        @if($inscripcion->estatus_inscripcion == 'Activo')
                                            <h5><span class="badge bg-success px-2 py-1">Activo</span></h5>
                                        @elseif($inscripcion->estatus_inscripcion == 'Retirado')
                                            <h5><span class="badge bg-danger px-2 py-1">Retirado</span></h5>
                                        @else
                                            <h5><span class="badge bg-secondary px-2 py-1">{{ $inscripcion->estatus_inscripcion }}</span></h5>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('personas.inscripciones.destroy', ['persona' => $persona->id_personas, 'inscripcion' => $inscripcion->id_inscripcion_seccion]) }}" method="POST" class="form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger shadow-sm" title="Anular Inscripción">
                                                Remover
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="bi bi-calendar-x fs-1 d-block mb-3" style="opacity: 0.5;"></i>
                                        <h6 class="fw-semibold">Sin historial de inscripciones</h6>
                                        <span class="small">El estudiante aún no ha sido matriculado en ninguna sección.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light py-2 text-muted small">
                Registro histórico de secciones cursadas.
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Estilo unificado para que Select2 refleje el fondo gris y la sombra en la sección */
    .select2-container--bootstrap-5 .select2-selection {
        background-color: #f8f9fa !important;
        border: 1px solid #cbd5e1 !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        border-radius: 0.375rem !important;
        min-height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
    }
    .select2-container--bootstrap-5.select2-container--open .select2-selection {
        background-color: #ffffff !important;
        border-color: #86b7fe !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
    }
</style>
@endpush

@if($persona->titulacionPersona && !$persona->inscripcionActiva)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const $selectSeccion = $('#select_seccion');
    const $inputSeccionHidden = $('#id_seccion_hidden');
    const $btnInscribir = $('#btn_inscribir');

    function inicializarSelectSeccionUnico() {
        if ($selectSeccion.length && !$selectSeccion.prop('disabled')) {
            if ($selectSeccion.data('select2')) {
                $selectSeccion.select2('destroy');
            }

            // Inicialización con el tema de Bootstrap 5 y control de contenedor
            $selectSeccion.select2({
                width: '100%',
                theme: 'bootstrap-5',
                placeholder: 'Seleccione una sección...',
                dropdownParent: $('#card_inscripcion_container')
            });
        }
    }

    // Retardo prudente para asegurar el pintado correcto de Bootstrap Tabs
    setTimeout(inicializarSelectSeccionUnico, 200);

    // Re-vincular al cambiar de pestaña
    $('button[data-bs-toggle="tab"], a[data-bs-toggle="tab"], button[data-bs-toggle="pill"], a[data-bs-toggle="pill"]').on('shown.bs.tab shown.bs.pill', function () {
        setTimeout(inicializarSelectSeccionUnico, 100);
    });

    // Evento change robusto para sincronizar el input oculto y habilitar el botón
    $selectSeccion.on('change', function () {
        const valor = $(this).val();
        $inputSeccionHidden.val(valor);
        
        if (valor) {
            $btnInscribir.prop('disabled', false);
        } else {
            $btnInscribir.prop('disabled', true);
        }
    });
});
</script>
@endpush
@endif