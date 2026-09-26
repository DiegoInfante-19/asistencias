<div class="btn-group shadow-sm" role="group" aria-label="Acciones de gestión de sección">
    
    {{-- 1. Botón Gestionar Estudiantes (Nivel 3) --}}
    <a href="{{ route('secciones.show', $seccion->id_seccion) }}" 
       class="btn btn-outline-secondary" 
       title="Ver y Gestionar Estudiantes">
        <i class="bi bi-eye"></i>
    </a>

    {{-- 2. Botón Editar (Abre Modal) --}}
    <button type="button"
        class="btn btn-outline-secondary"
        data-bs-toggle="modal"
        data-bs-target="#modalEditarSeccion"
        data-id="{{ $seccion->id_seccion }}"
        data-pnf="{{ $seccion->id_pnf }}"
        data-pnf-nombre="{{ $seccion->pnf->nombre_pnf ?? '' }}"
        data-nombre="{{ $seccion->nombre_seccion }}"
        data-estatus="{{ $seccion->estatus_seccion }}"
        title="Modificar Sección">
        <i class="bi bi-pencil"></i>
    </button>

    {{-- 3. Botón Eliminar --}}
    <button type="submit"
        form="form-delete-seccion-{{ $seccion->id_seccion }}"
        class="btn btn-outline-secondary"
        title="Eliminar Sección">
        <i class="bi bi-trash"></i>
    </button>

</div>

{{-- Formulario oculto para eliminar --}}
<form id="form-delete-seccion-{{ $seccion->id_seccion }}" action="{{ route('secciones.destroy', $seccion->id_seccion) }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>