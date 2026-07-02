<div class="d-flex justify-content-center">
    <button type="button" class="btn btn-tabla btn-outline-secondary me-1" title="Modificar Cohorte"
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

    <form action="{{ route('cohortes.destroy', $cohorte->id_cohortes) }}" method="POST" class="m-0 p-0 d-inline-block form-eliminar">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-tabla btn-outline-secondary" title="Eliminar Cohorte">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</div>