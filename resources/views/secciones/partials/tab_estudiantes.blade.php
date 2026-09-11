<div class="card border border-primary-subtle shadow-sm mb-4">
    <div class="card-body bg-light rounded d-flex flex-column flex-md-row align-items-md-end gap-3 p-3">
        <div class="flex-grow-1">
            <label class="form-label fw-bold text-dark mb-1"><i class="bi bi-person-plus me-1 text-success"></i> Añadir Estudiante a la Sección</label>
            <form id="formMatricular" action="{{ route('secciones.inscribir', $seccion->id_seccion) }}" method="POST">
                @csrf
                <select name="id_personas" class="form-select select2-buscador" required>
                    <option value="" selected disabled>Buscar estudiante disponible (Cédula o Nombre)...</option>
                    @foreach($estudiantesDisponibles as $estudiante)
                    <option value="{{ $estudiante->id_personas }}">
                        V-{{ ltrim($estudiante->cedula_personas, 'V-') }} - {{ $estudiante->nombre_corto }} - {{ $estudiante->cohorte->numero_cohorte ?? 'Externa' }} - {{ $estudiante->titulo_base }}
                    </option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="mt-2 mt-md-0">
            <button type="submit" form="formMatricular" class="btn btn-success fw-bold px-4 py-2 h-100 shadow-sm w-100">
                <i class="bi bi-plus-circle me-1"></i> Inscribir
            </button>
        </div>
    </div>
</div>
<div class="card border shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark fs-6"><i class="bi bi-table me-2 text-primary"></i>Nómina Activa</h5>
    </div>
    <div class="card-body bg-white p-3">
        <div class="table-responsive">
            {{ $dataTable->html()->table(['class' => 'table table-hover table-striped align-middle border w-100']) }}
        </div>
    </div>
</div>