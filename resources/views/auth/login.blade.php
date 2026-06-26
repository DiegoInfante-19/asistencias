@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-8 col-md-7 col-lg-7 col-xl-4">
            <div class="card card-primary card-outline shadow mt-5">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Inicio de Sesión') }}</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label for="login"><b>Cédula o Correo Electrónico</b></label>
                            <input id="login" type="text" class="form-control @error('login') is-invalid @enderror" 
                                   name="login" value="{{ old('login') }}" required autocomplete="off" 
                                   placeholder="Ingrese su identificación o correo"
                                   pattern="^[a-zA-Z0-9._%+-@]{4,64}$"> 
                            
                            <div class="invalid-feedback"></div>
                            @error('login')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password"><b>Contraseña</b></label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required autocomplete="current-password" 
                                   placeholder="Campo obligatorio">
                            
                            <div class="invalid-feedback"></div>
                            @error('password')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        @if (Route::has('password.request'))
                        <div class="text-right mb-3">
                            <a class="btn btn-link btn-sm" href="{{ route('password.request') }}" style="font-size: 0.9rem; text-decoration: none;">
                                <b>¿Olvidaste tu contraseña?</b>
                            </a>
                        </div>
                        @endif
                        
                        <hr>
                        
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary px-5" id="submitBtn">
                                <b>Acceder al Sistema</b>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/auth-validations-login.js') }}" defer></script>
@endsection