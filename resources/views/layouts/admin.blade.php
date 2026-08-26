<!doctype html>
<html lang="en" data-bs-theme="light">

<head><!--begin::Head-->
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>AdminLTE v4 | Dashboard</title>

  <!-- Token CSRF OBLIGATORIO para peticiones AJAX de jQuery -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!--begin::Accessibility Meta Tags-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
  <meta name="color-scheme" content="light dark" />
  <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
  <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
  <!--end::Accessibility Meta Tags-->

  <!--begin::Primary Meta Tags-->
  <meta name="title" content="AdminLTE v4 | Dashboard" />
  <meta name="author" content="ColorlibHQ" />
  <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard..." />
  <!--end::Primary Meta Tags-->

  <link rel="icon" type="image/png" href="{{ asset('images/upt_logo-modified.png') }}">

  <!-- Preload de AdminLTE CSS -->
  <link rel="preload" href="{{ asset('dist/css/adminlte.css') }}" as="style" />


  <!-- Estilos principales de AdminLTE -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}" />


  @yield('styles')
  <link href="{{ asset('css/custom-datatables.css') }}?v={{ time() }}" rel="stylesheet">

  <!-- INYECCIÓN DE VITE (Maneja CSS/JS centralizado: jQuery, Bootstrap, OverlayScrollbars, Select2, DataTables) -->
  @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<!--end::Head-->

<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">
    <nav class="app-header navbar navbar-expand bg-body">
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
          <span class="brand-text fw-light">Assis.UPT</span>
        </a>
      </div>
      <div class="sidebar-wrapper">
        <nav class="mt-2">
          <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false" id="navigation">

            <li class="nav-item">
              <a href="{{ route('admin.index') }}" class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                <i class="bi bi-house-fill"></i>
                <p>Página de Inicio</p>
              </a>
            </li>

            @if(auth()->user()->isAdmin() || auth()->user()->isCoordinador())
            <li class="nav-item {{ request()->routeIs('cohortes.*', 'periodos_recesos.*') ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ request()->routeIs('cohortes.*', 'periodos_recesos.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-range"></i>
                <p>Cohortes<i class="nav-arrow bi bi-chevron-right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('cohortes.index') }}" class="nav-link {{ request()->routeIs('cohortes.*') ? 'active' : '' }}">
                    <i class="bi bi-hourglass-split ms-3"></i>
                    <p>Cohortes</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('periodos_recesos.index') }}" class="nav-link {{ request()->routeIs('periodos_recesos.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event ms-3"></i>
                    <p>Periodos de Recesos</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item {{ request()->routeIs('personas.*') ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ request()->routeIs('personas.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                <p>Estudiantes<i class="nav-arrow bi bi-chevron-right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('personas.index') }}" class="nav-link {{ request()->routeIs('personas.*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge-fill ms-3"></i>
                    <p>Directorio General</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item {{ request()->routeIs('pnfs.*', 'titulos.*') ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ request()->routeIs('pnfs.*', 'titulos.*') ? 'active' : '' }}">
                <i class="bi bi-mortarboard-fill"></i>
                <p>Titulaciones<i class="nav-arrow bi bi-chevron-right"></i></p>
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
              </ul>
            </li>

            <li class="nav-item {{ request()->routeIs('profesores.*') || request()->routeIs('sesiones.*') ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ request()->routeIs('profesores.*') || request()->routeIs('sesiones.*') ? 'active' : '' }}">
                <i class="bi bi-person-video3"></i>
                <p>Profesores<i class="nav-arrow bi bi-chevron-right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('profesores.index') }}" class="nav-link {{ request()->routeIs('profesores.*') ? 'active' : '' }}">
                    <i class="bi bi-person-lines-fill ms-3"></i>
                    <p>Listado</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('sesiones.index') }}" class="nav-link {{ request()->routeIs('sesiones.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-check ms-3"></i>
                    <p>Portal de Clases</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item {{ request()->routeIs('empresas.*', 'cargos.*') ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ request()->routeIs('empresas.*', 'cargos.*') ? 'active' : '' }}">
                <i class="bi bi-building-fill"></i>
                <p>Empresas<i class="nav-arrow bi bi-chevron-right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('empresas.index') }}" class="nav-link {{ request()->routeIs('empresas.*') ? 'active' : '' }}">
                    <i class="bi bi-journals ms-3"></i>
                    <p>Catálogo</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('cargos.index') }}" class="nav-link {{ request()->routeIs('cargos.*') ? 'active' : '' }}">
                    <i class="bi bi-person-vcard-fill ms-3"></i>
                    <p>Cargos</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item {{ request()->routeIs('localidades.*') ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ request()->routeIs('localidades.*') ? 'active' : '' }}">
                <i class="bi bi-map-fill"></i>
                <p>Localidades<i class="nav-arrow bi bi-chevron-right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('localidades.index') }}" class="nav-link {{ request()->routeIs('localidades.*') ? 'active' : '' }}">
                    <i class="bi bi-geo-alt-fill ms-3"></i>
                    <p>Estados y Ciudades</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item {{ request()->routeIs('estatus_expedientes.*') ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ request()->routeIs('estatus_expedientes.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-bar-graph-fill"></i>
                <p>Reportes<i class="nav-arrow bi bi-chevron-right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('estatus_expedientes.index') }}" class="nav-link {{ request()->routeIs('estatus_expedientes.*') ? 'active' : '' }}">
                    <i class="bi bi-folder2-open ms-3"></i>
                    <p>Estatus Expedientes</p>
                  </a>
                </li>
              </ul>
            </li>
            @else
            <li class="nav-item">
              <a href="{{ route('sesiones.index') }}" class="nav-link {{ request()->routeIs('sesiones.*') ? 'active' : '' }}">
                <i class="bi bi-journal-check"></i>
                <p>Mis Clases y Asistencias</p>
              </a>
            </li>
            @endif

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

            <li class="nav-item">
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
  <!-- Script centralizado de Vite (Maneja Bootstrap, AdminLTE, OverlayScrollbars y Plugins) -->
  @vite(['resources/sass/app.scss', 'resources/js/app.js'])

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
      const isMobile = window.innerWidth <= 992;

      // Inicialización segura de OverlayScrollbars si está disponible globalmente
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

  @if(isset($requireSecuritySetup) && $requireSecuritySetup)
  <div class="modal fade" id="securityQuestionsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="securityQuestionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header border-bottom bg-light">
          <h1 class="modal-title fs-5 fw-bold text-dark" id="securityQuestionsModalLabel">
            <i class="bi bi-shield-lock-fill text-warning me-2"></i> Configuración de Seguridad Obligatoria
          </h1>
        </div>
        <div class="modal-body p-4">
          <div class="alert alert-warning border-0 shadow-sm mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Estimado usuario, por políticas de seguridad del sistema, es obligatorio configurar sus preguntas de recuperación antes de continuar operando.
          </div>
          <form id="securityQuestionsForm" method="POST" action="{{ route('seguridad.preguntas.store') }}">
            @csrf
            @method('PUT')
            <div class="row g-4">
              <div class="col-md-12">
                <label class="form-label fw-bold small text-muted text-uppercase">Pregunta de Seguridad 1</label>
                <select class="form-select @error('pregunta1') is-invalid @enderror" name="pregunta1" required style="color: black;">
                  <option value="" disabled {{ !old('pregunta1') ? 'selected' : '' }}>Seleccione una pregunta...</option>
                  <option value="11" {{ old('pregunta1') == '11' ? 'selected' : '' }}>¿Cuál es tu cantante favorito?</option>
                  <option value="12" {{ old('pregunta1') == '12' ? 'selected' : '' }}>¿Cuál es tu libro favorito?</option>
                  <option value="13" {{ old('pregunta1') == '13' ? 'selected' : '' }}>¿Cuál sería tu trabajo ideal?</option>
                  <option value="14" {{ old('pregunta1') == '14' ? 'selected' : '' }}>¿Cuál es tu película favorita?</option>
                  <option value="15" {{ old('pregunta1') == '15' ? 'selected' : '' }}>¿Cuál es tu personaje favorito?</option>
                </select>
              </div>
              <div class="col-md-12">
                <label class="form-label fw-bold small text-muted text-uppercase">Respuesta 1</label>
                <input type="text" class="form-control @error('respuesta1') is-invalid @enderror" name="respuesta1" value="{{ old('respuesta1') }}" autocomplete="off" required oninput="this.value = this.value.toUpperCase();" {{ old('pregunta1') ? '' : 'disabled' }}>
              </div>
              <div class="col-md-12 mt-4">
                <label class="form-label fw-bold small text-muted text-uppercase">Pregunta de Seguridad 2</label>
                <select class="form-select @error('pregunta2') is-invalid @enderror" name="pregunta2" required style="color: black;">
                  <option value="" disabled {{ !old('pregunta2') ? 'selected' : '' }}>Seleccione una pregunta...</option>
                  <option value="21" {{ old('pregunta2') == '21' ? 'selected' : '' }}>¿Cómo se llama tu mejor amigo?</option>
                  <option value="22" {{ old('pregunta2') == '22' ? 'selected' : '' }}>¿Cómo se llama tu primera mascota?</option>
                  <option value="23" {{ old('pregunta2') == '23' ? 'selected' : '' }}>¿Cómo se llama tu equipo favorito?</option>
                  <option value="24" {{ old('pregunta2') == '24' ? 'selected' : '' }}>¿Cómo se llama la calle donde creciste?</option>
                  <option value="25" {{ old('pregunta2') == '25' ? 'selected' : '' }}>¿Cómo se llama tu plato favorito?</option>
                </select>
              </div>
              <div class="col-md-12">
                <label class="form-label fw-bold small text-muted text-uppercase">Respuesta 2</label>
                <input type="text" class="form-control @error('respuesta2') is-invalid @enderror" name="respuesta2" value="{{ old('respuesta2') }}" autocomplete="off" required oninput="this.value = this.value.toUpperCase();" {{ old('pregunta2') ? '' : 'disabled' }}>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer bg-light border-top">
          <button type="submit" form="securityQuestionsForm" class="btn btn-primary fw-bold shadow-sm w-100">
            <i class="bi bi-save me-1"></i> Guardar y Continuar al Sistema
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      document.addEventListener('change', function(event) {
        if (event.target && event.target.name === 'pregunta1') {
          const input1 = document.querySelector('input[name="respuesta1"]');
          if (event.target.value && event.target.value !== "") {
            input1.removeAttribute('disabled');
            input1.focus();
          } else {
            input1.setAttribute('disabled', 'disabled');
            input1.value = "";
          }
        }
        if (event.target && event.target.name === 'pregunta2') {
          const input2 = document.querySelector('input[name="respuesta2"]');
          if (event.target.value && event.target.value !== "") {
            input2.removeAttribute('disabled');
            input2.focus();
          } else {
            input2.setAttribute('disabled', 'disabled');
            input2.value = "";
          }
        }
      });

      setTimeout(function() {
        const securityModalElement = document.getElementById('securityQuestionsModal');
        if (securityModalElement) {
          if (typeof bootstrap !== 'undefined') {
            const securityModal = new bootstrap.Modal(securityModalElement, {
              backdrop: 'static',
              keyboard: false
            });
            securityModal.show();
          }
        }
      }, 12000);
    });
  </script>
  @endif

  <!-- CAMPOS DE COMPATIBILIDAD CRÍTICOS PARA SELECT2 Y SCRIPTS -->
  

  @yield('scripts')
  @stack('scripts')

  @include('partials.alerts')
</body>

</html>