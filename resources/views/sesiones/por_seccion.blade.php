@extends('layouts.admin')

@section('header')
<x-page-header title="Historial de Sesiones">
    <li class="breadcrumb-item"><a href="{{ route('clases.secciones.index') }}">Directorio de Secciones</a></li>
    <li class="breadcrumb-item active" aria-current="page">Sección: {{ $seccion->nombre_seccion }}</li>
</x-page-header>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">
    
    <!-- ENCABEZADO Y DATOS DE LA SECCIÓN -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                <i class="bi bi-easel2-fill me-2 text-primary"></i>Sección: {{ $seccion->nombre_seccion }}
            </h4>
            <p class="text-muted small mb-0">
                <strong>PNF:</strong> {{ $seccion->pnf->nombre_pnf ?? 'N/D' }} | 
                <strong> {{ $seccion->periodoAcademico->cohorte->numero_cohorte ?? 'N/D' }} </strong>| 
                <strong>Estatus:</strong> <span class="badge bg-success">{{ $seccion->estatus_seccion }}</span>
            </p>
        </div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('clases.secciones.index') }}" class="btn btn-outline-secondary fw-bold shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Volver al Directorio
            </a>

            @can('create', App\Models\Sesion::class)
            <a href="{{ route('sesiones.create', ['seccion_id' => $seccion->id_seccion]) }}" class="btn btn-primary fw-bold shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Programar Clase
            </a>
            @endcan
        </div>
    </div>

    <!-- TARJETA PRINCIPAL CON DATATABLE -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between border-bottom">
            <h5 class="card-title text-dark mb-3 mb-md-0 fs-5" style="font-weight: 500;">
                Clases Programadas en esta Sección
            </h5>
            <!-- FILTRO DE ASISTENCIA -->
            <div class="d-flex align-items-center" style="min-width: 250px;">
                <label for="filtro_asistencia" class="form-label me-2 mb-0 fw-bold small text-muted text-nowrap">Filtrar por:</label>
                <select id="filtro_asistencia" class="form-select form-select-sm shadow-sm border-secondary-subtle">
                    <option value="">Todas las clases</option>
                    <option value="registrada">Asistencia Registrada</option>
                    <option value="pendiente">Asistencia Pendiente</option>
                </select>
            </div>
        </div>
        
        <div class="card-body bg-white py-4">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-striped table-hover align-middle w-100', 'style' => 'width:100%;']) !!}
            </div>
        </div>
        <div class="card-footer bg-white text-muted small py-3">
            Historial de sesiones académicas registradas para la sección {{ $seccion->nombre_seccion }}.
        </div>
    </div>

</div>
@endsection

@push('scripts')
{!! $dataTable->scripts(null, ['type' => 'module']) !!}

<script type="module">
    $(document).ready(function() {
        // Escuchar cambios en el selector de filtro
        $('#filtro_asistencia').on('change', function() {
            // Recargar el DataTable enviando el nuevo parámetro por AJAX
            if (window.LaravelDataTables && window.LaravelDataTables['sesiones-seccion-table']) {
                window.LaravelDataTables['sesiones-seccion-table'].draw();
            } else if ($.fn.DataTable.isDataTable('#sesiones-seccion-table')) {
                $('#sesiones-seccion-table').DataTable().draw();
            }
        });
    });
</script>
@endpush