@extends('layouts.admin')

@section('content')
<div class="content pt-4" style="margin: 20px;">
    <!-- Tarjeta Principal con diseño unificado (Ecosystem Card) -->
    <div class="card shadow-sm ecosystem-card">

        <div class="card-header bg-white py-3 d-flex align-items-center border-bottom">
            <h5 class="card-title text-dark mb-0 fs-5" style="font-weight: 500;">
                Registro Programas Nacionales de Formación (PNF)
            </h5>
            <button type="button" class="btn btn-primary fw-bold ms-auto shadow-sm" data-bs-toggle="modal" data-bs-target="#createPnfModal">
                <i class="bi bi-plus-circle me-1"></i> Añadir PNF
            </button>
        </div>

        <!-- Cuerpo con fondo blanco -->
        <div class="card-body bg-white p-4">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-striped table-hover align-middle w-100 border', 'style' => 'width:100%;']) !!}
            </div>
        </div>
        
        <div class="card-footer bg-light py-2 text-muted small border-top">
            Gestión centralizada de Programas Nacionales de Formación.
        </div>
    </div>
</div>

@include('pnfs.partials.modals')
@endsection

@section('styles')
<style>
    /* ---------------------------------------------------
        ESTÉTICA UNIFICADA DEL ECOSISTEMA DE TARJETAS
    ----------------------------------------------------- */
    .ecosystem-card {
        border: 1px solid #cbd5e1 !important;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05) !important;
        background-color: #ffffff !important;
        border-radius: 0.5rem !important;
        overflow: hidden !important;
    }

    .ecosystem-card .card-header {
        background-color: #ffffff !important;
        border-bottom: 1px solid #e2e8f0 !important;
        border-top-left-radius: 0.5rem !important;
        border-top-right-radius: 0.5rem !important;
    }

    .ecosystem-card .card-footer {
        background-color: #f8f9fa !important;
        border-top: 1px solid #e2e8f0 !important;
        border-bottom-left-radius: 0.5rem !important;
        border-bottom-right-radius: 0.5rem !important;
    }
</style>
@endsection

@push('scripts')
<!-- 1. Script de lógica local envuelto en type="module" -->
<script type="module">
    $(document).ready(function() {
        // Llenar el modal de edición dinámicamente
        $('#UpdatePnfModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            modal.find('#UpdatePnfForm').attr('action', button.data('url'));
            modal.find('#edit-nombre-pnf').val(button.data('nombre'));
            modal.find('#edit-descripcion-pnf').val(button.data('descripcion'));
            modal.find('#edit-vigencia-pnf').val(button.data('vigencia'));
        });
        
        $('#showPnfModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            // Asignar Nombre
            modal.find('#show-nombre-pnf').text(button.data('nombre'));

            // Asignar Descripción 
            var descripcion = button.data('descripcion');
            modal.find('#show-descripcion-pnf').text(descripcion ? descripcion : 'Sin descripción registrada.');

            // Asignar Vigencia como un Badge visual estandarizado
            var vigencia = button.data('vigencia');
            var badge = (vigencia == 1) ?
                '<span class="badge bg-success px-3 py-2 shadow-sm" style="font-weight: 500; font-size: 0.9rem;">Activo</span>' :
                '<span class="badge bg-danger px-3 py-2 shadow-sm" style="font-weight: 500; font-size: 0.9rem;">Inactivo</span>';
            modal.find('#show-vigencia-pnf').html(badge);
        });
    });
</script>

<script src="{{ asset('js/admin-validations.js') }}" defer></script>

<!-- 2. Inicialización de DataTables de forma modular -->
{!! $dataTable->scripts(null, ['type' => 'module']) !!}
@endpush