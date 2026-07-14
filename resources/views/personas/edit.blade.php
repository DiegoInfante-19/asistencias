@extends('layouts.admin')

@section('content')
<div class="content pt-4" style="margin: 20px;">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h3 class="card-title fw-bold text-dark mb-0">
                Editar Datos Básicos: <span class="text-primary">{{ $persona->nombres }} {{ $persona->apellidos }}</span>
            </h3>
            <!-- Botón para regresar al expediente principal (show) -->
            <a href="{{ route('personas.show', $persona->id_personas) }}" class="btn btn-outline-secondary ms-auto">
                <i class="bi bi-arrow-left-circle me-1"></i><b>Volver al Expediente</b>
            </a>
        </div>

        <div class="card-body">
            <!-- Apuntamos a la ruta UPDATE y pasamos el ID -->
            <form action="{{ route('personas.update', $persona->id_personas) }}" method="POST" id="editPersonaForm">
                @csrf
                @method('PUT') <!-- Falsificación de método requerida por Laravel para actualizaciones -->

                <div class="row g-3">
                    <!-- Cédula -->
                    <div class="col-md-4">
                        <label for="cedula" class="form-label fw-bold">Cédula <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('cedula') is-invalid @enderror" 
                               id="cedula" name="cedula" value="{{ old('cedula', $persona->cedula) }}" required>
                        @error('cedula')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nombres -->
                    <div class="col-md-4">
                        <label for="nombres" class="form-label fw-bold">Nombres <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombres') is-invalid @enderror" 
                               id="nombres" name="nombres" value="{{ old('nombres', $persona->nombres) }}" required>
                        @error('nombres')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Apellidos -->
                    <div class="col-md-4">
                        <label for="apellidos" class="form-label fw-bold">Apellidos <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('apellidos') is-invalid @enderror" 
                               id="apellidos" name="apellidos" value="{{ old('apellidos', $persona->apellidos) }}" required>
                        @error('apellidos')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Fecha de Nacimiento -->
                    <div class="col-md-6">
                        <label for="fecha_nacimiento" class="form-label fw-bold">Fecha de Nacimiento <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror" 
                               id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $persona->fecha_nacimiento) }}" required>
                        @error('fecha_nacimiento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Correo Electrónico -->
                    <div class="col-md-6">
                        <label for="correo_electronico" class="form-label fw-bold">Correo Electrónico</label>
                        <input type="email" class="form-control @error('correo_electronico') is-invalid @enderror" 
                               id="correo_electronico" name="correo_electronico" value="{{ old('correo_electronico', $persona->correo_electronico) }}">
                        @error('correo_electronico')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Lugar de Nacimiento -->
                    <div class="col-12">
                        <label for="lugar_nacimiento_personas" class="form-label fw-bold">Lugar de Nacimiento <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('lugar_nacimiento_personas') is-invalid @enderror" 
                               id="lugar_nacimiento_personas" name="lugar_nacimiento_personas" 
                               value="{{ old('lugar_nacimiento_personas', $persona->lugar_nacimiento_personas) }}" required>
                        @error('lugar_nacimiento_personas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Dirección -->
                    <div class="col-12">
                        <label for="direccion" class="form-label fw-bold">Dirección Completa <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('direccion') is-invalid @enderror" 
                                  id="direccion" name="direccion" rows="3" required>{{ old('direccion', $persona->direccion) }}</textarea>
                        @error('direccion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('personas.show', $persona->id_personas) }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-warning text-dark fw-bold">
                        <i class="bi bi-save-fill me-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/core-validations.js') }}" defer></script>
<script src="{{ asset('js/admin-validations.js') }}" defer></script>
@endsection