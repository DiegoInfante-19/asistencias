document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    const inputs = form.querySelectorAll('input:not([type="hidden"])');

    // Bloqueamos el botón al inicio
    submitBtn.disabled = true;

    function validarInput(input) {
        const valor = input.value.trim();
        // Buscamos el feedback de Laravel o el nuestro
        const container = input.closest('.form-group');
        const feedback = container.querySelector('.invalid-feedback');

        let esValido = true;

        // Validación simple: ¿Está vacío?
        if (valor === '') {
            esValido = false;
        } 
        // Validación extra para el campo login (mínimo 7 caracteres para cédula o formato email)
        else if (input.name === 'login' && valor.length < 4) {
            esValido = false;
        }

        if (esValido) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            // Ocultamos cualquier mensaje de error previo (de Laravel o JS)
            if (feedback) {
                feedback.style.display = 'none';
            }
        } else {
            input.classList.remove('is-valid');
            // No ponemos 'is-invalid' mientras escribe para no molestar, 
            // a menos que ya estuviera marcado como error por Laravel
        }

        return esValido;
    }

    function verificarFormulario() {
        let todoCorrecto = true;
        inputs.forEach(input => {
            if (input.value.trim() === '') {
                todoCorrecto = false;
            }
            // Si es el campo login, pedimos un mínimo para activar el botón
            if (input.name === 'login' && input.value.trim().length < 4) {
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
                this.classList.add('is-invalid');
                const feedback = this.closest('.form-group').querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.textContent = 'Este campo es obligatorio.';
                    feedback.style.display = 'block';
                }
            }
        });
    });
});