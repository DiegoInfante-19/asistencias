<div class="btn-group" role="group" aria-label="Acciones de gestión">
    
    <a href="{{ route('usuarios.show', $user->id_users) }}" class="btn btn-outline-secondary" title="Ver Detalles">
        <i class="bi bi-eye"></i>
    </a>

    <button type="button" class="btn btn-outline-secondary" title="Modificar Profesor"
        data-bs-toggle="modal"
        data-bs-target="#editUserModal"
        data-url="{{ route('usuarios.update', $user->id_users) }}"
        data-username="{{ $user->username }}"
        data-name="{{ $user->name_users }}"
        data-lastname="{{ $user->last_name_users }}"
        data-cedula="{{ $user->cedula_users }}"
        data-email="{{ $user->email_users }}"
        data-phone="{{ $user->phone_users }}"
        data-status="{{ $user->status_users }}"
        data-rol="{{ $user->id_rol }}"> <!-- NUEVO DATO -->
        <i class="bi bi-pencil"></i>
    </button>

    <button type="submit" form="form-delete-{{ $user->id_users }}" class="btn btn-outline-secondary" title="Eliminar Profesor">
        <i class="bi bi-trash"></i>
    </button>
</div>

<form id="form-delete-{{ $user->id_users }}" action="{{ route('usuarios.destroy', $user->id_users) }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>

<!-- <div class=" " role=" " aria-label="Acciones de gestión">
    
    <a href="{{ route('usuarios.show', $user->id_users) }}" class="btn btn-outline-secondary" title="Ver Detalles">
        <i class="bi bi-eye"></i>
    </a>

    <button type="button" class="btn btn-outline-secondary " title="Modificar Profesor"
        data-bs-toggle="modal"
        data-bs-target="#editUserModal"
        data-url="{{ route('usuarios.update', $user->id_users) }}"
        data-username="{{ $user->username }}"
        data-name="{{ $user->name_users }}"
        data-lastname="{{ $user->last_name_users }}"
        data-cedula="{{ $user->cedula_users }}"
        data-email="{{ $user->email_users }}"
        data-phone="{{ $user->phone_users }}"
        data-status="{{ $user->status_users }}">
        <i class="bi bi-pencil"></i>
    </button>

    <form action="{{ route('usuarios.destroy', $user->id_users) }}" method="POST" class="m-0 p-0 d-inline-block form-eliminar">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-secondary  " title="Eliminar Profesor">
            <i class="bi bi-trash"></i>
        </button>
    </form>
    
</div> -->


 <!-- <button type="button" class="btn btn-tabla btn-outline-secondary" title="Ver Detalles" style="background-color: #ffffff;"
        data-bs-toggle="modal"
        data-bs-target="#viewUserModal"
        data-username="{{ $user->username }}"
        data-name="{{ $user->name_users }}"
        data-lastname="{{ $user->last_name_users }}"
        data-cedula="{{ $user->cedula_users }}"
        data-email="{{ $user->email_users }}"
        data-phone="{{ $user->phone_users ?? 'No registrado' }}"
        data-status="{{ $user->status_users }}">
        <i class="bi bi-eye"></i>
    </button> -->