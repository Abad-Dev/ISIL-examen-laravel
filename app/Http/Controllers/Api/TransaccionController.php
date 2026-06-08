<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaccion;
use Illuminate\Http\Request;

class TransaccionController extends Controller
{
    public function index()
    {
        return Transaccion::all();
    }

    public function store(Request $request)
    {
        $transaccion = Transaccion::create($request->all());
        return response()->json($transaccion, 201);
    }
}