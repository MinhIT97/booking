@extends('web::components.layouts.web')

@section('title', 'StayNest — Find Your Perfect Stay')

@section('content')
    <!-- NAV -->
    <nav class="fixed top-0 inset-x-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 h-20 flex items-center px-8 transition-all duration-300">
        <div class="max-w-7xl mx-auto w-full flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center gap-2 group">
                <div class="p-2 bg-brand rounded-xl group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                </div>
                <span class="text-xl font-bold tracking-tight">Stay<span class="text-brand">Nest</span></span>
            </a>
            
            <div class="hidden lg:flex items-center gap-10 text-sm font-semibold text-gray-500">
                <a href="#featured" class="hover:text-brand transition-colors">Featured</a>
                <a href="#categories" class="hover:text-brand transition-colors">Categories</a>
                <a href="{{ route('host.properties.index') }}" class="hover:text-brand transition-colors">Become a Host</a>
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <div class="flex items-center gap-3 px-4 py-2 bg-gray-50 rounded-full border border-gray-100">
                        <div class="w-8 h-8 rounded-full bg-brand/10 flex items-center justify-center text-brand font-bold text-xs">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <span class="text-sm font-bold text-gray-700">{{ auth()->user()->name }}</span>
                    </div>
                @else
                    <a href="/login" class="text-sm font-bold text-gray-600 hover:text-brand transition-colors">Login</a>
                    <a href="/register" class="btn-brand text-white px-6 py-3 rounded-full text-sm font-bold shadow-lg shadow-brand/20">Sign up</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="relative h-[85vh] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0">
            <img src="/hero_villa.png" class="w-full h-full object-cover" alt="Hero Background">
            <div class="hero-gradient absolute inset-0"></div>
        </div>
        <div class="relative z-10 max-w-5xl mx-auto px-6 text-center text-white">
            <div class="animate-fadeUp opacity-0" style="animation-fill-mode: forwards;">
                <span class="inline-block bg-white/10 backdrop-blur-md px-4 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase mb-8 border border-white/20">
                    Trusted by 2M+ travellers
                </span>
            </div>
            <h1 class="text-6xl md:text-8xl font-black mb-8 leading-[1.1] animate-fadeUp opacity-0" style="animation-delay: 0.1s; animation-fill-mode: forwards;">
                Find your perfect <br/><span class="text-brand">getaway</span>
            </h1>
            <p class="text-lg md:text-xl text-white/80 max-w-2xl mx-auto mb-12 animate-fadeUp opacity-0" style="animation-delay: 0.2s; animation-fill-mode: forwards;">
                Handpicked homes, villas, and stays for your next adventure.
            </p>

            <div class="max-w-4xl mx-auto bg-white rounded-3xl p-3 shadow-2xl flex flex-col md:flex-row gap-2 animate-fadeUp opacity-0" style="animation-delay: 0.3s; animation-fill-mode: forwards;">
                <div class="flex-1 flex items-center gap-4 px-6 py-4 border-r border-gray-100">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <div class="text-left">
                        <label class="block text-[10px] font-black text-gray-400 uppercase">Location</label>
                        <input type="text" placeholder="Where next?" class="w-full text-gray-900 font-bold focus:outline-none placeholder-gray-300">
                    </div>
                </div>
                <div class="flex-1 flex items-center gap-4 px-6 py-4 border-r border-gray-100">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <div class="text-left">
                        <label class="block text-[10px] font-black text-gray-400 uppercase">Check-in</label>
                        <input type="text" placeholder="Add dates" class="w-full text-gray-900 font-bold focus:outline-none placeholder-gray-300">
                    </div>
                </div>
                <button class="btn-brand text-white font-black px-12 py-5 rounded-2xl text-lg flex items-center gap-2 group">
                    Search
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
            </div>
        </div>
    </section>

    <!-- CATEGORIES -->
    <div id="categories" class="bg-white border-b border-gray-100 sticky top-20 z-40">
        <div class="max-w-7xl mx-auto px-8 flex items-center justify-center gap-16 py-6 overflow-x-auto hide-scrollbar">
            @foreach($categories as $category)
                <a href="?type={{ $category->slug }}" class="flex flex-col items-center gap-2 group transition-all shrink-0 {{ request('type') == $category->slug ? 'active' : '' }}">
                    <div class="text-2xl {{ request('type') == $category->slug ? '' : 'grayscale' }} group-hover:grayscale-0 group-hover:scale-110 transition-all duration-300">
                        {{ $category->icon }}
                    </div>
                    <span class="text-[11px] font-black uppercase tracking-widest {{ request('type') == $category->slug ? 'text-black' : 'text-gray-400' }} group-hover:text-black">
                        {{ $category->name }}
                    </span>
                    <div class="h-0.5 {{ request('type') == $category->slug ? 'w-full' : 'w-0' }} bg-brand group-hover:w-full transition-all duration-300"></div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- FEATURED PROPERTIES -->
    <section id="featured" class="max-w-7xl mx-auto px-8 py-24">
        <div class="flex items-center justify-between mb-16">
            <div>
                <h2 class="text-4xl font-black mb-3">Featured <span class="text-brand">Stays</span></h2>
                <p class="text-gray-500 font-medium">Extraordinary properties verified for comfort and quality.</p>
            </div>
            <a href="#" class="text-sm font-black text-brand border-b-2 border-brand/20 hover:border-brand transition-all">View all listings</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            @forelse($featured as $property)
                <a href="{{ route('properties.show', $property->id) }}" class="group cursor-pointer block">
                    <div class="relative aspect-[4/5] rounded-[2.5rem] overflow-hidden bg-gray-100 mb-6 shadow-xl shadow-gray-200/50">
                        @if($property->primaryImage)
                            <img src="{{ $property->primaryImage->url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" alt="{{ $property->title }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-7xl bg-gray-50 uppercase font-black text-gray-100">{{ $property->type }}</div>
                        @endif
                        <div class="absolute top-6 left-6 flex gap-2">
                             <span class="bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-full text-[10px] font-black uppercase text-gray-900 tracking-wider">Verified</span>
                             @if($property->average_rating > 4.8)
                                <span class="bg-brand text-white px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider">Top rated</span>
                             @endif
                        </div>
                        <button class="absolute bottom-6 right-6 w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white border border-white/20 hover:bg-brand hover:border-brand transition-all">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.18L12 21z"/></svg>
                        </button>
                    </div>
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-black text-gray-900">{{ $property->city }}, {{ $property->country }}</h3>
                            <div class="flex items-center gap-1 font-black text-sm">
                                <svg class="w-4 h-4 text-brand" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                                {{ number_format($property->average_rating ?? 4.8, 1) }}
                            </div>
                        </div>
                        <p class="text-gray-400 font-medium mb-4">{{ $property->title }}</p>
                            <p class="text-2xl font-black text-gray-900 leading-none">
                                ${{ number_format($property->price_per_night, 0) }}<span class="text-gray-400 font-medium text-sm ml-1">/night</span>
                            </p>
                            <span class="text-sm font-black text-brand hover:translate-x-1 transition-transform">Details →</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-20 text-center border-2 border-dashed border-gray-100 rounded-[3rem]">
                   <p class="text-gray-300 font-black text-2xl uppercase tracking-widest">Finding some properties for you...</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- STATS -->
    <section class="bg-gray-50 py-24">
        <div class="max-w-7xl mx-auto px-8 grid grid-cols-2 md:grid-cols-4 gap-12">
            @foreach([['2M+', 'Happy Guests'], ['50K+', 'Stays'], ['120+', 'Countries'], ['4.9★', 'Top Rated']] as [$val, $label])
                <div class="text-center">
                    <p class="text-5xl font-black text-brand mb-2">{{ $val }}</p>
                    <p class="text-gray-400 text-xs font-black uppercase tracking-[0.2em]">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-gray-100 pt-24 pb-12">
        <div class="max-w-7xl mx-auto px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-16 mb-24">
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center gap-2 mb-8">
                        <div class="p-2 bg-brand rounded-xl">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                        </div>
                        <span class="text-lg font-bold tracking-tight">Stay<span class="text-brand">Nest</span></span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed mb-8">Book unique homes and experiences from verified hosts worldwide.</p>
                </div>
                <div>
                     <h4 class="font-black text-xs uppercase tracking-[0.2em] text-gray-900 mb-8">Quick Links</h4>
                     <ul class="flex flex-col gap-4 text-sm font-bold text-gray-400">
                        <li><a href="#" class="hover:text-brand transition-colors">Amazing Pools</a></li>
                        <li><a href="#" class="hover:text-brand transition-colors">Mountain Cabins</a></li>
                        <li><a href="#" class="hover:text-brand transition-colors">Beachfront Houses</a></li>
                     </ul>
                </div>
                <div>
                     <h4 class="font-black text-xs uppercase tracking-[0.2em] text-gray-900 mb-8">Support</h4>
                     <ul class="flex flex-col gap-4 text-sm font-bold text-gray-400">
                        <li><a href="#" class="hover:text-brand transition-colors">Help Centre</a></li>
                        <li><a href="#" class="hover:text-brand transition-colors">Cancellation</a></li>
                        <li><a href="#" class="hover:text-brand transition-colors">Safety Info</a></li>
                     </ul>
                </div>
                <div>
                     <h4 class="font-black text-xs uppercase tracking-[0.2em] text-gray-900 mb-8">Contact</h4>
                     <ul class="flex flex-col gap-4 text-sm font-bold text-gray-400">
                        <li>hello@staynest.com</li>
                        <li>+1 (800) STAY-NOW</li>
                     </ul>
                </div>
            </div>
            <div class="flex flex-col md:flex-row justify-between items-center gap-8 pt-8 border-t border-gray-50 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">
                <p>&copy; 2026 StayNest. Handcrafted with passion.</p>
                <div class="flex gap-8">
                    <a href="#" class="hover:text-brand transition-colors">Privacy</a>
                    <a href="#" class="hover:text-brand transition-colors">Terms</a>
                    <a href="#" class="hover:text-brand transition-colors">Cookies</a>
                </div>
            </div>
        </div>
    </footer>
@endsection

@push('styles')
<style>
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeUp {
        animation: fadeUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
</style>
@endpush
