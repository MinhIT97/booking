{{-- Modules/Web/resources/views/properties/show.blade.php --}}
@extends('web::components.layouts.web')

@section('title', $property->title . ' - StayHub')

@section('content')
<main class="pt-24 pb-32">
    <div class="max-w-7xl mx-auto px-8">
        {{-- HEADER --}}
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">{{ $property->title }}</h1>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4 text-sm font-semibold">
                    <div class="flex items-center gap-1">
                        <svg class="w-4 h-4 text-black" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                        <span>{{ number_format($property->average_rating ?? 4.8, 1) }}</span>
                    </div>
                    <span class="underline ml-1 cursor-pointer hover:text-gray-600 transition-colors">{{ $property->reviews_count ?? 124 }} reviews</span>
                    <span class="text-gray-300">|</span>
                    <span class="underline cursor-pointer hover:text-gray-600 transition-colors">{{ $property->city }}, {{ $property->country }}</span>
                </div>
                <div class="flex items-center gap-6">
                    <button class="flex items-center gap-2 text-sm font-semibold underline hover:bg-gray-50 px-3 py-2 rounded-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        Share
                    </button>
                    <button class="flex items-center gap-2 text-sm font-semibold underline hover:bg-gray-50 px-3 py-2 rounded-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        Save
                    </button>
                </div>
            </div>
        </div>

        {{-- GALLERY GRID (Premium) --}}
        <div class="grid grid-cols-4 grid-rows-2 gap-2 h-[500px] rounded-2xl overflow-hidden mb-12">
            @php $pImg = $property->primaryImage ? $property->primaryImage->url : 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200q=80'; @endphp
            <div class="col-span-2 row-span-2 relative group cursor-pointer overflow-hidden">
                <img src="{{ $pImg }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="Main cover">
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-500"></div>
            </div>
            @foreach($property->images->where('is_primary', false)->take(4) as $index => $image)
                <div class="relative group cursor-pointer overflow-hidden">
                    <img src="{{ $image->url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="Gallery">
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-500"></div>
                </div>
            @endforeach
            @if($property->images->count() < 5)
                @for($i = 0; $i < (5 - $property->images->count()); $i++)
                     <div class="bg-gray-100 flex items-center justify-center text-4xl grayscale">🏠</div>
                @endfor
            @endif
        </div>

        {{-- CONTENT LAYOUT --}}
        <div class="flex gap-20">
            {{-- LEFT COLUMN: INFO --}}
            <div class="flex-1">
                <div class="flex items-center justify-between pb-8 border-b border-gray-100 mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-1">Entire room hosted by {{ $property->host->name ?? 'Host' }}</h2>
                        <p class="text-gray-500">{{ $property->max_guests }} guests · {{ $property->bedrooms }} bedrooms · {{ $property->beds }} beds · {{ $property->bathrooms }} bath</p>
                    </div>
                    <div class="w-14 h-14 rounded-full overflow-hidden border border-gray-100">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($property->host->name ?? 'H') }}&background=000&color=fff&size=256" class="w-full h-full object-cover">
                    </div>
                </div>

                {{-- FEATURE HIGHLIGHTS --}}
                <div class="space-y-6 pb-8 border-b border-gray-100 mb-8">
                    <div class="flex gap-4">
                        <div class="mt-1"><svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg></div>
                        <div>
                            <p class="font-bold text-gray-900">Self check-in</p>
                            <p class="text-sm text-gray-500">Check yourself in with the smartlock.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="mt-1"><svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                        <div>
                            <p class="font-bold text-gray-900">Great location</p>
                            <p class="text-sm text-gray-500">95% of recent guests gave the location a 5-star rating.</p>
                        </div>
                    </div>
                </div>

                {{-- DESCRIPTION --}}
                <div class="pb-12 border-b border-gray-100 mb-12">
                    <p class="text-gray-600 leading-loose text-lg whitespace-pre-line">{{ $property->description }}</p>
                    <button class="mt-6 font-bold underline flex items-center gap-1 hover:text-gray-600 transition-colors">
                        Show more
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

                {{-- AMENITIES --}}
                <div class="mb-12">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">What this place offers</h3>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach(['Wifi', 'Kitchen', 'Free parking on premises', 'Pool', 'Air conditioning', 'Washer', 'TV', 'Patio or balcony'] as $ame)
                            <div class="flex items-center gap-4 text-gray-700">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ $ame }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: STICKY CARD --}}
            <div class="w-[380px]">
                <div class="sticky top-28 bg-white border border-gray-100 shadow-[0_12px_40px_rgba(0,0,0,0.1)] rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <span class="text-2xl font-extrabold text-gray-900">${{ number_format($property->price_per_night, 0) }}</span>
                            <span class="text-gray-500 text-sm">night</span>
                        </div>
                        <div class="flex items-center gap-1 text-xs font-bold">
                            <svg class="w-3 h-3 text-black" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                            <span>{{ number_format($property->average_rating ?? 4.8, 1) }}</span>
                            <span class="text-gray-300">·</span>
                            <span class="underline text-gray-500 font-medium">{{ $property->reviews_count ?? 124 }} reviews</span>
                        </div>
                    </div>

                    <form action="{{ route('bookings.create', $property->id) }}" method="GET" class="space-y-4">
                        <div class="border border-gray-300 rounded-xl overflow-hidden">
                            <div class="flex border-b border-gray-300">
                                <div class="flex-1 p-3 border-r border-gray-300">
                                    <label class="block text-[10px] font-black uppercase tracking-wider text-gray-900">Check-in</label>
                                    <input type="text" value="{{ now()->format('m/d/Y') }}" class="w-full text-xs font-medium focus:outline-none bg-transparent" readonly>
                                </div>
                                <div class="flex-1 p-3">
                                    <label class="block text-[10px] font-black uppercase tracking-wider text-gray-900">Checkout</label>
                                    <input type="text" value="{{ now()->addDays(5)->format('m/d/Y') }}" class="w-full text-xs font-medium focus:outline-none bg-transparent" readonly>
                                </div>
                            </div>
                            <div class="p-3">
                                <label class="block text-[10px] font-black uppercase tracking-wider text-gray-900">Guests</label>
                                <select name="guests" class="w-full text-xs font-medium focus:outline-none bg-transparent">
                                    @for($i = 1; $i <= $property->max_guests; $i++)
                                        <option value="{{ $i }}">{{ $i }} guest{{ $i > 1 ? 's' : '' }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-brand text-white font-extrabold py-3.5 rounded-xl hover:bg-brand-dark transition-all shadow-lg active:scale-95 duration-200">
                            Reserve
                        </button>
                    </form>

                    <p class="text-center text-gray-500 text-xs mt-4">You won't be charged yet</p>

                    {{-- FEE BREAKDOWN (Fake/Static for now) --}}
                    <div class="mt-6 space-y-3">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span class="underline">${{ number_format($property->price_per_night, 0) }} x 5 nights</span>
                            <span>${{ number_format($property->price_per_night * 5, 0) }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span class="underline">Cleaning fee</span>
                            <span>$40</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span class="underline">Service fee</span>
                            <span>$85</span>
                        </div>
                        <div class="pt-4 border-t border-gray-100 flex justify-between font-extrabold text-gray-900">
                            <span>Total</span>
                            <span>${{ number_format($property->price_per_night * 5 + 125, 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
