<div class="btn-group" role="group">
    <button type="button" class="btn btn-tabla btn-outline-secondary"
        data-bs-toggle="modal"
        data-bs-target="#UpdateEstateModal"
        data-url="{{ route('localidades.updateEstado', $estado->id_estado) }}"
        data-id="{{ $estado->id_estado }}"
        data-nombre="{{ $estado->nombre_estado }}">
        <i class="bi bi-pencil"></i>
    </button>

    <form action="{{ route('localidades.destroyEstado', $estado->id_estado) }}" method="POST" class="m-0 p-0 d-inline-block form-eliminar">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-tabla btn-outline-secondary" title="Eliminar Estado" style="border-top-left-radius: 0; border-bottom-left-radius: 0; margin-left: -1px; background-color: #ffffff;">
        <i class="bi bi-trash"></i>
    </button>
</form>
</div>