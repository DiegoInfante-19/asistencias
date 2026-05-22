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
        <div class="card-header">
            <h3 class="card-title fw-bold text-dark mb-0">Personal y Profesores Registrados</h3>
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


<div class="modal fade" id="viewUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h1 class="modal-title fs-5 fw-bold" id="viewUserModalLabel">
                    <i class="bi bi-person-vcard me-2"></i>Ficha del Profesor
                </h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4 text-dark">
                    <div class="col-md-4">
                        <label class="text-muted small fw-bold text-uppercase">Nombres</label>
                        <div class="fs-5" id="modal-name"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small fw-bold text-uppercase">Apellidos</label>
                        <div class="fs-5" id="modal-lastname"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small fw-bold text-uppercase">Cédula</label>
                        <div class="fs-5" id="modal-cedula"></div>
                    </div>

                    <hr class="my-2">

                    <div class="col-md-4">
                        <label class="text-muted small fw-bold text-uppercase">Usuario del Sistema</label>
                        <div class="fs-6" id="modal-username"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small fw-bold text-uppercase">Correo Electrónico</label>
                        <div class="fs-6" id="modal-email"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small fw-bold text-uppercase">Teléfono</label>
                        <div class="fs-6" id="modal-phone"></div>
                    </div>

                    <hr class="my-2">

                    <div class="col-md-4">
                        <label class="text-muted small fw-bold text-uppercase">Estado Actual</label>
                        <div class="mt-1" id="modal-status"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar Ficha</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <form id="editUserForm" method="POST" action="">
                @csrf
                @method('PUT')

                <div class="modal-header bg-warning text-dark">
                    <h1 class="modal-title fs-5 fw-bold" id="editUserModalLabel">
                        <i class="bi bi-pencil-square me-2"></i>Modificar Datos del Profesor
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Nombres</label>
                            <input type="text" class="form-control" name="name" id="edit-name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Apellidos</label>
                            <input type="text" class="form-control" name="last_name" id="edit-lastname" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Cédula</label>
                            <input type="text" class="form-control" name="cedula" id="edit-cedula" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Teléfono</label>
                            <input type="text" class="form-control" name="phone" id="edit-phone">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold small text-muted">Correo Electrónico</label>
                            <input type="email" class="form-control" name="email" id="edit-email" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">Estado</label>
                            <select class="form-select" name="status" id="edit-status" required>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                                <option value="Suspendido">Suspendido</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning fw-bold text-dark">
                        <i class="bi bi-save me-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection




@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.js" crossorigin="anonymous"></script>

{!! $dataTable->scripts() !!}

<script>
    $(document).ready(function() {
        $(window).on('resize', function() {
            if ($.fn.DataTable.isDataTable('#users-table')) {
                $('#users-table').DataTable().columns.adjust().responsive.recalc();
            }
        });
    });
    $('#viewUserModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Identifica exactamente qué botón se pulsó
        var modal = $(this);

        // Extraemos los datos del botón y los inyectamos en la vista del modal
        modal.find('#modal-username').text(button.data('username'));
        modal.find('#modal-name').text(button.data('name'));
        modal.find('#modal-lastname').text(button.data('lastname'));
        modal.find('#modal-cedula').text(button.data('cedula'));
        modal.find('#modal-email').text(button.data('email'));
        modal.find('#modal-phone').text(button.data('phone'));

        // Recreamos la etiqueta visual (badge) para el estado dentro del modal
        var status = button.data('status');
        var statusBadge = (status.toLowerCase() === 'activo') ?
            '<span class="badge bg-success px-3 py-2">Activo</span>' :
            '<span class="badge bg-danger px-3 py-2">' + status + '</span>';
        modal.find('#modal-status').html(statusBadge);
    });
</script>
@endsection