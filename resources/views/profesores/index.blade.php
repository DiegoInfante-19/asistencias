@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.css" crossorigin="anonymous">
@endsection

@section('content')
<div class="content" style="margin: 20px;">
    <div class="card">

        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h3 class="card-title fw-bold text-dark mb-0">Personal y Profesores Registrados</h3>
            <button type="button" class="btn btn-outline-secondary ms-auto" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="bi bi-person-plus-fill me-1"></i><b>Nuevo Profesor</b>
            </button>
        </div>

        <div class="card-body">
            <div class="w-100" style="overflow: hidden;">
                {!! $dataTable->table(['class' => 'table table-bordered table-striped table-hover align-middle nowrap dt-responsive', 'style' => 'width:100%;']) !!}
            </div>
        </div>

        <div class="card-footer bg-white text-muted small py-3">
            Procesamiento en tiempo real activo desde el servidor.
        </div>

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
                '<span class="badge bg-success px-3 py-2">Activo</span>' :
                '<span class="badge bg-danger px-3 py-2">' + status + '</span>';
            modal.find('#modal-status').html(statusBadge);
        });

        // 3. LÓGICA PARA EL MODAL: EDITAR DATOS
        $('#editUserModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            // Si el modal se abre automáticamente por código (ej. error de validación), el botón no existe
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
            // --- ESTO ES LO QUE FALTABA AQUÍ ADENTRO ---
            // Simula que el usuario acaba de escribir en los campos para que 
            // el script de validación los revise y encienda el botón "Guardar Cambios".
            modal.find('input, select').trigger('input');
        });

        // 4. LÓGICA PARA AUTO-ABRIR MODAL TRAS ERROR DE VALIDACIÓN
        @if($errors -> any())
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