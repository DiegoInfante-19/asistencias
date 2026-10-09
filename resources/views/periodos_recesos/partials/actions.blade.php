@php
    $inicio = $periodo->fecha_inicio_periodo_receso;
    $fin = $periodo->fecha_fin_periodo_receso;
    $fechaHumana = 'Fechas no definidas';

    if ($inicio && $fin) {
        $inicio->locale('es');
        $fin->locale('es');

        if ($inicio->isSameDay($fin)) {
            $fechaHumana = ucfirst($inicio->isoFormat('D [de] MMMM [de] YYYY'));
        } elseif ($inicio->isSameMonth($fin) && $inicio->isSameYear($fin)) {
            $fechaHumana = 'Del ' . $inicio->format('d') . ' al ' . $fin->isoFormat('D [de] MMMM [de] YYYY');
        } elseif ($inicio->isSameYear($fin)) {
            $fechaHumana = 'Del ' . $inicio->isoFormat('D [de] MMMM') . ' al ' . $fin->isoFormat('D [de] MMMM [de] YYYY');
        } else {
            $fechaHumana = 'Del ' . $inicio->isoFormat('D [de] MMMM [de] YYYY') . ' al ' . $fin->isoFormat('D [de] MMMM [de] YYYY');
        }
    }
@endphp

<div class="btn-group" role="group" aria-label="Acciones de gestión">
    
    <button type="button" class="btn btn-outline-secondary" title="Ver Detalles"
        data-bs-toggle="modal"
        data-bs-target="#showPeriodoModal"
        data-nombre="{{ $periodo->nombre_periodo_receso }}"
        data-fecha-humana="{{ $fechaHumana }}"
        data-descripcion="{{ $periodo->descripcion_periodo_receso }}"
        data-nivel="{{ $periodo->nivel_periodo_receso }}"
        data-suspension="{{ $periodo->suspension_actividades ? 1 : 0 }}">
        <i class="bi bi-eye"></i>
    </button>

    <button type="button" class="btn btn-outline-secondary" title="Modificar Periodo"
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

    <button type="submit" form="form-delete-{{ $periodo->id_periodo_receso }}" class="btn btn-outline-secondary" title="Eliminar Periodo">
        <i class="bi bi-trash"></i>
    </button>
</div>

<form id="form-delete-{{ $periodo->id_periodo_receso }}" action="{{ route('periodos_recesos.destroy', $periodo->id_periodo_receso) }}" method="POST" class="d-none">
    @csrf 
    @method('DELETE')
</form>