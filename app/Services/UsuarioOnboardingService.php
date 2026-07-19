<?php

namespace App\Services;

use App\Models\Usuario;

class UsuarioOnboardingService
{
    public function seed(Usuario $usuario): void
    {
        if ($usuario->cuentas()->exists() || $usuario->categorias()->exists()) {
            return;
        }

        $usuario->cuentas()->createMany(config('onboarding.cuentas'));
        $usuario->categorias()->createMany(config('onboarding.categorias'));
    }
}
