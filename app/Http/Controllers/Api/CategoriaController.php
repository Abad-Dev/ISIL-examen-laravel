<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        return auth()->user()->categorias;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:80'],
            'tipo' => ['required', 'in:ingreso,gasto'],
            'color' => ['nullable', 'string', 'size:7'],
        ]);

        $categoria = auth()->user()->categorias()->create($validated);

        return response()->json($categoria, 201);
    }

    public function show(Categoria $categoria)
    {
        $this->authorizeOwner($categoria);

        return $categoria;
    }

    public function update(Request $request, Categoria $categoria)
    {
        $this->authorizeOwner($categoria);

        $validated = $request->validate([
            'nombre' => ['sometimes', 'string', 'max:80'],
            'tipo' => ['sometimes', 'in:ingreso,gasto'],
            'color' => ['nullable', 'string', 'size:7'],
        ]);

        $categoria->update($validated);

        return response()->json($categoria);
    }

    public function destroy(Categoria $categoria)
    {
        $this->authorizeOwner($categoria);

        $categoria->delete();

        return response()->noContent();
    }

    private function authorizeOwner(Categoria $categoria): void
    {
        if ($categoria->usuario_id !== auth()->id()) {
            abort(403);
        }
    }
}
