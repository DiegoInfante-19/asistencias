<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\SecuritySettingsController;
use App\Http\Controllers\LocalidadController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\PnfController;
use App\Http\Controllers\CohorteController;
use App\Http\Controllers\PeriodoAcademicoController; 
use App\Http\Controllers\SeccionController;                
use App\Http\Controllers\TituloController;
use App\Http\Controllers\EstatusExpedienteController;
use App\Http\Controllers\PeriodoRecesoController;
use App\Http\Controllers\PersonaTelefonoController;
use App\Http\Controllers\PersonaObservacionController;
use App\Http\Controllers\PersonaEmpresaController;
use App\Http\Controllers\PersonaTitulacionController;
use App\Http\Controllers\PersonaFormacionAcademicaController;
use App\Http\Controllers\PersonaInscripcionController;
use App\Http\Controllers\PersonaController;

use App\Http\Controllers\SesionController;
use App\Http\Controllers\AsistenciaController;

Auth::routes(['register' => true]);

// =========================================================================
// CAPA PERIMETRAL BASE: (Solo usuarios autenticados y sin caché de historial)
// =========================================================================
Route::middleware(['auth', 'no-back-history'])->group(function () { 

    // ---------------------------------------------------------------------
    // BÓVEDA 1: ACCESO GLOBAL (Administrador, Coordinador y Profesor)
    // ---------------------------------------------------------------------
    Route::middleware(['role:Administrador,Coordinador,Profesor'])->group(function () {
        
        // Panel de inicio (Dashboard)
        Route::get('/', [AdminController::class, 'index'])->name('admin.index');

        // Perfil y Seguridad
        Route::get('/perfil', [ProfileController::class, 'index'])->name('perfil.index');
        Route::put('/perfil/update', [ProfileController::class, 'update'])->name('perfil.update');
        Route::put('/perfil/password/update', [SecuritySettingsController::class, 'updatePassword'])->name('seguridad.password.update');
        Route::put('/perfil/seguridad/preguntas/update', [SecuritySettingsController::class, 'updateSecurityQuestions'])->name('seguridad.preguntas.update');
        Route::put('/seguridad/preguntas', [SecurityController::class, 'storePreguntas'])->name('seguridad.preguntas.store');

        // Módulo Docente: Portal de Clases y Asistencias
        Route::controller(SesionController::class)->group(function () {
            Route::get('/sesiones/crear', 'create')->name('sesiones.create');
            Route::get('/sesiones', 'index')->name('sesiones.index');
            Route::post('/sesiones', 'store')->name('sesiones.store');
            Route::get('/sesiones/{sesion}', 'show')->name('sesiones.show');
        });
        
        Route::post('/asistencias/guardar-lote', [AsistenciaController::class, 'guardarLote'])->name('asistencias.guardar_lote');
    });

    // ---------------------------------------------------------------------
    // BÓVEDA 2: ACCESO RESTRINGIDO (Solo Administrador y Coordinador)
    // ---------------------------------------------------------------------
    Route::middleware(['role:Administrador,Coordinador'])->group(function () {
        
        // Gestión de Usuarios y Profesores
        Route::resource('usuarios', UserController::class);
        Route::post('/usuarios/{usuario}/asignar-pnf', [UserController::class, 'asignarPnf'])->name('usuarios.asignar_pnf');
        Route::post('/usuarios/{usuario}/asignar-seccion', [UserController::class, 'asignarSeccion'])->name('usuarios.asignar_seccion');
        Route::delete('/usuarios/{id_usuario}/remover-seccion/{id_seccion}', [UserController::class, 'removerSeccion'])->name('usuarios.remover_seccion');
        Route::get('/profesores', [UserController::class, 'index'])->name('profesores.index');

        // Catálogos del Sistema
        Route::controller(LocalidadController::class)->group(function () {
            Route::get('/localidades', 'index')->name('localidades.index');
            Route::post('/estados', 'storeEstado')->name('estados.store');
            Route::post('/ciudades', 'storeCiudad')->name('ciudades.store');
            Route::put('/localidades/estado/{estado}', 'updateEstado')->name('localidades.updateEstado');
            Route::put('/localidades/ciudad/{ciudad}', 'updateCiudad')->name('localidades.updateCiudad');
            Route::get('/api/ciudades/{id_estado}', 'getCiudadesPorEstado')->name('api.ciudades.get');
            Route::delete('/localidades/estado/{estado}', 'destroyEstado')->name('localidades.destroyEstado');
            Route::delete('/localidades/ciudad/{ciudad}', 'destroyCiudad')->name('localidades.destroyCiudad');
        });

        Route::controller(EmpresaController::class)->group(function () {
            Route::get('/empresas', 'index')->name('empresas.index');  
            Route::post('/empresas', 'store')->name('empresas.store'); 
            Route::put('/empresas/{empresa}', 'update')->name('empresas.update');     
            Route::delete('/empresas/{empresa}', 'destroy')->name('empresas.destroy'); 
        });

        Route::controller(CargoController::class)->group(function () {
            Route::get('/cargos', 'index')->name('cargos.index');
            Route::post('/cargos', 'store')->name('cargos.store');
            Route::put('/cargos/{cargo}', 'update')->name('cargos.update');
            Route::delete('/cargos/{cargo}', 'destroy')->name('cargos.destroy');
        });

        Route::controller(PnfController::class)->group(function () {
            Route::get('/pnfs', 'index')->name('pnfs.index');
            Route::post('/pnfs', 'store')->name('pnfs.store');
            Route::put('/pnfs/{pnf}', 'update')->name('pnfs.update');
            Route::delete('/pnfs/{pnf}', 'destroy')->name('pnfs.destroy');
            Route::get('/pnfs/{pnf}', 'show')->name('pnfs.show');
            Route::post('/pnfs/{pnf}/titulos', 'vincularTitulo')->name('pnfs.titulos.store');
            Route::delete('/pnfs/titulos/{titulo_pnf}', 'desvincularTitulo')->name('pnfs.titulos.destroy');
            Route::post('/pnfs/{pnf}/empresas', 'vincularEmpresa')->name('pnfs.empresas.store');
            Route::delete('/pnfs/empresas/{empresa_pnf}', 'desvincularEmpresa')->name('pnfs.empresas.destroy');
        });

        Route::controller(CohorteController::class)->group(function () {
            Route::get('/cohortes', 'index')->name('cohortes.index');
            Route::post('/cohortes', 'store')->name('cohortes.store');
            Route::put('/cohortes/{cohorte}', 'update')->name('cohortes.update');
            Route::delete('/cohortes/{cohorte}', 'destroy')->name('cohortes.destroy');
        });

        Route::resource('periodos-academicos', PeriodoAcademicoController::class)->parameters([
            'periodos-academicos' => 'periodo'
        ]);

        // Gestión de Secciones Académicas y Matrícula Interna
        Route::resource('secciones', SeccionController::class);
        // NUEVO: Rutas para inscribir y retirar estudiantes directamente en la sección
        Route::post('/secciones/{seccion}/inscribir', [SeccionController::class, 'inscribirEstudiante'])->name('secciones.inscribir');
        Route::delete('/secciones/{seccion}/retirar/{id_inscripcion}', [SeccionController::class, 'retirarEstudiante'])->name('secciones.retirar');

        Route::controller(TituloController::class)->group(function () {
            Route::get('/titulos', 'index')->name('titulos.index');
            Route::post('/titulos', 'store')->name('titulos.store');
            Route::put('/titulos/{titulo}', 'update')->name('titulos.update');
            Route::delete('/titulos/{titulo}', 'destroy')->name('titulos.destroy');
        });

        Route::controller(EstatusExpedienteController::class)->group(function () {
            Route::get('/estatus-expedientes', 'index')->name('estatus_expedientes.index');
            Route::post('/estatus-expedientes', 'store')->name('estatus_expedientes.store');
            Route::put('/estatus-expedientes/{estatus_expediente}', 'update')->name('estatus_expedientes.update');
            Route::delete('/estatus-expedientes/{estatus_expediente}', 'destroy')->name('estatus_expedientes.destroy');
        });

        Route::controller(PeriodoRecesoController::class)->group(function () {
            Route::get('/periodos-recesos', 'index')->name('periodos_recesos.index');
            Route::post('/periodos-recesos', 'store')->name('periodos_recesos.store');
            Route::put('/periodos-recesos/{periodo_receso}', 'update')->name('periodos_recesos.update');
            Route::delete('/periodos-recesos/{periodo_receso}', 'destroy')->name('periodos_recesos.destroy');
        });

        // Mega-CRUD (Personas)
        Route::resource('personas', PersonaController::class);

        Route::prefix('personas/{persona}')->name('personas.')->group(function () {
            Route::post('/telefonos', [PersonaTelefonoController::class, 'store'])->name('telefonos.store');
            Route::delete('/telefonos/{telefono}', [PersonaTelefonoController::class, 'destroy'])->name('telefonos.destroy');
            Route::post('/observaciones', [PersonaObservacionController::class, 'store'])->name('observaciones.store');
            Route::post('/empresas', [PersonaEmpresaController::class, 'store'])->name('empresas.store');
            Route::delete('/empresas/{empresa}', [PersonaEmpresaController::class, 'destroy'])->name('empresas.destroy');
            Route::post('/titulacion', [PersonaTitulacionController::class, 'store'])->name('titulacion.store');
            Route::post('/formacion', [PersonaFormacionAcademicaController::class, 'store'])->name('formacion.store');
            Route::delete('/formacion/{formacion}', [PersonaFormacionAcademicaController::class, 'destroy'])->name('formacion.destroy');
            Route::post('/inscripciones', [PersonaInscripcionController::class, 'store'])->name('inscripciones.store');
            Route::delete('/inscripciones/{inscripcion}', [PersonaInscripcionController::class, 'destroy'])->name('inscripciones.destroy');
        });
        
        Route::get('/titulos-por-pnf/{id_pnf}', [PersonaTitulacionController::class, 'getTitulosPorPnf'])->name('api.titulos.pnf');
    });

});