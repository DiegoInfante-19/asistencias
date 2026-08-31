<div class="btn-group" role="group" aria-label="Acciones">
    <!-- Botón para Editar (Abre Modal y pasa datos) -->
    <button type="button" class="btn btn-outline-secondary btn-sm" title="Modificar Período"
        data-bs-toggle="modal"
        data-bs-target="#editPeriodoModal"
        data-url="{{ route('periodos-academicos.update', $periodo->id_periodo) }}"
        data-cohorte="{{ $periodo->id_cohortes }}"
        data-inicio="{{ \Carbon\Carbon::parse($periodo->fecha_inicio)->format('Y-m-d') }}"
        data-fin="{{ \Carbon\Carbon::parse($periodo->fecha_fin)->format('Y-m-d') }}"
        data-estatus="{{ $periodo->estatus_periodo }}">
        <i class="bi bi-pencil"></i>
    </button>

    <!-- Botón para Eliminar -->
    <form action="{{ route('periodos-academicos.destroy', $periodo->id_periodo) }}" method="POST" class="d-inline form-delete" onsubmit="return confirm('¿Está seguro de eliminar este período académico?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger btn-sm" title="Eliminar Período">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</div>