<div class="btn-group" role="group" aria-label="Acciones de gestión">
    
    <a href="{{ route('usuarios.show', $user->id_users) }}" class="btn btn-outline-secondary" title="Ver Detalles">
        <i class="bi bi-eye"></i>
    </a>

    <!-- NUEVO BOTÓN: Cambiar Contraseña -->
    <button type="button" class="btn btn-outline-secondary" title="Cambiar Contraseña"
        data-bs-toggle="modal"
        data-bs-target="#changePasswordModal"
        data-url="{{ route('usuarios.cambiar_password', $user->id_users) }}"
        data-username="{{ $user->username }}"
        data-name="{{ trim($user->name_users) }}"
        data-lastname="{{ trim($user->last_name_users) }}">
        <i class="bi bi-key"></i>
    </button>

    <button type="button" class="btn btn-outline-secondary" title="Modificar Profesor"
        data-bs-toggle="modal"
        data-bs-target="#editUserModal"
        data-url="{{ route('usuarios.update', $user->id_users) }}"
        data-username="{{ $user->username }}"
        data-name="{{ trim($user->name_users) }}"
        data-lastname="{{ trim($user->last_name_users) }}"
        data-cedula="{{ trim($user->cedula_users) }}"
        data-email="{{ trim($user->email_users) }}"
        data-phone="{{ trim($user->phone_users) }}"
        data-status="{{ $user->status_users }}"
        data-rol="{{ $user->id_rol }}"> 
        <i class="bi bi-pencil"></i>
    </button>

    <button type="button" 
        class="btn btn-outline-secondary btn-delete-record" 
        data-form-id="form-delete-{{ $user->id_users }}" 
        title="Eliminar Profesor">
        <i class="bi bi-trash"></i>
    </button>
</div>

<form id="form-delete-{{ $user->id_users }}" action="{{ route('usuarios.destroy', $user->id_users) }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>