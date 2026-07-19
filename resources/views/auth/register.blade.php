@extends('layouts.auth')

@section('title', __('Register') . ' — ' . config('app.name'))

@section('content')
    <x-auth.card :title="__('Register')" :subtitle="__('Create your account and start organizing your finances.')">
        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <x-auth.input
                :label="__('Name')"
                name="name"
                type="text"
                icon="heroicon-o-user"
                value="{{ old('name') }}"
                required
                autocomplete="name"
                autofocus
            />

            <x-auth.input
                :label="__('Email Address')"
                name="email"
                type="email"
                icon="heroicon-o-envelope"
                value="{{ old('email') }}"
                required
                autocomplete="email"
            />

            <x-auth.input
                :label="__('Password')"
                name="password"
                type="password"
                icon="heroicon-o-lock-closed"
                required
                autocomplete="new-password"
            />

            <x-auth.input
                :label="__('Confirm Password')"
                name="password_confirmation"
                type="password"
                icon="heroicon-o-shield-check"
                required
                autocomplete="new-password"
            />

            <button type="submit" class="auth-btn-primary">
                <x-heroicon-o-user-plus class="size-5" />
                {{ __('Register') }}
            </button>
        </form>
    </x-auth.card>
@endsection

@section('footer')
    <p>
        {{ __('Already have an account?') }}
        <a href="{{ route('login') }}" class="auth-link underline decoration-palette-orange/60 underline-offset-2">
            {{ __('Login') }}
        </a>
    </p>
@endsection
