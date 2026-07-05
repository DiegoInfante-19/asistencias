<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Pnf; // NUEVO: Importación del modelo PNF
use App\Models\Profesor; // NUEVO: Importación del modelo Profesor

use Illuminate\Support\Facades\Hash;
use App\DataTables\UsersDataTable;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\AsignarPnfRequest; // NUEVO: Importación de nuestro escudo de validación

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

    /* =====================================================================
       NUEVOS MÉTODOS: GESTIÓN DE PERFIL Y ASIGNACIÓN DE PROFESORES
       ===================================================================== */

    /**
     * Muestra el panel administrativo de un usuario específico.
     */
    public function show($id)
    {
        // 1. Buscamos al usuario cargando sus relaciones para optimizar consultas
        $user = User::with(['rol', 'profesor.pnf'])->findOrFail($id);

        $pnfs = collect(); // Inicializamos una colección vacía

        // 2. Si el usuario es profesor, consultamos los PNFs activos para el formulario
        if ($user->isProfesor()) {
            $pnfs = Pnf::where('vigencia_pnf', true)->orderBy('nombre_pnf', 'asc')->get();
        }

        return view('profesores.show', compact('user', 'pnfs'));
    }

    /**
     * Asigna o actualiza el PNF vinculado a un usuario con rol Profesor.
     */
    public function asignarPnf(AsignarPnfRequest $request, $id)
    {
        $user = User::findOrFail($id);

        // Seguridad: Solo los profesores pueden tener asignación académica
        if (!$user->isProfesor()) {
            return redirect()->back()
                ->with('error', 'Acción denegada: Este usuario no posee el rol de Profesor.');
        }

        // updateOrCreate busca por 'id_users' y actualiza o inserta el PNF y la fecha
        Profesor::updateOrCreate(
            ['id_users' => $user->id_users],
            [
                'id_pnf' => $request->id_pnf,
                'fecha_asignacion_profesor' => $request->fecha_asignacion_profesor
            ]
        );

        return redirect()->route('usuarios.show', $id)
            ->with('success', 'Asignación académica actualizada correctamente.');
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