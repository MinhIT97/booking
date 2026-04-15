@extends('admin::layouts.master')

@section('title', 'Booking Detail')
@section('header', 'Booking Detail')
@section('subheader', $booking->property->title ?? 'Property removed')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between gap-4">
            <h2 class="font-bold text-gray-900">Reservation</h2>
            <span class="text-sm font-semibold text-gray-500">{{ $booking->status_label }}</span>
        </div>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-6 text-sm">
            <div><dt class="text-gray-400 mb-1">Guest</dt><dd class="font-semibold text-gray-900">{{ $booking->user->name ?? 'Guest removed' }}</dd></div>
            <div><dt class="text-gray-400 mb-1">Email</dt><dd class="font-semibold text-gray-900">{{ $booking->user->email ?? 'N/A' }}</dd></div>
            <div><dt class="text-gray-400 mb-1">Check-in</dt><dd class="font-semibold text-gray-900">{{ $booking->check_in_date->format('M d, Y') }}</dd></div>
            <div><dt class="text-gray-400 mb-1">Check-out</dt><dd class="font-semibold text-gray-900">{{ $booking->check_out_date->format('M d, Y') }}</dd></div>
            <div><dt class="text-gray-400 mb-1">Guests</dt><dd class="font-semibold text-gray-900">{{ $booking->guests }}</dd></div>
            <div><dt class="text-gray-400 mb-1">Total</dt><dd class="font-semibold text-gray-900">${{ number_format($booking->total_price, 2) }}</dd></div>
        </dl>
    </div>

    <div class="space-y-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-bold text-gray-900 mb-4">Actions</h3>
            <div class="space-y-2">
                <form method="POST" action="{{ route('admin.bookings.update-status', $booking->id) }}">
                    @csrf @method('PATCH')
                    <select name="status" class="w-full mb-3 border border-gray-200 rounded-xl px-3 py-2 text-sm">
                        @foreach(['pending' => 'Pending', 'confirmed' => 'Confirmed', 'cancelled' => 'Cancelled', 'completed' => 'Completed'] as $value => $label)
                            <option value="{{ $value }}" {{ $booking->status_key === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button class="btn-primary w-full justify-center">Update Status</button>
                </form>
                <form method="POST" action="{{ route('admin.bookings.destroy', $booking->id) }}" onsubmit="return confirm('Delete this booking?')">
                    @csrf @method('DELETE')
                    <button class="btn-outline w-full justify-center text-red-600 border-red-100">Delete Booking</button>
                </form>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-bold text-gray-900 mb-2">Property</h3>
            <p class="text-sm text-gray-600">{{ $booking->property->title ?? 'Property removed' }}</p>
            @if($booking->property)
                <a href="{{ route('admin.properties.show', $booking->property->id) }}" class="text-sm font-semibold text-brand mt-3 inline-block">View property</a>
            @endif
        </div>
    </div>
</div>
@endsection
