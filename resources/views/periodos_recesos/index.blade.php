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
        // Instancias globales de Flatpickr para los modales de periodos
        let pickerInicioCreate, pickerFinCreate;
        let pickerInicioEdit, pickerFinEdit;

        // Inicializar Flatpickr de manera libre (sin restricciones de días) cuando aplique
        if (typeof window.flatpickr !== 'undefined') {
            const configLibre = {
                locale: window.Spanish || "es",
                dateFormat: "Y-m-d",
                allowInput: false
            };

            // Para el modal de Crear
            pickerInicioCreate = window.flatpickr("#create-inicio-periodo", configLibre);
            pickerFinCreate = window.flatpickr("#create-fin-periodo", configLibre);

            // Para el modal de Modificar
            pickerInicioEdit = window.flatpickr("#edit-inicio-periodo", configLibre);
            pickerFinEdit = window.flatpickr("#edit-fin-periodo", configLibre);
        }

        // --- Lógica para Modal EDITAR ---
        $('#UpdatePeriodoModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            modal.find('#UpdatePeriodoForm').attr('action', button.data('url'));
            modal.find('#edit-nombre-periodo').val(button.data('nombre'));

            // Asignar fechas mediante Flatpickr si están inicializados
            if (pickerInicioEdit) {
                pickerInicioEdit.setDate(button.data('inicio'), true);
            } else {
                modal.find('#edit-inicio-periodo').val(button.data('inicio'));
            }

            if (pickerFinEdit) {
                pickerFinEdit.setDate(button.data('fin'), true);
            } else {
                modal.find('#edit-fin-periodo').val(button.data('fin'));
            }

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

            // 1. Textos básicos
            modal.find('#show-nombre-periodo').text(button.data('nombre'));

            // 2. Tipo de evento con colores institucionales
            var nivel = button.data('nivel');
            var badgeClass = 'bg-secondary';
            if (nivel === 'Académico') badgeClass = 'bg-primary';
            else if (nivel === 'Administrativo') badgeClass = 'bg-info text-dark';
            else if (nivel === 'Institucional') badgeClass = 'bg-dark';
            else if (nivel === 'Feriado Nacional') badgeClass = 'bg-success';

            modal.find('#show-nivel-periodo')
                .removeClass('bg-secondary bg-primary bg-info bg-dark bg-success text-dark')
                .addClass('badge ' + badgeClass + ' px-3 py-2 fs-6 shadow-sm')
                .text(nivel ? nivel : 'No especificado');

            // 3. Fecha formateada unificada
            var fechaHumana = button.data('fecha-humana');
            modal.find('#show-inicio-periodo').text(fechaHumana);

            // 4. Descripción
            var descripcion = button.data('descripcion');
            modal.find('#show-descripcion-periodo').text(descripcion ? descripcion : 'Sin descripción registrada.');

            // 5. Suspensión
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