<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCuentaRequest;
use App\Http\Requests\UpdateCuentaRequest;
use App\Models\Cuenta;
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

    public function update(UpdateCuentaRequest $request, Cuenta $cuenta): RedirectResponse
    {
        $this->authorizeCuenta($cuenta);

        $cuenta->update($request->validated());

        return redirect()
            ->route('cuentas.index')
            ->with('status', __('Account updated successfully.'));
    }

    public function destroy(Cuenta $cuenta): RedirectResponse
    {
        $this->authorizeCuenta($cuenta);

        $cuenta->delete();

        return redirect()
            ->route('cuentas.index')
            ->with('status', __('Account deleted successfully.'));
    }

    private function authorizeCuenta(Cuenta $cuenta): void
    {
        if ($cuenta->usuario_id !== auth()->id()) {
            abort(403);
        }
    }
}
