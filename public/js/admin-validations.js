document.addEventListener("DOMContentLoaded", function () {
    const reglas = window.CoreRules;

    function activarValidacion(formId) {
        const form = document.querySelector(formId);
        if (!form) return;

        const submitBtn = document.querySelector(`button[form="${form.id}"]`);
        const inputs = form.querySelectorAll(
            'input:not([type="hidden"]), select',
        );

        if (submitBtn) submitBtn.disabled = true;

        function validarInput(input) {
            const nombreCampo = input.name;
            const valor = input.value.trim();

            // CORRECCIÓN BOOTSTRAP 5:
            const contenedor = input.closest(".input-group, .mb-3, div");
            const feedback = contenedor
                ? contenedor.querySelector(".dynamic-feedback")
                : null;

            let esValido = true;
            let mensajeError = "";

            if (nombreCampo === "password_confirmation") {
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

                // Ocultar errores estáticos de Laravel
                if (contenedor) {
                    const erroresLaravel =
                        contenedor.querySelectorAll('[role="alert"]');
                    erroresLaravel.forEach((errorLaravel) => {
                        errorLaravel.classList.remove("d-block");
                        errorLaravel.style.display = "none";
                    });
                }
            }
            return esValido;
        }

        function verificarFormularioCompleto() {
            if (!submitBtn) return;
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
                } else if (reglas && reglas[nombreCampo]) {
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

        form.forzarValidacion = verificarFormularioCompleto;
    }

    document.addEventListener("hidden.bs.modal", function (event) {
        const formularios = event.target.querySelectorAll("form");
        formularios.forEach((form) => {
            form.reset();
            const inputs = form.querySelectorAll(
                'input:not([type="hidden"]), select',
            );
            inputs.forEach((input) => {
                input.classList.remove("is-valid", "is-invalid");
            });

            const feedbacks = form.querySelectorAll(
                ".dynamic-feedback, .invalid-feedback, [role='alert']",
            );
            feedbacks.forEach((feedback) => {
                feedback.textContent = "";
                feedback.style.display = "none";
                feedback.classList.remove("d-block");
            });

            const submitBtn = document.querySelector(
                `button[form="${form.id}"]`,
            );
            if (submitBtn) submitBtn.disabled = true;
        });
    });

    // SWEETALERT2 PARA FORMULARIOS DE ELIMINACIÓN ESTÁTICOS (.form-eliminar)
    document.addEventListener("submit", function (event) {
        if (event.target && event.target.classList.contains("form-eliminar")) {
            event.preventDefault();
            Swal.fire({
                title: "¿Estás seguro?",
                text: "¡Esta acción no se puede deshacer!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText:
                    '<i class="bi bi-trash me-1"></i> Sí, eliminar',
                cancelButtonText: "Cancelar",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit();
                }
            });
        }
    });

    const formEditar = document.getElementById("editUserForm");
    if (formEditar) {
        formEditar.addEventListener("submit", function (event) {
            if (this.checkValidity()) {
                event.preventDefault();
                Swal.fire({
                    title: "¿Guardar modificaciones?",
                    text: "¿Deseas aplicar los cambios realizados a este perfil?",
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#ffc107",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText:
                        '<i class="bi bi-save me-1"></i> Sí, guardar',
                    cancelButtonText: "Revisar nuevamente",
                    customClass: { confirmButton: "text-dark fw-bold" },
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            }
        });
    }

    const formCrear = document.getElementById("createUserForm");
    if (formCrear) {
        formCrear.addEventListener("submit", function (event) {
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
                                    response.message || "Registrado con éxito.",
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
                                    input.classList.add("is-invalid");
                                    const contenedor = input.closest(
                                        ".input-group, .mb-3, div",
                                    );
                                    const feedback = contenedor
                                        ? contenedor.querySelector(
                                              ".dynamic-feedback",
                                          )
                                        : null;
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

    // INTERCEPTOR GLOBAL ÚNICO ROBUSTO PARA .btn-delete-record (DATATABLES & VISTAS)
    $(document).on("click", ".btn-delete-record", function (e) {
        e.preventDefault();
        e.stopPropagation();

        const $btn = $(this);
        const formId = $btn.data("form-id");

        // Búsqueda por ID global o por proximidad en el DOM
        let formToSubmit = document.getElementById(formId);
        if (!formToSubmit) {
            formToSubmit =
                $btn.closest("td, div, tr").find("form#" + formId)[0] ||
                $btn.siblings("form")[0];
        }

        if (!formToSubmit) {
            console.error(
                "No se encontró el formulario de eliminación con ID: " + formId,
            );
            return;
        }

        // NIVEL 1: Primera advertencia
        Swal.fire({
            title: "¿Está seguro que quiere eliminar este registro?",
            text: "Esta acción lo quitará de la tabla principal.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Sí, quiero eliminarlo",
            cancelButtonText: "Cancelar",
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                // NIVEL 2: Confirmación estricta
                Swal.fire({
                    title: "Confirmación Final",
                    html: "Al eliminar este dato <b>se perderá de forma permanente</b> y podría afectar registros dependientes.<br><br>¿Confirmar eliminación?",
                    icon: "error",
                    showCancelButton: true,
                    confirmButtonColor: "#000000",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText:
                        '<i class="bi bi-trash-fill me-1"></i> Confirmar y Borrar',
                    cancelButtonText: "Cancelar",
                    reverseButtons: true,
                }).then((secondResult) => {
                    if (secondResult.isConfirmed) {
                        Swal.fire({
                            title: "Eliminando...",
                            text: "Por favor espere.",
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });

                        formToSubmit.submit();
                    }
                });
            }
        });
    });

    activarValidacion("#createUserForm");
    activarValidacion("#editUserForm");
    activarValidacion("#profileUpdateForm");
});