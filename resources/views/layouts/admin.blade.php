<!doctype html>
<html lang="en">

<head><!--begin::Head-->
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>AdminLTE v4 | Dashboard</title>
  <!--begin::Accessibility Meta Tags-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
  <meta name="color-scheme" content="light dark" />
  <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
  <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
  <!--end::Accessibility Meta Tags-->
  <!--begin::Primary Meta Tags-->
  <meta name="title" content="AdminLTE v4 | Dashboard" />
  <meta name="author" content="ColorlibHQ" />
  <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance." />
  <meta name="keywords" content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant" />
  <!--end::Primary Meta Tags-->
  <!--begin::Accessibility Features-->
  <!-- Skip links will be dynamically added by accessibility.js -->
  <meta name="supported-color-schemes" content="light dark" />

  <link rel="icon" type="image/png" href="{{ asset('images/upt_logo-modified.png') }}">

  <!-- CAMBIO AQUÍ: Preload de AdminLTE CSS -->
  <link rel="preload" href="{{ asset('dist/css/adminlte.css') }}" as="style" />
  <!--end::Accessibility Features-->

  <!--begin::Fonts-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print" onload="this.media = 'all'" />
  <!--end::Fonts-->
  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" /> <!--end::Third Party Plugin(OverlayScrollbars)-->
  <!--begin::Third Party Plugin(Bootstrap Icons)-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
  <!--end::Third Party Plugin(Bootstrap Icons)-->

  <!-- CAMBIO AQUÍ: Estilos principales de AdminLTE -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}" />
  <!--end::Required Plugin(AdminLTE)-->

  <!-- apexcharts -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />
  <!-- jsvectormap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous" />

  <!--DataTables-->
  <!-- <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.3.8/datatables.min.css" rel="stylesheet" integrity="sha384-nD9P196GmYuiIASpxI7+7/0LqD6BBA74CfgIOSQUo7brmKKeph8lSEMm2sGgSAvK" crossorigin="anonymous"> -->

  @yield('styles')
</head><!--end::Head-->

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
      <!--begin::Sidebar Brand-->
      <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="./index.html" class="brand-link">
          <!--begin::Brand Image-->
          <img
            src="{{ asset('images/upt_logo-modified.png') }}"
            alt="AdminLTE Logo"
            class="brand-image opacity-75 shadow" />
          <!--end::Brand Image-->
          <!--begin::Brand Text-->
          <span class="brand-text fw-light">Assis.UPT</span>
          <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
      </div>
      <!--end::Sidebar Brand-->
      <!--begin::Sidebar Wrapper-->
      <div class="sidebar-wrapper">
        <nav class="mt-2">
          <!--begin::Sidebar Menu-->
          <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false" id="navigation">

            <li class="nav-item">
              <a href="{{ route('admin.index') }}" class="nav-link">
                <i class="bi bi-house-fill"></i>
                <p>Pagina de Inicio</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="bi bi-calendar-range"></i>
                <p>Cohortes<i class="nav-arrow bi bi-chevron-right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('cohortes.index') }}" class="nav-link {{ request()->routeIs('cohortes.index') ? '' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <i class="bi bi-hourglass-split"></i>
                    <p>Cohortes</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="bi bi-people-fill"></i>
                <p>Estudiantes<i class="nav-arrow bi bi-chevron-right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="./widgets/small-box.html" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Opciones</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="bi bi-mortarboard-fill"></i>
                <p>Titulaciones<i class="nav-arrow bi bi-chevron-right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('pnfs.index') }}" class="nav-link {{ request()->routeIs('pnfs.*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <i class="bi bi-journal-bookmark-fill"></i>
                    <p>PNFs</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('titulos.index') }}" class="nav-link {{ request()->routeIs('titulos.*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <i class="bi bi-mortarboard-fill"></i>
                    <p>Títulos</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="bi bi-person-video3"></i>
                <p>Profesores<i class="nav-arrow bi bi-chevron-right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('profesores.index') }}" class="nav-link">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="bi bi-person-lines-fill"></i>
                    <p>Listado</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link {{ request()->routeIs('empresas.*') ? '' : '' }}">
                <i class="bi bi-building-fill"></i>
                <p>
                  Empresas
                  <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('empresas.index') }}" class="nav-link {{ request()->routeIs('empresas.index') ? '' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <i class="bi bi-journals"></i>
                    <p>Catálogo</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('cargos.index') }}" class="nav-link {{ request()->routeIs('cargos.index') ? '' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <i class="bi bi-person-vcard-fill"></i>
                    <p>Cargos</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="bi bi-map-fill"></i>
                <p>
                  Localidades
                  <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('localidades.index') }}" class="nav-link {{ request()->routeIs('localidades.index') ? '' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="bi bi-geo-alt-fill"></i>
                    <p>Estados y Ciudades</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="bi bi-file-earmark-bar-graph-fill"></i>
                <p>
                  Reportes
                  <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="./widgets/small-box.html" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Opciones</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="bi bi-gear-fill"></i>
                <p>
                  Opciones
                  <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('perfil.index') }}" class="nav-link">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="bi bi-person-circle"></i>
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
          <!--end::Sidebar Menu-->
        </nav>
      </div>
      <!--end::Sidebar Wrapper-->
    </aside>
    <!--end::Sidebar-->

    <!--begin::App Main-->
    <main class="app-main">
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-8">
              <h3 class="mb-0">ViceRectorado Académico </h3>
            </div>
            <div class="col-sm-4">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
              </ol>
            </div>
          </div>
        </div>
      </div>


      <div class="app-content">
        <div class="container-fluid">
          @yield('content')
        </div>
      </div>
    </main>

    <footer class="app-footer">
      <div class="float-end d-none d-sm-inline">Anything you want</div>
      <strong>
        Copyright &copy; 2014-2026&nbsp;
        <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
      </strong>
      All rights reserved.
    </footer>
  </div>
  <!-- 1. OVERLAY SCROLLBARS:
  Este script sirve para reemplazar las barras de desplazamiento por defecto del navegador 
  por unas personalizadas, más estéticas y estilizadas que se adaptan al diseño de AdminLTE.
-->
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script> <!--end::Third Party Plugin(OverlayScrollbars)-->
  <!-- 2. POPPER.JS:
  Es una librería requerida obligatoriamente por Bootstrap 5. Sirve para calcular de forma 
  dinámica la posición de elementos flotantes como los tooltips (bocadillos de texto), 
  popovers, y algunos menús desplegables.
-->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script> <!--end::Required Plugin(popperjs for Bootstrap 5)-->
  <!-- 3. BOOTSTRAP 5:
  El framework sobre el que está construido AdminLTE. Este archivo JavaScript maneja toda la 
  interactividad nativa de Bootstrap: abrir/cerrar modales, alertas que se cierran, pestañas (tabs), 
  y el funcionamiento de los dropdowns junto con Popper.js.
-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script> <!--end::Required Plugin(Bootstrap 5)-->
  <!-- 4. ADMINLTE (ADAPTADO PARA LARAVEL):
  Este es el núcleo de la plantilla. Controla los comportamientos específicos de AdminLTE v4, 
  como colapsar o expandir el menú lateral (sidebar), activar el modo oscuro/claro de forma dinámica, 
  y los efectos visuales propios del panel de administración.
-->
  <script src="{{ asset('dist/js/adminlte.js') }}"></script>
  <!--end::Required Plugin(AdminLTE)-->
  <!-- 5. CONFIGURACIÓN DE OVERLAY SCROLLBARS:
  Este bloque de código personalizado sirve para inicializar y configurar las barras de 
  desplazamiento en el menú lateral (sidebar).
-->


  <script>
    // Indica el contenedor del menú lateral al cual se le aplicará la barra personalizada
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';

    // Configuración por defecto: tema claro, ocultar barra al salir el cursor, permitir scroll con click
    const Default = {
      scrollbarTheme: 'os-theme-light',
      scrollbarAutoHide: 'leave',
      scrollbarClickScroll: true,
    };

    // Espera a que todo el HTML de la página esté cargado para ejecutar la configuración
    document.addEventListener('DOMContentLoaded', function() {
      const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);

      // Detecta si la pantalla es menor o igual a 992px (Dispositivos móviles o tablets)
      const isMobile = window.innerWidth <= 992;

      // Si el menú existe en la página, la librería está cargada y NO es un móvil, activa la barra
      if (
        sidebarWrapper &&
        OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
        !isMobile
      ) {
        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
          scrollbars: {
            theme: Default.scrollbarTheme,
            autoHide: Default.scrollbarAutoHide,
            clickScroll: Default.scrollbarClickScroll,
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
                <div class="invalid-feedback dynamic-feedback fw-bold">
                  @error('pregunta1') {{ $message }} @enderror
                </div>
              </div>

              <div class="col-md-12">
                <label class="form-label fw-bold small text-muted text-uppercase">Respuesta 1</label>
                <input type="text" class="form-control @error('respuesta1') is-invalid @enderror" name="respuesta1" value="{{ old('respuesta1') }}" autocomplete="off" required oninput="this.value = this.value.toUpperCase();" {{ old('pregunta1') ? '' : 'disabled' }}>
                <div class="invalid-feedback dynamic-feedback fw-bold">
                  @error('respuesta1') {{ $message }} @enderror
                </div>
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
                <div class="invalid-feedback dynamic-feedback fw-bold">
                  @error('pregunta2') {{ $message }} @enderror
                </div>
              </div>

              <div class="col-md-12">
                <label class="form-label fw-bold small text-muted text-uppercase">Respuesta 2</label>
                <input type="text" class="form-control @error('respuesta2') is-invalid @enderror" name="respuesta2" value="{{ old('respuesta2') }}" autocomplete="off" required oninput="this.value = this.value.toUpperCase();" {{ old('pregunta2') ? '' : 'disabled' }}>
                <div class="invalid-feedback dynamic-feedback fw-bold">
                  @error('respuesta2') {{ $message }} @enderror
                </div>
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

      // 1. Lógica de Desbloqueo mediante Delegación de Eventos (Infalible y sin jQuery)
      document.addEventListener('change', function(event) {

        // Si el cambio ocurrió en la Pregunta 1
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

        // Si el cambio ocurrió en la Pregunta 2
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

      // 2. Temporizador exacto de 12 segundos para mostrar el modal
      setTimeout(function() {
        const securityModalElement = document.getElementById('securityQuestionsModal');

        if (securityModalElement) {
          // Aseguramos de que Bootstrap esté cargado antes de inicializar el modal
          if (typeof bootstrap !== 'undefined') {
            const securityModal = new bootstrap.Modal(securityModalElement, {
              backdrop: 'static',
              keyboard: false
            });
            securityModal.show();
          } else {
            console.error("Bootstrap no está cargado aún, el modal no puede abrirse.");
          }
        }
      }, 12000);

    });
  </script>
  @endif
  @yield('scripts')
  @include('partials.alerts')

</body>
<!--end::Body-->

</html>