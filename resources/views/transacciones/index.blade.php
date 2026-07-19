@extends('layouts.app')

@section('title', __('Transactions') . ' — ' . config('app.name'))
@section('mobile-title', __('Transactions'))

@section('content')
    <x-auth.card :title="__('Transactions')" :subtitle="__('What movements did I make and from which account?')">
        <p class="text-slate-600 dark:text-slate-300">{{ __('Review your income and expenses and see which account each movement belongs to.') }}</p>
    </x-auth.card>
@endsection
