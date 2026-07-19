@extends('layouts.app')

@section('title', __('Dashboard') . ' — ' . config('app.name'))

@section('content')
    <div class="mx-auto max-w-lg">
        <x-auth.card :title="__('Dashboard')">
            @if (session('status'))
                <x-auth.alert type="success" class="mb-5">
                    {{ session('status') }}
                </x-auth.alert>
            @endif

            <p class="text-slate-600 dark:text-slate-300">{{ __('You are logged in!') }}</p>
        </x-auth.card>
    </div>
@endsection
