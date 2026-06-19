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