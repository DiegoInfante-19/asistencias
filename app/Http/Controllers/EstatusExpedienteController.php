<?php

namespace App\Http\Controllers;

use App\Models\EstatusExpediente;
use App\DataTables\EstatusExpedientesDataTable; // Lo crearemos en el Paso 5
use App\Http\Requests\StoreEstatusExpedienteRequest;
use App\Http\Requests\UpdateEstatusExpedienteRequest;
use Illuminate\Database\QueryException;

class EstatusExpedienteController extends Controller
{
    /**
     * Muestra el catálogo principal de estatus de expedientes delegando la renderización al DataTable.
     */
    public function index(EstatusExpedientesDataTable $dataTable)
    {
        return $dataTable->render('estatus_expedientes.index');
    }

    /**
     * Guarda un nuevo estatus de expediente en la base de datos.
     */
    public function store(StoreEstatusExpedienteRequest $request)
    {
        try {
            EstatusExpediente::create($request->validated());

            return redirect()->route('estatus_expedientes.index')
                             ->with('success', 'Estatus de expediente registrado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('estatus_expedientes.index')
                             ->with('error', 'Ocurrió un error al registrar el estatus.');
        }
    }

    /**
     * Actualiza el estatus de expediente seleccionado.
     */
    public function update(UpdateEstatusExpedienteRequest $request, $id)
    {
        try {
            $estatus = EstatusExpediente::findOrFail($id);
            $estatus->update($request->validated());

            return redirect()->route('estatus_expedientes.index')
                             ->with('success', 'Estatus de expediente actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('estatus_expedientes.index')
                             ->with('error', 'Ocurrió un error al actualizar el estatus.');
        }
    }

    /**
     * Elimina el estatus de expediente seleccionado protegiendo la integridad referencial.
     */
    public function destroy($id)
    {
        try {
            $estatus = EstatusExpediente::findOrFail($id);
            $estatus->delete();

            return redirect()->route('estatus_expedientes.index')
                             ->with('success', 'Estatus de expediente eliminado correctamente.');
                             
        } catch (QueryException $e) {
            // Este bloque captura si el estatus ya está asociado a un expediente real.
            return redirect()->route('estatus_expedientes.index')
                             ->with('error', 'No se puede eliminar este estatus porque ya está asociado a uno o más expedientes.');
        } catch (\Exception $e) {
            return redirect()->route('estatus_expedientes.index')
                             ->with('error', 'Ocurrió un error inesperado al intentar eliminar el estatus.');
        }
    }
}

/*

cohortes
periodos de receso
pnf
titulos
users
empresas
cargos
estados
ciudades
estatus expedientes


*/