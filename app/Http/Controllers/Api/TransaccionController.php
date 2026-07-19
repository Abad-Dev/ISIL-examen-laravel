<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Transaccion;
use Illuminate\Http\Request;

class TransaccionController extends Controller
{
    public function index()
    {
        return auth()->user()->transacciones;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'categoria_id' => ['required', 'exists:categorias,id'],
            'tipo' => ['required', 'in:ingreso,gasto'],
            'monto' => ['required', 'numeric', 'min:0.01'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'fecha' => ['required', 'date'],
        ]);

        $categoria = Categoria::findOrFail($validated['categoria_id']);
        $this->authorizeCategoriaOwner($categoria);

        $transaccion = auth()->user()->transacciones()->create($validated);

        return response()->json($transaccion, 201);
    }

    public function show(Transaccion $transaccion)
    {
        $this->authorizeOwner($transaccion);

        return $transaccion;
    }

    public function update(Request $request, Transaccion $transaccion)
    {
        $this->authorizeOwner($transaccion);

        $validated = $request->validate([
            'categoria_id' => ['sometimes', 'exists:categorias,id'],
            'tipo' => ['sometimes', 'in:ingreso,gasto'],
            'monto' => ['sometimes', 'numeric', 'min:0.01'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'fecha' => ['sometimes', 'date'],
        ]);

        if (isset($validated['categoria_id'])) {
            $categoria = Categoria::findOrFail($validated['categoria_id']);
            $this->authorizeCategoriaOwner($categoria);
        }

        $transaccion->update($validated);

        return response()->json($transaccion);
    }

    public function destroy(Transaccion $transaccion)
    {
        $this->authorizeOwner($transaccion);

        $transaccion->delete();

        return response()->noContent();
    }

    private function authorizeOwner(Transaccion $transaccion): void
    {
        if ($transaccion->usuario_id !== auth()->id()) {
            abort(403);
        }
    }

    private function authorizeCategoriaOwner(Categoria $categoria): void
    {
        if ($categoria->usuario_id !== auth()->id()) {
            abort(403);
        }
    }
}
