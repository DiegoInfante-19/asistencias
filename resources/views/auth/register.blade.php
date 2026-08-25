@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center justify-content-center min-vh-100 bg-body-secondary py-5">
  <main class="register-box" style="width: 750px; max-width: 95%;">
    <div class="register-logo mb-4">
      <a href="{{ url('/') }}"><b>Assis</b>.UPT</a>
    </div>
    <!-- /.register-logo -->
    <div class="card shadow-sm border-0">
      <div class="card-body register-card-body p-4">
        <p class="login-box-msg mb-4 text-muted">Complete los datos requeridos para registrar una nueva cuenta</p>

        <form method="POST" action="{{ route('register') }}">
          @csrf

          <div class="row">
            <!-- Nombre de Usuario -->
            <div class="col-md-6 mb-3">
              <label for="username" class="form-label text-secondary" style="font-weight: 500; font-size: 0.9rem;">Nombre de Usuario</label>
              <input id="username" type="text" name="username" value="{{ old('username') }}" required autocomplete="off" placeholder="Este campo es obligatorio"
                  class="form-control @error('username') is-invalid @enderror">
              <div class="invalid-feedback dynamic-feedback fw-bold" style="display: none;"></div>
              @error('username')
                <div class="invalid-feedback fw-bold">{{ $message }}</div>
              @enderror
            </div>

            <!-- Correo Electrónico -->
            <div class="col-md-6 mb-3">
              <label for="email" class="form-label text-secondary" style="font-weight: 500; font-size: 0.9rem;">Correo Electrónico</label>
              <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="off" placeholder="Este campo es obligatorio"
                  class="form-control @error('email') is-invalid @enderror">
              <div class="invalid-feedback dynamic-feedback fw-bold" style="display: none;"></div>
              @error('email')
                <div class="invalid-feedback fw-bold">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="row">
            <!-- Nombres -->
            <div class="col-md-6 mb-3">
              <label for="name" class="form-label text-secondary" style="font-weight: 500; font-size: 0.9rem;">Nombres</label>
              <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="off" placeholder="Este campo es obligatorio"
                  class="form-control @error('name') is-invalid @enderror">
              <div class="invalid-feedback dynamic-feedback fw-bold" style="display: none;"></div>
              @error('name')
                <div class="invalid-feedback fw-bold">{{ $message }}</div>
              @enderror
            </div>

            <!-- Apellidos -->
            <div class="col-md-6 mb-3">
              <label for="last_name" class="form-label text-secondary" style="font-weight: 500; font-size: 0.9rem;">Apellidos</label>
              <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required autocomplete="off" placeholder="Este campo es obligatorio"
                  class="form-control @error('last_name') is-invalid @enderror">
              <div class="invalid-feedback dynamic-feedback fw-bold" style="display: none;"></div>
              @error('last_name')
                <div class="invalid-feedback fw-bold">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="row">
            <!-- Cédula de Identidad -->
            <div class="col-md-6 mb-3">
              <label for="cedula" class="form-label text-secondary" style="font-weight: 500; font-size: 0.9rem;">Cédula de Identidad</label>
              <input id="cedula" type="text" name="cedula" value="{{ old('cedula') }}" required autocomplete="off" placeholder="Este campo es obligatorio"
                  class="form-control @error('cedula') is-invalid @enderror">
              <div class="invalid-feedback dynamic-feedback fw-bold" style="display: none;"></div>
              @error('cedula')
                <div class="invalid-feedback fw-bold">{{ $message }}</div>
              @enderror
            </div>

            <!-- Teléfono -->
            <div class="col-md-6 mb-3">
              <label for="phone" class="form-label text-secondary" style="font-weight: 500; font-size: 0.9rem;">Teléfono</label>
              <input id="phone" type="text" name="phone" value="{{ old('phone') }}" autocomplete="off" placeholder="Este campo es obligatorio"
                  class="form-control @error('phone') is-invalid @enderror">
              <div class="invalid-feedback dynamic-feedback fw-bold" style="display: none;"></div>
              @error('phone')
                <div class="invalid-feedback fw-bold">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="row">
            <!-- Contraseña -->
            <div class="col-md-6 mb-3">
              <label for="password" class="form-label text-secondary" style="font-weight: 500; font-size: 0.9rem;">Contraseña</label>
              <input id="password" type="password" name="password" required autocomplete="off" placeholder="Este campo es obligatorio"
                  class="form-control @error('password') is-invalid @enderror">
              <div class="invalid-feedback dynamic-feedback fw-bold" style="display: none;"></div>
              @error('password')
                <div class="invalid-feedback fw-bold">{{ $message }}</div>
              @enderror
            </div>

            <!-- Confirmar Contraseña -->
            <div class="col-md-6 mb-4">
              <label for="password-confirm" class="form-label text-secondary" style="font-weight: 500; font-size: 0.9rem;">Confirmar Contraseña</label>
              <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="off" placeholder="Este campo es obligatorio"
                  class="form-control">
              <div class="invalid-feedback dynamic-feedback fw-bold" style="display: none;"></div>
            </div>
          </div>

          <!-- Botones de Acción -->
          <div class="row g-2">
            <div class="col-6">
              <a href="{{ route('login') }}" class="btn btn-secondary w-100 py-2">
                Cancelar
              </a>
            </div>
            <div class="col-6">
              <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                Completar
              </button>
            </div>
          </div>
        </form>

        <div class="text-center mt-3">
          <p class="mb-0 text-muted" style="font-weight: 500; font-size: 0.9rem;">
            ¿Ya tienes una cuenta? <a href="{{ route('login') }}" class="text-decoration-none">Inicia sesión</a>
          </p>
        </div>
      </div>
      <!-- /.register-card-body -->
    </div>
  </main>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/core-validations.js') }}" defer></script>
<script src="{{ asset('js/auth-validations.js') }}" defer></script>
@endsection