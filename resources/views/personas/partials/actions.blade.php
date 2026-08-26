<div class="btn-group" role="group" aria-label="Acciones de gestión">
    
    <!-- Botón VER EXPEDIENTE (Show) -->
    <a href="{{ route('personas.show', $persona->id_personas) }}" class="btn btn-outline-secondary" title="Ver Expediente Académico">
        <i class="bi bi-eye"></i>
    </a>

    <!-- Botón EDITAR DATOS (Edit) -->
    <a href="{{ route('personas.edit', $persona->id_personas) }}" class="btn btn-outline-secondary" title="Modificar Datos Básicos">
        <i class="bi bi-pencil"></i>
    </a>

    <!-- Botón ELIMINAR -->
    <button type="submit" form="form-delete-{{ $persona->id_personas }}" class="btn btn-outline-secondary" title="Eliminar Estudiante">
        <i class="bi bi-trash"></i>
    </button>
</div>

<form id="form-delete-{{ $persona->id_personas }}" action="{{ route('personas.destroy', $persona->id_personas) }}" method="POST" class="d-none form-delete">
    @csrf
    @method('DELETE')
</form>