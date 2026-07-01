<div class="d-flex justify-content-center">
    <button type="button" class="btn btn-tabla btn-outline-secondary me-1" title="Modificar Cargo"
        data-bs-toggle="modal" 
        data-bs-target="#UpdateCargoModal"
        data-url="{{ route('cargos.update', $cargo->id_cargo) }}"
        data-descripcion="{{ $cargo->descripcion_cargo }}">
        <i class="bi bi-pencil"></i>
    </button>

    <form action="{{ route('cargos.destroy', $cargo->id_cargo) }}" method="POST" class="m-0 p-0 d-inline-block form-eliminar">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-tabla btn-outline-secondary" title="Eliminar Cargo">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</div>