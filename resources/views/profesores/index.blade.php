@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.css" crossorigin="anonymous">

<style>
    /* Tus ajustes estéticos exactos */
    div.dataTables_wrapper div.dataTables_filter {
        text-align: right;
        margin-bottom: 15px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0 !important;
    }

    #users-table {
        width: 100% !important;
    }

    div.dataTables_wrapper div.dataTables_length {
        margin-bottom: 0 !important;
    }

    div.dataTables_wrapper div.dataTables_length label {
        margin-bottom: 0 !important;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    div.dataTables_wrapper div.dataTables_length select {
        width: auto;
        display: inline-block;
    }

    .btn-tabla {
        /* Usamos var() para llamar a la variable de Bootstrap */
        border: 1px solid var(--bs-secondary-color) !important;
        color: var(--bs-secondary-color) !important;
    }



    .btn-tabla:hover {
        background-color: var(--bs-secondary-color) !important;
        color: #ffffff !important;
        border: 1px solid var(--bs-secondary-color) !important;
        /* Eliminamos sombras interiores y degradados que causan la línea clara */
        box-shadow: none !important;
        background-image: none !important;

        /* Igualamos el color del borde con el del fondo para que se fusione perfecto */
        border-color: var(--bs-secondary-color) !important;
    }

    /* --- TRANSFORMACIÓN DE LA PAGINACIÓN A ESTILO OUTLINE-SECONDARY --- */

    /* 1. Estilo base (Botón inactivo / outline) */
    .dataTables_wrapper .pagination .page-link {
        color: var(--bs-secondary-color) !important;
        border-color: var(--bs-border-color) !important;
        background-color: #ffffff !important;
        box-shadow: none !important;
        /* Quitamos sombras al hacer clic */
    }

    /* 2. Estilo Hover (Al pasar el mouse por encima) */
    .dataTables_wrapper .pagination .page-link:hover {
        background-color: var(--bs-secondary-color) !important;
        border-color: var(--bs-secondary-color) !important;
        color: #ffffff !important;
    }

    /* 3. Estilo Activo (La página en la que estás actualmente) */
    .dataTables_wrapper .pagination .page-item.active .page-link {
        background-color: var(--bs-secondary-color) !important;
        border-color: var(--bs-secondary-color) !important;
        color: #ffffff !important;
    }

    /* 4. Estilo Deshabilitado (Cuando ya no hay más páginas) */
    .dataTables_wrapper .pagination .page-item.disabled .page-link {
        color: #adb5bd !important;
        background-color: #f8f9fa !important;
        border-color: var(--bs-border-color) !important;
    }
</style>
@endsection

@section('content')
<div class="content" style="margin: 20px;">
    <div class="card">
        <div class="card-header bg-white py-3" style="position: relative;">

            <h3 class="card-title fw-bold text-dark mb-0">Personal y Profesores Registrados</h3>

            <button type="button" class="btn btn-outline-secondary btn-tabla" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%);" data-bs-toggle="modal" data-bs-target="#createUserModal">
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
            var button = $(event.relatedTarget); // Identifica el botón presionado
            var modal = $(this);
            // Extraemos e inyectamos datos como texto
            modal.find('#modal-username').text(button.data('username'));
            modal.find('#modal-name').text(button.data('name'));
            modal.find('#modal-lastname').text(button.data('lastname'));
            modal.find('#modal-cedula').text(button.data('cedula'));
            modal.find('#modal-email').text(button.data('email'));
            modal.find('#modal-phone').text(button.data('phone'));
            // Etiqueta visual para el estado
            var status = button.data('status');
            var statusBadge = (status.toLowerCase() === 'activo') ?
                '<span class="badge bg-success px-3 py-2">Activo</span>' :
                '<span class="badge bg-danger px-3 py-2">' + status + '</span>';
            modal.find('#modal-status').html(statusBadge);
        });
        // 3. LÓGICA PARA EL MODAL: EDITAR DATOS (¡NUEVO!)
        $('#editUserModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Identifica el botón del lápiz presionado
            var modal = $(this);
            // A. Inyectamos la URL dinámica al atributo "action" del formulario
            modal.find('#editUserForm').attr('action', button.data('url'));
            // B. Llenamos los <input> y <select> con la información actual (.val)
            modal.find('#edit-name').val(button.data('name'));
            modal.find('#edit-lastname').val(button.data('lastname'));
            modal.find('#edit-cedula').val(button.data('cedula'));
            modal.find('#edit-phone').val(button.data('phone'));
            modal.find('#edit-email').val(button.data('email'));
            modal.find('#edit-status').val(button.data('status'));
        });
    });
</script>
@endsection

<!-- 
    modificacion *
    eliminacion  *
    creacion     *
    validacion   
    preguntar antes de hacer cada cosa
    modal de respuesta positiva
    diseño perfil
    modales perfil
    
-->