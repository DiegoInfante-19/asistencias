@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.css" crossorigin="anonymous">
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">
    <div class="card">

        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h5 class="card-title fw-bold text-dark mb-0">Catálogo de Periodos y Eventos</h5>
            <button type="button" class="btn btn-outline-secondary ms-auto" data-bs-toggle="modal" data-bs-target="#createPeriodoModal">
                <i class="bi bi-calendar-plus me-1"></i> <b>Nuevo Periodo</b>
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-bordered table-striped table-hover align-middle nowrap', 'style' => 'width:100%;']) !!}
            </div>
        </div>
    </div>
</div>

@include('periodos_recesos.partials.modals')
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.js" crossorigin="anonymous"></script>

{!! $dataTable->scripts() !!}

<script>
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
@endsection