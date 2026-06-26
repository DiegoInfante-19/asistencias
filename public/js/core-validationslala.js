document.addEventListener("DOMContentLoaded", function () {
    // Ya no necesitamos hardcodear nada, leemos directamente lo que PHP nos envió
    const reglas = window.CoreRules;

    if (!reglas) {
        console.error(
            "CoreRules no está definido. Revisa la inyección en tu layout.",
        );
        return;
    }

    // Esta función limpia los mensajes y clases de error
    window.limpiarValidacion = function (input) {
        input.classList.remove("is-invalid", "is-valid");
        const feedback = input.parentNode.querySelector(".dynamic-feedback");
        if (feedback) {
            feedback.textContent = "";
            feedback.style.display = "none";
        }
    };

    // Esta función aplica la validación según la regla correspondiente
    window.validarCampo = function (input) {
        const nombreCampo = input.name;
        const valor = input.value.trim();
        let esValido = true;
        let mensajeError = "";

        // 1. Lógica especial para confirmación de contraseña
        if (nombreCampo === "password_confirmation") {
            const passInput = document.querySelector('input[name="password"]');
            if (valor !== passInput?.value) {
                esValido = false;
                mensajeError = "Las contraseñas no coinciden.";
            }
        }
        // 2. Lógica estándar unificada (simplificada y limpia)
        else if (reglas[nombreCampo]) {
            const regla = reglas[nombreCampo];

            // Evaluamos directamente la regex ya que config/regex.php trae los ^ y $
            if (input.required && valor === "") {
                esValido = false;
                mensajeError = "Este campo es obligatorio.";
            } else if (valor !== "" && !new RegExp(regla.html).test(valor)) {
                esValido = false;
                mensajeError = regla.error;
            }
        }

        // 3. Manejo de estados e interfaz (con soporte ARIA)
        const formGroup = input.closest(".form-group");
        const feedback = formGroup
            ? formGroup.querySelector(".dynamic-feedback")
            : null;

        if (!esValido) {
            input.classList.add("is-invalid");
            input.setAttribute("aria-invalid", "true"); // Accesibilidad
            if (feedback) {
                feedback.textContent = mensajeError;
                feedback.style.display = "block";
            }
        } else {
            input.classList.remove("is-invalid");
            input.setAttribute("aria-invalid", "false"); // Accesibilidad
            if (valor !== "") input.classList.add("is-valid");

            // Opcional: ocultar el mensaje si es válido
            if (feedback) feedback.style.display = "none";
        }

        return esValido;
    };
});

/*

esto que esta comentado es la antigua version que tenia
indow.CoreRules = {
    // USUARIO
    username: { 
    regex: /^[A-Z](?=.*\d)[a-zA-Z0-9_]{3,19}$/, 
    error: 'Debe iniciar con mayúscula, usar minúsculas y al menos un número, Sin espacios (4-20 caracteres).' 
},
    
    // CORREOS (Soporta ambos nombres de inputs)
    email: { 
        regex: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, 
        error: 'Ingrese un correo electrónico válido.' 
    },
    email_users: { 
        regex: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, 
        error: 'Ingrese un correo electrónico válido.' 
    },
    
    // NOMBRES (Soporta ambos nombres de inputs)
    name: { 
        regex: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/, 
        error: 'Solo letras y espacios (mínimo 3 caracteres).' 
    },
    name_users: { 
        regex: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/, 
        error: 'Solo letras y espacios (mínimo 3 caracteres).' 
    },
    
    // APELLIDOS
    last_name: { 
        regex: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/, 
        error: 'Solo letras y espacios (mínimo 3 caracteres).' 
    },
    last_name_users: { 
        regex: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/, 
        error: 'Solo letras y espacios (mínimo 3 caracteres).' 
    },
    
    // CÉDULA
    cedula: { 
        regex: /^\d{6,8}$/, 
        error: 'La cédula debe tener entre 6 y 8 números exactos. Sin espacios' 
    },
    cedula_users: { 
        regex: /^\d{6,8}$/, 
        error: 'La cédula debe tener entre 6 y 8 números exactos. Sin espacios' 
    },
    
    // TELÉFONO
    phone: { 
        regex: /^\d{10,11}$/, 
        error: 'El teléfono debe tener entre 10 y 11 números, sin guiones. Sin espacios' 
    },
    phone_users: { 
        regex: /^\d{10,11}$/, 
        error: 'El teléfono debe tener entre 10 y 11 números, sin guiones. Sin espacios' 
    },
    
    // CONTRASEÑA (Mucho más segura y amigable)
    password: { 
        regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.+-])[A-Za-z\d@$!%*?&.+-]{8,64}$/, 
        error: 'Mínimo 8 caracteres: al menos una mayúscula, una minúscula, un número y un símbolo. Sin espacios' 
    },

    respuesta1: { 
        // Acepta cualquier carácter, pero exige un mínimo de 3 caracteres.
        regex: /^.{3,150}$/, 
        error: 'La respuesta debe tener entre 3 y 150 caracteres.' 
    },
    
    respuesta2: { 
        regex: /^.{3,150}$/, 
        error: 'La respuesta debe tener entre 3 y 150 caracteres.' 
    }
};
*/
