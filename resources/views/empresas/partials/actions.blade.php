<div class="d-flex justify-content-center">
    <button type="button" class="btn btn-tabla btn-outline-secondary me-1" title="Modificar Empresa"
        data-bs-toggle="modal" 
        data-bs-target="#UpdateEmpresaModal"
        data-url="{{ route('empresas.update', $empresa->id_empresa) }}"
        data-id="{{ $empresa->id_empresa }}"
        data-nombre="{{ $empresa->nombre_empresa }}">
        <i class="bi bi-pencil"></i>
    </button>

    <form action="{{ route('empresas.destroy', $empresa->id_empresa) }}" method="POST" class="m-0 p-0 d-inline-block form-eliminar">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-tabla btn-outline-secondary" title="Eliminar Empresa">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</div>