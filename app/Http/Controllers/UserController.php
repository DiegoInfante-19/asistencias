<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Pnf;
use App\Models\Profesor;
use Illuminate\Support\Facades\Hash;
use App\DataTables\UsersDataTable;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\AsignarPnfRequest;

class UserController extends Controller
{
    public function index(UsersDataTable $dataTable)
    {
        // Blindaje AJAX para prevenir que devuelva el layout completo en peticiones asíncronas
        if (request()->ajax() || request()->wantsJson()) {
            return $dataTable->ajax();
        }

        $roles = Role::all();
        return $dataTable->render('profesores.index', compact('roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('usuarios.create', compact('roles'));
    }

    public function store(UserStoreRequest $request)
    {
        $user = User::create([
            'name_users'      => $request->name_users,
            'last_name_users' => $request->last_name_users,
            'cedula_users'    => $request->cedula_users,
            'email_users'     => $request->email_users,
            'username'        => $request->username,
            'phone_users'     => $request->phone_users,
            'status_users'    => 'Activo',
            'id_rol'          => $request->id_rol,
            'password_users'  => Hash::make($request->password),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'El profesor ha sido registrado desde el panel exitosamente.',
                'user'    => $user
            ]);
        }

        if ($request->has('origen') && $request->origen === 'modal') {
            return redirect()->back()->with('success', 'El profesor ha sido registrado desde el panel exitosamente.');
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::where('id_users', $id)->firstOrFail();

        $user->update([
            'username'        => $request->username,
            'name_users'      => $request->name_users,
            'last_name_users' => $request->last_name_users,
            'cedula_users'    => $request->cedula_users,
            'email_users'     => $request->email_users,
            'phone_users'     => $request->phone_users,
            'status_users'    => $request->status_users,
            'id_rol'          => $request->id_rol,
        ]);

        return redirect()->back()->with('success', 'Los datos del usuario han sido actualizados correctamente.');
    }

    public function destroy($id)
    {
        $user = User::where('id_users', $id)->firstOrFail();

        if (auth()->user()->id_users === $user->id_users) {
            return redirect()->back()->with('error', 'No puedes eliminar tu propia cuenta mientras estás en sesión.');
        }

        $user->delete();
        return redirect()->back()->with('success', 'El usuario ha sido eliminado del sistema exitosamente.');
    }

    public function show($id)
    {
        // FASE 3: Cargamos profundamente las secciones del profesor, incluyendo su PNF, período y la lista de estudiantes inscritos
        $user = User::with([
            'rol', 
            'profesor.pnf', 
            'profesor.secciones.periodoAcademico.cohorte',
            'profesor.secciones.pnf',
            'profesor.secciones.inscripciones.persona.cohorte',
            'profesor.secciones.inscripciones.persona.empresaPersona.empresa'
        ])->findOrFail($id);

        $pnfs = collect();
        $seccionesDisponibles = collect();

        if ($user->isProfesor()) {
            $pnfs = Pnf::where('vigencia_pnf', true)->orderBy('nombre_pnf', 'asc')->get();

            if ($user->profesor && $user->profesor->id_pnf) {
                // FASE 2 - PASO 2.2: Aplicamos el Scope estricto y transformamos con el nombre enriquecido
                $seccionesDisponibles = \App\Models\Seccion::with(['periodoAcademico.cohorte', 'pnf'])
                    ->where('id_pnf', $user->profesor->id_pnf)
                    ->activasParaAsignacion()
                    ->get()
                    ->map(function ($seccion) {
                        $seccion->nombre_seccion_formateado = $seccion->nombre_completo_select;
                        return $seccion;
                    });
            }
        }

        return view('profesores.show', compact('user', 'pnfs', 'seccionesDisponibles'));
    }

    public function asignarPnf(AsignarPnfRequest $request, $id)
    {
        $user = User::findOrFail($id);

        if (!$user->isProfesor()) {
            return redirect()->back()
                ->with('error', 'Acción denegada: Este usuario no posee el rol de Profesor.');
        }

        Profesor::updateOrCreate(
            ['id_users' => $user->id_users],
            [
                'id_pnf' => $request->id_pnf,
                'nivel_asignado' => $request->nivel_asignado,
                'fecha_asignacion_profesor' => $request->fecha_asignacion_profesor
            ]
        );

        return redirect()->route('usuarios.show', $id)
            ->with('success', 'Asignación académica actualizada correctamente.');
    }

    public function asignarSeccion(Request $request, $id)
    {
        $request->validate([
            'id_seccion' => 'required|exists:secciones,id_seccion'
        ]);

        $user = User::with('profesor')->findOrFail($id);

        if (!$user->profesor) {
            return redirect()->back()->with('error', 'Debe configurar el PNF del profesor antes de asignarle secciones.');
        }

        // Usamos syncWithoutDetaching para añadir la sección de forma ágil a su carga múltiple N:M
        $user->profesor->secciones()->syncWithoutDetaching([$request->id_seccion]);

        return redirect()->back()->with('success', 'Sección académica asignada exitosamente a la carga del profesor.');
    }

    public function removerSeccion($id_usuario, $id_seccion)
    {
        $user = User::with('profesor')->findOrFail($id_usuario);
        
        if ($user->profesor) {
            $user->profesor->secciones()->detach($id_seccion);
        }

        return redirect()->back()->with('success', 'La sección ha sido removida de la carga del profesor.');
    }
}