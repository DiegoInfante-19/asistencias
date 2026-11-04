<div class="btn-group shadow-sm" role="group" aria-label="Acciones de gestión de sección">
    
    {{-- 1. Botón Gestionar Estudiantes (Ver) --}}
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
        data-pnfs-json="{{ json_encode($seccion->pnfs->pluck('id_pnf')->toArray()) }}"
        data-nombre="{{ $seccion->nombre_seccion }}"
        data-estatus="{{ $seccion->estatus_seccion }}"
        title="Modificar Sección">
        <i class="bi bi-pencil"></i>
    </button>

    {{-- 3. Botón Eliminar --}}
    <button type="button"
        class="btn btn-outline-secondary btn-delete-record"
        data-form-id="form-delete-seccion-{{ $seccion->id_seccion }}"
        title="Eliminar Sección">
        <i class="bi bi-trash"></i>
    </button>

</div>

{{-- Formulario oculto para eliminar --}}
<form id="form-delete-seccion-{{ $seccion->id_seccion }}" action="{{ route('secciones.destroy', $seccion->id_seccion) }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>