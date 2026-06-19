@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6"> <!-- Aumenté un poco el ancho para que los campos dobles respiren mejor -->
            <div class="card card-primary card-outline shadow">
                <div class="card-header">
                    <h3>Registro de Usuario</h3>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- FILA 1: Username y Email -->
                        <div class="row">
                            <div class="col-md-6 form-group mb-3 mb-md-0">
                                <label for="username"><b>Nombre de Usuario</b><span class="text-danger">*</span></label>
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required  autocomplete="off">
                                <div class="invalid-feedback dynamic-feedback text-bold"></div>
                                @error('username') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                                
                            <div class="col-md-6 form-group">
                                <label for="email"><b>Correo Electrónico</b><span class="text-danger">*</span></label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="off">
                                <div class="invalid-feedback dynamic-feedback text-bold"></div>
                                @error('email') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                        </div>

                        <br>

                        <!-- FILA 2: Nombres y Apellidos -->
                        <div class="row">
                            <div class="col-md-6 form-group mb-3 mb-md-0">
                                <label for="name"><b>Nombres</b><span class="text-danger">*</span></label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="off">
                                <div class="invalid-feedback dynamic-feedback text-bold"></div>
                                @error('name') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="last_name"><b>Apellidos</b><span class="text-danger">*</span></label>
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="off">
                                <div class="invalid-feedback dynamic-feedback text-bold"></div>
                                @error('last_name') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                        </div>

                        <br>

                        <!-- FILA 3: Cédula y Teléfono -->
                        <!-- Ejemplo para Cédula -->
                        <div class="row">
                            <div class="col-md-6 form-group mb-3 mb-md-0">
                                <label for="cedula"><b>Cédula de Identidad</b> <span class="text-danger">*</span></label>
                                <input id="cedula" type="text" class="form-control @error('cedula') is-invalid @enderror" name="cedula" value="{{ old('cedula') }}" required autocomplete="off">
                                <div class="invalid-feedback dynamic-feedback text-bold"></div>
                                <!-- Mensaje dinámico si está vacío (HTML5) -->
                                <div class="invalid-feedback d-none" id="cedula-error">
                                    Este campo es obligatorio para el registro. 
                                </div>
                                @error('cedula') <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                            <!-- Ejemplo para Teléfono -->
                            <div class="col-md-6 form-group">
                                <label for="phone"><b>Teléfono</b></label>
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" autocomplete="off">
                                <div class="invalid-feedback dynamic-feedback text-bold"></div>
                            </div>
                        </div>

                        <br>

                        <!-- FILA 4: Contraseña y Confirmar -->
                        <div class="row">
                            <div class="col-md-6 form-group mb-3 mb-md-0">
                                <label for="password"><b>Contraseña</b><span class="text-danger">*</span></label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="off">
                                <div class="invalid-feedback dynamic-feedback text-bold"></div>
                                @error('password') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="password-confirm"><b>Confirmar Contraseña</b><span class="text-danger">*</span></label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="off">
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary px-5">
                                    <b>Completar Registro</b>
                                </button>
                            </div>
                        </div>
                    </form> 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/core-validations.js') }}" defer></script>
    <script src="{{ asset('js/auth-validations.js') }}" defer></script>
@endsection


<!-- 
    quedamos en adaptar la validacion de los formularios del crud panel de usuarios 
-->