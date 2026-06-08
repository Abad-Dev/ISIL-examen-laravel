<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Presupuesto;
use Illuminate\Http\Request;

class PresupuestoController extends Controller
{
    public function index()
    {
        return Presupuesto::all();
    }

    public function store(Request $request)
    {
        $presupuesto = Presupuesto::create($request->all());
        return response()->json($presupuesto, 201);
    }
}