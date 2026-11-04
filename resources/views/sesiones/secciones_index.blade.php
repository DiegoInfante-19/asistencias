@extends('layouts.admin')

@section('styles')
<style>
    /* =========================================
        ESTILOS GENERALES Y DE FORMULARIO "SOFT"
       ========================================= */
    .card-body.bg-white .form-control,
    .card-body.bg-white .form-select {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
    }

    .card-body.bg-white .select2-container--bootstrap-5 .select2-selection {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
        min-height: calc(1.5em + .75rem + 2px);
        padding: .375rem .75rem;
        font-size: 0.9rem;
    }

    .card-body.bg-white .form-control:focus,
    .card-body.bg-white .form-select:focus,
    .card-body.bg-white .select2-container--bootstrap-5.select2-container--open .select2-selection {
        background-color: #ffffff !important;
        border-color: #86b7fe !important;
    }

    /* =========================================
        ESTILO CUSTOM PARA BADGE SECCIÓN (SOFT BORDER)
       ========================================= */
    .badge-seccion-custom {
        background-color: #e7f1ff !important; 
        color: #0d6efd !important;             
        border: 1px solid #0d6efd !important;  
        font-weight: 600 !important;
        font-size: 0.85rem !important;
        padding: 0.4em 0.75em !important;
        border-radius: 0.375rem !important;
    }

    /* =========================================
        DISEÑO MÓVIL (TELÉFONOS)
       ========================================= */
    @media (max-width: 767.98px) {
        .content {
            margin: 5px !important;
            padding: 0 !important;
        }

        .card {
            border-radius: 0.5rem !important;
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }

        .card-seccion-movil {
            transition: transform 0.2s ease;
        }

        .card-seccion-movil:active {
            transform: scale(0.99);
        }
    }

    /* Estilos para el botón ColVis de DataTables */
    .dt-buttons .btn.buttons-colvis {
        background-color: #fff !important;
        border: 1px solid #dee2e6 !important;
        color: #495057 !important;
        font-weight: 500;
    }
    .dt-buttons .btn.buttons-colvis:hover {
        background-color: #f8f9fa !important;
    }
    .dt-button-collection {
        padding: 0.5rem !important;
        border-radius: 0.5rem !important;
    }
    .dt-button-collection .dt-button {
        display: block;
        width: 100%;
        text-align: left;
        border: none !important;
        background: transparent !important;
        padding: 0.375rem 1rem;
        margin-bottom: 2px;
        border-radius: 0.25rem;
    }
    .dt-button-collection .dt-button:hover {
        background-color: #e9ecef !important;
    }
    .dt-button-collection .dt-button.active {
        background-color: #e0f0ff !important;
        color: #0d6efd !important;
        font-weight: bold;
    }
</style>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">

    <!-- PANEL DE FILTROS AVANZADOS -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-white py-3 border-bottom">
            <h4 class="card-title text-dark mb-0 fs-5 fw-bold">
                <i class="bi bi-funnel-fill text-primary me-2"></i>Filtros de Búsqueda
            </h4>
        </div>
        
        <div class="card-body bg-white py-4">
            <div class="row g-3">
                <!-- Filtro por PNF -->
                <div class="{{ (auth()->user()->isAdmin() || auth()->user()->isCoordinador()) ? 'col-md-6' : 'col-12' }}">
                    <label for="filtro_pnf" class="form-label fw-bold small text-muted text-uppercase">Programa (PNF)</label>
                    <select id="filtro_pnf" class="form-select select2-buscador">
                        <option value="">Todos los PNF...</option>
                        @foreach($pnfs as $pnf)
                        <option value="{{ $pnf->id_pnf }}" {{ request('id_pnf') == $pnf->id_pnf ? 'selected' : '' }}>
                            {{ $pnf->nombre_pnf }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por Profesor (Solo visible para Administradores o Coordinadores) -->
                @if(auth()->user()->isAdmin() || auth()->user()->isCoordinador())
                <div class="col-md-6">
                    <label for="filtro_profesor" class="form-label fw-bold small text-muted text-uppercase">Docente Asignado</label>
                    <select id="filtro_profesor" class="form-select select2-buscador">
                        <option value="">Cualquier docente...</option>
                        @foreach($profesores as $profesor)
                        @php
                        $nombreProf = trim(($profesor->user->name_users ?? '') . ' ' . ($profesor->user->last_name_users ?? ''));
                        @endphp
                        <option value="{{ $profesor->id_profesor }}" {{ request('id_profesor') == $profesor->id_profesor ? 'selected' : '' }}>
                            {{ $nombreProf }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
        </div>

        <div class="card-footer bg-white py-3 d-flex justify-content-end border-top">
            <button type="button" id="btn-limpiar-filtros" class="btn btn-success fw-bold shadow-sm">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Limpiar Filtros
            </button>
        </div>
    </div>

    <!-- TARJETA PRINCIPAL -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <h5 class="card-title text-dark mb-0 fs-5 fw-bold">
                <i class="bi bi-easel2-fill text-primary me-2"></i>Directorio de Secciones
            </h5>
            <span class="badge bg-light text-primary border fs-6 d-md-none">
                {{ isset($seccionesMovil) ? $seccionesMovil->count() : 0 }} Registros
            </span>
        </div>

        <div class="card-body bg-white py-4">
            
            <!-- MODO COMPUTADORA (TABLA DATATABLES) -->
            <div class="table-responsive d-none d-md-block">
                {!! $dataTable->table(['class' => 'table table-striped table-hover align-middle w-100', 'style' => 'width:100%;']) !!}
            </div>

            <!-- MODO TELÉFONO (TARJETAS TÁCTILES RESPONSIVE) -->
            <div class="d-block d-md-none">
                @php
                    $user = auth()->user();
                    // CORRECCIÓN AQUÍ: Se cambió 'pnf' a 'pnfs' en el with()
                    $queryMovil = App\Models\Seccion::query()->activasParaAsignacion()->with(['pnfs', 'periodoAcademico.cohorte', 'profesores.user']);
                    
                    if (!$user->isAdmin() && !$user->isCoordinador()) {
                        if ($user->profesor) {
                            $queryMovil->porProfesor($user->profesor->id_profesor);
                        } else {
                            $queryMovil->whereRaw('1 = 0');
                        }
                    } else {
                        if (request()->filled('id_profesor')) {
                            $queryMovil->porProfesor(request('id_profesor'));
                        }
                    }

                    if (request()->filled('id_pnf')) {
                        $queryMovil->porPnf(request('id_pnf'));
                    }

                    $seccionesMovil = $queryMovil->get();
                @endphp

                @forelse($seccionesMovil as $seccion)
                <div class="card card-seccion-movil mb-3 border shadow-sm p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <!-- Badge con estilo de borde completo y fondo claro diferenciado -->
                            <span class="badge badge-seccion-custom mb-1">Sección: {{ $seccion->nombre_seccion }}</span>
                            <div class="text-muted small fw-medium mt-1">
                                <i class="bi bi-mortarboard text-secondary me-1"></i>
                                <!-- CORRECCIÓN AQUÍ: Renderizado del array de PNFs -->
                                @if($seccion->pnfs->isEmpty())
                                    <span class="fst-italic">Sin PNF</span>
                                @else
                                    {{ $seccion->pnfs->pluck('nombre_pnf')->implode(' / ') }}
                                @endif
                            </div>
                        </div>
                        <!-- Cohorte limpio sin texto adicional -->
                        <span class="badge bg-light text-dark border shadow-sm">
                            {{ $seccion->periodoAcademico->cohorte->numero_cohorte ?? 'S/C' }}
                        </span>
                    </div>

                    <!-- Profesores asignados en móvil -->
                    <div class="bg-light rounded p-2 mb-3 small text-secondary">
                        <strong><i class="bi bi-person-badge me-1"></i>Docente(s):</strong>
                        <div class="mt-1">
                            @forelse($seccion->profesores as $prof)
                                <span class="badge bg-white text-dark border me-1 mb-1 shadow-sm">
                                    {{ trim(($prof->user->name_users ?? '') . ' ' . ($prof->user->last_name_users ?? '')) }}
                                </span>
                            @empty
                                <span class="fst-italic text-muted">Sin asignar</span>
                            @endforelse
                        </div>
                    </div>

                    <!-- Métricas de Clases en Móvil -->
                    <div class="row text-center g-2 mb-3">
                        <div class="col-4">
                            <div class="border rounded p-1 bg-white shadow-sm">
                                <span class="d-block text-muted" style="font-size: 0.7rem;">Vistas</span>
                                <span class="fw-bold text-success fs-6">{{ $seccion->sesiones()->has('asistencias')->count() }}</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-1 bg-white shadow-sm">
                                <span class="d-block text-muted" style="font-size: 0.7rem;">Pendientes</span>
                                <span class="fw-bold text-warning fs-6">{{ $seccion->sesiones()->doesntHave('asistencias')->count() }}</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-1 bg-white shadow-sm">
                                <span class="d-block text-muted" style="font-size: 0.7rem;">Totales</span>
                                <span class="fw-bold text-primary fs-6">{{ $seccion->sesiones()->count() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Botón de Acción Principal en Móvil (Sólido) -->
                    <div class="d-grid">
                        <a href="{{ route('clases.secciones.sesiones', $seccion->id_seccion) }}" class="btn btn-primary btn-sm fw-bold shadow-sm py-2">
                            <i class="bi bi-calendar-check me-1"></i> Gestionar Sesiones
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                    <p class="mb-0">No se encontraron secciones registradas con los filtros seleccionados.</p>
                </div>
                @endforelse
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script type="module">
    $(document).ready(function() {
        // 0. Inicializar Select2 con tema Bootstrap 5
        $('.select2-buscador').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });

        // 1. Vincular los filtros a la petición AJAX del DataTable (Escritorio)
        $('#secciones-clases-table').on('preXhr.dt', function(e, settings, data) {
            data.id_pnf = $('#filtro_pnf').val();
            data.id_profesor = $('#filtro_profesor').length ? $('#filtro_profesor').val() : null;
        });

        function aplicarFiltros() {
            let idPnf = $('#filtro_pnf').val();
            let idProfesor = $('#filtro_profesor').length ? $('#filtro_profesor').val() : '';

            let url = new URL(window.location.href);
            if (idPnf) {
                url.searchParams.set('id_pnf', idPnf);
            } else {
                url.searchParams.delete('id_pnf');
            }

            if (idProfesor) {
                url.searchParams.set('id_profesor', idProfesor);
            } else {
                url.searchParams.delete('id_profesor');
            }

            window.location.href = url.toString();
        }

        // 2. Disparar cambio de filtros
        const selectsFiltros = '#filtro_pnf, #filtro_profesor';
        $(selectsFiltros).on('change', function() {
            if (window.innerWidth < 768) {
                aplicarFiltros();
            } else {
                if (window.LaravelDataTables && window.LaravelDataTables['secciones-clases-table']) {
                    window.LaravelDataTables['secciones-clases-table'].draw();
                } else if ($.fn.DataTable.isDataTable('#secciones-clases-table')) {
                    $('#secciones-clases-table').DataTable().draw();
                }
            }
        });

        $('#filtro_pnf, #filtro_profesor').on('select2:select select2:unselect', function() {
            if (window.innerWidth < 768) {
                aplicarFiltros();
            }
        });

        // 3. Botón para limpiar filtros
        $('#btn-limpiar-filtros').on('click', function() {
            let url = new URL(window.location.href);
            url.searchParams.delete('id_pnf');
            url.searchParams.delete('id_profesor');
            window.location.href = url.toString();
        });

        // 4. Reajuste Responsive de la tabla de escritorio
        $(window).on('resize', function() {
            if ($.fn.DataTable.isDataTable('#secciones-clases-table')) {
                let dt = $('#secciones-clases-table').DataTable();
                dt.columns.adjust();
                if (typeof dt.responsive !== 'undefined' && dt.responsive !== null) {
                    dt.responsive.recalc();
                }
            }
        });
    });
</script>
{!! $dataTable->scripts(null, ['type' => 'module']) !!}
@endpush