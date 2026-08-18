@extends('layouts.app')

@section('content')
<section class="bg-gray-50 dark:bg-gray-900 min-h-[85vh] flex items-center justify-center">
    <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 w-full">
        
        <!-- Contenedor Principal de 2 Columnas -->
        <div class="grid lg:grid-cols-12 gap-8 lg:gap-16 items-center">
            
            <!-- COLUMNA IZQUIERDA: Texto / Branding Institucional -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <a href="#" class="inline-flex items-center space-x-3 rtl:space-x-reverse">
                    <img src="{{ asset('images/upt_logo-modified.png') }}" class="h-10" alt="Logo UPT">
                    <span class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Sistema de Asistencias
                    </span>
                </a>
                
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 md:text-4xl dark:text-white leading-snug">
                    Control y Gestión Académica <span class="text-primary-600 dark:text-primary-500">Eficiente</span>
                </h1>
                
                <p class="text-lg font-normal text-gray-500 dark:text-gray-400 max-w-2xl mx-auto lg:mx-0">
                    Plataforma oficial para la supervisión de asistencia de trabajadores, control de períodos académicos y seguimiento administrativo del Vicerrectorado Académico.
                </p>

                <!-- Puntos destacados -->
                <div class="space-y-4 pt-4 text-left max-w-md mx-auto lg:mx-0">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 text-primary-600 dark:text-primary-500 font-bold">✓</div>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Registro automatizado y reportes detallados en formato PDF instantáneos.</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 text-primary-600 dark:text-primary-500 font-bold">✓</div>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Gestión de secciones, PNF y profesores de forma centralizada.</p>
                    </div>
                </div>
            </div>

            <!-- COLUMNA DERECHA: Tu Card de Login -->
            <div class="lg:col-span-5 w-full flex justify-center">
                <div class="w-full bg-white rounded-lg shadow-xl dark:border sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                    <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                        <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                            {{ __('Inicio de Sesión') }}
                        </h1>

                        <form class="space-y-4 md:space-y-6" method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Campo Login (Cédula o Correo) -->
                            <div>
                                <label for="login" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Cédula o Correo Electrónico
                                </label>
                                <input id="login" type="text" name="login" value="{{ old('login') }}" required autocomplete="off" placeholder="Este campo es obligatorio"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('login') border-red-500 is-invalid @enderror">

                                <!-- Error del Backend -->
                                @error('login')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500 font-bold">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <!-- Campo Password -->
                            <div>
                                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Contraseña
                                </label>
                                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Este campo es obligatorio"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('password') border-red-500 is-invalid @enderror">

                                <!-- Error del Backend -->
                                @error('password')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500 font-bold">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-between">
                                @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500">
                                    ¿Olvidaste tu contraseña?
                                </a>
                                @endif
                            </div>

                            <button type="submit" class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 disabled:opacity-50 disabled:cursor-not-allowed">
                                <b>Acceder al Sistema</b>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('js/auth-validations-login.js') }}" defer></script>
@endsection