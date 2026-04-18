@extends('web::components.layouts.web')

@section('title', 'StayNest — Find Your Perfect Stay')

@section('content')
  <!-- NAV -->
  <nav class="fixed top-0 inset-x-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
      <a href="{{ route('landing') }}" class="flex items-center gap-2 group">
        <svg class="w-8 h-8 text-brand group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
        <span class="text-xl font-bold text-gray-900 tracking-tight">Stay<span class="text-brand">Nest</span></span>
      </a>
      <div class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-600">
        <a href="#features" class="nav-link hover:text-brand transition-colors">Features</a>
        <a href="{{ route('properties.search') }}" class="nav-link hover:text-brand transition-colors">Properties</a>
        <a href="{{ route('host.properties.index') }}" class="nav-link hover:text-brand transition-colors">Become a Host</a>
      </div>
      <div class="flex items-center gap-3">
        @auth
          <div class="flex items-center gap-3 px-4 py-2 bg-gray-50 rounded-full border border-gray-100">
            <div class="w-7 h-7 rounded-full bg-brand/10 flex items-center justify-center text-brand font-bold text-xs">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <span class="text-xs font-bold text-gray-700 hidden sm:block">{{ auth()->user()->name }}</span>
          </div>
        @else
          <a href="/login" class="hidden sm:block text-sm font-medium text-gray-700 hover:text-brand transition-colors">Sign in</a>
          <a href="/register" class="btn-primary text-white text-sm font-semibold px-5 py-2.5 rounded-full shadow-lg shadow-brand/20">Get Started</a>
        @endauth
      </div>
    </div>
  </nav>

  <!-- HERO -->
  <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0">
      <img src="/hero_villa.png" alt="Luxury Villa" class="w-full h-full object-cover object-center" />
      <div class="hero-gradient absolute inset-0"></div>
    </div>
    <div class="relative z-10 max-w-5xl mx-auto px-4 text-center">
      <div class="opacity-0 animate-fadeUp">
        <span class="inline-block bg-white/20 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-widest px-4 py-1.5 rounded-full mb-6 border border-white/30">
          🌍 2,000+ properties worldwide
        </span>
      </div>
      <h1 class="text-5xl sm:text-7xl lg:text-8xl font-black text-white leading-[1.1] mb-6 opacity-0 animate-fadeUp" style="animation-delay:0.1s">
        Find your perfect<br><span class="text-rose-400">getaway</span>
      </h1>
      <p class="text-lg sm:text-xl text-white/80 max-w-2xl mx-auto mb-10 opacity-0 animate-fadeUp" style="animation-delay:0.2s">
        Discover handpicked homes, villas, and private stays. Book in minutes, enjoy forever.
      </p>

      <!-- SEARCH BAR -->
      <form action="{{ route('properties.search') }}" method="GET" class="opacity-0 animate-fadeUp bg-white rounded-3xl shadow-2xl p-2.5 flex flex-col sm:flex-row gap-2 max-w-4xl mx-auto" style="animation-delay:0.3s">
        <div class="flex items-center gap-3 flex-1 px-5 py-4 border border-gray-100/80 rounded-2xl bg-gray-50/50">
          <svg class="w-5 h-5 text-brand flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          <div class="text-left w-full">
            <label class="block text-[9px] font-black text-gray-400 uppercase tracking-tighter mb-0.5">Location</label>
            <input type="text" name="location" value="{{ request('location') }}" placeholder="Where are you going?" class="w-full text-gray-900 text-sm font-bold focus:outline-none bg-transparent placeholder-gray-400" />
          </div>
        </div>
        <div class="flex items-center gap-3 flex-1 px-5 py-4 border border-gray-100/80 rounded-2xl bg-gray-50/50">
          <svg class="w-5 h-5 text-brand flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          <div class="text-left w-full overflow-hidden">
            <label class="block text-[9px] font-black text-gray-400 uppercase tracking-tighter mb-0.5">Check-in — Check-out</label>
            <input type="text" name="dates" value="{{ request('dates') }}" placeholder="Select dates" class="datepicker-range w-full text-gray-900 text-sm font-bold focus:outline-none bg-transparent placeholder-gray-400" />
          </div>
        </div>
        <button type="submit" class="btn-primary text-white font-bold px-10 py-4 rounded-2xl flex items-center gap-2 justify-center transition-all">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          Search
        </button>
      </form>

      <div class="mt-10 flex items-center justify-center gap-8 text-white/70 text-xs font-bold uppercase tracking-widest opacity-0 animate-fadeUp" style="animation-delay:0.4s">
        <span>✨ No booking fees</span>
        <span class="w-1 h-1 bg-white/30 rounded-full"></span>
        <span>🔒 Secure payments</span>
        <span class="w-1 h-1 bg-white/30 rounded-full"></span>
        <span>⭐ Verified hosts</span>
      </div>
    </div>

    <!-- Scroll hint -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-float">
      <svg class="w-6 h-6 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </div>
  </section>

  <!-- CATEGORIES (STICKY) -->
  <div id="categories" class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-16 z-40 transition-all shadow-sm">
    <div class="max-w-7xl mx-auto px-4 flex items-center justify-center gap-12 sm:gap-16 py-5 overflow-x-auto hide-scrollbar">
      @foreach($categories as $category)
        <a href="?type={{ $category->slug }}" class="flex flex-col items-center gap-2 group transition-all shrink-0 {{ request('type') == $category->slug ? 'active' : '' }}">
          <div class="text-2xl {{ request('type') == $category->slug ? '' : 'filter grayscale opacity-40 group-hover:grayscale-0 group-hover:opacity-100' }} transition-all duration-300 transform group-hover:scale-110">
            {{ $category->icon }}
          </div>
          <span class="text-[10px] font-bold uppercase tracking-[0.15em] {{ request('type') == $category->slug ? 'text-black' : 'text-gray-400' }} group-hover:text-black transition-colors">
            {{ $category->name }}
          </span>
          <div class="h-0.5 {{ request('type') == $category->slug ? 'w-full' : 'w-0' }} bg-brand group-hover:w-full transition-all duration-300"></div>
        </a>
      @endforeach
    </div>
  </div>

  <!-- FEATURES -->
  <section id="features" class="py-24 bg-gray-50/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-20 animate-fadeUp opacity-0" style="animation-fill-mode: forwards;">
        <span class="text-brand font-bold text-xs uppercase tracking-[0.3em] mb-4 block">Our Advantage</span>
        <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">Why choose <span class="text-brand">StayNest</span>?</h2>
        <p class="text-gray-500 text-lg max-w-2xl mx-auto font-medium">Everything you need for the perfect stay, built into one seamless experience designed for modern travellers.</p>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">

        <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-500 group">
          <div class="feature-icon w-20 h-20 rounded-3xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
            <svg class="w-10 h-10 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
          </div>
          <h3 class="text-xl font-black text-gray-900 mb-4">Easy Booking</h3>
          <p class="text-gray-500 text-sm leading-relaxed font-medium">Book your dream stay in under 2 minutes with our streamlined and lightning-fast process.</p>
        </div>

        <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-500 group">
          <div class="feature-icon w-20 h-20 rounded-3xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
            <svg class="w-10 h-10 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
          </div>
          <h3 class="text-xl font-black text-gray-900 mb-4">Verified Rooms</h3>
          <p class="text-gray-500 text-sm leading-relaxed font-medium">Every property is manually inspected and verified by our team for quality and extreme accuracy.</p>
        </div>

        <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-500 group">
          <div class="feature-icon w-20 h-20 rounded-3xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
            <svg class="w-10 h-10 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
          </div>
          <h3 class="text-xl font-black text-gray-900 mb-4">Secure Payment</h3>
          <p class="text-gray-500 text-sm leading-relaxed font-medium">Advanced bank-grade encryption protects every single transaction. Pay with total confidence.</p>
        </div>

        <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-500 group">
          <div class="feature-icon w-20 h-20 rounded-3xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
            <svg class="w-10 h-10 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
          </div>
          <h3 class="text-xl font-black text-gray-900 mb-4">24/7 Support</h3>
          <p class="text-gray-500 text-sm leading-relaxed font-medium">Our global team is always here to help you, day or night, wherever your journey takes you.</p>
        </div>

      </div>
    </div>
  </section>

  <!-- PROPERTIES -->
  <section id="properties" class="py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-end justify-between mb-16">
        <div>
          <span class="text-brand font-bold text-xs uppercase tracking-[0.3em] mb-4 block">Hand-picked</span>
          <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">Featured <span class="text-brand">Stays</span></h2>
          <p class="text-gray-500 font-medium">Extraordinary properties loved and verified by our global community.</p>
        </div>
        <a href="#" class="hidden sm:flex items-center gap-2 text-brand font-bold text-sm tracking-tight hover:gap-4 transition-all group">
          View all listings <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-12">
        @forelse($featured as $property)
          <div class="card-hover bg-white rounded-[2.5rem] overflow-hidden shadow-xl shadow-gray-100/50 cursor-pointer group flex flex-col h-full border border-gray-50">
            <div class="relative overflow-hidden h-72">
              @if($property->primaryImage)
                <img src="{{ $property->primaryImage->url }}" alt="{{ $property->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" />
              @else
                <div class="w-full h-full bg-gray-100 flex items-center justify-center text-4xl font-black text-gray-200 uppercase">{{ $property->propertyType->name ?? 'Stay' }}</div>
              @endif
              
              <div class="absolute top-6 left-6 flex flex-col gap-2">
                <span class="bg-white/90 backdrop-blur-md text-gray-900 text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full shadow-sm">
                  {{ $property->propertyType->name ?? 'Entire Home' }}
                </span>
                @if($property->average_rating >= 4.9)
                  <span class="bg-brand text-white text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full shadow-lg shadow-brand/20">
                    🔥 Top Rated
                  </span>
                @endif
              </div>

              <button class="absolute top-6 right-6 w-10 h-10 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-sm text-white hover:bg-brand hover:text-white transition-all border border-white/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
              </button>
            </div>
            <div class="p-8 flex-1 flex flex-col">
              <div class="flex items-start justify-between mb-4">
                <div class="flex-1 pr-4">
                  <h3 class="font-black text-xl text-gray-900 group-hover:text-brand transition-colors line-clamp-1 mb-1">{{ $property->city }}, {{ $property->country }}</h3>
                  <p class="text-gray-400 text-sm font-medium line-clamp-1">{{ $property->title }}</p>
                </div>
                <div class="flex items-center gap-1.5 bg-amber-50 px-3 py-1.5 rounded-xl text-xs font-black text-amber-600">
                  <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                  {{ number_format($property->average_rating ?? 4.8, 1) }}
                </div>
              </div>
              <div class="border-t border-gray-50 pt-6 mt-auto flex items-center justify-between">
                <div>
                  <span class="text-3xl font-black text-gray-900 tracking-tighter">${{ number_format($property->price_per_night) }}</span>
                  <span class="text-gray-400 text-xs font-bold uppercase tracking-widest ml-1">/ night</span>
                </div>
                <a href="{{ route('properties.show', $property->slug) }}" class="btn-primary text-white text-xs font-black px-6 py-3 rounded-2xl shadow-md">Book Now</a>
              </div>
            </div>
          </div>
        @empty
          <div class="col-span-full py-32 text-center bg-gray-50 rounded-[3rem] border-2 border-dashed border-gray-200">
            <div class="text-4xl mb-4">🏠</div>
            <p class="text-gray-400 font-black text-xl uppercase tracking-widest">No properties found match your search.</p>
          </div>
        @endforelse
      </div>
    </div>
  </section>

  <!-- STATS -->
  <section class="py-20 bg-brand/5 border-y border-brand/5">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-12 text-center">
      <div>
        <p class="text-5xl font-black text-brand mb-2 tracking-tighter">2M+</p>
        <p class="text-gray-400 font-bold text-[10px] uppercase tracking-[0.3em]">Happy guests</p>
      </div>
      <div>
        <p class="text-5xl font-black text-brand mb-2 tracking-tighter">50K+</p>
        <p class="text-gray-400 font-bold text-[10px] uppercase tracking-[0.3em]">Properties</p>
      </div>
      <div>
        <p class="text-5xl font-black text-brand mb-2 tracking-tighter">120+</p>
        <p class="text-gray-400 font-bold text-[10px] uppercase tracking-[0.3em]">Countries</p>
      </div>
      <div>
        <p class="text-5xl font-black text-brand mb-2 tracking-tighter">4.9★</p>
        <p class="text-gray-400 font-bold text-[10px] uppercase tracking-[0.3em]">Avg. rating</p>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section id="cta" class="py-32 bg-white relative overflow-hidden">
    <!-- Subtle background elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-brand/5 rounded-full filter blur-3xl -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-rose-100/50 rounded-full filter blur-3xl translate-y-1/2 -translate-x-1/2"></div>
    
    <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
      <span class="inline-block bg-rose-100 text-brand text-xs font-black uppercase tracking-[0.3em] px-5 py-2 rounded-full mb-8">Ready to explore?</span>
      <h2 class="text-5xl md:text-6xl font-black text-gray-900 mb-8 leading-[1.1]">Start your next adventure<br><span class="text-brand">today</span></h2>
      <p class="text-gray-500 text-lg max-w-xl mx-auto mb-12 font-medium">Join millions of travellers who found their perfect stay with StayNest. No hidden fees. No surprises. Just memories.</p>
      <div class="flex flex-col sm:flex-row gap-5 justify-center">
        <a href="/register" class="btn-primary text-white font-black text-lg px-12 py-5 rounded-[2rem] inline-flex items-center gap-2 shadow-2xl shadow-brand/40">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          Start Booking Now
        </a>
        <a href="{{ route('host.properties.index') }}" class="bg-white border-2 border-gray-100 text-gray-700 hover:border-brand hover:text-brand font-black text-lg px-12 py-5 rounded-[2rem] transition-all inline-flex items-center gap-2">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
          Become a Host
        </a>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="bg-gray-900 text-gray-400 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-16 mb-20">
        <div class="col-span-1 md:col-span-1">
          <div class="flex items-center gap-2 mb-8">
            <svg class="w-8 h-8 text-brand" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
            <span class="text-white font-black text-2xl tracking-tighter">Stay<span class="text-brand">Nest</span></span>
          </div>
          <p class="text-sm leading-relaxed mb-8 font-medium">Discover the world one stay at a time. Book unique homes with verified hosts and bank-grade security.</p>
          <div class="flex gap-4">
            @foreach(['twitter', 'instagram', 'facebook'] as $social)
              <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-brand rounded-xl flex items-center justify-center transition-all group">
                <svg class="w-5 h-5 text-white group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                  @if($social == 'twitter') <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/>
                  @elseif($social == 'instagram') <rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke-width="2" stroke="currentColor" fill="none"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01"/>
                  @else <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/> @endif
                </svg>
              </a>
            @endforeach
          </div>
        </div>

        <div>
          <h4 class="text-white font-black text-xs uppercase tracking-[0.3em] mb-8">Explore</h4>
          <ul class="space-y-4 text-xs font-bold uppercase tracking-widest text-gray-500">
            <li><a href="#" class="hover:text-brand transition-colors">Amazing Pools</a></li>
            <li><a href="#" class="hover:text-brand transition-colors">Mountain Cabins</a></li>
            <li><a href="#" class="hover:text-brand transition-colors">City Apartments</a></li>
            <li><a href="#" class="hover:text-brand transition-colors">Beach Houses</a></li>
          </ul>
        </div>

        <div>
          <h4 class="text-white font-black text-xs uppercase tracking-[0.3em] mb-8">Support</h4>
          <ul class="space-y-4 text-xs font-bold uppercase tracking-widest text-gray-500">
            <li><a href="#" class="hover:text-brand transition-colors">Help Centre</a></li>
            <li><a href="#" class="hover:text-brand transition-colors">Cancellation</a></li>
            <li><a href="#" class="hover:text-brand transition-colors">Safety Info</a></li>
            <li><a href="#" class="hover:text-brand transition-colors">Report Issue</a></li>
          </ul>
        </div>

        <div>
          <h4 class="text-white font-black text-xs uppercase tracking-[0.3em] mb-8">Contact</h4>
          <ul class="space-y-6 text-sm font-medium">
            <li class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center text-brand"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
              hello@staynest.com
            </li>
            <li class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center text-brand"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></div>
              +1 (800) STAY-NOW
            </li>
          </ul>
        </div>
      </div>
      <div class="border-t border-gray-800 pt-10 flex flex-col md:flex-row items-center justify-between gap-6 text-[10px] font-black uppercase tracking-[0.3em] text-gray-500">
        <p>&copy; 2026 StayNest. Handcrafted with passion.</p>
        <div class="flex gap-10">
          <a href="#" class="hover:text-brand transition-colors">Privacy</a>
          <a href="#" class="hover:text-brand transition-colors">Terms</a>
          <a href="#" class="hover:text-brand transition-colors">Cookies</a>
        </div>
      </div>
    </div>
  </footer>
@endsection
