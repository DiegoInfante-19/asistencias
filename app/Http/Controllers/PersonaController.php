<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdatePersonaRequest;
use App\DataTables\PersonasDataTable;
use Illuminate\Support\Facades\Log;

class PersonaController extends Controller
{
    public function index(PersonasDataTable $dataTable)
    {
        return $dataTable->render('personas.index');
    }

    public function create()
    {
        $estados = \App\Models\Estado::all();
        return view('personas.create', compact('estados'));
    }

    public function store(StorePersonaRequest $request)
    {
        try {
            \DB::beginTransaction();

            $data = $request->validated();

            // CORREGIDO: Se elimina 'id_estado' ya que no pertenece a la tabla lugar_nacimiento_personas
            $lugar = \App\Models\LugarNacimientoPersona::firstOrCreate([
                'id_ciudad' => $data['id_ciudad'],
                'detalles_adicionales' => $data['detalles_adicionales'] ?? null
            ]);

            $personaData = array_diff_key($data, array_flip(['id_estado', 'id_ciudad', 'detalles_adicionales']));
            $personaData['id_lugar_nacimiento'] = $lugar->id_lugar_nacimiento;

            \App\Models\Persona::create($personaData);

            \DB::commit();
            return redirect()->route('personas.index')->with('success', 'Registrado con éxito.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        // CORREGIDO: Carga ansiosa adaptada en cascada (.ciudad.estado) debido al cambio de relaciones
        $persona = Persona::with([
            'lugarNacimiento.ciudad.estado', 
            'telefonos',
            'observacion',
            'empresaPersona.empresa',
            'empresaPersona.cargo',
            'titulacionPersona',
            'formacionAcademica.titulo',
            'formacionAcademica.tituloPnf',
            'inscripciones'
        ])->findOrFail($id);

        $pnfs               = \App\Models\Pnf::all();
        $titulos            = \App\Models\Titulo::all();
        $titulos_pnf        = \App\Models\TituloPnf::all();
        $estatusExpedientes = \App\Models\EstatusExpediente::all();
        $cohortes           = \App\Models\Cohorte::all();
        $empresas           = \App\Models\Empresa::all();
        $cargos             = \App\Models\Cargo::all();

        return view('personas.show', compact(
            'persona',
            'pnfs',
            'titulos',
            'titulos_pnf',
            'estatusExpedientes',
            'cohortes',
            'empresas',
            'cargos'
        ));
    }

    public function edit($id)
    {
        // Se carga la ruta en cascada para el formulario de edición si es requerido
        $persona = \App\Models\Persona::with('lugarNacimiento.ciudad')->findOrFail($id);
        $estados = \App\Models\Estado::all();

        return view('personas.edit', compact('persona', 'estados'));
    }

    public function update(UpdatePersonaRequest $request, Persona $persona)
    {
        try {
            \DB::beginTransaction();

            $data = $request->validated();

            // CORREGIDO: Se remueve 'id_estado' del proceso de consulta/creación de la entidad satélite
            $lugar = \App\Models\LugarNacimientoPersona::firstOrCreate([
                'id_ciudad' => $data['id_ciudad'],
                'detalles_adicionales' => $data['detalles_adicionales'] ?? null
            ]);

            $personaData = array_diff_key($data, array_flip(['id_estado', 'id_ciudad', 'detalles_adicionales']));
            $personaData['id_lugar_nacimiento'] = $lugar->id_lugar_nacimiento;

            $persona->update($personaData);

            \DB::commit();
            return redirect()->route('personas.show', $persona->id_personas)
                ->with('success', 'Actualizado correctamente.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error al actualizar.');
        }
    }

    public function destroy(Persona $persona)
    {
        try {
            $persona->delete();
            return redirect()->route('personas.index')
                ->with('success', 'Estudiante eliminado del sistema.');
        } catch (\Exception $e) {
            Log::error('Error eliminando persona: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Ocurrió un error al intentar eliminar el registro.');
        }
    }
}