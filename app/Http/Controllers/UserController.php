<?php

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

    // =========================================================================
    // REGISTRO PÚBLICO (LOG IN / REGISTRO INICIAL) - Mantener intacto
    // =========================================================================

    public function create()
    {
        $roles = Role::all();
        return view('usuarios.create', compact('roles')); // Tu formulario original
    }

    public function store(UserStoreRequest $request)
    {
        // Creamos al usuario (el código es el mismo para ambos casos)
        $user = User::create([
            'name'      => $request->name,
            'last_name' => $request->last_name,
            'cedula'    => $request->cedula,
            'email'     => $request->email,
            'username'  => $request->username,
            'phone'     => $request->phone,
            'status'    => 'Activo',
            'role_id'   => $request->role_id,
            'password'  => Hash::make($request->password),
        ]);

        // Si la petición es AJAX, respondemos con JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'El profesor ha sido registrado desde el panel exitosamente.',
                'user'    => $user
            ]);
        }

        // Redirección Inteligente: Comprobamos de dónde viene la petición
        if ($request->has('origen') && $request->origen === 'modal') {
            // Si viene del modal (Panel Administrativo), recargamos la tabla
            return redirect()->back()->with('success', 'El profesor ha sido registrado desde el panel exitosamente.');
        }

        // Si viene del formulario clásico, redirigimos al index
        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }



   public function update(UserUpdateRequest $request, $id){
        $user = User::where('id_users', $id)->firstOrFail();
        $user->update([
            'username'  => $request->username,
            'name'      => $request->name,
            'last_name' => $request->last_name,
            'cedula'    => $request->cedula,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'status'    => $request->status,
        ]);
        return redirect()->back()->with('success', 'Los datos del profesor han sido actualizados correctamente.');
    }

    public function destroy($id){
        $user = User::where('id_users', $id)->firstOrFail();
        if (auth()->user()->id_users === $user->id_users) {
            return redirect()->back()->with('error', 'No puedes eliminar tu propia cuenta mientras estás en sesión.');
        }
        $user->delete();
        return redirect()->back()->with('success', 'El profesor ha sido eliminado del sistema exitosamente.');
    }
}

