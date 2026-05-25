<div class="btn-group " role="group" aria-label="Acciones de gestión">

    <button type="button" class="btn btn-tabla btn-outline-secondary" title="Ver Detalles" style="background-color: #ffffff;"
        data-bs-toggle="modal"
        data-bs-target="#viewUserModal"
        data-username="{{ $user->username }}"
        data-name="{{ $user->name }}"
        data-lastname="{{ $user->last_name }}"
        data-cedula="{{ $user->cedula }}"
        data-email="{{ $user->email }}"
        data-phone="{{ $user->phone ?? 'No registrado' }}"
        data-status="{{ $user->status }}">
        <i class="bi bi-eye"></i>
    </button>

    <button type="button" class="btn btn-tabla btn-outline-secondary" title="Modificar Profesor" style="background-color: #ffffff;"
        data-bs-toggle="modal"
        data-bs-target="#editUserModal"
        data-url="{{ route('usuarios.update', $user->id_users) }}"
        data-name="{{ $user->name }}"
        data-lastname="{{ $user->last_name }}"
        data-cedula="{{ $user->cedula }}"
        data-email="{{ $user->email }}"
        data-phone="{{ $user->phone }}"
        data-status="{{ $user->status }}">
        <i class="bi bi-pencil"></i>
    </button>

    <form action="{{ route('usuarios.destroy', $user->id_users) }}" method="POST" class="m-0 p-0 d-inline-block" onsubmit="return confirm('¿Estás totalmente seguro de eliminar a este profesor del sistema?');">
        @csrf
        @method('DELETE')

        <button type="submit" class="btn btn-tabla btn-outline-secondary" title="Eliminar Profesor" style="border-top-left-radius: 0; border-bottom-left-radius: 0; margin-left: -1px;background-color: #ffffff;">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</div>