@extends('layouts.admin')

@section('content')
<div class="content" style="margin: 20px;">
    <div class="container-fluid">
        <div class="row g-3">
            
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center mb-3" style="width: 96px; height: 96px; font-size: 2.5rem; font-weight: bold;" aria-hidden="true">
                            JP
                        </div>
                        <h3 class="h5 mb-0">Juan Pérez</h3>
                        <p class="text-secondary mb-3">
                            <span class="badge text-bg-primary">Profesor</span>
                        </p>
                        
                        <ul class="list-group list-group-flush text-start small">
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-secondary"><i class="bi bi-person-vcard me-1"></i> Cédula</span>
                                <span class="fw-semibold">V-15.345.678</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-secondary"><i class="bi bi-envelope me-1"></i> Correo</span>
                                <span class="fw-semibold">j.perez@upt.edu.ve</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-secondary"><i class="bi bi-telephone me-1"></i> Teléfono</span>
                                <span class="fw-semibold">0414-1234567</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card mt-3 shadow-sm">
                    <div class="card-header bg-light">
                        <h3 class="card-title"><i class="bi bi-diagram-3 me-1 text-primary"></i> PNF Asignado</h3>
                    </div>
                    <div class="card-body small text-center">
                        <p class="fw-bold mb-1 text-dark fs-6">
                            PNF en Informática
                        </p>
                        <p class="text-secondary mb-0">
                            Fecha de Asignación:<br>
                            <span class="fw-semibold">15/01/2024</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="profile-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="cohortes-tab" data-bs-toggle="tab" data-bs-target="#cohortes" type="button" role="tab" aria-selected="true">
                                    <i class="bi bi-people me-1"></i> Mis Cohortes
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="sesiones-tab" data-bs-toggle="tab" data-bs-target="#sesiones" type="button" role="tab" aria-selected="false" tabindex="-1">
                                    <i class="bi bi-calendar-event me-1"></i> Próximas Sesiones
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="empresas-tab" data-bs-toggle="tab" data-bs-target="#empresas" type="button" role="tab" aria-selected="false" tabindex="-1">
                                    <i class="bi bi-building me-1"></i> Empresas Vinculadas
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab" aria-selected="false" tabindex="-1">
                                    <i class="bi bi-shield-lock me-1"></i> Configuración
                                </button>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="card-body">
                        <div class="tab-content">
                            
                            <div class="tab-pane fade show active" id="cohortes" role="tabpanel" aria-labelledby="cohortes-tab">
                                <h5 class="mb-3 text-primary">Historial de Cohortes - PNF en Informática</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover align-middle small">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Nº Cohorte</th>
                                                <th>Periodo Académico</th>
                                                <th>Fecha Inicio</th>
                                                <th>Fecha Fin</th>
                                                <th>Estatus</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="table-primary">
                                                <td class="fw-bold">#3</td>
                                                <td>Cohorte 3 (2025 - 2026)</td>
                                                <td>25/09/2025</td>
                                                <td>17/06/2026</td>
                                                <td>
                                                    <span class="badge text-bg-success">Activo (Por culminar)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">#2</td>
                                                <td>Cohorte 2 (2024 - 2025)</td>
                                                <td>20/09/2024</td>
                                                <td>10/07/2025</td>
                                                <td>
                                                    <span class="badge text-bg-secondary">Finalizada</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">#1</td>
                                                <td>Cohorte 1 (2023 - 2024)</td>
                                                <td>15/09/2023</td>
                                                <td>15/07/2024</td>
                                                <td>
                                                    <span class="badge text-bg-secondary">Finalizada</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="sesiones" role="tabpanel" aria-labelledby="sesiones-tab">
                                <h5 class="mb-3 text-primary">Agenda de Sesiones de Clases</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle small">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Fecha de la Sesión</th>
                                                <th>Cohorte Asignado</th>
                                                <th>Observaciones</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="fw-semibold">
                                                    <i class="bi bi-calendar-day me-1 text-secondary"></i> 
                                                    15/06/2026
                                                </td>
                                                <td>Cohorte #3</td>
                                                <td class="text-muted">Revisión de portafolios de saberes</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-outline-primary"><i class="bi bi-list-check me-1"></i> Control de Asistencia</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">
                                                    <i class="bi bi-calendar-day me-1 text-secondary"></i> 
                                                    22/06/2026
                                                </td>
                                                <td>Cohorte #3</td>
                                                <td class="text-muted">Defensa oral de proyectos finales</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-outline-primary"><i class="bi bi-list-check me-1"></i> Control de Asistencia</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="empresas" role="tabpanel" aria-labelledby="empresas-tab">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="text-primary mb-0">Estudiantes vinculados a la CVG</h5>
                                    <span class="badge text-bg-info">Total: 20 Estudiantes</span>
                                </div>
                                
                                <div class="row g-3">
                                    <div class="col-md-6 col-lg-4">
                                        <div class="info-box bg-light border shadow-sm h-100 mb-0">
                                            <span class="info-box-icon bg-white shadow-sm p-1">
                                                <img src="data:image/png;base64,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" alt="Logo Venalum" style="width: 100%; height: 100%; object-fit: contain;">
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text text-wrap fw-bold">CVG Venalum</span>
                                                <span class="info-box-number text-secondary">
                                                    <i class="bi bi-person-workspace me-1"></i> 8 Estudiantes
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-4">
                                        <div class="info-box bg-light border shadow-sm h-100 mb-0">
                                            <span class="info-box-icon bg-white shadow-sm p-1">
                                                <img src="https://d1yjjnpx0p53s8.cloudfront.net/styles/logo-thumbnail/s3/0014/5628/brand.gif?itok=z2ZQMaqT" alt="Logo Sidor" style="width: 100%; height: 100%; object-fit: contain;">
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text text-wrap fw-bold">CVG Sidor</span>
                                                <span class="info-box-number text-secondary">
                                                    <i class="bi bi-person-workspace me-1"></i> 4 Estudiantes
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-4">
                                        <div class="info-box bg-light border shadow-sm h-100 mb-0">
                                            <span class="info-box-icon bg-white shadow-sm p-1">
                                                <img src="https://www.bauxilum.com.ve/wp-content/uploads/2022/11/Bauxilum-logo-oficial-png-01.png" alt="Logo Bauxilum" style="width: 100%; height: 100%; object-fit: contain;">
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text text-wrap fw-bold">CVG Bauxilum</span>
                                                <span class="info-box-number text-secondary">
                                                    <i class="bi bi-person-workspace me-1"></i> 3 Estudiantes
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3 mt-2">
                                    <div class="col-md-6 col-lg-4">
                                        <div class="info-box bg-light border shadow-sm h-100 mb-0">
                                            <span class="info-box-icon bg-white shadow-sm p-1">
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/4/47/Corporaci%C3%B3n_Venezolana_de_Guayana_%28Logo%29.png" alt="Logo Bauxilum" style="width: 100%; height: 100%; object-fit: contain;">
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text text-wrap fw-bold">CVG Empresa Matriz</span>
                                                <span class="info-box-number text-secondary">
                                                    <i class="bi bi-person-workspace me-1"></i> 1 Estudiantes
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-4">
                                        <div class="info-box bg-light border shadow-sm h-100 mb-0">
                                            <span class="info-box-icon bg-white shadow-sm p-1">
                                                <img src="https://media.glassdoor.com/sqll/4467134/cvg-carbonorca-squarelogo-1626953059243.png" alt="Logo Bauxilum" style="width: 100%; height: 100%; object-fit: contain;">
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text text-wrap fw-bold">CVG Carbonorca</span>
                                                <span class="info-box-number text-secondary">
                                                    <i class="bi bi-person-workspace me-1"></i> 3 Estudiantes
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-4">
                                        <div class="info-box bg-light border shadow-sm h-100 mb-0">
                                            <span class="info-box-icon bg-white shadow-sm p-1">
                                                <img src="data:image/png;base64,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" alt="Logo Bauxilum" style="width: 100%; height: 100%; object-fit: contain;">
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text text-wrap fw-bold">CVG Cabelum</span>
                                                <span class="info-box-number text-secondary">
                                                    <i class="bi bi-person-workspace me-1"></i> 1 Estudiantes
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                                <form class="row g-3">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">Datos Básicos del Profesor</h6>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label text-secondary small">Nombres (Solo lectura)</label>
                                        <input type="text" class="form-control" value="Juan Antonio" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-secondary small">Apellidos (Solo lectura)</label>
                                        <input type="text" class="form-control" value="Pérez Gómez" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="correo">Correo Electrónico</label>
                                        <input type="email" name="correo" class="form-control" id="correo" value="j.perez@upt.edu.ve">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="telefono">Teléfono Principal</label>
                                        <input type="text" name="telefono" class="form-control" id="telefono" value="0414-1234567">
                                    </div>

                                    <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">Seguridad Institucional</h6>

                                    <div class="col-md-6">
                                        <label class="form-label" for="pregunta1">Pregunta Secreta 1</label>
                                        <input type="text" name="pregunta1" class="form-control" id="pregunta1" value="Nombre de tu primera mascota">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="respuesta1">Respuesta 1</label>
                                        <input type="password" name="respuesta1" class="form-control" id="respuesta1" placeholder="Dejar en blanco para mantener actual">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="pregunta2">Pregunta Secreta 2</label>
                                        <input type="text" name="pregunta2" class="form-control" id="pregunta2" value="Ciudad de nacimiento de tu madre">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="respuesta2">Respuesta 2</label>
                                        <input type="password" name="respuesta2" class="form-control" id="respuesta2" placeholder="Dejar en blanco para mantener actual">
                                    </div>

                                    <div class="col-12 mt-4">
                                        <button type="button" class="btn btn-primary"><i class="bi bi-save me-1"></i> Actualizar Credenciales</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection