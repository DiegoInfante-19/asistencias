<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>

<script>
    // Inyectamos las variables de sesión
    const mensajeExito = @json(session('success'));
    const mensajeError = @json(session('error'));
    // ¡NUEVO!: Obtenemos todos los errores de validación (Cédulas duplicadas, correos en uso, etc.)
    const erroresValidacion = @json($errors->all());

    document.addEventListener('DOMContentLoaded', function() {
        
        const swalConfig = {
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
        };

        // 1. Mensajes de Éxito
        if (mensajeExito) {
            Swal.fire({
                ...swalConfig,
                icon: 'success',
                title: '¡Operación Exitosa!',
                text: mensajeExito,
                timer: 3000,
                showConfirmButton: false
            });
        }

        // 2. Mensajes de Error Generales
        if (mensajeError) {
            Swal.fire({
                ...swalConfig,
                icon: 'error',
                title: 'Ocurrió un problema',
                text: mensajeError,
            });
        }

        // 3. ERRORES DE VALIDACIÓN (Los que impiden el registro o login)
        if (erroresValidacion.length > 0) {
            // Construimos una lista HTML con todos los errores que devuelva Laravel
            let listaErrores = '<ul style="text-align: left; list-style: none; padding: 0; margin-top: 15px;">';
            erroresValidacion.forEach(function(error) {
                listaErrores += `<li style="margin-bottom: 8px;">
                                    <strong style="color: #dc3545;">•</strong> ${error}
                                 </li>`;
            });
            listaErrores += '</ul>';

            Swal.fire({
                ...swalConfig,
                icon: 'warning', // Usamos warning porque el usuario debe corregir datos
                title: 'Verifique los datos',
                html: 'Se encontraron los siguientes problemas al intentar procesar su solicitud:' + listaErrores,
                confirmButtonText: 'Entendido'
            });
        }

        // 4. Lógica Global para los botones de Eliminar (Panel Admin) - VERSIÓN NATIVA
        document.addEventListener('submit', function(e) {
            // Verificamos si el formulario disparado tiene la clase form-delete
            if (e.target && e.target.classList.contains('form-delete')) {
                e.preventDefault(); 
                const form = e.target;

                Swal.fire({
                    title: '¿Estás totalmente seguro?',
                    text: "Esta acción eliminará el registro de forma permanente.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="bi bi-trash"></i> Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); 
                    }
                });
            }
        });

        // 5. Confirmación para Cerrar Sesión
        // Seleccionamos el enlace por su icono o clase si prefieres, 
        // pero aquí usamos el ID o una clase específica en el botón de logout
        const logoutBtn = document.getElementById('logout-link');
        
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: '¿Cerrar sesión?',
                    text: "¿Estás seguro de que deseas salir del sistema?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, salir',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Enviamos el formulario que está en tu admin.blade.php
                        document.getElementById('logout-form').submit();
                    }
                });
            });
        }
    });
</script>