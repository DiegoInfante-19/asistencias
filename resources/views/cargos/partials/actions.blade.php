<div class="btn-group" role="group" aria-label="Acciones de gestión">

    <button type="button" class="btn btn-outline-secondary" title="Modificar Cargo"
        data-bs-toggle="modal"
        data-bs-target="#UpdateCargoModal"
        data-url="{{ route('cargos.update', $cargo->id_cargo) }}"
        data-descripcion="{{ $cargo->descripcion_cargo }}">
        <i class="bi bi-pencil"></i>
    </button>

    <button type="submit" form="form-delete-{{ $cargo->id_cargo }}" class="btn btn-outline-secondary" title="Eliminar Cargo">
        <i class="bi bi-trash"></i>
    </button>
</div>

<form id="form-delete-{{ $cargo->id_cargo }}" action="{{ route('cargos.destroy', $cargo->id_cargo) }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>