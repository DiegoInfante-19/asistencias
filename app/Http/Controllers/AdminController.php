<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ministerio;
use App\Models\Miembro;


class AdminController extends Controller{
    public function index(){
        $ministerios = Ministerio::all();
        $miembros    = Miembro::all();
        return view('index', ['ministerios'=>$ministerios,'miembros'=>$miembros]);
    }
}