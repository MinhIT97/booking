{{-- Modules/Host/resources/views/properties/index.blade.php --}}
@extends('host::components.layouts.master')

@section('title', 'My Properties')
@section('breadcrumb', 'Properties')
@section('header', 'My Properties')
@section('subheader', 'Manage and update your rental listings.')

@section('header_actions')
<a href="{{ route('host.properties.create') }}" class="btn-primary flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
    </svg>
    New Property
</a>
@endsection

@section('content')

{{-- Search + filter bar --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6 flex flex-col sm:flex-row gap-3">
    <form method="GET" action="{{ route('host.properties.index') }}" class="flex flex-1 gap-3 flex-wrap">
        <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 flex-1 min-w-[200px]">
            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input name="q" type="text" value="{{ request('q') }}"
                placeholder="Search properties…"
                class="flex-1 text-sm bg-transparent focus:outline-none text-gray-700 placeholder-gray-400" />
        </div>

        <select name="type"
            class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-brand text-gray-700 bg-white">
            <option value="">All types</option>
            @foreach (['villa', 'apartment', 'cabin', 'studio', 'house'] as $t)
            <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>
                {{ ucfirst($t) }}
            </option>
            @endforeach
        </select>

        <select name="status"
            class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-brand text-gray-700 bg-white">
            <option value="">All status</option>
            <option value="active" {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="draft" {{ request('status') === 'draft'    ? 'selected' : '' }}>Draft</option>
        </select>

        <button type="submit"
            class="btn-primary">Filter</button>

        @if (request()->hasAny(['q', 'type', 'status']))
        <a href="{{ route('host.properties.index') }}"
            class="btn-outline">Clear</a>
        @endif
    </form>
</div>

{{-- Properties grid --}}
@if ($properties->isEmpty())
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm text-center py-24">
    <div class="text-5xl mb-4">🏡</div>
    <h3 class="text-lg font-bold text-gray-800 mb-2">No properties found</h3>
    <p class="text-gray-400 text-sm mb-6">
        @if (request()->hasAny(['q', 'type', 'status']))
        Try adjusting your filters.
        @else
        Get started by listing your first property.
        @endif
    </p>
    <a href="{{ route('host.properties.create') }}" class="btn-primary inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add Property
    </a>
</div>
@else
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
    @foreach ($properties as $property)
    <article class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow group">

        {{-- Image --}}
        <div class="relative h-48 overflow-hidden bg-gray-100">
            @if ($property->primaryImage)
            <img src="{{ $property->primaryImage->url }}"
                alt="{{ $property->title }}"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            @else
            <div class="w-full h-full flex items-center justify-center text-5xl bg-gray-50">🏠</div>
            @endif

            {{-- Status badge --}}

            <span class="absolute top-3 left-3 flex items-center gap-1.5 bg-white/90 backdrop-blur text-xs font-semibold px-2.5 py-1 rounded-full text-gray-800">
                <span class="w-1.5 h-1.5 rounded-full {{ $property->status_badge }}"></span>
                {{ ucfirst( $property->status_label) }}
            </span>

            ns overlay --}}
            <div class="absolute top-3 right-3 flex gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                <a href="{{ route('host.properties.edit', $property->id) }}"
                    class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-gray-600 hover:text-brand shadow-sm"
                    title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
                <form method="POST"
                    action="{{ route('host.properties.destroy', $property->id) }}"
                    onsubmit="return confirm('Delete this property?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-gray-600 hover:text-red-500 shadow-sm"
                        title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- Details --}}
        <div class="p-4">
            <h3 class="font-bold text-gray-900 text-sm truncate mb-0.5">{{ $property->title }}</h3>
            <p class="text-xs text-gray-400 flex items-center gap-1 mb-3">
                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                </svg>
                {{ $property->city ?? $property->location ?? '—' }}
            </p>
            <div class="flex items-center justify-between border-t border-gray-100 pt-3">
                <div>
                    <span class="text-lg font-extrabold text-gray-900">${{ number_format($property->price_per_night, 0) }}</span>
                    <span class="text-gray-400 text-xs">/night</span>
                </div>
                <div class="flex items-center gap-3 text-xs text-gray-500">
                    <span>★ {{ number_format($property->average_rating ?? 0, 1) }}</span>
                    <span>·</span>
                    <span>{{ $property->bookings_count ?? 0 }} bookings</span>
                </div>
            </div>
        </div>
    </article>
    @endforeach
</div>

{{-- Pagination --}}
@if ($properties->hasPages())
<div class="mt-8 flex justify-center">
    {{ $properties->withQueryString()->links() }}
</div>
@endif
@endif

@endsection