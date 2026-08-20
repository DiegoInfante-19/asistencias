@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.css" crossorigin="anonymous">
@endsection

@section('content')
<!-- Encabezado de la página -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Personal y Profesores
    </h2>

    <nav>
        <ol class="flex items-center gap-2 text-sm">
            <li>
                <a class="hover:text-primary" href="{{ url('/') }}">Inicio /</a>
            </li>
            <li class="text-primary font-medium">Profesores</li>
        </ol>
    </nav>
</div>

<!-- Contenedor Principal (Reemplaza a div.card) -->
<div class="rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
    
    <!-- Cabecera de la Tarjeta -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <h4 class="text-xl font-semibold text-black dark:text-white">
            Personal y Profesores Registrados
        </h4>
        
        <!-- Botón Moderno de Nuevo Profesor -->
        <button type="button" data-bs-toggle="modal" data-bs-target="#createUserModal" 
            class="inline-flex items-center justify-center gap-2.5 rounded-md bg-primary py-2 px-4 text-center font-medium text-white hover:bg-opacity-90">
            <svg class="fill-current" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8zm-6 4a6 6 0 1 1 12 0 6 6 0 0 1-12 0zm-2 9a6.992 6.992 0 0 1 11.286-4.46c.451.35.83.774 1.131 1.25A7 7 0 0 1 20 21v1h-2v-1a5 5 0 0 0-10 0v1H4v-1a6.974 6.974 0 0 1 0-8z" fill="currentColor"/>
            </svg>
            Nuevo Profesor
        </button>
    </div>

    <!-- Contenedor de la Tabla -->
    <div class="max-w-full overflow-x-auto pb-4">
        {!! $dataTable->table(['class' => 'table table-bordered table-striped table-hover align-middle nowrap dt-responsive', 'style' => 'width:100%;']) !!}
    </div>

    <!-- Footer de la Tarjeta -->
    <div class="border-t border-stroke py-4 text-sm text-gray-500 dark:border-strokedark dark:text-gray-400">
        Procesamiento en tiempo real activo desde el servidor.
    </div>
</div>

@include('profesores.partials.modals')

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.js" crossorigin="anonymous"></script>

{!! $dataTable->scripts() !!}

<script>
    $(document).ready(function() {
        // 1. REAJUSTE DE DATATABLES (Responsive)
        $(window).on('resize', function() {
            if ($.fn.DataTable.isDataTable('#users-table')) {
                $('#users-table').DataTable().columns.adjust().responsive.recalc();
            }
        });

        // 2. LÓGICA PARA EL MODAL: VER DETALLES
        $('#viewUserModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            modal.find('#modal-username').text(button.data('username'));
            modal.find('#modal-name').text(button.data('name'));
            modal.find('#modal-lastname').text(button.data('lastname'));
            modal.find('#modal-cedula').text(button.data('cedula'));
            modal.find('#modal-email').text(button.data('email'));
            modal.find('#modal-phone').text(button.data('phone'));
            var status = button.data('status');
            var statusBadge = (status.toLowerCase() === 'activo') ?
                '<span class="inline-flex rounded-full bg-success bg-opacity-10 py-1 px-3 text-sm font-medium text-success">Activo</span>' :
                '<span class="inline-flex rounded-full bg-danger bg-opacity-10 py-1 px-3 text-sm font-medium text-danger">' + status + '</span>';
            modal.find('#modal-status').html(statusBadge);
        });

        // 3. LÓGICA PARA EL MODAL: EDITAR DATOS
        $('#editUserModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            if (!button.length) return;
            var modal = $(this);
            modal.find('#editUserForm').attr('action', button.data('url'));
            modal.find('#edit_url').val(button.data('url'));
            modal.find('#edit-username').val(button.data('username'));
            modal.find('#edit-name').val(button.data('name'));
            modal.find('#edit-lastname').val(button.data('lastname'));
            modal.find('#edit-cedula').val(button.data('cedula'));
            modal.find('#edit-phone').val(button.data('phone'));
            modal.find('#edit-email').val(button.data('email'));
            modal.find('#edit-status').val(button.data('status'));
            modal.find('#edit-rol').val(button.data('rol'));
            modal.find('input, select').trigger('input');
        });

        // 4. LÓGICA PARA AUTO-ABRIR MODAL TRAS ERROR DE VALIDACIÓN
        @if($errors->any())
            @if(old('origen') == 'modal')
            var createUserModal = new bootstrap.Modal(document.getElementById('createUserModal'));
            createUserModal.show();
            @elseif(old('_method') == 'PUT')
            $('#editUserForm').attr('action', $('#edit_url').val());
            var editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'));
            editUserModal.show();
            @endif
        @endif
    });
</script>
<script src="{{ asset('js/core-validations.js') }}" defer></script>
<script src="{{ asset('js/admin-validations.js') }}" defer></script>
@endsection