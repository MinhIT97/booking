@extends('mobile::components.layouts.mobile')

@section('title', 'Explore Rooms')

@section('header')
    <div class="flex-1 pr-4">
        <div class="bg-white rounded-full shadow-lg border border-gray-100 px-4 py-2 flex items-center gap-3">
            <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <div class="flex-1">
                <p class="text-xs font-bold leading-tight">Where to?</p>
                <p class="text-[10px] text-gray-500 leading-tight">Anywhere • Any week • Guests</p>
            </div>
        </div>
    </div>
    <div class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center">
        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
        </svg>
    </div>
@endsection

@section('content')
    {{-- Categories --}}
    <div class="sticky top-14 z-40 bg-white/80 backdrop-blur-md border-b border-gray-50 overflow-x-auto hide-scrollbar flex items-center gap-8 px-6 py-3">
        @foreach(['Amazing pools', 'Cabins', 'Beachfront', 'Luxe', 'Trending', 'Countryside'] as $cat)
            <div class="flex flex-col items-center gap-1.5 flex-shrink-0 @if($loop->first) border-b-2 border-black pb-1 @endif">
                <div class="w-6 h-6 grayscale group-hover:grayscale-0">
                    {{-- Using emojis as icons for light weight --}}
                    <span>{{ ['🏊', '🏕️', '🏖️', '💎', '🔥', '🌄'][$loop->index] }}</span>
                </div>
                <span class="text-[11px] font-medium {{ $loop->first ? 'text-black' : 'text-gray-500' }}">{{ $cat }}</span>
            </div>
        @endforeach
    </div>

    {{-- Property List --}}
    <div class="px-6 py-6 space-y-10">
        @forelse($properties as $property)
            <a href="{{ route('mobile.detail', $property->id) }}" class="block group">
                <div class="relative aspect-square rounded-2xl overflow-hidden bg-gray-100 mb-4 shadow-sm">
                    @if($property->primaryImage)
                        <img src="{{ $property->primaryImage->url }}" class="w-full h-full object-cover group-active:scale-95 transition-transform duration-300" alt="{{ $property->title }}">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-5xl">🏠</div>
                    @endif
                    <button class="absolute top-4 right-4 text-white drop-shadow-md">
                        <svg class="w-6 h-6" fill="rgba(0,0,0,0.3)" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </button>
                    <div class="absolute bottom-4 left-4 right-4 flex justify-center gap-1.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                        <div class="w-1.5 h-1.5 rounded-full bg-white/60"></div>
                        <div class="w-1.5 h-1.5 rounded-full bg-white/60"></div>
                    </div>
                </div>
                
                <div class="flex justify-between items-start">
                    <div class="min-w-0 pr-4">
                        <h3 class="font-bold text-[15px] truncate">{{ $property->city }}, {{ $property->country ?? 'Vietnam' }}</h3>
                        <p class="text-gray-500 text-[14px]">Stay with {{ $property->host->name ?? 'Host' }}</p>
                        <p class="text-gray-500 text-[14px] mb-2">Dec 15 - 20</p>
                        <p class="text-[15px]"><span class="font-bold">${{ number_format($property->price_per_night, 0) }}</span> <span class="text-gray-600 font-normal">night</span></p>
                    </div>
                    <div class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-black" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                        <span class="text-[14px] font-medium">{{ number_format($property->average_rating ?? 4.8, 1) }}</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="py-20 text-center">
                <p class="text-gray-500">No rooms found. Try another category!</p>
            </div>
        @endforelse
    </div>

    {{-- Floating Map Button (App Style) --}}
    <div class="fixed bottom-24 left-1/2 -translate-x-1/2 z-40">
        <button class="bg-black text-white px-5 py-3 rounded-full flex items-center gap-2 shadow-2xl active:scale-95 transition-all">
            <span class="text-[13px] font-bold">Map</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m6 13l5.447-2.724A1 1 0 0021 16.382V5.618a1 1 0 00-1.447-.894L15 7m-6 0h6m-6 0l-1.447 2.894L9 13m6-6l1.447 2.894L15 13m-6 0h6"/></svg>
        </button>
    </div>
@endsection
