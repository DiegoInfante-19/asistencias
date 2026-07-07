<div class="btn-group" role="group" aria-label="Acciones de gestión">

    <button type="button" class="btn btn-outline-secondary" title="Modificar Empresa"
        data-bs-toggle="modal" 
        data-bs-target="#UpdateEmpresaModal"
        data-url="{{ route('empresas.update', $empresa->id_empresa) }}"
        data-id="{{ $empresa->id_empresa }}"
        data-nombre="{{ $empresa->nombre_empresa }}">
        <i class="bi bi-pencil"></i>
    </button>

    <button type="submit" form="form-delete-{{ $empresa->id_empresa }}" class="btn btn-outline-secondary" title="Eliminar Empresa">
        <i class="bi bi-trash"></i>
    </button>
</div>

<form id="form-delete-{{ $empresa->id_empresa }}" action="{{ route('empresas.destroy', $empresa->id_empresa) }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>