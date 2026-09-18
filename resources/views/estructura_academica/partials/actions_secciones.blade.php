{{-- Botón Editar (Icono de Lápiz - Abre Modal de Sección) --}}
<button type="button"
    class="btn btn-sm btn-outline-secondary shadow-sm me-1"
    data-bs-toggle="modal"
    data-bs-target="#modalEditarSeccion"
    data-id="{{ $seccion->id_seccion }}"
    data-pnf="{{ $seccion->id_pnf }}"
    data-pnf-nombre="{{ $seccion->pnf->nombre_pnf ?? '' }}"
    data-nombre="{{ $seccion->nombre_seccion }}"
    data-estatus="{{ $seccion->estatus_seccion }}"
    title="Editar Sección">
    <i class="bi bi-pencil"></i>
</button>

{{-- Botón Eliminar (Icono de Papelera) --}}
<button type="submit"
    form="form-delete-seccion-{{ $seccion->id_seccion }}"
    class="btn btn-sm btn-outline-secondary shadow-sm"
    title="Eliminar Sección">
    <i class="bi bi-trash"></i>
</button>

{{-- Formulario oculto para eliminar --}}
<form id="form-delete-seccion-{{ $seccion->id_seccion }}" action="{{ route('secciones.destroy', $seccion->id_seccion) }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>