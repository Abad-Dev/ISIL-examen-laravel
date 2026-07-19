@extends('layouts.auth')

@section('title', __('Login') . ' — ' . config('app.name'))

@section('content')
    <x-auth.card :title="__('Login')" :subtitle="__('Access your account to manage your budget.')">
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <x-auth.input
                :label="__('Email Address')"
                name="email"
                type="email"
                icon="heroicon-o-envelope"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                autofocus
            />

            <x-auth.input
                :label="__('Password')"
                name="password"
                type="password"
                icon="heroicon-o-lock-closed"
                required
                autocomplete="current-password"
            />

            <div class="flex items-center justify-between gap-4">
                <label class="flex cursor-pointer items-center gap-2 text-sm text-slate-600">
                    <input
                        type="checkbox"
                        name="remember"
                        id="remember"
                        class="size-4 rounded border-slate-300 text-palette-green focus:ring-palette-green/40"
                        {{ old('remember') ? 'checked' : '' }}
                    >
                    <span>{{ __('Remember Me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="auth-link text-sm">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
            </div>

            <button type="submit" class="auth-btn-primary">
                <x-heroicon-o-arrow-right-on-rectangle class="size-5" />
                {{ __('Login') }}
            </button>
        </form>
    </x-auth.card>
@endsection

@section('footer')
    @if (Route::has('register'))
        <p>
            {{ __('Don\'t have an account?') }}
            <a href="{{ route('register') }}" class="auth-link underline decoration-palette-orange/60 underline-offset-2">
                {{ __('Register') }}
            </a>
        </p>
    @endif
@endsection
