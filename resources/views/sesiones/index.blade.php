@extends('layouts.admin')

@section('header')
<x-page-header title="Portal del Docente">
    <li class="breadcrumb-item active" aria-current="page">Mis Sesiones de Clase</li>
</x-page-header>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-0"><i class="bi bi-easel2-fill me-2 text-primary"></i>Historial de Clases</h4>
            <p class="text-muted small mb-0 mt-1">Gestione sus sesiones y registros de asistencia</p>
        </div>
        
        {{-- Solo mostramos el botón de aperturar si el usuario tiene permiso de creación según la Policy --}}
        @can('create', App\Models\Sesion::class)
        <a href="{{ route('sesiones.create') }}" class="btn btn-primary fw-bold shadow-sm">
            <i class="bi bi-plus-circle me-2"></i> Aperturar Nueva Clase
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
                            <th>Cohorte</th>
                            <th>PNF / Nivel</th>
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
                            <td><span class="badge bg-info text-dark">{{ $sesion->grupo->cohorte->numero_cohorte }}</span></td>
                            <td>
                                <div class="fw-bold text-dark">{{ $sesion->grupo->pnf->nombre_pnf }}</div>
                                <div class="small text-muted">{{ $sesion->grupo->nivel_academico }}</div>
                            </td>
                            <td class="text-truncate" style="max-width: 200px;" title="{{ $sesion->observacion_sesion }}">
                                {{ $sesion->observacion_sesion ?? 'Sin observaciones' }}
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group" role="group">
                                    {{-- Botón de Visualización: Accesible si la Policy 'view' lo permite --}}
                                    @can('view', $sesion)
                                    <a href="{{ route('sesiones.show', $sesion->id_sesiones) }}" class="btn btn-sm btn-outline-primary fw-bold" title="Ver Lista de Asistencia">
                                        <i class="bi bi-clipboard-check me-1"></i> Ver Lista
                                    </a>
                                    @endcan

                                    {{-- Indicador visual si expiró la ventana de 48h para profesores (Administradores siempre pueden) --}}
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
                                <h6 class="fw-bold">No hay clases registradas</h6>
                                <p class="small mb-0">Haga clic en "Aperturar Nueva Clase" para iniciar su primer registro de asistencia.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Paginación -->
        @if($sesiones->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $sesiones->links() }}
        </div>
        @endif
    </div>

</div>
@endsection