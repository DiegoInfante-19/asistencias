<?php

namespace App\Http\Controllers;

use App\Models\Pnf;
use App\Models\Titulo;
use App\Models\Empresa;
use App\Models\TituloPnf;
use App\Models\EmpresaPnf;
use App\DataTables\PnfsDataTable;
use App\Http\Requests\StorePnfRequest;
use App\Http\Requests\UpdatePnfRequest;
use App\Http\Requests\VincularTituloPnfRequest;
use App\Http\Requests\VincularEmpresaPnfRequest;
use Illuminate\Database\QueryException;

class PnfController extends Controller
{
    /**
     * Muestra el catálogo de PNFs.
     */
    public function index(PnfsDataTable $dataTable)
    {
        // Blindaje para responder con JSON si es una petición asíncrona de DataTables
        if (request()->ajax() || request()->wantsJson()) {
            return $dataTable->ajax();
        }

        return $dataTable->render('pnfs.index');
    }

    /**
     * Guarda un nuevo PNF.
     */
    public function store(StorePnfRequest $request)
    {
        Pnf::create($request->validated());

        return redirect()->route('pnfs.index')
            ->with('success', 'PNF registrado correctamente.');
    }

    /**
     * Actualiza un PNF existente.
     */
    public function update(UpdatePnfRequest $request, $id)
    {
        $pnf = Pnf::findOrFail($id);
        $pnf->update($request->validated());

        if ($request->has('origen') && $request->origen == 'update_pnf_show') {
            return redirect()->route('pnfs.show', $id)
                ->with('success', 'PNF actualizado correctamente.');
        }

        return redirect()->route('pnfs.index')
            ->with('success', 'PNF actualizado correctamente.');
    }

    /**
     * Elimina un PNF (protegido contra restricciones de clave foránea).
     */
    public function destroy($id)
    {
        try {
            $pnf = Pnf::findOrFail($id);
            $pnf->delete();

            return redirect()->route('pnfs.index')
                ->with('success', 'PNF eliminado correctamente.');
        } catch (QueryException $e) {
            return redirect()->route('pnfs.index')
                ->with('error', 'No se puede eliminar este PNF porque tiene acreditaciones, profesores o empresas vinculadas.');
        }
    }

    /* =====================================================================
       MÉTODOS DEL DASHBOARD (VISTA SHOW Y VINCULACIONES)
       ===================================================================== */

    public function show($id)
    {
        $pnf = Pnf::with([
            'titulosPnf.titulo',
            'empresasPnf.empresa'
        ])->findOrFail($id);

        $catalogoTitulos = Titulo::orderBy('nombre_titulo_base', 'asc')->get();
        $catalogoEmpresas = Empresa::orderBy('nombre_empresa', 'asc')->get();

        return view('pnfs.show', compact('pnf', 'catalogoTitulos', 'catalogoEmpresas'));
    }

    public function vincularTitulo(VincularTituloPnfRequest $request, $id)
    {
        try {
            TituloPnf::create([
                'id_pnf' => $id,
                'id_titulo' => $request->id_titulo,
                'nombre_titulo_pnf' => $request->nombre_titulo_pnf
            ]);

            return redirect()->route('pnfs.show', $id)
                ->with('success', 'Título vinculado correctamente al PNF.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error al vincular el título: ' . $e->getMessage());
        }
    }

    public function desvincularTitulo($id_titulos_pnf)
    {
        try {
            $vinculo = TituloPnf::findOrFail($id_titulos_pnf);
            $vinculo->delete();

            return redirect()->back()->with('success', 'Título desvinculado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo desvincular el título porque está en uso por expedientes de estudiantes.');
        }
    }

    public function vincularEmpresa(VincularEmpresaPnfRequest $request, $id)
    {
        try {
            EmpresaPnf::create([
                'id_pnf' => $id,
                'id_empresa' => $request->id_empresa,
                'tipo_relacion' => $request->tipo_relacion,
                'observacion_empresa_pnf' => $request->observacion_empresa_pnf
            ]);

            return redirect()->route('pnfs.show', $id)
                ->with('success', 'Empresa aliada registrada correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error al vincular la empresa: ' . $e->getMessage());
        }
    }

    public function desvincularEmpresa($id_empresa_pnf)
    {
        try {
            $vinculo = EmpresaPnf::findOrFail($id_empresa_pnf);
            $vinculo->delete();

            return redirect()->back()->with('success', 'Empresa desvinculada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo retirar la empresa por restricciones del sistema.');
        }
    }
}