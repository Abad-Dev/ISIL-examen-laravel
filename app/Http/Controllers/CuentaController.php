<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCuentaRequest;
use App\Http\Requests\UpdateCuentaRequest;
use App\Models\Cuenta;
use App\Support\Money;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CuentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $cuentas = auth()->user()->cuentas()->with(['principal', 'vinculadas'])->latest()->get();

        return view('cuentas.index', [
            'cuentas' => $cuentas,
            'cuentasPrincipales' => $cuentas->whereNull('cuenta_principal_id')->sortBy('nombre')->values(),
            'icons' => config('cuentas.icons'),
            'colors' => config('cuentas.colors'),
        ]);
    }

    public function store(StoreCuentaRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($data['cuenta_principal_id']) {
            // El saldo vive en la cuenta principal.
            $data['saldo'] = '0.00';
        }

        auth()->user()->cuentas()->create($data);

        return redirect()
            ->route('cuentas.index')
            ->with('status', __('Account created successfully.'));
    }

    public function update(UpdateCuentaRequest $request, Cuenta $cuenta): RedirectResponse
    {
        $this->authorizeCuenta($cuenta);

        $data = $request->validated();

        DB::transaction(function () use ($cuenta, $data) {
            $principalId = $data['cuenta_principal_id'];

            if ($principalId) {
                if ($cuenta->cuenta_principal_id === null) {
                    // Al unificar, el saldo de esta cuenta se suma al de la principal.
                    $principal = Cuenta::query()->lockForUpdate()->findOrFail($principalId);
                    $principal->saldo = Money::add($principal->saldo ?? '0.00', $cuenta->saldo ?? '0.00');
                    $principal->save();
                }

                $data['saldo'] = '0.00';
            }

            $cuenta->update($data);
        });

        return redirect()
            ->route('cuentas.index')
            ->with('status', __('Account updated successfully.'));
    }

    public function destroy(Cuenta $cuenta): RedirectResponse
    {
        $this->authorizeCuenta($cuenta);

        if ($cuenta->vinculadas()->exists()) {
            return redirect()
                ->route('cuentas.index')
                ->with('error', __('Cannot delete an account that has linked accounts.'));
        }

        if ($cuenta->transacciones()->exists() || $cuenta->transferenciasRecibidas()->exists()) {
            return redirect()
                ->route('cuentas.index')
                ->with('error', __('Cannot delete account with transactions.'));
        }

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
