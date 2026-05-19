<div class="btn-group" role="group" aria-label="Acciones de Usuario">
    <!-- Botón Ver Detalles -->
    <a href="{{ route('usuarios.show', $user->id) }}" class="btn btn-sm btn-outline-secondary border-0" title="Ver Detalles">
        <i class="fas fa-eye text-info"></i>
    </a>
    
    <!-- Botón Editar -->
    <a href="{{ route('usuarios.edit', $user->id) }}" class="btn btn-sm btn-outline-secondary border-0" title="Editar Registro">
        <i class="fas fa-edit text-warning"></i>
    </a>
</div>