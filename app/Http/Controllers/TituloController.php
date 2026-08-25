<?php

namespace App\Http\Controllers;

use App\Models\Titulo;
use App\DataTables\TitulosDataTable;
use App\Http\Requests\StoreTituloRequest;
use App\Http\Requests\UpdateTituloRequest;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class TituloController extends Controller
{
    public function index(TitulosDataTable $dataTable)
    {
        // Blindaje AJAX: Evita que se devuelva el HTML completo del layout
        if (request()->ajax() || request()->wantsJson()) {
            return $dataTable->ajax();
        }

        return $dataTable->render('titulos.index');
    }

    public function store(StoreTituloRequest $request)
    {
        try {
            Titulo::create($request->validated());
            return redirect()->route('titulos.index')->with('success', 'Título registrado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('titulos.index')->with('error', 'Error al registrar el título: ' . $e->getMessage());
        }
    }

    public function update(UpdateTituloRequest $request, $id)
    {
        try {
            $titulo = Titulo::findOrFail($id);
            $titulo->update($request->validated());
            return redirect()->route('titulos.index')->with('success', 'Título actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('titulos.index')->with('error', 'Error al actualizar el título.');
        }
    }

    public function destroy($id)
    {
        try {
            $titulo = Titulo::findOrFail($id);
            $titulo->delete();
            return redirect()->route('titulos.index')->with('success', 'Título eliminado correctamente.');
        } catch (QueryException $e) {
            return redirect()->route('titulos.index')
                ->with('error', 'No se puede eliminar el título porque está siendo utilizado en otros registros.');
        } catch (\Exception $e) {
            return redirect()->route('titulos.index')->with('error', 'Ocurrió un error inesperado.');
        }
    }
}