@extends('layouts.admin')

@section('content')
<div class="content pt-4" style="margin: 20px;">
    <div class="card shadow-sm border-0">

        <!-- Cabecera de la tarjeta principal -->
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h5 class="card-title text-dark mb-0 fs-5" style="font-weight: 500;">
                Registro de Cohortes
            </h5>
            <button type="button" class="btn btn-primary fw-bold ms-auto" data-bs-toggle="modal" data-bs-target="#createCohorteModal">
                <i class="bi bi-calendar-plus me-1"></i> Añadir Cohorte
            </button>
        </div>

        <!-- Cuerpo de la tarjeta principal -->
        <div class="card-body bg-white py-4">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-striped table-hover align-middle w-100', 'style' => 'width:100%;']) !!} 
            </div>
        </div>

    </div>
</div>

@include('cohortes.partials.modals')
@endsection

@push('scripts')
<!-- 1. Script para el Modal adaptado a módulo estandarizado -->
<script type="module">
    $(document).ready(function() {
        $('#UpdateCohorteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            modal.find('#UpdateCohorteForm').attr('action', button.data('url'));
            modal.find('#edit-numero-cohorte').val(button.data('numero'));
            modal.find('#edit-descripcion').val(button.data('descripcion'));
            modal.find('#edit-estatus').val(button.data('estatus'));
        });
    });
</script>

<script src="{{ asset('js/admin-validations.js') }}" defer></script>

<!-- 2. Inicialización modular idéntica al resto de las tablas -->
{!! $dataTable->scripts(null, ['type' => 'module']) !!}
@endpush