@extends('layouts.auth')

@section('title', __('Verify Your Email Address') . ' — ' . config('app.name'))

@section('content')
    <x-auth.card :title="__('Verify Your Email Address')" :subtitle="__('Before proceeding, please check your email for a verification link.')">
        @if (session('resent'))
            <x-auth.alert type="success" class="mb-5">
                {{ __('A fresh verification link has been sent to your email address.') }}
            </x-auth.alert>
        @endif

        <div class="flex flex-col items-center gap-4 text-center">
            <span class="flex size-14 items-center justify-center rounded-2xl bg-palette-yellow shadow-md shadow-palette-yellow/40 ring-4 ring-white">
                <x-heroicon-o-envelope-open class="size-7 text-slate-800" />
            </span>

            <p class="text-sm text-slate-500">
                {{ __('If you did not receive the email') }},
            </p>

            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:border-palette-orange hover:bg-palette-orange/10"
                >
                    <x-heroicon-o-arrow-path class="size-4" />
                    {{ __('click here to request another') }}
                </button>
            </form>
        </div>
    </x-auth.card>
@endsection
