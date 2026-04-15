{{-- Modules/Host/resources/views/properties/show.blade.php --}}
@extends('host::components.layouts.master')

@section('title', $property->title)
@section('breadcrumb', $property->title)
@section('header', $property->title)
@section('subheader', ($property->city ?? '') . ' · ' . ucfirst($property->type ?? ''))

@section('header_actions')
    <a href="{{ route('host.properties.edit', $property->id) }}" class="btn-outline flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Edit
    </a>
    <form method="POST" action="{{ route('host.properties.destroy', $property->id) }}"
          onsubmit="return confirm('Permanently delete this property?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn-outline text-red-500 border-red-200 hover:border-red-400 hover:text-red-600 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Delete
        </button>
    </form>
@endsection

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- LEFT: details ────────────────────────────────────────── --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Image gallery --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            @if ($property->images->isNotEmpty())
                <div class="grid grid-cols-2 gap-1 h-64">
                    <img src="{{ $property->images->firstWhere('is_primary', true)?->url ?? $property->images->first()->url }}"
                         class="col-span-1 w-full h-full object-cover row-span-2" alt="{{ $property->title }}" />
                    @foreach ($property->images->where('is_primary', false)->take(2) as $img)
                        <img src="{{ $img->url }}" class="w-full h-full object-cover" alt="image" />
                    @endforeach
                </div>
            @else
                <div class="h-48 flex items-center justify-center bg-gray-50 text-4xl">🏠</div>
            @endif
        </div>

        {{-- Property details --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-bold text-gray-900 mb-4">Property Details</h2>
            <dl class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                @foreach ([
                    'Type'        => ucfirst($property->type ?? '—'),
                    'Status'      => $property->status_label,
                    'Max Guests'  => $property->max_guests ?? '—',
                    'Bedrooms'    => $property->bedrooms ?? '—',
                    'Bathrooms'   => $property->bathrooms ?? '—',
                    'Beds'        => $property->beds ?? '—',
                    'City'        => $property->city ?? '—',
                    'Country'     => $property->country ?? '—',
                ] as $label => $value)
                    <div class="bg-gray-50 rounded-xl p-3">
                        <dt class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">{{ $label }}</dt>
                        <dd class="font-semibold text-gray-900">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>

            @if ($property->description)
                <div class="mt-5 pt-5 border-t border-gray-100">
                    <h3 class="font-semibold text-gray-900 mb-2 text-sm">Description</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $property->description }}</p>
                </div>
            @endif
        </div>

        {{-- Bookings for this property --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-900">Bookings</h2>
                <span class="text-xs text-gray-400">{{ $property->bookings->count() }} total</span>
            </div>

            @if ($property->bookings->isEmpty())
                <div class="text-center py-12 text-gray-400">
                    <div class="text-3xl mb-2">📅</div>
                    <p class="text-sm">No bookings yet for this property.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                            <tr>
                                <th class="px-6 py-3 text-left">Guest</th>
                                <th class="px-6 py-3 text-left">Check-in</th>
                                <th class="px-6 py-3 text-left">Check-out</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($property->bookings as $booking)
                                @php
                                    $color = match ($booking->status) {
                                        \Modules\Booking\Enums\BookingStatus::Confirmed => 'bg-green-100 text-green-700',
                                        \Modules\Booking\Enums\BookingStatus::Pending => 'bg-amber-100 text-amber-700',
                                        \Modules\Booking\Enums\BookingStatus::Cancelled => 'bg-red-100 text-red-600',
                                        \Modules\Booking\Enums\BookingStatus::Completed => 'bg-blue-100 text-blue-700',
                                        default     => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-3 font-medium">{{ $booking->user->name ?? '—' }}</td>
                                    <td class="px-6 py-3 text-gray-500">
                                        {{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-3 text-gray-500">
                                        {{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-3">
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                            {{ $booking->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-right font-semibold">
                                        ${{ number_format($booking->total_price, 0) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- RIGHT: sidebar stats ─────────────────────────────────── --}}
    <div class="space-y-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-bold text-gray-900 mb-4 text-sm">Quick Stats</h3>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Price / night</span>
                    <span class="font-bold text-gray-900">${{ number_format($property->price_per_night, 0) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Avg. rating</span>
                    <span class="font-bold text-amber-500">★ {{ number_format($property->average_rating ?? 0, 1) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Total bookings</span>
                    <span class="font-bold text-gray-900">{{ $property->bookings->count() }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Revenue (all)</span>
                    <span class="font-bold text-green-600">
                        ${{ number_format($property->bookings->sum('total_price'), 0) }}
                    </span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Listed since</span>
                    <span class="font-medium text-gray-700">
                        {{ \Carbon\Carbon::parse($property->created_at)->format('M Y') }}
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-bold text-gray-900 mb-4 text-sm">Quick Actions</h3>
            <div class="space-y-2">
                <a href="{{ route('host.properties.edit', $property->id) }}"
                   class="flex items-center gap-2 w-full text-sm font-medium text-gray-700 border border-gray-200 px-4 py-2.5 rounded-xl hover:border-brand hover:text-brand transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit details
                </a>
                <a href="/properties.html" target="_blank"
                   class="flex items-center gap-2 w-full text-sm font-medium text-gray-700 border border-gray-200 px-4 py-2.5 rounded-xl hover:border-brand hover:text-brand transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    View on site
                </a>
            </div>
        </div>
    </div>

</div>

@endsection
