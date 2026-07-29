@extends('layouts.admin')

@section('header')
<!-- 1. CSS de Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- Opcional: Tema claro minimalista -->
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">

<x-page-header title="Aperturar Sesión de Clase">
    <li class="breadcrumb-item"><a href="{{ route('sesiones.index') }}">Mis Clases</a></li>
    <li class="breadcrumb-item active" aria-current="page">Nueva Sesión</li>
</x-page-header>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-door-open me-2"></i>Registrar Clase del Día</h5>
                </div>
                
                <div class="card-body p-4 bg-light">
                    
                    @if($grupos->isEmpty())
                        <div class="alert alert-warning border-0 shadow-sm">
                            <h5 class="alert-heading fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Sin Carga Académica</h5>
                            <p class="mb-0">Control de estudios aún no le ha asignado grupos académicos a su perfil. No puede aperturar clases hasta que se le asigne al menos un salón.</p>
                        </div>
                    @else
                        <form action="{{ route('sesiones.store') }}" method="POST">
                            @csrf
                            
                            <!-- Selección del Grupo Autorizado -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">1. Seleccione el Grupo a evaluar <span class="text-danger">*</span></label>
                                <select class="form-select border-secondary @error('id_grupo') is-invalid @enderror" name="id_grupo" required>
                                    <option value="" selected disabled>Seleccione de su carga académica...</option>
                                    @foreach($grupos as $grupo)
                                        <option value="{{ $grupo->id_grupo }}" {{ old('id_grupo') == $grupo->id_grupo ? 'selected' : '' }}>
                                            Cohorte {{ $grupo->cohorte->numero_cohorte }} — {{ $grupo->pnf->nombre_pnf }} ({{ $grupo->nivel_academico }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_grupo') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                            </div>

                            <!-- Fecha de la Clase (Modificado para Flatpickr) -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">2. Fecha de la Clase (Solo Miércoles) <span class="text-danger">*</span></label>
                                <input type="text" id="fecha_sesion" class="form-control border-secondary @error('fecha_sesion') is-invalid @enderror" 
                                       name="fecha_sesion" 
                                       value="{{ old('fecha_sesion') }}" 
                                       placeholder="Seleccione un miércoles..."
                                       required style="background-color: white;">
                                @error('fecha_sesion') <div class="invalid-feedback d-block fw-bold">{{ $message }}</div> @enderror
                            </div>

                            <!-- Observaciones / Tema de la clase (CORREGIDO A OBSERVACION_SESION) -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">3. Tema u Observaciones de la Clase <span class="text-muted small">(Opcional)</span></label>
                                <textarea class="form-control border-secondary @error('observacion_sesion') is-invalid @enderror" 
                                          name="observacion_sesion" rows="3" 
                                          placeholder="Ej: Unidad II - Fundamentos de Programación Asíncrona">{{ old('observacion_sesion') }}</textarea>
                                @error('observacion_sesion') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end border-top pt-4">
                                <a href="{{ route('sesiones.index') }}" class="btn btn-outline-secondary fw-bold px-4">Cancelar</a>
                                <button type="submit" class="btn btn-primary fw-bold px-4 shadow-sm">
                                    <i class="bi bi-door-open-fill me-2"></i> Aperturar e Ir a Lista
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<!-- Scripts de Flatpickr y Traducción al Español -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. Inyectar los recesos desde Laravel de forma segura
        const recesosDB = {!! json_encode($periodosRecesos, JSON_HEX_TAG) !!};
        
        // 2. Mapear los recesos al formato de rango de Flatpickr { from: "...", to: "..." }
        let bloqueosFlatpickr = recesosDB.map(receso => {
            return {
                from: receso.fecha_inicio_periodo_receso.split('T')[0], 
                to: receso.fecha_fin_periodo_receso.split('T')[0]
            };
        });

        // 3. Añadir la regla matemática para deshabilitar días distintos al miércoles
        bloqueosFlatpickr.push(function(date) {
            return (date.getDay() !== 3);
        });

        // 4. Inicializar Flatpickr con ambas restricciones
        flatpickr("#fecha_sesion", {
            locale: "es", 
            dateFormat: "Y-m-d", 
            maxDate: "today", 
            disable: bloqueosFlatpickr,
            allowInput: false, 
        });
    });
</script>
@endsection