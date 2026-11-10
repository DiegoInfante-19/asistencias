@extends('layouts.admin')

@section('styles')
<style>
    /* =========================================
        ESTILOS ADAPTATIVOS PARA MÓVILES Y TABLETS
        ========================================= */
    @media (max-width: 767.98px) {
        .content, .app-content {
            margin: 0 !important;
            padding: 5px !important;
        }

        .card {
            border-radius: 0.5rem !important;
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }

        /* Ajuste de títulos en cabeceras para evitar desbordes */
        h3.fw-bold {
            font-size: 1.4rem !important;
        }

        /* Las tarjetas de lista de tareas del profesor se apilan verticalmente en móvil */
        .list-group-item-sesion {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 1rem;
        }

        .list-group-item-sesion .btn {
            width: 100%;
        }
    }
</style>
@endsection

@section('header')
<x-page-header title="Dashboard Principal" :breadcrumbs="false" />
@endsection

@section('content')
<div class="app-content">
    <div class="container-fluid pt-3 px-2 px-md-3">

        @php
        $esDirectivo = auth()->user()->isAdmin() || auth()->user()->isCoordinador();
        $hoyFormateado = \Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY');
        @endphp

        <!-- =======================================================
             DASHBOARD DIRECTIVO (ADMINISTRADOR / COORDINADOR)
             ======================================================= -->
        @if($esDirectivo)

        <!-- Cabecera Directiva -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
            <div>
                <h3 class="mb-0 fw-bold text-dark">Panel de Control Gerencial</h3>
                <p class="text-muted mb-0 small">
                    <i class="bi bi-calendar3 me-1"></i> {{ ucfirst($hoyFormateado) }}
                </p>
            </div>
            <div>
                <span class="badge bg-primary px-3 py-2 fs-6 shadow-sm">
                    <i class="bi bi-flag-fill me-1"></i> Período Activo: {{ $periodoActual->nombre_periodo ?? 'No definido' }}
                </span>
            </div>
        </div>

        <!-- Fila de Indicadores Globales (4 Tarjetas) -->
        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-6">
                <div class="small-box text-bg-primary rounded-3 shadow-sm h-100">
                    <div class="inner p-3">
                        <h3 class="fw-bold">{{ $totalProfesores ?? 0 }}</h3>
                        <p class="mb-0">Profesores</p>
                    </div>
                    <div class="small-box-icon">
                        <i class="bi bi-person-badge-fill opacity-50"></i>
                    </div>
                    <a href="{{ route('profesores.index') }}" class="small-box-footer link-light link-underline-opacity-0">
                        Ver nómina <i class="bi bi-arrow-right-circle ms-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box text-bg-success rounded-3 shadow-sm h-100">
                    <div class="inner p-3">
                        <h3 class="fw-bold">{{ $totalEstudiantes ?? 0 }}</h3>
                        <p class="mb-0">Estudiantes Activos</p>
                    </div>
                    <div class="small-box-icon">
                        <i class="bi bi-people-fill opacity-50"></i>
                    </div>
                    <a href="{{ route('personas.index') }}" class="small-box-footer link-light link-underline-opacity-0">
                        Directorio <i class="bi bi-arrow-right-circle ms-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box text-bg-warning rounded-3 shadow-sm h-100">
                    <div class="inner p-3">
                        <h3 class="fw-bold text-dark">{{ $totalEmpresas ?? 0 }}</h3>
                        <p class="mb-0 text-dark">Empresas CVG</p>
                    </div>
                    <div class="small-box-icon">
                        <i class="bi bi-building-fill opacity-50 text-dark"></i>
                    </div>
                    <a href="{{ route('empresas.index') }}" class="small-box-footer link-dark link-underline-opacity-0">
                        Convenios <i class="bi bi-arrow-right-circle ms-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box text-bg-info rounded-3 shadow-sm h-100">
                    <div class="inner p-3">
                        <h3 class="fw-bold text-dark">{{ $totalSecciones ?? 0 }}</h3>
                        <p class="mb-0 text-dark">Secciones Abiertas</p>
                    </div>
                    <div class="small-box-icon">
                        <i class="bi bi-easel2-fill opacity-50 text-dark"></i>
                    </div>
                    <a href="{{ route('clases.secciones.index') }}" class="small-box-footer link-dark link-underline-opacity-0">
                        Aulas <i class="bi bi-arrow-right-circle ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- FILA 1 DE GRÁFICOS -->
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold text-dark">Estudiantes por PNF</h5>
                    </div>
                    <div class="card-body p-3 p-md-4 d-flex align-items-center justify-content-center">
                        <div style="width: 100%; height: 350px; position: relative;">
                            <canvas id="chartEstudiantesPnf"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold text-dark">Matrícula por Empresa</h5>
                    </div>
                    <div class="card-body p-3 p-md-4 d-flex align-items-center justify-content-center">
                        <div style="width: 100%; height: 350px; position: relative;">
                            <canvas id="chartMatriculaEmpresas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILA 2 DE GRÁFICOS -->
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold text-dark">Asistencia Total por PNF</h5>
                    </div>
                    <div class="card-body p-3 p-md-4 d-flex align-items-center justify-content-center">
                        <div style="width: 100%; height: 350px; position: relative;">
                            <canvas id="chartAsistenciaPnf"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold text-dark">Asistencia Total por Empresa</h5>
                    </div>
                    <div class="card-body p-3 p-md-4 d-flex align-items-center justify-content-center">
                        <div style="width: 100%; height: 350px; position: relative;">
                            <canvas id="chartAsistenciaEmpresa"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- =======================================================
             DASHBOARD OPERATIVO (PROFESORES)
             ======================================================= -->
        @else

        <!-- Cabecera Profesor -->
        <div class="row mb-4 align-items-center">
            <div class="col-12">
                <h3 class="mb-0 fw-bold text-dark">Bienvenido, Prof. {{ auth()->user()->name_users }}</h3>
                <p class="text-muted mb-0 small">
                    <i class="bi bi-calendar-event me-1"></i> {{ ucfirst($hoyFormateado) }}
                </p>
            </div>
        </div>

        <!-- Métricas Operativas -->
        <div class="row g-3 mb-4">
            <div class="col-lg-4 col-12">
                <div class="card border-0 shadow-sm bg-primary text-white h-100">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="flex-shrink-0 me-3">
                            <i class="bi bi-easel2-fill fs-1 opacity-50"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $misSecciones ?? 0 }}</h3>
                            <p class="mb-0 small text-white-50">Secciones Asignadas</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="card border-0 shadow-sm bg-success text-white h-100">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="flex-shrink-0 me-3">
                            <i class="bi bi-people-fill fs-1 opacity-50"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $misAlumnos ?? 0 }}</h3>
                            <p class="mb-0 small text-white-50">Estudiantes a cargo</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="card border-0 shadow-sm bg-warning text-dark h-100">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="flex-shrink-0 me-3">
                            <i class="bi bi-journal-x fs-1 opacity-50"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ count($misClasesPendientes ?? []) }}</h3>
                            <p class="mb-0 small text-dark-50">Asistencias Pendientes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de Tareas del Profesor -->
        <div class="row g-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm border-top border-primary border-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0 fw-bold text-dark"><i class="bi bi-list-task text-primary me-2"></i>Mis Clases Pendientes por Evaluar</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($misClasesPendientes ?? [] as $clase)
                            <div class="list-group-item list-group-item-sesion d-flex justify-content-between align-items-center p-3 p-md-4">
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark">Sección: {{ $clase->seccion->nombre_seccion }}</h6>
                                    <p class="mb-0 text-muted small">
                                        <i class="bi bi-clock me-1"></i>
                                        Clase del {{ ucfirst(\Carbon\Carbon::parse($clase->fecha_sesion)->locale('es')->isoFormat('dddd, D [de] MMMM [del año] YYYY')) }}
                                    </p>
                                </div>
                                <a href="{{ route('sesiones.show', $clase->id_sesiones) }}" class="btn btn-primary shadow-sm fw-semibold btn-sm py-2 px-3">
                                    <i class="bi bi-pencil-square me-1"></i> Pasar Asistencia
                                </a>
                            </div>
                            @empty
                            <div class="p-5 text-center">
                                <i class="bi bi-check2-circle text-success" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-dark fw-bold">¡Todo al día!</h5>
                                <p class="text-muted">No tienes clases pendientes de asistencia.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endif

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paletteColors = [
            '#0d6efd', '#198754', '#ffc107', '#0dcaf0',
            '#6f42c1', '#fd7e14', '#20c997', '#d63384',
            '#6610f2', '#20c997', '#e83e8c', '#adb5bd'
        ];

        function getDynamicColors(count) {
            let colors = [];
            for (let i = 0; i < count; i++) {
                colors.push(paletteColors[i % paletteColors.length]);
            }
            return colors;
        }

        // ==========================================
        // 1. ESTUDIANTES POR PNF
        // ==========================================
        const canvasPnf = document.getElementById('chartEstudiantesPnf');
        if (canvasPnf) {
            const rawDataPnf = {!! json_encode($metricasPnf ?? []) !!};
            const labelsPnf = rawDataPnf.map(item => item.nombre_pnf);
            const dataPnf = rawDataPnf.map(item => item.total_estudiantes);

            new Chart(canvasPnf.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labelsPnf.length > 0 ? labelsPnf : ['Sin Registros'],
                    datasets: [{
                        label: 'Estudiantes',
                        data: dataPnf.length > 0 ? dataPnf : [0],
                        backgroundColor: getDynamicColors(labelsPnf.length),
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ` Total: ${context.raw} estudiantes`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f5f9' } },
                        y: { grid: { display: false } }
                    }
                }
            });
        }

        // ==========================================
        // 2. MATRÍCULA POR EMPRESA
        // ==========================================
        const canvasEmpresas = document.getElementById('chartMatriculaEmpresas');
        if (canvasEmpresas) {
            const rawDataEmpresas = {!! json_encode($metricasEmpresas ?? []) !!};
            const labelsEmpresas = rawDataEmpresas.map(item => item.nombre_empresa);
            const dataEmpresas = rawDataEmpresas.map(item => item.total_alumnos);

            new Chart(canvasEmpresas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labelsEmpresas.length > 0 ? labelsEmpresas : ['Sin Registros'],
                    datasets: [{
                        label: 'Alumnos',
                        data: dataEmpresas.length > 0 ? dataEmpresas : [0],
                        backgroundColor: getDynamicColors(labelsEmpresas.length),
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ` Trabajadores activos: ${context.raw}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f5f9' } },
                        y: { grid: { display: false } }
                    }
                }
            });
        }

        // ==========================================
        // 3. % ASISTENCIA TOTAL POR PNF
        // ==========================================
        const canvasAsisPnf = document.getElementById('chartAsistenciaPnf');
        if (canvasAsisPnf) {
            const rawData = {!! json_encode($asistenciaPnfChart ?? []) !!};
            const labels = rawData.map(item => item.nombre_pnf);
            const data = rawData.map(item => item.porcentaje);

            new Chart(canvasAsisPnf.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels.length > 0 ? labels : ['Sin Registros'],
                    datasets: [{
                        label: '% Asistencia',
                        data: data.length > 0 ? data : [0],
                        backgroundColor: getDynamicColors(labels.length),
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ` Promedio Asistencia: ${context.raw}%`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' }, grid: { color: '#f1f5f9' } },
                        y: { grid: { display: false } }
                    }
                }
            });
        }

        // ==========================================
        // 4. % ASISTENCIA TOTAL POR EMPRESA
        // ==========================================
        const canvasAsisEmpresa = document.getElementById('chartAsistenciaEmpresa');
        if (canvasAsisEmpresa) {
            const rawData = {!! json_encode($asistenciaEmpresaChart ?? []) !!};
            const labels = rawData.map(item => item.nombre_empresa);
            const data = rawData.map(item => item.porcentaje);

            new Chart(canvasAsisEmpresa.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels.length > 0 ? labels : ['Sin Registros'],
                    datasets: [{
                        label: '% Asistencia',
                        data: data.length > 0 ? data : [0],
                        backgroundColor: getDynamicColors(labels.length),
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ` Promedio Asistencia: ${context.raw}%`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' }, grid: { color: '#f1f5f9' } },
                        y: { grid: { display: false } }
                    }
                }
            });
        }
    });
</script>
@endpush