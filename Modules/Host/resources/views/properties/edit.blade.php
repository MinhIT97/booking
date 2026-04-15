{{-- Modules/Host/resources/views/properties/edit.blade.php --}}
@extends('host::components.layouts.master')

@section('title', 'Edit: ' . $property->title)
@section('breadcrumb', 'Edit Property')
@section('header', 'Edit Property')
@section('subheader', $property->title)

@section('content')

<form method="POST"
      action="{{ route('host.properties.update', $property->id) }}"
      enctype="multipart/form-data"
      class="space-y-6 max-w-3xl">

    @csrf
    @method('PUT')

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
                       value="{{ old('title', $property->title) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 @error('title') border-red-400 @enderror" />
                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="property_type_id" class="block text-sm font-semibold text-gray-700 mb-1.5">Property Type</label>
                    <select id="property_type_id" name="property_type_id"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand @error('property_type_id') border-red-400 @enderror">
                        @foreach ($propertyTypes as $type)
                            <option value="{{ $type->id }}" {{ old('property_type_id', $property->property_type_id) === $type->id ? 'selected' : '' }}>
                                {{ $type->icon }} {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('property_type_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-1.5">Status</label>
                    <select id="status" name="status"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand">
                        @foreach (['active' => 'Active', 'draft' => 'Draft', 'rejected' => 'Rejected'] as $val => $label)
                            <option value="{{ $val }}" {{ old('status', $property->status_key) === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="price_per_night" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Price per Night (USD) <span class="text-brand">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-bold">$</span>
                    <input id="price_per_night" name="price_per_night" type="number" min="1"
                           value="{{ old('price_per_night', $property->price_per_night) }}"
                           class="w-full border border-gray-200 rounded-xl pl-8 pr-4 py-3 text-sm focus:outline-none focus:border-brand @error('price_per_night') border-red-400 @enderror" />
                </div>
                @error('price_per_night')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
                <textarea id="description" name="description" rows="5"
                          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand resize-none @error('description') border-red-400 @enderror">{{ old('description', $property->description) }}</textarea>
                @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
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
                <input id="city" name="city" type="text" value="{{ old('city', $property->city) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand" />
            </div>
            <div>
                <label for="country" class="block text-sm font-semibold text-gray-700 mb-1.5">Country</label>
                <input id="country" name="country" type="text" value="{{ old('country', $property->country) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand" />
            </div>
            <div class="sm:col-span-2">
                <label for="address" class="block text-sm font-semibold text-gray-700 mb-1.5">Full Address</label>
                <input id="address" name="address" type="text" value="{{ old('address', $property->address) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand" />
            </div>
        </div>
    </div>

    {{-- ── EXISTING PHOTOS ── --}}
    @if ($property->images->isNotEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-brand text-white text-xs font-bold flex items-center justify-center">3</span>
                Current Photos
            </h2>
            <div class="grid grid-cols-3 sm:grid-cols-5 gap-2 mb-4">
                @foreach ($property->images as $image)
                    <div class="relative group">
                        <img src="{{ $image->url }}"
                             alt="Property image"
                             class="w-full h-20 object-cover rounded-xl" />
                        @if ($image->is_primary)
                            <span class="absolute top-1 left-1 bg-brand text-white text-xs px-1.5 py-0.5 rounded-full">Primary</span>
                        @endif
                    </div>
                @endforeach
            </div>
            <label for="images" class="flex flex-col items-center border-2 border-dashed border-gray-300 rounded-2xl p-4 cursor-pointer hover:border-brand hover:bg-rose-50 transition-colors">
                <p class="text-sm text-gray-500">Upload additional photos</p>
                <input id="images" name="images[]" type="file" multiple accept="image/*" class="hidden" />
            </label>
        </div>
    @endif

    {{-- ── SUBMIT ── --}}
    <div class="flex items-center justify-between gap-4">
        <a href="{{ route('host.properties.show', $property->id) }}" class="btn-outline">← Cancel</a>
        <button type="submit" class="btn-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Save Changes
        </button>
    </div>

</form>

@endsection
