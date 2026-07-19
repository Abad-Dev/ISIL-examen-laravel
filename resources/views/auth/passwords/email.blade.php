@extends('layouts.auth')

@section('title', __('Reset Password') . ' — ' . config('app.name'))

@section('content')
    <x-auth.card :title="__('Reset Password')" :subtitle="__('Enter your email and we will send you a reset link.')">
        @if (session('status'))
            <x-auth.alert type="success" class="mb-5">
                {{ session('status') }}
            </x-auth.alert>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
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

            <button type="submit" class="auth-btn-primary">
                <x-heroicon-o-paper-airplane class="size-5" />
                {{ __('Send Password Reset Link') }}
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
