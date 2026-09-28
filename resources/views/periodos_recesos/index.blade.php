@extends('layouts.admin')

@section('content')
<div class="content pt-4" style="margin: 20px;">
    <!-- Tarjeta Principal con diseño limpio y estilo unificado ecosystem-card -->
    <div class="card ecosystem-card">

        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h5 class="card-title text-dark mb-0 fs-5" style="font-weight: 500;">
                Registro de Eventos y Periodos 
            </h5>
            <button type="button" class="btn btn-primary ms-auto shadow-sm" data-bs-toggle="modal" data-bs-target="#createPeriodoModal">
                <i class="bi bi-calendar-plus me-1"></i> Añadir Periodo
            </button>
        </div>

        <!-- Cuerpo con fondo blanco -->
        <div class="card-body bg-white py-4">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-striped table-hover align-middle w-100', 'style' => 'width:100%;']) !!}
            </div>
        </div>

        <!-- Footer opcional para mantener la coherencia visual del ecosistema -->
        <div class="card-footer bg-light py-2 text-muted small border-top">
            Gestión de eventos y periodos académicos registrados en el sistema.
        </div>
    </div>
</div>

@include('periodos_recesos.partials.modals')
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
<!-- 1. Script del Modal: Envuelto en type="module" -->
<script type="module">
    $(document).ready(function() {
        // --- Lógica para Modal EDITAR ---
        $('#UpdatePeriodoModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            modal.find('#UpdatePeriodoForm').attr('action', button.data('url'));
            modal.find('#edit-nombre-periodo').val(button.data('nombre'));
            modal.find('#edit-inicio-periodo').val(button.data('inicio'));
            modal.find('#edit-fin-periodo').val(button.data('fin'));
            modal.find('#edit-descripcion-periodo').val(button.data('descripcion'));
            modal.find('#edit-nivel-periodo').val(button.data('nivel'));
            modal.find('#edit-tipo-receso').val(button.data('tipo'));

            if (button.data('suspension') == 1) {
                modal.find('#edit-suspension-periodo').prop('checked', true);
            } else {
                modal.find('#edit-suspension-periodo').prop('checked', false);
            }
        });

        // --- Lógica para Modal VER DETALLES (SHOW) ---
        $('#showPeriodoModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            // Textos básicos
            modal.find('#show-nombre-periodo').text(button.data('nombre'));
            modal.find('#show-nivel-periodo').text(button.data('nivel'));

            // Fechas formateadas (d/m/Y)
            modal.find('#show-inicio-periodo').text(button.data('inicio-format'));
            modal.find('#show-fin-periodo').text(button.data('fin-format'));

            // Descripción (manejando el caso de que sea null)
            var descripcion = button.data('descripcion');
            modal.find('#show-descripcion-periodo').text(descripcion ? descripcion : 'Sin descripción registrada.');

            // Construir el Badge visual para la suspensión
            var suspension = button.data('suspension');
            var badge = (suspension == 1) ?
                '<span class="badge bg-danger px-3 py-2 fs-6"><i class="bi bi-x-circle me-1"></i> Sí, se suspenden</span>' :
                '<span class="badge bg-success px-3 py-2 fs-6"><i class="bi bi-check-circle me-1"></i> No (Día hábil)</span>';
            modal.find('#show-suspension-periodo').html(badge);
        });
    });
</script>

<script src="{{ asset('js/admin-validations.js') }}" defer></script>

<!-- 2. Script de Yajra modular -->
{!! $dataTable->scripts(null, ['type' => 'module']) !!}
@endpush