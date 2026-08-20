@extends('layouts.app')

@section('styles')
<!-- CSS de Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
@endsection

@section('content')
<!-- Encabezado de la página -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Aperturar Sesión de Clase
    </h2>

    <nav>
        <ol class="flex items-center gap-2 text-sm">
            <li>
                <a class="hover:text-primary" href="{{ url('/') }}">Inicio /</a>
            </li>
            <li>
                <a class="hover:text-primary" href="{{ route('sesiones.index') }}">Mis Clases /</a>
            </li>
            <li class="text-primary font-medium">Nueva Sesión</li>
        </ol>
    </nav>
</div>

<div class="flex justify-center">
    <div class="w-full max-w-3xl">
        <!-- Contenedor Principal -->
        <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
            
            <div class="border-b border-stroke py-4 px-6.5 dark:border-strokedark bg-black dark:bg-meta-4">
                <h3 class="font-bold text-white flex items-center gap-2">
                    <svg class="fill-current w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19 3H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2zm0 16H5V5h14v14z"/><path d="M12 11c1.654 0 3-1.346 3-3s-1.346-3-3-3-3 1.346-3 3 1.346 3 3 3zm0-4c.551 0 1 .449 1 1s-.449 1-1 1-1-.449-1-1 .449-1 1-1zm-5 9c0-1.782 2.378-2.5 5-2.5s5 .718 5 2.5v1H7v-1z"/></svg>
                    Registrar Clase del Día
                </h3>
            </div>

            <div class="p-6.5">
                @if($grupos->isEmpty())
                <div class="flex w-full rounded-md border-l-6 border-warning bg-warning bg-opacity-10 px-7 py-8 shadow-md dark:bg-[#1B1B24] dark:bg-opacity-30 md:p-9">
                    <div class="mr-5 flex h-9 w-full max-w-[36px] items-center justify-center rounded-lg bg-warning">
                        <svg width="19" height="16" viewBox="0 0 19 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.50493 16H17.5023C18.6204 16 19.3413 14.9018 18.8354 13.9735L10.8367 0.770573C10.2852 -0.256858 8.70677 -0.256858 8.15528 0.770573L0.156617 13.9735C-0.334072 14.8998 0.386764 16 1.50493 16ZM10.7585 12.9298C10.7585 13.6155 10.2223 14.1433 9.45583 14.1433C8.6894 14.1433 8.15311 13.6155 8.15311 12.9298V12.9015C8.15311 12.2159 8.6894 11.688 9.45583 11.688C10.2223 11.688 10.7585 12.2159 10.7585 12.9015V12.9298ZM8.75236 4.01062H10.2548C10.6674 4.01062 10.9127 4.33826 10.8671 4.75288L10.2071 10.1186C10.1615 10.5049 9.88572 10.7455 9.50142 10.7455C9.11923 10.7455 8.84138 10.5028 8.7978 10.1186L8.13781 4.75288C8.09423 4.33826 8.3395 4.01062 8.75236 4.01062Z" fill="#FFFFFF"></path></svg>
                    </div>
                    <div class="w-full">
                        <h5 class="mb-3 text-lg font-bold text-[#9D5425]">Sin Carga Académica</h5>
                        <p class="leading-relaxed text-[#D0915C]">
                            Control de estudios aún no le ha asignado grupos académicos a su perfil. No puede aperturar clases hasta que se le asigne al menos un salón.
                        </p>
                    </div>
                </div>
                @else
                <form action="{{ route('sesiones.store') }}" method="POST">
                    @csrf

                    <div class="mb-5">
                        <label class="mb-3 block text-sm font-bold text-black dark:text-white">
                            1. Seleccione el Grupo a evaluar <span class="text-danger">*</span>
                        </label>
                        <select name="id_grupo" required class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary @error('id_grupo') border-danger @enderror">
                            <option value="" selected disabled>Seleccione de su carga académica...</option>
                            @foreach($grupos as $grupo)
                            <option value="{{ $grupo->id_grupo }}" {{ old('id_grupo') == $grupo->id_grupo ? 'selected' : '' }}>
                                Cohorte {{ $grupo->cohorte->numero_cohorte }} — {{ $grupo->pnf->nombre_pnf }} ({{ $grupo->nivel_academico }})
                            </option>
                            @endforeach
                        </select>
                        @error('id_grupo') <p class="mt-2 text-sm font-bold text-danger">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-5">
                        <label class="mb-3 block text-sm font-bold text-black dark:text-white">
                            2. Fecha de la Clase (Solo Miércoles) <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="fecha_sesion" name="fecha_sesion" value="{{ old('fecha_sesion') }}" placeholder="Seleccione un miércoles..." required 
                            class="w-full rounded border-[1.5px] border-stroke bg-white py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:text-black @error('fecha_sesion') border-danger @enderror">
                        @error('fecha_sesion') <p class="mt-2 text-sm font-bold text-danger">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="mb-3 block text-sm font-bold text-black dark:text-white">
                            3. Tema u Observaciones de la Clase <span class="text-gray-500 font-normal small">(Opcional)</span>
                        </label>
                        <textarea name="observacion_sesion" rows="3" placeholder="Ej: Unidad II - Fundamentos de Programación Asíncrona"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary @error('observacion_sesion') border-danger @enderror">{{ old('observacion_sesion') }}</textarea>
                        @error('observacion_sesion') <p class="mt-2 text-sm font-bold text-danger">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-4 border-t border-stroke pt-6 dark:border-strokedark">
                        <a href="{{ route('sesiones.index') }}" class="flex justify-center rounded border border-stroke py-2 px-6 font-medium text-black hover:shadow-1 dark:border-strokedark dark:text-white">
                            Cancelar
                        </a>
                        <button type="submit" class="flex justify-center rounded bg-primary py-2 px-6 font-medium text-white hover:bg-opacity-90">
                            Aperturar e Ir a Lista
                        </button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Scripts de Flatpickr y Traducción al Español (Intactos) -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const recesosDB = {!! json_encode($periodosRecesos, JSON_HEX_TAG) !!};
        let bloqueosFlatpickr = recesosDB.map(receso => {
            return {
                from: receso.fecha_inicio_periodo_receso.split('T')[0],
                to: receso.fecha_fin_periodo_receso.split('T')[0]
            };
        });
        bloqueosFlatpickr.push(function(date) {
            return (date.getDay() !== 3);
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