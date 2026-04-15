{{-- Modules/Host/resources/views/properties/create.blade.php --}}
@extends('host::components.layouts.master')

@section('title', 'Add Property')
@section('breadcrumb', 'Add Property')
@section('header', 'List a New Property')
@section('subheader', 'Fill in the details below to publish your listing.')

@section('content')

<form method="POST"
    action="{{ route('host.properties.store') }}"
    enctype="multipart/form-data"
    class="space-y-6 max-w-3xl">

    @csrf

    {{-- ── BASIC INFO ── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="font-bold text-gray-900 mb-5 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-brand text-white text-xs font-bold flex items-center justify-center">1</span>
            Basic Information
        </h2>
        <div class="space-y-4">

            <div>
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Property Title <span class="text-brand">*</span>
                </label>
                <input id="title" name="title" type="text"
                    value="{{ old('title') }}"
                    placeholder="e.g. Cozy Beachfront Villa in Seminyak"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 @error('title') border-red-400 @enderror" />
                @error('title')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="type" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Property Type <span class="text-brand">*</span>
                    </label>
                    <select id="type" name="type"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 @error('type') border-red-400 @enderror">
                        <option value="">Select type…</option>
                        @foreach (['villa', 'apartment', 'cabin', 'studio', 'house'] as $t)
                        <option value="{{ $t }}" {{ old('type') === $t ? 'selected' : '' }}>
                            {{ ucfirst($t) }}
                        </option>
                        @endforeach
                    </select>
                    @error('type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price_per_night" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Price per Night (USD) <span class="text-brand">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-bold">$</span>
                        <input id="price_per_night" name="price_per_night" type="number" min="1" step="1"
                            value="{{ old('price_per_night') }}"
                            placeholder="0"
                            class="w-full border border-gray-200 rounded-xl pl-8 pr-4 py-3 text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 @error('price_per_night') border-red-400 @enderror" />
                    </div>
                    @error('price_per_night')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Description <span class="text-brand">*</span>
                </label>
                <textarea id="description" name="description" rows="5"
                    placeholder="Describe your property — highlight what makes it unique…"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 resize-none @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- ── LOCATION ── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="font-bold text-gray-900 mb-5 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-brand text-white text-xs font-bold flex items-center justify-center">2</span>
            Location
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="city" class="block text-sm font-semibold text-gray-700 mb-1.5">City</label>
                <input id="city" name="city" type="text" value="{{ old('city') }}"
                    placeholder="e.g. Seminyak"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand @error('city') border-red-400 @enderror" />
                @error('city')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="country" class="block text-sm font-semibold text-gray-700 mb-1.5">Country</label>
                <input id="country" name="country" type="text" value="{{ old('country') }}"
                    placeholder="e.g. Indonesia"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand @error('country') border-red-400 @enderror" />
                @error('country')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label for="address" class="block text-sm font-semibold text-gray-700 mb-1.5">Full Address</label>
                <input id="address" name="address" type="text" value="{{ old('address') }}"
                    placeholder="Street address, landmark…"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand @error('address') border-red-400 @enderror" />
                @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    {{-- ── DETAILS & AMENITIES ── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="font-bold text-gray-900 mb-5 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-brand text-white text-xs font-bold flex items-center justify-center">3</span>
            Details & Amenities
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            @foreach (['max_guests' => 'Max Guests', 'bedrooms' => 'Bedrooms', 'bathrooms' => 'Bathrooms', 'beds' => 'Beds'] as $field => $label)
            <div>
                <label for="{{ $field }}" class="block text-sm font-semibold text-gray-700 mb-1.5">{{ $label }}</label>
                <input id="{{ $field }}" name="{{ $field }}" type="number" min="1"
                    value="{{ old($field, 1) }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand @error($field) border-red-400 @enderror" />
                @error($field)<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            @endforeach
        </div>

        <label class="block text-sm font-semibold text-gray-700 mb-3">Amenities</label>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
            @foreach (['pool', 'wifi', 'parking', 'kitchen', 'air_conditioning', 'hot_tub', 'gym', 'beach_access', 'pet_friendly'] as $amenity)
            <label class="flex items-center gap-2.5 p-3 border border-gray-200 rounded-xl cursor-pointer hover:border-brand hover:bg-rose-50 transition-colors has-[:checked]:border-brand has-[:checked]:bg-rose-50">
                <input type="checkbox" name="amenities[]" value="{{ $amenity }}"
                    {{ in_array($amenity, old('amenities', [])) ? 'checked' : '' }}
                    class="w-4 h-4 accent-brand rounded" />
                <span class="text-sm text-gray-700">{{ ucwords(str_replace('_', ' ', $amenity)) }}</span>
            </label>
            @endforeach
        </div>
    </div>

    {{-- ── PHOTOS ── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="font-bold text-gray-900 mb-5 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-brand text-white text-xs font-bold flex items-center justify-center">4</span>
            Photos
        </h2>
        <label for="images"
            class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-2xl h-36 cursor-pointer hover:border-brand hover:bg-rose-50 transition-colors">
            <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="text-sm text-gray-500">Click to upload photos</p>
            <p class="text-xs text-gray-400 mt-1">PNG, JPG, WEBP up to 5MB each</p>
            <input id="images" name="images[]" type="file" multiple accept="image/*" class="hidden" />
        </label>
        @error('images.*')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
    </div>

    {{-- ── SUBMIT ── --}}
    <div class="flex items-center justify-between gap-4 pt-2">
        <a href="{{ route('host.properties.index') }}" class="btn-outline">← Cancel</a>
        <div class="flex gap-3">
            <button type="submit" name="status" value="1"
                class="btn-outline">Save as Draft</button>
            <button type="submit" name="status" value="2"
                class="btn-primary flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Publish Listing
            </button>
        </div>
    </div>

</form>

@endsection