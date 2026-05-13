@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12"> <!-- Aumenté un poco el ancho para que los campos dobles respiren mejor -->
            <div class="card card-primary card-outline shadow">
                <div class="card-header">
                    <h3>Registro de Usuario</h3>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- FILA 1: Username y Email -->
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="username"><b>Nombre de Usuario</b><span class="text-danger">*</span></label>
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus autocomplete="off">
                                <div class="invalid-feedback dynamic-feedback text-bold"></div>
                                @error('username')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="email"><b>Correo Electrónico</b><span class="text-danger">*</span></label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autocomplete="off">
                                <div class="invalid-feedback dynamic-feedback text-bold"></div>
                                @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <br>

                        <!-- FILA 2: Nombres y Apellidos -->
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="name"><b>Nombres</b><span class="text-danger">*</span></label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="off">
                                <div class="invalid-feedback dynamic-feedback text-bold"></div>
                                @error('name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="last_name"><b>Apellidos</b><span class="text-danger">*</span></label>
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="off">
                                <div class="invalid-feedback dynamic-feedback text-bold"></div>
                                @error('last_name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <br>

                        <!-- FILA 3: Cédula y Teléfono -->
                        <!-- Ejemplo para Cédula -->
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="cedula"><b>Cédula de Identidad</b> <span class="text-danger">*</span></label>
                                <input id="cedula" type="text" class="form-control @error('cedula') is-invalid @enderror" name="cedula" value="{{ old('cedula') }}" required>
                                <div class="invalid-feedback dynamic-feedback text-bold"></div>
                                

                                <!-- Mensaje dinámico si está vacío (HTML5) -->
                                <div class="invalid-feedback d-none" id="cedula-error">
                                    Este campo es obligatorio para el registro académico.
                                </div>

                                @error('cedula')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <!-- Ejemplo para Teléfono -->
                            <div class="col-md-6 form-group">
                                <label for="phone"><b>Teléfono</b></label>
                                <input id="phone" type="text"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    name="phone" value="{{ old('phone') }}"
                                    placeholder="0412-1234567"
                                    pattern="\d{4}-\d{7}">
                                <small class="text-muted">Formato: 04XX-1234567</small>
                                <div class="invalid-feedback dynamic-feedback text-bold"></div>
                            </div>
                        </div>


                        <br>

                        <!-- FILA 4: Contraseña y Confirmar -->
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="password"><b>Contraseña</b><span class="text-danger">*</span></label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                <div class="invalid-feedback dynamic-feedback text-bold"></div>
                                @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="password-confirm"><b>Confirmar Contraseña</b><span class="text-danger">*</span></label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    const inputs = form.querySelectorAll('input:not([type="hidden"])');

    // 1. Objeto con las reglas exactas que tenemos en el Backend
    const reglas = {

        username: {
            regex: /^[a-z0-9]{4,20}$/,
            error: 'Solo minúsculas y números (4 a 20 caracteres).'
        },
        
        email: {
            regex: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
            error: 'Un correo electrónico válido incluye @ y un dominio (ejemplo@correo.com).'
        },
        
        name: {
            regex: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/,
            error: 'Solo letras y espacios (3 a 50 caracteres).'
        },
        
        last_name: {
            regex: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/,
            error: 'Solo letras y espacios (3 a 50 caracteres).'
        },
        
        cedula: {
            regex: /^\d{7,8}$/,
            error: 'Debe contener solo números (entre 7 y 8 dígitos).'
        },
        
        phone: {
            regex: /^\d{4}-\d{7}$/,
            error: 'El teléfono debe seguir el formato 04XX-1234567.'
        },
        
        password: {
            regex: /^(?=(.*[A-Z]){2,})(?=(.*[a-z]){2,})(?=(.*[0-9]){2,})(?=(.*[*$%&+-]){1,}).{8,}$/,
            error: 'Faltan requisitos: 2 mayúsculas, 2 minúsculas, 2 números, un cacter de estos (*,$,%,&,+,-).'
        }

    };

    // Al iniciar, el botón está bloqueado por seguridad
    submitBtn.disabled = true;

    // 2. Función que evalúa un input individual
    function validarInput(input) {
        const nombreCampo = input.name;
        const valor = input.value.trim();
        const feedback = input.parentNode.querySelector('.dynamic-feedback');
        
        let esValido = true;
        let mensajeError = '';

        // Lógica para confirmar contraseña
        if (nombreCampo === 'password_confirmation') {
            const passwordPrincipal = document.getElementById('password').value;
            if (valor === '') {
                esValido = false;
                mensajeError = 'Debe confirmar su contraseña.';
            } else if (valor !== passwordPrincipal) {
                esValido = false;
                mensajeError = 'Las contraseñas no coinciden.';
            }
        } 
        // Lógica para el resto de los campos basándose en el objeto 'reglas'
        else if (reglas[nombreCampo]) {
            const regla = reglas[nombreCampo];

            // Si es un campo opcional y está vacío, es válido (Ej: Teléfono)
            if (!input.required && valor === '') {
                esValido = true;
            } 
            // Si es requerido y está vacío
            else if (input.required && valor === '') {
                esValido = false;
                mensajeError = 'Este campo es obligatorio.';
            } 
            // Si tiene texto, lo pasamos por el filtro Regex
            else if (!regla.regex.test(valor)) {
                esValido = false;
                mensajeError = regla.error;
            }
        }

        // 3. Modificamos el DOM (Colores y Mensajes)
        if (!esValido) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            if (feedback) feedback.textContent = mensajeError;
        } else {
            input.classList.remove('is-invalid');
            // Solo pintamos de verde si el usuario ya escribió algo
            if (valor !== '') {
                input.classList.add('is-valid');
            }
            if (feedback) feedback.textContent = '';
        }

        return esValido;
    }

    // 4. Función para verificar todo el formulario y liberar el botón
    function verificarFormularioCompleto() {
        let formularioValido = true;

        inputs.forEach(input => {
            // Evaluamos silenciosamente sin mostrar errores si el usuario no ha tocado el campo aún
            const nombreCampo = input.name;
            const valor = input.value.trim();
            
            if (nombreCampo === 'password_confirmation') {
                if (valor !== document.getElementById('password').value || valor === '') formularioValido = false;
            } else if (reglas[nombreCampo]) {
                if (input.required && valor === '') formularioValido = false;
                if (valor !== '' && !reglas[nombreCampo].regex.test(valor)) formularioValido = false;
            }
        });

        submitBtn.disabled = !formularioValido;
    }

    // 5. Escuchamos los eventos 'input' (mientras escribe) y 'blur' (cuando sale del campo)
    inputs.forEach(input => {
        // Validación interactiva mientras escribe
        input.addEventListener('input', function() {
            validarInput(this);
            verificarFormularioCompleto();
        });

        // Validación estricta al hacer clic fuera del campo
        input.addEventListener('blur', function() {
            validarInput(this);
            verificarFormularioCompleto();
        });
    });
});
</script>

pruebas de acceso 
admin lt bootrap 5
columnas bootrap5

