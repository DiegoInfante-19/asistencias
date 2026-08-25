@extends('layouts.admin')

@section('header')
    <x-page-header title="Personal y Profesores">
        <li class="breadcrumb-item active" aria-current="page" style="font-weight: 500;">Profesores</li>
    </x-page-header>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">
    <!-- Tarjeta Principal con diseño limpio -->
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h4 class="card-title text-dark mb-0" style="font-weight: 500;">Personal y Profesores Registrados</h4>
            <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="bi bi-person-plus-fill me-1" style="font-weight: 500;"></i> Añadir Profesor
            </button>
        </div>

        <!-- Cuerpo con fondo blanco -->
        <div class="card-body bg-white">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-striped table-hover align-middle w-100', 'style' => 'width:100%;']) !!}
            </div>
        </div>

        <div class="card-footer bg-white text-muted small py-3">
            Procesamiento en tiempo real activo desde el servidor.
        </div>

    </div>
</div>

@include('profesores.partials.modals')
@endsection

@push('scripts')
<!-- Script modular envuelto en type="module" -->
<script type="module">
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
                '<span class="badge bg-success px-3 py-2 shadow-sm" style="font-weight: 500; font-size: 0.9rem;">Activo</span>' :
                '<span class="badge bg-danger px-3 py-2 shadow-sm" style="font-weight: 500; font-size: 0.9rem;">' + status + '</span>';
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

<!-- Inicialización de DataTables de forma modular -->
{!! $dataTable->scripts(null, ['type' => 'module']) !!}
@endpush