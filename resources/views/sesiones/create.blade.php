@extends('layouts.admin')

@section('header')
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

                            <!-- Fecha de la Clase -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">2. Fecha de la Clase <span class="text-danger">*</span></label>
                                <!-- max="\{\{ date('Y-m-d') \}\}" impide que registre clases en el futuro -->
                                <input type="date" class="form-control border-secondary @error('fecha_sesion') is-invalid @enderror" 
                                       name="fecha_sesion" 
                                       value="{{ old('fecha_sesion', date('Y-m-d')) }}" 
                                       max="{{ date('Y-m-d') }}" 
                                       required>
                                @error('fecha_sesion') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                            </div>

                            <!-- Observaciones / Tema de la clase -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">3. Tema u Observaciones de la Clase <span class="text-muted small">(Opcional)</span></label>
                                <textarea class="form-control border-secondary @error('observaciones_sesiones') is-invalid @enderror" 
                                          name="observaciones_sesiones" rows="3" 
                                          placeholder="Ej: Unidad II - Fundamentos de Programación Asíncrona">{{ old('observaciones_sesiones') }}</textarea>
                                @error('observaciones_sesiones') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
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

