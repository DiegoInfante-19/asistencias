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

    <style>
        /* 1. Ocultar enlaces de accesibilidad fantasma de AdminLTE */
        body > a[href="#main"],
        body > a[href="#navigation"],
        .skip-link,
        .visually-hidden-focusable {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
            height: 0 !important;
            width: 0 !important;
            position: absolute !important;
        }

        /* 2. Degradado de fondo personalizado para reducir fatiga visual */
        body {
            background-image: linear-gradient(to top, #cfd9df 0%, #e2ebf0 100%);
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-size: cover;
        }

        /* 3. Bordes personalizados y leves para el Navbar */
        .navbar-custom-borders {
            border-top: 14px solid #03396c !important;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100 antialiased">

    <!-- BANNER INSTITUCIONAL -->
    <div class="w-100 p-0 m-0 overflow-hidden bg-white">
        <img src="{{ asset('images/panel.png') }}" alt="Banner Institucional" class="img-fluid w-100 d-block" style="object-fit: cover; object-position: center; max-height: 140px;">
    </div>

    <!-- NAVBAR MODERNO CON BOOTSTRAP 5 (Se agregó navbar-custom-borders y se quitó border-bottom genérico) -->
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm sticky-top navbar-custom-borders">
        <div class="container d-flex justify-content-end">
            
            <button class="navbar-toggler border-0 shadow-none ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Abrir menú principal">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Opciones del Navbar -->
            <div class="collapse navbar-collapse" id="navbarMain">
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
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-2" style="width: 32px; height: 32px;">
                                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                                </div>
                                <span class="fw-medium text-dark">{{ Auth::user()->name ?? 'Usuario' }}</span>
                            </a>

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
    <main class="flex-grow-1 d-flex flex-column">
        @yield('content')
    </main>

    <!-- FOOTER INSTITUCIONAL (Se comprimió el padding a py-3 y gy-3 para evitar scroll innecesario) -->
    <footer class="bg-white border-top py-3 mt-auto shadow-sm">
        <div class="container">
            <div class="row gy-3">
                <div class="col-12 col-md-5">
                    <a href="{{ url('/') }}" class="d-flex align-items-center text-decoration-none mb-2">
                        <img src="{{ asset('images/upt_logo-modified.png') }}" alt="Logo UPT" height="28" class="me-3">
                        <span class="fs-6 fw-bold text-dark">SisControl</span>
                    </a>
                    <p class="text-muted small mb-0 pe-md-5">
                        Sistema de Control de Asistencias y Acreditaciones de los Trabajadores para la Oficina de Vice Rectorado Académico.
                    </p>
                </div>

                <div class="col-6 col-md-3">
                    <h6 class="text-uppercase fw-bold mb-2 small text-dark" style="font-size: 0.75rem;">Navegación</h6>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-1"><a href="{{ url('/') }}" class="text-muted text-decoration-none">Inicio</a></li>
                        <li class="mb-1"><a href="{{ route('login') }}" class="text-muted text-decoration-none">Iniciar Sesión</a></li>
                    </ul>
                </div>

                <div class="col-6 col-md-2">
                    <h6 class="text-uppercase fw-bold mb-2 small text-dark" style="font-size: 0.75rem;">Enlaces</h6>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-1"><a href="#" class="text-muted text-decoration-none">Vicerrectorado</a></li>
                        <li class="mb-1"><a href="#" class="text-muted text-decoration-none">Soporte Técnico</a></li>
                    </ul>
                </div>

                <div class="col-12 col-md-2">
                    <h6 class="text-uppercase fw-bold mb-2 small text-dark" style="font-size: 0.75rem;">Legal</h6>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-1"><a href="#" class="text-muted text-decoration-none">Privacidad</a></li>
                        <li class="mb-1"><a href="#" class="text-muted text-decoration-none">Términos</a></li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-3 border-secondary opacity-25">
            
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center">
                <span class="text-muted text-center text-sm-start" style="font-size: 0.8rem;">
                    © {{ date('Y') }} <a href="#" class="text-decoration-none text-muted fw-bold">Oficina de Vice Rectorado Académico</a>. Todos los derechos reservados.
                </span>
            </div>
        </div>
    </footer>

    @include('partials.alerts', ['default' => ''])
    @yield('scripts')
</body>

</html>