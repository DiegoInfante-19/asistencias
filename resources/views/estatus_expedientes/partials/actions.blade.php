<div class="d-flex justify-content-center">
    <button type="button" class="btn btn-tabla btn-outline-secondary me-1" title="Modificar Estatus"
        data-bs-toggle="modal" 
        data-bs-target="#UpdateEstatusModal"
        data-url="{{ route('estatus_expedientes.update', $estatus->id_estatus_expediente) }}"
        data-nombre="{{ $estatus->nombre_estatus_expediente }}">
        <i class="bi bi-pencil"></i>
    </button>

    <form action="{{ route('estatus_expedientes.destroy', $estatus->id_estatus_expediente) }}" method="POST" class="m-0 p-0 d-inline-block form-eliminar">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-tabla btn-outline-secondary" title="Eliminar Estatus">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</div>