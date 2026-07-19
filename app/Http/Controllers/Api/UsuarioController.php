<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function show()
    {
        return auth()->user();
    }

    public function update(Request $request)
    {
        $usuario = auth()->user();

        $validated = $request->validate([
            'nombre' => ['sometimes', 'string', 'max:100'],
            'email' => ['sometimes', 'string', 'email', 'max:150', 'unique:usuarios,email,'.$usuario->id],
            'moneda' => ['sometimes', 'string', 'size:3'],
            'password' => ['sometimes', 'string', 'min:8', 'confirmed'],
        ]);

        $usuario->update($validated);

        return response()->json($usuario);
    }
}
