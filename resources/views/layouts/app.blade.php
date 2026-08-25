<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SisControl') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/upt_logo-modified.png') }}">

    <!-- Carga de Estilos y Scripts (Bootstrap + AdminLTE vía Vite) -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @yield('styles')
</head>

<body class="d-flex flex-column min-vh-100 bg-body-tertiary antialiased">

    <!-- NAVBAR MODERNO CON BOOTSTRAP 5 -->
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm sticky-top border-bottom">
        <div class="container">
            <!-- Logo y Marca -->
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('images/upt_logo-modified.png') }}" alt="Logo UPT" height="32" class="me-2">
                <span class="fw-bold text-primary">{{ config('app.name', 'SisControl') }}</span>
            </a>

            <!-- Botón del Menú Móvil (Hamburguesa) -->
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Abrir menú principal">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Opciones del Navbar -->
            <div class="collapse navbar-collapse" id="navbarMain">
                <!-- Espacio a la izquierda -->
                <ul class="navbar-nav me-auto">
                </ul>

                <!-- Opciones a la derecha -->
                <ul class="navbar-nav ms-auto align-items-center">
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link fw-medium" href="{{ route('login') }}">Inicio de Sesión</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link fw-medium" href="{{ route('register') }}">Registro de Usuario</a>
                            </li>
                        @endif
                    @else
                        <!-- Menú de Usuario Autenticado -->
                        <li class="nav-item dropdown">
                            <a id="navbarUserDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <!-- Avatar dinámico circular -->
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-2" style="width: 32px; height: 32px;">
                                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                                </div>
                                <span class="fw-medium text-dark">{{ Auth::user()->name ?? 'Usuario' }}</span>
                            </a>

                            <!-- Dropdown menu -->
                            <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="navbarUserDropdown">
                                <div class="dropdown-item-text text-muted small">
                                    {{ Auth::user()->email ?? '' }}
                                </div>
                                <hr class="dropdown-divider">
                                <a class="dropdown-item text-danger fw-bold" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO PRINCIPAL -->
    <!-- flex-grow-1 asegura que el main ocupe el espacio disponible empujando el footer abajo -->
    <main class="flex-grow-1 d-flex flex-column">
        @yield('content')
    </main>

    <!-- FOOTER INSTITUCIONAL -->
    <footer class="bg-white border-top py-5 mt-auto">
        <div class="container">
            <div class="row gy-4">
                <!-- Columna 1: Branding -->
                <div class="col-12 col-md-5">
                    <a href="{{ url('/') }}" class="d-flex align-items-center text-decoration-none mb-3">
                        <img src="{{ asset('images/upt_logo-modified.png') }}" alt="Logo UPT" height="32" class="me-3">
                        <span class="fs-5 fw-bold text-dark">SisControl</span>
                    </a>
                    <p class="text-muted small mb-0 pe-md-5">
                        Sistema de Control de Asistencias y Acreditaciones de los Trabajadores para la Oficina de Vice Rectorado Académico.
                    </p>
                </div>

                <!-- Columna 2: Navegación -->
                <div class="col-6 col-md-3">
                    <h6 class="text-uppercase fw-bold mb-3 small text-dark">Navegación</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ url('/') }}" class="text-muted text-decoration-none text-primary-hover">Inicio</a></li>
                        <li class="mb-2"><a href="{{ route('login') }}" class="text-muted text-decoration-none text-primary-hover">Iniciar Sesión</a></li>
                    </ul>
                </div>

                <!-- Columna 3: Enlaces -->
                <div class="col-6 col-md-2">
                    <h6 class="text-uppercase fw-bold mb-3 small text-dark">Enlaces</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none text-primary-hover">Vicerrectorado</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none text-primary-hover">Soporte Técnico</a></li>
                    </ul>
                </div>

                <!-- Columna 4: Legal -->
                <div class="col-12 col-md-2">
                    <h6 class="text-uppercase fw-bold mb-3 small text-dark">Legal</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none text-primary-hover">Privacidad</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none text-primary-hover">Términos</a></li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4 border-secondary opacity-25">
            
            <!-- Derechos de autor -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center">
                <span class="text-muted small text-center text-sm-start">
                    © {{ date('Y') }} <a href="#" class="text-decoration-none text-muted fw-bold">Oficina de Vice Rectorado Académico</a>. Todos los derechos reservados.
                </span>
            </div>
        </div>
    </footer>

    <!-- Alertas globales -->
    @include('partials.alerts', ['default' => ''])

    @yield('scripts')
</body>

</html>