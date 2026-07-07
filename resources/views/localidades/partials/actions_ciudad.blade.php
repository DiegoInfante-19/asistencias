<div class="btn-group" role="group" aria-label="Acciones de gestión">

    <button type="button" class="btn btn-outline-secondary" title="Modificar Ciudad"
        data-bs-toggle="modal"
        data-bs-target="#UpdateCiudadModal"
        data-url="{{ route('localidades.updateCiudad', $ciudad->id_ciudad) }}"
        data-id="{{ $ciudad->id_ciudad }}"
        data-nombre="{{ $ciudad->nombre_ciudad }}"
        data-id-estado="{{ $ciudad->id_estado }}">
        <i class="bi bi-pencil"></i>
    </button>

    <button type="submit" form="form-delete-{{ $ciudad->id_ciudad }}" class="btn btn-outline-secondary" title="Eliminar Ciudad">
        <i class="bi bi-trash"></i>
    </button>
</div>

<form id="form-delete-{{ $ciudad->id_ciudad }}" action="{{ route('localidades.destroyCiudad', $ciudad->id_ciudad) }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>