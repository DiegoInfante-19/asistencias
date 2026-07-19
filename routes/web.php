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


// 

Auth::routes(['register' => true]);

Route::middleware(['auth', 'no-back-history'])->group(function () { //(Solo usuarios logueados)

    // Panel de inicio
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');

    // CRUD de Usuarios (mantiene URLs como usuarios/create, usuarios/edit)
    Route::resource('usuarios', UserController::class);

    // NUEVA RUTA: Procesar el formulario de asignación académica (Paso 3.2)
    Route::post('/usuarios/{usuario}/asignar-pnf', [UserController::class, 'asignarPnf'])
        ->name('usuarios.asignar_pnf');

    // Nuestra ruta especial para la tabla profesional de administración
    Route::get('/profesores', [UserController::class, 'index'])->name('profesores.index');

    // Ruta para el perfil del usuario autenticado
    Route::get('/perfil', [ProfileController::class, 'index'])->name('perfil.index');

    Route::put('/perfil/update', [ProfileController::class, 'update'])->name('perfil.update');

    // Rutas dedicadas exclusivamente a seguridad de credenciales
    Route::put('/perfil/password/update', [SecuritySettingsController::class, 'updatePassword'])
        ->name('seguridad.password.update');

    Route::put('/perfil/seguridad/preguntas/update', [SecuritySettingsController::class, 'updateSecurityQuestions'])
        ->name('seguridad.preguntas.update');

    // Ruta para recibir y procesar el modal de preguntas secretas
    Route::put('/seguridad/preguntas', [SecurityController::class, 'storePreguntas'])
        ->name('seguridad.preguntas.store');

    // Route::controller(LocalidadController::class)->group(function () {
    //     Route::get('/localidades', 'index')->name('localidades.index');
    //     Route::post('/estados', 'storeEstado')->name('estados.store');
    //     Route::post('/ciudades', 'storeCiudad')->name('ciudades.store');
    //     Route::get('/api/ciudades/{id_estado}', 'getCiudadesPorEstado')->name('api.ciudades.get');
    //     Route::put('/localidades/estado/{id}', 'updateEstado')->name('localidades.updateEstado');
    //     Route::put('/localidades/ciudad/{id}', 'updateCiudad')->name('localidades.updateCiudad');
    // });

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
        Route::get('/empresas', 'index')->name('empresas.index');  // Vista principal y carga del DataTable
        Route::post('/empresas', 'store')->name('empresas.store'); // Procesamiento de creación (Post)
        Route::put('/empresas/{empresa}', 'update')->name('empresas.update');     // Procesamiento de actualización (Put) -> El parámetro debe ser {empresa}
        Route::delete('/empresas/{empresa}', 'destroy')->name('empresas.destroy'); // Procesamiento de eliminación (Delete) -> El parámetro debe ser {empresa}
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

        // --- NUEVAS RUTAS PARA EL DASHBOARD (VISTA SHOW) ---
        Route::get('/pnfs/{pnf}', 'show')->name('pnfs.show');

        // --- RUTAS PARA VINCULACIONES DE TÍTULOS ---
        Route::post('/pnfs/{pnf}/titulos', 'vincularTitulo')->name('pnfs.titulos.store');
        Route::delete('/pnfs/titulos/{titulo_pnf}', 'desvincularTitulo')->name('pnfs.titulos.destroy');

        // --- RUTAS PARA VINCULACIONES DE EMPRESAS ---
        Route::post('/pnfs/{pnf}/empresas', 'vincularEmpresa')->name('pnfs.empresas.store');
        Route::delete('/pnfs/empresas/{empresa_pnf}', 'desvincularEmpresa')->name('pnfs.empresas.destroy');
    });

    Route::controller(App\Http\Controllers\CohorteController::class)->group(function () {
        Route::get('/cohortes', 'index')->name('cohortes.index');
        Route::post('/cohortes', 'store')->name('cohortes.store');
        Route::put('/cohortes/{cohorte}', 'update')->name('cohortes.update');
        Route::delete('/cohortes/{cohorte}', 'destroy')->name('cohortes.destroy');
    });

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

    // ==========================================
    // RUTAS PRINCIPALES DEL MEGA-CRUD (PERSONAS)
    // ==========================================
    // Debe ir PRIMERO para que rutas como /personas/create funcionen bien.
    Route::resource('personas', PersonaController::class);

    // ==========================================
    // RUTAS ANIDADAS DEL EXPEDIENTE DE PERSONAS
    // ==========================================
    Route::prefix('personas/{persona}')->name('personas.')->group(function () {

        // Módulo 1: Teléfonos
        Route::post('/telefonos', [PersonaTelefonoController::class, 'store'])->name('telefonos.store');
        Route::delete('/telefonos/{telefono}', [PersonaTelefonoController::class, 'destroy'])->name('telefonos.destroy');

        // Módulo 2: Observaciones
        Route::post('/observaciones', [PersonaObservacionController::class, 'store'])->name('observaciones.store');

        // Módulo 3: Perfil Laboral
        Route::post('/empresas', [PersonaEmpresaController::class, 'store'])->name('empresas.store');
        Route::delete('/empresas/{empresa}', [PersonaEmpresaController::class, 'destroy'])->name('empresas.destroy');

        // Módulo 4: Expediente Académico (Titulación)
        Route::post('/titulacion', [PersonaTitulacionController::class, 'store'])->name('titulacion.store');

        // Módulo 5: Formación Previa
        Route::post('/formacion', [PersonaFormacionAcademicaController::class, 'store'])->name('formacion.store');
        Route::delete('/formacion/{formacion}', [PersonaFormacionAcademicaController::class, 'destroy'])->name('formacion.destroy');

        // Módulo 6: Control de Estudio (Inscripciones a Cohortes)
        Route::post('/inscripciones', [PersonaInscripcionController::class, 'store'])->name('inscripciones.store');
        Route::delete('/inscripciones/{inscripcion}', [PersonaInscripcionController::class, 'destroy'])->name('inscripciones.destroy');
    });
    // Añade esta línea en tu grupo de rutas auth, preferiblemente cerca de las rutas de Persona
Route::get('/titulos-por-pnf/{id_pnf}', [PersonaTitulacionController::class, 'getTitulosPorPnf'])->name('api.titulos.pnf');
});
