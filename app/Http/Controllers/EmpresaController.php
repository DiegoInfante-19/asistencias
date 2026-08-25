<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\DataTables\EmpresasDataTable;
use App\Http\Requests\StoreEmpresaRequest;
use App\Http\Requests\UpdateEmpresaRequest;
use Illuminate\Database\QueryException;

class EmpresaController extends Controller
{
    /**
     * Muestra el catálogo principal de empresas delegando la renderización al DataTable.
     */
    public function index(EmpresasDataTable $dataTable)
    {
        // Blindaje AJAX para evitar conflictos de renderizado con el layout
        if (request()->ajax() || request()->wantsJson()) {
            return $dataTable->ajax();
        }

        return $dataTable->render('empresas.index');
    }

    /**
     * Guarda una nueva empresa asegurando que solo pase la data validada.
     */
    public function store(StoreEmpresaRequest $request)
    {
        Empresa::create($request->validated());

        return redirect()->route('empresas.index')
                         ->with('success', 'Empresa registrada correctamente.');
    }

    /**
     * Actualiza la empresa seleccionada utilizando exclusivamente la data validada.
     */
    public function update(UpdateEmpresaRequest $request, $id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->update($request->validated());

        return redirect()->route('empresas.index')
                         ->with('success', 'Empresa actualizada correctamente.');
    }

    /**
     * Intenta eliminar la empresa y captura excepciones de integridad referencial.
     */
    public function destroy($id)
    {
        try {
            $empresa = Empresa::findOrFail($id);
            $empresa->delete();

            return redirect()->route('empresas.index')
                           ->with('success', 'Empresa eliminada correctamente.');

        } catch (QueryException $e) {
            return redirect()->route('empresas.index')
                           ->with('error', 'No se puede eliminar la empresa porque tiene registros asociados (PNFs, personal o acreditaciones).');
        }
    }
}