@if($seccion->estaActiva())
<div class="card border border-primary-subtle shadow-sm mb-4">
    <div class="card-body bg-light rounded d-flex flex-column flex-md-row align-items-md-end gap-3 p-3">
        <div class="flex-grow-1">
            <label class="form-label fw-bold text-dark mb-1">Añadir Estudiante a la Sección</label>
            
            <!-- CANDADO VISUAL 3.4: Si no hay estudiantes disponibles, ocultar formulario y mostrar info -->
            @if($estudiantesDisponibles->isNotEmpty())
                <form id="formMatricular" action="{{ route('secciones.inscribir', $seccion->id_seccion) }}" method="POST">
                    @csrf
                    <select name="id_personas" class="form-select select2-buscador" required>
                        <option value="" selected disabled>Buscar estudiante disponible (Cédula o Nombre)...</option>
                        @foreach($estudiantesDisponibles as $estudiante)
                        <option value="{{ $estudiante->id_personas }}">
                            V-{{ ltrim($estudiante->cedula_personas, 'V-') }} - {{ $estudiante->nombre_corto }} - [{{ $estudiante->titulacionPersona->pnf->nombre_pnf ?? 'PNF' }}] - {{ $estudiante->titulo_base }}
                        </option>
                        @endforeach
                    </select>
                </form>
            @else
                <div class="alert alert-warning mb-0 py-2 small shadow-sm">
                    <i class="bi bi-info-circle me-1"></i> No hay estudiantes disponibles para matricular en los PNF de esta sección (todos se encuentran cursando en otra sección activa o marcados como "No Continúa").
                </div>
            @endif
        </div>
        <div class="mt-2 mt-md-0">
            <!-- Bloquear botón si la colección está vacía -->
            <button type="submit" form="formMatricular" class="btn btn-primary shadow-sm" {{ $estudiantesDisponibles->isEmpty() ? 'disabled' : '' }}>
                <i class="bi bi-plus-circle me-1"></i> Inscribir
            </button>
        </div>
    </div>
</div>
@else
<div class="alert alert-secondary d-flex align-items-center mb-4 shadow-sm" role="alert">
    <i class="bi bi-lock-fill fs-4 me-2"></i>
    <div>
        <strong>Sección Inactiva:</strong> El formulario de inscripción de estudiantes se encuentra bloqueado para preservar el histórico académico.
    </div>
</div>
@endif

<div class="card border shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark fs-6">Nómina Activa</h5>
    </div>
    <div class="card-body bg-white p-3">
        <div class="table-responsive">
            {{ $dataTable->html()->table(['class' => 'table table-hover table-striped align-middle border w-100']) }}
        </div>
    </div>
</div>