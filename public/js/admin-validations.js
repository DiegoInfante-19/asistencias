document.addEventListener('DOMContentLoaded', function () {

    const reglas = window.CoreRules;

    // Función constructora que aplica tu lógica a un formulario específico
    function activarValidacion(formId) {
        const form = document.querySelector(formId);
        if (!form) return; // Si el formulario no existe en la página, no hace nada

        const submitBtn = document.querySelector(`button[form="${form.id}"]`);
        const inputs = form.querySelectorAll('input:not([type="hidden"]), select');

        submitBtn.disabled = true;

        function validarInput(input) {
            const nombreCampo = input.name;
            const valor = input.value.trim();
            const feedback = input.parentNode.querySelector('.dynamic-feedback');
            let esValido = true;
            let mensajeError = '';

            if (nombreCampo === 'password_confirmation') {
                // Buscamos la contraseña (funciona tanto para admin como para auth)
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

                // ¡EL ARREGLO DEFINITIVO!
                // 1. Encontramos la "caja" principal de este campo usando closest()
                const formGroup = input.closest('.form-group');
                if (formGroup) {
                    // 2. Buscamos específicamente los errores de Laravel (tienen role="alert")
                    const erroresLaravel = formGroup.querySelectorAll('[role="alert"]');
                    erroresLaravel.forEach(errorLaravel => {
                        // 3. Le quitamos la clase rebelde de Bootstrap y lo ocultamos
                        errorLaravel.classList.remove('d-block');
                        errorLaravel.style.display = 'none';
                    });
                }
            }
            return esValido;
        }

        function verificarFormularioCompleto() {
            let formularioValido = true;
            inputs.forEach(input => {
                const nombreCampo = input.name;
                const valor = input.value.trim();

                if (nombreCampo === 'password_confirmation') {
                    const passInput = form.querySelector('input[name="password"]');
                    if (passInput && (valor !== passInput.value || valor === '')) formularioValido = false;
                } else if (reglas[nombreCampo]) {
                    if (input.required && valor === '') formularioValido = false;
                    if (valor !== '' && !reglas[nombreCampo].regex.test(valor)) formularioValido = false;
                } else if (input.required && valor === '') {
                    formularioValido = false;
                }
            });
            submitBtn.disabled = !formularioValido;
        }

        // Eventos
        inputs.forEach(input => {
            input.addEventListener('input', function () { validarInput(this); verificarFormularioCompleto(); });
            input.addEventListener('blur', function () { validarInput(this); verificarFormularioCompleto(); });
        });

        // Exponemos la función al formulario para poder forzar la validación vía jQuery
        form.forzarValidacion = verificarFormularioCompleto;
    }

    // Inicializamos ambos formularios
    activarValidacion('#createUserForm');
    activarValidacion('#editUserForm');
});