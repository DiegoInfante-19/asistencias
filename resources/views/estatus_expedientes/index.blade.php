@extends('layouts.admin')

@section('content')
<div class="content pt-4" style="margin: 20px;">
    <!-- Tarjeta Principal con diseño limpio -->
    <div class="card border-0 shadow-sm">

        <!-- Cabecera de la tarjeta principal -->
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h5 class="card-title text-dark mb-0 fs-5" style="font-weight: 500;">
                Registro de Estatus de Expedientes
            </h5>
            <button type="button" class="btn btn-primary fw-bold ms-auto" data-bs-toggle="modal" data-bs-target="#createEstatusModal">
                <i class="bi bi-folder-plus me-1"></i> Añadir Estatus
            </button>
        </div>
        
        <!-- Cuerpo con fondo blanco puro -->
        <div class="card-body bg-white py-4">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-striped table-hover align-middle w-100', 'style' => 'width:100%;']) !!}
            </div>
        </div>
    </div>
</div>

@include('estatus_expedientes.partials.modals')
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