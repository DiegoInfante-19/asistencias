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
                <strong>Cohorte Ref.:</strong> {{ $seccion->periodoAcademico->cohorte->numero_cohorte ?? 'N/D' }} | 
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

    <!-- TABLA DE SESIONES DE LA SECCIÓN -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-dark text-white py-3">
            <h5 class="mb-0 fw-bold fs-6"><i class="bi bi-calendar-check me-2"></i>Clases Programadas en esta Sección</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Fecha de Clase</th>
                            <th>Profesor Responsable</th>
                            <th>Observaciones / Tema</th>
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
                                @php
                                    $profNombre = trim(($sesion->profesor->user->name_users ?? '') . ' ' . ($sesion->profesor->user->last_name_users ?? ''));
                                @endphp
                                <span class="fw-bold text-dark">{{ $profNombre ?: 'Sin asignar' }}</span>
                            </td>
                            <td class="text-truncate" style="max-width: 250px;" title="{{ $sesion->observacion_sesion }}">
                                {{ $sesion->observacion_sesion ?? 'Sin observaciones' }}
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group" role="group">
                                    @can('view', $sesion)
                                    <a href="{{ route('sesiones.show', $sesion->id_sesiones) }}" class="btn btn-sm btn-outline-primary fw-bold" title="Tomar o Ver Lista de Asistencia">
                                        <i class="bi bi-clipboard-check me-1"></i> Asistencia
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
                            <td colspan="4" class="text-center text-muted py-5">
                                <i class="bi bi-journal-x fs-1 d-block mb-3 opacity-50"></i>
                                <h6 class="fw-bold">No hay clases programadas</h6>
                                <p class="small mb-0">Esta sección aún no registra sesiones de clases en el calendario.</p>
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