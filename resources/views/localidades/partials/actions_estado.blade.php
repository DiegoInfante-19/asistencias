<div class="btn-group" role="group" aria-label="Acciones de gestión">

    <button type="button" class="btn btn-outline-secondary" title="Modificar Estado"
        data-bs-toggle="modal"
        data-bs-target="#UpdateEstateModal"
        data-url="{{ route('localidades.updateEstado', $estado->id_estado) }}"
        data-id="{{ $estado->id_estado }}"
        data-nombre="{{ $estado->nombre_estado }}">
        <i class="bi bi-pencil"></i>
    </button>

    <button type="submit" form="form-delete-{{ $estado->id_estado }}" class="btn btn-outline-secondary" title="Eliminar Estado">
        <i class="bi bi-trash"></i>
    </button>
</div>

<form id="form-delete-{{ $estado->id_estado }}" action="{{ route('localidades.destroyEstado', $estado->id_estado) }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>