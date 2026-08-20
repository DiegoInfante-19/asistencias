@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.css" crossorigin="anonymous">
@endsection

@section('content')
<!-- Encabezado de la página -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Catálogo de Cargos
    </h2>

    <nav>
        <ol class="flex items-center gap-2 text-sm">
            <li>
                <a class="hover:text-primary" href="{{ url('/') }}">Inicio /</a>
            </li>
            <li class="text-primary font-medium">Cargos</li>
        </ol>
    </nav>
</div>

<!-- Contenedor Principal -->
<div class="rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
    
    <!-- Cabecera de la Tarjeta -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <h4 class="text-xl font-semibold text-black dark:text-white">
            Listado de Cargos
        </h4>
        
        <!-- Botón Moderno de Nuevo Cargo -->
        <button type="button" data-bs-toggle="modal" data-bs-target="#createCargoModal" 
            class="inline-flex items-center justify-center gap-2.5 rounded-md bg-primary py-2 px-4 text-center font-medium text-white hover:bg-opacity-90">
            <svg class="fill-current" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20 7h-4V5c0-1.103-.897-2-2-2h-4c-1.103 0-2 .897-2 2v2H4c-1.103 0-2 .897-2 2v10c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V9c0-1.103-.897-2-2-2zM10 5h4v2h-4V5zM4 9h16l.002 10H4V9z" fill="currentColor"/>
            </svg>
            Nuevo Cargo
        </button>
    </div>

    <!-- Contenedor de la Tabla -->
    <div class="max-w-full overflow-x-auto pb-4">
        {!! $dataTable->table(['class' => 'table table-bordered table-striped table-hover align-middle nowrap dt-responsive', 'style' => 'width:100%;']) !!}
    </div>
</div>

@include('cargos.partials.modals')
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.js" crossorigin="anonymous"></script>

{!! $dataTable->scripts() !!}

<script>
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
@endsection