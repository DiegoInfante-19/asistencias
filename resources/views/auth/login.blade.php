@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center justify-content-center min-vh-100 bg-body-secondary">
  <main class="login-box" style="width: 400px;">
    <div class="login-logo mb-4">
      <a href="{{ url('/') }}"><b>Assis</b>.UPT</a>
    </div>
    <!-- /.login-logo -->
    <div class="card shadow-sm border-0">
      <div class="card-body login-card-body p-4">
        <p class="login-box-msg mb-4 text-muted">Ingrese sus credenciales para iniciar sesión</p>

        <form method="POST" action="{{ route('login') }}">
          @csrf

          <!-- Campo Login (Cédula o Correo) -->
          <div class="mb-3">
            <label for="login" class="form-label text-secondary" style="font-weight: 500; font-size: 0.9rem;">Cédula o Correo Electrónico</label>
            <input id="login" type="text" name="login" value="{{ old('login') }}" required autocomplete="off" placeholder="Este campo es obligatorio"
                class="form-control @error('login') is-invalid @enderror">
            @error('login')
              <div class="invalid-feedback fw-bold">
                {{ $message }}
              </div>
            @enderror
          </div>

          <!-- Campo Password -->
          <div class="mb-3">
            <label for="password" class="form-label text-secondary" style="font-weight: 500; font-size: 0.9rem;">Contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Este campo es obligatorio"
                class="form-control @error('password') is-invalid @enderror">
            @error('password')
              <div class="invalid-feedback fw-bold">
                {{ $message }}
              </div>
            @enderror
          </div>

          <div class="row align-items-center mb-3">
            <div class="col-12 text-end">
              @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-decoration-none small" style="font-weight: 500; font-size: 0.9rem;">
                  ¿Olvidaste tu contraseña?
                </a>
              @endif
            </div>
          </div>

          <div class="d-grid gap-2 mb-3">
            <button type="submit" class="btn btn-primary fw-bold py-2">
              Acceder al Sistema
            </button>
          </div>
        </form>

        <div class="text-center mt-3">
          <p class="mb-0 text-muted" style="font-weight: 500; font-size: 0.9rem;">
            ¿No tienes una cuenta? <a href="{{ route('register') }}" class="text-decoration-none ">Regístrate aquí</a>
          </p>
        </div>
      </div>
      <!-- /.login-card-body -->
    </div>
  </main>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/core-validations.js') }}" defer></script>
<script src="{{ asset('js/auth-validations.js') }}" defer></script>
@endsection