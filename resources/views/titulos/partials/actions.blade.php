<div class="d-flex justify-content-center">
    <button type="button" class="btn btn-tabla btn-outline-secondary me-1" title="Modificar Título"
        data-bs-toggle="modal" 
        data-bs-target="#UpdateTituloModal"
        data-url="{{ route('titulos.update', $titulo->id_titulos) }}"
        data-nombre="{{ $titulo->nombre_titulo_base }}"
        data-nivel="{{ $titulo->nivel_academico }}">
        <i class="bi bi-pencil"></i>
    </button>

    <form action="{{ route('titulos.destroy', $titulo->id_titulos) }}" method="POST" class="m-0 p-0 d-inline-block form-eliminar">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-tabla btn-outline-secondary" title="Eliminar Título">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</div>