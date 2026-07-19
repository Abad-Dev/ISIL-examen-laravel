<?php

namespace App\Http\Controllers;

class CuentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('cuentas.index');
    }
}
