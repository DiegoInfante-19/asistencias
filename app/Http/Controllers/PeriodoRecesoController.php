<?php

namespace App\Http\Controllers;

use App\Models\PeriodoReceso;
use App\DataTables\PeriodosRecesosDataTable; 
use App\Http\Requests\StorePeriodoRecesoRequest;
use App\Http\Requests\UpdatePeriodoRecesoRequest;
use Illuminate\Database\QueryException;

class PeriodoRecesoController extends Controller
{
    /**
     * Muestra el catálogo principal delegando la renderización al DataTable.
     */
    public function index(PeriodosRecesosDataTable $dataTable)
    {
        // Blindaje extra: Si es una petición asíncrona de DataTables, devolvemos exclusivamente el JSON
        if (request()->ajax() || request()->wantsJson()) {
            return $dataTable->ajax();
        }

        // Si es una entrada normal por el navegador, renderizamos la vista completa
        return $dataTable->render('periodos_recesos.index');
    }

    /**
     * Guarda un nuevo periodo de receso asegurando que pase la validación de fechas.
     */
    public function store(StorePeriodoRecesoRequest $request)
    {
        try {
            PeriodoReceso::create($request->validated());

            return redirect()->route('periodos_recesos.index')
                             ->with('success', 'Periodo registrado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('periodos_recesos.index')
                             ->with('error', 'Ocurrió un error al registrar el periodo: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza el periodo seleccionado verificando las nuevas fechas.
     */
    public function update(UpdatePeriodoRecesoRequest $request, $id)
    {
        try {
            $periodo = PeriodoReceso::findOrFail($id);
            $periodo->update($request->validated());

            return redirect()->route('periodos_recesos.index')
                             ->with('success', 'Periodo actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('periodos_recesos.index')
                             ->with('error', 'Ocurrió un error al actualizar el periodo.');
        }
    }

    /**
     * Intenta eliminar el periodo capturando posibles restricciones de llave foránea.
     */
    public function destroy($id)
    {
        try {
            $periodo = PeriodoReceso::findOrFail($id);
            $periodo->delete();

            return redirect()->route('periodos_recesos.index')
                             ->with('success', 'Periodo eliminado correctamente.');
                             
        } catch (QueryException $e) {
            return redirect()->route('periodos_recesos.index')
                             ->with('error', 'No se puede eliminar este periodo porque existen registros de asistencia o dependencias asociadas a él.');
        } catch (\Exception $e) {
            return redirect()->route('periodos_recesos.index')
                             ->with('error', 'Ocurrió un error inesperado al intentar eliminar el periodo.');
        }
    }
}