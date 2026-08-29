@extends('layouts.admin')

@section('content')
<div class="content pt-4" style="margin: 20px;">
    
    <div class="row g-4">
        <!-- COLUMNA IZQUIERDA: TARJETA DE PRESENTACIÓN -->
        <div class="col-md-4 col-lg-3">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body text-center p-4">
                    <!-- Avatar generado con iniciales -->
                    
                    <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 100px; height: 100px; font-size: 2.5rem; font-weight: 500;">
                        {{ substr($user->name_users, 0, 1) }}{{ substr($user->last_name_users, 0, 1) }}
                    </div>

                    <h5 class="text-dark mb-1" style="font-weight: 600;">{{ $user->name_users }} {{ $user->last_name_users }}</h5>
                    <p class="text-muted small fw-semibold mb-3" style="font-weight: 500;">
                        <i class="bi bi-at"></i>{{ $user->username }}
                    </p>

                    <div class="mb-4">
                        <span class="badge bg-primary px-3 py-2 shadow-sm fs-6">
                            {{ $user->rol->nombre_rol ?? 'Usuario' }}
                        </span>
                    </div>

                    <!-- Lista de datos de contacto rápida -->
                    <ul class="list-group list-group-flush text-start small">
                        <li class="list-group-item px-0 bg-transparent border-secondary-subtle">
                            <span class="text-muted d-block" style="font-size: 0.75rem;">Cédula de Identidad</span>
                            <span class="fw-semibold text-dark" style="font-weight: 500;">{{ $user->cedula_users }}</span>
                        </li>
                        <li class="list-group-item px-0 bg-transparent border-secondary-subtle">
                            <span class="text-muted d-block" style="font-size: 0.75rem;">Correo Electrónico</span>
                            <span class="fw-semibold text-dark" style="font-weight: 500;">{{ $user->email_users }}</span>
                        </li>
                        <li class="list-group-item px-0 bg-transparent border-0">
                            <span class="text-muted d-blocke" style="font-size: 0.75rem;">Teléfono</span>
                            <span class="fw-semibold text-dark" style="font-weight: 500;">{{ $user->phone_users ?? 'No registrado' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: FORMULARIO DE ACTUALIZACIÓN -->
        <div class="col-md-8 col-lg-9">
            <div class="card border-0 shadow-sm bg-white h-100">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                    <h5 class="text-dark mb-0" style="font-weight: 500;">
                        Actualizar Datos Personales
                    </h5>
                    
                    <!-- Botón anclado a la derecha con ms-auto -->
                    <button type="button" class="btn btn-outline-primary btn-sm fw-bold ms-auto" data-bs-toggle="modal" data-bs-target="#securityModal">
                        <i class="bi bi-shield-lock-fill me-1"></i> Seguridad de la Cuenta
                    </button>
                </div>
                
                <div class="card-body p-4">
                    <form id="profileUpdateForm" action="{{ route('perfil.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Nombre de Usuario <span class="text-danger">*</span></label>
                                <input type="text" name="username" class="form-control bg-light border-secondary-subtle shadow-sm" value="{{ $user->username }}" required autocomplete="off">
                                <div class="dynamic-feedback fw-bold small text-danger" style="display:none;"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Correo Electrónico <span class="text-danger">*</span></label>
                                <input type="email" name="email_users" class="form-control bg-light border-secondary-subtle shadow-sm" value="{{ $user->email_users }}" required autocomplete="off">
                                <div class="dynamic-feedback fw-bold small text-danger" style="display:none;"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Nombres <span class="text-danger">*</span></label>
                                <input type="text" name="name_users" class="form-control bg-light border-secondary-subtle shadow-sm" value="{{ $user->name_users }}" required autocomplete="off">
                                <div class="dynamic-feedback fw-bold small text-danger" style="display:none;"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Apellidos <span class="text-danger">*</span></label>
                                <input type="text" name="last_name_users" class="form-control bg-light border-secondary-subtle shadow-sm" value="{{ $user->last_name_users }}" required autocomplete="off">
                                <div class="dynamic-feedback fw-bold small text-danger" style="display:none;"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Cédula de Identidad <span class="text-danger">*</span></label>
                                <input type="text" name="cedula_users" class="form-control bg-light border-secondary-subtle shadow-sm" value="{{ $user->cedula_users }}" required autocomplete="off">
                                <div class="dynamic-feedback fw-bold small text-danger" style="display:none;"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Número Telefónico</label>
                                <input type="text" name="phone_users" class="form-control bg-light border-secondary-subtle shadow-sm" value="{{ $user->phone_users }}" autocomplete="off" placeholder="Opcional">
                                <div class="dynamic-feedback fw-bold small text-danger" style="display:none;"></div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="card-footer bg-white border-top py-3 text-end">
                    <button type="submit" class="btn btn-primary fw-bold px-4" form="profileUpdateForm">
                        <i class="bi bi-save-fill me-1"></i> Guardar Cambios
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="{{ asset('js/core-validations.js') }}" defer></script>
<script src="{{ asset('js/admin-validations.js') }}" defer></script>

@include('perfil.partials.security-modal')
@endsection