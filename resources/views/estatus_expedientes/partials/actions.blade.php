<div class="btn-group" role="group" aria-label="Acciones de gestión">

    <button type="button" class="btn btn-outline-secondary" title="Modificar Estatus"
        data-bs-toggle="modal" 
        data-bs-target="#UpdateEstatusModal"
        data-url="{{ route('estatus_expedientes.update', $estatus->id_estatus_expediente) }}"
        data-nombre="{{ $estatus->nombre_estatus_expediente }}">
        <i class="bi bi-pencil"></i>
    </button>

    <button type="submit" form="form-delete-{{ $estatus->id_estatus_expediente }}" class="btn btn-outline-secondary" title="Eliminar Estatus">
        <i class="bi bi-trash"></i>
    </button>
</div>

<form id="form-delete-{{ $estatus->id_estatus_expediente }}" action="{{ route('estatus_expedientes.destroy', $estatus->id_estatus_expediente) }}" method="POST" class="d-none">
    @csrf 
    @method('DELETE')
</form>