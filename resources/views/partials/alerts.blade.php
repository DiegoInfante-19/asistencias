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

        // 4. Lógica Global para los botones de Eliminar (Panel Admin)
        $('.form-delete').on('submit', function(e) {
            e.preventDefault(); 
            var form = this;

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
        });
    });
</script>