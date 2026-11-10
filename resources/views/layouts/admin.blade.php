<!doctype html>
<html lang="en" data-bs-theme="light">

<head><!--begin::Head-->
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>SATAE</title>

  <!-- Token CSRF OBLIGATORIO para peticiones AJAX de jQuery -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!--begin::Accessibility Meta Tags-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
  <meta name="color-scheme" content="light dark" />
  <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
  <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
  <!--end::Accessibility Meta Tags-->

  <!--begin::Primary Meta Tags-->
  <meta name="title" content="SATAE" />
  <meta name="author" content="ColorlibHQ" />
  <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard..." />
  <!--end::Primary Meta Tags-->

  <link rel="icon" type="image/png" href="{{ asset('images/upt_logo-modified.png') }}">

  <!-- 2. Custom CSS -->
  <link href="{{ asset('css/custom-datatables.css') }}?v={{ time() }}" rel="stylesheet">
  @yield('styles')

  <!-- 3. Cargar Vite -->
  @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<!--end::Head-->

<!--begin::Body-->
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">
    <nav class="app-header navbar navbar-expand bg-white border-bottom">
      <div class="container-fluid">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
              <i class="bi bi-list"></i>
            </a>
          </li>
        </ul>
      </div>
    </nav>
    <!--end::Header-->

    <!--begin::Sidebar-->
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
      <div class="sidebar-brand">
        <a href="{{ route('admin.index') }}" class="brand-link">
          <img src="{{ asset('images/upt_logo-modified.png') }}" alt="Logo" class="brand-image opacity-75 shadow" />
          <span class="brand-text fw-light">SATAE</span>
        </a>
      </div>

      <div class="sidebar-wrapper">
        <nav class="mt-2">
          <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false" id="navigation">

            <!-- 1. Página de Inicio -->
            <li class="nav-item">
              <a href="{{ route('admin.index') }}" class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                <i class="bi bi-house-fill"></i>
                <p>Página de Inicio</p>
              </a>
            </li>

            @if(auth()->user()->isAdmin() || auth()->user()->isCoordinador())
            <!-- 2. Estructura Académica -->
            <li class="nav-item">
              <a href="{{ route('estructura.index') }}" class="nav-link {{ request()->routeIs('estructura.*') ? 'active' : '' }}">
                <i class="bi bi-diagram-3-fill"></i>
                <p>Estructura Académica</p>
              </a>
            </li>

            <!-- 3. Docencia y Clases -->
            <li class="nav-item {{ request()->routeIs('personas.*', 'profesores.*', 'clases.secciones.*', 'sesiones.*') ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ request()->routeIs('personas.*', 'profesores.*', 'clases.secciones.*', 'sesiones.*') ? 'active' : '' }}">
                <i class="bi bi-person-video3"></i>
                <p>Docencia y Clases<i class="nav-arrow bi bi-chevron-right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('personas.index') }}" class="nav-link {{ request()->routeIs('personas.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill ms-3"></i>
                    <p>Estudiantes</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('profesores.index') }}" class="nav-link {{ request()->routeIs('profesores.*') ? 'active' : '' }}">
                    <i class="bi bi-person-lines-fill ms-3"></i>
                    <p>Profesores</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('clases.secciones.index') }}" class="nav-link {{ request()->routeIs('clases.secciones.*', 'sesiones.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-check ms-3"></i>
                    <p>Portal de Clases</p>
                  </a>
                </li>
              </ul>
            </li>

           

            <!-- 4. Catálogos -->
            <li class="nav-item {{ request()->routeIs('pnfs.*', 'titulos.*', 'empresas.*', 'cargos.*', 'periodos_recesos.*', 'localidades.*', 'estatus_expedientes.*') ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ request()->routeIs('pnfs.*', 'titulos.*', 'empresas.*', 'cargos.*', 'periodos_recesos.*', 'localidades.*', 'estatus_expedientes.*') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i>
                <p>Catálogos<i class="nav-arrow bi bi-chevron-right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('pnfs.index') }}" class="nav-link {{ request()->routeIs('pnfs.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-bookmark-fill ms-3"></i>
                    <p>PNFs</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('titulos.index') }}" class="nav-link {{ request()->routeIs('titulos.*') ? 'active' : '' }}">
                    <i class="bi bi-mortarboard-fill ms-3"></i>
                    <p>Títulos</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('empresas.index') }}" class="nav-link {{ request()->routeIs('empresas.*') ? 'active' : '' }}">
                    <i class="bi bi-building-fill ms-3"></i>
                    <p>Empresas</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('cargos.index') }}" class="nav-link {{ request()->routeIs('cargos.*') ? 'active' : '' }}">
                    <i class="bi bi-person-vcard-fill ms-3"></i>
                    <p>Cargos</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('periodos_recesos.index') }}" class="nav-link {{ request()->routeIs('periodos_recesos.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event ms-3"></i>
                    <p>Periodos de Recesos</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('localidades.index') }}" class="nav-link {{ request()->routeIs('localidades.*') ? 'active' : '' }}">
                    <i class="bi bi-geo-alt-fill ms-3"></i>
                    <p>Estados y Ciudades</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('estatus_expedientes.index') }}" class="nav-link {{ request()->routeIs('estatus_expedientes.*') ? 'active' : '' }}">
                    <i class="bi bi-folder2-open ms-3"></i>
                    <p>Estatus de Expediente</p>
                  </a>
                </li>
              </ul>
            </li>

            @else
            <li class="nav-item">
              <a href="{{ route('clases.secciones.index') }}" class="nav-link {{ request()->routeIs('clases.secciones.*', 'sesiones.*') ? 'active' : '' }}">
                <i class="bi bi-journal-check"></i>
                <p>Mis Clases y Asistencias</p>
              </a>
            </li>
            @endif

            <!-- 5. Opciones -->
            <li class="nav-item {{ request()->routeIs('perfil.*') ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ request()->routeIs('perfil.*') ? 'active' : '' }}">
                <i class="bi bi-gear-fill"></i>
                <p>Opciones<i class="nav-arrow bi bi-chevron-right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('perfil.index') }}" class="nav-link {{ request()->routeIs('perfil.*') ? 'active' : '' }}">
                    <i class="bi bi-person-circle ms-3"></i>
                    <p>Ver Perfil</p>
                  </a>
                </li>
              </ul>
            </li>

            <!-- 6. Cerrar Sesión -->
            <li class="nav-item mt-2">
              <a href="#" class="nav-link" id="logout-link">
                <i class="bi bi-door-open-fill"></i>
                <p>Cerrar Sesión</p>
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
            </li>

          </ul>
        </nav>
      </div>
    </aside>
    <!--end::Sidebar-->

    <main class="app-main">
      @hasSection('header')
      @yield('header')
      @else
      <x-page-header title="ViceRectorado Académico" />
      @endif

      <div class="app-content">
        <div class="container-fluid">
          @yield('content')
        </div>
      </div>
    </main>

    <footer class="app-footer">
      <div class="float-end d-none d-sm-inline">Anything you want</div>
      <strong>Copyright &copy; 2014-2026&nbsp;<a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.</strong> All rights reserved.
    </footer>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
      const isMobile = window.innerWidth <= 992;

      if (sidebarWrapper && window.OverlayScrollbars && !isMobile) {
        window.OverlayScrollbars(sidebarWrapper, {
          scrollbars: {
            theme: 'os-theme-light',
            autoHide: 'leave',
            clickScroll: true,
          },
        });
      }
    });
  </script>

  @yield('scripts')
  @stack('scripts')

  @include('partials.alerts')
</body>

</html>