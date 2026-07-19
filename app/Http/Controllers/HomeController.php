<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $usuario = auth()->user();

        return view('home', [
            'cuentas' => $usuario->cuentas()->orderBy('nombre')->get(),
            'categorias' => $usuario->categorias()->orderBy('tipo')->orderBy('nombre')->get(),
        ]);
    }
}
