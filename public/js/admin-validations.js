// Función constructora que aplica tu lógica a un formulario específico
window.activarValidacion = function (formId) {
    const form = document.querySelector(formId);
    if (!form) return;

    const submitBtn = document.querySelector(`button[form="${form.id}"]`);
    const inputs = form.querySelectorAll('input:not([type="hidden"]), select');

    if (submitBtn) submitBtn.disabled = true;

    // Función auxiliar para verificar si el formulario debe desbloquearse
    function verificarFormularioCompleto() {
        let formularioValido = true;
        inputs.forEach((input) => {
            // Si el campo es requerido y está vacío, el form no es válido
            if (input.required && input.value.trim() === "") {
                formularioValido = false;
            }
            // Si el campo tiene error de validación, el form no es válido
            if (input.classList.contains("is-invalid")) {
                formularioValido = false;
            }
        });
        if (submitBtn) submitBtn.disabled = !formularioValido;
    }

    // Eventos delegados a las funciones globales de core-validations.js
    inputs.forEach((input) => {
        input.addEventListener("input", function () {
            // CAMBIO AQUÍ: Llamamos a nuestra función local que gestiona la UI
            window.validarInput(this);
            verificarFormularioCompleto();
        });
        input.addEventListener("blur", function () {
            // CAMBIO AQUÍ: Llamamos a nuestra función local que gestiona la UI
            window.validarInput(this);
            verificarFormularioCompleto();
        });
    });

    // Exponemos la función al formulario para uso externo
    form.forzarValidacion = verificarFormularioCompleto;
};

window.validarInput = function (input) {
    // Ejecutamos la validación del motor
    window.validarCampo(input);

    const feedback = input.parentElement.querySelector(".dynamic-feedback");

    if (input.classList.contains("is-invalid")) {
        if (feedback) {
            feedback.classList.add("d-block");
            feedback.style.display = "block";
        }
    } else {
        if (feedback) {
            feedback.classList.remove("d-block");
            feedback.style.display = "none";
        }
    }
};

// Listener global para capturar el cierre de cualquier modal
document.addEventListener("hidden.bs.modal", function (event) {
    const formularios = event.target.querySelectorAll("form");
    formularios.forEach((form) => {
        form.reset();
        const inputs = form.querySelectorAll(
            'input:not([type="hidden"]), select',
        );
        inputs.forEach((input) => window.limpiarValidacion(input)); // Limpiamos vía core-validations

        const submitBtn = document.querySelector(`button[form="${form.id}"]`);
        if (submitBtn) submitBtn.disabled = true;
    });
});

// =================================================================
// INTEGRACIÓN SWEETALERT2 Y AJAX (Se mantiene igual)
// =================================================================

// 1. Confirmación para Eliminar Profesor
document.addEventListener("submit", function (event) {
    if (event.target && event.target.classList.contains("form-eliminar")) {
        event.preventDefault();
        Swal.fire({
            title: "¿Estás seguro?",
            text: "¡Esta acción no se puede deshacer!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            confirmButtonText: "Sí, eliminar",
        }).then((result) => {
            if (result.isConfirmed) event.target.submit();
        });
    }
});

// 2. Envío de formularios (Editar/Crear)
const formsToHandle = ["#editUserForm", "#createUserForm"];
formsToHandle.forEach((id) => {
    const form = document.querySelector(id);
    if (form) {
        form.addEventListener("submit", function (event) {
            // Forzamos validación antes de enviar
            if (this.checkValidity()) {
                event.preventDefault();
                Swal.fire({
                    title: "¿Guardar modificaciones?",
                    text: "¿Deseas aplicar los cambios realizados a este perfil?",
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#ffc107", // Amarillo warning de Bootstrap
                    cancelButtonColor: "#6c757d",
                    confirmButtonText:
                        '<i class="bi bi-save me-1"></i> Sí, guardar',
                    cancelButtonText: "Revisar nuevamente",
                    customClass: {
                        confirmButton: "text-dark fw-bold",
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            }
        });
    }
});

// 3. Envío asíncrono para Crear Profesor (DEBES MANTENER ESTO)
const formCrear = document.getElementById("createUserForm");
if (formCrear) {
    formCrear.addEventListener("submit", function (event) {
        // Usamos checkValidity() (nativo) que ahora es mucho más preciso
        // gracias a que tu core-validations ya marcó las clases is-invalid/is-valid
        if (this.checkValidity()) {
            event.preventDefault();

            const formData = new FormData(this);
            const actionUrl = this.getAttribute("action");
            const submitBtn = document.querySelector(
                `button[form="${this.id}"]`,
            );
            if (submitBtn) submitBtn.disabled = true;

            $.ajax({
                url: actionUrl,
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: { "X-Requested-With": "XMLHttpRequest" },
                success: function (response) {
                    if (response.success) {
                        const modalEl =
                            document.getElementById("createUserModal");
                        const modalInstance =
                            bootstrap.Modal.getInstance(modalEl) ||
                            new bootstrap.Modal(modalEl);
                        modalInstance.hide();

                        if ($.fn.DataTable.isDataTable("#users-table")) {
                            $("#users-table")
                                .DataTable()
                                .ajax.reload(null, false);
                        }

                        Swal.fire({
                            title: "¡Registrado!",
                            text:
                                response.message ||
                                "El profesor ha sido registrado con éxito.",
                            icon: "success",
                            confirmButtonColor: "#3085d6",
                            confirmButtonText: "Aceptar",
                        });
                    }
                },
                error: function (xhr) {
                    if (submitBtn) submitBtn.disabled = false;
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach((key) => {
                            const input = formCrear.querySelector(
                                `[name="${key}"]`,
                            );
                            if (input) {
                                // Aquí puedes seguir usando la clase para marcar error
                                input.classList.add("is-invalid");
                                const feedback =
                                    input.parentNode.querySelector(
                                        ".dynamic-feedback",
                                    );
                                if (feedback) {
                                    feedback.textContent = errors[key][0];
                                    feedback.style.display = "block";
                                }
                            }
                        });
                    }
                },
            });
        }
    });
}

// Inicializamos
document.addEventListener("DOMContentLoaded", function () {
    if (document.getElementById("createUserForm"))
        activarValidacion("#createUserForm");
    if (document.getElementById("editUserForm"))
        activarValidacion("#editUserForm");
    if (document.getElementById("profileUpdateForm"))
        activarValidacion("#profileUpdateForm");
});

/*
codigo viejo de public\js\admin-validations.js [

document.addEventListener("DOMContentLoaded", function () {
    const reglas = window.CoreRules;

    // Función constructora que aplica tu lógica a un formulario específico
    function activarValidacion(formId) {
        const form = document.querySelector(formId);
        if (!form) return; // Si el formulario no existe en la página, no hace nada

        const submitBtn = document.querySelector(`button[form="${form.id}"]`);
        const inputs = form.querySelectorAll(
            'input:not([type="hidden"]), select',
        );

        submitBtn.disabled = true;

        function validarInput(input) {
            const nombreCampo = input.name;
            const valor = input.value.trim();
            const feedback =
                input.parentNode.querySelector(".dynamic-feedback");
            let esValido = true;
            let mensajeError = "";

            if (nombreCampo === "password_confirmation") {
                // Buscamos la contraseña (funciona tanto para admin como para auth)
                const passInput =
                    document.getElementById("password") ||
                    document.querySelector('input[name="password"]');
                const passwordPrincipal = passInput ? passInput.value : "";

                if (valor === "") {
                    esValido = false;
                    mensajeError = "Debe confirmar su contraseña.";
                } else if (valor !== passwordPrincipal) {
                    esValido = false;
                    mensajeError = "Las contraseñas no coinciden.";
                }
            } else if (reglas && reglas[nombreCampo]) {
                const regla = reglas[nombreCampo];
                if (input.required && valor === "") {
                    esValido = false;
                    mensajeError = "Este campo es obligatorio.";
                } else if (valor !== "" && !regla.regex.test(valor)) {
                    esValido = false;
                    mensajeError = regla.error;
                }
            }

            if (!esValido) {
                input.classList.add("is-invalid");
                input.classList.remove("is-valid");
                if (feedback) {
                    feedback.textContent = mensajeError;
                    feedback.style.display = "block";
                }
            } else {
                input.classList.remove("is-invalid");
                if (valor !== "") {
                    input.classList.add("is-valid");
                } else {
                    input.classList.remove("is-valid");
                }
                if (feedback) {
                    feedback.textContent = "";
                    feedback.style.display = "none";
                }

                // ¡EL ARREGLO DEFINITIVO!
                // 1. Encontramos la "caja" principal de este campo usando closest()
                const formGroup = input.closest(".form-group");
                if (formGroup) {
                    // 2. Buscamos específicamente los errores de Laravel (tienen role="alert")
                    const erroresLaravel =
                        formGroup.querySelectorAll('[role="alert"]');
                    erroresLaravel.forEach((errorLaravel) => {
                        // 3. Le quitamos la clase rebelde de Bootstrap y lo ocultamos
                        errorLaravel.classList.remove("d-block");
                        errorLaravel.style.display = "none";
                    });
                }
            }
            return esValido;
        }

        function verificarFormularioCompleto() {
            let formularioValido = true;
            inputs.forEach((input) => {
                const nombreCampo = input.name;
                const valor = input.value.trim();

                if (nombreCampo === "password_confirmation") {
                    const passInput = form.querySelector(
                        'input[name="password"]',
                    );
                    if (
                        passInput &&
                        (valor !== passInput.value || valor === "")
                    )
                        formularioValido = false;
                } else if (reglas[nombreCampo]) {
                    if (input.required && valor === "")
                        formularioValido = false;
                    if (valor !== "" && !reglas[nombreCampo].regex.test(valor))
                        formularioValido = false;
                } else if (input.required && valor === "") {
                    formularioValido = false;
                }
            });
            submitBtn.disabled = !formularioValido;
        }

        // Eventos
        inputs.forEach((input) => {
            input.addEventListener("input", function () {
                validarInput(this);
                verificarFormularioCompleto();
            });
            input.addEventListener("blur", function () {
                validarInput(this);
                verificarFormularioCompleto();
            });
        });

        // Exponemos la función al formulario para poder forzar la validación vía jQuery
        form.forzarValidacion = verificarFormularioCompleto;
    }

    // Listener global para capturar el cierre de cualquier modal en el documento
    document.addEventListener("hidden.bs.modal", function (event) {
        console.log("Modal cerrado (evento detectado):", event.target.id);

        // Buscamos TODOS los formularios que estén dentro del modal que se cerró
        const formularios = event.target.querySelectorAll("form");

        formularios.forEach((form) => {
            console.log("Reseteando formulario...", form.id);

            // 1. Limpiar los valores de todos los inputs
            form.reset();

            // 2. Remover las clases de validación
            const inputs = form.querySelectorAll(
                'input:not([type="hidden"]), select',
            );
            inputs.forEach((input) => {
                input.classList.remove("is-valid", "is-invalid");
            });

            // 3. Vaciar y ocultar los contenedores de feedback
            const feedbacks = form.querySelectorAll(
                ".dynamic-feedback, .invalid-feedback",
            );
            feedbacks.forEach((feedback) => {
                feedback.textContent = "";
                feedback.style.display = "none";
            });

            // Limpiar también los errores estáticos de Laravel si los hay
            const erroresLaravel = form.querySelectorAll('[role="alert"]');
            erroresLaravel.forEach((errorLaravel) => {
                errorLaravel.classList.remove("d-block");
                errorLaravel.style.display = "none";
            });

            // 4. Volver a bloquear el botón de Submit asociado a este formulario en específico
            const submitBtn = document.querySelector(
                `button[form="${form.id}"]`,
            );
            if (submitBtn) {
                submitBtn.disabled = true;
            }
        });
    });

    // =================================================================
    // INTEGRACIÓN SWEETALERT2: CONFIRMACIONES DE FORMULARIOS
    // =================================================================

    // 1. Confirmación para Eliminar Profesor (Delegación de Eventos para DataTables)
    document.addEventListener("submit", function (event) {
        // Verificamos si el elemento que disparó el submit tiene la clase form-eliminar
        if (event.target && event.target.classList.contains("form-eliminar")) {
            event.preventDefault(); // Detenemos el envío inmediato

            Swal.fire({
                title: "¿Estás seguro?",
                text: "Estás a punto de eliminar a este profesor. ¡Esta acción no se puede deshacer!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33", // Rojo peligro
                cancelButtonColor: "#6c757d", // Gris secundario
                confirmButtonText:
                    '<i class="bi bi-trash me-1"></i> Sí, eliminar',
                cancelButtonText: "Cancelar",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit(); // Enviamos el formulario específico
                }
            });
        }
    });

    // 2. Confirmación para Guardar Cambios (Edición)
    const formEditar = document.getElementById("editUserForm");

    if (formEditar) {
        formEditar.addEventListener("submit", function (event) {
            // Verificamos si el formulario ya fue validado por tu función actual
            if (this.checkValidity()) {
                event.preventDefault(); // Detenemos el envío

                Swal.fire({
                    title: "¿Guardar modificaciones?",
                    text: "¿Deseas aplicar los cambios realizados a este perfil?",
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#ffc107", // Amarillo warning de Bootstrap
                    cancelButtonColor: "#6c757d",
                    confirmButtonText:
                        '<i class="bi bi-save me-1"></i> Sí, guardar',
                    cancelButtonText: "Revisar nuevamente",
                    customClass: {
                        confirmButton: "text-dark fw-bold",
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            }
        });
    }

    // 3. Envío asíncrono para Crear Profesor
    const formCrear = document.getElementById("createUserForm");
    if (formCrear) {
        formCrear.addEventListener("submit", function (event) {
            if (this.checkValidity()) {
                event.preventDefault(); // Detenemos el envío tradicional

                const formData = new FormData(this);
                const actionUrl = this.getAttribute("action");

                // Deshabilitamos el botón para evitar doble submit
                const submitBtn = document.querySelector(
                    `button[form="${this.id}"]`,
                );
                if (submitBtn) submitBtn.disabled = true;

                $.ajax({
                    url: actionUrl,
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                    },
                    success: function (response) {
                        if (response.success) {
                            // Cerrar el modal
                            const modalEl =
                                document.getElementById("createUserModal");
                            const modalInstance =
                                bootstrap.Modal.getInstance(modalEl) ||
                                new bootstrap.Modal(modalEl);
                            modalInstance.hide();

                            // Recargar DataTable
                            if ($.fn.DataTable.isDataTable("#users-table")) {
                                $("#users-table")
                                    .DataTable()
                                    .ajax.reload(null, false);
                            }

                            // SweetAlert de éxito
                            Swal.fire({
                                title: "¡Registrado!",
                                text:
                                    response.message ||
                                    "El profesor ha sido registrado con éxito.",
                                icon: "success",
                                confirmButtonColor: "#3085d6",
                                confirmButtonText: "Aceptar",
                            });
                        }
                    },
                    error: function (xhr) {
                        // Rehabilitar botón
                        if (submitBtn) submitBtn.disabled = false;

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            // Encender los errores en el modal
                            Object.keys(errors).forEach((key) => {
                                const input = formCrear.querySelector(
                                    `[name="${key}"]`,
                                );
                                if (input) {
                                    input.classList.add("is-invalid");
                                    const feedback =
                                        input.parentNode.querySelector(
                                            ".dynamic-feedback",
                                        );
                                    if (feedback) {
                                        feedback.textContent = errors[key][0];
                                        feedback.style.display = "block";
                                    }
                                }
                            });
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: "Ocurrió un error inesperado al guardar el registro.",
                                icon: "error",
                                confirmButtonColor: "#d33",
                                confirmButtonText: "Aceptar",
                            });
                        }
                    },
                });
            }
        });
    }

    // Inicializamos ambos formularios
    activarValidacion("#createUserForm");
    activarValidacion("#editUserForm");
    activarValidacion("#profileUpdateForm");
});
*/
