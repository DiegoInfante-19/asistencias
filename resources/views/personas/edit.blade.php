@extends('layouts.app')

@section('content')
<div class="content pt-4" style="margin: 20px;">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h3 class="card-title fw-bold text-dark mb-0">
                Editar Datos Básicos:
                <span class="text-primary">{{ $persona->primer_nombre_personas }} {{ $persona->primer_apellido_personas }}</span>
            </h3>
            <!-- Botón para regresar al expediente principal (show) -->
            <a href="{{ route('personas.show', $persona->id_personas) }}" class="btn btn-outline-secondary ms-auto">
                <b>Revisar Expediente</b>
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('personas.update', $persona->id_personas) }}" method="POST" id="editPersonaForm">
                @csrf
                @method('PUT') <!-- Requerido por Laravel para actualizar -->

                <div class="row g-3">
                    <!-- Cédula -->
                    <div class="col-md-4">
                        <label for="cedula_personas" class="form-label fw-bold">Cédula <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('cedula_personas') is-invalid @enderror"
                            id="cedula_personas" name="cedula_personas" value="{{ old('cedula_personas', $persona->cedula_personas) }}"
                            autocomplete="off" required>
                        @error('cedula_personas')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nombres -->
                    <div class="col-md-4">
                        <label for="primer_nombre_personas" class="form-label fw-bold">Primer Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('primer_nombre_personas') is-invalid @enderror"
                            id="primer_nombre_personas" name="primer_nombre_personas" value="{{ old('primer_nombre_personas', $persona->primer_nombre_personas) }}"
                            autocomplete="off" required>
                        @error('primer_nombre_personas')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="segundo_nombre_personas" class="form-label fw-bold">Segundo Nombre <span class="text-muted fw-normal">(Opcional)</span></label>
                        <input type="text" class="form-control @error('segundo_nombre_personas') is-invalid @enderror"
                            id="segundo_nombre_personas" name="segundo_nombre_personas" value="{{ old('segundo_nombre_personas', $persona->segundo_nombre_personas) }}"
                            autocomplete="off">
                    </div>

                    <!-- Apellidos -->
                    <div class="col-md-4">
                        <label for="primer_apellido_personas" class="form-label fw-bold">Primer Apellido <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('primer_apellido_personas') is-invalid @enderror"
                            id="primer_apellido_personas" name="primer_apellido_personas" value="{{ old('primer_apellido_personas', $persona->primer_apellido_personas) }}"
                            autocomplete="off" required>
                    </div>

                    <div class="col-md-4">
                        <label for="segundo_apellido_personas" class="form-label fw-bold">Segundo Apellido <span class="text-muted fw-normal">(Opcional)</span></label>
                        <input type="text" class="form-control @error('segundo_apellido_personas') is-invalid @enderror"
                            id="segundo_apellido_personas" name="segundo_apellido_personas" value="{{ old('segundo_apellido_personas', $persona->segundo_apellido_personas) }}"
                            autocomplete="off">
                    </div>

                    <!-- Sexo -->
                    <div class="col-md-4">
                        <label for="sexo_personas" class="form-label fw-bold">Sexo <span class="text-danger">*</span></label>
                        <select class="form-select @error('sexo_personas') is-invalid @enderror" id="sexo_personas" name="sexo_personas" required>
                            <option value="">Seleccione...</option>
                            <option value="M" {{ old('sexo_personas', $persona->sexo_personas) == 'M' ? 'selected' : '' }}>Masculino</option>
                            <option value="F" {{ old('sexo_personas', $persona->sexo_personas) == 'F' ? 'selected' : '' }}>Femenino</option>
                        </select>
                    </div>

                    <!-- Fecha de Nacimiento -->
                    <div class="col-md-4">
                        <label for="fecha_nacimiento_personas" class="form-label fw-bold">Fecha de Nacimiento <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('fecha_nacimiento_personas') is-invalid @enderror"
                            id="fecha_nacimiento_personas" name="fecha_nacimiento_personas" value="{{ old('fecha_nacimiento_personas', $persona->fecha_nacimiento_personas) }}" autocomplete="off" required>
                    </div>

                    <!-- Correo -->
                    <div class="col-md-4">
                        <label for="email_personas" class="form-label fw-bold">Correo Electrónico <span class="text-muted fw-normal">(Opcional)</span></label>
                        <input type="email" class="form-control @error('email_personas') is-invalid @enderror"
                            id="email_personas" name="email_personas" value="{{ old('email_personas', $persona->email_personas) }}" autocomplete="off">
                    </div>

                    <!-- ========================================== -->
                    <!-- SECCIÓN: LUGAR DE NACIMIENTO -->
                    <!-- ========================================== -->
                    <div class="col-12 mt-4">
                        <h5 class="fw-bold border-bottom pb-2"><i class="bi bi-geo-alt-fill me-2"></i>Lugar de Nacimiento</h5>
                    </div>

                    @php
                    // CORREGIDO: El estado actual ya no se lee de lugarNacimiento directamente, sino a través de la ciudad
                    $estadoActual = $persona->lugarNacimiento->ciudad->id_estado ?? '';
                    $ciudadActual = $persona->lugarNacimiento->id_ciudad ?? '';
                    $detallesActual = $persona->lugarNacimiento->detalles_adicionales ?? '';
                    @endphp

                    <!-- Estado -->
                    <div class="col-md-4">
                        <label for="id_estado" class="form-label fw-bold">Estado <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_estado') is-invalid @enderror" id="id_estado" name="id_estado" required>
                            <option value="">Seleccione...</option>
                            @foreach($estados as $estado)
                            <option value="{{ $estado->id_estado }}" {{ old('id_estado', $estadoActual) == $estado->id_estado ? 'selected' : '' }}>
                                {{ $estado->nombre_estado }}
                            </option>
                            @endforeach
                        </select>
                        @error('id_estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Ciudad (Con botón Collapse) -->
                    <div class="col-md-4">
                        <label for="id_ciudad" class="form-label fw-bold">Ciudad <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select class="form-select @error('id_ciudad') is-invalid @enderror" id="id_ciudad" name="id_ciudad" required disabled>
                                <option value="">Seleccione primero un estado...</option>
                            </select>
                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNuevaCiudad" aria-expanded="false" aria-controls="collapseNuevaCiudad" id="btnToggleCiudad" disabled>
                                <i class="bi bi-plus-lg"></i> Nueva
                            </button>
                        </div>
                        @error('id_ciudad') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Detalles Adicionales -->
                    <div class="col-md-4">
                        <label for="detalles_adicionales" class="form-label fw-bold">Detalles (Opcional)</label>
                        <input type="text" class="form-control" id="detalles_adicionales" name="detalles_adicionales" value="{{ old('detalles_adicionales', $detallesActual) }}" placeholder="Ej: Hospital Ruiz y Páez">
                    </div>

                    <!-- ========================================== -->
                    <!-- PANEL COLAPSABLE: NUEVA CIUDAD -->
                    <!-- ========================================== -->
                    <div class="col-5">
                        <div class="collapse" id="collapseNuevaCiudad">
                            <div class="card card-body bg-light border-primary shadow-sm mt-2">
                                <h6 class="fw-bold text-primary mb-3"><i class="bi bi-building-add me-2"></i>Registrar Nueva Ciudad</h6>
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-8">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Nombre de la Ciudad</label>
                                        <input type="text" class="form-control" id="nueva_nombre_ciudad" placeholder="Ej: Puerto Ordaz">
                                        <small class="text-muted">Se asociará automáticamente al Estado seleccionado arriba.</small>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary w-100 fw-bold" id="btnGuardarCiudad">
                                            <i class="bi bi-save me-1"></i> Guardar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <hr class="my-4">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('personas.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-warning text-dark fw-bold">
                        <i class="bi bi-pencil-square me-1"></i> Actualizar Estudiante
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- 1. CARGAMOS JQUERY PRIMERO -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>

<script src="{{ asset('js/core-validations.js') }}" defer></script>
<script src="{{ asset('js/admin-validations.js') }}" defer></script>

<script>
    $(document).ready(function() {

        function mostrarErrorSwal(titulo, mensaje) {
            Swal.fire({
                icon: 'error',
                title: titulo,
                text: mensaje,
                confirmButtonColor: '#0d6efd'
            });
        }

        const estadoSelect = $('#id_estado');
        const ciudadSelect = $('#id_ciudad');
        const btnToggleCiudad = $('#btnToggleCiudad');
        const collapseElement = $('#collapseNuevaCiudad');
        const inputNuevaCiudad = $('#nueva_nombre_ciudad');
        const btnGuardarCiudad = $('#btnGuardarCiudad');

        // FUNCIÓN CENTRALIZADA PARA CARGAR CIUDADES
        function cargarCiudades(id_estado, id_ciudad_seleccionada = null) {
            if (!id_estado) {
                ciudadSelect.prop('disabled', true).empty().append('<option value="">Seleccione primero un estado...</option>');
                btnToggleCiudad.prop('disabled', true);
                collapseElement.collapse('hide');
                return;
            }

            let url = "{{ route('api.ciudades.get', ['id_estado' => ':id']) }}".replace(':id', id_estado);

            $.get(url, function(data) {
                ciudadSelect.prop('disabled', false);
                btnToggleCiudad.prop('disabled', false);
                ciudadSelect.empty().append('<option value="">Seleccione la ciudad...</option>');

                $.each(data, function(key, ciudad) {
                    let selected = (id_ciudad_seleccionada && ciudad.id_ciudad == id_ciudad_seleccionada) ? 'selected' : '';
                    ciudadSelect.append('<option value="' + ciudad.id_ciudad + '" ' + selected + '>' + ciudad.nombre_ciudad + '</option>');
                });
            }).fail(function() {
                mostrarErrorSwal('Error de carga', 'Ocurrió un error al cargar las ciudades.');
            });
        }

        // 1. EVENTO CHANGE
        estadoSelect.on('change', function() {
            cargarCiudades($(this).val());
        });

        // 2. PERSISTENCIA Y CARGA INICIAL (Combina BD y Errores de Validación)
        // Tomamos el 'old' si hubo error, sino tomamos el valor actual de la BD que inyectamos en Blade
        let oldEstado = "{{ old('id_estado', $estadoActual ?? '') }}";
        let oldCiudad = "{{ old('id_ciudad', $ciudadActual ?? '') }}";

        if (oldEstado) {
            cargarCiudades(oldEstado, oldCiudad);
        }

        // 3. Guardar Ciudad por AJAX
        btnGuardarCiudad.on('click', function() {
            let id_estado = estadoSelect.val();
            let nombre_ciudad = inputNuevaCiudad.val().trim();

            if (!nombre_ciudad) {
                mostrarErrorSwal('Campo requerido', 'Por favor, escriba el nombre de la ciudad.');
                return;
            }

            btnGuardarCiudad.prop('disabled', true).html('<i class="spinner-border spinner-border-sm"></i> Guardando...');

            $.ajax({
                type: "POST",
                url: "{{ route('ciudades.store') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    id_estado: id_estado,
                    nombre_ciudad: nombre_ciudad,
                    origen: 'create_ciudad'
                },
                success: function(response) {
                    ciudadSelect.append('<option value="' + response.ciudad.id_ciudad + '" selected>' + response.ciudad.nombre_ciudad + '</option>');
                    inputNuevaCiudad.val('');
                    collapseElement.collapse('hide');
                    btnGuardarCiudad.prop('disabled', false).html('<i class="bi bi-save me-1"></i> Guardar');
                },
                error: function(xhr) {
                    btnGuardarCiudad.prop('disabled', false).html('<i class="bi bi-save me-1"></i> Guardar');
                    if (xhr.status === 422) {
                        let errores = xhr.responseJSON.errors;
                        mostrarErrorSwal('Verifique los datos', errores.nombre_ciudad ? errores.nombre_ciudad[0] : 'Error de validación');
                    } else {
                        mostrarErrorSwal('Error del servidor', 'No se pudo guardar la ciudad.');
                    }
                }
            });
        });
    });
</script>
@endsection