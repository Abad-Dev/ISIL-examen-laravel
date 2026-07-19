<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCuentaRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CuentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        return view('cuentas.index', [
            'cuentas' => auth()->user()->cuentas()->latest()->get(),
            'icons' => config('cuentas.icons'),
            'colors' => config('cuentas.colors'),
        ]);
    }

    public function store(StoreCuentaRequest $request): RedirectResponse
    {
        auth()->user()->cuentas()->create($request->validated());

        return redirect()
            ->route('cuentas.index')
            ->with('status', __('Account created successfully.'));
    }
}
