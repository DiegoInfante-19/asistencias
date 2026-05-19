@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Encabezado de la Sección -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="text-dark fw-bold mb-0">Gestión de Personal</h4>
            <p class="text-muted small mb-0">Secretarias, Profesores y Administradores del sistema</p>
        </div>
        <a href="{{ route('usuarios.create') }}" class="btn btn-sm btn-primary px-3 rounded-3 shadow-sm">
            <i class="fas fa-plus me-1"></i> Registrar Usuario
        </a>
    </div>

    <!-- Alerta de Éxito por si vienes de guardar un usuario -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tarjeta Contenedora de la Tabla -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <div class="table-responsive">
                
                <!-- PIEZA 1: Aquí Yajra dibuja el esqueleto de la tabla automáticamente -->
                {!! $dataTable->table(['class' => 'table table-hover align-middle w-100']) !!}
                
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- PIEZA 2: Cargamos las herramientas interactivas (jQuery y DataTables Core) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <!-- PIEZA 3: Inyecta el JavaScript que conecta el buscador y la paginación con el backend -->
    {!! $dataTable->scripts() !!}
@endsection