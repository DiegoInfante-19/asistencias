@extends('layouts.app')

@section('content')
<!-- Encabezado de la página -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Mi Perfil
    </h2>
    <nav>
        <ol class="flex items-center gap-2 text-sm">
            <li>
                <a class="hover:text-primary" href="{{ url('/') }}">Inicio /</a>
            </li>
            <li class="text-primary font-medium">Perfil</li>
        </ol>
    </nav>
</div>

<!-- Contenedor Principal (Grid) -->
<div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

    <!-- COLUMNA IZQUIERDA: RESUMEN DE PERFIL -->
    <div class="col-span-1">
        <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="p-6 text-center">
                <!-- Avatar Circular -->
                <div class="mx-auto mb-4 flex h-24 w-24 items-center justify-center rounded-full bg-primary bg-opacity-20 text-4xl font-bold text-primary">
                    {{ substr($user->name_users, 0, 1) }}{{ substr($user->last_name_users, 0, 1) }}
                </div>

                <h3 class="mb-1 text-xl font-semibold text-black dark:text-white">
                    {{ $user->name_users }} {{ $user->last_name_users }}
                </h3>
                <p class="mb-3 text-sm font-medium text-gray-500 dark:text-gray-400">
                    <svg class="inline-block h-4 w-4 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12zm-1-5a1 1 0 002 0V7a1 1 0 00-2 0v4z"/></svg>
                    {{ $user->username }}
                </p>

                <!-- Rol -->
                <div class="mb-6">
                    <span class="inline-flex rounded-full bg-primary py-1 px-4 text-sm font-medium text-white">
                        {{ $user->rol->nombre_rol ?? 'Usuario' }}
                    </span>
                </div>

                <!-- Lista de Detalles -->
                <ul class="flex flex-col gap-4 text-left">
                    <li class="flex items-center justify-between border-b border-stroke pb-3 dark:border-strokedark">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Cédula</span>
                        <span class="font-semibold text-black dark:text-white">{{ $user->cedula_users }}</span>
                    </li>
                    <li class="flex items-center justify-between border-b border-stroke pb-3 dark:border-strokedark">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Correo</span>
                        <span class="font-semibold text-black dark:text-white">{{ $user->email_users }}</span>
                    </li>
                    <li class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Teléfono</span>
                        <span class="font-semibold text-black dark:text-white">{{ $user->phone_users ?? 'No registrado' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- COLUMNA DERECHA: CONFIGURACIÓN Y TABS -->
    <div class="col-span-1 lg:col-span-2">
        <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark" x-data="{ activeTab: 'settings' }">
            
            <!-- Navegación de Tabs (Alpine.js) -->
            <div class="flex flex-wrap items-center border-b border-stroke px-4 dark:border-strokedark sm:px-6">
                <button @click="activeTab = 'settings'" :class="activeTab === 'settings' ? 'text-primary border-primary' : 'text-gray-500 border-transparent hover:text-black dark:hover:text-white'" class="border-b-2 py-4 px-4 text-sm font-medium md:text-base">
                    Configuración
                </button>
                <button @click="activeTab = 'cohortes'" :class="activeTab === 'cohortes' ? 'text-primary border-primary' : 'text-gray-500 border-transparent hover:text-black dark:hover:text-white'" class="border-b-2 py-4 px-4 text-sm font-medium md:text-base">
                    Mis Cohortes
                </button>
                <button @click="activeTab = 'sesiones'" :class="activeTab === 'sesiones' ? 'text-primary border-primary' : 'text-gray-500 border-transparent hover:text-black dark:hover:text-white'" class="border-b-2 py-4 px-4 text-sm font-medium md:text-base">
                    Próximas Sesiones
                </button>
                <button @click="activeTab = 'empresas'" :class="activeTab === 'empresas' ? 'text-primary border-primary' : 'text-gray-500 border-transparent hover:text-black dark:hover:text-white'" class="border-b-2 py-4 px-4 text-sm font-medium md:text-base">
                    Empresas Vinculadas
                </button>
            </div>

            <!-- Contenido de las Tabs -->
            <div class="p-6">
                
                <!-- Tab Configuración -->
                <div x-show="activeTab === 'settings'" class="transition-opacity duration-300">
                    <h5 class="mb-6 text-xl font-bold text-black dark:text-white">Actualizar Datos Personales</h5>

                    <form id="profileUpdateForm" action="{{ route('perfil.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-bold text-black dark:text-white">Nombre de Usuario</label>
                                <input type="text" name="username" value="{{ $user->username }}" required
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                                <div class="dynamic-feedback mt-1 text-sm font-bold text-danger" style="display:none;"></div>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-bold text-black dark:text-white">Correo Electrónico</label>
                                <input type="email" name="email_users" value="{{ $user->email_users }}" required
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                                <div class="dynamic-feedback mt-1 text-sm font-bold text-danger" style="display:none;"></div>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-bold text-black dark:text-white">Nombres</label>
                                <input type="text" name="name_users" value="{{ $user->name_users }}" required
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                                <div class="dynamic-feedback mt-1 text-sm font-bold text-danger" style="display:none;"></div>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-bold text-black dark:text-white">Apellidos</label>
                                <input type="text" name="last_name_users" value="{{ $user->last_name_users }}" required
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                                <div class="dynamic-feedback mt-1 text-sm font-bold text-danger" style="display:none;"></div>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-bold text-black dark:text-white">Cédula</label>
                                <input type="text" name="cedula_users" value="{{ $user->cedula_users }}" required
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                                <div class="dynamic-feedback mt-1 text-sm font-bold text-danger" style="display:none;"></div>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-bold text-black dark:text-white">Teléfono</label>
                                <input type="text" name="phone_users" value="{{ $user->phone_users }}"
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                                <div class="dynamic-feedback mt-1 text-sm font-bold text-danger" style="display:none;"></div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="mt-8 flex flex-col sm:flex-row gap-4">
                            <button type="submit" class="flex items-center justify-center rounded bg-primary py-3 px-6 font-medium text-white hover:bg-opacity-90">
                                Guardar Cambios
                            </button>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#securityModal" class="flex items-center justify-center rounded border border-primary text-primary py-3 px-6 font-medium hover:bg-primary hover:text-white transition-colors">
                                Seguridad y Credenciales
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tabs en Construcción -->
                <div x-show="activeTab === 'cohortes'" style="display: none;">
                    <p class="text-gray-500">Módulo de Cohortes en construcción...</p>
                </div>
                <div x-show="activeTab === 'sesiones'" style="display: none;">
                    <p class="text-gray-500">Módulo de Sesiones en construcción...</p>
                </div>
                <div x-show="activeTab === 'empresas'" style="display: none;">
                    <p class="text-gray-500">Módulo de Empresas en construcción...</p>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/core-validations.js') }}" defer></script>
<script src="{{ asset('js/admin-validations.js') }}" defer></script>
@include('perfil.partials.security-modal')
@endsection