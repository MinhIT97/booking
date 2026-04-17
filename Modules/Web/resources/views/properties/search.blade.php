@extends('web::components.layouts.web')

@section('title', 'Properties Search — StayNest')

@section('content')
  <!-- NAV -->
  <nav class="fixed top-0 inset-x-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
      <a href="{{ route('landing') }}" class="flex items-center gap-2 group">
        <svg class="w-8 h-8 text-brand group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
        <span class="text-xl font-bold text-gray-900 tracking-tight">Stay<span class="text-brand">Nest</span></span>
      </a>
      
      <!-- MINI SEARCH -->
      <form action="{{ route('properties.search') }}" method="GET" class="hidden md:flex items-center bg-white border border-gray-200 rounded-full shadow-sm pr-1 pl-6 py-1 gap-4 hover:shadow-md transition-all cursor-pointer">
        <div class="flex flex-col">
            <span class="text-[9px] font-black uppercase tracking-widest text-gray-400">Location</span>
            <input type="text" name="location" value="{{ request('location') }}" placeholder="Anywhere" class="text-xs font-bold text-gray-800 focus:outline-none bg-transparent w-40">
        </div>
        <div class="w-px h-8 bg-gray-100"></div>
        <div class="flex flex-col flex-1 min-w-0">
            <span class="text-[8px] font-black uppercase tracking-widest text-gray-400">Dates</span>
            <input type="text" name="dates" value="{{ request('dates') }}" placeholder="Any week" class="datepicker-range mini-picker text-[11px] font-bold text-gray-800 focus:outline-none bg-transparent w-full">
        </div>
        <button type="submit" class="w-10 h-10 bg-brand text-white rounded-full flex items-center justify-center hover:bg-brand-dark transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </button>
      </form>

      <div class="flex items-center gap-3">
        @auth
          <div class="flex items-center gap-3 px-4 py-2 bg-gray-50 rounded-full border border-gray-100">
            <div class="w-7 h-7 rounded-full bg-brand/10 flex items-center justify-center text-brand font-bold text-xs">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <span class="text-xs font-bold text-gray-700 hidden sm:block">{{ auth()->user()->name }}</span>
          </div>
        @else
          <a href="/login" class="text-sm font-medium text-gray-700 hover:text-brand transition-colors">Sign in</a>
          <a href="/register" class="btn-primary text-white text-sm font-semibold px-5 py-2.5 rounded-full shadow-lg shadow-brand/20">Get Started</a>
        @endauth
      </div>
    </div>
  </nav>

  <!-- CATEGORIES (STICKY BELOW NAV) -->
  <div class="bg-white border-b border-gray-100 sticky top-16 z-40 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 flex items-center justify-start sm:justify-center gap-10 sm:gap-16 py-4 overflow-x-auto hide-scrollbar">
      @foreach($categories as $category)
        <a href="{{ route('properties.search', ['type' => $category->slug, 'location' => request('location')]) }}" class="flex flex-col items-center gap-2 group transition-all shrink-0 {{ request('type') == $category->slug ? 'active' : '' }}">
          <div class="text-xl {{ request('type') == $category->slug ? '' : 'filter grayscale opacity-40 group-hover:grayscale-0 group-hover:opacity-100' }} transition-all duration-300 transform group-hover:scale-110">
            {{ $category->icon }}
          </div>
          <span class="text-[9px] font-bold uppercase tracking-[0.1em] {{ request('type') == $category->slug ? 'text-black' : 'text-gray-400' }} group-hover:text-black transition-colors">
            {{ $category->name }}
          </span>
          <div class="h-0.5 {{ request('type') == $category->slug ? 'w-full' : 'w-0' }} bg-brand group-hover:w-full transition-all duration-300"></div>
        </a>
      @endforeach
    </div>
  </div>

  <!-- SEARCH CONTENT -->
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-10 animate-fadeUp opacity-0" style="animation-fill-mode: forwards;">
        @if(request('location') || request('type'))
            <h1 class="text-2xl font-black text-gray-900 mb-2">
                @if($properties->total() > 0)
                    Showing {{ $properties->total() }} stays
                    @if(request('location')) in "{{ request('location') }}" @endif
                @else
                    No stays found
                    @if(request('location')) in "{{ request('location') }}" @endif
                @endif
            </h1>
        @else
            <h1 class="text-2xl font-black text-gray-900 mb-2">Explore all stays</h1>
        @endif
        <p class="text-gray-500 font-medium text-sm">Discover unique homes points for your next adventure.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($properties as $property)
          <div class="card-hover bg-white rounded-[2rem] overflow-hidden shadow-xl shadow-gray-100/50 cursor-pointer group flex flex-col h-full border border-gray-50 animate-fadeUp opacity-0" style="animation-delay: {{ $loop->index * 0.05 }}s; animation-fill-mode: forwards;">
            <div class="relative overflow-hidden h-64">
              @if($property->primaryImage)
                <img src="{{ $property->primaryImage->url }}" alt="{{ $property->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" />
              @else
                <div class="w-full h-full bg-gray-100 flex items-center justify-center text-3xl font-black text-gray-200 uppercase">{{ $property->propertyType->name ?? 'Stay' }}</div>
              @endif
              
              <div class="absolute top-4 left-4 flex flex-col gap-2">
                <span class="bg-white/90 backdrop-blur-md text-gray-900 text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full shadow-sm">
                  {{ $property->propertyType->name ?? 'Entire Home' }}
                </span>
              </div>

              <button class="absolute top-4 right-4 w-9 h-9 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-sm text-white hover:bg-brand hover:text-white transition-all border border-white/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
              </button>
            </div>
            <div class="p-6 flex-1 flex flex-col">
              <div class="flex items-start justify-between mb-4">
                <div class="flex-1 pr-4">
                  <h3 class="font-black text-lg text-gray-900 group-hover:text-brand transition-colors line-clamp-1 mb-1">{{ $property->city }}, {{ $property->country }}</h3>
                  <p class="text-gray-400 text-xs font-medium line-clamp-1">{{ $property->title }}</p>
                </div>
                <div class="flex items-center gap-1 bg-amber-50 px-2 py-1 rounded-lg text-[10px] font-black text-amber-600">
                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                  {{ number_format($property->average_rating ?? 4.8, 1) }}
                </div>
              </div>
              <div class="border-t border-gray-50 pt-4 mt-auto flex items-center justify-between">
                <div>
                  <span class="text-2xl font-black text-gray-900 tracking-tighter">${{ number_format($property->price_per_night) }}</span>
                  <span class="text-gray-400 text-[10px] font-bold uppercase tracking-widest ml-1">/ night</span>
                </div>
                <a href="{{ route('properties.show', $property->slug) }}" class="btn-primary text-white text-[10px] font-black px-4 py-2.5 rounded-xl shadow-md">Details</a>
              </div>
            </div>
          </div>
        @empty
          <div class="col-span-full py-32 text-center bg-gray-50 rounded-[3rem] border-2 border-dashed border-gray-200">
            <div class="text-5xl mb-6">🔍</div>
            <h2 class="text-2xl font-black text-gray-900 mb-2">No results found</h2>
            <p class="text-gray-400 font-medium max-w-sm mx-auto">We couldn't find any stays matching your search. Try adjusting your filters or searching a different area.</p>
            <a href="{{ route('landing') }}" class="btn-primary text-white font-black px-8 py-3 rounded-2xl mt-8 inline-block shadow-lg shadow-brand/20">Back to Home</a>
          </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    <div class="mt-20">
        {{ $properties->appends(request()->query())->links() }}
    </div>
  </main>

  <!-- FOOTER (SIMPLE) -->
  <footer class="bg-white border-t border-gray-100 py-10 mt-auto">
    <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">
      <div class="flex items-center gap-2">
        <svg class="w-5 h-5 text-brand" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
        <span>&copy; 2026 StayNest</span>
      </div>
      <div class="flex gap-8">
        <a href="#" class="hover:text-brand transition-colors">Privacy</a>
        <a href="#" class="hover:text-brand transition-colors">Terms</a>
        <a href="#" class="hover:text-brand transition-colors">Cookies</a>
      </div>
    </div>
  </footer>
@endsection
