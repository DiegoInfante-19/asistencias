<div class="btn-group" role="group" aria-label="Acciones de gestión">
    
    <!-- Botón VER EXPEDIENTE (Show) -->
    <a href="{{ route('personas.show', $persona->id_personas) }}" class="btn btn-outline-secondary" title="Ver Expediente Académico">
        <i class="bi bi-eye"></i>
    </a>

    <!-- Botón EDITAR DATOS (Edit - Redirige a la vista completa) -->
    <a href="{{ route('personas.edit', $persona->id_personas) }}" class="btn btn-outline-secondary" title="Modificar Datos Básicos">
        <i class="bi bi-pencil"></i>
    </a>

    <!-- Botón ELIMINAR (Dispara el submit del form oculto asociado) -->
    <button type="submit" form="form-delete-{{ $persona->id_personas }}" class="btn btn-outline-secondary" title="Eliminar Estudiante">
        <i class="bi bi-trash"></i>
    </button>
</div>

<!-- Formulario oculto individual para procesar la eliminación -->
<form id="form-delete-{{ $persona->id_personas }}" action="{{ route('personas.destroy', $persona->id_personas) }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>