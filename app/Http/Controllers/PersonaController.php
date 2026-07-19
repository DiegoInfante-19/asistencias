<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdatePersonaRequest;
use App\DataTables\PersonasDataTable; // Asegúrate de crear este DataTable luego
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

            // 1. Validamos (esto ya incluye los campos de lugar)
            $data = $request->validated();

            // 2. Creamos o buscamos el lugar de nacimiento
            $lugar = \App\Models\LugarNacimientoPersona::firstOrCreate([
                'id_estado' => $data['id_estado'],
                'id_ciudad' => $data['id_ciudad'],
                'detalles_adicionales' => $data['detalles_adicionales'] ?? null
            ]);

            // 3. Preparamos los datos de Persona (eliminamos los del lugar)
            $personaData = array_diff_key($data, array_flip(['id_estado', 'id_ciudad', 'detalles_adicionales']));
            $personaData['id_lugar_nacimiento'] = $lugar->id_lugar_nacimiento;

            // 4. Creamos
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
        $persona = Persona::with([
            'lugarNacimiento.estado', // <-- Carga el Estado asociado
            'lugarNacimiento.ciudad', // <-- Carga la Ciudad asociada
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

    // public function show($id)
    // {
    //     $persona = Persona::with([
    //         'telefonos',
    //         'observacion',
    //         'empresaPersona.empresa', // <-- CARGA LA EMPRESA
    //         'empresaPersona.cargo',
    //         'titulacionPersona',
    //         'formacionAcademica',
    //         'inscripciones'
    //     ])->findOrFail($id);

    //     // Catálogos para Pestaña Titulación
    //     $pnfs = \App\Models\Pnf::all();
    //     $titulos = \App\Models\Titulo::all();
    //     $estatusExpedientes = \App\Models\EstatusExpediente::all();

    //     // Catálogo para Pestaña Inscripciones
    //     $cohortes = \App\Models\Cohorte::all();

    //     // NUEVO: Catálogos para Pestaña Perfil Laboral
    //     $empresas = \App\Models\Empresa::all();
    //     $cargos = \App\Models\Cargo::all();

    //     // Enviamos TOOOODO al Mega-CRUD
    //     return view('personas.show', compact('persona', 'pnfs', 'titulos', 'estatusExpedientes', 'cohortes', 'empresas', 'cargos'));
    // }


    public function edit($id)
    {
        // Cargamos a la persona asegurando que traiga su lugar de nacimiento
        $persona = \App\Models\Persona::with('lugarNacimiento')->findOrFail($id);

        // Consultamos los estados
        $estados = \App\Models\Estado::all();

        return view('personas.edit', compact('persona', 'estados'));
    }


    public function update(UpdatePersonaRequest $request, Persona $persona)
    {
        try {
            \DB::beginTransaction();

            $data = $request->validated();

            $lugar = \App\Models\LugarNacimientoPersona::firstOrCreate([
                'id_estado' => $data['id_estado'],
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
            // Elimina a la persona. Si configuraste eliminación en cascada en tu base de datos
            // o usas eventos en el modelo, sus satélites también se verán afectados lógicamente.
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
