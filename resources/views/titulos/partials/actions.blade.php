<div class="btn-group" role="group" aria-label="Acciones de gestión">

    <button type="button" class="btn btn-outline-secondary" title="Modificar Título"
        data-bs-toggle="modal" 
        data-bs-target="#UpdateTituloModal"
        data-url="{{ route('titulos.update', $titulo->id_titulos) }}"
        data-nombre="{{ $titulo->nombre_titulo_base }}"
        data-nivel="{{ $titulo->nivel_academico }}">
        <i class="bi bi-pencil"></i>
    </button>

    <button type="submit" form="form-delete-{{ $titulo->id_titulos }}" class="btn btn-outline-secondary" title="Eliminar Título">
        <i class="bi bi-trash"></i>
    </button>
</div>

<form id="form-delete-{{ $titulo->id_titulos }}" action="{{ route('titulos.destroy', $titulo->id_titulos) }}" method="POST" class="d-none">
    @csrf 
    @method('DELETE')
</form>