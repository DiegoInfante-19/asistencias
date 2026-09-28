@extends('layouts.admin')

@section('content')
<div class="content pt-4" style="margin: 20px;">
    <!-- Tarjeta Principal con diseño unificado (Ecosystem Card) -->
    <div class="card shadow-sm ecosystem-card">

        <!-- Cabecera de la tarjeta principal -->
        <div class="card-header bg-white py-3 d-flex align-items-center border-bottom">
            <h5 class="card-title text-dark mb-0 fs-5" style="font-weight: 500;">
                Registro de Estatus de Expedientes
            </h5>
            <button type="button" class="btn btn-primary fw-bold ms-auto shadow-sm" data-bs-toggle="modal" data-bs-target="#createEstatusModal">
                <i class="bi bi-folder-plus me-1"></i> Añadir Estatus
            </button>
        </div>
        
        <!-- Cuerpo con fondo blanco puro -->
        <div class="card-body bg-white p-4">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-striped table-hover align-middle w-100 border', 'style' => 'width:100%;']) !!}
            </div>
        </div>

        <!-- Footer añadido para cerrar el diseño de la tarjeta -->
        <div class="card-footer bg-light py-2 text-muted small border-top">
            Directorio general de estatus académicos para los expedientes.
        </div>
    </div>
</div>

@include('estatus_expedientes.partials.modals')
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
        // Llenado dinámico del modal de edición
        $('#UpdateEstatusModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            
            // Actualizar la ruta del formulario (action)
            modal.find('#UpdateEstatusForm').attr('action', button.data('url'));
            
            // Inyectar el nombre del estatus en el input
            modal.find('#edit-nombre-estatus').val(button.data('nombre'));
        });
    });
</script>

<script src="{{ asset('js/admin-validations.js') }}" defer></script>

<!-- 2. Inicialización modular de Yajra DataTables -->
{!! $dataTable->scripts(null, ['type' => 'module']) !!}
@endpush