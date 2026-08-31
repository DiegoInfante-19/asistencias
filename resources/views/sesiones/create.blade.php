@extends('layouts.admin')

@section('header')
<!-- CSS de Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">

<x-page-header title="Programar Sesión de Clase">
    <li class="breadcrumb-item"><a href="{{ route('sesiones.index') }}">Calendario Institucional</a></li>
    <li class="breadcrumb-item active" aria-current="page">Programar Clase</li>
</x-page-header>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-calendar-plus me-2"></i>Programación Administrativa de Clase</h5>
                </div>
                
                <div class="card-body p-4 bg-light">
                    
                    @if($secciones->isEmpty())
                        <div class="alert alert-warning border-0 shadow-sm">
                            <h5 class="alert-heading fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>No hay Secciones Activas</h5>
                            <p class="mb-0">Debe crear al menos una Sección y asignarle un Profesor antes de poder programar clases en el calendario.</p>
                        </div>
                    @else
                        <form action="{{ route('sesiones.store') }}" method="POST">
                            @csrf
                            
                            <!-- 1. Selección de la Sección -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">1. Seleccione la Sección <span class="text-danger">*</span></label>
                                <select class="form-select border-secondary @error('id_seccion') is-invalid @enderror" name="id_seccion" id="id_seccion" required>
                                    <option value="" selected disabled>Seleccione una sección...</option>
                                    @foreach($secciones as $seccion)
                                        <option value="{{ $seccion->id_seccion }}" {{ old('id_seccion') == $seccion->id_seccion ? 'selected' : '' }}>
                                            {{ $seccion->nombre_seccion }} — {{ $seccion->pnf->nombre_pnf ?? 'Sin PNF' }} (Cohorte Ref. {{ $seccion->periodoAcademico->cohorte->numero_cohorte ?? 'N/D' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_seccion') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                            </div>

                            <!-- 2. Selección Dinámica del Profesor -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">2. Profesor Responsable <span class="text-danger">*</span></label>
                                <select class="form-select border-secondary @error('id_profesor') is-invalid @enderror" name="id_profesor" id="id_profesor" required disabled>
                                    <option value="" selected disabled>Primero seleccione una sección...</option>
                                </select>
                                <div class="form-text text-muted small">Solo se muestran los docentes asignados previamente a esta sección.</div>
                                @error('id_profesor') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                            </div>

                            <!-- 3. Fecha de la Clase (Flatpickr) -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">3. Fecha de la Clase (Solo Miércoles) <span class="text-danger">*</span></label>
                                <input type="text" id="fecha_sesion" class="form-control border-secondary @error('fecha_sesion') is-invalid @enderror" 
                                       name="fecha_sesion" 
                                       value="{{ old('fecha_sesion') }}" 
                                       placeholder="Seleccione un miércoles..."
                                       required style="background-color: white;">
                                @error('fecha_sesion') <div class="invalid-feedback d-block fw-bold">{{ $message }}</div> @enderror
                            </div>

                            <!-- 4. Observaciones -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">4. Observaciones de Programación <span class="text-muted small">(Opcional)</span></label>
                                <textarea class="form-control border-secondary @error('observacion_sesion') is-invalid @enderror" 
                                          name="observacion_sesion" rows="3" 
                                          placeholder="Ej: Clase de recuperación / Suplencia">{{ old('observacion_sesion') }}</textarea>
                                @error('observacion_sesion') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end border-top pt-4">
                                <a href="{{ route('sesiones.index') }}" class="btn btn-outline-secondary fw-bold px-4">Cancelar</a>
                                <button type="submit" class="btn btn-primary fw-bold px-4 shadow-sm">
                                    <i class="bi bi-calendar-check-fill me-2"></i> Programar Clase
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
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- 1. LÓGICA DINÁMICA DE SECCIONES Y PROFESORES ---
        const selectSeccion = document.getElementById('id_seccion');
        const selectProfesor = document.getElementById('id_profesor');
        
        // Mapeamos las secciones y sus profesores desde el Backend usando JSON
        const dataSecciones = @json($secciones->mapWithKeys(function($s) {
            return [$s->id_seccion => $s->profesores->map(function($p) {
                return [
                    'id_profesor' => $p->id_profesor, 
                    'nombre' => trim(($p->user->name_users ?? '') . ' ' . ($p->user->last_name_users ?? ''))
                ];
            })];
        }));

        const oldProfesor = "{{ old('id_profesor') }}";

        function cargarProfesores(idSeccion) {
            selectProfesor.innerHTML = '<option value="" selected disabled>Seleccione el docente...</option>';
            if(idSeccion && dataSecciones[idSeccion]) {
                selectProfesor.disabled = false;
                const profesores = dataSecciones[idSeccion];
                
                if(profesores.length === 0) {
                    selectProfesor.innerHTML = '<option value="" disabled>No hay docentes asignados a esta sección.</option>';
                    return;
                }

                profesores.forEach(prof => {
                    const isSelected = oldProfesor == prof.id_profesor ? 'selected' : '';
                    selectProfesor.innerHTML += `<option value="${prof.id_profesor}" ${isSelected}>${prof.nombre}</option>`;
                });
            } else {
                selectProfesor.disabled = true;
            }
        }

        // Si ya hay una sección seleccionada (por ej. falló validación y recargó)
        if(selectSeccion.value) {
            cargarProfesores(selectSeccion.value);
        }

        // Evento onChange
        selectSeccion.addEventListener('change', function() {
            cargarProfesores(this.value);
        });

        // --- 2. LÓGICA DE FLATPICKR Y RECESOS ---
        const recesosDB = {!! json_encode($periodosRecesos, JSON_HEX_TAG) !!};
        
        let bloqueosFlatpickr = recesosDB.map(receso => {
            return {
                from: receso.fecha_inicio_periodo_receso.split('T')[0], 
                to: receso.fecha_fin_periodo_receso.split('T')[0]
            };
        });

        bloqueosFlatpickr.push(function(date) {
            return (date.getDay() !== 3); // Solo miércoles
        });

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