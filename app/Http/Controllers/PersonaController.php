<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdatePersonaRequest;
use App\DataTables\PersonasDataTable;
use Illuminate\Support\Facades\Log;
use App\Models\Cohorte;
use Illuminate\Support\Facades\DB;

class PersonaController extends Controller{

    public function index(PersonasDataTable $dataTable){
        // Blindaje AJAX para evitar que devuelva el layout completo en peticiones asíncronas
        if (request()->ajax() || request()->wantsJson()) {
            return $dataTable->ajax();
        }

        return $dataTable->render('personas.index');
    }

    public function create(){
        $estados = \App\Models\Estado::all();
        $cohortes = Cohorte::all(); // --- NUEVO: Traer cohortes ---
        
        return view('personas.create', compact('estados', 'cohortes'));
    }

    public function store(StorePersonaRequest $request){
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $lugar = \App\Models\LugarNacimientoPersona::firstOrCreate([
                'id_ciudad' => $data['id_ciudad'],
                'detalles_adicionales' => $data['detalles_adicionales'] ?? null
            ]);

            // Se elimina estado, ciudad y detalles para guardar en persona
            $personaData = array_diff_key($data, array_flip(['id_estado', 'id_ciudad', 'detalles_adicionales']));
            $personaData['id_lugar_nacimiento'] = $lugar->id_lugar_nacimiento;

            // La llave 'id_cohortes' ya viene en $personaData porque fue validada en el Request

            \App\Models\Persona::create($personaData);

            DB::commit();
            return redirect()->route('personas.index')->with('success', 'Registrado con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $persona = Persona::with([
            'lugarNacimiento.ciudad.estado', 
            'cohorte', // --- NUEVO: Cargar la cohorte directamente desde la persona ---
            'telefonos',
            'observacion',
            'empresaPersona.empresa',
            'empresaPersona.cargo',
            'titulacionPersona',
            'formacionAcademica.titulo',
            'formacionAcademica.tituloPnf',
            'inscripcionesSecciones.seccion.periodoAcademico', // (Ajustado, se quitó cohorte de aquí)
            'inscripcionesSecciones.seccion.pnf'                        
        ])->findOrFail($id);

        $pnfs                 = \App\Models\Pnf::all();
        $titulos              = \App\Models\Titulo::all();
        $titulos_pnf          = \App\Models\TituloPnf::all();
        $estatusExpedientes = \App\Models\EstatusExpediente::all();
        $cohortes             = Cohorte::all();
        $empresas             = \App\Models\Empresa::all();
        $cargos               = \App\Models\Cargo::all();

        // Modificamos para no pedir 'periodoAcademico.cohorte' ya que cohorte ya no está ahí
        $seccionesData = \App\Models\Seccion::with(['pnf', 'periodoAcademico'])
            ->whereHas('periodoAcademico', function($q) { 
                $q->where('estatus_periodo', 'Activo'); 
            })
            ->where('estatus_seccion', 'Activa')
            ->get()
            ->map(function ($seccion) {
                return [
                    'id_seccion'      => $seccion->id_seccion,
                    'id_periodo'      => $seccion->id_periodo,
                    'id_pnf'          => $seccion->id_pnf,
                    'nombre_seccion'  => $seccion->nombre_seccion,
                    'nombre_pnf'      => $seccion->pnf->nombre_pnf ?? '',
                ];
            });

        return view('personas.show', compact(
            'persona',
            'pnfs',
            'titulos',
            'titulos_pnf',
            'estatusExpedientes',
            'cohortes',
            'empresas',
            'cargos',
            'seccionesData'
        ));
    }

    public function edit($id)
    {
        $persona = \App\Models\Persona::with('lugarNacimiento.ciudad')->findOrFail($id);
        $estados = \App\Models\Estado::all();
        $cohortes = Cohorte::all(); // --- NUEVO: Traer cohortes ---

        return view('personas.edit', compact('persona', 'estados', 'cohortes'));
    }

    public function update(UpdatePersonaRequest $request, Persona $persona)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $lugar = \App\Models\LugarNacimientoPersona::firstOrCreate([
                'id_ciudad' => $data['id_ciudad'],
                'detalles_adicionales' => $data['detalles_adicionales'] ?? null
            ]);

            $personaData = array_diff_key($data, array_flip(['id_estado', 'id_ciudad', 'detalles_adicionales']));
            $personaData['id_lugar_nacimiento'] = $lugar->id_lugar_nacimiento;

            $persona->update($personaData);

            DB::commit();
            return redirect()->route('personas.show', $persona->id_personas)
                ->with('success', 'Actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
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