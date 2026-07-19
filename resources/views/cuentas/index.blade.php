@extends('layouts.app')

@section('title', __('Accounts') . ' — ' . config('app.name'))
@section('mobile-title', __('Accounts'))

@section('content')
    <x-auth.card :title="__('Accounts')" :subtitle="__('Where do I keep my money?')">
        <p class="text-slate-600 dark:text-slate-300">{{ __('Manage your cash, bank accounts and other places where you store money.') }}</p>
    </x-auth.card>
@endsection
