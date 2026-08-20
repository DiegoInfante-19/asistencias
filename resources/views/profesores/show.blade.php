@extends('layouts.app')

@section('content')
<!-- Encabezado y Migas de Pan -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Ficha de Profesor
    </h2>

    <nav>
        <ol class="flex items-center gap-2 text-sm">
            <li>
                <a class="hover:text-primary" href="{{ url('/') }}">Inicio /</a>
            </li>
            <li>
                <a class="hover:text-primary" href="{{ route('profesores.index') }}">Profesores /</a>
            </li>
            <li class="text-primary font-medium">Ficha</li>
        </ol>
    </nav>
</div>

<!-- Botonera Superior -->
<div class="mb-6 flex justify-end">
    <a href="{{ route('profesores.index') }}" class="inline-flex items-center justify-center gap-2.5 rounded-md border border-primary text-primary hover:bg-primary hover:text-white py-2 px-4 text-center font-medium transition-all">
        <svg class="fill-current" width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M16.6666 9.16666H6.51659L11.1833 4.49999L9.99992 3.33333L3.33325 9.99999L9.99992 16.6667L11.1833 15.5L6.51659 10.8333H16.6666V9.16666Z" fill="currentColor"/>
        </svg>
        Volver al Listado
    </a>
</div>

<!-- GRID PRINCIPAL (Maneja 1 o 2 columnas dependiendo si es profesor o no) -->
<div class="grid grid-cols-1 gap-6 {{ $user->isProfesor() ? 'xl:grid-cols-2' : '' }}">

    <!-- COLUMNA IZQUIERDA: DATOS BÁSICOS DEL USUARIO -->
    <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="border-b border-stroke py-4 px-6.5 dark:border-strokedark">
            <h3 class="font-bold text-black dark:text-white flex items-center gap-2">
                Datos de Identificación
            </h3>
        </div>
        <div class="p-6.5">
            <div class="mb-4 flex flex-wrap gap-3">
                <!-- Badges (Activo / Inactivo) -->
                <span class="inline-flex rounded-full {{ $user->status_users === 'Activo' ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger' }} py-1 px-3 text-sm font-medium">
                    {{ $user->status_users }}
                </span>
                <!-- Badge de Rol -->
                <span class="inline-flex rounded-full bg-primary bg-opacity-10 py-1 px-3 text-sm font-medium text-primary">
                    Rol: {{ $user->rol->nombre_rol ?? 'Sin Rol Asignado' }}
                </span>
            </div>

            <h3 class="mb-6 text-2xl font-bold text-black dark:text-white">
                {{ $user->name_users }} {{ $user->last_name_users }}
            </h3>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-bold text-gray-500 dark:text-gray-400">Cédula de Identidad</label>
                    <p class="text-lg font-semibold text-black dark:text-white">{{ $user->cedula_users }}</p>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-bold text-gray-500 dark:text-gray-400">Nombre de Usuario</label>
                    <p class="text-lg font-medium text-black dark:text-white">{{ $user->username }}</p>
                </div>
                <div class="col-span-1 sm:col-span-2">
                    <hr class="border-stroke dark:border-strokedark">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-bold text-gray-500 dark:text-gray-400">Correo Electrónico</label>
                    <p class="text-base text-black dark:text-white break-words">{{ $user->email_users }}</p>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-bold text-gray-500 dark:text-gray-400">Teléfono de Contacto</label>
                    <p class="text-base text-black dark:text-white">{{ $user->phone_users ?? 'No registrado' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- COLUMNA DERECHA: CONFIGURACIÓN DE PNF Y NIVEL -->
    @if($user->isProfesor())
    <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark border-t-4 border-t-warning">
        <div class="border-b border-stroke py-4 px-6.5 dark:border-strokedark">
            <h3 class="font-bold text-black dark:text-white flex items-center gap-2">
                Perfil Académico
            </h3>
        </div>
        <div class="p-6.5 flex flex-col justify-between h-full">
            
            <div class="mb-6">
                @if($user->profesor && $user->profesor->pnf && $user->profesor->nivel_asignado)
                <div class="rounded-md border border-primary bg-primary bg-opacity-10 p-4">
                    <span class="mb-1 block text-sm font-bold text-primary">ASIGNACIÓN ACTUAL:</span>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-lg font-bold text-black dark:text-white">{{ $user->profesor->pnf->nombre_pnf }}</span>
                        <span class="inline-flex rounded-md bg-gray-200 py-1 px-2 text-xs font-medium text-black dark:bg-gray-700 dark:text-white">
                            {{ $user->profesor->nivel_asignado }}
                        </span>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        Asignado el: {{ \Carbon\Carbon::parse($user->profesor->fecha_asignacion_profesor)->format('d/m/Y') }}
                    </span>
                </div>
                @else
                <div class="rounded-md border border-warning bg-warning bg-opacity-10 p-4">
                    <p class="text-warning font-medium">
                        <b>Perfil incompleto.</b> Defina el PNF y el Nivel Académico para habilitar la asignación de grupos.
                    </p>
                </div>
                @endif
            </div>

            <form action="{{ route('usuarios.asignar_pnf', $user->id_users) }}" method="POST" class="mt-auto">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 mb-4">
                    <div class="sm:col-span-7">
                        <label class="mb-2 block text-sm font-bold text-black dark:text-white">PNF Asignado <span class="text-danger">*</span></label>
                        <select name="id_pnf" required class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                            <option value="" disabled {{ !$user->profesor ? 'selected' : '' }}>Seleccione...</option>
                            @foreach($pnfs as $pnf)
                            <option value="{{ $pnf->id_pnf }}" {{ ($user->profesor && $user->profesor->id_pnf == $pnf->id_pnf) ? 'selected' : '' }}>
                                {{ $pnf->nombre_pnf }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-5">
                        <label class="mb-2 block text-sm font-bold text-black dark:text-white">Nivel <span class="text-danger">*</span></label>
                        <select name="nivel_asignado" required class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                            <option value="" disabled {{ !$user->profesor || !$user->profesor->nivel_asignado ? 'selected' : '' }}>Seleccione...</option>
                            <option value="TSU" {{ ($user->profesor && $user->profesor->nivel_asignado == 'TSU') ? 'selected' : '' }}>TSU</option>
                            <option value="Ingeniería" {{ ($user->profesor && $user->profesor->nivel_asignado == 'Ingeniería') ? 'selected' : '' }}>Ingeniería</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="mb-2 block text-sm font-bold text-black dark:text-white">Fecha de Asignación <span class="text-danger">*</span></label>
                    <input type="date" name="fecha_asignacion_profesor" value="{{ $user->profesor ? $user->profesor->fecha_asignacion_profesor : date('Y-m-d') }}" required
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                </div>

                <button type="submit" class="flex w-full justify-center rounded bg-warning p-3 font-medium text-black hover:bg-opacity-90">
                    {{ ($user->profesor && $user->profesor->id_pnf && $user->profesor->nivel_asignado) ? 'Actualizar Perfil' : 'Registrar Perfil' }}
                </button>
            </form>
        </div>
    </div>
    @endif

    <!-- SECCIÓN INFERIOR: CARGA ACADÉMICA (GRUPOS ASIGNADOS) -->
    @if($user->profesor && $user->profesor->id_pnf && $user->profesor->nivel_asignado)
    <div class="col-span-1 xl:col-span-2 mt-2">
        <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="border-b border-stroke py-4 px-6.5 bg-black dark:bg-meta-4">
                <h3 class="font-bold text-white flex items-center gap-2">
                    Carga Académica (Grupos Asignados)
                </h3>
            </div>
            
            <div class="p-6.5 grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Formulario para agregar un grupo -->
                <div class="md:col-span-1 border-r-0 md:border-r border-stroke pr-0 md:pr-6 dark:border-strokedark">
                    <h4 class="mb-4 text-lg font-semibold text-black dark:text-white">Asignar Nuevo Grupo</h4>
                    <form action="{{ route('usuarios.asignar_grupo', $user->id_users) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="mb-2 block text-sm font-medium text-black dark:text-white">Seleccione el Grupo disponible</label>
                            <select name="id_grupo" required class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                                <option value="" selected disabled>Seleccione un grupo...</option>
                                @php $hayDisponibles = false; @endphp
                                @foreach($gruposDisponibles as $grupo)
                                    @if($grupo->id_pnf == $user->profesor->id_pnf && $grupo->nivel_academico == $user->profesor->nivel_asignado)
                                        @if(!$user->profesor->grupos->contains('id_grupo', $grupo->id_grupo))
                                            @php $hayDisponibles = true; @endphp
                                            <option value="{{ $grupo->id_grupo }}">
                                                Cohorte: {{ $grupo->cohorte->numero_cohorte ?? 'N/D' }} ({{ $grupo->nivel_academico }})
                                            </option>
                                        @endif
                                    @endif
                                @endforeach

                                @if(!$hayDisponibles)
                                    <option value="" disabled>No hay grupos disponibles para este PNF/Nivel</option>
                                @endif
                            </select>
                        </div>
                        <button type="submit" class="flex w-full justify-center rounded bg-primary p-3 font-medium text-white hover:bg-opacity-90">
                            Añadir a la Carga
                        </button>
                    </form>
                </div>

                <!-- Lista de grupos asignados -->
                <div class="md:col-span-2">
                    <h4 class="mb-4 text-lg font-semibold text-black dark:text-white">Grupos Actuales del Docente</h4>
                    
                    <div class="max-w-full overflow-x-auto">
                        <table class="w-full table-auto">
                            <thead>
                                <tr class="bg-gray-2 text-left dark:bg-meta-4">
                                    <th class="min-w-[120px] py-4 px-4 font-medium text-black dark:text-white">Cohorte</th>
                                    <th class="min-w-[150px] py-4 px-4 font-medium text-black dark:text-white">PNF</th>
                                    <th class="min-w-[120px] py-4 px-4 font-medium text-black dark:text-white">Nivel Académico</th>
                                    <th class="py-4 px-4 font-medium text-black dark:text-white text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->profesor->grupos as $grupoAsignado)
                                <tr>
                                    <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                                        <p class="font-bold text-black dark:text-white">{{ $grupoAsignado->cohorte->numero_cohorte ?? 'N/D' }}</p>
                                    </td>
                                    <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                                        <p class="text-black dark:text-white">{{ $user->profesor->pnf->nombre_pnf }}</p>
                                    </td>
                                    <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                                        <span class="inline-flex rounded-full bg-gray-200 py-1 px-3 text-sm font-medium text-black dark:bg-gray-700 dark:text-white">
                                            {{ $grupoAsignado->nivel_academico }}
                                        </span>
                                    </td>
                                    <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark text-center">
                                        <form action="{{ route('usuarios.remover_grupo', ['id_usuario' => $user->id_users, 'id_grupo' => $grupoAsignado->id_grupo]) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('¿Está seguro de remover este grupo?')" class="text-danger hover:text-meta-1 font-medium transition-colors">
                                                Quitar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="border-b border-[#eee] py-8 px-4 dark:border-strokedark text-center">
                                        <p class="text-gray-500 font-medium">El profesor no tiene grupos asignados aún.</p>
                                        <p class="text-sm text-gray-400 mt-1">No podrá registrar asistencias hasta que le asigne al menos un grupo.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection