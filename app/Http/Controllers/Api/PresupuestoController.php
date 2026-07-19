<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Presupuesto;
use Illuminate\Http\Request;

class PresupuestoController extends Controller
{
    public function index()
    {
        return auth()->user()->presupuestos;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'categoria_id' => ['required', 'exists:categorias,id'],
            'monto_limite' => ['required', 'numeric', 'min:0.01'],
            'mes' => ['required', 'integer', 'min:1', 'max:12'],
            'anio' => ['required', 'integer', 'min:2000'],
        ]);

        $categoria = Categoria::findOrFail($validated['categoria_id']);
        $this->authorizeCategoriaOwner($categoria);

        $presupuesto = auth()->user()->presupuestos()->create($validated);

        return response()->json($presupuesto, 201);
    }

    public function show(Presupuesto $presupuesto)
    {
        $this->authorizeOwner($presupuesto);

        return $presupuesto;
    }

    public function update(Request $request, Presupuesto $presupuesto)
    {
        $this->authorizeOwner($presupuesto);

        $validated = $request->validate([
            'categoria_id' => ['sometimes', 'exists:categorias,id'],
            'monto_limite' => ['sometimes', 'numeric', 'min:0.01'],
            'mes' => ['sometimes', 'integer', 'min:1', 'max:12'],
            'anio' => ['sometimes', 'integer', 'min:2000'],
        ]);

        if (isset($validated['categoria_id'])) {
            $categoria = Categoria::findOrFail($validated['categoria_id']);
            $this->authorizeCategoriaOwner($categoria);
        }

        $presupuesto->update($validated);

        return response()->json($presupuesto);
    }

    public function destroy(Presupuesto $presupuesto)
    {
        $this->authorizeOwner($presupuesto);

        $presupuesto->delete();

        return response()->noContent();
    }

    private function authorizeOwner(Presupuesto $presupuesto): void
    {
        if ($presupuesto->usuario_id !== auth()->id()) {
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
