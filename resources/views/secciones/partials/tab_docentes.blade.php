<div class="card border border-warning-subtle shadow-sm mb-4">
    <div class="card-body bg-light rounded d-flex flex-column flex-md-row align-items-md-end gap-3 p-3">
        <div class="flex-grow-1">
            <label class="form-label fw-bold text-dark mb-1"><i class="bi bi-person-badge me-1 text-warning"></i> Asignar Docente a la Sección</label>
            <form id="formDocente" action="{{ route('secciones.asignar-profesor', $seccion->id_seccion) }}" method="POST">
                @csrf
                <select name="id_profesor" class="form-select select2-profesores" required>
                    <option value="" selected disabled>Buscar profesor disponible (Cédula o Nombre)...</option>
                    @foreach($profesoresDisponibles as $profeDisp)
                    <option value="{{ $profeDisp->id_profesor }}">
                        V-{{ ltrim($profeDisp->user->cedula_users, 'V-') }} — {{ $profeDisp->user->name_users }} {{ $profeDisp->user->last_name_users }} (PNF: {{ $profeDisp->pnf->nombre_pnf ?? 'Sin PNF' }})
                    </option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="mt-2 mt-md-0">
            <button type="submit" form="formDocente" class="btn btn-warning text-dark fw-bold px-4 py-2 h-100 shadow-sm w-100">
                <i class="bi bi-plus-circle me-1"></i> Asignar
            </button>
        </div>
    </div>
</div>
<div class="card border shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark fs-6"><i class="bi bi-briefcase me-2 text-warning"></i>Carga Docente de la Sección</h5>
    </div>
    <div class="card-body bg-white p-3">
        <div class="table-responsive">
            {{ $profesorDataTable->html()->table(['class' => 'table table-hover table-striped align-middle border w-100']) }}
        </div>
    </div>
</div>