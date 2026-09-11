<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark fs-6">
            <i class="bi bi-calendar-check text-primary me-2"></i> Control de Sesiones y Asistencias (Días Miércoles)
        </h5>
        
        <!-- BOTÓN INTELIGENTE SEGÚN EL ROL -->
        <div>
            @if(auth()->user()->isAdmin() || auth()->user()->isCoordinador())
                <button type="button" class="btn btn-primary btn-sm fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalMaquinaTiempoSesion">
                    <i class="bi bi-clock-history me-1"></i> + Registrar Sesión (Administrativo)
                </button>
            @elseif(auth()->user()->isProfesor())
                <form action="{{ route('sesiones.store') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="id_seccion" value="{{ $seccion->id_seccion }}">
                    <input type="hidden" name="id_profesor" value="{{ auth()->user()->profesor->id_profesor ?? '' }}">
                    <input type="hidden" name="fecha_sesion" value="{{ now()->isWednesday() ? now()->format('Y-m-d') : now()->next(Carbon\Carbon::WEDNESDAY)->format('Y-m-d') }}">
                    <button type="submit" class="btn btn-primary btn-sm fw-bold shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> + Registrar Sesión de Clase
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="card-body bg-white p-4">
        <div class="table-responsive">
            <!-- AQUÍ RENDERIZAMOS EL DATATABLE DE SESIONES -->
            {{ $sesionesDataTable->html()->table(['class' => 'table table-hover align-middle mb-0 w-100']) }}
        </div>
    </div>
    <div class="card-footer bg-light py-2 text-muted small">
        <span>Historial cronológico de clases de los días miércoles.</span>
    </div>
</div>