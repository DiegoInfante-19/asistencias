<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdatePersonaRequest;
use App\DataTables\PersonasDataTable;
use Illuminate\Support\Facades\Log;
use App\Models\Cohorte;
use App\Models\GrupoAcademico;
use Illuminate\Support\Facades\DB; // Añadido para el manejo de transacciones

class PersonaController extends Controller{

    public function index(PersonasDataTable $dataTable){
        return $dataTable->render('personas.index');
    }

    public function create(){
        $estados = \App\Models\Estado::all();
        return view('personas.create', compact('estados'));
    }

    public function store(StorePersonaRequest $request){
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $lugar = \App\Models\LugarNacimientoPersona::firstOrCreate([
                'id_ciudad' => $data['id_ciudad'],
                'detalles_adicionales' => $data['detalles_adicionales'] ?? null
            ]);

            $personaData = array_diff_key($data, array_flip(['id_estado', 'id_ciudad', 'detalles_adicionales']));
            $personaData['id_lugar_nacimiento'] = $lugar->id_lugar_nacimiento;

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
            'telefonos',
            'observacion',
            'empresaPersona.empresa',
            'empresaPersona.cargo',
            'titulacionPersona',
            'formacionAcademica.titulo',
            'formacionAcademica.tituloPnf',
            'inscripcionesSecciones.seccion.periodoAcademico.cohorte', // Actualizado
            'inscripcionesSecciones.seccion.pnf'                       // Actualizado
        ])->findOrFail($id);

        // Catálogos para el resto de los Tabs
        $pnfs               = \App\Models\Pnf::all();
        $titulos            = \App\Models\Titulo::all();
        $titulos_pnf        = \App\Models\TituloPnf::all();
        $estatusExpedientes = \App\Models\EstatusExpediente::all();
        $cohortes           = Cohorte::all();
        $empresas           = \App\Models\Empresa::all();
        $cargos             = \App\Models\Cargo::all();

        // Data Específica para el Tab de Inscripciones (Cascada JS adaptada a Secciones y Periodos)
        $seccionesData = \App\Models\Seccion::with(['pnf', 'periodoAcademico.cohorte'])
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

        // UN SOLO RETURN con TODAS las variables
        return view('personas.show', compact(
            'persona',
            'pnfs',
            'titulos',
            'titulos_pnf',
            'estatusExpedientes',
            'cohortes',
            'empresas',
            'cargos',
            'seccionesData' // Reemplaza a gruposData
        ));
    }

    public function edit($id)
    {
        $persona = \App\Models\Persona::with('lugarNacimiento.ciudad')->findOrFail($id);
        $estados = \App\Models\Estado::all();

        return view('personas.edit', compact('persona', 'estados'));
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