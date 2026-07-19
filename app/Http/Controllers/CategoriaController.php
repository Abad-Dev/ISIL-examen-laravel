<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Models\Categoria;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $categorias = auth()->user()->categorias()->orderBy('orden')->orderBy('nombre')->get();

        return view('categorias.index', [
            'categorias' => $categorias,
            'categoriasGasto' => $categorias->where('tipo', 'gasto')->values(),
            'categoriasIngreso' => $categorias->where('tipo', 'ingreso')->values(),
            'icons' => config('categorias.icons'),
            'colors' => config('categorias.colors'),
        ]);
    }

    public function store(StoreCategoriaRequest $request): RedirectResponse
    {
        auth()->user()->categorias()->create([
            ...$request->validated(),
            'activo' => true,
            'orden' => 0,
        ]);

        return redirect()
            ->route('web.categorias.index')
            ->with('status', __('Category created successfully.'));
    }

    public function update(UpdateCategoriaRequest $request, Categoria $categoria): RedirectResponse
    {
        $this->authorizeCategoria($categoria);

        $categoria->update($request->validated());

        return redirect()
            ->route('web.categorias.index')
            ->with('status', __('Category updated successfully.'));
    }

    public function destroy(Categoria $categoria): RedirectResponse
    {
        $this->authorizeCategoria($categoria);

        if ($categoria->transacciones()->exists()) {
            return redirect()
                ->route('web.categorias.index')
                ->with('error', __('Cannot delete category with transactions.'));
        }

        $categoria->delete();

        return redirect()
            ->route('web.categorias.index')
            ->with('status', __('Category deleted successfully.'));
    }

    private function authorizeCategoria(Categoria $categoria): void
    {
        if ($categoria->usuario_id !== auth()->id()) {
            abort(403);
        }
    }
}
