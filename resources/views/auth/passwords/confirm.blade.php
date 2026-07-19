@extends('layouts.auth')

@section('title', __('Confirm Password') . ' — ' . config('app.name'))

@section('content')
    <x-auth.card :title="__('Confirm Password')" :subtitle="__('Please confirm your password before continuing.')">
        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
            @csrf

            <x-auth.input
                :label="__('Password')"
                name="password"
                type="password"
                icon="heroicon-o-lock-closed"
                required
                autocomplete="current-password"
                autofocus
            />

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <button type="submit" class="auth-btn-primary w-auto px-5">
                    <x-heroicon-o-check-circle class="size-5" />
                    {{ __('Confirm Password') }}
                </button>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="auth-link text-sm">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
            </div>
        </form>
    </x-auth.card>
@endsection
