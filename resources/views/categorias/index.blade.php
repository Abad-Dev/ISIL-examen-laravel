@extends('layouts.app')

@section('title', __('Categories') . ' — ' . config('app.name'))
@section('mobile-title', __('Categories'))

@section('content')
    <x-auth.card :title="__('Categories')" :subtitle="__('What did I spend on or where did income come from?')">
        <p class="text-slate-600 dark:text-slate-300">{{ __('Organize your movements by type to understand how you use your money.') }}</p>
    </x-auth.card>
@endsection
