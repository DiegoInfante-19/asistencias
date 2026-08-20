@extends('layouts.app')

@section('content')
<!-- Encabezado de la página -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Mis Sesiones de Clase
    </h2>

    <nav>
        <ol class="flex items-center gap-2 text-sm">
            <li><a class="hover:text-primary" href="{{ url('/') }}">Inicio /</a></li>
            <li class="text-primary font-medium">Clases</li>
        </ol>
    </nav>
</div>

<!-- Cabecera de Acción -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h4 class="text-xl font-bold text-black dark:text-white flex items-center gap-2">
            Historial de Clases
        </h4>
        <p class="text-sm text-gray-500 mt-1">Gestione sus sesiones y registros de asistencia</p>
    </div>

    @can('create', App\Models\Sesion::class)
    <a href="{{ route('sesiones.create') }}" class="inline-flex items-center justify-center gap-2.5 rounded-md bg-primary py-2 px-4 text-center font-medium text-white hover:bg-opacity-90">
        <svg class="fill-current w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2H7V9h2V7h2v2h2v2h-2v2z"/></svg>
        Aperturar Nueva Clase
    </a>
    @endcan
</div>

<!-- Contenedor Principal (Tabla) -->
<div class="rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
    
    <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto">
            <thead>
                <tr class="bg-gray-2 text-left dark:bg-meta-4">
                    <th class="min-w-[120px] py-4 px-4 font-medium text-black dark:text-white">Fecha</th>
                    <th class="min-w-[100px] py-4 px-4 font-medium text-black dark:text-white">Cohorte</th>
                    <th class="min-w-[150px] py-4 px-4 font-medium text-black dark:text-white">PNF / Nivel</th>
                    <th class="min-w-[200px] py-4 px-4 font-medium text-black dark:text-white">Observaciones</th>
                    <th class="py-4 px-4 font-medium text-black dark:text-white text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sesiones as $sesion)
                <tr>
                    <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                        <p class="font-semibold text-black dark:text-white">
                            {{ \Carbon\Carbon::parse($sesion->fecha_sesion)->format('d/m/Y') }}
                        </p>
                    </td>
                    <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                        <span class="inline-flex rounded-full bg-primary bg-opacity-10 py-1 px-3 text-sm font-medium text-primary">
                            {{ $sesion->grupo->cohorte->numero_cohorte }}
                        </span>
                    </td>
                    <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                        <p class="font-bold text-black dark:text-white">{{ $sesion->grupo->pnf->nombre_pnf }}</p>
                        <p class="text-sm text-gray-500">{{ $sesion->grupo->nivel_academico }}</p>
                    </td>
                    <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                        <p class="text-sm truncate max-w-[200px]" title="{{ $sesion->observacion_sesion }}">
                            {{ $sesion->observacion_sesion ?? 'Sin observaciones' }}
                        </p>
                    </td>
                    <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark text-center">
                        <div class="flex items-center justify-center gap-2">
                            @can('view', $sesion)
                            <a href="{{ route('sesiones.show', $sesion->id_sesiones) }}" class="inline-flex rounded border border-primary py-1 px-3 text-sm font-medium text-primary hover:bg-primary hover:text-white transition">
                                Ver Lista
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
                            <span class="inline-flex rounded bg-gray-200 py-1 px-2 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300" title="Ventana de edición de 48h expirada">
                                Bloqueada
                            </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="border-b border-[#eee] py-8 px-4 text-center dark:border-strokedark">
                        <p class="text-gray-500 font-medium">No hay clases registradas</p>
                        <p class="text-sm text-gray-400 mt-1">Haga clic en "Aperturar Nueva Clase" para iniciar su primer registro de asistencia.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($sesiones->hasPages())
    <div class="border-t border-stroke py-4 mt-2 dark:border-strokedark">
        {{ $sesiones->links() }}
    </div>
    @endif
</div>
@endsection