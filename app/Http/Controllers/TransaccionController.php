<?php

namespace App\Http\Controllers;

class TransaccionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('transacciones.index');
    }
}
