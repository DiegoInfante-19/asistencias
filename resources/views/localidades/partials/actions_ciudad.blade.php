<div class="btn-group" role="group">
    <button type="button" class="btn btn-tabla btn-outline-secondary" title="Modificar Ciudad"
        data-bs-toggle="modal"
        data-bs-target="#UpdateCiudadModal"
        data-url="{{ route('localidades.updateCiudad', $ciudad->id_ciudad) }}"
        data-id="{{ $ciudad->id_ciudad }}"
        data-nombre="{{ $ciudad->nombre_ciudad }}"
        data-id-estado="{{ $ciudad->id_estado }}"> <i class="bi bi-pencil"></i>
    </button>

    <form action="{{ route('localidades.destroyCiudad', $ciudad->id_ciudad) }}" method="POST" class="m-0 p-0 d-inline-block form-eliminar">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-tabla btn-outline-secondary" title="Eliminar Ciudad" style="border-top-left-radius: 0; border-bottom-left-radius: 0; margin-left: -1px; background-color: #ffffff;">
        <i class="bi bi-trash"></i>
    </button>
</form>
</div>