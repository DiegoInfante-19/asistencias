@extends('layouts.admin')

@section('content')
<div class="content pt-4" style="margin: 20px;">
    <!-- Tarjeta Principal con diseño limpio -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h5 class="card-title text-dark mb-0 fs-5" style="font-weight: 500;">
                Registro de Cargos
            </h5>
            <button type="button" class="btn btn-primary fw-bold ms-auto" data-bs-toggle="modal" data-bs-target="#createCargoModal">
                <i class="bi bi-plus-circle me-1"></i> Añadir Cargo
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

@include('cargos.partials.modals')
@endsection

@push('scripts')
<!-- 1. Script del Modal envuelto en type="module" -->
<script type="module">
    $(document).ready(function() {
        $('#UpdateCargoModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            modal.find('#UpdateCargoForm').attr('action', button.data('url'));
            modal.find('#edit-descripcion-cargo').val(button.data('descripcion'));
        });
    });
</script>

<script src="{{ asset('js/admin-validations.js') }}" defer></script>

<!-- 2. Inicialización modular de Yajra DataTables -->
{!! $dataTable->scripts(null, ['type' => 'module']) !!}
@endpush