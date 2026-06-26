@extends('layouts.admin')

@section('content')
<div class="content" style="margin: 20px;">
    <div class="container-fluid">
        <div class="row g-3">

            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center mb-3" style="width: 96px; height: 96px; font-size: 2.5rem; font-weight: bold;">
                            {{ substr($user->name_users, 0, 1) }}{{ substr($user->last_name_users, 0, 1) }}
                        </div>
                        <h3 class="h5 mb-0">{{ $user->name_users }} {{ $user->last_name_users }}</h3>
                        <p class="text-muted mb-2"><i class="bi bi-at"></i> {{ $user->username }}</p>
                        <p class="text-secondary mb-3"><span class="badge text-bg-primary">{{ $user->rol->nombre_rol ?? 'Usuario' }}</span></p>
                        <ul class="list-group list-group-flush text-start small">
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-secondary"><i class="bi bi-person-vcard me-1"></i> Cédula</span>
                                <span class="fw-semibold">{{ $user->cedula_users }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-secondary"><i class="bi bi-envelope me-1"></i> Correo</span>
                                <span class="fw-semibold">{{ $user->email_users }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-secondary"><i class="bi bi-telephone me-1"></i> Teléfono</span>
                                <span class="fw-semibold">{{ $user->phone_users ?? 'No registrado' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="profile-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab" aria-selected="false" tabindex="-1">
                                    <i class="bi bi-shield-lock me-1"></i> Configuración
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link " id="cohortes-tab" data-bs-toggle="tab" data-bs-target="#cohortes" type="button" role="tab" aria-selected="true">
                                    <i class="bi bi-people me-1"></i> Mis Cohortes
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="sesiones-tab" data-bs-toggle="tab" data-bs-target="#sesiones" type="button" role="tab" aria-selected="false" tabindex="-1">
                                    <i class="bi bi-calendar-event me-1"></i> Próximas Sesiones
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="empresas-tab" data-bs-toggle="tab" data-bs-target="#empresas" type="button" role="tab" aria-selected="false" tabindex="-1">
                                    <i class="bi bi-building me-1"></i> Empresas Vinculadas
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content">

                            <div class="tab-pane fade show active" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                                <h5 class="fw-bold mb-4">Actualizar Datos Personales</h5>

                                <form id="profileUpdateForm" action="{{ route('perfil.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row g-4">
                                        <div class="col-md-6 form-group">
                                            <label class="form-label small fw-bold">Nombre de Usuario</label>
                                            <input type="text" name="username" class="form-control" value="{{ $user->username }}" required pattern="{{ config('regex.username.html') }}">
                                            <div class="dynamic-feedback fw-bold small text-danger" style="display:none;"></div>
                                        </div>

                                        <div class="col-md-6 form-group">
                                            <label class="form-label small fw-bold">Correo Electrónico</label>
                                            <input type="email" name="email_users" class="form-control" value="{{ $user->email_users }}" required pattern="{{ config('regex.email.html') }}">
                                            <div class="dynamic-feedback fw-bold small text-danger" style="display:none;"></div>
                                        </div>

                                        <div class="col-md-6 form-group">
                                            <label class="form-label small fw-bold">Nombres</label>
                                            <input type="text" name="name_users" class="form-control" value="{{ $user->name_users }}" required pattern="{{ config('regex.name.html') }}">
                                            <div class="dynamic-feedback fw-bold small text-danger" style="display:none;"></div>
                                        </div>

                                        <div class="col-md-6 form-group">
                                            <label class="form-label small fw-bold">Apellidos</label>
                                            <input type="text" name="last_name_users" class="form-control" value="{{ $user->last_name_users }}" required pattern="{{ config('regex.last_name.html') }}">
                                            <div class="dynamic-feedback fw-bold small text-danger" style="display:none;"></div>
                                        </div>

                                        <div class="col-md-6 form-group">
                                            <label class="form-label small fw-bold">Cédula</label>
                                            <input type="text" name="cedula_users" class="form-control" value="{{ $user->cedula_users }}" required pattern="{{ config('regex.cedula.html') }}">
                                            <div class="dynamic-feedback fw-bold small text-danger" style="display:none;"></div>
                                        </div>

                                        <div class="col-md-6 form-group">
                                            <label class="form-label small fw-bold">Teléfono</label>
                                            <input type="text" name="phone_users" class="form-control" value="{{ $user->phone_users }}" pattern="{{ config('regex.phone.html') }}">
                                            <div class="dynamic-feedback fw-bold small text-danger" style="display:none;"></div>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <button type="submit" class="btn btn-primary fw-bold" form="profileUpdateForm">
                                                <i class="bi bi-save me-1"></i> Guardar Cambios
                                            </button>
                                            <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#securityModal">
                                                <i class="bi bi-shield-lock me-1"></i> Seguridad y Credenciales
                                            </button>
                                        </div>


                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="En_Construccion_1" role="tabpanel" aria-labelledby=En_Construccion_1-tab">
                            </div>

                            <div class="tab-pane fade" id="En_Construccion_2" role="tabpanel" aria-labelledby="En_Construccion_2-tab">
                            </div>

                            <div class="tab-pane fade" id="En_Construccion_3" role="tabpanel" aria-labelledby="En_Construccion_3-tab">
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@include('perfil.partials.security-modal')
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Activamos la validación automática para este formulario
        activarValidacion('#profileUpdateForm');
        activarValidacion('#formPassword');
        activarValidacion('#formSecurity')
    });
</script>
@endsection