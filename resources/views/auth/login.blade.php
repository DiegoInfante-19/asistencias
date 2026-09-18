@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center justify-content-center flex-grow-1 py-5">
    <div class="w-100 px-3" style="max-width: 420px;">

        <!-- TARJETA ESTANDARIZADA DEL LOGIN -->
        <div class="card bg-white border border-secondary-subtle shadow-sm overflow-hidden">

            <!-- Cuerpo del Formulario -->
            <div class="card-body p-4 p-md-5">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Campo Login (Cédula o Correo) -->
                    <div class="mb-4">
                        <label for="login" class="form-label fw-bold small text-muted">
                            Cédula o Correo Electrónico
                        </label>
                        <input id="login" type="text" name="login" value="{{ old('login') }}" required autocomplete="off" placeholder="Este campo es obligatorio"
                            class="form-control bg-light @error('login') is-invalid @enderror">
                        @error('login')
                        <div class="invalid-feedback fw-bold">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Campo Password y Recuperación -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="password" class="form-label fw-bold small text-muted mb-0">
                                Contraseña
                            </label>

                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Este campo es obligatorio" class="form-control bg-light @error('password') is-invalid @enderror">
                        @error('password')
                        <div class="invalid-feedback fw-bold">
                            {{ $message }}
                        </div>
                        @enderror
                        @if (Route::has('password.request'))
                        <br><a href="{{ route('password.request') }}" class="text-decoration-none small fw-medium">
                            ¿Olvidaste tu contraseña?
                        </a>
                        @endif
                    </div>

                    <!-- Botón de Acceso -->
                    <div class="d-grid gap-2 mt-2">
                        <button type="submit" class="btn btn-primary fw-bold py-2 shadow-sm rounded-3">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Acceder al Sistema
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer del Card para el enlace de registro -->
            <div class="card-footer bg-light py-3 text-center border-top">
                <p class="mb-0 text-muted small fw-medium">
                    ¿No tienes una cuenta?
                    <a href="{{ route('register') }}" class="text-decoration-none fw-bold">Regístrate aquí</a>
                </p>
            </div>

        </div><br><br>
        <!-- Fin Tarjeta -->

    </div>
</div>
@endsection