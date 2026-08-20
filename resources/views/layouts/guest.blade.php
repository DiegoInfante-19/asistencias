<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/upt_logo.png') }}">

    <!-- Fonts (Rubik local vía app.css) -->
    <link rel="preconnect" href="https://fonts.bunny.net">

    <!-- Scripts y Estilos (Tailwind v4 + Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('scripts')
</head>

<body class="bg-gray-50 text-gray-800 antialiased dark:bg-gray-900 dark:text-gray-200 min-h-screen flex flex-col font-sans">

    <!-- NAVBAR MODERNO CON FLOWBITE -->
    <nav class="bg-white fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">

            <!-- Logo y Marca -->
            <a href="{{ url('/') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="{{ asset('images/upt_logo.png') }}" class="h-8 w-8 object-contain" alt="Logo UPT">
                <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white text-primary-600">
                    {{ config('app.name', 'Laravel') }}
                </span>
            </a>

            <!-- Botón del Menú Móvil (Hamburguesa) -->
            <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-default" aria-expanded="false">
                <span class="sr-only">Abrir menú principal</span>
                <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14" />
                </svg>
            </button>

            <!-- Opciones del Navbar -->
            <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-800 dark:border-gray-700 items-center">

                    @guest
                        @if (Route::has('login'))
                        <li>
                            <a href="{{ route('login') }}" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-primary-600 md:p-0 dark:text-white md:dark:hover:text-primary-500 dark:hover:bg-gray-700 dark:hover:text-white">
                                Inicio de Sesión
                            </a>
                        </li>
                        @endif

                        @if (Route::has('register'))
                        <li>
                            <a href="{{ route('register') }}" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-primary-600 md:p-0 dark:text-white md:dark:hover:text-primary-500 dark:hover:bg-gray-700 dark:hover:text-white">
                                Registro de Usuario
                            </a>
                        </li>
                        @endif
                    @else
                        <!-- Menú de Usuario Autenticado -->
                        <li class="relative">
                            <button id="dropdownUserButton" data-dropdown-toggle="dropdownUser" class="flex items-center text-sm font-medium text-gray-900 rounded-full hover:text-primary-600 dark:hover:text-primary-500 md:me-0 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-white" type="button">
                                <span class="sr-only">Abrir menú de usuario</span>
                                <div class="w-8 h-8 rounded-full bg-primary-600 text-white flex items-center justify-center font-bold mr-2">
                                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                                </div>
                                <span>{{ Auth::user()->name ?? 'Usuario' }}</span>
                                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </button>

                            <!-- Dropdown menu -->
                            <div id="dropdownUser" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
                                <div class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                    <div class="font-medium truncate">{{ Auth::user()->email ?? '' }}</div>
                                </div>
                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownUserButton">
                                    <li>
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-red-600 dark:text-red-400">
                                            Cerrar Sesión
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endguest

                </ul>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO PRINCIPAL (Con pt-24 para compensar el navbar fijo y centrado limpio) -->
    <main class="flex-grow pt-24 pb-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
        <div class="max-w-7xl mx-auto w-full">
            @yield('content')
        </div>
    </main>

    <!-- FOOTER INSTITUCIONAL -->
   <footer class="p-4 bg-white sm:p-6 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
        <div class="mx-auto max-w-screen-xl">
            <div class="md:flex md:justify-between">
                <div class="mb-6 md:mb-0">
                    <a href="{{ url('/') }}" class="flex items-center">
                        <img src="{{ asset('images/upt_logo.png') }}" class="mr-3 h-8 w-8 object-contain" alt="Logo UPT" />
                        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">SisControl</span>
                    </a>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 max-w-xs">
                        Sistema de Control de Asistencias y Acreditaciones de los Trabajadores.
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Navegación</h2>
                        <ul class="text-gray-600 dark:text-gray-400 text-sm">
                            <li class="mb-4">
                                <a href="{{ url('/') }}" class="hover:underline">Inicio</a>
                            </li>
                            <li>
                                <a href="{{ route('login') }}" class="hover:underline">Iniciar Sesión</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Enlaces</h2>
                        <ul class="text-gray-600 dark:text-gray-400 text-sm">
                            <li class="mb-4">
                                <a href="#" class="hover:underline">Vicerrectorado</a>
                            </li>
                            <li>
                                <a href="#" class="hover:underline">Soporte Técnico</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Legal</h2>
                        <ul class="text-gray-600 dark:text-gray-400 text-sm">
                            <li class="mb-4">
                                <a href="#" class="hover:underline">Privacidad</a>
                            </li>
                            <li>
                                <a href="#" class="hover:underline">Términos</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />
            <div class="sm:flex sm:items-center sm:justify-between">
                <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">
                    © {{ date('Y') }} <a href="#" class="hover:underline">Oficina de Vice Rectorado Académico</a>. Todos los derechos reservados.
                </span>
                <div class="flex mt-4 space-x-6 sm:justify-center sm:mt-0">
                    <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                        <span class="sr-only">Portal</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Alertas globales -->
    @include('partials.alerts', ['default' => ''])

</body>

</html>