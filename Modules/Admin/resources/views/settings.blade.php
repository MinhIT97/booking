@extends('admin::layouts.master')

@section('title', 'Settings')
@section('header', 'Settings')
@section('subheader', 'Runtime and maintenance information.')

@section('content')
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden max-w-3xl">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-bold text-gray-900">Application</h2>
    </div>
    <dl class="divide-y divide-gray-50 text-sm">
        <div class="px-6 py-4 flex justify-between gap-4">
            <dt class="text-gray-500">Environment</dt>
            <dd class="font-semibold text-gray-900">{{ app()->environment() }}</dd>
        </div>
        <div class="px-6 py-4 flex justify-between gap-4">
            <dt class="text-gray-500">Debug</dt>
            <dd class="font-semibold text-gray-900">{{ config('app.debug') ? 'Enabled' : 'Disabled' }}</dd>
        </div>
        <div class="px-6 py-4 flex justify-between gap-4">
            <dt class="text-gray-500">Cache Driver</dt>
            <dd class="font-semibold text-gray-900">{{ config('cache.default') }}</dd>
        </div>
        <div class="px-6 py-4 flex justify-between gap-4">
            <dt class="text-gray-500">Queue Driver</dt>
            <dd class="font-semibold text-gray-900">{{ config('queue.default') }}</dd>
        </div>
    </dl>
</div>
@endsection
