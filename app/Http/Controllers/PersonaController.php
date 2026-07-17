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
            // 1. Obtenemos los datos validados
            $data = $request->validated();

            // 2. Buscamos o creamos el registro de lugar_nacimiento
            // Usamos firstOrCreate para evitar duplicados si ya existe el mismo lugar
            $lugar = \App\Models\LugarNacimientoPersona::firstOrCreate(
                [
                    'id_estado' => $request->id_estado,
                    'id_ciudad' => $request->id_ciudad,
                    'detalles_adicionales' => $request->detalles_adicionales
                ]
            );

            // 3. Asignamos el ID del lugar al array de datos de la persona
            $data['id_lugar_nacimiento'] = $lugar->id_lugar_nacimiento;

            // 4. Creamos la persona
            \App\Models\Persona::create($data);

            return redirect()->route('personas.index')
                ->with('success', 'Estudiante registrado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error registrando persona: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al intentar registrar al estudiante.');
        }
    }

    public function show($id){
        $persona = Persona::with([
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
            $persona->update($request->validated());

            // Redirigimos de vuelta al expediente (show) para que la secretaria
            // siga viendo el perfil actualizado.
            return redirect()->route('personas.show', $persona->id_personas)
                ->with('success', 'Datos del estudiante actualizados correctamente.');
        } catch (\Exception $e) {
            Log::error('Error actualizando persona: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar los datos.');
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
