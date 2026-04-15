@extends('admin::layouts.master')

@section('title', 'Admin Dashboard')
@section('header', 'Admin Dashboard')
@section('subheader', 'Review platform activity and pending operations.')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
    <div class="stat-card">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Active Users</p>
        <p class="text-3xl font-extrabold text-gray-900">{{ number_format($stats['active_users']) }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ number_format($stats['hosts']) }} hosts</p>
    </div>
    <div class="stat-card">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Active Properties</p>
        <p class="text-3xl font-extrabold text-gray-900">{{ number_format($stats['active_properties']) }}</p>
        <p class="text-xs text-amber-500 mt-1">{{ number_format($stats['draft_properties']) }} awaiting review</p>
    </div>
    <div class="stat-card">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Confirmed Bookings</p>
        <p class="text-3xl font-extrabold text-gray-900">{{ number_format($stats['confirmed_bookings']) }}</p>
        <p class="text-xs text-amber-500 mt-1">{{ number_format($stats['pending_bookings']) }} pending</p>
    </div>
    <div class="stat-card">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Total Revenue</p>
        <p class="text-3xl font-extrabold text-gray-900">${{ number_format($stats['revenue_total'], 0) }}</p>
        <p class="text-xs text-gray-400 mt-1">Confirmed and completed</p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-900">Recent Users</h2>
            <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-brand">View all</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentUsers as $user)
                <a href="{{ route('admin.users.show', $user->id) }}" class="block px-6 py-4 hover:bg-gray-50">
                    <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                    <p class="text-xs text-gray-400">{{ $user->email }} · {{ $user->role->name ?? 'user' }}</p>
                </a>
            @empty
                <p class="px-6 py-10 text-sm text-gray-400 text-center">No users yet.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-900">Recent Properties</h2>
            <a href="{{ route('admin.properties.index') }}" class="text-sm font-semibold text-brand">View all</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentProperties as $property)
                <a href="{{ route('admin.properties.show', $property->id) }}" class="block px-6 py-4 hover:bg-gray-50">
                    <p class="font-semibold text-gray-900">{{ $property->title }}</p>
                    <p class="text-xs text-gray-400">{{ $property->city }}, {{ $property->country }} · {{ $property->status_label }}</p>
                </a>
            @empty
                <p class="px-6 py-10 text-sm text-gray-400 text-center">No properties yet.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-900">Recent Bookings</h2>
            <a href="{{ route('admin.bookings.index') }}" class="text-sm font-semibold text-brand">View all</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentBookings as $booking)
                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="block px-6 py-4 hover:bg-gray-50">
                    <p class="font-semibold text-gray-900">{{ $booking->property->title ?? 'Property removed' }}</p>
                    <p class="text-xs text-gray-400">{{ $booking->user->name ?? 'Guest removed' }} · {{ $booking->status_label }} · ${{ number_format($booking->total_price, 0) }}</p>
                </a>
            @empty
                <p class="px-6 py-10 text-sm text-gray-400 text-center">No bookings yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
