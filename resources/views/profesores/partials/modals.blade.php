<div class="modal fade" id="viewUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold" id="viewUserModalLabel">
                    Ficha del Profesor
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 pb-3 border-bottom gap-3">
                    <div class="d-flex align-items-center">
                        <div class="me-4">
                            <i class="bi bi-person-circle text-dark" style="font-size: 5.5rem; line-height: 1;"></i>
                        </div>
                        <div>
                            <div class="fs-3 fw-bold text-dark" id="modal-username"></div>
                            <div class="fs-5 text-primary mb-2" id="modal-email"></div>
                            <div id="modal-status"></div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-tabla">
                            <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-tabla">
                            <i class="bi bi-file-earmark-excel me-1"></i> Excel
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-tabla">
                            <i class="bi bi-printer me-1"></i> Imprimir
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-tabla">
                            <i class="bi bi-clipboard me-1"></i> Copiar
                        </button>
                    </div>
                </div>
                <div class="row g-4 text-dark mt-2">
                    <div class="col-md-3">
                        <label class="text-muted small fw-bold text-uppercase">Nombres</label>
                        <div class="fs-5" id="modal-name"></div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small fw-bold text-uppercase">Apellidos</label>
                        <div class="fs-5" id="modal-lastname"></div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small fw-bold text-uppercase">Cédula</label>
                        <div class="fs-5" id="modal-cedula"></div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small fw-bold text-uppercase">Teléfono</label>
                        <div class="fs-5" id="modal-phone"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom text-dark">
                <h1 class="modal-title fs-5 fw-bold" id="editUserModalLabel"> Modificar Datos del Profesor </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editUserForm" method="POST" action="" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="edit_url" id="edit_url" value="{{ old('edit_url') }}">
                    <div class="row g-4">

                        <div class="col-md-6">
                            <label for="username" class="form-label fw-bold small text-muted text-uppercase">Nombre de Usuario</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" id="username" value="{{ old('_method') == 'PUT' ? old('username') : '' }}" autocomplete="off" required pattern="{{ config('regex.username.html') }}" aria-describedby="feedback-username">
                            <div id="feedback-username" class="invalid-feedback dynamic-feedback fw-bold"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label fw-bold small text-muted text-uppercase">Correo Electrónico</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('_method') == 'PUT' ? old('email') : '' }}" autocomplete="off" required pattern="{{ config('regex.email.html') }}" aria-describedby="feedback-email">
                            <div id="feedback-email" class="invalid-feedback dynamic-feedback fw-bold"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="name" class="form-label fw-bold small text-muted text-uppercase">Nombres</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('_method') == 'PUT' ? old('name') : '' }}" autocomplete="off" required pattern="{{ config('regex.name.html') }}" aria-describedby="feedback-name">
                            <div id="feedback-name" class="invalid-feedback dynamic-feedback fw-bold"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="last_name" class="form-label fw-bold small text-muted text-uppercase">Apellidos</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" id="last_name" value="{{ old('_method') == 'PUT' ? old('last_name') : '' }}" autocomplete="off" required pattern="{{ config('regex.last_name.html') }}" aria-describedby="feedback-last_name">
                            <div id="feedback-last_name" class="invalid-feedback dynamic-feedback fw-bold"></div>
                        </div>

                        <div class="col-md-4">
                            <label for="cedula" class="form-label fw-bold small text-muted text-uppercase">Cédula</label>
                            <input type="text" class="form-control @error('cedula') is-invalid @enderror" name="cedula" id="cedula" value="{{ old('_method') == 'PUT' ? old('cedula') : '' }}" autocomplete="off" required pattern="{{ config('regex.cedula.html') }}" aria-describedby="feedback-cedula">
                            <div id="feedback-cedula" class="invalid-feedback dynamic-feedback fw-bold"></div>
                        </div>

                        <div class="col-md-4">
                            <label for="edit-phone" class="form-label fw-bold small text-muted text-uppercase">Teléfono</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" id="edit-phone" value="{{ old('_method') == 'PUT' ? old('phone') : '' }}" autocomplete="off" pattern="{{ config('regex.phone.html') }}" aria-describedby="feedback-phone">
                            <div id="feedback-phone" class="invalid-feedback dynamic-feedback fw-bold"></div>
                        </div>

                        <div class="col-md-4">
                            <label for="edit-status" class="form-label fw-bold small text-muted text-uppercase">Estado</label>
                            <select class="form-select @error('status_users') is-invalid @enderror" name="status_users" id="edit-status" required aria-describedby="feedback-status">
                                <option value="Activo" {{ old('_method') == 'PUT' && old('status_users') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                <option value="Inactivo" {{ old('_method') == 'PUT' && old('status_users') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                <option value="Suspendido" {{ old('_method') == 'PUT' && old('status_users') == 'Suspendido' ? 'selected' : '' }}>Suspendido</option>
                            </select>
                            <div id="feedback-status" class="invalid-feedback dynamic-feedback fw-bold"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                <button type="submit" form="editUserForm" class="btn btn-warning fw-bold text-dark shadow-sm">
                    <i class="bi bi-save me-1"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header border-bottom">
                <h1 class="modal-title fs-5 fw-bold" id="createUserModalLabel">
                    Registrar Profesor
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <form id="createUserForm" method="POST" action="{{ route('usuarios.store') }}" novalidate>
                    @csrf
                    <input type="hidden" name="origen" value="modal">
                    <div class="row g-4">

                        <div class="col-md-6">
                            <label for="username" class="form-label fw-bold small text-muted text-uppercase">Nombre de Usuario</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" id="username" value="{{ old('origen') == 'modal' ? old('username') : '' }}" autocomplete="off" required pattern="{{ config('regex.username.html') }}" aria-describedby="feedback-username">
                            <div id="feedback-username" class="invalid-feedback dynamic-feedback fw-bold"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label fw-bold small text-muted text-uppercase">Correo Electrónico</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('origen') == 'modal' ? old('email') : '' }}" autocomplete="off" required pattern="{{ config('regex.email.html') }}" aria-describedby="feedback-email">
                            <div id="feedback-email" class="invalid-feedback dynamic-feedback fw-bold"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="name" class="form-label fw-bold small text-muted text-uppercase">Nombres</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('origen') == 'modal' ? old('name') : '' }}" autocomplete="off" required pattern="{{ config('regex.name.html') }}" aria-describedby="feedback-name">
                            <div id="feedback-name" class="invalid-feedback dynamic-feedback fw-bold"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="last_name" class="form-label fw-bold small text-muted text-uppercase">Apellidos</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" id="last_name" value="{{ old('origen') == 'modal' ? old('last_name') : '' }}" autocomplete="off" required pattern="{{ config('regex.last_name.html') }}" aria-describedby="feedback-last_name">
                            <div id="feedback-last_name" class="invalid-feedback dynamic-feedback fw-bold"></div>
                        </div>

                        <div class="col-md-4">
                            <label for="cedula" class="form-label fw-bold small text-muted text-uppercase">Cédula</label>
                            <input type="text" class="form-control @error('cedula') is-invalid @enderror" name="cedula" id="cedula" value="{{ old('origen') == 'modal' ? old('cedula') : '' }}" autocomplete="off" required pattern="{{ config('regex.cedula.html') }}" aria-describedby="feedback-cedula">
                            <div id="feedback-cedula" class="invalid-feedback dynamic-feedback fw-bold"></div>
                        </div>

                        <div class="col-md-4">
                            <label for="phone" class="form-label fw-bold small text-muted text-uppercase">Teléfono</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" id="phone" value="{{ old('origen') == 'modal' ? old('phone') : '' }}" autocomplete="off" pattern="{{ config('regex.phone.html') }}" aria-describedby="feedback-phone">
                            <div id="feedback-phone" class="invalid-feedback dynamic-feedback fw-bold"></div>
                        </div>

                        <div class="col-md-4">
                            <label for="id_rol" class="form-label fw-bold small text-muted text-uppercase">Rol del Sistema</label>
                            <select class="form-select @error('id_rol') is-invalid @enderror" name="id_rol" id="id_rol" required style="color: black;" aria-describedby="feedback-rol">
                                <option value="" disabled {{ !old('id_rol') ? 'selected' : '' }}>Seleccione un rol...</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->id_rol }}" {{ old('origen') == 'modal' && old('id_rol') == $role->id_rol ? 'selected' : '' }}>{{ $role->nombre_rol }}</option>
                                @endforeach
                            </select>
                            <div id="feedback-rol" class="invalid-feedback dynamic-feedback fw-bold"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label fw-bold small text-muted text-uppercase">Contraseña</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" required pattern="{{ config('regex.password.html') }}" aria-describedby="feedback-password">
                            <div id="feedback-password" class="invalid-feedback dynamic-feedback fw-bold"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="password-confirm" class="form-label fw-bold small text-muted text-uppercase">Confirmar Contraseña</label>
                            <input type="password" class="form-control" name="password_confirmation" id="password-confirm" required aria-describedby="feedback-password-confirm">
                            <div id="feedback-password-confirm" class="invalid-feedback dynamic-feedback fw-bold"></div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="createUserForm" class="btn btn-primary fw-bold shadow-sm">
                    <i class="bi bi-save me-1"></i> Guardar Registro
                </button>
            </div>
        </div>
    </div>
</div>