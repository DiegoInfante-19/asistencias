document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    const inputs = form.querySelectorAll('input:not([type="hidden"])');
    const reglas = {
    username: {
        // Mantenemos solo minúsculas y números, pero ajustamos el error para que sea claro
        regex: /^[a-z0-9]{4,20}$/,
        error: 'Usa solo letras minúsculas y números (entre 4 y 20 caracteres).'
    },

    email: {
        // Explicación: ^[a-zA-Z0-9._%+-]+  -> Nombre de usuario
        // @                                -> El arroba obligatorio
        // [a-zA-Z0-9.-]+                   -> El dominio (gmail, outlook, etc)
        // \.com$                           -> Obligamos a que termine exactamente en .com
        regex: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.com$/,
        error: 'ingrese un correo electronico valido (ejemplo@dominio.com)'
    },

    name: {
        regex: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/,
        error: 'Solo letras y espacios (mínimo 3 caracteres).'
    },

    last_name: {
        regex: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/,
        error: 'Solo letras y espacios (mínimo 3 caracteres).'
    },

    cedula: {
        regex: /^\d{7,8}$/,
        error: 'La cédula debe tener entre 7 y 8 números.'
    },

    phone: {
        // Explicación: ^\d{11}$ -> Solo números y exactamente 11 dígitos
        regex: /^\d{11}$/,
        error: 'El teléfono debe tener exactamente 11 números, sin guiones (ej: 04167654321).'
    },

    password: {
        // Se mantiene tu lógica de alta seguridad: 2 Mayus, 2 Minus, 2 Núm, 1 Especial
        regex: /^(?=(.*[A-Z]){2,})(?=(.*[a-z]){2,})(?=(.*[0-9]){2,})(?=(.*[*$%&+-]){1,}).{8,}$/,
        error: 'Debe tener: 2 mayúsculas, 2 minúsculas, 2 números y 1 carácter (*,$,%,&,+,-).'
    }
};

    submitBtn.disabled = true;

    function validarInput(input) {
        const nombreCampo = input.name;
        const valor = input.value.trim();
        const feedback = input.parentNode.querySelector('.dynamic-feedback');
        let esValido = true;
        let mensajeError = '';

        // 1. Lógica de Validación
        if (nombreCampo === 'password_confirmation') {
            const passwordPrincipal = document.getElementById('password').value;
            if (valor === '') {
                esValido = false; mensajeError = 'Debe confirmar su contraseña.';
            } else if (valor !== passwordPrincipal) {
                esValido = false; mensajeError = 'Las contraseñas no coinciden.';
            }
        } else if (reglas[nombreCampo]) {
            const regla = reglas[nombreCampo];
            if (input.required && valor === '') {
                esValido = false; mensajeError = 'Este campo es obligatorio.';
            } else if (valor !== '' && !regla.regex.test(valor)) {
                esValido = false; mensajeError = regla.error;
            }
        }

        // 2. Acción única sobre el DOM (Sin repeticiones)
        if (!esValido) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            if (feedback) {
                feedback.textContent = mensajeError;
                feedback.style.display = 'block'; 
            }
        } else {
            input.classList.remove('is-invalid');
            if (valor !== '') {
                input.classList.add('is-valid');
            } else {
                input.classList.remove('is-valid');
            }
            if (feedback) {
                feedback.textContent = '';
                feedback.style.display = 'none'; 
            }
        }
        return esValido;
    }

    // 4. Función para verificar todo el formulario y liberar el botón
    function verificarFormularioCompleto() {
        let formularioValido = true;
        inputs.forEach(input => {
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
        input.addEventListener('input', function () {
            validarInput(this);
            verificarFormularioCompleto();
        });

        // Validación estricta al hacer clic fuera del campo
        input.addEventListener('blur', function () {
            validarInput(this);
            verificarFormularioCompleto();
        });
    });
});