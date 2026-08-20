@extends('layouts.app')

@section('styles')
<!-- Mantenemos el CSS de DataTables para que la tabla y su paginación no se rompan -->
<link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.css" crossorigin="anonymous">
@endsection

@section('content')
<!-- Encabezado de la página (Reemplaza al antiguo x-page-header) -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Cohortes Académicos
    </h2>

    <nav>
        <ol class="flex items-center gap-2 text-sm">
            <li>
                <a class="hover:text-primary" href="{{ url('/') }}">Inicio /</a>
            </li>
            <li class="text-primary font-medium">Cohortes</li>
        </ol>
    </nav>
</div>

<!-- Contenedor Principal (Reemplaza al antiguo div.card de Bootstrap) -->
<div class="rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
    
    <!-- Cabecera de la Tarjeta -->
    <div class="mb-6 flex items-center justify-between">
        <h4 class="text-xl font-semibold text-black dark:text-white">
            Catálogo de Cohortes
        </h4>
        
        <!-- Botón Moderno (Reemplaza al btn btn-outline-secondary) -->
        <button type="button" data-bs-toggle="modal" data-bs-target="#createCohorteModal" 
            class="inline-flex items-center justify-center gap-2.5 rounded-md bg-primary py-2 px-4 text-center font-medium text-white hover:bg-opacity-90">
            <svg class="fill-current" width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 2.5C10.3452 2.5 10.625 2.77982 10.625 3.125V9.375H16.875C17.2202 9.375 17.5 9.65482 17.5 10C17.5 10.3452 17.2202 10.625 16.875 10.625H10.625V16.875C10.625 17.2202 10.3452 17.5 10 17.5C9.65482 17.5 9.375 17.2202 9.375 16.875V10.625H3.125C2.77982 10.625 2.5 10.3452 2.5 10C2.5 9.65482 2.77982 9.375 3.125 9.375H9.375V3.125C9.375 2.77982 9.65482 2.5 10 2.5Z" fill="currentColor"/>
            </svg>
            Nuevo Cohorte
        </button>
    </div>

    <!-- Contenedor de la Tabla -->
    <div class="max-w-full overflow-x-auto pb-4">
        <!-- Dejamos las clases internas de Bootstrap en DataTables para no romper la estructura de la librería -->
        {!! $dataTable->table(['class' => 'table table-bordered table-striped table-hover align-middle nowrap', 'style' => 'width:100%;']) !!}
    </div>
</div>

@include('cohortes.partials.modals')
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.js" crossorigin="anonymous"></script>

{!! $dataTable->scripts() !!}

<script>
    $(document).ready(function() {
        $('#UpdateCohorteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            modal.find('#UpdateCohorteForm').attr('action', button.data('url'));
            modal.find('#edit-numero-cohorte').val(button.data('numero'));
            modal.find('#edit-fecha-inicio').val(button.data('inicio'));
            modal.find('#edit-fecha-fin').val(button.data('fin'));
            modal.find('#edit-descripcion').val(button.data('descripcion'));
            modal.find('#edit-estatus').val(button.data('estatus'));
        });
    });
</script>
<script src="{{ asset('js/admin-validations.js') }}" defer></script>
@endsection