@extends('layouts.admin')

@section('header')
    <x-page-header title="Acreditación - PNF Informática" :breadcrumbs="false" />
@endsection

@section('content')
<div class="app-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box text-bg-primary">
                    <div class="inner">
                        <h3>20</h3>
                        <p>Estudiantes Activos</p>
                    </div>
                    <div class="small-box-icon">
                        <i class="bi bi-people-fill fs-1 opacity-50"></i>
                    </div>
                    <a href="#" class="small-box-footer link-light link-underline-opacity-0">
                        Ver listado <i class="bi bi-arrow-right-circle"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box text-bg-success">
                    <div class="inner">
                        <h3>N# 3</h3>
                        <p>Cohorte en Curso</p>
                    </div>
                    <div class="small-box-icon">
                        <i class="bi bi-mortarboard-fill fs-1 opacity-50"></i>
                    </div>
                    <a href="#" class="small-box-footer link-light link-underline-opacity-0">
                        Gestionar <i class="bi bi-arrow-right-circle"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box text-bg-warning">
                    <div class="inner">
                        <h3>6</h3>
                        <p>Empresas CVG</p>
                    </div>
                    <div class="small-box-icon">
                        <i class="bi bi-building-fill fs-1 opacity-50"></i>
                    </div>
                    <a href="#" class="small-box-footer link-light link-underline-opacity-0">
                        Ver detalles <i class="bi bi-arrow-right-circle"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box text-bg-info">
                    <div class="inner">
                        <h3>2</h3>
                        <p>Sesiones Pendiente Hoy</p>
                    </div>
                    <div class="small-box-icon">
                        <i class="bi bi-calendar-x-fill fs-1 opacity-50"></i>
                    </div>
                    <a href="#" class="small-box-footer link-light link-underline-opacity-0">
                        Ir a la agenda <i class="bi bi-arrow-right-circle"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row mt-4">

            <div class="col-md-4">
                <div class="card card-outline card-primary shadow-sm h-100">
                    <div class="card-header">
                        <h3 class="card-title"><i class="bi bi-list-check me-1"></i> Acción Requerida</h3>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="text-center mb-3">
                            <i class="bi bi-exclamation-circle text-warning" style="font-size: 2.5rem;"></i>
                            <h5 class="mt-2 text-dark">Hoy (11/06/2026)</h5>
                            <p class="text-muted small">Tienes 2 sesiones pendientes de asistencia:</p>
                        </div>

                        <div class="list-group list-group-flush mb-3">
                            <div class="list-group-item d-flex justify-content-between align-items-start px-0 py-3 flex-wrap gap-2">
                                <span class="small fw-semibold text-secondary flex-grow-1" style="max-width: 70%;">
                                    08:00 AM - Revisión de Portafolios de Saberes y Acreditación
                                </span>
                                <button class="btn btn-sm btn-outline-secondary flex-shrink-0">Tomar asistencia</button>
                            </div>

                            <div class="list-group-item d-flex justify-content-between align-items-start px-0 py-3 flex-wrap gap-2">
                                <span class="small fw-semibold text-secondary flex-grow-1" style="max-width: 70%;">
                                    02:00 PM - Entrega de Proyectos Finales y Defensa Oral
                                </span>
                                <button class="btn btn-sm btn-outline-secondary flex-shrink-0">Tomar asistencia</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-light">
                        <h3 class="card-title"><i class="bi bi-bar-chart-fill me-1 text-primary"></i> Asistencia por Empresas (Cohorte #3)</h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item py-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="small fw-semibold">CVG Venalum</span>
                                    <span class="small fw-bold text-success">95%</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 95%"></div>
                                </div>
                            </li>
                            <li class="list-group-item py-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="small fw-semibold">CVG Sidor</span>
                                    <span class="small fw-bold text-primary">80%</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 80%"></div>
                                </div>
                            </li>
                            <li class="list-group-item py-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="small fw-semibold">CVG Carbonorca</span>
                                    <span class="small fw-bold text-warning">65%</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 65%"></div>
                                </div>
                            </li>
                            <li class="list-group-item py-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="small fw-semibold">CVG Bauxilum</span></span>
                                    <span class="small fw-bold text-danger">45%</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 45%"></div>
                                </div>
                            </li>
                            <li class="list-group-item py-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="small fw-semibold">CVG Empresa Matriz</span></span>
                                    <span class="small fw-bold text-danger">20%</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 20%"></div>
                                </div>
                            </li>
                            <li class="list-group-item py-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="small fw-semibold">CVG Cabelum</span></span>
                                    <span class="small fw-bold text-danger">12%</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 12%"></div>
                                </div>
                            </li>
                            <li class="list-group-item text-center bg-light">
                                <a href="#" class="small text-decoration-none text-primary fw-semibold">Ver reporte completo de asistencias <i class="bi bi-arrow-right"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h3 class="card-title"><i class="bi bi-calendar3 me-1 text-primary"></i> Cronograma de Sesiones (Cohorte #3)</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0 align-middle small">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Cohorte</th>
                                        <th>Actividad Planificada</th>
                                        <th>Estado de Asistencia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-warning">
                                        <td class="fw-bold"><i class="bi bi-geo-alt-fill text-danger me-1"></i> 11/06 - 08:00 AM</td>
                                        <td class="fw-semibold">Cohorte #3</td>
                                        <td>Revisión de Portafolios</td>
                                        <td><span class="badge bg-warning text-dark">Pendiente</span></td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td class="fw-bold"><i class="bi bi-geo-alt-fill text-danger me-1"></i> 11/06 - 02:00 PM</td>
                                        <td class="fw-semibold">Cohorte #3</td>
                                        <td>Entrega de Proyectos</td>
                                        <td><span class="badge bg-warning text-dark">Pendiente</span></td>
                                    </tr>
                                    <tr>
                                        <td>12/06/2026</td>
                                        <td>Cohorte #3</td>
                                        <td>Asesoría Técnica Individual</td>
                                        <td><span class="badge bg-secondary">Programada</span></td>
                                    </tr>

                                    <tr>
                                        <td>15/06/2026</td>
                                        <td>Cohorte #3</td>
                                        <td>Defensa Oral de Proyectos (Parte I)</td>
                                        <td><span class="badge text-bg-secondary">Programada</span></td>
                                    </tr>
                                    <tr>
                                        <td>16/06/2026</td>
                                        <td>Cohorte #3</td>
                                        <td>Defensa Oral de Proyectos (Parte II)</td>
                                        <td><span class="badge text-bg-secondary">Programada</span></td>
                                    </tr>
                                    <tr>
                                        <td>17/06/2026</td>
                                        <td>Cohorte #3</td>
                                        <td>Firma de Actas y Cierre de Notas</td>
                                        <td><span class="badge text-bg-info">Cierre de Cohorte</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection