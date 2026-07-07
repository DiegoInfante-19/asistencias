<div class="btn-group" role="group" aria-label="Acciones de gestión">

    <button type="button" class="btn btn-outline-secondary" title="Modificar Cohorte"
        data-bs-toggle="modal"
        data-bs-target="#UpdateCohorteModal"
        data-url="{{ route('cohortes.update', $cohorte->id_cohortes) }}"
        data-numero="{{ $cohorte->numero_cohorte }}"
        data-inicio="{{ $cohorte->fecha_inicio_cohorte }}"
        data-fin="{{ $cohorte->fecha_fin_cohorte }}"
        data-descripcion="{{ $cohorte->descripcion_cohorte }}"
        data-estatus="{{ $cohorte->estatus_cohorte }}">
        <i class="bi bi-pencil"></i>
    </button>

    <button type="submit" form="form-delete-{{ $cohorte->id_cohortes }}" class="btn btn-outline-secondary" title="Eliminar Cohorte">
        <i class="bi bi-trash"></i>
    </button>
</div>

<form id="form-delete-{{ $cohorte->id_cohortes }}" action="{{ route('cohortes.destroy', $cohorte->id_cohortes) }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>