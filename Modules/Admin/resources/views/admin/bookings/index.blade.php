@extends('admin::layouts.master')

@section('title', 'Booking Management')
@section('header', 'Booking Management')
@section('subheader', 'Review reservations and update booking states.')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="stat-card">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Total Bookings</p>
        <p class="text-3xl font-extrabold text-gray-900">{{ $bookings->total() }}</p>
    </div>
    <div class="stat-card">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Pending On Page</p>
        <p class="text-3xl font-extrabold text-amber-500">{{ $bookings->where('status_key', 'pending')->count() }}</p>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
    <form action="{{ route('admin.bookings.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search guest, email, property, city..."
                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-brand focus:border-brand">
        </div>
        <select name="status" onchange="this.form.submit()" class="w-full py-2.5 px-4 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-brand focus:border-brand">
            <option value="">All Statuses</option>
            @foreach(['pending' => 'Pending', 'confirmed' => 'Confirmed', 'cancelled' => 'Cancelled', 'completed' => 'Completed'] as $value => $label)
                <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <div class="flex gap-2">
            <button type="submit" class="btn-primary flex-1 justify-center">Filter</button>
            <a href="{{ route('admin.bookings.index') }}" class="btn-outline px-3 border-gray-100 flex items-center justify-center">Clear</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                <tr>
                    <th class="px-6 py-4 text-left">Guest</th>
                    <th class="px-6 py-4 text-left">Property</th>
                    <th class="px-6 py-4 text-left">Dates</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-right">Total</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-900">{{ $booking->user->name ?? 'Guest removed' }}</p>
                            <p class="text-xs text-gray-400">{{ $booking->user->email ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900">{{ $booking->property->title ?? 'Property removed' }}</p>
                            <p class="text-xs text-gray-400">{{ $booking->property->city ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $booking->check_in_date->format('M d') }} - {{ $booking->check_out_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'confirmed' => 'bg-green-100 text-green-700',
                                    'cancelled' => 'bg-red-100 text-red-600',
                                    'completed' => 'bg-blue-100 text-blue-700',
                                ];
                            @endphp
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColors[$booking->status_key] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $booking->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-gray-900">${{ number_format($booking->total_price, 0) }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn-outline px-3 py-1.5 text-xs">View</a>
                                @if($booking->status_key === 'pending')
                                    <form method="POST" action="{{ route('admin.bookings.confirm', $booking->id) }}">@csrf
                                        <button class="btn-outline px-3 py-1.5 text-xs text-green-600 border-green-100">Confirm</button>
                                    </form>
                                @endif
                                @if(!in_array($booking->status_key, ['cancelled', 'completed'], true))
                                    <form method="POST" action="{{ route('admin.bookings.cancel', $booking->id) }}" onsubmit="return confirm('Cancel this booking?')">@csrf
                                        <button class="btn-outline px-3 py-1.5 text-xs text-red-600 border-red-100">Cancel</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-20 text-center text-gray-400">No bookings found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bookings->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">{{ $bookings->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
