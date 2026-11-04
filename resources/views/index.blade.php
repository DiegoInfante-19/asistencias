@extends('layouts.admin')

@section('header')
    <x-page-header title="Dashboard Principal" :breadcrumbs="false" />
@endsection

@section('content')
<div class="app-content">
    <div class="container-fluid pt-3">

        @php
            $esDirectivo = auth()->user()->isAdmin() || auth()->user()->isCoordinador();
            $hoyFormateado = \Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY');
        @endphp

        <!-- =======================================================
             DASHBOARD DIRECTIVO (ADMINISTRADOR / COORDINADOR)
             ======================================================= -->
        @if($esDirectivo)
            
            <!-- Cabecera Directiva -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                <div>
                    <h3 class="mb-0 fw-bold text-dark">Panel de Control Gerencial</h3>
                    <p class="text-muted mb-0">
                        <i class="bi bi-calendar3 me-1"></i> {{ ucfirst($hoyFormateado) }}
                    </p>
                </div>
                <div class="mt-3 mt-md-0">
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

            <div class="row g-4 mb-4">
                <!-- Gráfico de Pastel: Distribución de Estudiantes por PNF -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 fw-bold text-dark">
                                <i class="bi bi-pie-chart-fill text-primary me-2"></i>Estudiantes por PNF
                            </h5>
                        </div>
                        <div class="card-body p-4 d-flex align-items-center justify-content-center">
                            <!-- ALTURA AUMENTADA A 480px Y SIN MAX-WIDTH RESTRINGIDO -->
                            <div style="width: 100%; height: 480px; position: relative;">
                                <canvas id="chartEstudiantesPnf"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Distribución por Empresas -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="card-title mb-0 fw-bold text-dark"><i class="bi bi-bar-chart-steps text-primary me-2"></i>Matrícula por Empresa</h5>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @forelse($metricasEmpresas ?? [] as $metrica)
                                    @if($metrica->total_alumnos > 0)
                                    <li class="list-group-item py-3 px-4">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="small fw-semibold text-dark">{{ $metrica->nombre_empresa }}</span>
                                            <span class="small fw-bold text-primary">{{ $metrica->total_alumnos }} alu.</span>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-primary rounded-pill" role="progressbar" style="width: {{ $metrica->porcentaje }}%"></div>
                                        </div>
                                    </li>
                                    @endif
                                @empty
                                <li class="list-group-item text-center py-4 text-muted small">
                                    No hay empresas con estudiantes registrados.
                                </li>
                                @endforelse
                            </ul>
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
                    <p class="text-muted mb-0">
                        <i class="bi bi-calendar-event me-1"></i> {{ ucfirst($hoyFormateado) }}
                    </p>
                </div>
            </div>

            <!-- Métricas Operativas -->
            <div class="row g-3 mb-4">
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm bg-primary text-white h-100">
                        <div class="card-body d-flex align-items-center">
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
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm bg-success text-white h-100">
                        <div class="card-body d-flex align-items-center">
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
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm bg-warning text-dark h-100">
                        <div class="card-body d-flex align-items-center">
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
                                    <div class="list-group-item d-flex justify-content-between align-items-center p-4">
                                        <div>
                                            <h6 class="mb-1 fw-bold text-dark">Sección: {{ $clase->seccion->nombre_seccion }}</h6>
                                            <p class="mb-0 text-muted small">
                                                <i class="bi bi-clock me-1"></i> Clase del {{ \Carbon\Carbon::parse($clase->fecha_sesion)->format('d/m/Y') }}
                                            </p>
                                        </div>
                                        <a href="{{ route('sesiones.show', $clase->id_sesiones) }}" class="btn btn-primary shadow-sm fw-semibold">
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
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('chartEstudiantesPnf');
        if (!canvas) return;

        const rawData = {!! json_encode($metricasPnf ?? []) !!};

        const labelsPnf = rawData.map(item => item.nombre_pnf);
        const dataPnf = rawData.map(item => item.total_estudiantes);

        const totalSum = dataPnf.reduce((a, b) => a + b, 0);

        const ctx = canvas.getContext('2d');

        // Determina la posición de la leyenda según el ancho de la pantalla
        const legendPosition = window.innerWidth >= 768 ? 'right' : 'bottom';

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: totalSum > 0 ? labelsPnf : ['Sin Estudiantes Inscritos'],
                datasets: [{
                    data: totalSum > 0 ? dataPnf : [1],
                    backgroundColor: totalSum > 0 ? [
                        '#0d6efd',
                        '#198754',
                        '#ffc107',
                        '#0dcaf0',
                        '#6f42c1',
                        '#fd7e14',
                        '#20c997',
                        '#d63384'
                    ] : ['#e9ecef'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: 15
                },
                plugins: {
                    legend: {
                        position: legendPosition,
                        labels: {
                            padding: 16,
                            usePointStyle: true,
                            font: { 
                                size: 13,
                                family: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif"
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (totalSum === 0) return ' Sin estudiantes registrados';
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                return ` ${label}: ${value} estudiante(s)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush