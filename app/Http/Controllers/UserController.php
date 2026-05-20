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
        // Renderiza el DataTables dentro de la carpeta profesores
        return $dataTable->render('profesores.index');
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

    // =========================================================================
    // GESTIÓN INTERNA DE PROFESORES / PERSONAL - Nuevos Métodos
    // =========================================================================

    /**
     * Muestra el formulario para crear un profesor desde el panel interno.
     */
    public function createProfesor()
    {
        $roles = Role::all();   
        // Apunta a tu vista dedicada interna
        return view('profesores.create', compact('roles')); 
    }

    /**
     * Procesa y guarda el profesor creado desde el panel interno.
     */
    public function storeProfesor(Request $request)
    {
        // Ejecuta la lógica y validación exacta del store principal
        $this->store($request);

        // Al terminar, sobrescribe la redirección para quedarse en el panel administrativo
        return redirect()->route('profesores.index')->with('success', 'Personal registrado exitosamente.');
    }
}