document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    if (!form) return;

    const submitBtn = form.querySelector('button[type="submit"]');
    const inputs = form.querySelectorAll('input:not([type="hidden"])');

    if (!submitBtn) return;

    // Bloqueamos el botón al inicio
    submitBtn.disabled = true;

    function mostrarErrorMensaje(input, mensaje) {
        input.classList.remove('border-green-500', 'border-gray-300');
        input.classList.add('border-red-500');

        const container = input.closest('div');
        if (!container) return;

        let feedback = container.querySelector('.js-dynamic-error');
        if (!feedback) {
            feedback = document.createElement('p');
            feedback.className = 'mt-2 text-sm text-red-600 dark:text-red-500 js-dynamic-error font-bold';
            container.appendChild(feedback);
        }
        feedback.textContent = mensaje;
        feedback.style.display = 'block';
    }

    function ocultarErrorMensaje(input) {
        input.classList.remove('border-red-500');
        input.classList.add('border-green-500');

        const container = input.closest('div');
        if (!container) return;

        const feedback = container.querySelector('.js-dynamic-error');
        if (feedback) {
            feedback.style.display = 'none';
        }
    }

    function validarInput(input) {
        const valor = input.value.trim();
        let esValido = true;

        // Validación: ¿Está vacío?
        if (valor === '') {
            esValido = false;
        } 
        // Validación extra para el campo login (mínimo 4 caracteres)
        else if (input.name === 'login' && valor.length < 4) {
            esValido = false;
        }
        // Validación para password (mínimo 4 caracteres)
        else if (input.type === 'password' && valor.length < 4) {
            esValido = false;
        }

        if (esValido) {
            ocultarErrorMensaje(input);
        } else {
            input.classList.remove('border-green-500');
        }

        return esValido;
    }

    function verificarFormulario() {
        let todoCorrecto = true;
        inputs.forEach(input => {
            if (input.value.trim() === '') {
                todoCorrecto = false;
            }
            if (input.name === 'login' && input.value.trim().length < 4) {
                todoCorrecto = false;
            }
            if (input.type === 'password' && input.value.trim().length < 4) {
                todoCorrecto = false;
            }
        });
        submitBtn.disabled = !todoCorrecto;
    }

    inputs.forEach(input => {
        input.addEventListener('input', function () {
            validarInput(this);
            verificarFormulario();
        });

        input.addEventListener('blur', function () {
            if (this.value.trim() === '') {
                mostrarErrorMensaje(this, 'Este campo es obligatorio.');
            } else if (this.name === 'login' && this.value.trim().length < 4) {
                mostrarErrorMensaje(this, 'Debe ingresar al menos 4 caracteres.');
            } else if (this.type === 'password' && this.value.trim().length < 4) {
                mostrarErrorMensaje(this, 'La contraseña es muy corta.');
            }
            verificarFormulario();
        });
    });
});