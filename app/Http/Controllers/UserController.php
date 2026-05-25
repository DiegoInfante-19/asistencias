<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\DataTables\UsersDataTable;

class UserController extends Controller
{
    /**
     * Muestra la tabla interactiva de DataTables en el área administrativa.
     */
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

    public function store(Request $request)
    {
        // ... Tu lógica de validación y creación original ...
        // (Mantén el código exacto que ya tenías aquí abajo)

        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'cedula' => 'required|string|unique:users',
            'email' => 'required|string|email|unique:users',
            'username' => 'required|string|unique:users',
            'phone' => 'nullable|numeric|digits_between:7,15',
            'role_id' => 'required|exists:roles,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'cedula' => $request->cedula,
            'email' => $request->email,
            'username' => $request->username,
            'phone' => $request->phone,
            'status' => 'Activo',
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }


    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'cedula'    => 'required|string|max:20|unique:users,cedula',
            'email'     => 'required|string|email|max:255|unique:users,email',
            'username'  => 'required|string|max:50|unique:users,username',
            'phone' => 'nullable|numeric|digits_between:7,15', 
            'role_id'   => 'required|exists:roles,id',
            'password'  => 'required|string|min:8|confirmed',
        ]);
        User::create([
            'name'      => $request->name,
            'last_name' => $request->last_name,
            'cedula'    => $request->cedula,
            'email'     => $request->email,
            'username'  => $request->username,
            'phone'     => $request->phone,
            'role_id'   => $request->role_id,
            'status'    => 'Activo',
            'password'  => Hash::make($request->password),
        ]);
        // Redirige a la misma página (la tabla) para que DataTables se actualice
        return redirect()->back()->with('success', 'El profesor ha sido registrado desde el panel exitosamente.');
    }


    public function update(Request $request, $id)
    {
        // 1. Validamos estrictamente los datos que llegan del formulario
        $request->validate([
            'name'      => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            // Validamos que la cédula sea única, EXCEPTO para el usuario que estamos editando
            'cedula'    => 'required|string|max:20|unique:users,cedula,' . $id . ',id_users',
            'email'     => 'required|email|max:255|unique:users,email,' . $id . ',id_users',
            'phone'     => 'nullable|string|max:20',
            'status'    => 'required|in:Activo,Inactivo,Suspendido',
        ]);

        // 2. Buscamos al usuario por nuestra clave primaria personalizada (id_users)
        $user = User::where('id_users', $id)->firstOrFail();

        // 3. Actualizamos los campos
        $user->update([
            'name'      => $request->name,
            'last_name' => $request->last_name,
            'cedula'    => $request->cedula,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'status'    => $request->status,
        ]);

        // 4. Redirigimos de vuelta a la tabla con un mensaje de éxito
        return redirect()->back()->with('success', 'Los datos del profesor han sido actualizados correctamente.');
    }

    public function destroy($id){
        // 1. Buscamos al usuario por nuestra clave primaria personalizada (id_users)
        $user = User::where('id_users', $id)->firstOrFail();

        // OPCIONAL PERO RECOMENDADO: Evitar que el administrador se borre a sí mismo por accidente
        if (auth()->user()->id_users === $user->id_users) {
            return redirect()->back()->with('error', 'No puedes eliminar tu propia cuenta mientras estás en sesión.');
        }

        // 2. Ejecutamos la eliminación
        $user->delete();

        // 3. Redirigimos a la tabla con un mensaje de éxito
        return redirect()->back()->with('success', 'El profesor ha sido eliminado del sistema exitosamente.');
    }

    // =========================================================================
    // GESTIÓN INTERNA DE PROFESORES / PERSONAL - Nuevos Métodos
    // =========================================================================

    /**
     * Muestra el formulario para crear un profesor desde el panel interno.
     */
    // public function createProfesor()
    // {
    //     $roles = Role::all();
    //     // Apunta a tu vista dedicada interna
    //     return view('profesores.create', compact('roles'));
    // }

    /**
     * Procesa y guarda el profesor creado desde el panel interno.
     */
    // public function storeProfesor(Request $request)
    // {
    //     // Ejecuta la lógica y validación exacta del store principal
    //     $this->store($request);

    //     // Al terminar, sobrescribe la redirección para quedarse en el panel administrativo
    //     return redirect()->route('profesores.index')->with('success', 'Personal registrado exitosamente.');
    // }
}
