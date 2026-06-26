@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="card card-primary card-outline shadow">
                <div class="card-header"><h3>Registro de Usuario</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="username"><b>Nombre de Usuario</b><span class="text-danger">*</span></label>
                                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autocomplete="off" pattern="{{ config('regex.username.html') }}">
                                <div class="invalid-feedback dynamic-feedback text-bold"></div>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="email"><b>Correo Electrónico</b><span class="text-danger">*</span></label>
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="off" pattern="{{ config('regex.email.html') }}">
                                <div class="invalid-feedback dynamic-feedback text-bold"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="name"><b>Nombres</b><span class="text-danger">*</span></label>
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autocomplete="off" pattern="{{ config('regex.name.html') }}">
                                <div class="invalid-feedback dynamic-feedback text-bold"></div>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="last_name"><b>Apellidos</b><span class="text-danger">*</span></label>
                                <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required autocomplete="off" pattern="{{ config('regex.last_name.html') }}">
                                <div class="invalid-feedback dynamic-feedback text-bold"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="cedula"><b>Cédula</b><span class="text-danger">*</span></label>
                                <input id="cedula" type="text" class="form-control" name="cedula" value="{{ old('cedula') }}" required autocomplete="off" pattern="{{ config('regex.cedula.html') }}">
                                <div class="invalid-feedback dynamic-feedback text-bold"></div>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="telefono"><b>Teléfono</b></label>
                                
                                <input id="phone" type="text" class="form-control" name="phone" 
                                value="{{ old('phone') }}" 
                                autocomplete="off" 
                                pattern="{{ config('regex.phone.html') }}">

                                <div class="invalid-feedback dynamic-feedback text-bold"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="password"><b>Contraseña</b><span class="text-danger">*</span></label>
                                <input id="password" type="password" class="form-control" name="password" required autocomplete="off" pattern="{{ config('regex.password.html') }}">
                                <div class="invalid-feedback dynamic-feedback text-bold"></div>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="password-confirm"><b>Confirmar Contraseña</b><span class="text-danger">*</span></label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="off">
                                <div class="invalid-feedback dynamic-feedback text-bold"></div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary px-5"><b>Completar Registro</b></button>
                        </div>
                    </form> 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
   <script>
        window.addEventListener('load', function() {
            if (typeof window.activarValidacion === 'function') {
                window.activarValidacion('#registerForm');
            } else {
                console.error("activarValidacion no está cargado aún");
            }
        });
    </script>
@endsection