document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    if (!form) return;
    
    const submitBtn = form.querySelector('button[type="submit"]');
    const inputs = form.querySelectorAll('input:not([type="hidden"])');
    const reglas = window.CoreRules;

    if (submitBtn) submitBtn.disabled = true;

    function validarInput(input) {
        const nombreCampo = input.name;
        const valor = input.value.trim();
        
        // CORRECCIÓN BOOTSTRAP 5: Buscar en el contenedor padre cercano
        const contenedor = input.closest('.input-group, .mb-3, div');
        const feedback = contenedor ? contenedor.querySelector('.dynamic-feedback') : null;
        
        let esValido = true;
        let mensajeError = '';

        if (nombreCampo === 'password_confirmation') {
            const passInput = document.getElementById('password') || document.querySelector('input[name="password"]');
            const passwordPrincipal = passInput ? passInput.value : '';

            if (valor === '') {
                esValido = false; mensajeError = 'Debe confirmar su contraseña.';
            } else if (valor !== passwordPrincipal) {
                esValido = false; mensajeError = 'Las contraseñas no coinciden.';
            }
        } else if (reglas && reglas[nombreCampo]) {
            const regla = reglas[nombreCampo];
            if (input.required && valor === '') {
                esValido = false; mensajeError = 'Este campo es obligatorio.';
            } else if (valor !== '' && !regla.regex.test(valor)) {
                esValido = false; mensajeError = regla.error;
            }
        }

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

            // Ocultar errores del backend de Laravel
            if (contenedor) {
                const erroresLaravel = contenedor.querySelectorAll('.invalid-feedback:not(.dynamic-feedback), [role="alert"]');
                erroresLaravel.forEach(errorLaravel => {
                    errorLaravel.classList.remove('d-block');
                    errorLaravel.style.display = 'none';
                });
            }
        }
        return esValido;
    }

    function verificarFormularioCompleto() {
        if (!submitBtn) return;
        let formularioValido = true;
        inputs.forEach(input => {
            const nombreCampo = input.name;
            const valor = input.value.trim();
            if (nombreCampo === 'password_confirmation') {
                const passInput = document.getElementById('password');
                if (passInput && (valor !== passInput.value || valor === '')) formularioValido = false;
            } else if (reglas && reglas[nombreCampo]) {
                if (input.required && valor === '') formularioValido = false;
                if (valor !== '' && !reglas[nombreCampo].regex.test(valor)) formularioValido = false;
            }
        });
        submitBtn.disabled = !formularioValido;
    }

    inputs.forEach(input => {
        input.addEventListener('input', function () {
            validarInput(this);
            verificarFormularioCompleto();
        });
        input.addEventListener('blur', function () {
            validarInput(this);
            verificarFormularioCompleto();
        });
    });
});