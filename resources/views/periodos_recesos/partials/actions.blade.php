<div class="btn-group" role="group">
    <button type="button" class="btn btn-tabla btn-outline-secondary" title="Ver Detalles"
        data-bs-toggle="modal"
        data-bs-target="#showPeriodoModal"
        data-nombre="{{ $periodo->nombre_periodo_receso }}"
        data-inicio-format="{{ $periodo->fecha_inicio_periodo_receso ? $periodo->fecha_inicio_periodo_receso->format('d/m/Y') : '' }}"
        data-fin-format="{{ $periodo->fecha_fin_periodo_receso ? $periodo->fecha_fin_periodo_receso->format('d/m/Y') : '' }}"
        data-descripcion="{{ $periodo->descripcion_periodo_receso }}"
        data-nivel="{{ $periodo->nivel_periodo_receso }}"
        data-suspension="{{ $periodo->suspension_actividades ? 1 : 0 }}">
        <i class="bi bi-eye"></i>
    </button>

    <button type="button" class="btn btn-tabla btn-outline-secondary" title="Modificar Periodo"
        data-bs-toggle="modal"
        data-bs-target="#UpdatePeriodoModal"
        data-url="{{ route('periodos_recesos.update', $periodo->id_periodo_receso) }}"
        data-nombre="{{ $periodo->nombre_periodo_receso }}"
        data-inicio="{{ $periodo->fecha_inicio_periodo_receso ? $periodo->fecha_inicio_periodo_receso->format('Y-m-d') : '' }}"
        data-fin="{{ $periodo->fecha_fin_periodo_receso ? $periodo->fecha_fin_periodo_receso->format('Y-m-d') : '' }}"
        data-descripcion="{{ $periodo->descripcion_periodo_receso }}"
        data-nivel="{{ $periodo->nivel_periodo_receso }}"
        data-suspension="{{ $periodo->suspension_actividades ? 1 : 0 }}">
        <i class="bi bi-pencil"></i>
    </button>

    <form action="{{ route('periodos_recesos.destroy', $periodo->id_periodo_receso) }}" method="POST" class="m-0 p-0 d-inline-block form-eliminar">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-tabla btn-outline-secondary" style="border-top-left-radius: 0; border-bottom-left-radius: 0; margin-left: -1px;" title="Eliminar Periodo">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</div>