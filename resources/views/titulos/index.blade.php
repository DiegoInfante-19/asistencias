@extends('layouts.admin')

@section('content')
<div class="content pt-4" style="margin: 20px;">
    <!-- Tarjeta Principal con diseño limpio -->
    <div class="card border-0 shadow-sm">

        <!-- Cabecera de la tarjeta principal -->
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h5 class="card-title text-dark mb-0  fs-5" style="font-weight: 500;">
                Registro de Títulos
            </h5>
            <button type="button" class="btn btn-primary fw-bold ms-auto" data-bs-toggle="modal" data-bs-target="#createTituloModal">
                <i class="bi bi-plus-circle me-1"></i> Añadir Título
            </button>
        </div>

        <!-- Cuerpo con fondo blanco puro -->
        <div class="card-body bg-white py-4">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-striped table-hover align-middle w-100', 'style' => 'width:100%;']) !!}
            </div>
        </div>
        <div class="card-footer bg-white text-muted small py-3">
            Directorio principal de títulos académicos para el sistema.
        </div>
    </div>
</div>
@include('titulos.partials.modals')
@endsection

@push('scripts')
<!-- Script local envuelto en type="module" -->
<script type="module">
    $(document).ready(function() {
        $('#UpdateTituloModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            modal.find('#UpdateTituloForm').attr('action', button.data('url'));
            modal.find('#edit-nombre-titulo').val(button.data('nombre'));
            modal.find('#edit-nivel-titulo').val(button.data('nivel'));
        });
    });
</script>

<script src="{{ asset('js/admin-validations.js') }}" defer></script>

<!-- Inicialización de DataTables de forma modular -->
{!! $dataTable->scripts(null, ['type' => 'module']) !!}
@endpush