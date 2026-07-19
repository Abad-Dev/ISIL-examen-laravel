<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Services\UsuarioOnboardingService;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct(
        private UsuarioOnboardingService $onboarding
    ) {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:usuarios,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data): Usuario
    {
        $usuario = Usuario::create([
            'nombre' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'moneda' => config('money.currency'),
        ]);

        $this->onboarding->seed($usuario);

        return $usuario;
    }
}
