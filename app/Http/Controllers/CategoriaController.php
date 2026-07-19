<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoriaRequest;
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
        return view('categorias.index', [
            'categorias' => auth()->user()->categorias()->orderBy('tipo')->orderBy('orden')->orderBy('nombre')->get(),
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
}
