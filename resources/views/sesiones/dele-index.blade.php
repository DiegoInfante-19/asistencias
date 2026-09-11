@extends('layouts.admin')

@section('header')
<x-page-header title="Portal del Docente">
    <li class="breadcrumb-item active" aria-current="page">Mis Sesiones de Clase (Hoy)</li>
</x-page-header>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-0"><i class="bi bi-easel2-fill me-2 text-primary"></i>Clases Programadas para Hoy</h4>
            <p class="text-muted small mb-0 mt-1">
                @if(auth()->user()->isAdmin() || auth()->user()->isCoordinador())
                    Vista global del calendario institucional (Administrador).
                @else
                    Mostrando únicamente las clases correspondientes a la fecha actual ({{ \Carbon\Carbon::today()->format('d/m/Y') }}).
                @endif
            </p>
        </div>
        
        @can('create', App\Models\Sesion::class)
        <a href="{{ route('sesiones.create') }}" class="btn btn-primary fw-bold shadow-sm">
            <i class="bi bi-plus-circle me-2"></i> Programar Nueva Clase
        </a>
        @endcan
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Fecha</th>
                            <th>Sección</th>
                            <th>PNF / Cohorte Ref.</th>
                            <th>Observaciones</th>
                            <th class="text-center pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sesiones as $sesion)
                        <tr>
                            <td class="ps-4 fw-semibold text-dark">
                                <i class="bi bi-calendar-event text-secondary me-2"></i>
                                {{ \Carbon\Carbon::parse($sesion->fecha_sesion)->format('d/m/Y') }}
                            </td>
                            <td>
                                <span class="fw-bold text-primary">{{ $sesion->seccion->nombre_seccion ?? 'N/D' }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $sesion->seccion->pnf->nombre_pnf ?? 'N/D' }}</div>
                                <div class="small text-muted">Cohorte Ref. {{ $sesion->seccion->periodoAcademico->cohorte->numero_cohorte ?? 'N/D' }}</div>
                            </td>
                            <td class="text-truncate" style="max-width: 200px;" title="{{ $sesion->observacion_sesion }}">
                                {{ $sesion->observacion_sesion ?? 'Sin observaciones' }}
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group" role="group">
                                    @can('view', $sesion)
                                    <a href="{{ route('sesiones.show', $sesion->id_sesiones) }}" class="btn btn-sm btn-outline-primary fw-bold" title="Tomar o Ver Lista de Asistencia">
                                        <i class="bi bi-clipboard-check me-1"></i> Tomar Asistencia
                                    </a>
                                    @endcan

                                    @php
                                        $expirado = false;
                                        if (!auth()->user()->isAdmin() && !auth()->user()->isCoordinador()) {
                                            $limite = \Carbon\Carbon::parse($sesion->fecha_sesion)->addHours(48);
                                            $expirado = \Carbon\Carbon::now()->greaterThan($limite);
                                        }
                                    @endphp

                                    @if($expirado)
                                    <span class="badge bg-secondary align-self-center ms-2" title="Ventana de edición de 48h expirada">
                                        <i class="bi bi-lock-fill"></i> Bloqueada
                                    </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="bi bi-journal-x fs-1 d-block mb-3 opacity-50"></i>
                                <h6 class="fw-bold">No hay clases programadas para hoy</h6>
                                <p class="small mb-0">Usted no posee sesiones asignadas en su bandeja para el día de hoy.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($sesiones->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $sesiones->links() }}
        </div>
        @endif
    </div>

</div>
@endsection