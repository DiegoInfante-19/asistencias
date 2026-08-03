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
        $user = User::with(['rol', 'profesor.pnf', 'profesor.grupos.cohorte'])->findOrFail($id);

        $pnfs = collect();
        $gruposDisponibles = collect();

        if ($user->isProfesor()) {
            $pnfs = Pnf::where('vigencia_pnf', true)->orderBy('nombre_pnf', 'asc')->get();

            if ($user->profesor && $user->profesor->id_pnf && $user->profesor->nivel_asignado) {
                $gruposDisponibles = \App\Models\GrupoAcademico::with('cohorte')
                    ->where('id_pnf', $user->profesor->id_pnf)
                    ->where('nivel_academico', $user->profesor->nivel_asignado)
                    ->where('estatus_grupo', 'Activo')
                    ->get();
            }
        }

        return view('profesores.show', compact('user', 'pnfs', 'gruposDisponibles'));
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

    public function asignarGrupo(Request $request, $id)
    {
        $request->validate([
            'id_grupo' => 'required|exists:grupos_academicos,id_grupo'
        ]);

        $user = User::with('profesor')->findOrFail($id);

        if (!$user->profesor) {
            return redirect()->back()->with('error', 'Debe asignar un PNF y Nivel primero.');
        }

        $user->profesor->grupos()->syncWithoutDetaching([$request->id_grupo]);

        return redirect()->back()->with('success', 'Grupo académico asignado exitosamente a la carga del profesor.');
    }

    public function removerGrupo($id_usuario, $id_grupo)
    {
        $user = User::with('profesor')->findOrFail($id_usuario);
        
        if ($user->profesor) {
            $user->profesor->grupos()->detach($id_grupo);
        }

        return redirect()->back()->with('success', 'El grupo ha sido removido de la carga del profesor.');
    }
}


/*
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

use Illuminate\Support\Facades\Hash;
use App\DataTables\UsersDataTable;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
 
    public function index(UsersDataTable $dataTable)
    {
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
        
        // CORRECCIÓN: Usamos las llaves correctas que vienen del Request
        $user->update([
            'username'        => $request->username,
            'name_users'      => $request->name_users,
            'last_name_users' => $request->last_name_users,
            'cedula_users'    => $request->cedula_users,
            'email_users'     => $request->email_users,
            'phone_users'     => $request->phone_users,
            'status_users'    => $request->status_users,
        ]);
        
        return redirect()->back()->with('success', 'Los datos del profesor han sido actualizados correctamente.');
    }

    public function destroy($id)
    {
        $user = User::where('id_users', $id)->firstOrFail();
        
        if (auth()->user()->id_users === $user->id_users) {
            return redirect()->back()->with('error', 'No puedes eliminar tu propia cuenta mientras estás en sesión.');
        }
        
        $user->delete();
        return redirect()->back()->with('success', 'El profesor ha sido eliminado del sistema exitosamente.');
    }
}
    */