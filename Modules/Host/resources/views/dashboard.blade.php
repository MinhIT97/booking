{{-- Modules/Host/resources/views/dashboard.blade.php --}}
@extends('host::components.layouts.master')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')
@section('header', 'Welcome back, ' . (auth()->user()->name ?? 'Host') . '! 👋')
@section('subheader', 'Here\'s an overview of your hosting activity.')

@section('header_actions')
    <a href="{{ route('host.properties.create') }}" class="btn-primary flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Property
    </a>
@endsection

@section('content')

{{-- ── STAT CARDS ──────────────────────────────────────────── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

    {{-- Total Properties --}}
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Properties</p>
            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-gray-900">{{ $stats['total_properties'] ?? 0 }}</p>
        <p class="text-xs text-green-500 font-medium mt-1">
            ↑ {{ $stats['new_properties_this_month'] ?? 0 }} this month
        </p>
    </div>

    {{-- Active Bookings --}}
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Active Bookings</p>
            <div class="w-9 h-9 rounded-xl bg-rose-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-gray-900">{{ $stats['active_bookings'] ?? 0 }}</p>
        <p class="text-xs text-gray-400 font-medium mt-1">
            {{ $stats['pending_bookings'] ?? 0 }} pending approval
        </p>
    </div>

    {{-- Monthly Revenue --}}
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Revenue (Month)</p>
            <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-gray-900">
            ${{ number_format($stats['monthly_revenue'] ?? 0, 0) }}
        </p>
        <p class="text-xs text-green-500 font-medium mt-1">
            ↑ {{ $stats['revenue_growth'] ?? 0 }}% vs last month
        </p>
    </div>

    {{-- Average Rating --}}
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Avg. Rating</p>
            <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-gray-900">
            {{ number_format($stats['average_rating'] ?? 0, 1) }}
        </p>
        <p class="text-xs text-gray-400 font-medium mt-1">
            From {{ $stats['total_reviews'] ?? 0 }} reviews
        </p>
    </div>

</div>

{{-- ── RECENT BOOKINGS + QUICK PROPERTIES ─────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Recent Bookings table --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-900">Recent Bookings</h2>
            <a href="{{ route('host.bookings.index') }}"
               class="text-xs font-semibold text-brand hover:underline">View all →</a>
        </div>

        @if ($recentBookings->isEmpty())
            <div class="text-center py-16 text-gray-400">
                <div class="text-4xl mb-3">📅</div>
                <p class="text-sm">No bookings yet.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        <tr>
                            <th class="px-6 py-3 text-left">Guest</th>
                            <th class="px-6 py-3 text-left">Property</th>
                            <th class="px-6 py-3 text-left">Check-in</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($recentBookings as $booking)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $booking->user->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-gray-500 max-w-[140px] truncate">
                                    {{ $booking->property->title ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'confirmed' => 'bg-green-100 text-green-700',
                                            'pending'   => 'bg-amber-100 text-amber-700',
                                            'cancelled' => 'bg-red-100 text-red-600',
                                            'completed' => 'bg-blue-100 text-blue-700',
                                        ];
                                        $color = $statusColors[$booking->status_key] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                        {{ $booking->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">
                                    ${{ number_format($booking->total_price, 0) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Top properties --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-900">Top Properties</h2>
            <a href="{{ route('host.properties.index') }}"
               class="text-xs font-semibold text-brand hover:underline">All →</a>
        </div>

        <div class="p-4 space-y-3">
            @forelse ($topProperties as $property)
                <a href="{{ route('host.properties.show', $property->id) }}" 
                   class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-xl transition-colors cursor-pointer block">
                    <div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                        @if ($property->primaryImage)
                            <img src="{{ $property->primaryImage->url }}"
                                 alt="{{ $property->title }}"
                                 class="w-full h-full object-cover" />
                        @else
                            <div class="w-full h-full flex items-center justify-center text-xl">🏠</div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900 text-sm truncate">{{ $property->title }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $property->city ?? $property->location }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-sm font-bold text-gray-900">${{ number_format($property->price_per_night, 0) }}</p>
                        <p class="text-xs text-amber-500">★ {{ number_format($property->average_rating ?? 0, 1) }}</p>
                    </div>
                </a>
            @empty
                <div class="text-center py-10 text-gray-400">
                    <p class="text-sm">No properties yet.</p>
                    <a href="{{ route('host.properties.create') }}"
                       class="text-brand text-sm font-semibold hover:underline mt-1 inline-block">Add one →</a>
                </div>
            @endforelse
        </div>
    </div>

</div>

@endsection
