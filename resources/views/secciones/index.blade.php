@extends('layouts.admin')

@section('content')
<div class="content pt-4" style="margin: 20px;">
    
    <!-- TARJETA DE FILTROS AVANZADOS -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-light py-3">
            <h5 class="mb-0 fw-bold text-secondary"><i class="bi bi-funnel me-2"></i>Filtros Avanzados de Secciones (Intersección Mixta)</h5>
        </div>
        <div class="card-body">
            <form id="filter-form" class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-uppercase">PNF</label>
                    <select id="filtro_pnf" class="form-select select2-filtro">
                        <option value="">Todos...</option>
                        @foreach($pnfs as $pnf)
                            <option value="{{ $pnf->id_pnf }}">{{ $pnf->nombre_pnf }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-uppercase">Docente</label>
                    <select id="filtro_profesor" class="form-select select2-filtro">
                        <option value="">Cualquiera...</option>
                        @foreach($profesores as $profesor)
                            <option value="{{ $profesor->id_profesor }}">
                                {{ $profesor->user->name_users ?? '' }} {{ $profesor->user->last_name_users ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-uppercase">Empresa (Alumnos)</label>
                    <select id="filtro_empresa" class="form-select select2-filtro">
                        <option value="">Cualquiera...</option>
                        @foreach($empresas as $empresa)
                            <option value="{{ $empresa->id_empresa }}">{{ $empresa->nombre_empresa }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-uppercase">Cohorte de Origen</label>
                    <select id="filtro_cohorte" class="form-select select2-filtro">
                        <option value="">Todas...</option>
                        @foreach(\App\Models\Cohorte::all() as $cohorte)
                            <option value="{{ $cohorte->id_cohortes }}">Cohorte {{ $cohorte->numero_cohorte }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="button" id="btn-filtrar" class="btn btn-dark w-100 fw-bold"><i class="bi bi-search me-1"></i> Filtrar</button>
                    <button type="button" id="btn-limpiar" class="btn btn-outline-secondary w-100" title="Limpiar filtros"><i class="bi bi-eraser"></i></button>
                </div>
            </form>
        </div>
    </div>

    <!-- TABLA PRINCIPAL -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h4 class="card-title text-dark mb-0 fw-bold"><i class="bi bi-grid-3x3-gap me-2 text-primary"></i>Listado de Secciones Académicas</h4>
            <button type="button" class="btn btn-primary ms-auto shadow-sm" data-bs-toggle="modal" data-bs-target="#createSeccionModal">
                <i class="bi bi-plus-circle me-1"></i> Nueva Sección
            </button>
        </div>
        <div class="card-body bg-white p-4">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-striped table-hover align-middle w-100']) !!}
            </div>
        </div>
    </div>
</div>

<!-- Modal para Crear Sección -->
@include('secciones.partials.create-modal', ['periodos' => $periodos, 'pnfs' => $pnfs])
@endsection

@push('scripts')
{!! $dataTable->scripts(null, ['type' => 'module']) !!}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof jQuery !== 'undefined' && $.fn.select2) {
            $('.select2-filtro').select2({ width: '100%', theme: 'bootstrap-5' });
        }

        // Recargar DataTable al presionar "Filtrar"
        document.getElementById('btn-filtrar').addEventListener('click', function() {
            window.LaravelDataTables["secciones-table"].ajax.reload();
        });

        // Limpiar Filtros y recargar
        document.getElementById('btn-limpiar').addEventListener('click', function() {
            $('#filtro_pnf').val('').trigger('change');
            $('#filtro_profesor').val('').trigger('change');
            $('#filtro_empresa').val('').trigger('change');
            $('#filtro_cohorte').val('').trigger('change');
            window.LaravelDataTables["secciones-table"].ajax.reload();
        });
    });
</script>
@endpush