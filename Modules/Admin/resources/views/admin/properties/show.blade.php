@extends('admin::layouts.master')

@section('title', 'Property Detail')
@section('header', $property->title)
@section('subheader', ($property->city ?? '') . ', ' . ($property->country ?? ''))

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            @if($property->images->isNotEmpty())
                <div class="grid grid-cols-2 gap-1 h-72">
                    <img src="{{ $property->primaryImage->url ?? $property->images->first()->url }}" alt="{{ $property->title }}" class="w-full h-full object-cover row-span-2">
                    @foreach($property->images->where('is_primary', false)->take(2) as $image)
                        <img src="{{ $image->url }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                    @endforeach
                </div>
            @endif
            <div class="p-6">
                <div class="flex justify-between gap-4 mb-4">
                    <div>
                        <h2 class="font-bold text-gray-900 text-xl">{{ $property->title }}</h2>
                        <p class="text-sm text-gray-400">{{ $property->address }}</p>
                    </div>
                    <span class="text-sm font-semibold text-gray-600">{{ $property->status_label }}</span>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $property->description }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100"><h2 class="font-bold text-gray-900">Bookings</h2></div>
            <div class="divide-y divide-gray-50">
                @forelse($property->bookings->take(10) as $booking)
                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="block px-6 py-4 hover:bg-gray-50">
                        <p class="font-semibold text-gray-900">{{ $booking->user->name ?? 'Guest removed' }}</p>
                        <p class="text-xs text-gray-400">{{ $booking->check_in_date->format('M d') }} - {{ $booking->check_out_date->format('M d, Y') }} · {{ $booking->status_label }}</p>
                    </a>
                @empty
                    <p class="px-6 py-10 text-sm text-gray-400 text-center">No bookings yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-bold text-gray-900 mb-4">Details</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between gap-4"><dt class="text-gray-500">Host</dt><dd class="font-semibold text-gray-900">{{ $property->host->name ?? 'Unknown' }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-gray-500">Price</dt><dd class="font-semibold text-gray-900">${{ number_format($property->price_per_night, 0) }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-gray-500">Guests</dt><dd class="font-semibold text-gray-900">{{ $property->max_guests }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-gray-500">Bookings</dt><dd class="font-semibold text-gray-900">{{ $property->bookings_count ?? $property->bookings->count() }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-gray-500">Rating</dt><dd class="font-semibold text-gray-900">{{ number_format($property->average_rating ?? 0, 1) }}</dd></div>
            </dl>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-bold text-gray-900 mb-4">Actions</h3>
            <div class="space-y-2">
                @if($property->status_key !== 'active')
                    <form method="POST" action="{{ route('admin.properties.approve', $property->id) }}">@csrf
                        <button class="btn-primary w-full justify-center">Approve</button>
                    </form>
                @endif
                @if($property->status_key !== 'rejected')
                    <form method="POST" action="{{ route('admin.properties.reject', $property->id) }}">@csrf
                        <button class="btn-outline w-full justify-center text-red-600 border-red-100">Reject</button>
                    </form>
                @endif
                <form method="POST" action="{{ route('admin.properties.destroy', $property->id) }}" onsubmit="return confirm('Delete this property?')">
                    @csrf @method('DELETE')
                    <button class="btn-outline w-full justify-center text-red-600 border-red-100">Delete Property</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
