<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class UserController extends Controller{
    
    public function index(){
        $usuarios = \App\Models\User::all();
        return view('usuarios.index',['usuarios' => $usuarios]);  
    }

    public function create(){
        return view('usuarios.create');

    }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function store(Request $request)
    {
        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = Hash::make($request['password']);
        $usuario->fecha_ingreso = date($format = 'Y-m-d');
        $usuario->estado = '1';
        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Se registro el usuario de manera correcta');

    }

    public function show($id){
        $usuario = User::findOrFail($id);
        return view('usuarios.show',['usuario'=>$usuario]);
    }

    public function edit($id){
        $usuario = User::findOrFail($id);
        return view('usuarios.edit',['usuario'=>$usuario]);
    }

    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);
        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = Hash::make($request['password']);
        $usuario->save();
        return redirect()->route('usuarios.index')->with('success', 'Se registro el usuario de manera correcta');
    }

    public function destroy($id){
        User::destroy($id);
        return redirect()->route('usuarios.index')->with('success','Se elimino al usuario de manera correcta');
    }
        
    

}
