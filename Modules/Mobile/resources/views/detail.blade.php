@extends('mobile::components.layouts.mobile')

@section('title', $property->title)

@section('header')
    <a href="{{ route('mobile.home') }}" class="w-8 h-8 flex items-center justify-center bg-white border border-gray-200 rounded-full text-black">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div class="flex items-center gap-4">
        <button class="w-8 h-8 flex items-center justify-center text-black"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg></button>
        <button class="w-8 h-8 flex items-center justify-center text-black"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg></button>
    </div>
@endsection

@section('content')
    {{-- Gallery --}}
    <div class="relative overflow-x-auto flex snap-x snap-mandatory hide-scrollbar">
        @if($property->images->isNotEmpty())
            @foreach($property->images as $image)
                <div class="w-full h-[30vh] flex-shrink-0 snap-center">
                    <img src="{{ $image->url }}" class="w-full h-full object-cover" alt="{{ $property->title }}">
                </div>
            @endforeach
        @else
            <div class="w-full h-[30vh] flex-shrink-0 snap-center bg-gray-100 flex items-center justify-center text-6xl">🏢</div>
        @endif
        
        {{-- Indicator --}}
        <div class="absolute bottom-4 right-4 bg-black/60 text-white text-[10px] font-bold px-2 py-1 rounded-md">
            1 / {{ max(1, $property->images->count()) }}
        </div>
    </div>

    {{-- Content --}}
    <div class="px-6 py-6 border-b border-gray-100">
        <h1 class="text-2xl font-bold mb-1 leading-tight">{{ $property->title }}</h1>
        <div class="flex items-center gap-1 mb-4 text-[14px] font-semibold">
            <svg class="w-3 h-3 text-black" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
            <span>{{ number_format($property->average_rating ?? 4.8, 1) }}</span>
            <span class="underline ml-1">· {{ $property->reviews_count ?? 124 }} reviews</span>
            <span class="ml-1 text-gray-400">·</span>
            <span class="ml-1">{{ $property->city }}, {{ $property->country }}</span>
        </div>

        <div class="py-6 border-t border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-[17px] font-bold">Entire room hosted by {{ $property->host->name ?? 'Host' }}</h2>
                    <p class="text-gray-500 text-[14px]">{{ $property->max_guests }} guests · {{ $property->bedrooms }} bedrooms · {{ $property->beds }} beds · {{ $property->bathrooms }} bath</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-gray-100 overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($property->host->name ?? 'H') }}&background=random" class="w-full h-full object-cover">
                </div>
            </div>
        </div>

        {{-- Amenities Preview --}}
        <div class="py-6 border-t border-gray-100 space-y-5">
            <div class="flex items-start gap-4">
                <svg class="w-5 h-5 mt-0.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <div>
                    <p class="text-[15px] font-bold">Self check-in</p>
                    <p class="text-gray-500 text-[13px]">Check yourself in with the smartlock.</p>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <svg class="w-5 h-5 mt-0.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <div>
                    <p class="text-[15px] font-bold">Great location</p>
                    <p class="text-gray-500 text-[13px]">95% of recent guests gave the location a 5-star rating.</p>
                </div>
            </div>
        </div>

        <div class="py-6 border-t border-gray-100">
            <h3 class="text-[17px] font-bold mb-4">About this space</h3>
            <p class="text-gray-600 text-[15px] leading-relaxed line-clamp-4">{{ $property->description }}</p>
            <button class="text-black font-bold underline mt-4 text-[14px]">Show more</button>
        </div>
    </div>

    {{-- Bottom Action Bar --}}
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 z-50 p-4 pb-safe flex items-center justify-between shadow-[0_-4px_16px_rgba(0,0,0,0.05)]">
        <div>
            <p class="text-[17px]"><span class="font-bold">${{ number_format($property->price_per_night, 0) }}</span> <span class="text-gray-600 text-[14px]">night</span></p>
            <p class="text-[12px] font-bold underline">Dec 15 - 20</p>
        </div>
        <a href="{{ route('mobile.booking', $property->id) }}" class="bg-brand text-white px-8 py-3.5 rounded-xl font-bold active:scale-95 transition-transform">
            Reserve
        </a>
    </div>
@endsection
