<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\DataTables\CargosDataTable;
use App\Http\Requests\StoreCargoRequest;
use App\Http\Requests\UpdateCargoRequest;
use Illuminate\Database\QueryException;

class CargoController extends Controller
{
    /**
     * Muestra el catálogo de cargos.
     */
    public function index(CargosDataTable $dataTable)
    {
        return $dataTable->render('cargos.index');
    }

    /**
     * Guarda un nuevo cargo.
     */
    public function store(StoreCargoRequest $request)
    {
        Cargo::create($request->validated());

        return redirect()->route('cargos.index')
            ->with('success', 'Cargo registrado correctamente.');
    }

    /**
     * Actualiza un cargo existente.
     */
    public function update(UpdateCargoRequest $request, $id)
    {
        $cargo = Cargo::findOrFail($id);
        $cargo->update($request->validated());

        return redirect()->route('cargos.index')
            ->with('success', 'Cargo actualizado correctamente.');
    }

    /**
     * Elimina un cargo (protegido contra restricciones de clave foránea).
     */
    public function destroy($id)
    {
        try {
            $cargo = Cargo::findOrFail($id);
            $cargo->delete();

            return redirect()->route('cargos.index')
                ->with('success', 'Cargo eliminado correctamente.');
        } catch (QueryException $e) {
            return redirect()->route('cargos.index')
                ->with('error', 'No se puede eliminar el cargo porque tiene registros asociados.');
        }
    }
}