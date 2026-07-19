@extends('layouts.auth')

@section('title', __('Reset Password') . ' — ' . config('app.name'))

@section('content')
    <x-auth.card :title="__('Reset Password')" :subtitle="__('Choose a new password for your account.')">
        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <x-auth.input
                :label="__('Email Address')"
                name="email"
                type="email"
                icon="heroicon-o-envelope"
                value="{{ $email ?? old('email') }}"
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
                <x-heroicon-o-key class="size-5" />
                {{ __('Reset Password') }}
            </button>
        </form>
    </x-auth.card>
@endsection

@section('footer')
    <a href="{{ route('login') }}" class="auth-link inline-flex items-center gap-1 underline decoration-palette-orange/60 underline-offset-2">
        <x-heroicon-o-arrow-left class="size-4" />
        {{ __('Back to login') }}
    </a>
@endsection
