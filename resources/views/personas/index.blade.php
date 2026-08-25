@extends('layouts.admin')

@section('styles')
<style>
    /* Azul muy claro (transparente) para Masculino */
    table.dataTable tbody tr.bg-masculino > td {
        background-color: rgba(13, 110, 253, 0.05) !important;
    }

    /* Rosa muy claro (transparente) para Femenino */
    table.dataTable tbody tr.bg-femenino > td {
        background-color: rgba(214, 51, 132, 0.05) !important;
    }

    /* Mantenemos un hover un poco más oscuro para interactividad */
    table.dataTable tbody tr.bg-masculino:hover > td {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }

    table.dataTable tbody tr.bg-femenino:hover > td {
        background-color: rgba(214, 51, 132, 0.1) !important;
    }
</style>
@endsection

@section('header')
    <x-page-header title="Personal y Profesores">
        <li class="breadcrumb-item active" aria-current="page" style="font-weight: 500;">Estudiantes</li>
    </x-page-header>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">
    <!-- Tarjeta Principal con diseño limpio -->
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h4 class="card-title text-dark mb-0" style="font-weight: 500;">Directorio de Estudiantes</h4>
            <a href="{{ route('personas.create') }}" class="btn btn-primary ms-auto">
                <i class="bi bi-person-plus-fill me-1" style="font-weight: 500;"></i> Añadir Estudiante
            </a>
        </div>

        <!-- Cuerpo con fondo blanco -->
        <div class="card-body bg-white">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-striped table-hover align-middle w-100', 'style' => 'width:100%;']) !!}
            </div>
        </div>

        <div class="card-footer bg-white text-muted small py-3">
            Procesamiento en tiempo real activo desde el servidor.
        </div>

    </div>
</div>
@endsection

@push('scripts')
<!-- Script modular envuelto en type="module" -->
<script type="module">
    $(document).ready(function() {
        // REAJUSTE DE DATATABLES (Responsive)
        $(window).on('resize', function() {
            if ($.fn.DataTable.isDataTable('#personas-table')) {
                $('#personas-table').DataTable().columns.adjust().responsive.recalc();
            }
        });
    });
</script>

<script src="{{ asset('js/core-validations.js') }}" defer></script>
<script src="{{ asset('js/admin-validations.js') }}" defer></script>

<!-- Inicialización de DataTables de forma modular -->
{!! $dataTable->scripts(null, ['type' => 'module']) !!}
@endpush